<?php

namespace App\Jobs;

use App\Mail\EmailForQueuing;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Mail;

class SendEmails implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $emails;
	public $subject;
	public $message;

	public $tries = 1;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($emails, $subject, $message)
	{
		$this->emails = $emails;
		$this->subject = $subject;
		$this->message = $message;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		foreach ($this->emails as $email) {
			$emailForQueuing = new EmailForQueuing( $email, $this->subject, $this->message );
			Mail::to( $email )->send( $emailForQueuing );
		}
	}
}