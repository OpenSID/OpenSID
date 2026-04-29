<?php

namespace Spatie\OneTimePasswords\Support\OriginInspector;

use Illuminate\Http\Request;
use Spatie\OneTimePasswords\Models\OneTimePassword;

interface OriginEnforcer
{
    /** @return array<string, string|int> */
    public function gatherProperties(Request $request): array;

    public function verifyProperties(OneTimePassword $oneTimePassword, Request $request): bool;
}
