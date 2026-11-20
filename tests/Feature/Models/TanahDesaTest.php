<?php

namespace Tests\Feature\Models;

use App\Models\TanahDesa;
use App\Models\User;
use Database\Factories\TanahDesaFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\TanahDesa (tabel tanah_desa).
 */
class TanahDesaTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    #[Test]
    public function scope_visible_memfilter_kolom_visible(): void
    {
        $tampil  = TanahDesaFactory::new()->visible(1)->create();
        $sembunyi = TanahDesaFactory::new()->visible(0)->create();

        $ids = TanahDesa::visible(1)->pluck('id');
        $this->assertTrue($ids->contains($tampil->id));
        $this->assertFalse($ids->contains($sembunyi->id));
    }

    #[Test]
    public function factory_menyimpan_tanah_desa_ke_database(): void
    {
        $tanah = TanahDesaFactory::new()->create(['nama_pemilik_asal' => 'Pemilik Uji Coverage']);

        $this->assertDatabaseHas('tanah_desa', [
            'id'                => $tanah->id,
            'nama_pemilik_asal' => 'Pemilik Uji Coverage',
        ]);
    }
}
