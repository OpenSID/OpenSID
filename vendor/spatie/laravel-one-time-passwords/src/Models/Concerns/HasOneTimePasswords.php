<?php

namespace Spatie\OneTimePasswords\Models\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\OneTimePasswords\Actions\ConsumeOneTimePasswordAction;
use Spatie\OneTimePasswords\Actions\CreateOneTimePasswordAction;
use Spatie\OneTimePasswords\Enums\ConsumeOneTimePasswordResult;
use Spatie\OneTimePasswords\Exceptions\InvalidConfig;
use Spatie\OneTimePasswords\Models\OneTimePassword;
use Spatie\OneTimePasswords\Support\Config;

/** @mixin Model&Authenticatable */
trait HasOneTimePasswords
{
    /**
     * @return MorphMany<OneTimePassword, Model>
     *
     * @throws InvalidConfig
     */
    public function oneTimePasswords(): MorphMany
    {
        $modelClass = Config::oneTimePasswordModel();

        return $this->morphMany($modelClass, 'authenticatable');
    }

    public function deleteAllOneTimePasswords(): void
    {
        $this->oneTimePasswords()->delete();
    }

    public function createOneTimePassword(?int $expiresInMinutes = null): OneTimePassword
    {
        $action = Config::getAction('create_one_time_password', CreateOneTimePasswordAction::class);

        $expiresInMinutes ??= config('one-time-passwords.default_expires_in_minutes');

        return $action->execute($this, $expiresInMinutes);
    }

    public function sendOneTimePassword(?int $expiresInMinutes = null): self
    {
        $oneTimePassword = $this->createOneTimePassword($expiresInMinutes);

        $notificationClass = Config::oneTimePasswordNotificationClass();
        $this->notify(new $notificationClass($oneTimePassword));

        return $this;
    }

    public function consumeOneTimePassword(string $password): ConsumeOneTimePasswordResult
    {
        $action = Config::getAction('consume_one_time_password', ConsumeOneTimePasswordAction::class);

        return $action->execute($this, $password, request());
    }

    public function attemptLoginUsingOneTimePassword(string $password, bool $remember = false): ConsumeOneTimePasswordResult
    {
        $result = $this->consumeOneTimePassword($password);

        if ($result->isOk()) {
            auth()->login($this, $remember);
        }

        return $result;
    }
}
