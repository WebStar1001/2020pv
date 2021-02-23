<?php

namespace App\Jobs;

use App\ChatSession;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EndInactivechatSessions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $opened_chat_sessions = ChatSession::where(['is_ended' => 0])->get();

        foreach ($opened_chat_sessions as $chat_session) {
	        $latest_message = $chat_session->messages()->latest()->first();

        	if ($latest_message && $latest_message->created_at->diffInSeconds(now()) > 665 ||
	            !$latest_message && $chat_session->created_at->diffInSeconds(now()) > 665) {
		        $chat_session->end('cron');
	        }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
