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
use App\Models\SettingAplikasi;
use Database\Seeders\ConfigSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\SettingSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
final class SettingAplikasiTest extends BaseTestCase
{
    use RefreshDatabase;

    private $configId = 1;
    private $setting;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setting = [
            'config_id'  => 1,
            'judul'      => 'Contoh Setting',
            'key'        => 'contoh_setting',
            'value'      => 'contoh',
            'keterangan' => 'Contoh setting aplikasi',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ];

        Model::withoutEvents(function () {
            SettingAplikasi::create($this->setting);
        });
    }

    public function testConfigTableExists()
    {
        $this->assertTrue(Schema::hasTable('setting_aplikasi'));
    }

    public function testCreateSetting()
    {
        $newSetting = array_merge($this->setting, [
            'config_id'  => 1,
            'judul'      => 'Contoh Setting Baru',
            'key'        => 'contoh_setting_baru',
            'value'      => 'contoh',
            'keterangan' => 'Contoh baru setting aplikasi',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ]);

        Model::withoutEvents(function () use ($newSetting) {
            SettingAplikasi::create($newSetting);
        });

        $this->assertDatabaseHas('setting_aplikasi', $newSetting);
    }

    public function testUpdateSetting()
    {
        $updatedData = ['kategori' => 'contoh'];

        SettingAplikasi::withoutConfigId($this->configId)->update($updatedData);

        $newSetting = array_merge($this->setting, $updatedData);

        $this->assertDatabaseHas('setting_aplikasi', $newSetting);
    }

    public function testDeleteSetting()
    {
        SettingAplikasi::withoutConfigId($this->configId)->delete();

        $this->assertDatabaseMissing('setting_aplikasi', ['config_id' => $this->configId]);
    }
}
