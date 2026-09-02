<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TimesheetApproved extends Notification
{
    use Queueable;

    protected $timesheet;
    protected string $approverName;

    public function __construct($timesheet, string $approverName)
    {
        $this->timesheet = $timesheet;
        $this->approverName = $approverName;
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
            ->subject("Timesheet Approved - {$bulan}")
            ->greeting("Halo {$notifiable->nama}")
            ->line("Timesheet Anda telah disetujui oleh {$this->approverName}.")
            ->line("Periode: {$bulan}")
            ->line("Total Jam: " . round($this->timesheet->total_minutes / 60, 2))
            ->action('Lihat Timesheet', route('timesheet.index'));
    }
}