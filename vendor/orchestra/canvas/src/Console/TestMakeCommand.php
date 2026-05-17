<?php

namespace Orchestra\Canvas\Console;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns\CodeGenerator;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Orchestra\Canvas\GeneratorPreset;
use Symfony\Component\Console\Attribute\AsCommand;

use function Illuminate\Filesystem\join_paths;

/**
 * @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Foundation/Console/TestMakeCommand.php
 */
#[AsCommand(name: 'make:test', description: 'Create a new test class')]
class TestMakeCommand extends \Illuminate\Foundation\Console\TestMakeCommand
{
    use CodeGenerator;
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
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function generatingCode($stub, $name)
    {
        $preset = $this->generatorPreset();

        if (! $preset instanceof GeneratorPreset) {
            return $stub;
        }

        $testCase = $this->option('unit')
            ? $preset->canvas()->config('testing.extends.unit', 'PHPUnit\Framework\TestCase')
            : $preset->canvas()->config(
                'testing.extends.feature',
                $preset->canvas()->is('laravel') ? 'Tests\TestCase' : 'Orchestra\Testbench\TestCase'
            );

        return $this->replaceTestCase($stub, $testCase);
    }

    /**
     * Replace the model for the given stub.
     */
    protected function replaceTestCase(string $stub, string $testCase): string
    {
        $namespaceTestCase = $testCase = str_replace('/', '\\', $testCase);

        if (str_starts_with($testCase, '\\')) {
            $stub = str_replace('NamespacedDummyTestCase', trim($testCase, '\\'), $stub);
        } else {
            $stub = str_replace('NamespacedDummyTestCase', $namespaceTestCase, $stub);
        }

        $stub = str_replace(
            "use {$namespaceTestCase};\nuse {$namespaceTestCase};", "use {$namespaceTestCase};", $stub
        );

        $testCase = class_basename(trim($testCase, '\\'));

        return str_replace('DummyTestCase', $testCase, $stub);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    #[\Override]
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
     * Get the generator preset source path.
     */
    protected function getGeneratorSourcePath(): string
    {
        return $this->generatorPreset()->testingPath();
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
        return $this->generatorPreset()->testingNamespace();
    }
}
