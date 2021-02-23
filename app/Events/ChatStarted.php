<?php

namespace App\Events;

use App\ChatSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatStarted implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $chat_session;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(ChatSession $chat_session)
	{
		$this->chat_session = $chat_session;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('chat.'.$this->chat_session->id);
	}
}
