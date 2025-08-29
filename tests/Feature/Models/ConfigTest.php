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

namespace Tests\Feature;

use App\Models\Config;
use Tests\BaseTestCase;
use Database\Seeders\ConfigSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
final class ConfigTest extends BaseTestCase
{
    use RefreshDatabase;

    private $appKey = 'base64:fTc4df0qWY59nmxJDX/ZJu4tI+JIyC7w63WP2q5FBQk=';
    private $config;

    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../donjo-app/helpers/core_helper.php';

        $this->config = [
            'app_key'           => $this->appKey,
            'nama_desa'         => '',
            'kode_desa'         => '',
            'nama_kecamatan'    => '',
            'kode_kecamatan'    => '',
            'nama_kabupaten'    => '',
            'kode_kabupaten'    => '',
            'nama_propinsi'     => '',
            'kode_propinsi'     => '',
            'nama_kepala_camat' => '',
            'nip_kepala_camat'  => '',
        ];

        Model::withoutEvents(function () {
            Config::create($this->config);
        });
    }

    public function testConfigTableExists()
    {
        $this->assertTrue(Schema::hasTable('config'));
    }

    public function testCreateConfig()
    {
        $newConfig = array_merge($this->config, [
            'app_key' => 'base64:1234567890',
            'kode_desa' => '12345678910',
            'kode_kecamatan' => '123456',
            'kode_kabupaten' => '1234',
            'kode_propinsi' => '12',
        ]);

        Model::withoutEvents(function () use ($newConfig) {
            Config::create($newConfig);
        });

        $this->assertDatabaseHas('config', $newConfig);
    }

    public function testUpdateConfig()
    {
        $updatedData = ['nama_desa' => 'Desa Baru'];

        Config::appKey($this->appKey)->update($updatedData);

        $newConfig = array_merge($this->config, $updatedData);

        $this->assertDatabaseHas('config', $newConfig);
    }

    public function testDeleteConfig()
    {
        Config::appKey($this->appKey)->delete();

        $this->assertDatabaseMissing('config', $this->config);
    }
}
