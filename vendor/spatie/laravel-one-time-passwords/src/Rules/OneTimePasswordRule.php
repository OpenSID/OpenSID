<?php

namespace Spatie\OneTimePasswords\Rules;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\ValidationRule;
use Spatie\OneTimePasswords\Actions\ConsumeOneTimePasswordAction;

class OneTimePasswordRule implements ValidationRule
{
    public function __construct(protected Authenticatable $user) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = app(ConsumeOneTimePasswordAction::class)->execute(
            $this->user,
            $value,
            request(),
        );

        if ($result->isOk()) {
            return;
        }

        $fail($result->validationMessage());
    }
}
