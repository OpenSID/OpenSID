<?php

namespace OpenSID\LaravelFilemanager;

use Illuminate\Support\Facades\Gate;
use OpenSID\LaravelFilemanager\Console\HardenUploadsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelFilemanagerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filemanager')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('filemanager')
            ->hasCommand(HardenUploadsCommand::class);
    }

    public function packageBooted(): void
    {
        $this->loadFilemanagerTranslations();
        $this->registerDefaultGates();
        $this->publishPackageResources();
    }

    /**
     * Register translations namespace.
     */
    protected function loadFilemanagerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filemanager');
    }

    /**
     * Publish package resources: config, assets, translations/lang, and views.
     */
    protected function publishPackageResources(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Publish Config: php artisan vendor:publish --tag=filemanager-config
        $this->publishes([
            __DIR__.'/../config/filemanager.php' => config_path('filemanager.php'),
        ], 'filemanager-config');

        // Publish Assets: php artisan vendor:publish --tag=filemanager-assets
        $this->publishes([
            __DIR__.'/../resources/dist' => base_path(config('filemanager.assets_path', 'assets/vendor/filemanager')),
        ], 'filemanager-assets');

        // Publish Lang: php artisan vendor:publish --tag=filemanager-lang
        $this->publishes([
            __DIR__.'/../resources/lang' => lang_path('vendor/filemanager'),
        ], 'filemanager-lang');

        // Publish Views: php artisan vendor:publish --tag=filemanager-views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filemanager'),
        ], 'filemanager-views');
    }

    /**
     * Default permission wiring, driven entirely by config('filemanager.
     * permissions') — no host AuthServiceProvider code required. $context
     * is the module slug filemanager_authorize() stamped into the session
     * from the embedding page (see DialogController::resolveContext); null
     * when the host never calls it.
     *
     * A host can still override any of these Gate::define('filemanager.*')
     * calls itself (e.g. in its own AuthServiceProvider) — that simply
     * replaces the definition below, since Gate::define() is last-wins.
     */
    protected function registerDefaultGates(): void
    {
        Gate::define('filemanager.access', fn ($user = null, ?string $context = null) => $this->checkConfiguredAbility('access', $context, allowNullContext: true));
        Gate::define('filemanager.upload', fn ($user = null, ?string $context = null) => $this->checkConfiguredAbility('upload', $context));
        Gate::define('filemanager.delete', fn ($user = null, ?string $context = null) => $this->checkConfiguredAbility('delete', $context));
    }

    /**
     * config('filemanager.permissions.{key}') holds the "akses" level to
     * check (OpenSID's own can($akses, $modul) convention — 'b' baca, 'u'
     * ubah, 'h' hapus), or null to always allow regardless of context.
     */
    protected function checkConfiguredAbility(string $key, ?string $context, bool $allowNullContext = false): bool
    {
        $ability = config("filemanager.permissions.{$key}");

        if ($ability === null) {
            return true;
        }

        if ($allowNullContext && $context === null) {
            return true;
        }

        if (! function_exists('can')) {
            return false;
        }

        return (bool) can($ability, $context);
    }
}
