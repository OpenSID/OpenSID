<?php

namespace Orchestra\Canvas\Console;

use Composer\InstalledVersions;
use Orchestra\Canvas\Core\Commands\GeneratorCommand;
use Orchestra\Canvas\Core\Concerns\ResolvesPresetStubs;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

use function Illuminate\Filesystem\join_paths;

#[AsCommand(name: 'make:user-model', description: 'Create the User model class')]
class UserModelMakeCommand extends GeneratorCommand
{
    use ResolvesPresetStubs;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    #[\Override]
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        if (! InstalledVersions::isInstalled('laravel/sanctum')) {
            $stub = str_replace(
                '    use HasApiTokens, HasFactory, Notifiable;'.PHP_EOL,
                '    use HasFactory, Notifiable;'.PHP_EOL,
                $stub
            );
        }

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    #[\Override]
    protected function getStub()
    {
        return $this->resolveStubPath(join_paths('stubs', 'user-model.stub'));
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
     */
    #[\Override]
    public function getDefaultNamespace($rootNamespace)
    {
        return rtrim($this->generatorPreset()->modelNamespace(), '\\');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    #[\Override]
    protected function getNameInput()
    {
        return 'User';
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
