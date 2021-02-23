<?php

namespace App\Http\Controllers;

use App\Country;
use App\Horoscope;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteAccountRequest;
use App\Http\Requests\Admin\UpdateGeneralInformationRequest;
use App\Http\Resources\PendingPaymentEmailResource;
use App\Language;
use App\PendingPaymentEmail;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PendingPaymentEmailsController extends Controller
{
	public function __construct()
	{

	}

	public function getPendingPaymentEmails(Request $request)
	{
		$users = PendingPaymentEmail::where(['pending' => 1])
		                            ->with(['user'])
		                            ->offset($request->offset)
		                            ->limit($request->limit)
		                            ->orderBy($request->sort, $request->order)
		                            ->get();

		$total = PendingPaymentEmail::where(['pending' => 1])
		                            ->offset($request->offset)
		                            ->limit($request->limit)
		                            ->orderBy($request->sort, $request->order)
		                            ->count();

		return [
			'data' => new PendingPaymentEmailResource($users, auth()->user()),
			'total' => $total
		];
	}

	public function approvePaymentEmail(Request $request, User $user)
	{
		$pending_email = $user->pendingPaymentEmails()->where(['pending' => 1])->first();

		$user->update(['payment_email' => $pending_email->email]);

		$user->pendingPaymentEmails()->update([
			'pending' => 0
		]);
	}
}
