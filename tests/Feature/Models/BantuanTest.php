<?php

namespace Tests\Feature\Models;

use App\Enums\AktifEnum;
use App\Models\Bantuan;
use Database\Factories\BantuanFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Bantuan (tabel `program`).
 *
 * Menguji scope status (masa aktif berdasarkan sdate/edate), scope getProgram
 * (withCount peserta), accessor status_masa_aktif yang dipersist, dan slug
 * Sluggable. Schema dimuat via RefreshDatabase.
 */
class BantuanTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_status_aktif_hanya_program_dalam_masa_aktif(): void
    {
        $aktif      = BantuanFactory::new()->aktif()->create();
        $belumMulai = BantuanFactory::new()->belumMulai()->create();
        $berakhir   = BantuanFactory::new()->sudahBerakhir()->create();

        $ids = Bantuan::status(AktifEnum::AKTIF)->pluck('id');

        $this->assertTrue($ids->contains($aktif->id));
        $this->assertFalse($ids->contains($belumMulai->id));
        $this->assertFalse($ids->contains($berakhir->id));
    }

    #[Test]
    public function scope_status_tidak_aktif_menangkap_program_di_luar_rentang(): void
    {
        $aktif    = BantuanFactory::new()->aktif()->create();
        $berakhir = BantuanFactory::new()->sudahBerakhir()->create();

        $ids = Bantuan::status(AktifEnum::TIDAK_AKTIF)->pluck('id');

        $this->assertTrue($ids->contains($berakhir->id));
        $this->assertFalse($ids->contains($aktif->id));
    }

    #[Test]
    public function scope_get_program_memuat_jumlah_peserta(): void
    {
        $program = BantuanFactory::new()->create();

        $hasil = Bantuan::getProgram($program->id)->first();

        $this->assertNotNull($hasil);
        $this->assertSame($program->id, $hasil->id);
        $this->assertSame(0, (int) $hasil->peserta_count);
    }

    #[Test]
    public function accessor_status_masa_aktif_terpersist_dari_database(): void
    {
        $program = BantuanFactory::new()->aktif()->create();

        $fresh = Bantuan::find($program->id);

        $this->assertSame('Aktif', $fresh->status_masa_aktif);
    }

    #[Test]
    public function sluggable_menghasilkan_slug_dari_nama(): void
    {
        $program = BantuanFactory::new()->create(['nama' => 'Bantuan Langsung Tunai Pengujian']);

        $this->assertNotEmpty($program->slug);
        $this->assertDatabaseHas('program', [
            'id'   => $program->id,
            'slug' => $program->slug,
        ]);
    }

    #[Test]
    public function scope_config_id_memuat_program_milik_desa(): void
    {
        $program = BantuanFactory::new()->create();

        $ids = Bantuan::configId()->pluck('id');

        $this->assertTrue($ids->contains($program->id));
    }

    #[Test]
    public function scope_list_program_mengembalikan_array(): void
    {
        BantuanFactory::new()->forSasaran(\App\Enums\SasaranEnum::PENDUDUK)->create();

        // sasaran > 0 -> filter sasaran; sasaran 0 -> select CONCAT('50',id). Keduanya return array.
        $this->assertIsArray(Bantuan::listProgram(\App\Enums\SasaranEnum::PENDUDUK));
        $this->assertIsArray(Bantuan::listProgram(0));
    }
}
