<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram\Contracts;

use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

interface TelegramSenderContract
{
    /**
     * Send the message.
     *
     * @return ResponseInterface|array<int, array<string, mixed>>|null
     *
     * @throws CouldNotSendNotification
     */
    public function send(): ResponseInterface|array|null;
}
