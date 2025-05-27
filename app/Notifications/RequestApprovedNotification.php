<?php

namespace App\Notifications;

use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Request $request
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Request Has Been Approved')
            ->line('Your request has been fully approved.')
            ->line('Request Title: ' . $this->request->title)
            ->action('View Request', route('requests.show', $this->request))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'title' => 'Request Approved',
            'message' => 'Your request "' . $this->request->title . '" has been approved.',
            'url' => route('requests.show', $this->request),
        ];
    }
}