<?php

namespace Spatie\WebhookClient\Exceptions;

use Exception;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;

class InvalidConfig extends Exception
{
    public static function couldNotFindConfig(string $notFoundConfigName): self
    {
        return new static("Could not find the configuration for `{$notFoundConfigName}`");
    }

    public static function invalidSignatureValidator(string $invalidSignatureValidator): self
    {
        $signatureValidatorInterface = SignatureValidator::class;

        return new static("`{$invalidSignatureValidator}` is not a valid signature validator class. A valid signature validator is a class that implements `{$signatureValidatorInterface}`.");
    }

    public static function invalidWebhookProfile(string $webhookProfile): self
    {
        $webhookProfileInterface = WebhookProfile::class;

        return new static("`{$webhookProfile}` is not a valid webhook profile class. A valid web hook profile is a class that implements `{$webhookProfileInterface}`.");
    }

    public static function invalidWebhookResponse(string $webhookResponse): self
    {
        $webhookResponseInterface = RespondsToWebhook::class;

        return new static("`{$webhookResponse}` is not a valid webhook response class. A valid webhook response is a class that implements `{$webhookResponseInterface}`.");
    }

    public static function invalidProcessWebhookJob(string $processWebhookJob): self
    {
        $abstractProcessWebhookJob = ProcessWebhookJob::class;

        return new static("`{$processWebhookJob}` is not a valid process webhook job class. A valid class should implement `{$abstractProcessWebhookJob}`.");
    }

    public static function signingSecretNotSet(): self
    {
        return new static('The webhook signing secret is not set. Make sure that the `signing_secret` config key is set to the correct value.');
    }

    public static function invalidPrunable(mixed $value): self
    {
        return new static("`{$value}` is not a valid amount of days.");
    }
}
