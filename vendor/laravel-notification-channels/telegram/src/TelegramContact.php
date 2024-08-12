<?php

namespace NotificationChannels\Telegram;

use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TelegramContact.
 */
class TelegramContact extends TelegramBase implements TelegramSenderContract
{
    public function __construct(string $phoneNumber = '')
    {
        parent::__construct();
        $this->phoneNumber($phoneNumber);
    }

    public static function create(string $phoneNumber = ''): self
    {
        return new self($phoneNumber);
    }

    /**
     * Contact phone number.
     *
     * @return $this
     */
    public function phoneNumber(string $phoneNumber): self
    {
        $this->payload['phone_number'] = $phoneNumber;

        return $this;
    }

    /**
     * Contact first name.
     *
     * @return $this
     */
    public function firstName(string $firstName): self
    {
        $this->payload['first_name'] = $firstName;

        return $this;
    }

    /**
     * Contact last name.
     *
     * @return $this
     */
    public function lastName(string $lastName): self
    {
        $this->payload['last_name'] = $lastName;

        return $this;
    }

    /**
     * Contact vCard.
     *
     * @return $this
     */
    public function vCard(string $vCard): self
    {
        $this->payload['vcard'] = $vCard;

        return $this;
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send(): ?ResponseInterface
    {
        return $this->telegram->sendContact($this->toArray());
    }
}
