<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\ValidationRule;

class NotInPasswordHistory implements ValidationRule
{
    public function __construct(
        private ?Authenticatable $authenticatable,
        private ?int $limit = null
    ) {}

    /**
     * {@inheritDoc}
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->authenticatable) {
            return;
        }

        if ($this->authenticatable->wasPasswordUsedBefore($value, $this->limit)) {
            $fail("Sandi gagal diganti, <b>Kata sandi</b> pernah digunakan sebelumnya.");
        }
    }
}
