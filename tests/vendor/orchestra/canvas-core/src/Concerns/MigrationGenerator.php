<?php

namespace Orchestra\Canvas\Core\Concerns;

use function Illuminate\Filesystem\join_paths;

trait MigrationGenerator
{
    use CreatesUsingGeneratorPreset;

    /**
     * Create a base migration file for the table.
     */
    protected function createBaseMigrationUsingCanvas(string $table): string
    {
        return $this->laravel['migration.creator']->create(
            "create_{$table}_table", $this->generatorPreset()->migrationPath()
        );
    }

    /**
     * Determine whether a migration for the table already exists.
     */
    protected function migrationExistsUsingCanvas(string $table): bool
    {
        return \count($this->files->glob(
            join_paths($this->generatorPreset()->migrationPath(), '*_*_*_*_create_'.$table.'_table.php')
        )) !== 0;
    }
}
