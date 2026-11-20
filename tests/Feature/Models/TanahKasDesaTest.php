<?php

namespace Tests\Feature\Models;

use App\Models\TanahKasDesa;
use App\Models\User;
use Database\Factories\TanahKasDesaFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\TanahKasDesa (tabel tanah_kas_desa).
 */
class TanahKasDesaTest extends BaseTestCase
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
        $tampil   = TanahKasDesaFactory::new()->visible(1)->create();
        $sembunyi = TanahKasDesaFactory::new()->visible(0)->create();

        $ids = TanahKasDesa::visible(1)->pluck('id');
        $this->assertTrue($ids->contains($tampil->id));
        $this->assertFalse($ids->contains($sembunyi->id));
    }

    #[Test]
    public function scope_check_letter_c_mendeteksi_keberadaan_letter_c(): void
    {
        TanahKasDesaFactory::new()->visible(1)->letterC('LC-UJI-001')->create();

        $this->assertTrue(TanahKasDesa::checkLetterC('LC-UJI-001'));
        $this->assertFalse(TanahKasDesa::checkLetterC('LC-TIDAK-ADA'));
    }

    #[Test]
    public function factory_menyimpan_tanah_kas_desa_ke_database(): void
    {
        $tanah = TanahKasDesaFactory::new()->letterC('LC-FACTORY-9999')->create();

        $this->assertDatabaseHas('tanah_kas_desa', [
            'id'       => $tanah->id,
            'letter_c' => 'LC-FACTORY-9999',
        ]);
    }
}
