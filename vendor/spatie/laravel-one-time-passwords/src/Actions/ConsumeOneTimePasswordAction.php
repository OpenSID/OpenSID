<?php

namespace Spatie\OneTimePasswords\Actions;

use Carbon\CarbonInterval;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Timebox;
use Spatie\OneTimePasswords\Enums\ConsumeOneTimePasswordResult;
use Spatie\OneTimePasswords\Events\FailedToConsumeOneTimePassword;
use Spatie\OneTimePasswords\Events\OneTimePasswordSuccessfullyConsumed;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;
use Spatie\OneTimePasswords\Models\OneTimePassword;
use Spatie\OneTimePasswords\Support\OriginInspector\OriginEnforcer;

class ConsumeOneTimePasswordAction
{
    public function __construct(
        protected OriginEnforcer $originEnforcer,
    ) {}

    /**
     * @param  Authenticatable&HasOneTimePasswords  $user
     */
    public function execute(
        Authenticatable $user,
        string $password,
        Request $request
    ): ConsumeOneTimePasswordResult {
        return (new Timebox)->call(
            callback: fn () => $this->consumeOneTimePassword($user, $password, $request),
            microseconds: CarbonInterval::milliseconds(100)->microseconds,
        );
    }

    /**
     * @param  Authenticatable&HasOneTimePasswords  $user
     */
    protected function consumeOneTimePassword(
        Authenticatable $user,
        string $password,
        Request $request
    ): ConsumeOneTimePasswordResult {
        $oneTimePasswords = $this->getAllOneTimePasswordsForUser($user);

        if (! $this->allowedByRateLimit($user)) {
            return $this->onFailedToValidate($user, ConsumeOneTimePasswordResult::RateLimitExceeded);
        }

        if (! count($oneTimePasswords)) {
            return $this->onFailedToValidate($user, ConsumeOneTimePasswordResult::NoOneTimePasswordsFound);
        }

        $oneTimePassword = $oneTimePasswords->firstWhere('password', $password);

        if (! $oneTimePassword) {
            return $this->onFailedToValidate($user, ConsumeOneTimePasswordResult::IncorrectOneTimePassword);
        }

        if ($oneTimePassword->isExpired()) {
            return $this->onFailedToValidate($user, ConsumeOneTimePasswordResult::OneTimePasswordExpired);
        }

        $originPropertiesAreValid = $this->originEnforcer->verifyProperties(
            $oneTimePassword,
            $request,
        );

        if (! $originPropertiesAreValid) {
            return $this->onFailedToValidate($user, ConsumeOneTimePasswordResult::DifferentOrigin);
        }

        $this->onSuccessfullyValidated($user, $oneTimePassword);

        return ConsumeOneTimePasswordResult::Ok;
    }

    /**
     * @param  Authenticatable&HasOneTimePasswords  $user
     * @return Collection<OneTimePassword>
     */
    protected function getAllOneTimePasswordsForUser(Authenticatable $user): Collection
    {
        return $user->oneTimePasswords()->get();
    }

    protected function validateRequestProperties(
        OneTimePassword $oneTimePassword,
        Request $request,
    ): bool {
        if ($request->userAgent() !== $oneTimePassword->origin_properties['userAgent']) {
            return false;
        }

        if ($request->ip() !== $oneTimePassword->origin_properties['ip']) {
            return false;
        }

        return true;
    }

    protected function onSuccessfullyValidated(Authenticatable $user, OneTimePassword $oneTimePassword): void
    {
        event(new OneTimePasswordSuccessfullyConsumed($user, $oneTimePassword));

        $oneTimePassword->delete();
    }

    protected function onFailedToValidate(
        Authenticatable $user,
        ConsumeOneTimePasswordResult $validationResult
    ): ConsumeOneTimePasswordResult {
        event(new FailedToConsumeOneTimePassword($user, $validationResult));

        return $validationResult;
    }

    protected function allowedByRateLimit(Authenticatable $user): bool
    {
        return RateLimiter::attempt(
            "consume-one-time-password-attempt:{$user->getKey()}",
            maxAttempts: config('one-time-passwords.rate_limit_attempts.max_attempts_per_user'),
            callback: function () {},
            decaySeconds: config('one-time-passwords.rate_limit_attempts.time_window_in_seconds'),
        );
    }
}
