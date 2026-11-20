<?php

namespace Tests\Feature\Models;

use App\Models\FormatSurat;
use Database\Factories\FormatSuratFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\FormatSurat (tabel tweb_surat_format).
 */
class FormatSuratTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_mandiri_dan_favorit_dan_kunci_memfilter(): void
    {
        $mandiri = FormatSuratFactory::new()->mandiri()->create();
        $favorit = FormatSuratFactory::new()->favorit()->create();
        $kunci   = FormatSuratFactory::new()->terkunci()->create();

        $this->assertTrue(FormatSurat::mandiri()->pluck('id')->contains($mandiri->id));
        $this->assertTrue(FormatSurat::favorit(FormatSurat::FAVORIT)->pluck('id')->contains($favorit->id));
        $this->assertTrue(FormatSurat::kunci(FormatSurat::KUNCI)->pluck('id')->contains($kunci->id));
    }

    #[Test]
    public function scope_jenis_memfilter_jenis(): void
    {
        $sistem = FormatSuratFactory::new()->jenis(3)->create();
        $lain   = FormatSuratFactory::new()->jenis(5)->create();

        $ids = FormatSurat::jenis(3)->pluck('id');
        $this->assertTrue($ids->contains($sistem->id));
        $this->assertFalse($ids->contains($lain->id));
    }

    #[Test]
    public function scope_is_exist_dan_cetak(): void
    {
        $format = FormatSuratFactory::new()->create(['url_surat' => 'surat-uji-coverage']);

        $this->assertTrue(FormatSurat::isExist('surat-uji-coverage'));
        $this->assertFalse(FormatSurat::isExist('url-tidak-ada'));

        $ids = FormatSurat::cetak('surat-uji-coverage')->pluck('id');
        $this->assertTrue($ids->contains($format->id));
    }

    #[Test]
    public function factory_menyimpan_format_surat_ke_database(): void
    {
        $format = FormatSuratFactory::new()->create(['nama' => 'Surat Keterangan Uji']);

        $this->assertDatabaseHas('tweb_surat_format', [
            'id'   => $format->id,
            'nama' => 'Surat Keterangan Uji',
        ]);
    }

    #[Test]
    public function format_penomoran_surat_jika_helper_tersedia(): void
    {
        foreach (['format_penomoran_surat', 'setting', 'bulan_romawi'] as $f) {
            if (! function_exists($f)) {
                $this->markTestSkipped("Helper CI3 {$f}() belum dimuat pada context ini (lihat HELPER_AUDIT.md).");
            }
        }
        // setting() butuh state runtime CI3 yang tidak diinisialisasi di harness.
        try {
            setting('format_nomor_surat');
        } catch (\Throwable $e) {
            $this->markTestSkipped('setting() butuh state runtime CI3 yang tidak tersedia di harness (lihat HELPER_AUDIT.md).');
        }

        $hasil = FormatSurat::format_penomoran_surat([
            'surat' => ['format_nomor_global' => 1, 'format_nomor' => '[nomor_surat]/[kode_surat]', 'kode_surat' => '470'],
            'input' => ['nomor' => 5],
        ]);

        $this->assertIsString($hasil);
    }
}
