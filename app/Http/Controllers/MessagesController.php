<?php

namespace App\Http\Controllers;

use App\EmailMessage;
use App\EmailSubject;
use App\Events\BalanceUpdated;
use App\Events\EmailMessageSent;
use App\Events\InvoiceAccepted;
use App\Events\InvoiceCancelled;
use App\Events\InvoiceRejected;
use App\Events\UnreadMessagesUpdated;
use App\Http\Requests\Admin\ImageRequest;
use App\Http\Resources\EmailSubjectResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class MessagesController extends Controller
{
	public function getSubjects(Request $request)
	{
		$user = auth()->user();
		$subjects = [];

		if ($user->isCustomer() || $user->isAdvisor()) {
			$subjects = $user->emailSubjects()->with( 'customer', 'advisor', 'messages', 'unreadMessages' )
			                 ->orderBy( $request->sort, $request->order )
			                 ->get();
		}

		return response()->json([
			'subjects' => $subjects
		]);
	}

	public function getSubjectsAdvisors(  )
	{
		$user = auth()->user();

		$advisors = $user->emailSubjects()
		                 ->with('advisor')
		                 ->distinct('advisor_id')
		                 ->groupBy('advisor_id')
		                 ->select('advisor_id')
		                 ->get();

		$select_list = [];

		foreach ($advisors as $key => $advisor) {
			$select_list[$key]['slug'] = $advisor->advisor->slug;
			$select_list[$key]['display_name'] = $advisor->advisor->display_name;
			$select_list[$key]['avatar'] = $advisor->advisor->avatar;
		}

		return response()->json([
			'advisors' => $select_list
		]);
	}

	public function createNewSubject(Request $request, User $user)
	{
		$auth_user = auth()->user();

		$subject = $auth_user->emailSubjects()->create([
			'customer_id' => $auth_user->isCustomer() ? $auth_user->id : $user->id,
			'advisor_id' => $auth_user->isAdvisor() ? $auth_user->id : $user->id,
			'subject' => $request->subject
		]);

		$subject->messages()->create([
			'sender_id' => $auth_user->id,
			'receiver_id' => $user->id,
			'message' => $request->message
		]);

		broadcast( new UnreadMessagesUpdated($subject->id, $user->id, $user->getUnreadMessagesCount()) )->toOthers();

		return response()->json($subject->id);
	}

	public function getSubjectMessages(Request $request, EmailSubject $subject)
	{
		$auth_user = auth()->user();

		$customer = $subject->customer;
		$customer['status'] = $customer->status();
		$customer['master_service'] = $customer->masterService;

		$advisor = $subject->advisor;
		$advisor['status'] = $advisor->status();
		$advisor['master_service'] = $advisor->masterService;
		$advisor['rating'] = $advisor->rating();
		$advisor['reviews_total'] = $advisor->reviews()->count();
		$advisor['readings_count'] = $advisor->chatSessions()->count() + $advisor->readings;

		$first_message = EmailMessage::where(['sender_id' => $customer->id, 'receiver_id' => $advisor->id])
		                             ->orWhere(['sender_id' => $advisor->id, 'receiver_id' => $customer->id])
		                             ->first();

		$customer['since'] = $first_message->created_at->format('d M Y');
		$advisor['since'] = $first_message->created_at->format('d M Y');

		$last_message = EmailMessage::where( [ 'sender_id' => $auth_user->isCustomer() ? $advisor->id : $customer->id ] )
		                            ->orderBy( 'created_at', 'desc' )
		                            ->first();

		$customer['last_contacted'] = $last_message ? $last_message->created_at->format('d M Y') : null;
		$advisor['last_contacted'] = $last_message ? $last_message->created_at->format('d M Y') : null;

		return response()->json([
			'subject' => $subject->subject,
			'messages' => $subject->messages()->with('sender')->get(),
			'customer' => $customer,
			'advisor' => $advisor
		]);
	}

	public function sendMessage(Request $request, EmailSubject $subject)
	{
		$auth_user = auth()->user();

		if ( $auth_user->id === $subject->advisor_id || $auth_user->id === $subject->customer_id ) {
			$receiver = $auth_user->isCustomer() ? $subject->advisor : $subject->customer;

			$message = $subject->messages()->create( [
				'sender_id' => $auth_user->id,
				'receiver_id' => $receiver->id,
				'message' => $request->message
			] );

			broadcast( new EmailMessageSent( $subject->id, $auth_user, $message ) )->toOthers();
			broadcast( new UnreadMessagesUpdated($subject->id, $receiver->id, $receiver->getUnreadMessagesCount()) )->toOthers();
		}

		return response()->json(['status' => 'Message Sent!']);
	}

	public function sendInvoice(Request $request, EmailSubject $subject)
	{
		$auth_user = auth()->user();

		if ( $auth_user->id === $subject->advisor_id) {
			$receiver = $auth_user->isCustomer() ? $subject->advisor : $subject->customer;

			$message = $subject->messages()->create( [
				'sender_id' => $auth_user->id,
				'receiver_id' => $receiver->id,
				'message' => null,
				'invoice_amount' => $request->amount
			] );

			broadcast( new EmailMessageSent( $subject->id, $auth_user, $message ) )->toOthers();
			broadcast( new UnreadMessagesUpdated($subject->id, $receiver->id, $receiver->getUnreadMessagesCount()) )->toOthers();
		}

		return response()->json(['status' => 'Invoice Sent!']);
	}

	public function cancelInvoice(Request $request, EmailSubject $subject, EmailMessage $invoice)
	{
		$invoice->update(['invoice_cancelled' => 1]);

		broadcast( new InvoiceCancelled($subject->id, $invoice->id))->toOthers();
	}

	public function rejectInvoice(Request $request, EmailSubject $subject, EmailMessage $invoice)
	{
		$invoice->update(['invoice_rejected' => 1]);

		broadcast( new InvoiceRejected($subject->id, $invoice->id))->toOthers();
	}

	public function acceptInvoice(Request $request, EmailSubject $subject, EmailMessage $invoice)
	{
		$customer = $subject->customer;
		$advisor = $subject->advisor;

		if ($customer->balance >= $invoice->invoice_amount) {
			$customer->update(['balance' => $customer->balance - $invoice->invoice_amount]);

			broadcast(new BalanceUpdated($customer));

			$customer->payments()->firstOrCreate([
				'relationship_id' => $invoice->id,
				'type'            => constants( 'payments.email_chat_outcome' ),
			], [
				'type'            => constants( 'payments.email_chat_outcome' ),
				'amount'          => -$invoice->invoice_amount,
				'balance'         => $customer->balance
			]);

			$advisor_income = $invoice->invoice_amount / 100 * (100 - $advisor->commission_percentage);

			$advisor->update(['balance' => $advisor->balance + $advisor_income]);

			broadcast(new BalanceUpdated($advisor));

			$advisor->payments()->firstOrCreate([
				'relationship_id' => $invoice->id,
				'type'            => constants( 'payments.email_chat_income' ),
			], [
				'type'            => constants( 'payments.email_chat_income' ),
				'amount'          => $advisor_income,
				'balance'         => $advisor->balance
			]);

			$invoice->update(['invoice_accepted' => 1]);

			broadcast( new InvoiceAccepted($subject->id, $invoice->id))->toOthers();

			return response()->json(true);
		}

		return response()->json(false);
	}

	public function uploadFile(ImageRequest $request, EmailSubject $subject)
	{
		$path = '';

		if ($request->hasFile('file')) {
			$path = $request->file('file')->store('email_chat_attachments/' . $subject->id);
		}

		return response()->json($path);
	}

	public function removeFile(Request $request, EmailSubject $subject)
	{
		if ($request->file && Storage::exists($request->file)) {
			Storage::delete($request->file);
		}

		return response()->json(true);
	}

	public function storeReport(Request $request, EmailSubject $subject)
	{
		$subject->reports()->create([
			'email_subject_id' => $subject->id,
			'comment' => $request->comment,
		]);
	}

	public function updateReadStatus(Request $request, EmailSubject $subject)
	{
		$auth_user = auth()->user();

		$subject->messages()
		        ->where(['receiver_id' => $auth_user->id, 'read_status' => 0])
		        ->update(['read_status' => 1]);

		broadcast( new UnreadMessagesUpdated($subject->id, $auth_user->id, $auth_user->getUnreadMessagesCount()) );
	}

	public function getEmailSubjects(Request $request)
	{
		$subjects = EmailSubject::with(['customer', 'advisor'])
		                    ->join('users', function ($join) {
			                    $join->on('advisor_id', '=', 'users.id')
			                         ->orOn('customer_id', '=', 'users.id');
		                    })
		                    ->where(function($query) use ($request) {
			                    if ($request->search) {
				                    $query->where('email_subjects.id', $request->search)
				                          ->orWhere('users.display_name', 'LIKE', '%'.$request->search.'%');
			                    }
		                    })
		                    ->offset($request->offset)
		                    ->limit($request->limit)
		                    ->distinct('email_subjects.id')
		                    ->groupBy('email_subjects.id')
		                    ->orderBy($request->sort, $request->order)
		                    ->select('email_subjects.*')
		                    ->get();

		$total = EmailSubject::count();

		return [
			'data' => new EmailSubjectResource($subjects, auth()->user()),
			'total' => $total
		];
	}

	public function storeReview(Request $request, EmailSubject $subject)
	{
		$auth_user = auth()->user();

		$auth_user->reviews()->create([
			'email_subject_id' => $subject->id,
			'advisor_id' => $subject->advisor_id,
			'rating' => $request->rating,
			'text' => $request->text
		]);

		$subject->messages()->where(['id' => $request->message_id])->update(['review_left' => 1]);
	}

	public function downloadHistory(Request $request, EmailSubject $subject)
	{
		$messages = $subject->messages()->with(['sender'])->get();

		$pdf = PDF::loadView('pdf.messages', compact('messages', 'subject'));

		return $pdf->download('messages_history_' . $subject->id . '.pdf');
	}

}
