<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountDeclined extends Notification
{
    use Queueable;

    protected $decline_reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($decline_reason)
    {
        $this->decline_reason = $decline_reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
	        ->subject('Your application is declined!')
	        ->greeting('Hello!')
	        ->line('You applcation is declined')
	        ->line('Reason: ' . $this->decline_reason)
	        ->line('If you believe this email has been sent in error kindly disregard it.')
	        ->line('With regards')
	        ->line('GoPsys Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
