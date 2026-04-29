<?php

namespace Spatie\OneTimePasswords\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\OneTimePasswords\Models\OneTimePassword;

class OneTimePasswordSuccessfullyConsumed
{
    public function __construct(
        protected readonly Authenticatable $user,
        protected readonly OneTimePassword $oneTimePassword,
    ) {}
}
