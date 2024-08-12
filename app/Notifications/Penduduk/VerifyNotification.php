<?php

namespace App\Notifications\Penduduk;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;

class VerifyNotification extends Notification
{
    public function __construct(protected $via)
    {
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [$this->via];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifikasi Alamat Email')
            ->from(identitas('email_desa'), identitas('nama_desa'))
            ->view('layanan_mandiri.auth.notifikasi_verifikasi_email', [
                'url' => $this->verificationUrl($notifiable),
            ]);
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->getTelegramForVerification())
            ->content("Hello!")
            ->line('')
            ->line('')
            ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat telegram Anda.')
            ->line('')
            ->line('Jika Anda tidak membuat akun, tidak diperlukan tindakan lebih lanjut.')
            ->line('')
            ->line('Salam,')
            ->line(identitas('nama_desa'))
            ->button('Verifikasi Alamat Telegram', $this->verificationUrl($notifiable));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $hash      = sha1($for = $this->via == 'mail' ? $notifiable->getEmailForVerification() : $notifiable->getTelegramForVerification());
        $signature = hash_hmac('sha256', $for, config_item('encryption_key'));
        $expire    = strtotime(date('Y-m-d H:i:s') . ' +60 minutes');
        $via       = $this->via === 'mail' ? 'email' : 'telegram';

        return site_url("layanan-mandiri/daftar/verifikasi/{$via}/verify/{$hash}?signature={$signature}&expires={$expire}");
    }
}
