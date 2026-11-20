<?php

namespace Tests\Feature\Http\Middleware;

use App\Actions\Tracking\TrackDesaAction;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class TrackDesaActivityTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/_test/track-desa', function () {
                return response('ok');
            })->middleware(\App\Http\Middleware\TrackDesaActivity::class);
        });
    }

    #[Test]
    public function status_200_dan_passthrough(): void
    {
        $this->get('/_test/track-desa')
            ->assertStatus(200)
            ->assertSee('ok');
    }

    #[Test]
    public function track_desa_action_dipanggil_setiap_request(): void
    {
        $trackDesa = $this->mock(TrackDesaAction::class);
        $trackDesa->shouldReceive('execute')->once();
        $this->instance(TrackDesaAction::class, $trackDesa);

        $this->get('/_test/track-desa')
            ->assertStatus(200)
            ->assertSee('ok');
    }

    #[Test]
    public function dua_request_berturut_turut_tetap_konsisten(): void
    {
        $trackDesa = $this->mock(TrackDesaAction::class);
        $trackDesa->shouldReceive('execute')->twice();
        $this->instance(TrackDesaAction::class, $trackDesa);

        $this->get('/_test/track-desa')->assertStatus(200);
        $this->get('/_test/track-desa')->assertStatus(200);
    }
}
