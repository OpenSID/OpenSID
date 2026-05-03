<?php

namespace Spatie\OneTimePasswords;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\OneTimePasswords\Livewire\OneTimePasswordComponent;
use Spatie\OneTimePasswords\Support\Config;
use Spatie\OneTimePasswords\Support\OriginInspector\OriginEnforcer;
use Spatie\OneTimePasswords\Support\PasswordGenerators\OneTimePasswordGenerator;

class OneTimePasswordsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('one-time-passwords')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigration('create_one_time_passwords_table');
    }

    public function packageRegistered(): void
    {
        $this->app->bind(OriginEnforcer::class, config('one-time-passwords.origin_enforcer'));

        $this->app->bind(OneTimePasswordGenerator::class, function () {
            $generator = Config::getPasswordGenerator();

            $generator->numberOfCharacters(config('one-time-passwords.password_length'));

            return $generator;
        });

        if (class_exists(Livewire::class)) {
            Livewire::component('one-time-password', OneTimePasswordComponent::class);
        }
    }
}
