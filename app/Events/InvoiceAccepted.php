<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InvoiceAccepted implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $email_subject_id;
	public $invoice_id;


	public function __construct($subject_id, $invoice_id)
	{
		$this->email_subject_id = $subject_id;
		$this->invoice_id = $invoice_id;
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
