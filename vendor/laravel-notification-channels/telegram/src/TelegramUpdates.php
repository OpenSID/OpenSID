<?php

namespace NotificationChannels\Telegram;

/**
 * Class TelegramUpdates.
 */
class TelegramUpdates
{
    /** @var array Params payload. */
    protected array $payload = [];

    public static function create(): self
    {
        return new self();
    }

    /**
     * Telegram updates limit.
     *
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->payload['limit'] = $limit;

        return $this;
    }

    /**
     * Additional options.
     *
     * @return $this
     */
    public function options(array $options): self
    {
        $this->payload = array_merge($this->payload, $options);

        return $this;
    }

    public function latest(): self
    {
        $this->payload['offset'] = -1;

        return $this;
    }

    public function get(): array
    {
        $response = app(Telegram::class)->getUpdates($this->payload);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}
