<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TimesheetApproved extends Notification
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

        $namaPetugas = optional($this->timesheet->approver)->nama ?? 'Atasan/Admin';

        return (new MailMessage)
            ->subject("Timesheet Approved - {$bulan}")
            ->greeting("Halo {$notifiable->nama}")
            ->line("Timesheet Anda telah disetujui oleh {$namaPetugas}.")
            ->line("Periode: {$bulan}")
            ->line("Total Jam: " . round($this->timesheet->total_minutes / 60, 2))
            ->action('Lihat Timesheet', route('timesheet.index'));
    }
}