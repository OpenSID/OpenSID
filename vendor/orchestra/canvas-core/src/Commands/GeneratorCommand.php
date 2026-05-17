<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns;
use Orchestra\Canvas\Core\Contracts\GeneratesCode;

/**
 * @property string|null $name
 * @property string|null $description
 */
abstract class GeneratorCommand extends \Illuminate\Console\GeneratorCommand implements GeneratesCode
{
    use Concerns\CodeGenerator;
    use Concerns\TestGenerator;
    use Concerns\UsesGeneratorOverrides;

    /**
     * Create a new controller creator command instance.
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
        return $this->generateCode() ? self::SUCCESS : self::FAILURE;
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
     * Qualify the given model class base name.
     *
     * @return string
     */
    #[\Override]
    protected function qualifyModel(string $model)
    {
        return $this->qualifyModelUsingCanvas($model);
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

    /**
     * Get the first view directory path from the application configuration.
     *
     * @return string
     */
    #[\Override]
    protected function viewPath($path = '')
    {
        return $this->viewPathUsingCanvas($path);
    }

    /**
     * Get a list of possible model names.
     *
     * @return array<int, string>
     */
    #[\Override]
    protected function possibleModels()
    {
        return $this->possibleModelsUsingCanvas();
    }

    /**
     * Get a list of possible event names.
     *
     * @return array<int, string>
     */
    #[\Override]
    protected function possibleEvents()
    {
        return $this->possibleEventsUsingCanvas();
    }
}
