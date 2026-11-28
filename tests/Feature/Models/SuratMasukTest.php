<?php

namespace Tests\Feature\Models;

use App\Models\SuratMasuk;
use Database\Factories\SuratMasukFactory;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\SuratMasuk (tabel surat_masuk).
 */
class SuratMasukTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_tahun_dapat_dieksekusi(): void
    {
        SuratMasukFactory::new()->create();

        $this->assertInstanceOf(Collection::class, SuratMasuk::tahun()->get());
    }

    #[Test]
    public function scope_autocomplete_mengembalikan_array_pengirim(): void
    {
        SuratMasukFactory::new()->create(['pengirim' => 'Kecamatan Uji']);

        $this->assertIsArray(SuratMasuk::autocomplete());
    }

    #[Test]
    public function scope_arsip_fisik_surat_masuk_hanya_yang_punya_berkas(): void
    {
        $berkas = SuratMasukFactory::new()->denganBerkas()->create();

        $ids = SuratMasuk::arsipFisikSuratMasuk()->pluck('id');

        $this->assertTrue($ids->contains($berkas->id));
    }

    #[Test]
    public function factory_menyimpan_surat_masuk_ke_database(): void
    {
        $surat = SuratMasukFactory::new()->create(['nomor_surat' => '005/UJI/2024']);

        $this->assertDatabaseHas('surat_masuk', [
            'id'          => $surat->id,
            'nomor_surat' => '005/UJI/2024',
        ]);
    }
}
