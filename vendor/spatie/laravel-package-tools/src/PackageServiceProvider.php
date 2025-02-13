<?php

namespace Spatie\LaravelPackageTools;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;

abstract class PackageServiceProvider extends ServiceProvider
{
    protected Package $package;

    abstract public function configurePackage(Package $package): void;

    /** @throws InvalidPackage */
    public function register()
    {
        $this->registeringPackage();

        $this->package = $this->newPackage();
        $this->package->setBasePath($this->getPackageBaseDir());

        $this->configurePackage($this->package);
        if (empty($this->package->name)) {
            throw InvalidPackage::nameIsRequired();
        }

        $this->registerConfigs();
        $this->packageRegistered();

        return $this;
    }

    public function registeringPackage()
    {
    }

    public function newPackage(): Package
    {
        return new Package();
    }

    public function registerConfigs()
    {
        if (empty($this->package->configFileNames)) {
            return;
        }

        foreach ($this->package->configFileNames as $configFileName) {
            $this->mergeConfigFrom($this->package->basePath("/../config/{$configFileName}.php"), $configFileName);
        }
    }

    public function packageRegistered()
    {
    }

    public function boot()
    {
        $this->bootingPackage();

        $this
            ->bootPackageAssets()
            ->bootPackageCommands()
            ->bootPackageConsoleCommands()
            ->bootPackageConfigs()
            ->bootPackageInertia()
            ->bootPackageMigrations()
            ->bootPackageProviders()
            ->bootPackageRoutes()
            ->bootPackageTranslations()
            ->bootPackageViews()
            ->bootPackageViewComponents()
            ->bootPackageViewComposers()
            ->bootPackageViewSharedData();

        $this->packageBooted();

        return $this;
    }

    public function bootingPackage()
    {
    }

    public function packageBooted()
    {
    }

    protected function getPackageBaseDir(): string
    {
        $reflector = new ReflectionClass(get_class($this));

        return dirname($reflector->getFileName());
    }

    public function packageView(?string $namespace): ?string
    {
        return is_null($namespace)
            ? $this->package->shortName()
            : $this->package->viewNamespace;
    }

    protected function bootPackageAssets(): static
    {
        if (! $this->package->hasAssets || ! $this->app->runningInConsole()) {
            return $this;
        }

        $vendorAssets = $this->package->basePath('/../resources/dist');
        $appAssets = public_path("vendor/{$this->package->shortName()}");

        $this->publishes([$vendorAssets => $appAssets], "{$this->package->shortName()}-assets");

        return $this;
    }

    protected function bootPackageCommands(): self
    {
        if (empty($this->package->commands)) {
            return $this;
        }

        $this->commands($this->package->commands);

        return $this;
    }

    protected function bootPackageConsoleCommands(): self
    {
        if (empty($this->package->consoleCommands) || ! $this->app->runningInConsole()) {
            return $this;
        }

        $this->commands($this->package->consoleCommands);

        return $this;
    }

    protected function bootPackageConfigs(): self
    {
        if ($this->app->runningInConsole()) {
            foreach ($this->package->configFileNames as $configFileName) {
                $vendorConfig = $this->package->basePath("/../config/{$configFileName}.php");
                $appConfig = config_path("{$configFileName}.php");

                $this->publishes([$vendorConfig => $appConfig], "{$this->package->shortName()}-config");
            }
        }

        return $this;
    }

