<?php

namespace NotificationChannels\Telegram;

use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TelegramLocation.
 */
class TelegramLocation extends TelegramBase implements TelegramSenderContract
{
    /**
     * Telegram Location constructor.
     */
    public function __construct(float|string $latitude = '', float|string $longitude = '')
    {
        parent::__construct();
        $this->latitude($latitude);
        $this->longitude($longitude);
    }

    public static function create(float|string $latitude = '', float|string $longitude = ''): self
    {
        return new self($latitude, $longitude);
    }

    /**
     * Location's latitude.
     *
     * @return $this
     */
    public function latitude(float|string $latitude): self
    {
        $this->payload['latitude'] = $latitude;

        return $this;
    }

    /**
     * Location's longitude.
     *
     * @return $this
     */
    public function longitude(float|string $longitude): self
    {
        $this->payload['longitude'] = $longitude;

        return $this;
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send(): ?ResponseInterface
    {
        return $this->telegram->sendLocation($this->toArray());
    }
}
