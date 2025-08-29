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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Feature\Helpers;

use Tests\BaseTestCase;
use Illuminate\Support\Facades\Cache;

final class CoreHelperTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // if (! function_exists('config_item')) {
        //     /**
        //      * Cek status anjungan.
        //      */
        //     function config_item($key)
        //     {
        //         return match ($key) {
        //             'demo_mode' => true,
        //             default => null,
        //         };
        //     }
        // }

        require_once __DIR__ . '/../../../donjo-app/helpers/core_helper.php';
    }

    public function testVersionDefined()
    {
        $this->assertTrue(defined('VERSION'));
    }

    public function testPremiumDefined()
    {
        $this->assertTrue(defined('PREMIUM'));
    }

    public function testVersiDatabaseDefined()
    {
        $this->assertTrue(defined('VERSI_DATABASE'));
    }

    public function testMinimumVersiDefined()
    {
        $this->assertTrue(defined('MINIMUM_VERSI'));
    }

    public function testWebsiteDemoDefined()
    {
        $this->assertTrue(defined('WEBSITE_DEMO'));
    }

    public function testModulBawaanDefined()
    {
        $this->assertTrue(defined('MODUL_BAWAAN'));
    }

    public function testCekAnjunganInDevelopment()
    {
        define('ENVIRONMENT', 'development');

        $this->assertTrue(cek_anjungan());
    }

    public function testCekAnjunganInDemo()
    {
        define('APP_URL', 'opensid.test');

        $this->asserttrue(cek_anjungan());
    }

    public function testCekAnjunganWithCacheTrue()
    {
        Cache::put('license_anjungan', true, 60);

        $this->assertTrue(cek_anjungan());
    }

    public function testCekAnjunganWithCacheFalse()
    {
        Cache::put('license_anjungan', false, 60);

        $this->assertTrue(cek_anjungan());
    }

    public function testDesaStorage()
    {
        define('DESAPATH', 'desa');

        $this->assertEquals('desa' . DIRECTORY_SEPARATOR . 'file.txt', desa_storage('file.txt'));
    }
}
