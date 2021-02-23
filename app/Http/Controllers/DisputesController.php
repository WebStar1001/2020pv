<?php

namespace App\Http\Controllers;

use App\Dispute;
use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutResource;
use App\Http\Resources\UserResource;
use App\Payout;
use App\PayoutItem;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\Currency;
use PayPal\Api\Payout as PaypalPayout;
use PayPal\Api\PayoutItem as PaypalPayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class DisputesController extends Controller
{
	public function __construct()
	{

	}

	public function getDisputes(Request $request)
	{
		$disputes = Dispute::with(['user'])
		                   ->offset($request->offset)
		                   ->limit($request->limit)
		                   ->orderBy($request->sort, $request->order)
		                   ->get();



		$total = Dispute::count();

		return [
			'data' => new PayoutResource($disputes, Auth::user()),
			'total' => $total
		];
	}

	public function getDisputeDetails(Request $request, Dispute $dispute)
	{
		$client = new \GuzzleHttp\Client();

		$response = $client->post(env('PAYPAL_API_URL') . "/oauth2/token", [
			'headers' =>
				[
					'Accept' => 'application/json',
					'Accept-Language' => 'en_US',
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
			'body' => 'grant_type=client_credentials',
			'auth' => [env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'), 'basic']
		]);

		$token = json_decode($response->getBody()->getContents());

		$response = $client->get(env('PAYPAL_API_URL') . "/customer/disputes/$dispute->dispute_id", [
			'headers' =>
				[
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $token->access_token
				],
		]);

		$dispute_details = json_decode($response->getBody()->getContents());

		return response()->json($dispute_details);
	}

}
