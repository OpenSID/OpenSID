<?php

namespace Orchestra\Canvas\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Orchestra\Canvas\Core\Concerns\CodeGenerator;
use Orchestra\Canvas\Core\Concerns\TestGenerator;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Foundation/Console/ModelMakeCommand.php
 */
#[AsCommand(name: 'make:model', description: 'Create a new Eloquent model class')]
class ModelMakeCommand extends \Illuminate\Foundation\Console\ModelMakeCommand
{
    use CodeGenerator;
    use TestGenerator;
    use UsesGeneratorOverrides;

    /**
     * Create a new creator command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addGeneratorPresetOptions();
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    #[\Override]
    public function handle()
    {
        /** @phpstan-ignore return.type */
        return $this->generateCode() ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Run after code successfully generated.
     */
    protected function afterCodeHasBeenGenerated(): void
    {
        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('policy', true);
            $this->input->setOption('resource', true);
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('seed')) {
            $this->createSeeder();
        }

        if ($this->option('controller') || $this->option('resource') || $this->option('api')) {
            $this->createController();
        }

        if ($this->option('policy')) {
            $this->createPolicy();
        }
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    #[\Override]
    protected function createFactory()
    {
        $factory = Str::studly($this->getNameInput());

        $this->call('make:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->qualifyClass($this->getNameInput()),
            '--preset' => $this->option('preset'),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    #[\Override]
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->getNameInput())));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--fullpath' => true,
            '--preset' => $this->option('preset'),
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    #[\Override]
    protected function createSeeder()
    {
        $seeder = Str::studly(class_basename($this->getNameInput()));

        $this->call('make:seeder', [
            'name' => "{$seeder}Seeder",
            '--preset' => $this->option('preset'),
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    #[\Override]
    protected function createController()
    {
        $controller = Str::studly(class_basename($this->getNameInput()));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--preset' => $this->option('preset'),
        ]));
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    #[\Override]
    protected function createPolicy()
    {
        $policy = Str::studly(class_basename($this->getNameInput()));

        $this->call('make:policy', [
            'name' => "{$policy}Policy",
            '--model' => $this->qualifyClass($this->getNameInput()),
            '--preset' => $this->option('preset'),
        ]);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    #[\Override]
    protected function getDefaultNamespace($rootNamespace)
    {
        return rtrim($this->generatorPreset()->modelNamespace(), '\\');
    }

    /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return string
     */
    #[\Override]
    protected function qualifyModel(string $model)
    {
        return $this->qualifyModelUsingCanvas($model);
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    #[\Override]
    protected function getPath($name)
    {
        return $this->getPathUsingCanvas($name);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    #[\Override]
    protected function rootNamespace()
    {
        return $this->rootNamespaceUsingCanvas();
    }
}
