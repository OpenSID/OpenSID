<?php

namespace Orchestra\Testbench\Concerns\Database;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

trait InteractsWithSqliteDatabaseFile
{
    use InteractsWithPublishedFiles;

    /**
     * List of generated files.
     *
     * @var array<int, string>
     */
    protected $files = [];

    /**
     * Drop Sqlite Database.
     *
     * @api
     *
     * @param  callable():void  $callback
     * @return void
     */
    protected function withoutSqliteDatabase(callable $callback): void
    {
        $time = time();
        $filesystem = new Filesystem;

        $database = database_path('database.sqlite');

        if ($filesystem->exists($database)) {
            $filesystem->move($database, $temporary = "{$database}.backup-{$time}");

            $this->files[] = $temporary;
        }

        value($callback);

        if (isset($temporary)) {
            $filesystem->move($temporary, $database);
        }
    }

    /**
     * Drop and create a new Sqlite Database.
     *
     * @api
     *
     * @param  callable():void  $callback
     * @return void
     */
    protected function withSqliteDatabase(callable $callback): void
    {
        $this->withoutSqliteDatabase(static function () use ($callback) {
            $filesystem = new Filesystem;

            $database = database_path('database.sqlite');

            if (! $filesystem->exists($database)) {
                $filesystem->copy($example = "{$database}.example", $database);
            }

            value($callback);

            if (isset($example)) {
                $filesystem->delete($database);
            }
        });
    }

    /**
     * Tear down the Dusk test case class.
     *
     * @afterClass
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function cleanupBackupSqliteDatabaseFilesOnFailed()
    {
        $filesystem = new Filesystem;

        $filesystem->delete(
            (new Collection($filesystem->glob(database_path('database.sqlite.backup-*'))))
                ->filter(static function ($file) use ($filesystem) {
                    return $filesystem->exists($file);
                })->all()
        );
    }
}
