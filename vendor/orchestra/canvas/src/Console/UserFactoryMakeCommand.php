<?php

namespace Orchestra\Canvas\Console;

use Orchestra\Canvas\Core\Commands\GeneratorCommand;
use Orchestra\Canvas\Core\Concerns\ResolvesPresetStubs;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

use function Illuminate\Filesystem\join_paths;

#[AsCommand(name: 'make:user-factory', description: 'Create the User factory class')]
class UserFactoryMakeCommand extends GeneratorCommand
{
    use ResolvesPresetStubs;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Factory';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    #[\Override]
    protected function getStub()
    {
        return $this->resolveStubPath(join_paths('stubs', 'user-factory.stub'));
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
     * Get the root namespace for the class.
     *
     * @return string
     */
    #[\Override]
    protected function rootNamespace()
    {
        return $this->generatorPreset()->factoryNamespace();
    }

    /**
     * Get the generator preset source path.
     */
    protected function getGeneratorSourcePath(): string
    {
        return $this->generatorPreset()->factoryPath();
    }

    /**
     * Handle generating code.
     */
    public function generatingCode(string $stub, string $className): string
    {
        $preset = $this->generatorPreset();

        return str_replace([
            '{{ factoryNamespace }}',
            '{{ namespacedModel }}',
            '{{ model }}',
        ], [
            rtrim($preset->factoryNamespace(), '\\'),
            $preset->modelNamespace().'User',
            'User',
        ], $stub);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    #[\Override]
    protected function getNameInput()
    {
        return 'UserFactory';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    #[\Override]
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array<int, array>
     */
    #[\Override]
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the generator already exists'],
        ];
    }
}
