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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev extends MY_model
{
    public function up()
    {
        $hasil = true;
        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        // foreach ($config_id as $id) {
        // }

        $hasil = $hasil && $this->migrasi_2024051253($hasil);
        $hasil = $hasil && $this->migrasi_2024060152($hasil);

        return $hasil && true;
    }

    protected function migrasi_2024051253($hasil)
    {
        Schema::table('alias_kodeisian', static function (Blueprint $table) {
            $table->string('judul', 20)->change();
        });

        return $hasil;
    }

    protected function migrasi_2024060152($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'pengaturan-analisis'],
            ['url' => 'setting_analisis']
        );
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'pengaturan-web'],
            ['url' => 'setting_web']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'pengaturan-layanan-mandiri'],
            ['url' => 'setting_mandiri']
        );
    }
}
