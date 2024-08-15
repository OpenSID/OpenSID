<?php

namespace App\Services\Auth\Traits;

use App\Traits\ProvidesConvenienceMethods;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait LoginRequest
{
    use ProvidesConvenienceMethods;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules()
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function authenticate($extra = [])
    {
        $this->ensureIsNotRateLimited();

        $data = $this->validated($request = request(), $this->rules());
        $data = except($data, 'g-recaptcha-response');

        if (! Auth::guard($this->guard)->attempt([...$data, ...$extra])) {
            RateLimiter::hit($this->throttleKey(), config_item('lockout_time'));

            try {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            } catch (ValidationException $e) {
                return $this->invalid($request, $e);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), config_item('maximum_login_attempts'))) {
            return;
        }

        event(new Lockout($request = request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        try {
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        } catch (ValidationException $e) {
            return $this->invalid($request, $e);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey()
    {
        return Str::transliterate(Str::lower(request()->string('email')).'|'.request()->ip());
    }
}
