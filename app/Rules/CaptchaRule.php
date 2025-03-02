<?php

namespace App\Rules;

use App\Libraries\Captcha;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Captcha::check($value)) {
            $fail(__('Captcha yang dimasukkan tidak valid.'));
        }
    }
}
