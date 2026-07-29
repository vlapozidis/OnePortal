<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckoutReminder extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Reminder: check out for today'))
            ->greeting(__('Don\'t forget to check out'))
            ->line(__('You checked in today but haven\'t checked out yet.'))
            ->action(__('Check out now'), route('workforce.today'));
    }
}
