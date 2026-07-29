<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestReviewed extends Notification
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
        $approved = $this->leaveRequest->status === 'Approved';

        $mail = (new MailMessage)
            ->subject($approved
                ? __('Your leave request was approved')
                : __('Your leave request was rejected'))
            ->greeting($approved ? __('Leave request approved') : __('Leave request rejected'))
            ->line(__('Your leave request from :start to :end has been :status.', [
                'start' => $this->leaveRequest->start_date->toFormattedDateString(),
                'end' => $this->leaveRequest->end_date->toFormattedDateString(),
                'status' => __(strtolower($this->leaveRequest->status)),
            ]));

        if ($this->leaveRequest->admin_comment) {
            $mail->line(__('Note from admin: :comment', ['comment' => $this->leaveRequest->admin_comment]));
        }

        return $mail->action(__('View leave requests'), route('leave-requests.index'));
    }
}
