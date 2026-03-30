<?php

namespace Orchestra\Canvas\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Orchestra\Canvas\Core\Concerns\CodeGenerator;
use Orchestra\Canvas\Core\Concerns\TestGenerator;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Orchestra\Canvas\GeneratorPreset;
use Symfony\Component\Console\Attribute\AsCommand;

use function Illuminate\Filesystem\join_paths;

#[AsCommand(name: 'make:view', description: 'Create a new view')]
class ViewMakeCommand extends \Illuminate\Foundation\Console\ViewMakeCommand
{
    use CodeGenerator;
    use TestGenerator {
        handleTestCreationUsingCanvas as protected handleTestCreationUsingCanvasFromTrait;
    }
    use UsesGeneratorOverrides;

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
        /** @phpstan-ignore return.type */
        return $this->generateCode() ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        $preset = $this->generatorPreset();

        if (! $preset instanceof GeneratorPreset) {
            return parent::resolveStubPath($stub);
        }

        return $preset->hasCustomStubPath() && file_exists($customPath = join_paths($preset->basePath(), $stub))
            ? $customPath
            : $this->resolveDefaultStubPath($stub);
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
     * Get the destination view path.
     *
     * @param  string  $name
     * @return string
     */
    #[\Override]
    protected function getPath($name)
    {
        /** @var string $extension */
        /** @phpstan-ignore argument.type */
        $extension = transform($this->option('extension'), fn (string $extension) => trim($extension));

        return $this->viewPath(
            $this->getNameInput().'.'.$extension,
        );
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

    /**
     * Get the desired view name from the input.
     *
     * @return string
     */
    #[\Override]
    protected function getNameInput()
    {
        /** @phpstan-ignore argument.type */
        return transform($this->argument('name'), function (string $name) {
            return str_replace(['\\', '.'], '/', trim($name));
        });
    }

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     */
    protected function handleTestCreationUsingCanvas($path): bool
    {
        if (! $this->option('test') && ! $this->option('pest')) {
            return false;
        }

        $preset = $this->generatorPreset();

        if (! $preset instanceof GeneratorPreset) {
            return $this->handleTestCreationUsingCanvasFromTrait($path);
        }

        $namespaceTestCase = $testCase = $preset->canvas()->config(
            'testing.extends.feature',
            $preset->canvas()->is('laravel') ? 'Tests\TestCase' : 'Orchestra\Testbench\TestCase'
        );

        $stub = $this->files->get($this->getTestStub());

        if (Str::startsWith($testCase, '\\')) {
            $stub = str_replace('NamespacedDummyTestCase', trim($testCase, '\\'), $stub);
        } else {
            $stub = str_replace('NamespacedDummyTestCase', $namespaceTestCase, $stub);
        }

        $contents = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ name }}', 'DummyTestCase'],
            [$this->testNamespace(), $this->testClassName(), $this->testViewName(), class_basename(trim($testCase, '\\'))],
            $stub,
        );

        $this->files->ensureDirectoryExists(\dirname($this->getTestPath()), 0755, true);

        return $this->files->put($this->getTestPath(), $contents) !== false;
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
     * Get the destination test case path.
     *
     * @return string
     */
    #[\Override]
    protected function getTestPath()
    {
        $preset = $this->generatorPreset();

        $testPath = Str::of($this->testClassFullyQualifiedName())
            ->replace('\\', DIRECTORY_SEPARATOR)
            ->replaceFirst(join_paths('Tests', 'Feature'), join_paths(str_replace($preset->basePath(), '', $preset->testingPath()), 'Feature'))
            ->append('Test.php')
            ->value();

        return $preset->basePath().$testPath;
    }

    /**
     * Get the test stub file for the generator.
     *
     * @return string
     */
    #[\Override]
    protected function getTestStub()
    {
        $stubName = 'view.'.($this->option('pest') ? 'pest' : 'test').'.stub';

        return $this->resolveStubPath(join_paths('stubs', $stubName));
    }
}
