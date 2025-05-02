<?php

namespace NotificationChannels\Telegram\Contracts;

use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

interface TelegramSenderContract
{
    /**
     * Send the message.
     *
     *
     * @throws CouldNotSendNotification
     */
    public function send(): ResponseInterface|array|null;
}
