<?php

namespace App\Events;

use App\ChatSession;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallEnded implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $user;
	public $chat_session_id;
	public $duration;
	public $reason;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, $chat_session_id, $duration, $reason)
	{
		$this->user = $user;
		$this->chat_session_id = $chat_session_id;
		$this->duration = $duration;
		$this->reason = $reason;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('chat.'.$this->chat_session_id);
	}
}
