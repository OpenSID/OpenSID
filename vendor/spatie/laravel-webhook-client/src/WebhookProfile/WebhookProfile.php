<?php

namespace Spatie\WebhookClient\WebhookProfile;

use Illuminate\Http\Request;

interface WebhookProfile
{
    public function shouldProcess(Request $request): bool;
}
