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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024100951 extends MY_model
{
    public function up()
    {
        $hasil = true;
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $this->migrasi_2024100351($hasil, $id);
            $hasil = $this->migrasi_2024100852($hasil, $id);
        }

        return $this->migrasi_2024100851($hasil);
    }

    private function migrasi_2024100351($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Versi Umum Setara',
            'key'        => 'compatible_version_general',
            'value'      => null,
            'keterangan' => 'Versi Umum Yang Setara',
            'jenis'      => 'text',
            'attribute'  => null,
            'kategori'   => 'default',
        ], $id);
    }

    protected function migrasi_2024100851($hasil)
    {
        if (! Schema::hasColumn('log_notifikasi_mandiri', 'token')) {
            Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
                $table->longText('token')->nullable()->after('isi');
            });
        }

        if (! Schema::hasColumn('log_notifikasi_mandiri', 'device')) {
            Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
                $table->longText('device')->nullable()->after('token');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024100852($hasil, $id)
    {
        // Panggil disaat ada perubahan pada form surat bawaan saja
        restoreSuratBawaanTinyMCE($id);
        restoreSuratBawaanDinasTinyMCE($id);

        return $hasil;
    }
}
