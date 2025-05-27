<?php

namespace App\Notifications;

use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Request $request,
        public ?string $rejectionReason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Your Request Has Been Rejected')
            ->line('Your request has been rejected.')
            ->line('Request Title: ' . $this->request->title);

        if ($this->rejectionReason) {
            $mailMessage->line('Reason: ' . $this->rejectionReason);
        }

        return $mailMessage
            ->action('View Request', route('requests.show', $this->request))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'title' => 'Request Rejected',
            'message' => 'Your request "' . $this->request->title . '" has been rejected.' . 
                ($this->rejectionReason ? ' Reason: ' . $this->rejectionReason : ''),
            'url' => route('requests.show', $this->request),
        ];
    }
}