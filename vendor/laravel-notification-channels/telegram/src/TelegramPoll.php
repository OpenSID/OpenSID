<?php

namespace NotificationChannels\Telegram;

use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TelegramPoll.
 */
class TelegramPoll extends TelegramBase implements TelegramSenderContract
{
    public function __construct(string $question = '')
    {
        parent::__construct();
        $this->question($question);
    }

    public static function create(string $question = ''): self
    {
        return new self($question);
    }

    /**
     * Poll question.
     *
     * @return $this
     */
    public function question(string $question): self
    {
        $this->payload['question'] = $question;

        return $this;
    }

    /**
     * Poll choices.
     *
     * @return $this
     */
    public function choices(array $choices): self
    {
        $this->payload['options'] = json_encode($choices, JSON_THROW_ON_ERROR);

        return $this;
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send(): ?ResponseInterface
    {
        return $this->telegram->sendPoll($this->toArray());
    }
}
