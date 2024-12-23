<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Verifikasi Alamat Email')
                    ->view('admin.auth.notifikasi_verifikasi_email')
                    ->with([
                        'hash' => sha1($this->user->email),
                        'expire' => strtotime(date('Y-m-d H:i:s') . ' +60 minutes'),
                        'signature' => hash_hmac('sha256', $this->user->email, config('app.key')),
                    ]);
    }
}