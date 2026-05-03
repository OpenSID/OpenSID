<?php

namespace Spatie\OneTimePasswords\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\OneTimePasswords\Enums\ConsumeOneTimePasswordResult;

class FailedToConsumeOneTimePassword
{
    public function __construct(
        public readonly Authenticatable $user,
        public readonly ConsumeOneTimePasswordResult $validationResult,
    ) {}
}
