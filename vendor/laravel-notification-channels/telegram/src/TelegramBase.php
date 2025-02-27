<?php

namespace NotificationChannels\Telegram;

use JsonSerializable;
use NotificationChannels\Telegram\Traits\HasSharedLogic;

/**
 * Class TelegramBase.
 */
class TelegramBase implements JsonSerializable
{
    use HasSharedLogic;

    public Telegram $telegram;

    public function __construct()
    {
        $this->telegram = app(Telegram::class);
    }
}
