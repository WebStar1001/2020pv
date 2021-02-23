<?php

namespace App\Http\Controllers;

use App\Dispute;
use App\Http\Controllers\Controller;
use App\Payout;
use App\PayoutItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhooksController extends Controller
{
	public function __construct()
	{

	}

	public function payouts(Request $request)
	{
		if ($request['resource_type'] === 'payouts_item') {
			$payout_item = PayoutItem::where([
				'payout_item_id' => $request['resource']['payout_item_id']
			])->first();

			if ($payout_item && $payout_item->transaction_status !== 'SUCCESS') {
				if (isset($request['resource']['transaction_id'])) {
					$payout_item->update( [
						'transaction_id'     => $request['resource']['transaction_id'],
						'transaction_status' => $request['resource']['transaction_status']
					] );
				} else {
					$payout_item->update( [
						'transaction_status' => $request['resource']['transaction_status']
					] );
				}
			}
		}

		if ($request['resource_type'] === 'payouts') {
			$payout = Payout::where([
				'payout_batch_id' => $request['resource']['batch_header']['payout_batch_id']
			])->first();

			if ($payout && $payout->batch_status !== 'SUCCESS') {
				$payout->update([
					'batch_status' => $request['resource']['batch_header']['batch_status']
				]);
			}
		}
	}

	public function disputes(Request $request)
	{
		if ($request['resource_type'] === 'dispute') {
			$payout_item = PayoutItem::where([
				'transaction_id' => $request['disputed_transactions'][0]['seller_transaction_id']
			])->first();

			$user_id = $payout_item ? $payout_item->user->id : null;

			Dispute::updateOrCreate(
				['dispute_id' => $request['resource']['dispute_id']],
				[
					'user_id' => $user_id,
					'reason' => $request['resource']['reason'],
					'status' => $request['resource']['status'],
					'dispute_amount' => $request['resource']['dispute_amount']['value']
				]
			);
		}
	}

}
