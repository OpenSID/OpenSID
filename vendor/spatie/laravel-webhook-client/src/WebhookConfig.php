<?php

namespace Spatie\WebhookClient;

use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;
use Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;

class WebhookConfig
{
    public string $name;

    public string $signingSecret;

    public string $signatureHeaderName;

    public SignatureValidator $signatureValidator;

    public WebhookProfile $webhookProfile;

    public RespondsToWebhook $webhookResponse;

    public string $webhookModel;

    public array | string $storeHeaders;

    public bool $storeAttachments;

    public string $processWebhookJobClass;

    public function __construct(array $properties)
    {
        $this->name = $properties['name'];

        $this->signingSecret = $properties['signing_secret'] ?? '';

        $this->signatureHeaderName = $properties['signature_header_name'] ?? '';

        if (! is_subclass_of($properties['signature_validator'], SignatureValidator::class)) {
            throw InvalidConfig::invalidSignatureValidator($properties['signature_validator']);
        }
        $this->signatureValidator = app($properties['signature_validator']);

        if (! is_subclass_of($properties['webhook_profile'], WebhookProfile::class)) {
            throw InvalidConfig::invalidWebhookProfile($properties['webhook_profile']);
        }
        $this->webhookProfile = app($properties['webhook_profile']);

        $webhookResponseClass = $properties['webhook_response'] ?? DefaultRespondsTo::class;
        if (! is_subclass_of($webhookResponseClass, RespondsToWebhook::class)) {
            throw InvalidConfig::invalidWebhookResponse($webhookResponseClass);
        }
        $this->webhookResponse = app($webhookResponseClass);

        $this->webhookModel = $properties['webhook_model'];

        $this->storeHeaders = $properties['store_headers'] ?? [];

        $this->storeAttachments = $properties['store_attachments'] ?? true;

        if (! is_subclass_of($properties['process_webhook_job'], ProcessWebhookJob::class)) {
            throw InvalidConfig::invalidProcessWebhookJob($properties['process_webhook_job']);
        }

        $this->processWebhookJobClass = $properties['process_webhook_job'];
    }
}
