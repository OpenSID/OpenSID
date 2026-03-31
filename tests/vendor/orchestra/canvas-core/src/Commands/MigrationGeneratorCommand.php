<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Orchestra\Canvas\Core\Concerns\MigrationGenerator;

/**
 * @property string|null  $name
 * @property string|null  $description
 */
abstract class MigrationGeneratorCommand extends \Illuminate\Console\MigrationGeneratorCommand
{
    use MigrationGenerator;

    /**
     * Create a new notifications table command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct($files, $composer);

        $this->addGeneratorPresetOptions();
    }

    /**
     * Create a base migration file for the table.
     *
     * @param  string  $table
     * @return string
     */
    #[\Override]
    protected function createBaseMigration($table)
    {
        return $this->createBaseMigrationUsingCanvas($table);
    }

    /**
     * Determine whether a migration for the table already exists.
     *
     * @param  string  $table
     * @return bool
     */
    #[\Override]
    protected function migrationExists($table)
    {
        return $this->migrationExistsUsingCanvas($table);
    }
}
