<?php

namespace Tests\Traits;
trait RefreshDatabase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected bool $dropViews = true;

    /**
     * The parameters that should be used when running "migrate:fresh".
     *
     * @return array
     */
    protected function migrateFreshUsing(): array
    {
        $seeder = $this->seeder();

        return array_merge(
            [
                '--drop-views'  => $this->shouldDropViews(),
                '--drop-types'  => $this->shouldDropTypes(),
                '--schema-path' => base_path('tests/playwright/storage/database/schema.sql'),
                '--path'        => base_path('app/database/migrations'),
                '--realpath'    => true,
            ],
            $seeder ? ['--seeder' => $seeder] : ['--seed' => $this->shouldSeed()]
        );
    }
}
