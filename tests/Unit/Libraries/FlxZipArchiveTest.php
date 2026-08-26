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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Libraries;

use App\Libraries\FlxZipArchive;
use Tests\BaseTestCase;
use ZipArchive;

/**
 * Mirror dari premium#6964 (OpenSID/premium teknis/anjungan-kiosk-seam):
 * FlxZipArchive::addDirDo() (dipakai Job::backup_inkremental()) sebelumnya
 * mengikuti symlink lewat filetype() (resolve ke 'dir' untuk symlink ke
 * folder) — menyalin utuh isi folder TARGET ke backup satu tenant. Berbahaya
 * untuk kategori C SiapPakai (`desa/themes/<tema>` → symlink ke
 * `master-tema-pro/<tema>`, kode vendor yang sengaja hanya satu salinan
 * bersama). Pengujian ini memastikan symlink dilewati (tidak diikuti) dan
 * dicatat di manifest arsip, bukan hilang tanpa jejak.
 */
class FlxZipArchiveTest extends BaseTestCase
{
    private string $base;

    protected function setUp(): void
    {
        parent::setUp();

        // get_file_info() adalah helper bawaan CodeIgniter 3 (dimuat via
        // Job::backup_inkremental() lewat $this->load->helper(['number', 'file'])
        // saat runtime nyata) — tak tersedia di konteks Unit test murni tanpa CI3.
        if (! function_exists('get_file_info')) {
            require_once base_path('vendor/codeigniter/framework/system/helpers/file_helper.php');
        }

        $this->base = sys_get_temp_dir() . '/flxzip-umum-' . uniqid('', true);
        mkdir($this->base . '/plain', 0777, true);
        file_put_contents($this->base . '/plain/file.txt', 'isi biasa');

        // Simulasikan kategori C SiapPakai: folder master di LUAR pohon yang
        // di-backup, symlink di dalamnya menunjuk ke folder master tsb.
        mkdir($this->base . '/master-tema-pro/tema-x', 0777, true);
        file_put_contents($this->base . '/master-tema-pro/tema-x/vendor.php', 'kode vendor');
        symlink($this->base . '/master-tema-pro/tema-x', $this->base . '/plain/tema-symlink');
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->base);
        parent::tearDown();
    }

    public function test_read_dir_melewati_symlink_dan_mencatatnya_di_manifest(): void
    {
        $za  = new FlxZipArchive();
        $out = $za->read_dir($this->base);

        $this->assertNotNull($out, 'read_dir() harus berhasil membuat arsip');

        $zip = new ZipArchive();
        $this->assertTrue($zip->open((string) $out) === true);

        $root = basename($this->base);

        // File biasa tetap tersalin.
        $this->assertNotFalse($zip->locateName($root . '/plain/file.txt'), 'file biasa harus ada di arsip');

        // Isi folder TARGET symlink TIDAK boleh tersalin.
        $this->assertFalse(
            $zip->locateName($root . '/plain/tema-symlink/vendor.php'),
            'isi folder di balik symlink tidak boleh ikut ter-backup'
        );

        // Manifest symlink yang dilewati harus ada dan mencatat target aslinya.
        $manifestIdx = $zip->locateName($root . '/SYMLINK_DILEWATI.json');
        $this->assertNotFalse($manifestIdx, 'manifest symlink yang dilewati harus ditulis ke arsip');

        $manifest    = json_decode((string) $zip->getFromName($root . '/SYMLINK_DILEWATI.json'), true);
        $manifestKey = $root . '/plain/tema-symlink';
        $this->assertIsArray($manifest);
        $this->assertArrayHasKey($manifestKey, $manifest);
        $this->assertSame(
            $this->base . '/master-tema-pro/tema-x',
            $manifest[$manifestKey]
        );

        $zip->close();
        @unlink((string) $out);
    }

    public function test_read_dir_tanpa_symlink_tidak_menulis_manifest(): void
    {
        @unlink($this->base . '/plain/tema-symlink');
        $this->removeDir($this->base . '/master-tema-pro');

        $za  = new FlxZipArchive();
        $out = $za->read_dir($this->base);

        $zip = new ZipArchive();
        $this->assertTrue($zip->open((string) $out) === true);

        $root = basename($this->base);
        $this->assertFalse($zip->locateName($root . '/SYMLINK_DILEWATI.json'), 'tanpa symlink, tak perlu manifest');
        $this->assertSame([], $za->symlinkDilewati);

        $zip->close();
        @unlink((string) $out);
    }

    private function removeDir(string $dir): void
    {
        if (is_link($dir)) {
            @unlink($dir);

            return;
        }

        if (! is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $dir . '/' . $entry;

            if (is_link($path)) {
                @unlink($path);

                continue;
            }

            is_dir($path) ? $this->removeDir($path) : @unlink($path);
        }

        @rmdir($dir);
    }
}
