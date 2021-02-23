<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallCancelled implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $customer;
	public $advisor;
	public $advisor_not_answering;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $customer, User $advisor, $advisor_not_answering)
	{
		$this->customer = $customer;
		$this->advisor = $advisor;
		$this->advisor_not_answering = $advisor_not_answering;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('chat');
	}
}
