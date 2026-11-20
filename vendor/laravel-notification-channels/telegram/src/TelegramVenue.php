<?php

namespace NotificationChannels\Telegram;

use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TelegramVenue.
 */
class TelegramVenue extends TelegramBase implements TelegramSenderContract
{
    /**
     * Telegram Venue constructor.
     */
    public function __construct(
        float|string $latitude = '',
        float|string $longitude = '',
        string $title = '',
        string $address = ''
    ) {
        parent::__construct();
        $this->latitude($latitude);
        $this->longitude($longitude);
        $this->title($title);
        $this->address($address);
    }

    public static function create(
        float|string $latitude = '',
        float|string $longitude = '',
        string $title = '',
        string $address = ''
    ): self {
        return new self($latitude, $longitude, $title, $address);
    }

    /**
     * Venue's latitude.
     *
     * @return $this
     */
    public function latitude(float|string $latitude): self
    {
        $this->payload['latitude'] = $latitude;

        return $this;
    }

    /**
     * Venue's longitude.
     *
     * @return $this
     */
    public function longitude(float|string $longitude): self
    {
        $this->payload['longitude'] = $longitude;

        return $this;
    }

    /**
     * Venue's name/title.
     *
     * @return $this
     */
    public function title(string $title): self
    {
        $this->payload['title'] = $title;

        return $this;
    }

    /**
     * Venue's address.
     *
     * @return $this
     */
    public function address(string $address): self
    {
        $this->payload['address'] = $address;

        return $this;
    }

    /**
     * Optional: Foursquare ID.
     *
     * @return $this
     */
    public function foursquareId(string $foursquareId): self
    {
        $this->payload['foursquare_id'] = $foursquareId;

        return $this;
    }

    /**
     * Optional: Foursquare Type.
     *
     * @return $this
     */
    public function foursquareType(string $foursquareType): self
    {
        $this->payload['foursquare_type'] = $foursquareType;

        return $this;
    }

    /**
     * Optional: Google Place ID.
     *
     * @return $this
     */
    public function googlePlaceId(string $googlePlaceId): self
    {
        $this->payload['google_place_id'] = $googlePlaceId;

        return $this;
    }

    /**
     * Optional: Google Place Type.
     *
     * @return $this
     */
    public function googlePlaceType(string $googlePlaceType): self
    {
        $this->payload['google_place_type'] = $googlePlaceType;

        return $this;
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send(): ?ResponseInterface
    {
        return $this->telegram->sendVenue($this->toArray());
    }
}
