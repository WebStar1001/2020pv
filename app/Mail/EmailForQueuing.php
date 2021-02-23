<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;

class EmailForQueuing extends Mailable
{
	use Queueable, SerializesModels;

	public $email;
	public $subject;
	public $message;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($email, $subject, $message)
	{
		$this->email = $email;
		$this->subject = $subject;
		$this->message = $message;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
		            ->subject($this->subject)
		            ->view('emails.newsletter', [
		            	'hash' => Crypt::encrypt($this->email),
		            	'text' => nl2br($this->message)
		            ]);
	}
}