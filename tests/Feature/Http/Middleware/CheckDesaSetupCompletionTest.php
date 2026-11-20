<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\Config;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

class CheckDesaSetupCompletionTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/_test/setup-check', function () {
                return response('ok');
            })->middleware(\App\Http\Middleware\CheckDesaSetupCompletion::class);
        });
    }

    #[Test]
    public function akses_tanpa_kode_desa_redirect_ke_identitas_desa(): void
    {
        $config = Config::first();
        $config->kode_desa = '';
        $config->save();

        $response = $this->get('/_test/setup-check');

        $response->assertRedirect('identitas_desa');
    }

    #[Test]
    public function akses_dengan_kode_desa_status_200(): void
    {
        $config = Config::first();
        $config->kode_desa = '1234567890';
        $config->save();

        $response = $this->get('/_test/setup-check');

        $response->assertStatus(200);
        $response->assertSee('ok');
    }

    #[Test]
    public function dua_request_berturut_turut_tetap_konsisten(): void
    {
        $config = Config::first();
        $config->kode_desa = '1234567890';
        $config->save();

        $this->get('/_test/setup-check')->assertStatus(200);
        $this->get('/_test/setup-check')->assertStatus(200);
    }
}
