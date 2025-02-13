<?php

namespace Spatie\LaravelPackageTools\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;

class InstallCommand extends Command
{
    protected Package $package;

    public ?Closure $startWith = null;

    protected array $publishes = [];

    protected bool $askToRunMigrations = false;

    protected bool $copyServiceProviderInApp = false;

    protected ?string $starRepo = null;

    public ?Closure $endWith = null;

    public $hidden = true;

    public function __construct(Package $package)
    {
        $this->signature = $package->shortName() . ':install';

        $this->description = 'Install ' . $package->name;

        $this->package = $package;

        parent::__construct();
    }

    public function handle()
    {
        if ($this->startWith) {
            ($this->startWith)($this);
        }

        foreach ($this->publishes as $tag) {
            $name = str_replace('-', ' ', $tag);
            $this->comment("Publishing {$name}...");

            $this->callSilently("vendor:publish", [
                '--tag' => "{$this->package->shortName()}-{$tag}",
            ]);
        }

        if ($this->askToRunMigrations) {
            if ($this->confirm('Would you like to run the migrations now?')) {
                $this->comment('Running migrations...');

                $this->call('migrate');
            }
        }

        if ($this->copyServiceProviderInApp) {
            $this->comment('Publishing service provider...');

            $this->copyServiceProviderInApp();
        }

        if ($this->starRepo) {
            if ($this->confirm('Would you like to star our repo on GitHub?')) {
                $repoUrl = "https://github.com/{$this->starRepo}";

                if (PHP_OS_FAMILY == 'Darwin') {
                    exec("open {$repoUrl}");
                }
                if (PHP_OS_FAMILY == 'Windows') {
                    exec("start {$repoUrl}");
                }
                if (PHP_OS_FAMILY == 'Linux') {
                    exec("xdg-open {$repoUrl}");
                }
            }
        }

        $this->info("{$this->package->shortName()} has been installed!");

        if ($this->endWith) {
            ($this->endWith)($this);
        }
    }

    public function publish(string ...$tag): self
    {
        $this->publishes = array_merge($this->publishes, $tag);

        return $this;
    }

    public function publishConfigFile(): self
    {
        return $this->publish('config');
    }

    public function publishAssets(): self
    {
        return $this->publish('assets');
    }

    public function publishInertiaComponents(): self
    {
        return $this->publish('inertia-components');
    }

    public function publishMigrations(): self
    {
        return $this->publish('migrations');
    }

    public function askToRunMigrations(): self
    {
        $this->askToRunMigrations = true;

        return $this;
    }

    public function copyAndRegisterServiceProviderInApp(): self
    {
        $this->copyServiceProviderInApp = true;

        return $this;
    }

    public function askToStarRepoOnGitHub($vendorSlashRepoName): self
    {
        $this->starRepo = $vendorSlashRepoName;

        return $this;
    }

    public function startWith($callable): self
    {
        $this->startWith = $callable;

        return $this;
    }

    public function endWith($callable): self
    {
        $this->endWith = $callable;

        return $this;
    }

    protected function copyServiceProviderInApp(): self
    {
        $providerName = $this->package->publishableProviderName;

        if (! $providerName) {
            return $this;
        }

        $this->callSilent('vendor:publish', ['--tag' => $this->package->shortName() . '-provider']);

        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        if (intval(app()->version()) < 11 || ! file_exists(base_path('bootstrap/providers.php'))) {
            $appConfig = file_get_contents(config_path('app.php'));
        } else {
            $appConfig = file_get_contents(base_path('bootstrap/providers.php'));
        }

        $class = '\\Providers\\' . Str::replace('/', '\\', $providerName) . '::class';

        if (Str::contains($appConfig, $namespace . $class)) {
            return $this;
        }

        if (intval(app()->version()) < 11 || ! file_exists(base_path('bootstrap/providers.php'))) {
            file_put_contents(config_path('app.php'), str_replace(
                "{$namespace}\\Providers\\BroadcastServiceProvider::class,",
                "{$namespace}\\Providers\\BroadcastServiceProvider::class," . PHP_EOL . "        {$namespace}{$class},",
                $appConfig
            ));
        } else {
            file_put_contents(base_path('bootstrap/providers.php'), str_replace(
                "{$namespace}\\Providers\\AppServiceProvider::class,",
                "{$namespace}\\Providers\\AppServiceProvider::class," . PHP_EOL . "        {$namespace}{$class},",
                $appConfig
            ));
        }

        file_put_contents(app_path('Providers/' . $providerName . '.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/' . $providerName . '.php'))
        ));

        return $this;
    }
}
