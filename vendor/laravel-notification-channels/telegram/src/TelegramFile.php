<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram;

use Illuminate\Support\Facades\View;
use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Enums\FileType;
use NotificationChannels\Telegram\Enums\ParseMode;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use NotificationChannels\Telegram\Traits\InteractsWithTelegramMedia;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class TelegramFile
 *
 * Handles file-based Telegram notifications with support for various file types
 * and content formats.
 *
 * @phpstan-type MultipartItem array{name: string, contents: mixed, filename?: string}
 */
class TelegramFile extends TelegramBase implements TelegramSenderContract
{
    use InteractsWithTelegramMedia;

    /** @var FileType The file content type */
    public FileType $type = FileType::Document;

    /** @var list<FileType> File types that don't support captions */
    protected array $captionUnsupportedTypes = [
        FileType::VideoNote,
        FileType::Sticker,
    ];

    /**
     * Create a new TelegramFile instance.
     */
    public function __construct(string $content = '')
    {
        parent::__construct();
        $this->content($content);
        $this->parseMode(ParseMode::Markdown);
    }

    /**
     * Create a new instance of TelegramFile.
     */
    public static function create(string $content = ''): self
    {
        return new self($content);
    }

    /**
     * Set notification caption for supported file types with markdown support.
     */
    public function content(string $content): self
    {
        $this->payload['caption'] = $content;

        return $this;
    }

    /**
     * Attach a file to the message.
     *
     * @param  resource|StreamInterface|string  $file  The file content or path
     * @param  FileType|string  $type  The file type
     * @param  string|null  $filename  Optional custom filename
     *
     * @throws CouldNotSendNotification
     */
    public function file(mixed $file, FileType|string $type, ?string $filename = null): self
    {
        $this->type = is_string($type)
            ? FileType::tryFrom($type) ?? FileType::Document
            : $type;

        $typeValue = $this->type->value;

        // Handle file URLs or Telegram file IDs
        if (is_string($file) && $this->isRemoteIdentifier($file, $filename)) {
            $this->payload[$typeValue] = $file;

            return $this;
        }

        $contents = $this->normalizeUpload($file, $filename);

        $this->payload['file'] = $this->makeMultipartItem($typeValue, $contents, $filename);

        return $this;
    }

    /**
     * Attach a photo.
     */
    public function photo(string $file): self
    {
        return $this->file($file, FileType::Photo);
    }

    /**
     * Attach an audio file.
     */
    public function audio(string $file): self
    {
        return $this->file($file, FileType::Audio);
    }

    /**
     * Attach a document file.
     */
    public function document(string $file, ?string $filename = null): self
    {
        return $this->file($file, FileType::Document, $filename);
    }

    /**
     * Attach a video file.
     */
    public function video(string $file): self
    {
        return $this->file($file, FileType::Video);
    }

    /**
     * Attach an animation file.
     */
    public function animation(string $file): self
    {
        return $this->file($file, FileType::Animation);
    }

    /**
     * Attach a voice message file.
     */
    public function voice(string $file): self
    {
        return $this->file($file, FileType::Voice);
    }

    /**
     * Attach a video note file.
     */
    public function videoNote(string $file): self
    {
        return $this->file($file, FileType::VideoNote);
    }

    /**
     * Attach a sticker.
     */
    public function sticker(string $file): self
    {
        return $this->file($file, FileType::Sticker);
    }

    /**
     * Use a Laravel Blade view as the content.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $mergeData
     */
    public function view(string $view, array $data = [], array $mergeData = []): self
    {
        return $this->content(View::make($view, $data, $mergeData)->render());
    }

    /**
     * @param  list<array<string, mixed>>  $captionEntities
     */
    public function captionEntities(array $captionEntities): self
    {
        $this->payload['caption_entities'] = json_encode($captionEntities, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function showCaptionAboveMedia(bool $show = true): self
    {
        $this->payload['show_caption_above_media'] = $show;

        return $this;
    }

    /**
     * Check if a file is attached.
     */
    public function hasFile(): bool
    {
        return isset($this->payload['file']);
    }

    /**
     * Check if the current file type supports captions.
     */
    protected function supportsCaptions(): bool
    {
        return ! in_array($this->type, $this->captionUnsupportedTypes, true);
    }

    /**
     * Convert the notification to an array for API consumption.
     *
     * @return array<string, mixed>|list<MultipartItem>
     */
    public function toArray(): array
    {
        $payload = $this->payload;

        // Remove caption for unsupported file types
        if (! $this->supportsCaptions() && isset($payload['caption'])) {
            unset($payload['caption']);
        }

        return $this->hasFile()
            ? $this->toMultipart($payload)
            : $payload;
    }

    /**
     * Create multipart array for file uploads.
     *
     * @param  array<string, mixed>|null  $payload
     * @return list<MultipartItem>
     */
    public function toMultipart(?array $payload = null): array
    {
        $payload = $payload ?? $this->payload;
        $file = $payload['file'] ?? null;
        unset($payload['file']);

        $multipart = $this->payloadToMultipart($payload);

        if (is_array($file) && isset($file['name'], $file['contents']) && is_string($file['name'])) {
            $multipart[] = $this->makeMultipartItem(
                $file['name'],
                $file['contents'],
                isset($file['filename']) && is_string($file['filename']) ? $file['filename'] : null
            );
        }

        return $multipart;
    }

    /**
     * Send the notification through Telegram.
     *
     * @throws CouldNotSendNotification
     */
    public function send(): ?ResponseInterface
    {
        // Get the method endpoint based on file type
        return $this->telegram->sendFile(
            $this->toArray(),
            $this->type->value,
            $this->hasFile()
        );
    }
}
