<?php

namespace Tests\Feature\Http\Middleware;

use App\Actions\Desa\ValidateInstallAction;
use App\Services\DesaIdentityService;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class EnsureDesaInstalledTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/_test/ensure-desa', function () {
                return response('ok');
            })->middleware(\App\Http\Middleware\EnsureDesaInstalled::class);
        });
    }

    #[Test]
    public function redirect_ke_install_ketika_desa_belum_diinstal(): void
    {
        $desa = $this->mock(DesaIdentityService::class);
        $desa->shouldReceive('isInstalled')->andReturn(false);
        $this->instance(ValidateInstallAction::class, new ValidateInstallAction($desa));

        $response = $this->get('/_test/ensure-desa');

        $response->assertRedirect('install');
    }

    #[Test]
    public function status_200_ketika_desa_sudah_diinstal(): void
    {
        $desa = $this->mock(DesaIdentityService::class);
        $desa->shouldReceive('isInstalled')->andReturn(true);
        $desa->shouldReceive('validAppKey')->andReturn(true);
        $this->instance(ValidateInstallAction::class, new ValidateInstallAction($desa));

        $response = $this->get('/_test/ensure-desa');

        $response->assertStatus(200);
        $response->assertSee('ok');
    }

    #[Test]
    public function dua_request_berturut_turut_tetap_konsisten(): void
    {
        $desa = $this->mock(DesaIdentityService::class);
        $desa->shouldReceive('isInstalled')->andReturn(true);
        $desa->shouldReceive('validAppKey')->andReturn(true);
        $this->instance(ValidateInstallAction::class, new ValidateInstallAction($desa));

        $this->get('/_test/ensure-desa')->assertStatus(200);
        $this->get('/_test/ensure-desa')->assertStatus(200);
    }
}
