<?php

namespace Spatie\OneTimePasswords\Support\OriginInspector;

use Illuminate\Http\Request;
use Spatie\OneTimePasswords\Models\OneTimePassword;

class DefaultOriginEnforcer implements OriginEnforcer
{
    /**
     * @return array<string, (string|null)>
     */
    public function gatherProperties(Request $request): array
    {
        return [
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ];
    }

    public function verifyProperties(OneTimePassword $oneTimePassword, Request $request): bool
    {
        $requestProperties = $oneTimePassword->origin_properties ?? [];

        if ($requestProperties['ip'] !== $request->ip()) {
            return false;
        }

        if ($requestProperties['userAgent'] !== $request->userAgent()) {
            return false;
        }

        return true;
    }
}
