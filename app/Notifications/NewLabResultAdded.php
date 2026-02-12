<?php

namespace App\Notifications;

use App\Models\LabResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLabResultAdded extends Notification
{
    use Queueable;

    public function __construct(protected LabResult $labResult) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Hasil Lab Baru Menunggu Validasi')
            ->greeting('Halo dr. ' . $notifiable->name . ',')
            ->line('Ada hasil pemeriksaan lab baru yang memerlukan validasi Anda.')
            ->line('Pasien: ' . $this->labResult->patient->user->name)
            ->line('Pemeriksaan: ' . $this->labResult->test_name)
            ->line('Tanggal: ' . \Carbon\Carbon::parse($this->labResult->test_date)->format('d/m/Y'))
            ->action('Lihat Hasil Lab', url('/dokter/lab-results/' . $this->labResult->id))
            ->line('Mohon segera lakukan validasi untuk kelanjutan layanan pasien.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lab_result_id' => $this->labResult->id,
            'patient_name' => $this->labResult->patient->user->name,
            'test_name' => $this->labResult->test_name,
            'message' => 'Hasil lab baru untuk ' . $this->labResult->patient->user->name . ' (' . $this->labResult->test_name . ') menunggu validasi Anda.',
            'type' => 'lab_result_pending_validation'
        ];
    }
}
