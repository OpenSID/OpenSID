<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram;

use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class TelegramDice extends TelegramBase implements TelegramSenderContract
{
    public function __construct(string $emoji = '')
    {
        parent::__construct();

        if ($emoji !== '') {
            $this->emoji($emoji);
        }
    }

    public static function create(string $emoji = ''): self
    {
        return new self($emoji);
    }

    public function emoji(string $emoji): self
    {
        $this->payload['emoji'] = $emoji;

        return $this;
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send(): ?ResponseInterface
    {
        return $this->telegram->sendDice($this->toArray());
    }
}
