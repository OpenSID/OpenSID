<?php

namespace Tests\Feature\Http\Middleware;

use App\Attributes\AdminModul;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class ShareAdminModulTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/_test/share-modul', [ModulTestController::class, 'index'])
                ->name('admin-modul.index')
                ->middleware(\App\Http\Middleware\ShareAdminModul::class);

            Route::get('/_test/share-modul-plain', [PlainTestController::class, 'index'])
                ->name('plain-test.index')
                ->middleware(\App\Http\Middleware\ShareAdminModul::class);

            Route::get('/_test/share-modul-controller', [ControllerArgTestController::class, 'index'])
                ->name('controller-arg.index')
                ->middleware(\App\Http\Middleware\ShareAdminModul::class);

            Route::get('/_test/share-modul-method', [MethodLevelTestController::class, 'index'])
                ->name('method-class.index')
                ->middleware(\App\Http\Middleware\ShareAdminModul::class);

            Route::get('/_test/share-modul-method-special', [MethodLevelTestController::class, 'special'])
                ->name('method-special.index')
                ->middleware(\App\Http\Middleware\ShareAdminModul::class);

            Route::get('/_test/share-modul-closure', fn () => response('ok'))
                ->name('closure-fallback.index')
                ->middleware(\App\Http\Middleware\ShareAdminModul::class);
        });
    }

    #[Test]
    public function modul_ini_dan_sub_modul_ini_dishare_dari_attribute(): void
    {
        $this->get('/_test/share-modul')
            ->assertStatus(200)
            ->assertSee('ok');

        $this->assertSame('statistik', view()->shared('modul_ini'));
        $this->assertSame('laporan-perkembangan', view()->shared('sub_modul_ini'));
        $this->assertSame('laporan-perkembangan', view()->shared('akses_modul'));
    }

    #[Test]
    public function controller_diambil_dari_controller_class(): void
    {
        $this->get('/_test/share-modul')->assertStatus(200);

        $this->assertSame('modultest', view()->shared('controller'));
    }

    #[Test]
    public function controller_diambil_dari_attribute_saat_dideklarasikan(): void
    {
        $this->get('/_test/share-modul-controller')->assertStatus(200);

        $this->assertSame('surat-mohon', view()->shared('controller'));
        $this->assertSame('layanan-surat', view()->shared('modul_ini'));
    }

    #[Test]
    public function kategori_pengaturan_dishare_dari_attribute(): void
    {
        $this->get('/_test/share-modul')->assertStatus(200);

        $this->assertSame('laporan', view()->shared('kategori_pengaturan'));
    }

    #[Test]
    public function fallback_modul_ke_controller_class_saat_attribute_tidak_ada(): void
    {
        $this->get('/_test/share-modul-plain')
            ->assertStatus(200)
            ->assertSee('ok');

        $this->assertSame('plaintest', view()->shared('modul_ini'));
        $this->assertNull(view()->shared('sub_modul_ini'));
        $this->assertSame('plaintest', view()->shared('akses_modul'));
        $this->assertNull(view()->shared('kategori_pengaturan'));
    }

    #[Test]
    public function dua_request_berturut_turut_tetap_konsisten(): void
    {
        $this->get('/_test/share-modul')->assertStatus(200);
        $this->get('/_test/share-modul')->assertStatus(200);

        $this->assertSame('statistik', view()->shared('modul_ini'));
    }

    #[Test]
    public function attribute_level_method_menang_atas_class(): void
    {
        $this->get('/_test/share-modul-method-special')->assertStatus(200);

        $this->assertSame('method-modul', view()->shared('modul_ini'));
        $this->assertSame('method-sub', view()->shared('sub_modul_ini'));

        $this->get('/_test/share-modul-method')->assertStatus(200);

        $this->assertSame('class-modul', view()->shared('modul_ini'));
        $this->assertSame('class-sub', view()->shared('sub_modul_ini'));
    }

    #[Test]
    public function fallback_route_name_saat_closure_route(): void
    {
        $this->get('/_test/share-modul-closure')->assertStatus(200);

        $this->assertSame('closure-fallback', view()->shared('controller'));
        $this->assertSame('closure-fallback', view()->shared('modul_ini'));
        $this->assertSame('closure-fallback', view()->shared('akses_modul'));
    }

    #[Test]
    public function resolve_static_mengikuti_urutan_method_lalu_class(): void
    {
        $this->assertSame('statistik', AdminModul::resolve(ModulTestController::class)?->modul);
        $this->assertSame('method-modul', AdminModul::resolve(MethodLevelTestController::class, 'special')?->modul);
        $this->assertSame('class-modul', AdminModul::resolve(MethodLevelTestController::class)?->modul);
        $this->assertSame('acak', AdminModul::name('App\Http\Controllers\Admin\AcakController'));
        $this->assertNull(AdminModul::resolve(null));
    }

    #[Test]
    public function resolve_static_selalu_mengembalikan_konteks_untuk_class_route(): void
    {
        $context = AdminModul::resolve(PlainTestController::class);

        $this->assertNotNull($context);
        $this->assertSame('plaintest', $context?->modul);
        $this->assertSame('plaintest', $context?->controller);
    }

    #[Test]
    public function for_class_dan_for_method_mengembalikan_attribute_memoized(): void
    {
        $this->assertCount(1, AdminModul::forClass(ModulTestController::class));
        $this->assertSame('statistik', AdminModul::forClass(ModulTestController::class)[0]?->modul);

        $this->assertCount(1, AdminModul::forMethod(MethodLevelTestController::class, 'special'));
        $this->assertSame('method-modul', AdminModul::forMethod(MethodLevelTestController::class, 'special')[0]?->modul);

        $this->assertSame([], AdminModul::forClass(PlainTestController::class));
        $this->assertSame([], AdminModul::forMethod(MethodLevelTestController::class, 'index'));
    }
}

#[AdminModul(modul: 'statistik', subModul: 'laporan-perkembangan', kategoriPengaturan: 'laporan')]
class ModulTestController extends Controller
{
    public function index()
    {
        return response('ok');
    }
}

class PlainTestController extends Controller
{
    public function index()
    {
        return response('ok');
    }
}

#[AdminModul(modul: 'layanan-surat', controller: 'surat-mohon')]
class ControllerArgTestController extends Controller
{
    public function index()
    {
        return response('ok');
    }
}

#[AdminModul(modul: 'class-modul', subModul: 'class-sub')]
class MethodLevelTestController extends Controller
{
    public function index()
    {
        return response('ok');
    }

    #[AdminModul(modul: 'method-modul', subModul: 'method-sub')]
    public function special()
    {
        return response('ok');
    }
}
