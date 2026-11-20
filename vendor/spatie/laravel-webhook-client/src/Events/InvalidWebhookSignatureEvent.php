<?php

namespace Spatie\WebhookClient\Events;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;

class InvalidWebhookSignatureEvent
{
    public function __construct(
        public Request $request,
        public WebhookConfig $config
    ) {
    }
}
