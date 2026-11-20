<?php

namespace Spatie\WebhookClient\WebhookResponse;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;
use Symfony\Component\HttpFoundation\Response;

interface RespondsToWebhook
{
    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response;
}
