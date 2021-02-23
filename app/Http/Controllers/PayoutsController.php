<?php

namespace App\Http\Controllers;

use App\Events\BalanceUpdated;
use App\Http\Resources\PayoutResource;
use App\Http\Resources\UserResource;
use App\Payout;
use App\PayoutItem;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Currency;
use PayPal\Api\Payout as PaypalPayout;
use PayPal\Api\PayoutItem as PaypalPayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayoutsController extends Controller
{
	public function __construct()
	{

	}

	public function getPayouts(Request $request)
	{
		$auth_user = auth()->user();

		$payouts = $auth_user->payoutItems()
		                     ->offset($request->offset)
		                     ->limit($request->limit)
		                     ->orderBy($request->sort, $request->order)
		                     ->get();

		$total = $auth_user->payoutItems()->count();

		return [
			'data' => new PayoutResource($payouts, Auth::user()),
			'total' => $total
		];
	}

	public function getAdvisors(Request $request)
	{
		$advisors = User::leftJoin('payout_items', 'users.id', '=', 'payout_items.user_id')
		                ->with(['payoutItems'])
		                ->where([
							'role_id' => Role::getAdvisorId(),
							'approved' => 1,
							'blocked' => 0,
							'deleted' => 0,
							'payouts_enabled' => 1,
			                'paypal_payment' => $request->paypal_payment
						])
						->where(function($query) use ($request) {
							$query->where('display_name', 'LIKE', '%'.$request->search.'%')
							      ->orWhere('payment_email', 'LIKE', '%'.$request->search.'%')
							      ->orWhere('email', 'LIKE', '%'.$request->search.'%')
							      ->orWhere('users.id', $request->search)
							      ->orWhere('payout_items.transaction_id', $request->search);
						})
						->select('users.*')
		                ->offset($request->offset)
		                ->limit($request->limit)
		                ->distinct('users.id')
		                ->orderBy($request->sort, $request->order)
		                ->get();

		$total = User::with(['payoutItems'])
		                         ->where([
			                         'role_id' => Role::getAdvisorId(),
			                         'approved' => 1,
			                         'blocked' => 0,
			                         'deleted' => 0,
			                         'payouts_enabled' => 1
		                         ])
		                         ->where(function($query) use ($request) {
		                         	$query->where('display_name', 'LIKE', '%'.$request->search.'%')
			                                ->orWhere('payment_email', 'LIKE', '%'.$request->search.'%')
		                                    ->orWhere('email', 'LIKE', '%'.$request->search.'%');
		                         })
		                         ->count();

		return [
			'data' => new UserResource($advisors, Auth::user()),
			'total' => $total
		];
	}

	public function makePayouts(Request $request)
	{
		$paypal_payout = new PaypalPayout();

		$sender_batch_header = new PayoutSenderBatchHeader();
		$sender_batch_header->setSenderBatchId(uniqid());
		$sender_batch_header->setEmailSubject('Payment from gopsys.com');
		$paypal_payout->setSenderBatchHeader($sender_batch_header);

		foreach ($request->ids as $id) {
			$user = User::find($id);

			if ($user->paypal_payment) {
				$fee_amount = ($user->balance / 100) * 2;
				$payout_amount = $user->balance - $fee_amount;

				$currency = new Currency();
				$currency->setValue($payout_amount);
				$currency->setCurrency('USD');

				$payout_item = new PaypalPayoutItem();
				$payout_item->setRecipientType('EMAIL');
				$payout_item->setAmount($currency);
				$payout_item->setNote('Payment to '. $user->email .' from gopsys.com');
				$payout_item->setSenderItemId(uniqid());
				$payout_item->setReceiver($user->payment_email);

				$paypal_payout->addItem($payout_item);
			} else {
				$payout = Payout::create([
					'sender_batch_id' => 'BANK',
					'payout_batch_id' => 'BANK',
					'batch_status' => 'SUCCESS'
				]);

				$payout_item = $user->payoutItems()->create([
					'payout_id' => $payout->id,
					'payout_item_id' => 'BANK',
					'transaction_status' => 'SUCCESS',
					'amount' => $user->balance,
				]);

				$user->update(['balance' => 0]);

				$user->payments()->create( [
					'relationship_id' => -1,
					'type'            => constants( 'payments.monthly_withdrawal' ),
					'amount'          => -$payout_item->amount,
					'balance'         => $user->balance
				] );

				return response()->json(true);
			}
		}

		return $this->createPayout($paypal_payout);
	}

	static function createPayout($paypal_payout, $retry = false)
	{
		$paypal = new ApiContext(
			new OAuthTokenCredential(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
		);

		if (!env('APP_DEBUG')) {
			$paypal->setConfig(['mode' => 'live']);
		}

		$payout_response = $paypal_payout->create([], $paypal);

		$payout = Payout::create([
			'sender_batch_id' => $payout_response->batch_header->sender_batch_header->sender_batch_id,
			'payout_batch_id' => $payout_response->batch_header->payout_batch_id,
			'batch_status' => $payout_response->batch_header->batch_status
		]);

		$payout_details = PaypalPayout::get($payout->payout_batch_id, $paypal);

		foreach ($payout_details->items as $payout_item_details) {
			$user_email = explode(' ', $payout_item_details->payout_item->note)[2];
			$user = User::where(['email' => $user_email])->first();

			if ($user) {
				$payout_item = $user->payoutItems()->create([
					'payout_id' => $payout->id,
					'payout_item_id' => $payout_item_details->payout_item_id,
					'transaction_status' => $payout_item_details->transaction_status,
					'amount' => $payout_item_details->payout_item->amount->value,
				]);

				if (!$retry) {
					$fee_amount = ( $user->balance / 100 ) * 2;

					$user->update( [ 'balance' => $user->balance - $payout_item->amount - $fee_amount ] );

					broadcast( new BalanceUpdated( $user ) );

					if ( $user->isChatting() ) {
						$active_chat = $user->activeChatSession();

						if ( $active_chat ) {
							$active_chat->update( [ 'advisor_balance_before' => 0 ] );
						}
					}

					$user->payments()->create( [
						'relationship_id' => $payout_item->id,
						'type'            => constants( 'payments.monthly_withdrawal' ),
						'amount'          => - ( $payout_item->amount + $fee_amount ),
						'balance'         => $user->balance
					] );
				}
			}
		}

		return $payout;
	}

	public function getPayoutItemDetails(PayoutItem $payout_item, Request $request)
	{
		$paypal = new ApiContext(
			new OAuthTokenCredential(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
		);

		if (!env('APP_DEBUG')) {
			$paypal->setConfig(['mode' => 'live']);
		}

		$payout_item_details = PaypalPayoutItem::get($payout_item->payout_item_id, $paypal);

		if ($payout_item_details->transaction_status !== $payout_item->transaction_status) {
			$payout_item->update([
				'transaction_status' => $payout_item_details->transaction_status
			]);
		}

		return $payout_item_details;
	}

	public function cancelUnclaimedPayout(PayoutItem $payout_item, Request $request)
	{
		$paypal = new ApiContext(
			new OAuthTokenCredential(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
		);

		if (!env('APP_DEBUG')) {
			$paypal->setConfig(['mode' => 'live']);
		}

		$payout_item_details = PaypalPayoutItem::cancel($payout_item->payout_item_id, $paypal);

		$payout_item->update([
			'transaction_status' => $payout_item_details->transaction_status
		]);

		$fee_amount = ($payout_item->amount / 98) * 2;

		$advisor = $payout_item->user;

		$advisor->update([
			'balance' => $payout_item->user->balance + $payout_item->amount + $fee_amount
		]);

		broadcast(new BalanceUpdated($advisor));

		$advisor->payments()->create([
			'relationship_id' => $payout_item->id,
			'type'            => constants('payments.unclaimed_payout'),
			'amount'          => $payout_item->amount + $fee_amount,
			'balance'         => $advisor->balance
		]);
	}

	public function retryFailedPayout(PayoutItem $payout_item, Request $request)
	{
		$paypal_payout = new PaypalPayout();

		$sender_batch_header = new PayoutSenderBatchHeader();
		$sender_batch_header->setSenderBatchId(uniqid());
		$sender_batch_header->setEmailSubject('Payment from gopsys.com');
		$paypal_payout->setSenderBatchHeader($sender_batch_header);

		$user = $payout_item->user;

		if ($user) {
			$currency = new Currency();
			$currency->setValue($payout_item->amount);
			$currency->setCurrency('USD');

			$payout_item = new PaypalPayoutItem();
			$payout_item->setRecipientType('EMAIL');
			$payout_item->setAmount($currency);
			$payout_item->setNote('Payment to '. $user->email .' from gopsys.com');
			$payout_item->setSenderItemId(uniqid());
			$payout_item->setReceiver($user->payment_email);

			$paypal_payout->addItem($payout_item);
		}

		return $this->createPayout($paypal_payout, true);
	}


}
