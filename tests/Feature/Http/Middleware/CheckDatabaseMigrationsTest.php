<?php

namespace Tests\Feature\Http\Middleware;

use App\Actions\Database\MigrationCheckAction;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class CheckDatabaseMigrationsTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/_test/check-migrations', function () {
                return response('ok');
            })->middleware(\App\Http\Middleware\CheckDatabaseMigrations::class);
        });
    }

    #[Test]
    public function status_200_dan_passthrough(): void
    {
        $this->get('/_test/check-migrations')
            ->assertStatus(200)
            ->assertSee('ok');
    }

    #[Test]
    public function migration_check_dipanggil_setiap_request(): void
    {
        $migrationCheck = $this->mock(MigrationCheckAction::class);
        $migrationCheck->shouldReceive('execute')->once();
        $this->instance(MigrationCheckAction::class, $migrationCheck);

        $this->get('/_test/check-migrations')
            ->assertStatus(200)
            ->assertSee('ok');
    }
}
