<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), 'OpenSID')
                    ->subject('Verifikasi Akun Email')
                    ->view('email.verifikasi', ['token' => $this->otp]);
    }
}