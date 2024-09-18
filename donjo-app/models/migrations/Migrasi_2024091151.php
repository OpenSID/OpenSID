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

use App\Models\SettingAplikasi;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024091151 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024090551($hasil, $id);
        }
        $hasil = $this->migrasi_2024090951($hasil);

        return $hasil && $this->migrasi_2024090552($hasil);
    }

    protected function migrasi_2024090551($hasil, $config_id)
    {
        $this->db
            ->update(
                'setting_aplikasi',
                ['kategori' => 'Wilayah Administratif'],
                [
                    'config_id'   => $config_id,
                    'key'         => 'sebutan_dusun',
                    'kategori !=' => 'Wilayah Administratif',
                ]
            );

        return $hasil;
    }

    protected function migrasi_2024090552($hasil)
    {
        if (! Schema::hasColumn('log_notifikasi_admin', 'token')) {
            Schema::table('log_notifikasi_admin', static function (Blueprint $table) {
                $table->longText('token')->nullable()->after('isi');
            });
        }

        if (! Schema::hasColumn('log_notifikasi_admin', 'device')) {
            Schema::table('log_notifikasi_admin', static function (Blueprint $table) {
                $table->longText('device')->after('token');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024090951($hasil)
    {
        // pakai get, bisa jadi di database gabungan
        $penduduk_luar = SettingAplikasi::dontCache()->withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('key', '=', 'form_penduduk_luar')->get();
        if ($penduduk_luar) {
            foreach ($penduduk_luar as $key => $penduduk) {
                if ($penduduk) {
                    $penduduk->value = json_encode(updateIndex(json_decode($penduduk->value, true)), JSON_THROW_ON_ERROR);
                    $penduduk->save();
                }
            }
        }

        return $hasil;
    }
}
