<?php

namespace Tests\Feature\Models;

use App\Models\SuratKeluar;
use App\Models\User;
use Database\Factories\SuratKeluarFactory;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\SuratKeluar (tabel surat_keluar).
 */
class SuratKeluarTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    #[Test]
    public function scope_tahun_dapat_dieksekusi(): void
    {
        SuratKeluarFactory::new()->create();

        $this->assertInstanceOf(Collection::class, SuratKeluar::tahun()->get());
    }

    #[Test]
    public function scope_autocomplete_mengembalikan_array_tujuan(): void
    {
        SuratKeluarFactory::new()->create(['tujuan' => 'Dinas Pendidikan Uji']);

        $this->assertIsArray(SuratKeluar::autocomplete());
    }

    #[Test]
    public function scope_arsip_fisik_surat_keluar_hanya_yang_punya_berkas(): void
    {
        $berkas = SuratKeluarFactory::new()->denganBerkas()->create();

        $ids = SuratKeluar::arsipFisikSuratKeluar()->pluck('id');

        $this->assertTrue($ids->contains($berkas->id));
    }

    #[Test]
    public function factory_menyimpan_surat_keluar_ke_database(): void
    {
        $surat = SuratKeluarFactory::new()->create(['nomor_surat' => '470/UJI/2024']);

        $this->assertDatabaseHas('surat_keluar', [
            'id'          => $surat->id,
            'nomor_surat' => '470/UJI/2024',
        ]);
    }
}
