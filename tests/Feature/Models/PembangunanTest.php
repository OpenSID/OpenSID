<?php

namespace Tests\Feature\Models;

use App\Models\Pembangunan;
use Database\Factories\PembangunanFactory;
use Database\Factories\WilayahFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Pembangunan (tabel pembangunan).
 */
class PembangunanTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_active_dan_status_memfilter_status(): void
    {
        $aktif    = PembangunanFactory::new()->aktif()->create();
        $nonAktif = PembangunanFactory::new()->tidakAktif()->create();

        $idsActive = Pembangunan::active()->pluck('id');
        $this->assertTrue($idsActive->contains($aktif->id));
        $this->assertFalse($idsActive->contains($nonAktif->id));

        $idsStatus0 = Pembangunan::status(0)->pluck('id');
        $this->assertTrue($idsStatus0->contains($nonAktif->id));
    }

    #[Test]
    public function scope_slug_memfilter_slug(): void
    {
        $p = PembangunanFactory::new()->create(['slug' => 'pembangunan-uji-coverage']);

        $ids = Pembangunan::slug('pembangunan-uji-coverage')->pluck('id');

        $this->assertTrue($ids->contains($p->id));
    }

    #[Test]
    public function scope_tipe_rencana_mengambil_pembangunan_tanpa_dokumentasi(): void
    {
        $p = PembangunanFactory::new()->create();

        $ids = Pembangunan::tipe('rencana')->pluck('id');

        $this->assertTrue($ids->contains($p->id));
    }

    #[Test]
    public function relasi_wilayah_dieksekusi(): void
    {
        $wilayah = WilayahFactory::new()->create();
        $p       = PembangunanFactory::new()->create(['id_lokasi' => $wilayah->id]);

        $fresh = Pembangunan::find($p->id);

        $this->assertNotNull($fresh->wilayah);
        $this->assertSame($wilayah->id, $fresh->wilayah->id);
    }

    #[Test]
    public function factory_menyimpan_pembangunan_ke_database(): void
    {
        $p = PembangunanFactory::new()->create(['judul' => 'Pembangunan Jembatan Uji']);

        $this->assertDatabaseHas('pembangunan', [
            'id'    => $p->id,
            'judul' => 'Pembangunan Jembatan Uji',
        ]);
    }
}
