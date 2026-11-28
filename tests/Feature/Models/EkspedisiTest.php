<?php

namespace Tests\Feature\Models;

use App\Models\Ekspedisi;
use App\Models\User;
use Database\Factories\SuratKeluarFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Ekspedisi.
 *
 * Ekspedisi memetakan tabel surat_keluar dengan global scope `ekspedisi = 1`,
 * jadi data dibuat via SuratKeluarFactory state ekspedisi().
 */
class EkspedisiTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    #[Test]
    public function global_scope_hanya_menampilkan_ekspedisi_satu(): void
    {
        $ekspedisi = SuratKeluarFactory::new()->ekspedisi()->create();
        $biasa     = SuratKeluarFactory::new()->create(); // ekspedisi = 0

        $ids = Ekspedisi::pluck('id');
        $this->assertTrue($ids->contains($ekspedisi->id));
        $this->assertFalse($ids->contains($biasa->id));
    }

    #[Test]
    public function scope_get_tahun_mengembalikan_array(): void
    {
        SuratKeluarFactory::new()->ekspedisi()->create();

        $this->assertIsArray(Ekspedisi::getTahun());
    }

    #[Test]
    public function scope_get_tanda_terima_mengembalikan_baris(): void
    {
        $ekspedisi = SuratKeluarFactory::new()->ekspedisi()->create();

        $hasil = Ekspedisi::getTandaTerima($ekspedisi->id);

        $this->assertNotNull($hasil);
    }

    #[Test]
    public function scope_untuk_ekspedisi_mengeluarkan_surat_dari_ekspedisi(): void
    {
        $ekspedisi = SuratKeluarFactory::new()->ekspedisi()->create();

        Ekspedisi::untukEkspedisi($ekspedisi->id, 0);

        // Setelah di-set ekspedisi = 0, tidak lagi terlihat oleh global scope Ekspedisi.
        $this->assertNull(Ekspedisi::find($ekspedisi->id));
    }
}
