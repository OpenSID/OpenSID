<?php

namespace Spatie\OneTimePasswords\Exceptions;

use Exception;

class InvalidConfig extends Exception
{
    public static function invalidModel(string $model): self
    {
        return new self("The configured model `{$model}` is not a valid model because it does not extend or is `Spatie\OneTimePasswords\Models\OneTimePassword`.");
    }

    public static function invalidNotification(mixed $notificationClass): self
    {
        return new self("The configured notification `{$notificationClass}` is not a valid notification class because it does not extend `Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification`.");
    }

    public static function invalidPasswordGenerator(mixed $generatorClass): self
    {
        return new self("The configured password generator `{$generatorClass}` is not a valid password generator class because it does not extend `Spatie\OneTimePasswords\Support\PasswordGenerators\OneTimePasswordGenerator`.");
    }
}
