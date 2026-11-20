<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Utils;

/**
 * Class CouldNotSendNotification.
 */
final class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when there's a bad request and an error is responded.
     *
     * @throws InvalidArgumentException
     */
    public static function telegramRespondedWithAnError(ClientException $exception): self
    {
        if (! $exception->hasResponse()) {
            return new self('Telegram responded with an error but no response body found');
        }

        $statusCode = $exception->getResponse()->getStatusCode();

        $result = Utils::jsonDecode($exception->getResponse()->getBody()->getContents(), true);
        $description = is_array($result) && is_string($result['description'] ?? null)
            ? $result['description']
            : 'no description given';

        return new self("Telegram responded with an error `{$statusCode} - {$description}`", 0, $exception);
    }

    /**
     * Thrown when there's no bot token provided.
     */
    public static function telegramBotTokenNotProvided(string $message): self
    {
        return new self($message);
    }

    /**
     * Thrown when we're unable to communicate with Telegram.
     */
    public static function couldNotCommunicateWithTelegram(string $message): self
    {
        return new self("The communication with Telegram failed. `{$message}`");
    }

    /**
     * Thrown when the file cannot be opened.
     */
    public static function fileAccessFailed(string $file): self
    {
        return new self("Failed to open file: {$file}");
    }

    /**
     * Thrown when the file identifier is invalid (ID or URL).
     */
    public static function invalidFileIdentifier(string $file): self
    {
        return new self("Invalid file identifier: {$file}");
    }
}
