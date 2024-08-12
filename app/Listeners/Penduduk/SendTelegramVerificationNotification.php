<?php

namespace App\Listeners\Penduduk;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendTelegramVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (null === $event->user->telegram) {
            return;
        }

        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedTelegram()) {
            $event->user->sendTelegramVerificationNotification();
        }
    }
}
