<?php

namespace App\Notifications;

use App\Models\LabResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LabResultStatusUpdated extends Notification
{
    use Queueable;

    /**
     * @param string $statusAction 'created' or 'validated'
     */
    public function __construct(protected LabResult $labResult, protected string $statusAction) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->statusAction === 'validated'
            ? 'Hasil Lab Anda Telah Divalidasi'
            : 'Hasil Lab Anda Tersedia (Menunggu Validasi)';

        $message = $this->statusAction === 'validated'
            ? 'Hasil pemeriksaan lab ' . $this->labResult->test_name . ' Anda telah divalidasi oleh dokter dan siap dilihat.'
            : 'Pemeriksaan lab ' . $this->labResult->test_name . ' Anda telah selesai diinput dan sedang menunggu validasi dokter.';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($message)
            ->action('Lihat Hasil Lab', url('/pasien/lab-results/' . $this->labResult->id))
            ->line('Terima kasih telah menggunakan layanan RS. Bhayangkara Makassar.');
    }

    public function toArray(object $notifiable): array
    {
        $message = $this->statusAction === 'validated'
            ? 'Hasil pemeriksaan ' . $this->labResult->test_name . ' Anda telah divalidasi dokter.'
            : 'Hasil pemeriksaan ' . $this->labResult->test_name . ' Anda telah tersedia (menunggu validasi).';

        return [
            'lab_result_id' => $this->labResult->id,
            'test_name' => $this->labResult->test_name,
            'status' => $this->labResult->status,
            'action' => $this->statusAction,
            'message' => $message,
            'type' => 'lab_result_update'
        ];
    }
}
