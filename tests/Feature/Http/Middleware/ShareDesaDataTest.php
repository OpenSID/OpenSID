<?php

namespace Tests\Feature\Http\Middleware;

use App\Attributes\AdminModul;
use App\Repositories\SettingAplikasiRepository;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class ShareDesaDataTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $settings = $this->mock(SettingAplikasiRepository::class);
        $settings->shouldReceive('resolveSetting')->andReturn((object) [
            'sebutan_desa' => 'Desa',
        ]);
        $settings->shouldReceive('resolvedListSetting')->andReturn(collect());
        $this->instance(SettingAplikasiRepository::class, $settings);

        Route::middleware('web')->group(function () {
            Route::get('/_test/share-desa', function () {
                return response('ok');
            })->middleware(\App\Http\Middleware\ShareDesaData::class);
        });
    }

    #[Test]
    public function setting_dishare_ke_view(): void
    {
        $this->get('/_test/share-desa')
            ->assertStatus(200)
            ->assertSee('ok');

        $this->assertEquals('Desa', view()->shared('setting')->sebutan_desa);
    }

    #[Test]
    public function desa_tersedia_di_view(): void
    {
        $this->get('/_test/share-desa')->assertStatus(200);

        $this->assertArrayHasKey('desa', view()->getShared());
    }

    #[Test]
    public function token_name_tersedia_di_view(): void
    {
        $this->get('/_test/share-desa')->assertStatus(200);

        $this->assertArrayHasKey('token_name', view()->getShared());
    }

    #[Test]
    public function dua_request_berturut_turut_tetap_200(): void
    {
        $this->get('/_test/share-desa')->assertStatus(200);
        $this->get('/_test/share-desa')->assertStatus(200);
    }

    #[Test]
    public function module_name_diisi_dari_konteks_modul_share_admin_modul(): void
    {
        $this->mock(SettingAplikasiRepository::class)
            ->shouldReceive('resolveSetting')->andReturn((object) [
                'sebutan_desa' => 'Desa',
            ])
            ->shouldReceive('resolvedListSetting')->andReturn(collect([
                (object) ['slug' => 'laporan-perkembangan', 'modul' => 'Laporan Perkembangan'],
            ]));

        Route::get('/_test/module-name', [TestModuleNameController::class, '__invoke'])
            ->name('statistik.laporan-perkembangan')
            ->middleware([
                \App\Http\Middleware\ShareAdminModul::class,
                \App\Http\Middleware\ShareDesaData::class,
            ]);

        $this->get('/_test/module-name')->assertStatus(200);

        $this->assertSame('Laporan Perkembangan', view()->shared('module_name'));
    }
}

#[AdminModul(modul: 'statistik', subModul: 'laporan-perkembangan')]
class TestModuleNameController
{
    public function __invoke()
    {
        return response('ok');
    }
}
