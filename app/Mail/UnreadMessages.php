<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnreadMessages extends Mailable
{
    use Queueable, SerializesModels;

    public $unread_messages_count;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($unread_messages_count)
    {
        $this->unread_messages_count = $unread_messages_count;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.unread_messages');
    }
}
