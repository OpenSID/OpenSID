<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;

trait CodeGenerator
{
    use CreatesUsingGeneratorPreset;

    /**
     * Generate code.
     */
    public function generateCode(): bool
    {
        $name = $this->getNameInput();
        $force = $this->hasOption('force') && $this->option('force') === true;

        $className = $this->qualifyClass($name);
        $path = $this->getPath($this->qualifyClass($name));

        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($name)) {
            $this->components->error('The name "'.$name.'" is reserved by PHP.');

            return false;
        }

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if (! $force && $this->alreadyExists($name)) {
            return $this->codeAlreadyExists($className, $path);
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put(
            $path, $this->sortImports($this->generatingCode($this->buildClass($className), $className))
        );

        if (\in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            $this->handleTestCreationUsingCanvas($path);
        }

        return tap($this->codeHasBeenGenerated($className, $path), function ($exitCode) use ($className, $path) {
            $this->afterCodeHasBeenGenerated($className, $path);
        });
    }

    /**
     * Handle generating code.
     */
    public function generatingCode(string $stub, string $className): string
    {
        return $stub;
    }

    /**
     * Code already exists.
     */
    public function codeAlreadyExists(string $className, string $path): bool
    {
        $this->components->error(
            sprintf(
                '%s [%s] already exists!', $this->type, Str::after($path, $this->generatorPreset()->basePath().DIRECTORY_SEPARATOR)
            )
        );

        return false;
    }

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className, string $path): bool
    {
        $this->components->info(
            sprintf(
                '%s [%s] created successfully.', $this->type, Str::after($path, $this->generatorPreset()->basePath().DIRECTORY_SEPARATOR)
            )
        );

        return true;
    }

    /**
     * Run after code successfully generated.
     */
    public function afterCodeHasBeenGenerated(string $className, string $path): void
    {
        //
    }
}
