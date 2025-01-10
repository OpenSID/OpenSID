<?php

namespace App\Notifications\Admin;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class VerifyEmailNotification extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject(Lang::get('Verify Email Address'))
            ->from(identitas('email_desa'), identitas('nama_desa'))
            ->view('admin.auth.notifikasi_verifikasi_email', [
                'hash'      => sha1($notifiable->email),
                'expire'    => strtotime(date('Y-m-d H:i:s') . ' +60 minutes'),
                'signature' => hash_hmac('sha256', $notifiable->email, config('app.key')),
            ]);
    }
}
