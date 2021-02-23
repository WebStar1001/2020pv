<?php

namespace App\Jobs;

use App\Mail\EmailForQueuing;
use App\Mail\UnreadMessages;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Mail;

class SendEmailNotifications implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$users = User::where([
			'users.blocked' => 0,
			'users.deleted' => 0,
			'users.is_logged_in' => 0,
			'email_messages.read_status' => 0
		])->leftJoin('email_messages', 'users.id', '=', 'email_messages.receiver_id')
		             ->distinct('users.id')
		             ->groupBy('users.id')
		             ->select('users.id', 'users.email', DB::raw('count(email_messages.id) as count'))
		             ->get();


		foreach ($users as $user) {
			if (!$user->emailNotification) {
				Mail::to($user->email)->send(new UnreadMessages($user->count));

				$user->emailNotification()->create();
			}
		}
	}
}