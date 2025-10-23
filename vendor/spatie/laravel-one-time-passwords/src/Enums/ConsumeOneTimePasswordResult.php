<?php

namespace Spatie\OneTimePasswords\Enums;

use Illuminate\Support\Str;

enum ConsumeOneTimePasswordResult: string
{
    case Ok = 'ok';
    case NoOneTimePasswordsFound = 'noOneTimePasswordsFound';
    case IncorrectOneTimePassword = 'incorrectOneTimePassword';
    case DifferentOrigin = 'differentOrigin';
    case OneTimePasswordExpired = 'oneTimePasswordExpired';
    case RateLimitExceeded = 'rateLimitExceeded';

    public function isOk(): bool
    {
        return $this === self::Ok;
    }

    public function validationMessage(): string
    {
        $validationKey = Str::snake($this->value);

        return __("one-time-passwords::validation.{$validationKey}");
    }
}
