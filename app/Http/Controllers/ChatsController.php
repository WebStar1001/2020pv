<?php

namespace App\Http\Controllers;

use App\ChatSession;
use App\Discount;
use App\Events\CallAccepted;
use App\Events\CallCancelled;
use App\Events\CallIncoming;
use App\Events\AdvisorIsBusy;
use App\Events\CustomerIsOutOfMoney;
use App\Events\DiscountUsed;
use App\Events\PaymentCancelled;
use App\Http\Requests\Admin\ImageRequest;
use App\Http\Resources\ChatSessionResource;
use App\Events\MessageSent;
use App\Http\Resources\ReportResource;
use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDF;

class ChatsController extends Controller
{
	private $payment;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(PaymentController $payment)
	{
		$this->middleware('auth');
		$this->payment = $payment;
	}

	public function startChat(Request $request, User $advisor)
	{
		$customer = auth()->user();

		$expiresAt = Carbon::now()->addHours(24);
		Cache::put('user-is-busy-' . $customer->id, $advisor->id, $expiresAt);
		Cache::put('user-is-busy-' . $advisor->id, $customer->id, $expiresAt);

		if ($customer->isCustomer()) {
			if ($advisor->isChatting()) {
				broadcast(new AdvisorIsBusy($customer, $advisor));
			} else {
				$advisor->missedChats()->create(['customer_id' => $customer->id]);

				broadcast(new CallIncoming($customer, $advisor, $request->countdown_seconds))->toOthers();
			}
		}
	}

	public function endChat(Request $request, ChatSession $chat_session)
	{
		$chat_session->end($request->reason);
	}

	public function acceptCall(Request $request)
	{
		$customer = User::findOrFail($request->customer_id);
		$advisor = auth()->user();
		$discount = null;

		if ($advisor->isAdvisor()) {
			if ($customer->created_at->diffInDays(now()) <= 14) {
				$discount = Discount::where(['for_new_users' => 1])->latest()->first();
			} else if ($customer->created_at->diffInDays(now()) > 14) {
				$discount = Discount::where(['for_new_users' => 0])->latest()->first();
			}

			if ($discount && $discount->active) {
				$discount_history = $customer->discountHistory()->where([
					'discount_id' => $discount->id,
					'advisor_id' => $advisor->id
				])->first();

				if ($discount_history) {
					$discount = null;
				}
			}

			$price_per_minute = $discount && $discount->active ? $advisor->price_per_minute - ($advisor->price_per_minute / 100) * $discount->discount : $advisor->price_per_minute;

			$chat_session = ChatSession::create([
				'customer_id'      => $customer->id,
				'advisor_id'       => $advisor->id,
				'price_per_minute' => $price_per_minute,
				'commission_percentage' => $advisor->commission_percentage,
				'discount' => isset($discount) && $discount && $discount->active ? $discount->discount : 0,
				'customer_balance_before' => $customer->balance,
				'advisor_balance_before' => $advisor->balance
			]);

			if ($discount && $discount->active) {
				$customer->discountHistory()->create([
					'discount_id' => $discount->id,
					'advisor_id' => $advisor->id,
					'chat_session_id' => $chat_session->id
				]);

				broadcast( new DiscountUsed($customer->id, $advisor->id));
			}

			if ($advisor->free_minutes_enabled && $customer->created_at->diffInDays(now()) <= 14) {
				$customer->freeMinutes()->firstOrCreate(
					[
						'advisor_id'   => $advisor->id
					],
					[
						'advisor_id' => $advisor->id,
						'free_seconds' => 180 // 180
					]);
			}

			$advisor->missedChats()->latest()->first()->delete();

			Cache::put('chat_session_id_' . $chat_session->id, true, env('SESSION_LIFETIME', 120));

			broadcast(new CallAccepted($customer, $advisor, $chat_session->id));

			return response()->json($chat_session->id);
		}

		return null;
	}

