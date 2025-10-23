<?php

namespace App\Notifications\Admin;

use Illuminate\Notifications\Messages\MailMessage;

class OneTimePasswordNotification extends \Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification
{
    public function via(object $notifiable): string|array
    {
        return 'mail';
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi 2FA - OpenSID')
            ->from(identitas('email_desa'), identitas('nama_desa'))
            ->view('admin.auth.notifikasi_otp', [
                'oneTimePassword' => $this->oneTimePassword,
            ]);
    }
}
