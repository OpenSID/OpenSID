<?php

namespace Orchestra\Canvas\Console;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns\CodeGenerator;
use Orchestra\Canvas\Core\Concerns\ResolvesPresetStubs;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Symfony\Component\Console\Attribute\AsCommand;

use function Illuminate\Filesystem\join_paths;

/**
 * @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Database/Console/Factories/FactoryMakeCommand.php
 */
#[AsCommand(name: 'make:factory', description: 'Create a new model factory')]
class FactoryMakeCommand extends \Illuminate\Database\Console\Factories\FactoryMakeCommand
{
    use CodeGenerator;
    use ResolvesPresetStubs;
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
     * Resolve the default fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveDefaultStubPath($stub)
    {
        return join_paths(__DIR__, $stub);
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    #[\Override]
    protected function getNamespace($name)
    {
        return rtrim($this->generatorPreset()->factoryNamespace(), '\\');
    }

    /**
     * Get the generator preset source path.
     */
    protected function getGeneratorSourcePath(): string
    {
        return $this->generatorPreset()->factoryPath();
    }

    /**
     * Guess the model name from the Factory name or return a default model name.
     *
     * @param  string  $name
     * @return string
     */
    #[\Override]
    protected function guessModelName($name)
    {
        if (str_ends_with($name, 'Factory')) {
            $name = substr($name, 0, -7);
        }

        return $this->qualifyModelUsingCanvas($name);
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
     * Get the model for the default guard's user provider.
     *
     * @return string|null
     */
    #[\Override]
    protected function userProviderModel()
    {
        return $this->userProviderModelUsingCanvas();
    }
}
