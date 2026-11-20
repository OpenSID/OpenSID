<?php

declare(strict_types=1);

namespace NotificationChannels\Telegram;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Utils;
use Illuminate\Support\Str;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Telegram.
 */
class Telegram
{
    /** Default Telegram Bot API Base URI.*/
    protected const string API_BASE_URI = 'https://api.telegram.org';

    protected string $apiBaseUri;

    public function __construct(
        protected ?string $token = null,
        protected HttpClient $http = new HttpClient,
        ?string $apiBaseUri = null
    ) {
        $this->setApiBaseUri($apiBaseUri ?? static::API_BASE_URI);
    }

    /**
     * Token getter.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Token setter.
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * API Base URI getter.
     */
    public function getApiBaseUri(): string
    {
        return $this->apiBaseUri;
    }

    /**
     * API Base URI setter.
     *
     * @return $this
     */
    public function setApiBaseUri(string $apiBaseUri): self
    {
        $this->apiBaseUri = rtrim($apiBaseUri, '/');

        return $this;
    }

    /**
     * Set HTTP Client.
     *
     * @return $this
     */
    public function setHttpClient(HttpClient $http): self
    {
        $this->http = $http;

        return $this;
    }

    /**
     * Send text message.
     *
     * <code>
     * $params = [
     *   'chat_id'                  => '',
     *   'text'                     => '',
     *   'parse_mode'               => '',
     *   'disable_web_page_preview' => '',
     *   'disable_notification'     => '',
     *   'reply_to_message_id'      => '',
     *   'reply_markup'             => '',
     * ];
     * </code>
     *
     * @see https://core.telegram.org/bots/api#sendmessage
     *
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendMessage(array $params): ?ResponseInterface
    {
        return $this->sendRequest('sendMessage', $params);
    }

    /**
     * Send File as Image or Document.
     *
     * @param  array<string, mixed>|list<array{name: string, contents: mixed, filename?: string}>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendFile(array $params, string $type, bool $multipart = false): ?ResponseInterface
    {
        return $this->sendRequest('send'.Str::studly($type), $params, $multipart);
    }

    /**
     * Send a Poll.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendPoll(array $params): ?ResponseInterface
    {
        return $this->sendRequest('sendPoll', $params);
    }

    /**
     * Send a Contact.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendContact(array $params): ?ResponseInterface
    {
        return $this->sendRequest('sendContact', $params);
    }

    /**
     * Get updates.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function getUpdates(array $params): ?ResponseInterface
    {
        return $this->sendRequest('getUpdates', $params);
    }

    /**
     * Send a Location.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendLocation(array $params): ?ResponseInterface
    {
        return $this->sendRequest('sendLocation', $params);
    }

    /**
     * Send a Venue.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendVenue(array $params): ?ResponseInterface
    {
        return $this->sendRequest('sendVenue', $params);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendDice(array $params): ?ResponseInterface
    {
        return $this->sendRequest('sendDice', $params);
    }

    /**
     * @param  array<string, mixed>|list<array{name: string, contents: mixed, filename?: string}>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendMediaGroup(array $params, bool $multipart = false): ?ResponseInterface
    {
        return $this->sendRequest('sendMediaGroup', $params, $multipart);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function sendChatAction(array $params): ?ResponseInterface
    {
        return $this->sendRequest('sendChatAction', $params);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function editMessageText(array $params): ?ResponseInterface
    {
        return $this->sendRequest('editMessageText', $params);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function editMessageCaption(array $params): ?ResponseInterface
    {
        return $this->sendRequest('editMessageCaption', $params);
    }

    /**
     * @param  array<string, mixed>|list<array{name: string, contents: mixed, filename?: string}>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function editMessageMedia(array $params, bool $multipart = false): ?ResponseInterface
    {
        return $this->sendRequest('editMessageMedia', $params, $multipart);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function editMessageReplyMarkup(array $params): ?ResponseInterface
    {
        return $this->sendRequest('editMessageReplyMarkup', $params);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function stopPoll(array $params): ?ResponseInterface
    {
        return $this->sendRequest('stopPoll', $params);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function deleteMessage(array $params): ?ResponseInterface
    {
        return $this->sendRequest('deleteMessage', $params);
    }

    /**
     * @param  array<string, mixed>  $params
     *
     * @throws CouldNotSendNotification
     */
    public function deleteMessages(array $params): ?ResponseInterface
    {
        return $this->sendRequest('deleteMessages', $params);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws InvalidArgumentException
     */
    public static function decodeResponse(ResponseInterface $response): array
    {
        /** @var array<string, mixed> $decodedResponse */
        $decodedResponse = Utils::jsonDecode($response->getBody()->getContents(), true);

        return $decodedResponse;
    }

    /**
     * Get HttpClient.
     */
    protected function httpClient(): HttpClient
    {
        return $this->http;
    }

    /**
     * Send an API request and return response.
     *
     * @param  array<string, mixed>|list<array{name: string, contents: mixed, filename?: string}>  $params
     *
     * @throws CouldNotSendNotification
     */
    protected function sendRequest(string $endpoint, array $params, bool $multipart = false): ?ResponseInterface
    {
        if (blank($this->token)) {
            throw CouldNotSendNotification::telegramBotTokenNotProvided('You must provide your telegram bot token to make any API requests.');
        }

        $apiUri = sprintf('%s/bot%s/%s', $this->apiBaseUri, $this->token, $endpoint);

        try {
            return $this->httpClient()->post($apiUri, [
                $multipart ? 'multipart' : 'form_params' => $params,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::telegramRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithTelegram($exception->getMessage());
        }
    }
}
