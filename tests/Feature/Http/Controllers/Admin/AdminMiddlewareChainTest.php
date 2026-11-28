<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Attributes\AdminModul;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

class AdminMiddlewareChainTest extends BaseTestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();
        DB::table('config')->update(['kode_desa' => '1234567890']);

        Route::get('/_test/admin/test', [TestAdminController::class, 'index'])
            ->name('test.index')
            ->middleware([
                'web',
                'auth:admin',
                \App\Http\Middleware\EnsureDesaInstalled::class,
                \App\Http\Middleware\CheckDesaSetupCompletion::class,
                \App\Http\Middleware\ShareAnjungan::class,
                \App\Http\Middleware\ShareAdminModul::class,
                \App\Http\Middleware\ShareDesaData::class,
                \App\Http\Middleware\CheckDatabaseMigrations::class,
                \App\Http\Middleware\TrackDesaActivity::class,
            ]);
    }

    // —— EnsureDesaInstalled ——

    #[Test]
    public function redirect_ke_install_ketika_desa_belum_diinstal(): void
    {
        $this->actingAs($this->user, 'admin');

        rename(base_path('desa'), base_path('desa_backup'));

        $this->get('/_test/admin/test')
            ->assertRedirect('install');

        rename(base_path('desa_backup'), base_path('desa'));
    }

    #[Test]
    public function redirect_ke_koneksi_database_ketika_app_key_tidak_valid(): void
    {
        $this->actingAs($this->user, 'admin');

        DB::table('config')->update(['app_key' => 'some-other-key']);

        $this->get('/_test/admin/test')
            ->assertRedirect('koneksi_database/config');
    }

    // —— CheckDesaSetupCompletion ——

    #[Test]
    public function redirect_ke_identitas_desa_ketika_kode_desa_kosong(): void
    {
        $this->actingAs($this->user, 'admin');

        DB::table('config')->update(['kode_desa' => '']);

        $this->get('/_test/admin/test')
            ->assertRedirect('identitas_desa');
    }

    // —— Auth ——

    #[Test]
    public function tanpa_login_redirect_ke_siteman(): void
    {
        $this->get('/_test/admin/test')
            ->assertRedirect('siteman');
    }

    // —— Full success ——

    #[Test]
    public function semua_middleware_lulus_status_200(): void
    {
        $this->actingAs($this->user, 'admin')
            ->get('/_test/admin/test')
            ->assertStatus(200);
    }

    #[Test]
    public function response_content_type_html(): void
    {
        $this->actingAs($this->user, 'admin')
            ->get('/_test/admin/test')
            ->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    #[Test]
    public function setting_dan_desa_tersedia_di_view(): void
    {
        $this->actingAs($this->user, 'admin')
            ->get('/_test/admin/test');

        $this->assertNotNull(view()->shared('setting'));
        $this->assertNotNull(view()->shared('auth'));
        $this->assertNotNull(view()->shared('cek_anjungan'));
    }

    #[Test]
    public function modul_ini_dan_sub_modul_ini_diambil_dari_attribute_admin_modul(): void
    {
        $this->actingAs($this->user, 'admin')
            ->get('/_test/admin/test');

        $this->assertSame('test', view()->shared('modul_ini'));
        $this->assertSame('test_sub', view()->shared('sub_modul_ini'));
        $this->assertSame('test_sub', view()->shared('akses_modul'));
        $this->assertSame('testadmin', view()->shared('controller'));
    }
}

#[AdminModul(modul: 'test', subModul: 'test_sub')]
class TestAdminController extends Controller
{
    public function index()
    {
        return response('ok');
    }
}
