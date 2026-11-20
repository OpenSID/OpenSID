<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram;

use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Enums\FileType;
use NotificationChannels\Telegram\Enums\ParseMode;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use NotificationChannels\Telegram\Traits\InteractsWithTelegramMedia;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @phpstan-type MultipartItem array{name: string, contents: mixed, filename?: string}
 * @phpstan-type MediaItem array<string, mixed>
 */
class TelegramMediaGroup extends TelegramBase implements TelegramSenderContract
{
    use InteractsWithTelegramMedia;

    /**
     * @var list<MediaItem>
     */
    protected array $media = [];

    /**
     * @var list<MultipartItem>
     */
    protected array $attachments = [];

    public function __construct()
    {
        parent::__construct();
        $this->parseMode(ParseMode::Markdown);
    }

    public static function create(): self
    {
        return new self;
    }

    /**
     * @param  resource|StreamInterface|string  $media
     *
     * @throws CouldNotSendNotification
     */
    public function photo(mixed $media, ?string $caption = null, ?string $filename = null): self
    {
        return $this->addMedia(FileType::Photo, $media, $caption, $filename);
    }

    /**
     * @param  resource|StreamInterface|string  $media
     *
     * @throws CouldNotSendNotification
     */
    public function video(mixed $media, ?string $caption = null, ?string $filename = null): self
    {
        return $this->addMedia(FileType::Video, $media, $caption, $filename);
    }

    /**
     * @param  resource|StreamInterface|string  $media
     *
     * @throws CouldNotSendNotification
     */
    public function audio(mixed $media, ?string $caption = null, ?string $filename = null): self
    {
        return $this->addMedia(FileType::Audio, $media, $caption, $filename);
    }

    /**
     * @param  resource|StreamInterface|string  $media
     *
     * @throws CouldNotSendNotification
     */
    public function document(mixed $media, ?string $caption = null, ?string $filename = null): self
    {
        return $this->addMedia(FileType::Document, $media, $caption, $filename);
    }

    public function hasAttachments(): bool
    {
        return $this->attachments !== [];
    }

    /**
     * @return array<string, mixed>|list<MultipartItem>
     */
    public function toArray(): array
    {
        $payload = [
            ...$this->allowedPayload(),
            'media' => json_encode($this->media, JSON_THROW_ON_ERROR),
        ];

        if (! $this->hasAttachments()) {
            return $payload;
        }

        return [...$this->payloadToMultipart($payload), ...$this->attachments];
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send(): ?ResponseInterface
    {
        return $this->telegram->sendMediaGroup($this->toArray(), $this->hasAttachments());
    }

    /**
     * @param  resource|StreamInterface|string  $media
     *
     * @throws CouldNotSendNotification
     */
    protected function addMedia(
        FileType $type,
        mixed $media,
        ?string $caption = null,
        ?string $filename = null
    ): self {
        if (! in_array($type, [FileType::Photo, FileType::Video, FileType::Audio, FileType::Document], true)) {
            throw CouldNotSendNotification::invalidFileIdentifier($type->value);
        }

        $item = ['type' => $type->value];

        if (is_string($media) && $this->isRemoteIdentifier($media, $filename)) {
            $item['media'] = $media;
        } else {
            $attachmentName = 'file'.count($this->attachments);
            $item['media'] = 'attach://'.$attachmentName;

            $this->attachments[] = $this->makeMultipartItem(
                $attachmentName,
                $this->normalizeUpload($media, $filename, 'Invalid media input'),
                $filename
            );
        }

        if ($caption !== null) {
            $item['caption'] = $caption;

            $parseMode = $this->payload['parse_mode'] ?? null;

            if (is_string($parseMode)) {
                $item['parse_mode'] = $parseMode;
            }
        }

        $this->media[] = $item;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function allowedPayload(): array
    {
        return array_intersect_key($this->payload, array_flip([
            'business_connection_id',
            'chat_id',
            'message_thread_id',
            'direct_messages_topic_id',
            'disable_notification',
            'protect_content',
            'allow_paid_broadcast',
            'message_effect_id',
            'suggested_post_parameters',
            'reply_parameters',
        ]));
    }
}
