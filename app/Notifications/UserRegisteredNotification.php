<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification
{
    use Queueable;

    public $username;

    /**
     * Create a new notification instance.
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to Our Application!')
            ->greeting('Hello ' . $this->username . ',')
            ->line('Thank you for registering with us.')
            ->line('We are excited to have you onboard and look forward to providing you with excellent service.')
            ->line('If you have any questions, feel free to reach out to our support team.')
            ->line('Welcome aboard!');
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
