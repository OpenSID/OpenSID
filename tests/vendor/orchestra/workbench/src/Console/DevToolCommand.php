<?php

namespace Orchestra\Workbench\Console;

use Composer\InstalledVersions;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Orchestra\Testbench\Foundation\Console\Actions\EnsureDirectoryExists;
use Orchestra\Testbench\Foundation\Console\Actions\GeneratesFile;
use Orchestra\Workbench\Actions\DumpComposerAutoloads;
use Orchestra\Workbench\Actions\ModifyComposer;
use Orchestra\Workbench\Events\InstallEnded;
use Orchestra\Workbench\Events\InstallStarted;
use Orchestra\Workbench\StubRegistrar;
use Orchestra\Workbench\Workbench;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\confirm;
use function Orchestra\Sidekick\join_paths;
use function Orchestra\Testbench\package_path;

#[AsCommand(name: 'workbench:devtool', description: 'Configure Workbench for package development')]
class DevToolCommand extends Command implements PromptsForMissingInput
{
    /**
     * Namespace prefix for Workbench environment.
     */
    protected string $workbenchNamespacePrefix = 'Workbench\\';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $filesystem)
    {
        $workingPath = package_path();

        event(new InstallStarted($this->input, $this->output, $this->components));

        $this->prepareWorkbenchNamespaces($filesystem, $workingPath);
        $this->prepareWorkbenchDirectories($filesystem, $workingPath);

        if ($this->option('install') === true) {
            $this->call('workbench:install', [
                '--force' => $this->option('force'),
                '--no-devtool' => true,
                '--basic' => $this->option('basic'),
            ]);
        }

        return tap(Command::SUCCESS, function ($exitCode) use ($workingPath) {
            event(new InstallEnded($this->input, $this->output, $this->components, $exitCode));

            (new DumpComposerAutoloads($workingPath))->handle();
        });
    }

    /**
     * Prepare workbench directories.
     */
    protected function prepareWorkbenchDirectories(Filesystem $filesystem, string $workingPath): void
    {
        $workbenchWorkingPath = join_paths($workingPath, 'workbench');

        (new EnsureDirectoryExists(
            filesystem: $filesystem,
            components: $this->components,
        ))->handle(
            Collection::make([
                join_paths('app', 'Models'),
                join_paths('database', 'factories'),
                join_paths('database', 'migrations'),
                join_paths('database', 'seeders'),
            ])->when(
                $this->option('basic') === false,
                fn ($directories) => $directories->push(...['routes', join_paths('resources', 'views')])
            )->map(static fn ($directory) => join_paths($workbenchWorkingPath, $directory))
        );

        $this->callSilently('make:provider', [
            'name' => 'WorkbenchServiceProvider',
            '--preset' => 'workbench',
            '--force' => (bool) $this->option('force'),
        ]);

        StubRegistrar::replaceInFile($filesystem, join_paths($workbenchWorkingPath, 'Providers', 'WorkbenchServiceProvider.php'));

        $this->prepareWorkbenchDatabaseSchema($filesystem, $workbenchWorkingPath);

        if ($this->option('basic') === false) {
            foreach (['api', 'console', 'web'] as $route) {
                (new GeneratesFile(
                    filesystem: $filesystem,
                    components: $this->components,
                    force: (bool) $this->option('force'),
                ))->handle(
                    (string) Workbench::stubFile("routes.{$route}"),
                    join_paths($workbenchWorkingPath, 'routes', "{$route}.php")
                );
            }
        }
    }

    /**
     * Prepare workbench namespace to `composer.json`.
     */
    protected function prepareWorkbenchNamespaces(Filesystem $filesystem, string $workingPath): void
    {
        (new ModifyComposer($workingPath))
            ->handle(fn (array $content) => $this->appendScriptsToComposer(
                $this->appendAutoloadDevToComposer($content, $filesystem), $filesystem
            ));

        Workbench::flushCachedClassAndNamespaces();
    }

    /**
     * Prepare workbench database schema including user model, factory and seeder.
     */
    protected function prepareWorkbenchDatabaseSchema(Filesystem $filesystem, string $workingPath): void
    {
        $this->callSilently('make:user-model', [
            '--preset' => 'workbench',
            '--force' => (bool) $this->option('force'),
        ]);

        StubRegistrar::replaceInFile($filesystem, join_paths($workingPath, 'app', 'Models', 'User.php'));

        $this->callSilently('make:user-factory', [
            '--preset' => 'workbench',
            '--force' => (bool) $this->option('force'),
        ]);

        StubRegistrar::replaceInFile($filesystem, join_paths($workingPath, 'database', 'factories', 'UserFactory.php'));

        (new GeneratesFile(
            filesystem: $filesystem,
            components: $this->components,
            force: (bool) $this->option('force'),
        ))->handle(
            (string) Workbench::stubFile('seeders.database'),
            join_paths($workingPath, 'database', 'seeders', 'DatabaseSeeder.php')
        );

        StubRegistrar::replaceInFile($filesystem, join_paths($workingPath, 'database', 'seeders', 'DatabaseSeeder.php'));
    }

    /**
     * Append `scripts` to `composer.json`.
     */
    protected function appendScriptsToComposer(array $content, Filesystem $filesystem): array
    {
        $hasScriptsSection = \array_key_exists('scripts', $content);
        $hasTestbenchDusk = InstalledVersions::isInstalled('orchestra/testbench-dusk');

        if (! $hasScriptsSection) {
            $content['scripts'] = [];
        }

        $postAutoloadDumpScripts = array_filter([
            '@clear',
            '@prepare',
            $hasTestbenchDusk ? '@dusk:install-chromedriver' : null,
        ]);

        if (! \array_key_exists('post-autoload-dump', $content['scripts'])) {
            $content['scripts']['post-autoload-dump'] = $postAutoloadDumpScripts;
        } else {
            $content['scripts']['post-autoload-dump'] = array_values(array_unique([
                ...$postAutoloadDumpScripts,
                ...Arr::wrap($content['scripts']['post-autoload-dump']),
            ]));
        }

        $content['scripts']['clear'] = '@php vendor/bin/testbench package:purge-skeleton --ansi';
        $content['scripts']['prepare'] = '@php vendor/bin/testbench package:discover --ansi';

        if ($hasTestbenchDusk) {
            $content['scripts']['dusk:install-chromedriver'] = '@php vendor/bin/dusk-updater detect --auto-update --ansi';
        }

        $content['scripts']['build'] = '@php vendor/bin/testbench workbench:build --ansi';
        $content['scripts']['serve'] = [
            'Composer\\Config::disableProcessTimeout',
            '@build',
            $hasTestbenchDusk && \defined('TESTBENCH_DUSK')
                ? '@php vendor/bin/testbench-dusk serve --ansi'
                : '@php vendor/bin/testbench serve --ansi',
        ];

        if (! \array_key_exists('lint', $content['scripts'])) {
            $lintScripts = [];

            if (InstalledVersions::isInstalled('laravel/pint')) {
                $lintScripts[] = '@php vendor/bin/pint --ansi';
            } elseif ($filesystem->isFile(Workbench::packagePath('pint.json'))) {
                $lintScripts[] = 'pint';
            }

            if (InstalledVersions::isInstalled('phpstan/phpstan')) {
                $lintScripts[] = '@php vendor/bin/phpstan analyse --verbose --ansi';
            }

            if (\count($lintScripts) > 0) {
                $content['scripts']['lint'] = $lintScripts;
            }
        }

        if (
            $filesystem->isFile(Workbench::packagePath('phpunit.xml'))
            || $filesystem->isFile(Workbench::packagePath('phpunit.xml.dist'))
        ) {
            if (! \array_key_exists('test', $content['scripts'])) {
                $content['scripts']['test'] = [
                    '@clear',
                    InstalledVersions::isInstalled('pestphp/pest')
                        ? '@php vendor/bin/pest'
                        : '@php vendor/bin/phpunit',
                ];
            }
        }

        return $content;
    }

    /**
     * Append `autoload-dev` to `composer.json`.
     */
    protected function appendAutoloadDevToComposer(array $content, Filesystem $filesystem): array
    {
        /** @var array{autoload-dev?: array{psr-4?: array<string, string>}} $content */
        if (! \array_key_exists('autoload-dev', $content)) {
            $content['autoload-dev'] = [];
        }

        /** @var array{autoload-dev: array{psr-4?: array<string, string>}} $content */
        if (! \array_key_exists('psr-4', $content['autoload-dev'])) {
            $content['autoload-dev']['psr-4'] = [];
        }

        if (confirm('Prefix with `Workbench` namespace?', default: true) === false) {
            $this->workbenchNamespacePrefix = '';
        }

        $namespaces = [
            'workbench/app/' => $this->workbenchNamespacePrefix.'App\\',
            'workbench/database/factories/' => $this->workbenchNamespacePrefix.'Database\\Factories\\',
            'workbench/database/seeders/' => $this->workbenchNamespacePrefix.'Database\\Seeders\\',
        ];

        $autoloads = array_flip($content['autoload-dev']['psr-4']);

        foreach ($namespaces as $path => $namespace) {
            if (! \array_key_exists($path, $autoloads)) {
                $content['autoload-dev']['psr-4'][$namespace] = $path;

                $this->components->task(\sprintf(
                    'Added [%s] for [%s] to Composer', $namespace, './'.rtrim($path, '/')
                ));
            } else {
                $this->components->twoColumnDetail(
                    \sprintf('Composer already contains [%s] path assigned to [%s] namespace', './'.rtrim($path, '/'), $autoloads[$path]),
                    '<fg=yellow;options=bold>SKIPPED</>'
                );
            }
        }

        return $content;
    }

    /**
     * Prompt the user for any missing arguments.
     *
     * @return void
     */
    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        $install = null;

        if ($input->getOption('skip-install') === true) {
            $install = false;
        } elseif (\is_null($input->getOption('install'))) {
            $install = confirm('Run Workbench installation?', true);
        }

        if (! \is_null($install)) {
            $input->setOption('install', $install);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite any existing files'],
            ['install', null, InputOption::VALUE_NEGATABLE, 'Run Workbench installation'],
            ['basic', null, InputOption::VALUE_NONE, 'Workbench installation without discovers and routes'],

            /** @deprecated */
            ['skip-install', null, InputOption::VALUE_NONE, 'Skipped Workbench installation (deprecated)'],
        ];
    }
}
