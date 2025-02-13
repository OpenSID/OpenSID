<?php

namespace NotificationChannels\Telegram\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use JsonException;

/**
 * Class CouldNotSendNotification.
 */
final class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when there's a bad request and an error is responded.
     *
     *
     * @throws JsonException
     */
    public static function telegramRespondedWithAnError(ClientException $exception): self
    {
        if (! $exception->hasResponse()) {
            return new self('Telegram responded with an error but no response body found');
        }

        $statusCode = $exception->getResponse()->getStatusCode();

        $result = json_decode($exception->getResponse()->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $description = $result->description ?? 'no description given';

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
}
