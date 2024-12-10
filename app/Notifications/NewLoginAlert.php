<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLoginAlert extends Notification
{
    use Queueable;

    protected $loginDetails;

    /**
     * Create a new notification instance.
     *
     * @param array $loginDetails
     */
    public function __construct(array $loginDetails)
    {
        $this->loginDetails = $loginDetails;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We detected a new login to your account.')
            ->line('Login details:')
            ->line('IP Address: ' . $this->loginDetails['ip_address'])
            ->line('Device: ' . $this->loginDetails['device'])
            ->line('Time: ' . $this->loginDetails['time'])
            ->action('Review Your Account', url('/account/security'))
            ->line('If this wasn’t you, please secure your account immediately.')
            ->line('Thank you for staying vigilant!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ip_address' => $this->loginDetails['ip_address'],
            'device' => $this->loginDetails['device'],
            'time' => $this->loginDetails['time'],
        ];
    }
}
