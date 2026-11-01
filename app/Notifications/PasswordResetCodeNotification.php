<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetCodeNotification extends Notification
{
    use Queueable;

    public function __construct(public string $code) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Reset Password SIPEMA')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Gunakan kode berikut untuk mengatur ulang password akun SIPEMA Anda:')
            ->line('**'.$this->code.'**')
            ->line('Kode ini berlaku selama 10 menit dan hanya dapat digunakan satu kali.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return ['code' => $this->code];
    }
}
