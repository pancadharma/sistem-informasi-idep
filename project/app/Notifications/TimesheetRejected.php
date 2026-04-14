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
        $namaPetugas = optional($this->timesheet->approver)->nama ?? 'Atasan/Admin';
        

        // 🔥 Cek apakah ini penolakan mutlak atau sekadar revisi (draft)
        $isDraft = $this->timesheet->status === 'draft';

        // Sesuaikan Subjek Email
        $subject = $isDraft 
            ? "Revisi Timesheet - {$bulan}" 
            : "Timesheet Ditolak - {$bulan}";

        // Sesuaikan Pesan Utama
        $pesanUtama = $isDraft 
            ? "Timesheet Anda telah dikembalikan oleh {$namaPetugas} menjadi Draft karena memerlukan perbaikan." 
            : "Timesheet Anda untuk periode ini telah ditolak oleh {$namaPetugas}.";

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Halo {$notifiable->nama},")
            ->line($pesanUtama)
            ->line("Periode: {$bulan}")
            ->line("Catatan / Instruksi:")
            ->line($this->timesheet->approval_note ?? 'Tidak ada catatan tambahan.') // Catatan ini sangat penting
            ->action('Buka & Perbaiki Timesheet', route('timesheet.index'));
    }
}