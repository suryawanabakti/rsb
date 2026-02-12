<?php

namespace App\Notifications;

use App\Models\LetterRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LetterRequestStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected LetterRequest $letterRequest
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = strtoupper($this->letterRequest->status);
        return (new MailMessage)
            ->subject('Update Status Permohonan Surat')
            ->line('Status permohonan surat Anda telah berubah.')
            ->line('Jenis Surat: ' . $this->letterRequest->letterType->name)
            ->line('Status Baru: ' . $status)
            ->action('Lihat Detail', route('pasien.letter-requests.show', $this->letterRequest->id))
            ->line('Terima kasih telah menggunakan layanan RS. Bhayangkara Makassar!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'letter_request_id' => $this->letterRequest->id,
            'letter_type_name' => $this->letterRequest->letterType->name,
            'status' => $this->letterRequest->status,
            'message' => 'Status permohonan ' . $this->letterRequest->letterType->name . ' Anda telah berubah menjadi ' . $this->letterRequest->status,
        ];
    }
}
