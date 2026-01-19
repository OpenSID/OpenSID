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

namespace Modules\Analisis\Database\Seeders;

use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->createSettings([
            [
                'judul'      => 'Api Gform Id Script',
                'key'        => 'api_gform_id_script',
                'value'      => null,
                'keterangan' => 'Script ID untuk Google API',
                'jenis'      => 'text',
                'kategori'   => 'Analisis',
            ],
            [
                'judul'      => 'Api Gform Credential',
                'key'        => 'api_gform_credential',
                'value'      => null,
                'keterangan' => 'Credential untuk Google API',
                'jenis'      => 'textarea',
                'kategori'   => 'Analisis',
            ],
            [
                'judul'      => 'Api Gform Redirect Uri',
                'key'        => 'api_gform_redirect_uri',
                'value'      => 'https://berputar.opendesa.id/index.php/first/get_form_info',
                'keterangan' => 'Redirect URI untuk Google API',
                'jenis'      => 'text',
                'kategori'   => 'Analisis',
            ],
        ]);
    }
}
