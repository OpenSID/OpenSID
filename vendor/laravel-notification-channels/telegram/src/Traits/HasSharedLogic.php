<?php

namespace NotificationChannels\Telegram\Traits;

use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;

/**
 * Trait HasSharedLogic.
 */
trait HasSharedLogic
{
    use Conditionable;

    /** @var null|string Bot Token. */
    public ?string $token = null;

    /** @var array Params payload. */
    protected array $payload = [];

    /** @var array Keyboard Buttons. */
    protected array $keyboards = [];

    /** @var array Inline Keyboard Buttons. */
    protected array $buttons = [];

    /**
     * Recipient's Chat ID.
     *
     * @return static
     */
    public function to(int|string $chatId): self
    {
        $this->payload['chat_id'] = $chatId;

        return $this;
    }

    /**
     * sets reply markup for payload
     *
     *
     * @return static
     *
     * @throws \JsonException
     */
    public function keyboardMarkup(array $markup): self
    {
        $this->payload['reply_markup'] = json_encode($markup, JSON_THROW_ON_ERROR);

        return $this;
    }

    /**
     * unsets parse mode of the message.
     *
     * @return static
     */
    public function normal()
    {
        unset($this->payload['parse_mode']);

        return $this;
    }

    /**
     * Sets parse mode of the message.
     *
     * @return static
     */
    public function parseMode(?string $mode = null)
    {
        if (isset($mode) and ! in_array($mode, $allowed = ['Markdown', 'HTML', 'MarkdownV2'])) {
            throw new InvalidArgumentException("Invalid aggregate type [$mode], allowed types: [".implode(', ', $allowed).'].');
        }

        $this->payload['parse_mode'] = $mode;

        return $this;
    }

    /**
     * Add a normal keyboard button.
     *
     * @return static
     *
     * @throws \JsonException
     */
    public function keyboard(string $text, int $columns = 2, bool $request_contact = false, bool $request_location = false): self
    {
        $this->keyboards[] = compact('text', 'request_contact', 'request_location');

        $this->keyboardMarkup([
            'keyboard' => array_chunk($this->keyboards, $columns),
            'one_time_keyboard' => true, // Hide the keyboard after the user makes a selection
            'resize_keyboard' => true, // Allow the keyboard to be resized
        ]);

        return $this;
    }

    /**
     * Add an inline button.
     *
     * @return static
     *
     * @throws \JsonException
     */
    public function button(string $text, string $url, int $columns = 2): self
    {
        $this->buttons[] = compact('text', 'url');

        $this->keyboardMarkup([
            'inline_keyboard' => array_chunk($this->buttons, $columns),
        ]);

        return $this;
    }

    /**
     * Add an inline button with callback_data.
     *
     * @return static
     *
     * @throws \JsonException
     */
    public function buttonWithCallback(string $text, string $callback_data, int $columns = 2): self
    {
        $this->buttons[] = compact('text', 'callback_data');

        $this->keyboardMarkup([
            'inline_keyboard' => array_chunk($this->buttons, $columns),
        ]);

        return $this;
    }

    /**
     * Send the message silently.
     * Users will receive a notification with no sound.
     *
     * @return static
     */
    public function disableNotification(bool $disableNotification = true): self
    {
        $this->payload['disable_notification'] = $disableNotification;

        return $this;
    }

    /**
     * Bot Token.
     * Overrides default bot token with the given value for this notification.
     *
     * @return static
     */
    public function token(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Determine if bot token is given for this notification.
     */
    public function hasToken(): bool
    {
        return $this->token !== null;
    }

    /**
     * Additional options to pass to sendMessage method.
     *
     * @return static
     */
    public function options(array $options): self
    {
        $this->payload = array_merge($this->payload, $options);

        return $this;
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
     * @return null|mixed
     */
    public function getPayloadValue(string $key): mixed
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Returns params payload.
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * Convert the object into something JSON serializable.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
