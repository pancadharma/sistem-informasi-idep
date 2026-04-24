<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TimesheetSubmitted extends Notification
{
    use Queueable;

    protected $timesheet;

    public function __construct($timesheet)
    {
        $this->timesheet = $timesheet;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $bulan = \Carbon\Carbon::create(
            $this->timesheet->year,
            $this->timesheet->month
        )->translatedFormat('F Y');

        return (new MailMessage)
            ->subject("Timesheet Submitted - {$this->timesheet->user->nama}")
            ->greeting("Halo {$notifiable->nama}")
            ->line("Timesheet baru telah disubmit.")
            ->line("Staff  : {$this->timesheet->user->nama}")
            ->line("Periode: {$bulan}")
            ->line("Total Jam: " . round($this->timesheet->total_minutes / 60, 2))
            ->action('Review Timesheet', route('approval.show', $this->timesheet->id))
            ->line('Mohon untuk direview.');
    }
}