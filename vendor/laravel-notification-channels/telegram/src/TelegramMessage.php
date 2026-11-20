<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram;

use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\View;
use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Enums\ParseMode;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

final class TelegramMessage extends TelegramBase implements TelegramSenderContract
{
    private const DEFAULT_CHUNK_SIZE = 4096;

    private const CHUNK_SEPARATOR = '%#TGMSG#%';

    private string $text = '';

    public function __construct(
        string $content = '',
        public int $chunkSize = 0
    ) {
        parent::__construct();

        $this->text = $content;
        $this->parseMode(ParseMode::Markdown);
    }

    public static function create(string $content = ''): self
    {
        return new self($content);
    }

    /** @see https://core.telegram.org/bots/api#markdownv2-style */
    public static function escapeMarkdown(string $content): ?string
    {
        return preg_replace_callback(
            '/[_*[\]()~`>#\+\-=|{}.!]/',
            fn ($matches): string => "\\$matches[0]",
            $content
        );
    }

    public function content(string $content, ?int $limit = null): self
    {
        $this->text = $content;

        if ($limit !== null) {
            $this->chunkSize = $limit;
        }

        return $this;
    }

    public function line(string $content): self
    {
        $this->text .= $content."\n";

        return $this;
    }

    public function lineIf(bool $condition, string $line): self
    {
        if ($condition) {
            $this->line($line);
        }

        return $this;
    }

    public function escapedLine(string $content): self
    {
        $content = str_replace('\\', '\\\\', $content);

        $escaped = self::escapeMarkdown($content) ?? $content;

        return $this->line($escaped);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $mergeData
     */
    public function view(string $view, array $data = [], array $mergeData = []): self
    {
        return $this->content(View::make($view, $data, $mergeData)->render());
    }

    /**
     * @param  list<array<string, mixed>>  $entities
     */
    public function entities(array $entities): self
    {
        $this->payload['entities'] = json_encode($entities, JSON_THROW_ON_ERROR);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $linkPreviewOptions
     */
    public function linkPreviewOptions(array $linkPreviewOptions): self
    {
        $this->payload['link_preview_options'] = json_encode($linkPreviewOptions, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function chunk(int $limit = self::DEFAULT_CHUNK_SIZE): self
    {
        $this->chunkSize = $limit;

        return $this;
    }

    public function shouldChunk(): bool
    {
        return $this->chunkSize > 0;
    }

    /**
     * @return array<int, array<string, mixed>>|ResponseInterface|null
     *
     * @throws CouldNotSendNotification
     * @throws InvalidArgumentException
     */
    public function send(): array|ResponseInterface|null
    {
        /** @var array<string, mixed> $payload */
        $payload = $this->toArray();

        return $this->shouldChunk()
            ? $this->sendChunkedMessage($payload)
            : $this->telegram->sendMessage($payload);
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<int, array<string, mixed>>
     *
     * @throws CouldNotSendNotification
     * @throws InvalidArgumentException
     */
    private function sendChunkedMessage(array $params): array
    {
        $replyMarkup = $params['reply_markup'] ?? null;

        if ($replyMarkup !== null) {
            unset($params['reply_markup']);
        }

        $messages = $this->chunkStrings($this->text, $this->chunkSize);
        $messages = array_values(array_filter($messages, static fn ($m) => $m !== ''));

        $lastIndex = count($messages) - 1;
        $responses = [];

        foreach ($messages as $index => $message) {
            $payload = [...$params, 'text' => $message];

            if ($index === $lastIndex && $replyMarkup !== null) {
                $payload['reply_markup'] = $replyMarkup;
            }

            $response = $this->telegram->sendMessage($payload);

            // Telegram rate limiting safety
            sleep(1);

            if ($response !== null) {
                $responses[] = Telegram::decodeResponse($response);
            }
        }

        return $responses;
    }

    /**
     * @return list<string>
     */
    private function chunkStrings(string $value, int $limit = self::DEFAULT_CHUNK_SIZE): array
    {
        $limit = max(1, min($limit, self::DEFAULT_CHUNK_SIZE));

        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return [$value];
        }

        $wrapped = wordwrap($value, $limit, self::CHUNK_SEPARATOR);
        $parts = explode(self::CHUNK_SEPARATOR, $wrapped);

        return count($parts) > 1
            ? $parts
            : mb_str_split($value, $limit, 'UTF-8');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text,
            ...parent::toArray(),
        ];
    }

    public function getPayloadValue(string $key): string|int|float|bool|array|null
    {
        return match ($key) {
            'text' => $this->text,
            default => parent::getPayloadValue($key),
        };
    }
}
