<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram\Traits;

use Closure;
use Illuminate\Support\Traits\Conditionable;
use JsonException;
use NotificationChannels\Telegram\Enums\ParseMode;

/**
 * Trait HasSharedLogic
 *
 * Provides shared functionality for Telegram message handling.
 *
 * @phpstan-type PayloadValue string|int|float|bool|array<string, mixed>|null
 * @phpstan-type Payload array<string, PayloadValue>
 */
trait HasSharedLogic
{
    use Conditionable;

    /** @var string|null Bot Token */
    public ?string $token = null;

    /** @var Payload Params payload */
    protected array $payload = [];

    /** @var list<array<string, mixed>> Keyboard Buttons */
    protected array $keyboards = [];

    /** @var list<array{text: string} & array<string, mixed>> Inline Keyboard Buttons */
    protected array $buttons = [];

    /** @var bool|null Condition for sending the message */
    private ?bool $sendCondition = null;

    /** @var Closure|null Callback function to handle exceptions */
    public ?Closure $exceptionHandler = null;

    /**
     * Set the recipient's Chat ID.
     *
     * @param  int|string  $chatId  The unique identifier for the target chat
     */
    public function to(int|string $chatId): static
    {
        $this->payload['chat_id'] = $chatId;

        return $this;
    }

    /**
     * Set the keyboard markup for the message.
     *
     * @param  array<string, mixed>  $markup  The keyboard markup array
     *
     * @throws JsonException When JSON encoding fails
     */
    public function keyboardMarkup(array $markup): static
    {
        $this->payload['reply_markup'] = json_encode($markup, JSON_THROW_ON_ERROR);

        return $this;
    }

    /**
     * Unset parse mode of the message.
     */
    public function normal(): static
    {
        unset($this->payload['parse_mode']);

        return $this;
    }

    /**
     * Set the parse mode of the message.
     *
     * @param  ParseMode|string  $mode  The parse mode to use
     */
    public function parseMode(ParseMode|string $mode): static
    {
        $this->payload['parse_mode'] = ($mode instanceof ParseMode) ? $mode->value : $mode;

        return $this;
    }

    /**
     * Add a normal keyboard button.
     *
     * @param  string  $text  The text to display on the button
     * @param  int  $columns  Number of columns for button layout
     * @param  bool  $requestContact  Whether to request user's contact
     * @param  bool  $requestLocation  Whether to request user's location
     *
     * @throws JsonException When JSON encoding fails
     */
    public function keyboard(
        string $text,
        int $columns = 2,
        bool $requestContact = false,
        bool $requestLocation = false
    ): static {
        $columns = max(1, $columns);

        $this->keyboards[] = [
            'text' => $text,
            'request_contact' => $requestContact,
            'request_location' => $requestLocation,
        ];

        $this->keyboardMarkup([
            'keyboard' => array_chunk($this->keyboards, $columns),
            'one_time_keyboard' => true,
            'resize_keyboard' => true,
        ]);

        return $this;
    }

    /**
     * Add an inline button with URL.
     *
     * @param  string  $text  The text to display on the button
     * @param  string  $url  The URL to open when button is pressed
     * @param  int  $columns  Number of columns for button layout
     *
     * @throws JsonException When JSON encoding fails
     */
    public function button(string $text, string $url, int $columns = 2): static
    {
        $this->buttons[] = [
            'text' => $text,
            'url' => $url,
        ];

        return $this->updateInlineKeyboard($columns);
    }

    /**
     * Add an inline button with callback data.
     *
     * @param  string  $text  The text to display on the button
     * @param  string  $callbackData  The data to send when button is pressed
     * @param  int  $columns  Number of columns for button layout
     *
     * @throws JsonException When JSON encoding fails
     */
    public function buttonWithCallback(string $text, string $callbackData, int $columns = 2): static
    {
        $this->buttons[] = [
            'text' => $text,
            'callback_data' => $callbackData,
        ];

        return $this->updateInlineKeyboard($columns);
    }

    /**
     * Add an inline button with web app.
     *
     * @param  string  $text  The text to display on the button
     * @param  string  $url  The URL of the Web App to open
     * @param  int  $columns  Number of columns for button layout
     *
     * @throws JsonException When JSON encoding fails
     */
    public function buttonWithWebApp(string $text, string $url, int $columns = 2): static
    {
        $this->buttons[] = [
            'text' => $text,
            'web_app' => ['url' => $url],
        ];

        return $this->updateInlineKeyboard($columns);
    }

