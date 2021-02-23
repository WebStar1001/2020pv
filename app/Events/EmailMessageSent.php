<?php

namespace App\Events;

use App\EmailMessage;
use App\User;
use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmailMessageSent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $email_subject_id;

	/**
	 * User that sent the message
	 *
	 * @var User
	 */
	public $user;

	/**
	 * Message details
	 *
	 * @var Message
	 */
	public $message;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($subject_id, User $user, EmailMessage $message)
	{
		$this->email_subject_id = $subject_id;
		$this->user = $user;
		$this->message = $message;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('email_chat.'.$this->email_subject_id);
	}
}
