<?php

namespace Orchestra\Canvas\Core\Actions;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Sidekick\Env;

/**
 * @api
 */
class WriteEnvironmentVariables
{
    /**
     * Construct a new action instance.
     */
    public function __construct(
        public Filesystem $filesystem,
        public string|false|null $filename,
    ) {}

    /**
     * Handle the action.
     *
     * @param  array<string, mixed>  $variables
     *
     * @throws \RuntimeException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(array $variables, bool $overwrite = false): void
    {
        if (! \is_string($this->filename)) {
            throw new FileNotFoundException;
        }

        $this->writeVariables($variables, $this->filename, $overwrite);
    }

    /**
     * Write an array of key-value pairs to the environment file.
     *
     * @laravel-overrides
     *
     * @param  array<string, mixed>  $variables
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function writeVariables(array $variables, string $filename, bool $overwrite = false): void
    {
        if ($this->filesystem->missing($filename)) {
            throw new FileNotFoundException("The file [{$filename}] does not exist.");
        }

        $lines = explode(PHP_EOL, $this->filesystem->get($filename));

        foreach ($variables as $key => $value) {
            $lines = $this->addVariableToEnvContents($key, $value, $lines, $overwrite);
        }

        $this->filesystem->put($filename, implode(PHP_EOL, $lines));
    }

    /**
     * Add a variable to the environment file contents.
     *
     * @laravel-overrides
     */
    protected function addVariableToEnvContents(string $key, mixed $value, array $envLines, bool $overwrite): array
    {
        $prefix = explode('_', $key)[0].'_';
        $lastPrefixIndex = -1;

        $shouldQuote = \is_string($value) && preg_match('/^[a-zA-z0-9]+$/', $value) === 0;

        $lineToAddVariations = [
            $key.'='.(\is_string($value) ? '"'.addslashes($value).'"' : Env::encode($value)),
            $key.'='.(\is_string($value) ? "'".addslashes($value)."'" : Env::encode($value)),
            $key.'='.Env::encode($value),
        ];

        $lineToAdd = $shouldQuote ? $lineToAddVariations[0] : $lineToAddVariations[2];

        if ($value === '') {
            $lineToAdd = $key.'=';
        }

        foreach ($envLines as $index => $line) {
            if (str_starts_with($line, $prefix)) {
                $lastPrefixIndex = $index;
            }

            if (\in_array($line, $lineToAddVariations)) {
                // This exact line already exists, so we don't need to add it again.
                return $envLines;
            }

            if ($line === $key.'=') {
                // If the value is empty, we can replace it with the new value.
                $envLines[$index] = $lineToAdd;

                return $envLines;
            }

            if (str_starts_with($line, $key.'=')) {
                if (! $overwrite) {
                    return $envLines;
                }

                $envLines[$index] = $lineToAdd;

                return $envLines;
            }
        }

        if ($lastPrefixIndex === -1) {
            if (\count($envLines) && $envLines[\count($envLines) - 1] !== '') {
                $envLines[] = '';
            }

            return array_merge($envLines, [$lineToAdd]);
        }

        return array_merge(
            \array_slice($envLines, 0, $lastPrefixIndex + 1),
            [$lineToAdd],
            \array_slice($envLines, $lastPrefixIndex + 1)
        );
    }
}
