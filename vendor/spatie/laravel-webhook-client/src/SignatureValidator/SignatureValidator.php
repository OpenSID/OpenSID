<?php

namespace Spatie\WebhookClient\SignatureValidator;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;

interface SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool;
}
