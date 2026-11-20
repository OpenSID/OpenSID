<?php

declare(strict_types=1);

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

    public function __construct(?Telegram $telegram = null)
    {
        $this->telegram = $telegram ?? app(Telegram::class);
    }
}
