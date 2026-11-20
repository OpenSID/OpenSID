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
     * The location's radius of uncertainty for the location, measured in meters; 0-1500.
     *
     * @return $this
     */
    public function horizontalAccuracy(float|int|string $horizontalAccuracy): self
    {
        $this->payload['horizontal_accuracy'] = $horizontalAccuracy;

        return $this;
    }

    /**
     * Period in seconds during which the location can be updated.
     *
     * @return $this
     */
    public function livePeriod(int $livePeriod): self
    {
        $this->payload['live_period'] = $livePeriod;

        return $this;
    }

    /**
     * The direction in which the user is moving, in degrees.
     *
     * @return $this
     */
    public function heading(int $heading): self
    {
        $this->payload['heading'] = $heading;

        return $this;
    }

    /**
     * Maximum distance for proximity alerts about approaching another chat member, in meters.
     *
     * @return $this
     */
    public function proximityAlertRadius(int $proximityAlertRadius): self
    {
        $this->payload['proximity_alert_radius'] = $proximityAlertRadius;

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
