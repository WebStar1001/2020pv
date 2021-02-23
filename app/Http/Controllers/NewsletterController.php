<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmails;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
	public function enqueue(Request $request)
	{
		$emails = User::where([
			'approved' => 1,
			'blocked' => 0,
			'deleted' => 0,
		])->where(function($query) use ($request) {
			if ($request->role_id) {
				$query->where('role_id', $request->role_id);
			} else {
				$query->where('role_id', Role::getCustomerId())
				      ->orWhere('role_id', Role::getAdvisorId());
			}
		})->pluck('email')->toArray();

		foreach (array_chunk($emails, 10) as $email_chunk) {
			SendEmails::dispatch( $email_chunk, $request->subject, $request->message )
			          ->onConnection( 'database' )
			          ->delay(now()->addMinutes(2));
		}
	}

	public function test(Request $request)
	{
		SendEmails::dispatch([$request->email], $request->subject, $request->message);
	}

	public function unsubscribe($hash)
	{
		try {
			User::where( [ 'email' => Crypt::decrypt( $hash ) ] )->update( [ 'subscribed' => 0 ] );
		} catch (\Exception $e) {
			dd('Invalid Link');
		}

		return view('emails.unsubscribe');
	}

}
