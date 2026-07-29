<?php

namespace App\Notifications;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Models\LeaveRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmitted extends Notification
{
    public function __construct(private readonly LeaveRequest $leaveRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->leaveRequest->loadMissing('user');

        return (new MailMessage)
            ->subject(__('New leave request from :name', ['name' => $this->leaveRequest->user->name]))
            ->greeting(__('New leave request'))
            ->line(__(':name requested leave from :start to :end.', [
                'name' => $this->leaveRequest->user->name,
                'start' => $this->leaveRequest->start_date->toFormattedDateString(),
                'end' => $this->leaveRequest->end_date->toFormattedDateString(),
            ]))
            ->when($this->leaveRequest->reason, fn (MailMessage $mail) => $mail->line(__('Reason: :reason', ['reason' => $this->leaveRequest->reason])))
            ->action(__('Review request'), LeaveRequestResource::getUrl('index'));
    }
}
