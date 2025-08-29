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

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\BaseTestCase;

/**
 * @internal
 */
final class Rilis250200Test extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Hapus kolom border di tabel tweb_wil_clusterdesa untuk test migrasi
        Schema::table('tweb_wil_clusterdesa', function ($table) {
            $table->dropColumn('border');
        });

        require_once realpath(__DIR__ . '/../../../donjo-app/models/migrations/Migrasi_2025020171.php');

        $migration = new \Migrasi_2025020171();
        // $migration->tambahKolomDataFormIsian();
        $migration->ubahKolomUserAgent();
        $migration->tambahKolomDiArtikel();
        // $migration->hapusTabelRefPendudukSuku();
        $migration->tambahKolomBorderDiWilayah();
        // $migration->dropColumnStatusProgramBantuan();
    }

    // Start Issue P4384
    public function testTableArtikelExists()
    {
        $this->assertTrue(Schema::hasTable('artikel'));
    }

    public function testColumnUrutExists()
    {
        $this->assertTrue(Schema::hasColumn('artikel', 'urut'));
    }

    public function testColumnJenisWidgetExists()
    {
        $this->assertTrue(Schema::hasColumn('artikel', 'jenis_widget'));
    }
    // End Issue P4384

    // Start Issue U8854
    public function testTableLogLoginExists()
    {
        $this->assertTrue(Schema::hasTable('log_login'));
    }

    public function testColumnUserAgentExists()
    {
        $this->assertTrue(Schema::hasColumn('log_login', 'user_agent'));
    }

    public function testModifyColumnTypeUserAgent()
    {
        $this->assertSame('text', Schema::getColumnType('log_login', 'user_agent'));
    }
    // End Issue U8854

    // Start Issue U8866
    public function testTableTwebWilClusterdesaExists()
    {
        $this->assertTrue(Schema::hasTable('tweb_wil_clusterdesa'));
    }

    public function testColumnBorderExists()
    {
        $this->assertTrue(Schema::hasColumn('tweb_wil_clusterdesa', 'border'));
    }
    // End Issue U8866
}