    /**
     * Send the message silently. Users will receive a notification with no sound.
     *
     * @param  bool  $disable  Whether to disable the notification sound
     */
    public function disableNotification(bool $disable = true): static
    {
        $this->payload['disable_notification'] = $disable;

        return $this;
    }

    public function businessConnectionId(string $businessConnectionId): static
    {
        $this->payload['business_connection_id'] = $businessConnectionId;

        return $this;
    }

    public function messageThreadId(int $messageThreadId): static
    {
        $this->payload['message_thread_id'] = $messageThreadId;

        return $this;
    }

    public function directMessagesTopicId(int $directMessagesTopicId): static
    {
        $this->payload['direct_messages_topic_id'] = $directMessagesTopicId;

        return $this;
    }

    public function protectContent(bool $protect = true): static
    {
        $this->payload['protect_content'] = $protect;

        return $this;
    }

    public function allowPaidBroadcast(bool $allow = true): static
    {
        $this->payload['allow_paid_broadcast'] = $allow;

        return $this;
    }

    public function messageEffectId(string $messageEffectId): static
    {
        $this->payload['message_effect_id'] = $messageEffectId;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $replyParameters
     *
     * @throws JsonException
     */
    public function replyParameters(array $replyParameters): static
    {
        return $this->jsonPayload('reply_parameters', $replyParameters);
    }

    /**
     * @param  array<string, mixed>  $suggestedPostParameters
     *
     * @throws JsonException
     */
    public function suggestedPostParameters(array $suggestedPostParameters): static
    {
        return $this->jsonPayload('suggested_post_parameters', $suggestedPostParameters);
    }

    /**
     * Set the Bot Token. Overrides default bot token with the given value for this notification.
     *
     * @param  string  $token  The bot token to use
     */
    public function token(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Determine if bot token is given for this notification.
     *
     * @phpstan-assert-if-true string $this->token
     */
    public function hasToken(): bool
    {
        return $this->token !== null;
    }

    /**
     * Set additional options to pass to sendMessage method.
     *
     * @param  Payload  $options  Additional options
     */
    public function options(array $options): static
    {
        $this->payload = [...$this->payload, ...$options];

        return $this;
    }

    /**
     * Registers a callback function to handle exceptions.
     *
     * This method allows you to define a custom error handler,
     * which will be invoked if an exception occurs during the
     * notification process. The callback must be a valid Closure.
     *
     * @param  callable  $callback  The closure that will handle exceptions.
     */
    public function onError(callable $callback): self
    {
        $this->exceptionHandler = $callback instanceof Closure
            ? $callback
            : Closure::fromCallable($callback);

        return $this;
    }

    /**
     * Set a condition for sending the message.
     *
     * @param  bool|callable  $condition  The condition to evaluate
     */
    public function sendWhen(bool|callable $condition): static
    {
        $this->sendCondition = $this->when($condition, fn () => true, fn () => false);

        return $this;
    }

    /**
     * Determine if the message can be sent based on the condition.
     */
    public function canSend(): bool
    {
        return $this->sendCondition ?? true;
    }

    /**
     * Determine if chat id is not given.
     */
    public function toNotGiven(): bool
    {
        return ! isset($this->payload['chat_id']);
    }

    /**
     * Get payload value for given key.
     *
     * @param  string  $key  The key to retrieve from payload
     * @return PayloadValue The value from payload or null if not found
     */
    public function getPayloadValue(string $key): string|int|float|bool|array|null
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Get the complete payload as array.
     *
     * @return Payload
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return Payload
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Update the inline keyboard markup.
     *
     * @param  int  $columns  Number of columns for button layout
     *
     * @throws JsonException When JSON encoding fails
     */
    private function updateInlineKeyboard(int $columns): static
    {
        $columns = max(1, $columns);

        return $this->keyboardMarkup([
            'inline_keyboard' => array_chunk($this->buttons, $columns),
        ]);
    }

    /**
     * @param  array<string, mixed>|list<array<string, mixed>>  $value
     *
     * @throws JsonException
     */
    protected function jsonPayload(string $key, array $value): static
    {
        $this->payload[$key] = json_encode($value, JSON_THROW_ON_ERROR);

        return $this;
    }
}
