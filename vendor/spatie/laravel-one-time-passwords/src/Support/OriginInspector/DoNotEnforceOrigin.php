<?php

namespace Spatie\OneTimePasswords\Support\OriginInspector;

use Illuminate\Http\Request;
use Spatie\OneTimePasswords\Models\OneTimePassword;

class DoNotEnforceOrigin implements OriginEnforcer
{
    public function gatherProperties(Request $request): array
    {
        return [];
    }

    public function verifyProperties(OneTimePassword $oneTimePassword, Request $request): bool
    {
        return true;
    }
}
