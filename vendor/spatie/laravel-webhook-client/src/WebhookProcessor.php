<?php

namespace Spatie\WebhookClient;

use Exception;
use Illuminate\Http\Request;
use Spatie\WebhookClient\Events\InvalidWebhookSignatureEvent;
use Spatie\WebhookClient\Exceptions\InvalidWebhookSignature;
use Spatie\WebhookClient\Models\WebhookCall;
use Symfony\Component\HttpFoundation\Response;

class WebhookProcessor
{
    public function __construct(
        protected Request $request,
        protected WebhookConfig $config
    ) {
    }

    public function process(): Response
    {
        $this->ensureValidSignature();

        if (! $this->config->webhookProfile->shouldProcess($this->request)) {
            return $this->createResponse();
        }

        $webhookCall = $this->storeWebhook();

        $this->processWebhook($webhookCall);

        return $this->createResponse();
    }

    protected function ensureValidSignature(): self
    {
        if (! $this->config->signatureValidator->isValid($this->request, $this->config)) {
            event(new InvalidWebhookSignatureEvent($this->request, $this->config));

            throw InvalidWebhookSignature::make();
        }

        return $this;
    }

    protected function storeWebhook(): WebhookCall
    {
        return $this->config->webhookModel::storeWebhook($this->config, $this->request);
    }

    protected function processWebhook(WebhookCall $webhookCall): void
    {
        try {
            $job = new $this->config->processWebhookJobClass($webhookCall);

            $webhookCall->clearException();

            dispatch($job);
        } catch (Exception $exception) {
            $webhookCall->saveException($exception);

            throw $exception;
        }
    }

    protected function createResponse(): Response
    {
        return $this->config->webhookResponse->respondToValidWebhook($this->request, $this->config);
    }
}
