<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupervisorFeedbackGiven extends Notification
{
    use Queueable;
    public $feedback;
    public $reportId;

    public function __construct($feedback, $reportId)
    {
        $this->feedback = $feedback;
        $this->reportId = $reportId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'You received feedback from your supervisor.',
            'url' => route('daily-report.show', $this->reportId),
        ];
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
