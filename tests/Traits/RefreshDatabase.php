<?php

namespace Tests\Traits;

trait RefreshDatabase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function migrateFreshUsing(): array
    {
        $seeder = $this->seeder();

        return array_merge(
            [
                '--drop-views'  => $this->shouldDropViews(),
                '--drop-types'  => $this->shouldDropTypes(),
                '--schema-path' => base_path('tests/playwright/storage/database/schema.sql'),
                '--path'        => 'database/migrations',
                '--realpath'    => false,
            ],
            $seeder ? ['--seeder' => $seeder] : ['--seed' => $this->shouldSeed()]
        );
    }
}
