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

class CallIncoming implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $customer;
	public $advisor;
	public $countdown_seconds;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $customer, User $advisor, $countdown_seconds)
	{
		$this->customer = $customer;
		$this->advisor = $advisor;
		$this->countdown_seconds = $countdown_seconds;
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
