<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram;

use GuzzleHttp\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TelegramUpdates.
 */
class TelegramUpdates
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(protected array $payload = []) {}

    public static function create(): self
    {
        return new self;
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
     * @param  array<string, mixed>  $options
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

    /**
     * @return array<string, mixed>
     *
     * @throws InvalidArgumentException
     */
    public function get(): array
    {
        $response = app(Telegram::class)->getUpdates($this->payload);

        if (! $response instanceof ResponseInterface) {
            return [];
        }

        return Telegram::decodeResponse($response);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
