<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TimesheetRejected extends Notification
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
            ->subject("Timesheet Rejected - {$bulan}")
            ->greeting("Halo {$notifiable->nama}")
            ->line("Timesheet Anda ditolak.")
            ->line("Periode: {$bulan}")
            ->line("Catatan:")
            ->line($this->timesheet->approval_note ?? '-')
            ->action('Perbaiki Timesheet', route('timesheet.index'));
    }
}