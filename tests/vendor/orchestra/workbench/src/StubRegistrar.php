<?php

namespace Orchestra\Workbench;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

use function Orchestra\Sidekick\join_paths;

/**
 * @internal
 *
 * @phpstan-type TStubFiles array{
 *   config: ?string,
 *   'config.basic': ?string,
 *   gitignore: ?string,
 *   'routes.api': ?string,
 *   'routes.console': ?string,
 *   'routes.web': ?string,
 *   'seeders.database': ?string
 * }
 */
final class StubRegistrar
{
    /**
     * Files of stub files overrides.
     *
     * @var array<string, ?string>
     *
     * @phpstan-var TStubFiles
     */
    protected static array $files = [
        'config' => null,
        'config.basic' => null,
        'gitignore' => null,
        'routes.api' => null,
        'routes.console' => null,
        'routes.web' => null,
        'seeders.database' => null,
    ];

    /**
     * Swap stub file by name.
     *
     * @return $this
     */
    public function swap(string $name, ?string $file)
    {
        if (\array_key_exists($name, self::$files)) {
            self::$files[$name] = $file;
        }

        return $this;
    }

    /**
     * Retrieve the stub file from name.
     */
    public static function file(string $name): ?string
    {
        $defaultStub = join_paths(__DIR__, 'Console', 'stubs');

        return transform(
            Arr::get(array_merge([
                'config' => join_paths($defaultStub, 'testbench.yaml'),
                'config.basic' => join_paths($defaultStub, 'testbench.plain.yaml'),
                'gitignore' => join_paths($defaultStub, 'workbench.gitignore'),
                'routes.api' => join_paths($defaultStub, 'routes', 'api.php'),
                'routes.console' => join_paths($defaultStub, 'routes', 'console.php'),
                'routes.web' => join_paths($defaultStub, 'routes', 'web.php'),
                'seeders.database' => join_paths($defaultStub, 'database', 'seeders', 'DatabaseSeeder.php'),
            ], array_filter(self::$files)), $name),
            static function ($file) {
                $realpath = realpath($file);

                return $realpath !== false ? $realpath : null;
            }
        );
    }

    /**
     * Replace stub namespaces.
     */
    public static function replaceInFile(Filesystem $filesystem, string $filename): void
    {
        (new Actions\ReplaceNamespaces($filesystem))->handle($filename);
    }
}
