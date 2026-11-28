<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram;

use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\Contracts\TelegramSenderContract;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-type TelegramResponse array<string|int, mixed>
 */
class TelegramChannel
{
    public function __construct(
        private readonly Dispatcher $dispatcher
    ) {}

    /**
     * @return TelegramResponse|null
     *
     * @throws CouldNotSendNotification|InvalidArgumentException
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        if (! method_exists($notification, 'toTelegram')) {
            return null;
        }

        $message = $notification->toTelegram($notifiable);

        if (is_string($message)) {
            $message = TelegramMessage::create($message);
        }

        if (! ($message instanceof TelegramBase && $message instanceof TelegramSenderContract)) {
            return null;
        }

        if (! $message->canSend()) {
            return null;
        }

        $recipient = $this->resolveRecipient($message, $notifiable, $notification);

        if ($recipient === null) {
            return null;
        }

        $message->to($recipient);

        if ($message->hasToken()) {
            $message->telegram->setToken($message->token);
        }

        try {
            $response = $message->send();
        } catch (CouldNotSendNotification $exception) {
            $data = [
                'to' => $message->getPayloadValue('chat_id'),
                'request' => $message->toArray(),
                'exception' => $exception,
            ];

            $message->exceptionHandler?->__invoke($data);

            $this->dispatcher->dispatch(
                new NotificationFailed($notifiable, $notification, 'telegram', $data)
            );

            throw $exception;
        }

        return $response instanceof ResponseInterface
            ? Telegram::decodeResponse($response)
            : $response;
    }

    private function resolveRecipient(
        TelegramBase $message,
        mixed $notifiable,
        Notification $notification
    ): int|string|null {
        $chatId = $this->chatIdValue($message->getPayloadValue('chat_id'));

        if ($chatId !== null) {
            return $chatId;
        }

        if (! is_object($notifiable) || ! method_exists($notifiable, 'routeNotificationFor')) {
            return null;
        }

        return $this->chatIdValue(
            $notifiable->routeNotificationFor('telegram', $notification)
        ) ?? $this->chatIdValue(
            $notifiable->routeNotificationFor(self::class, $notification)
        );
    }

    private function chatIdValue(mixed $value): int|string|null
    {
        return is_int($value) || is_string($value) ? $value : null;
    }
}
