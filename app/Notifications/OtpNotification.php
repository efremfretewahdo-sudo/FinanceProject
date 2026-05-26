<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    public function __construct(private readonly string $otp) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your ADAM44 Verification Code')
            ->greeting("Hello, {$notifiable->name}!")
            ->line('Enter the code below to complete your verification.')
            ->line('This code expires in **10 minutes** and can only be used once.')
            ->line('')
            ->line('## ' . $this->otp)
            ->line('')
            ->line('If you did not request this code, please ignore this email.')
            ->line('Your account remains secure and no action is required.')
            ->salutation('The ADAM44 Finance Team');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
