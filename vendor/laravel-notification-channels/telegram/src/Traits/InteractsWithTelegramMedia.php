<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram\Traits;

use GuzzleHttp\Psr7\Utils;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\StreamInterface;

trait InteractsWithTelegramMedia
{
    /**
     * @return array{name: string, contents: mixed, filename?: string}
     */
    protected function makeMultipartItem(string $name, mixed $contents, ?string $filename = null): array
    {
        $item = [
            'name' => $name,
            'contents' => $contents,
        ];

        if ($filename !== null) {
            $item['filename'] = $filename;
        }

        return $item;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<array{name: string, contents: mixed, filename?: string}>
     */
    protected function payloadToMultipart(array $payload): array
    {
        $multipart = [];

        foreach ($payload as $name => $contents) {
            $multipart[] = $this->makeMultipartItem($name, $contents);
        }

        return $multipart;
    }

    protected function isReadableFile(string $file): bool
    {
        return is_file($file) && is_readable($file);
    }

    protected function isRemoteIdentifier(string $file, ?string $filename): bool
    {
        if ($filename !== null || $this->isReadableFile($file)) {
            return false;
        }

        return filter_var($file, FILTER_VALIDATE_URL) !== false
            || preg_match('/^[a-zA-Z0-9_-]+$/', $file) === 1;
    }

    /**
     * @param  resource|StreamInterface|string  $file
     *
     * @throws CouldNotSendNotification
     */
    protected function normalizeUpload(mixed $file, ?string $filename = null, string $invalidMessage = 'Invalid file input'): StreamInterface
    {
        return match (true) {
            $file instanceof StreamInterface => $file,
            is_resource($file) => Utils::streamFor($file),
            is_string($file) && $this->isReadableFile($file) => Utils::streamFor(fopen($file, 'rb') ?: throw CouldNotSendNotification::fileAccessFailed($file)),
            is_string($file) && $filename !== null => Utils::streamFor($file),
            default => throw CouldNotSendNotification::invalidFileIdentifier(
                is_string($file) ? $file : $invalidMessage
            ),
        };
    }
}
