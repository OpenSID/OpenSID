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
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Services\Theme;

use App\Services\Theme\KompatibilitasCoreTema;
use PHPUnit\Framework\TestCase;

/**
 * Gerbang kompatibilitas core tema (premium#7026). Menguji logika murni
 * ({@see KompatibilitasCoreTema::pesanRentang()} dan `pesanTakTerpenuhi()`)
 * lepas dari boot aplikasi.
 *
 * Catatan Umum: konstanta `VERSION` didefinisikan saat boot CI3
 * (`donjo-app/helpers/opensid_helper.php`), BUKAN via `composer.json`
 * `autoload.files` seperti di Premium. Di harness PHPUnit terisolasi Umum
 * konstanta itu tak ada, jadi test yang menyentuh `pesanTakTerpenuhi()`
 * (yang membaca `VERSION`) di-skip di sana — konsisten dengan tiadanya
 * `MigratorMinCoreTest` di Umum. Test `pesanRentang()` (perbandingan murni,
 * `$coreVersion` eksplisit) tetap jalan.
 */
class KompatibilitasCoreTemaTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir() . '/tema_corecompat_' . uniqid('', true);
        mkdir($this->dir);
    }

    private function lewatiBilaTanpaVersiCore(): void
    {
        if (! defined('VERSION')) {
            $this->markTestSkipped('Konstanta VERSION tak tersedia di harness terisolasi Umum (didefinisikan saat boot CI3).');
        }
    }

    protected function tearDown(): void
    {
        @array_map('unlink', glob($this->dir . '/*') ?: []);
        @rmdir($this->dir);
        parent::tearDown();
    }

    private function tulisManifest(array $meta): void
    {
        file_put_contents($this->dir . '/theme.json', json_encode($meta));
    }

    // --- pesanRentang(): perbandingan murni ---

    public function test_rentang_tanpa_batas_selalu_kompatibel(): void
    {
        $this->assertNull(KompatibilitasCoreTema::pesanRentang(null, null, '2610.0.0'));
        $this->assertNull(KompatibilitasCoreTema::pesanRentang('', '', '2610.0.0'));
    }

    public function test_rentang_core_di_bawah_min_core_ditolak(): void
    {
        $pesan = KompatibilitasCoreTema::pesanRentang('2610.0.0', null, '2609.0.0');
        $this->assertNotNull($pesan);
        $this->assertStringContainsString('minimal versi 2610.0.0', $pesan);
    }

    public function test_rentang_core_di_atas_max_core_ditolak(): void
    {
        $pesan = KompatibilitasCoreTema::pesanRentang(null, '2612.0.0', '2613.0.0');
        $this->assertNotNull($pesan);
        $this->assertStringContainsString('hingga OpenSID versi 2612.0.0', $pesan);
    }

    public function test_rentang_core_di_dalam_batas_kompatibel(): void
    {
        $this->assertNull(KompatibilitasCoreTema::pesanRentang('2610.0.0', '2699.0.0', '2610.0.0'));
        $this->assertNull(KompatibilitasCoreTema::pesanRentang('2610.0.0', '2699.0.0', '2650.5.0'));
    }

    public function test_min_core_baseline_2610_tidak_memblokir_lini_umum_27xx(): void
    {
        // Baseline awal: min_core = versi rilis 2610, max_core kosong. Umum
        // (27xx) tak boleh ikut terblokir karena 2709 > 2610 secara version_compare.
        $this->assertNull(KompatibilitasCoreTema::pesanRentang('2610.0.0', null, '2709.0.0'));
        $this->assertNull(KompatibilitasCoreTema::pesanRentang('2610.0.0', null, '2710.1.0'));
    }

    // --- pesanTakTerpenuhi(): pembacaan theme.json ---

    public function test_tanpa_manifest_tidak_memblokir(): void
    {
        $this->assertNull(KompatibilitasCoreTema::pesanTakTerpenuhi($this->dir));
    }

    public function test_manifest_tanpa_min_max_core_tidak_memblokir(): void
    {
        $this->lewatiBilaTanpaVersiCore();
        $this->tulisManifest(['name' => 'Tema Uji', 'version' => '1.0.0']);
        $this->assertNull(KompatibilitasCoreTema::pesanTakTerpenuhi($this->dir));
    }

    public function test_manifest_json_rusak_tidak_memblokir(): void
    {
        file_put_contents($this->dir . '/theme.json', '{ ini bukan json valid ');
        $this->assertNull(KompatibilitasCoreTema::pesanTakTerpenuhi($this->dir));
    }

    public function test_manifest_min_core_di_atas_versi_core_ditolak(): void
    {
        $this->lewatiBilaTanpaVersiCore();
        $this->tulisManifest(['min_core' => '9999.0.0']);
        $pesan = KompatibilitasCoreTema::pesanTakTerpenuhi($this->dir);
        $this->assertNotNull($pesan);
        $this->assertStringContainsString('9999.0.0', $pesan);
        $this->assertStringContainsString(VERSION, $pesan);
    }

    public function test_manifest_max_core_di_bawah_versi_core_ditolak(): void
    {
        $this->lewatiBilaTanpaVersiCore();
        $this->tulisManifest(['max_core' => '1.0.0']);
        $pesan = KompatibilitasCoreTema::pesanTakTerpenuhi($this->dir);
        $this->assertNotNull($pesan);
        $this->assertStringContainsString('1.0.0', $pesan);
    }

    public function test_manifest_rentang_mencakup_versi_core_kompatibel(): void
    {
        $this->lewatiBilaTanpaVersiCore();
        $this->tulisManifest(['min_core' => '1.0.0', 'max_core' => '9999.0.0']);
        $this->assertNull(KompatibilitasCoreTema::pesanTakTerpenuhi($this->dir));
    }
}
