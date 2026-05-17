<?php

namespace Orchestra\Canvas\Console;

use Illuminate\Support\Str;
use Orchestra\Canvas\Core\Commands\GeneratorCommand;
use Orchestra\Canvas\Core\Concerns\ResolvesPresetStubs;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

use function Illuminate\Filesystem\join_paths;

#[AsCommand(name: 'make:generator', description: 'Create a new generator command')]
class GeneratorMakeCommand extends GeneratorCommand
{
    use ResolvesPresetStubs;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Generator';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    #[\Override]
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        /** @var string $command */
        $command = $this->option('command') ?: 'make:'.Str::of($name)->classBasename()->kebab()->value();

        return str_replace(['dummy:command', '{{ command }}'], $command, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    #[\Override]
    protected function getStub()
    {
        return $this->resolveStubPath(join_paths('stubs', 'generator.stub'));
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
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    #[\Override]
    protected function getDefaultNamespace($rootNamespace)
    {
        return rtrim($this->generatorPreset()->commandNamespace(), '\\');
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
            ['command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned', 'make:name'],
        ];
    }
}