	public function cancelCall(Request $request)
	{
		$customer = User::findOrFail($request->customer_id);
		$advisor = User::findOrFail($request->advisor_id);

		Cache::forget('user-is-busy-' . $customer->id);
		Cache::forget('user-is-busy-' . $advisor->id);

		if ($request->advisor_not_answering) {
			$expiresAt = Carbon::now()->addHours( 24 );
			Cache::put( 'user-is-offline-' . $advisor->id, true, $expiresAt );

			$advisor->update(['is_logged_in' => 0]);
		}

		$advisor->missedChats()->latest()->first()->update(['cancelled' => 1]);

		broadcast(new CallCancelled($customer, $advisor, $request->advisor_not_answering))->toOthers();
	}

	public function advisorIsBusy(Request $request)
	{
		$customer = User::findOrFail($request->customer_id);
		$advisor = User::findOrFail($request->advisor_id);

		Cache::forget('user-is-busy-' . $customer->id);

		broadcast(new AdvisorIsBusy($customer, $advisor));
	}

	public function getData(ChatSession $chat_session, Request $request)
	{
		$auth_user = auth()->user();
		$messages = [];

		$freeSecondsRemaining = $chat_session->getRemainingFreeSeconds();
		$paidSecondsRemaining = $chat_session->getRemainingPaidSeconds();
		$isPaused = $chat_session->isPaused();

		if ($auth_user->isSuperAdmin() || $auth_user->isAdmin() || $auth_user->id === $chat_session->customer_id || $auth_user->id === $chat_session->advisor_id) {
			if (!$chat_session->deleted || $auth_user->isSuperAdmin() || $auth_user->isAdmin()) {
				$messages = $chat_session->messages()->with( 'user' )->get();
			}
		}

		if ($chat_session->isActive()) {
			if ($isPaused && $request->initial) {
				if ( $auth_user->isCustomer() ) {
					$pause = $chat_session->pauses()
					                      ->where( [ 'reason' => constants( 'chat_pauses.adding_funds' ) ] )
					                      ->whereNull( 'ended_at' )
					                      ->first();

					// end "adding_funds" pause manually if payment redirect wasn't called
					if ( $pause ) {
						$pause->update( [ 'ended_at' => now() ] );

						broadcast( new PaymentCancelled( $auth_user ) );

						// create "insufficient_funds" pause if payment was cancelled and no seconds left
						if ( ! $freeSecondsRemaining && ! $paidSecondsRemaining && !$chat_session->isPaused() ) {
							$chat_session->pauses()->create( [
								'reason' => constants( 'chat_pauses.insufficient_funds' )
							] );
						}
					}
				}
			} else {
//				if (!$freeSecondsRemaining && ! $paidSecondsRemaining ) {
//					$chat_session->pauses()
//					             ->where(['reason' => constants('chat_pauses.insufficient_funds')])
//					             ->whereNull('ended_at')
//					             ->firstOrCreate(['reason' => constants('chat_pauses.insufficient_funds')]);
//
//					$isPaused = true;
//				}
			}
		}

		$advisor = $chat_session->advisor;
		$advisor['master_service'] = $advisor->masterService;
		$advisor->status = $advisor->status();

		return response()->json([
			'messages' => $messages,
			'paidSeconds' => $paidSecondsRemaining,
			'freeSeconds' => $freeSecondsRemaining,
			'duration' => $chat_session->getDuration(),
			'activeSession' => $chat_session->isActive(),
			'sessionCreatedAt' => $chat_session->created_at,
			'advisor' => $chat_session->advisor,
			'customer' => $chat_session->customer,
			'price_per_minute' => $chat_session->price_per_minute,
			'chatStarted' => $chat_session->isTextExchanged() && !$isPaused,
			'chatPaused' => $isPaused,
			'chatDeleted' => $chat_session->deleted === 1,
			'pauseReason' => $chat_session->pauseReason(),
			'discount' => $chat_session->discount
		]);
	}