    protected function bootPackageInertia(): self
    {
        if (! $this->package->hasInertiaComponents) {
            return $this;
        }

        $namespace = $this->package->viewNamespace;
        $directoryName = Str::of($this->packageView($namespace))->studly()->remove('-')->value();
        $vendorComponents = $this->package->basePath('/../resources/js/Pages');
        $appComponents = base_path("resources/js/Pages/{$directoryName}");

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [$vendorComponents => $appComponents],
                "{$this->packageView($namespace)}-inertia-components"
            );
        }

        return $this;
    }

    protected function bootPackageMigrations(): self
    {
        if ($this->package->discoversMigrations) {
            $this->discoverPackageMigrations();

            return $this;
        }

        $now = Carbon::now();

        foreach ($this->package->migrationFileNames as $migrationFileName) {
            $vendorMigration = $this->package->basePath("/../database/migrations/{$migrationFileName}.php");
            $appMigration = $this->generateMigrationName($migrationFileName, $now->addSecond());

            // Support for the .stub file extension
            if (! file_exists($vendorMigration)) {
                $vendorMigration .= '.stub';
            }

            if ($this->app->runningInConsole()) {
                $this->publishes(
                    [$vendorMigration => $appMigration],
                    "{$this->package->shortName()}-migrations"
                );
            }

            if ($this->package->runsMigrations) {
                $this->loadMigrationsFrom($vendorMigration);
            }
        }

        return $this;
    }

    protected function bootPackageProviders(): self
    {
        if (! $this->package->publishableProviderName || ! $this->app->runningInConsole()) {
            return $this;
        }

        $providerName = $this->package->publishableProviderName;
        $vendorProvider = $this->package->basePath("/../resources/stubs/{$providerName}.php.stub");
        $appProvider = base_path("app/Providers/{$providerName}.php");

        $this->publishes([$vendorProvider => $appProvider], "{$this->package->shortName()}-provider");

        return $this;
    }

    protected function bootPackageRoutes(): self
    {
        if (empty($this->package->routeFileNames)) {
            return $this;
        }

        foreach ($this->package->routeFileNames as $routeFileName) {
            $this->loadRoutesFrom("{$this->package->basePath('/../routes/')}{$routeFileName}.php");
        }

        return $this;
    }

    protected function bootPackageTranslations(): self
    {
        if (! $this->package->hasTranslations) {
            return $this;
        }

        $vendorTranslations = $this->package->basePath('/../resources/lang');
        $appTranslations = (function_exists('lang_path'))
            ? lang_path("vendor/{$this->package->shortName()}")
            : resource_path("lang/vendor/{$this->package->shortName()}");

        $this->loadTranslationsFrom($vendorTranslations, $this->package->shortName());

        $this->loadJsonTranslationsFrom($vendorTranslations);
        $this->loadJsonTranslationsFrom($appTranslations);

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [$vendorTranslations => $appTranslations],
                "{$this->package->shortName()}-translations"
            );
        }

        return $this;
    }

    protected function bootPackageViews(): self
    {
        if (! $this->package->hasViews) {
            return $this;
        }

        $namespace = $this->package->viewNamespace;
        $vendorViews = $this->package->basePath('/../resources/views');
        $appViews = base_path("resources/views/vendor/{$this->packageView($namespace)}");

        $this->loadViewsFrom($vendorViews, $this->package->viewNamespace());

        if ($this->app->runningInConsole()) {
            $this->publishes([$vendorViews => $appViews], "{$this->packageView($namespace)}-views");
        }

        return $this;
    }

    protected function bootPackageViewComponents(): self
    {
        if (empty($this->package->viewComponents)) {
            return $this;
        }

        foreach ($this->package->viewComponents as $componentClass => $prefix) {
            $this->loadViewComponentsAs($prefix, [$componentClass]);
        }

        if ($this->app->runningInConsole()) {
            $vendorComponents = $this->package->basePath('/Components');
            $appComponents = base_path("app/View/Components/vendor/{$this->package->shortName()}");

            $this->publishes([$vendorComponents => $appComponents], "{$this->package->name}-components");
        }

        return $this;
    }

    protected function bootPackageViewComposers(): self
    {
        if (empty($this->package->viewComposers)) {
            return $this;
        }

        foreach ($this->package->viewComposers as $viewName => $viewComposer) {
            View::composer($viewName, $viewComposer);
        }

        return $this;
    }

    protected function bootPackageViewSharedData(): self
    {
        if (empty($this->package->sharedViewData)) {
            return $this;
        }

        foreach ($this->package->sharedViewData as $name => $value) {
            View::share($name, $value);
        }

        return $this;
    }

    protected function discoverPackageMigrations(): void
    {
        $now = Carbon::now();
        $migrationsPath = trim($this->package->migrationsPath, '/');

        $files = (new Filesystem())->files($this->package->basePath("/../{$migrationsPath}"));

        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $migrationFileName = Str::replace(['.stub', '.php'], '', $file->getFilename());

            $appMigration = $this->generateMigrationName($migrationFileName, $now->addSecond());

            if ($this->app->runningInConsole()) {
                $this->publishes(
                    [$filePath => $appMigration],
                    "{$this->package->shortName()}-migrations"
                );
            }

            if ($this->package->runsMigrations) {
                $this->loadMigrationsFrom($filePath);
            }
        }
    }

    protected function generateMigrationName(string $migrationFileName, Carbon $now): string
    {
        $migrationsPath = 'migrations/' . dirname($migrationFileName) . '/';
        $migrationFileName = basename($migrationFileName);

        $len = strlen($migrationFileName) + 4;

        if (Str::contains($migrationFileName, '/')) {
            $migrationsPath .= Str::of($migrationFileName)->beforeLast('/')->finish('/');
            $migrationFileName = Str::of($migrationFileName)->afterLast('/');
        }

        foreach (glob(database_path("{$migrationsPath}*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName . '.php')) {
                return $filename;
            }
        }

        $timestamp = $now->format('Y_m_d_His');
        $migrationFileName = Str::of($migrationFileName)->snake()->finish('.php');

        return database_path($migrationsPath . $timestamp . '_' . $migrationFileName);
    }
}
