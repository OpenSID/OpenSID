<?php

namespace Spatie\OneTimePasswords\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\OneTimePasswords\Models\OneTimePassword;

class OneTimePasswordNotification extends Notification
{
    public bool $deleteWhenMissingModels = true;

    use Queueable;

    public function __construct(public OneTimePassword $oneTimePassword) {}

    public function toMail(object $notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject())
            ->markdown('one-time-passwords::mail', [
                'oneTimePassword' => $this->oneTimePassword,
            ]);
    }

    public function via(object $notifiable): string|array
    {
        return 'mail';
    }

    public function subject(): string
    {
        return __('one-time-passwords::notifications.mail_subject', [
            'oneTimePassword' => $this->oneTimePassword->password,
        ]);
    }
}
