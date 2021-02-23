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

class PaymentApproved implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $user;
	public $amount;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, $amount)
	{
		$this->user = $user;
		$this->amount = $amount;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn()
	{
		$chat_session = $this->user->activeChatSession();

		if ($chat_session) {
			return new PrivateChannel('chat.'.$chat_session->id);
		}

		return new PrivateChannel('payment');
	}
}
