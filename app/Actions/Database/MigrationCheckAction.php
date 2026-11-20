<?php

namespace App\Actions\Database;

use App\Libraries\Database;

class MigrationCheckAction
{
    public function __construct(
        protected Database $migrator,
    ) {}

    public function execute(?bool $force = false): void
    {
        if (app()->environment('testing')) {
            return;
        }

        $this->migrator->checkMigration($force);
    }
}
