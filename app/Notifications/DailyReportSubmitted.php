<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyReportSubmitted extends Notification
{
    use Queueable;
    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $role = $notifiable->role;

    if ($role === 'industry_sv') {
        $url = route('industry.reports.show', $this->report->id);
    } elseif ($role === 'university_sv') {
        $url = route('supervisor.university.report.show', $this->report->id);
    } else {
        $url = '#';
    }

    return [
        'message' => 'Student submitted a daily report.',
        'url' => $url,
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
