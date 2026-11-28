<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class MatchesCurrentPassword implements ValidationRule
{
    public function __construct(private Authenticatable $user) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pwMasihMD5 = strlen($this->user->getAuthPassword()) === 32
            && stripos($this->user->getAuthPassword(), '$') === false;

        $check = $pwMasihMD5
            ? md5($value) === $this->user->getAuthPassword()
            : Hash::check($value, $this->user->getAuthPassword());

        if (! $check) {
            $fail('Sandi gagal diganti, <b>Sandi Lama</b> yang Anda masukkan tidak sesuai.');
        }
    }
}
