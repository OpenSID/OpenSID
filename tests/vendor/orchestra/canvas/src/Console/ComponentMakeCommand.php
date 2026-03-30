<?php

namespace Orchestra\Canvas\Console;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns\CodeGenerator;
use Orchestra\Canvas\Core\Concerns\TestGenerator;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Foundation/Console/ComponentMakeCommand.php
 */
#[AsCommand(name: 'make:component', description: 'Create a new view component class')]
class ComponentMakeCommand extends \Illuminate\Foundation\Console\ComponentMakeCommand
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
    public function afterCodeHasBeenGenerated(string $className, string $path): void
    {
        if ($this->option('view') || ! $this->option('inline')) {
            $this->writeView(function () {
                $this->components->info(\sprintf('View for %s created successfully.', $this->type));
            });
        }
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

    /**
     * Get the first view directory path from the application configuration.
     *
     * @param  string  $path
     * @return string
     */
    #[\Override]
    protected function viewPath($path = '')
    {
        return $this->viewPathUsingCanvas($path);
    }
}
