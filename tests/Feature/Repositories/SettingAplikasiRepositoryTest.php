<?php

namespace Tests\Feature\Repositories;

use App\Models\SettingAplikasi;
use App\Repositories\SettingAplikasiRepository;
use Database\Factories\SettingAplikasiFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Repositories\SettingAplikasiRepository.
 *
 * Menguji method query DB yang bersih dari helper CI3 (get/getByKategori/
 * firstByKey/updateWithKey/flushCache). Method ber-helper CI3
 * (applySettingCI/updateSetting) dipasang conditional skip — lihat HELPER_AUDIT.md.
 *
 * Catatan: SettingAplikasi memakai QueryCacheable ($cacheFor = -1), jadi query
 * cache di-flush di setUp() agar test tidak membaca cache stale antar test.
 */
class SettingAplikasiRepositoryTest extends BaseTestCase
{
    use RefreshDatabase;

    private SettingAplikasiRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        // Hindari query cache stale antar test.
        Cache::flush();
        (new SettingAplikasi())->flushQueryCache();

        $this->repo = new SettingAplikasiRepository();
    }

    private function skipKecualiHelper(array $fungsi = [], array $konstanta = []): void
    {
        foreach ($fungsi as $f) {
            if (! function_exists($f)) {
                $this->markTestSkipped("Helper CI3 {$f}() belum dimuat pada context ini (lihat HELPER_AUDIT.md).");
            }
        }
        foreach ($konstanta as $c) {
            if (! defined($c)) {
                $this->markTestSkipped("Konstanta CI3 {$c} belum terdefinisi pada context ini (lihat HELPER_AUDIT.md).");
            }
        }
    }

    #[Test]
    public function get_mengembalikan_collection_terurut_key(): void
    {
        SettingAplikasiFactory::new()->withKey('uji_zzz_get', 'z')->create();
        SettingAplikasiFactory::new()->withKey('uji_aaa_get', 'a')->create();

        $hasil = $this->repo->get();

        $this->assertInstanceOf(Collection::class, $hasil);
        $keys = $hasil->pluck('key');
        $this->assertTrue($keys->contains('uji_aaa_get'));
        $this->assertTrue($keys->contains('uji_zzz_get'));
        // urutan ascending by key: aaa muncul sebelum zzz
        $this->assertLessThan($keys->search('uji_zzz_get'), $keys->search('uji_aaa_get'));
    }

    #[Test]
    public function get_by_kategori_memfilter_kategori(): void
    {
        SettingAplikasiFactory::new()->kategori('UjiKategoriRepo')->count(2)->create();
        SettingAplikasiFactory::new()->kategori('KategoriLain')->create();

        $hasil = $this->repo->getByKategori('UjiKategoriRepo');

        $this->assertGreaterThanOrEqual(2, $hasil->count());
        foreach ($hasil as $item) {
            $this->assertSame('UjiKategoriRepo', $item->kategori);
        }
    }

    #[Test]
    public function first_by_key_mengembalikan_setting_yang_tepat(): void
    {
        SettingAplikasiFactory::new()->withKey('uji_firstbykey_001', 'nilai')->create();

        $hasil = $this->repo->firstByKey('uji_firstbykey_001');

        // Catatan: SettingAplikasi public $incrementing = false, jadi id tidak ter-hydrate
        // setelah create() -> assertion berbasis key/value yang dikontrol.
        $this->assertNotNull($hasil);
        $this->assertSame('uji_firstbykey_001', $hasil->key);
        $this->assertSame('nilai', $hasil->value);
    }

    #[Test]
    public function first_by_key_mengembalikan_null_untuk_key_tidak_ada(): void
    {
        $this->assertNull($this->repo->firstByKey('key_yang_pasti_tidak_ada_xyz'));
    }

    #[Test]
    public function update_with_key_memperbarui_value(): void
    {
        SettingAplikasiFactory::new()->withKey('uji_update_001', 'lama')->create();

        $this->assertTrue($this->repo->updateWithKey('uji_update_001', 'baru'));

        $this->assertDatabaseHas('setting_aplikasi', [
            'key'   => 'uji_update_001',
            'value' => 'baru',
        ]);
    }

    #[Test]
    public function update_with_key_mengembalikan_false_untuk_key_tidak_ada(): void
    {
        $this->assertFalse($this->repo->updateWithKey('key_tidak_ada_xyz', 'apa_saja'));
    }

    #[Test]
    public function flush_cache_tidak_melempar(): void
    {
        $this->repo->flushCache();
        $this->assertTrue(true);
    }

    // ===================== METHOD CI3-DEPENDENT (conditional skip) =====================

    #[Test]
    public function apply_setting_ci_butuh_runtime_ci3(): void
    {
        // applySettingCI() memakai SebutanDesa()/kades()/sekdes()/config_item() + konstanta
        // DESAPATH/ENVIRONMENT + objek $ci CodeIgniter. Hanya jalan di runtime CI3 penuh.
        $this->skipKecualiHelper(['SebutanDesa', 'kades', 'sekdes', 'config_item'], ['DESAPATH']);

        $this->assertTrue(method_exists(SettingAplikasiRepository::class, 'applySettingCI'));
    }

    #[Test]
    public function update_setting_butuh_runtime_ci3(): void
    {
        // updateSetting() memakai setting()/set_session()/base_url()/alfanumerik() + request()
        // + OtpService + LogBatch. Butuh runtime CI3 + konteks HTTP -> conditional skip.
        $this->skipKecualiHelper(['setting', 'set_session', 'alfanumerik']);

        $this->assertTrue(method_exists(SettingAplikasiRepository::class, 'updateSetting'));
    }
}
