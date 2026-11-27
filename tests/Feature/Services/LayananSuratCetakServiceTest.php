<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Feature\Services;

use App\Services\LayananSuratCetakService;
use Database\Factories\LogSuratFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Regresi untuk isu IDOR pada fmandiri/Surat::cetak() — LayananSuratCetakService
 * harus hanya mengembalikan surat milik id_pend yang diberikan.
 */
class LayananSuratCetakServiceTest extends BaseTestCase
{
    use RefreshDatabase;

    private LayananSuratCetakService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LayananSuratCetakService();
    }

    #[Test]
    public function mengembalikan_surat_jika_milik_penduduk_yang_login(): void
    {
        $surat = LogSuratFactory::new()->create(['id_pend' => 101]);

        $hasil = $this->service->milikPenduduk($surat->id, 101);

        $this->assertSame($surat->id, $hasil->id);
    }

    #[Test]
    public function melempar_model_not_found_jika_surat_milik_penduduk_lain(): void
    {
        $suratMilikOrangLain = LogSuratFactory::new()->create(['id_pend' => 101]);

        $this->expectException(ModelNotFoundException::class);
        $this->service->milikPenduduk($suratMilikOrangLain->id, 202);
    }

    #[Test]
    public function melempar_model_not_found_jika_id_surat_tidak_ditemukan(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->service->milikPenduduk(999999, 101);
    }

    #[Test]
    public function tidak_bisa_diakses_dengan_mengiterasi_id_secara_berurutan(): void
    {
        $suratWargaLain = [];
        for ($i = 0; $i < 5; $i++) {
            $suratWargaLain[] = LogSuratFactory::new()->create(['id_pend' => 500 + $i]);
        }

        $penyerang = 999;
        $tercuri   = 0;

        foreach ($suratWargaLain as $surat) {
            try {
                $this->service->milikPenduduk($surat->id, $penyerang);
                $tercuri++;
            } catch (ModelNotFoundException $e) {
                // diharapkan: surat warga lain tidak boleh terbaca
            }
        }

        $this->assertSame(0, $tercuri, 'Tidak boleh ada surat warga lain yang berhasil diakses lewat iterasi ID');
    }
}
