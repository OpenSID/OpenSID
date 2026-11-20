<?php

namespace Tests\Feature\Models;

use App\Models\Pamong;
use App\Models\User;
use Database\Factories\PamongFactory;
use Database\Factories\PendudukFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Pamong (tabel tweb_desa_pamong).
 */
class PamongTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // PendudukFactory memakai AuthorObserver (created_by dari auth admin).
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

    /**
     * setting() butuh state runtime CI3 (CI_Controller->setting) yang tidak
     * diinisialisasi di harness test. Skip bila helper tidak ada / belum siap.
     */
    private function skipJikaSettingTidakSiap(): void
    {
        if (! function_exists('setting')) {
            $this->markTestSkipped('Helper CI3 setting() belum dimuat (lihat HELPER_AUDIT.md).');
        }

        try {
            setting('sebutan_desa');
        } catch (\Throwable $e) {
            $this->markTestSkipped('setting() butuh state runtime CI3 yang tidak tersedia di harness (lihat HELPER_AUDIT.md).');
        }
    }

    #[Test]
    public function scope_status_dan_aktif_memfilter_pamong_status(): void
    {
        $aktif    = PamongFactory::new()->aktif()->create();
        $nonAktif = PamongFactory::new()->tidakAktif()->create();

        $statusIds = Pamong::status(Pamong::LOCK)->pluck('pamong_id');
        $this->assertTrue($statusIds->contains($aktif->pamong_id));
        $this->assertFalse($statusIds->contains($nonAktif->pamong_id));

        $aktifIds = Pamong::aktif()->pluck('pamong_id');
        $this->assertTrue($aktifIds->contains($aktif->pamong_id));
    }

    #[Test]
    public function scope_bukan_pengguna_dapat_dieksekusi(): void
    {
        $pamong = PamongFactory::new()->create();

        $ids = Pamong::bukanPengguna()->pluck('pamong_id');

        // pamong baru belum terhubung ke user -> termasuk hasil.
        $this->assertTrue($ids->contains($pamong->pamong_id));
    }

    #[Test]
    public function scope_list_atasan_dan_kehadiran_pamong_dapat_dieksekusi(): void
    {
        PamongFactory::new()->create();

        $this->assertInstanceOf(Collection::class, Pamong::listAtasan()->get());
        $this->assertInstanceOf(Collection::class, Pamong::kehadiranPamong()->get());
    }

    #[Test]
    public function relasi_penduduk_dieksekusi(): void
    {
        $penduduk = PendudukFactory::new()->create();
        $pamong   = PamongFactory::new()->forPenduduk($penduduk)->create();

        $fresh = Pamong::find($pamong->pamong_id);

        $this->assertNotNull($fresh->penduduk);
        $this->assertSame($penduduk->id, $fresh->penduduk->id);
    }

    #[Test]
    public function factory_menyimpan_pamong_ke_database(): void
    {
        $pamong = PamongFactory::new()->create(['pamong_nama' => 'Kaur Uji Coverage']);

        $this->assertDatabaseHas('tweb_desa_pamong', [
            'pamong_id'   => $pamong->pamong_id,
            'pamong_nama' => 'Kaur Uji Coverage',
        ]);
    }

    // ===================== METHOD CI3-DEPENDENT (conditional skip) =====================

    #[Test]
    public function scope_kepala_desa_jika_helper_tersedia(): void
    {
        $this->skipKecualiHelper(['kades']);
        $this->skipJikaSettingTidakSiap();
        PamongFactory::new()->create();

        $this->assertNotNull(Pamong::kepalaDesa());
    }

    #[Test]
    public function scope_urut_jika_helper_tersedia(): void
    {
        $this->skipKecualiHelper(['kades', 'sekdes']);
        PamongFactory::new()->create();

        $this->assertInstanceOf(Collection::class, Pamong::urut()->get());
    }

    #[Test]
    public function list_aparatur_desa_jika_helper_tersedia(): void
    {
        $this->skipKecualiHelper(['AmbilFoto']);
        $this->skipJikaSettingTidakSiap();

        $this->assertIsArray(Pamong::listAparaturDesa());
    }
}
