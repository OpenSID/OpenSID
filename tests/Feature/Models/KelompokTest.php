<?php

namespace Tests\Feature\Models;

use App\Enums\StatusDasarEnum;
use App\Models\Kelompok;
use App\Models\User;
use Database\Factories\KelompokFactory;
use Database\Factories\PendudukFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Kelompok (tabel kelompok).
 */
class KelompokTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    private function skipKecualiHelper(array $fungsi = []): void
    {
        foreach ($fungsi as $f) {
            if (! function_exists($f)) {
                $this->markTestSkipped("Helper CI3 {$f}() belum dimuat pada context ini (lihat HELPER_AUDIT.md).");
            }
        }
    }

    #[Test]
    public function scope_slug_dan_tipe_memfilter(): void
    {
        $k = KelompokFactory::new()->tipe('lembaga')->create(['slug' => 'kelompok-uji-coverage']);

        $this->assertTrue(Kelompok::slug('kelompok-uji-coverage')->pluck('id')->contains($k->id));
        $this->assertTrue(Kelompok::tipe('lembaga')->pluck('id')->contains($k->id));
    }

    #[Test]
    public function scope_status_berdasarkan_status_ketua(): void
    {
        $ketuaHidup = PendudukFactory::new()->create(['status_dasar' => StatusDasarEnum::HIDUP]);
        $ketuaMati  = PendudukFactory::new()->create(['status_dasar' => StatusDasarEnum::MATI]);

        $kelompokAktif = KelompokFactory::new()->ketua($ketuaHidup)->create();
        $kelompokMati  = KelompokFactory::new()->ketua($ketuaMati)->create();

        $ids = Kelompok::status(1)->pluck('id');

        $this->assertTrue($ids->contains($kelompokAktif->id));
        $this->assertFalse($ids->contains($kelompokMati->id));
    }

    #[Test]
    public function slug_check_mendeteksi_slug_dan_tipe(): void
    {
        KelompokFactory::new()->tipe('kelompok')->create(['slug' => 'uji-slug-check']);

        $this->assertTrue(Kelompok::slugCheck('uji-slug-check', 'kelompok'));
        $this->assertFalse(Kelompok::slugCheck('slug-tidak-ada', 'kelompok'));
    }

    #[Test]
    public function relasi_ketua_dieksekusi(): void
    {
        $ketua    = PendudukFactory::new()->create();
        $kelompok = KelompokFactory::new()->ketua($ketua)->create();

        $fresh = Kelompok::find($kelompok->id);

        $this->assertNotNull($fresh->ketua);
        $this->assertSame($ketua->id, $fresh->ketua->id);
    }

    #[Test]
    public function factory_menyimpan_kelompok_ke_database(): void
    {
        $kelompok = KelompokFactory::new()->create(['nama' => 'Kelompok Tani Uji']);

        $this->assertDatabaseHas('kelompok', [
            'id'   => $kelompok->id,
            'nama' => 'Kelompok Tani Uji',
        ]);
    }

    // ===================== CI3-DEPENDENT (conditional skip) =====================

    #[Test]
    public function accessor_url_logo_jika_helper_tersedia(): void
    {
        $this->skipKecualiHelper(['gambar_desa']);
        $kelompok = KelompokFactory::new()->create();

        $this->assertIsString(Kelompok::find($kelompok->id)->url_logo);
    }

    #[Test]
    public function scope_list_penduduk_jika_helper_tersedia(): void
    {
        // setting() butuh state runtime CI3 yang tidak diinisialisasi di harness.
        if (! function_exists('setting')) {
            $this->markTestSkipped('Helper CI3 setting() belum dimuat (lihat HELPER_AUDIT.md).');
        }
        try {
            setting('sebutan_desa');
        } catch (\Throwable $e) {
            $this->markTestSkipped('setting() butuh state runtime CI3 yang tidak tersedia di harness (lihat HELPER_AUDIT.md).');
        }

        $this->assertNotNull(Kelompok::listPenduduk());
    }
}
