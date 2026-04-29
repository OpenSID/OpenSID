<?php

namespace Spatie\OneTimePasswords\Support;

use Spatie\OneTimePasswords\Exceptions\InvalidActionClass;
use Spatie\OneTimePasswords\Exceptions\InvalidConfig;
use Spatie\OneTimePasswords\Models\OneTimePassword;
use Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification;
use Spatie\OneTimePasswords\Support\PasswordGenerators\OneTimePasswordGenerator;

class Config
{
    /**
     * @return class-string<OneTimePassword>
     */
    public static function oneTimePasswordModel(): string
    {
        $modelClass = config('one-time-passwords.model');

        if (! is_a($modelClass, OneTimePassword::class, true)) {
            throw InvalidConfig::invalidModel($modelClass);
        }

        return $modelClass;
    }

    public static function oneTimePasswordNotificationClass(): string
    {
        $notificationClass = config('one-time-passwords.notification');

        if (! is_a($notificationClass, OneTimePasswordNotification::class, true)) {
            throw InvalidConfig::invalidNotification($notificationClass);
        }

        return $notificationClass;
    }

    /**
     * @template T
     *
     * @param  class-string<T>  $actionBaseClass
     * @return T
     */
    public static function getAction(string $actionName, string $actionBaseClass)
    {
        $actionClass = self::getActionClass($actionName, $actionBaseClass);

        return app($actionClass);
    }

    public static function getActionClass(string $actionName, string $actionBaseClass): string
    {
        $actionClass = config("one-time-passwords.actions.{$actionName}");

        self::ensureValidActionClass($actionName, $actionBaseClass, $actionClass);

        return $actionClass;
    }

    public static function getPasswordGenerator(): OneTimePasswordGenerator
    {
        $generatorClass = config('one-time-passwords.password_generator');

        if (! is_a($generatorClass, OneTimePasswordGenerator::class, true)) {
            throw InvalidConfig::invalidPasswordGenerator($generatorClass);
        }

        return app($generatorClass);
    }

    protected static function ensureValidActionClass(string $actionName, string $actionBaseClass, string $actionClass): void
    {
        if (! is_a($actionClass, $actionBaseClass, true)) {
            throw InvalidActionClass::make($actionName, $actionBaseClass, $actionClass);
        }
    }
}
