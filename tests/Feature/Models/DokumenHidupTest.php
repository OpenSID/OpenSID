<?php

namespace Tests\Feature\Models;

use App\Models\DokumenHidup;
use App\Models\User;
use Database\Factories\DokumenHidupFactory;
use Database\Factories\PendudukFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\DokumenHidup (tabel dokumen_hidup).
 */
class DokumenHidupTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    #[Test]
    public function scope_status_memfilter_enabled(): void
    {
        $aktif    = DokumenHidupFactory::new()->aktif()->create();
        $nonAktif = DokumenHidupFactory::new()->nonAktif()->create();

        $ids = DokumenHidup::status(1)->pluck('id');
        $this->assertTrue($ids->contains($aktif->id));
        $this->assertFalse($ids->contains($nonAktif->id));
    }

    #[Test]
    public function scope_informasi_publik_hanya_tanpa_id_pend(): void
    {
        $penduduk = PendudukFactory::new()->create();
        $publik   = DokumenHidupFactory::new()->informasiPublik()->create();
        $warga    = DokumenHidupFactory::new()->create(['id_pend' => $penduduk->id]);

        $ids = DokumenHidup::informasiPublik()->pluck('id');
        $this->assertTrue($ids->contains($publik->id));
        $this->assertFalse($ids->contains($warga->id));
    }

    #[Test]
    public function scope_peraturan_desa_memfilter_kategori(): void
    {
        $perdes = DokumenHidupFactory::new()->kategori(3)->create();
        $lain   = DokumenHidupFactory::new()->kategori(1)->create();

        $ids = DokumenHidup::peraturanDesa(3)->pluck('id');
        $this->assertTrue($ids->contains($perdes->id));
        $this->assertFalse($ids->contains($lain->id));
    }

    #[Test]
    public function list_dokumen_mengembalikan_array(): void
    {
        $penduduk = PendudukFactory::new()->create();
        DokumenHidupFactory::new()->create(['id_pend' => $penduduk->id, 'deleted' => 0]);

        $hasil = DokumenHidup::listDokumen($penduduk->id);

        $this->assertIsArray($hasil);
        $this->assertNotEmpty($hasil);
        $this->assertSame(1, $hasil[0]['no']);
    }

    #[Test]
    public function factory_menyimpan_dokumen_ke_database(): void
    {
        $dokumen = DokumenHidupFactory::new()->create(['nama' => 'Perdes Uji Coverage']);

        $this->assertDatabaseHas('dokumen_hidup', [
            'id'   => $dokumen->id,
            'nama' => 'Perdes Uji Coverage',
        ]);
    }
}