	/**
	 * Store message to database
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function sendMessage(Request $request, ChatSession $chat_session)
	{
		if ($chat_session->isActive()) {
			$auth_user = auth()->user();

			if ( $auth_user->id === $chat_session->customer_id || $auth_user->id === $chat_session->advisor_id ) {
				$message = $chat_session->messages()->create( [
					'user_id' => $auth_user->id,
					'chat_id' => $auth_user->isCustomer() ? $chat_session->advisor_id : $chat_session->customer_id,
					'message' => $request->message
				] );

				broadcast( new MessageSent( $chat_session->id, $auth_user, $message ) )->toOthers();

				if ( ! $chat_session->started_at ) {
					$chat_session->isTextExchanged();
				}
			}

			if (!$chat_session->isPaused() && $chat_session->getRemainingFreeSeconds() + $chat_session->getRemainingPaidSeconds() <= 0) {
				return response()->json( [ 'status' => 'error' ] );
			}

			return response()->json(['status' => 'Message Sent!']);
		}

		return response()->json( [ 'status' => 'error' ] );
	}

	public function uploadFile(ImageRequest $request, ChatSession $chat_session)
	{
		$path = '';

		if ($request->hasFile('file')) {
			$path = $request->file('file')->store('chat_attachments/' . $chat_session->id);
		}

		return response()->json($path);
	}

	public function removeFile(Request $request, ChatSession $chat_session)
	{
		if ($request->file && Storage::exists($request->file)) {
			Storage::delete($request->file);
		}

		return response()->json(true);
	}

	public function readingHistory(Request $request, User $user = null)
	{
		$auth_user = auth()->user();

		$from_date = $request->fromDate ? Carbon::createFromFormat('m/d/Y', $request->fromDate)->startOfDay() : '';
		$to_date = $request->toDate ? Carbon::createFromFormat('m/d/Y', $request->toDate)->endOfDay() : '';

		$chats = $auth_user->chatSessions()
		                   ->with(['customer', 'advisor', 'review', 'payments'])
		                   ->when($user, function($query) use ($user) {
			                   if ($user) {
				                   if ($user->isAdvisor()) {
					                   $query->where('advisor_id', $user->id);
				                   } else {
					                   $query->where('customer_id', $user->id);
				                   }
			                   }
		                   })
		                   ->where(['is_ended' => 1])
							->when($from_date, function($query) use ($from_date) {
								if ($from_date) {
									$query->where('ended_at', '>', $from_date);
								}
							})
							->when($to_date, function($query) use ($to_date) {
								if ($to_date) {
									$query->where('ended_at', '<', $to_date);
								}
							})
		                   ->offset($request->offset)
		                   ->limit($request->limit)
		                   ->orderBy($request->sort, $request->order)
		                   ->get();

		$total = $auth_user->chatSessions()
							->when($user, function($query) use ($user) {
								if ($user) {
									if ($user->isAdvisor()) {
										$query->where('advisor_id', $user->id);
									} else {
										$query->where('customer_id', $user->id);
									}
								}
							})
		                   ->where(['is_ended' => 1])
		                   ->when($from_date, function($query) use ($from_date) {
			                   if ($from_date) {
				                   $query->where('ended_at', '>', $from_date);
			                   }
		                   })
		                   ->when($to_date, function($query) use ($to_date) {
			                   if ($to_date) {
				                   $query->where('ended_at', '<', $to_date);
			                   }
		                   })
		                   ->count();

		foreach ($chats as $key => $chat) {
			$chats[$key]->display_name = $auth_user->isCustomer() ? $chat->advisor->display_name : $chat->customer->display_name;
			$chats[$key]->amount = $this->getUserAmountEarnedOrSpent($chat, $auth_user);
			$chats[$key]->moneyback_amount = $chat->moneybackAmount();
		}

		return [
			'data' => new ChatSessionResource($chats, Auth::user()),
			'total' => $total
		];
	}

	public function storeReview(Request $request, ChatSession $chat_session)
	{
		$auth_user = auth()->user();

		$auth_user->reviews()->create([
			'session_id' => $chat_session->id,
			'advisor_id' => $chat_session->advisor_id,
			'rating' => $request->rating,
			'text' => $request->text
		]);

		$advisor = User::find($chat_session->advisor_id);

		$advisor->update(['rating' => $advisor->rating()]);
	}

	public function storeReport(Request $request, ChatSession $chat_session)
	{
		$chat_session->reports()->create([
			'chat_session_id' => $chat_session->id,
			'comment' => $request->comment,
		]);
	}

	public function getReports(Request $request)
	{
		$reports = Report::with(['chatSession', 'emailSubject'])
		                   ->offset($request->offset)
		                   ->limit($request->limit)
		                   ->orderBy($request->sort, $request->order)
		                   ->get();

		$total = Report::count();

		return [
			'data' => new ReportResource($reports, Auth::user()),
			'total' => $total
		];
	}

	public function outOfMoney(Request $request, ChatSession $chat_session)
	{
		if ($chat_session->isActive() && !$chat_session->isPaused()) {
			$chat_session->pauses()
			             ->where( [ 'reason' => constants( 'chat_pauses.insufficient_funds' ) ] )
			             ->whereNull( 'ended_at' )
			             ->firstOrCreate( [ 'reason' => constants( 'chat_pauses.insufficient_funds' ) ] );

			broadcast( new CustomerIsOutOfMoney( $chat_session, $chat_session->customer ) );
		}

//		if (!$pause) {
//			Log::info('create');
//			$chat_session->pauses()->create([
//				'reason' => constants('chat_pauses.insufficient_funds')
//			]);
//
////			broadcast(new CustomerIsOutOfMoney($chat_session, $chat_session->customer));
//		} else {
//			// fix if pause was created earlier than it should be
//			$pause->update(['created_at' => now()]);
//		}
	}

	public function advisorIsInactive(Request $request, ChatSession $chat_session)
	{
		if ($request->wait) {
			// end "advisor_is_inactive" pause because customer wants to wait
			$chat_session->pauses()
			             ->where(['reason' => constants('chat_pauses.advisor_is_inactive')])
			             ->whereNull('ended_at')
			             ->update(['ended_at' => now()]);
		} else {
			if (!$chat_session->isPaused()) {
				$chat_session->pauses()->create( [
					'reason' => constants( 'chat_pauses.advisor_is_inactive' )
				] );
			}
		}
	}

	private function getUserAmountEarnedOrSpent(ChatSession $chat_session, User $user)
	{
		$moneyback_amount = $chat_session->moneybackAmount();

		if ($user->isCustomer()) {
			$customer_spent = $chat_session->customer_balance_after - $chat_session->customer_balance_before + $moneyback_amount;

			return round($customer_spent, 2) === 0.00 ? 0 : $customer_spent;
		} else {
			$advisor_earned = $chat_session->advisor_balance_after - $chat_session->advisor_balance_before + $moneyback_amount;

			return round($advisor_earned, 2) === 0.00 ? 0 : $advisor_earned;
		}
	}

	public function getChatSessions(Request $request)
	{
		$chat_sessions = ChatSession::with(['customer', 'advisor'])
							->join('users', function ($join) {
								$join->on('advisor_id', '=', 'users.id')
								     ->orOn('customer_id', '=', 'users.id');
							})
		                    ->where(function($query) use ($request) {
		                    	if ($request->search) {
				                    $query->where('chat_sessions.id', $request->search)
				                          ->orWhere('users.display_name', 'LIKE', '%'.$request->search.'%');
			                    }
		                    })
		                    ->offset($request->offset)
		                    ->limit($request->limit)
		                    ->distinct('chat_sessions.id')
		                    ->groupBy('chat_sessions.id')
		                    ->orderBy($request->sort, $request->order)
							->select('chat_sessions.*')
		                    ->get();

		$total = ChatSession::count();

		return [
			'data' => new ChatSessionResource($chat_sessions, Auth::user()),
			'total' => $total
		];
	}

	public function deleteReadingHistory(Request $request, ChatSession $chat_session)
	{
		$chat_session->update(['deleted' => 1]);
	}

	public function downloadHistory(Request $request, ChatSession $chat_session)
	{
		$messages = $chat_session->messages()->with('user')->get();

		$pdf = PDF::loadView('pdf.chat', compact('messages', 'chat_session'));

		return $pdf->download('chat_history_' . $chat_session->id . '.pdf');
	}

}
