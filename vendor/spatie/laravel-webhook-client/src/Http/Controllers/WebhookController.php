<?php

namespace Spatie\WebhookClient\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookProcessor;

class WebhookController
{
    public function __invoke(Request $request, WebhookConfig $config)
    {
        return (new WebhookProcessor($request, $config))->process();
    }
}
