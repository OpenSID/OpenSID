<?php

namespace Tests\Feature\Http\Middleware;

use App\Actions\Anjungan\ResolveCurrentAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class ShareAnjunganTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $anjungan = $this->mock(ResolveCurrentAction::class);
        $anjungan->shouldReceive('execute')->andReturn(['tipe' => 'laptop']);
        $this->instance(ResolveCurrentAction::class, $anjungan);

        Route::middleware('web')->group(function () {
            Route::get('/_test/share-anjungan', [AnjunganTestController::class, 'index'])
                ->name('anjungan-test.index')
                ->middleware(\App\Http\Middleware\ShareAnjungan::class);
        });
    }

    #[Test]
    public function cek_anjungan_dishare_dari_resolve_current_action(): void
    {
        $this->get('/_test/share-anjungan')
            ->assertStatus(200)
            ->assertSee('ok');

        $this->assertSame(['tipe' => 'laptop'], view()->shared('cek_anjungan'));
    }
}

class AnjunganTestController extends Controller
{
    public function index()
    {
        return response('ok');
    }
}
