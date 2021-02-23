<?php

namespace App\Events;

use App\ChatSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DiscountUsed implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $customer_id;
	public $advisor_id;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($customer_id, $advisor_id)
	{
		$this->customer_id = $customer_id;
		$this->advisor_id = $advisor_id;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('App.User.'.$this->customer_id);
	}
}
