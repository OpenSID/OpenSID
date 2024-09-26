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

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024092651 extends MY_model
{
    public function up()
    {
        $hasil = $this->migrasi_2024090551(true);
        $hasil = $this->migrasi_2024092051($hasil);

        return $hasil && $this->migrasi_2024092151($hasil);
    }

    protected function migrasi_2024090551($hasil)
    {
        DB::table('setting_aplikasi')
            ->whereIn('key', ['sebutan_dusun', 'sebutan_singkatan_kadus'])
            ->where('kategori', '!=', 'Wilayah Administratif')
            ->update(['kategori' => 'Wilayah Administratif']);

        DB::table('setting_aplikasi')
            ->where('key', 'sebutan_singkatan_kadus')
            ->update([
                'key'        => 'sebutan_kepala_dusun',
                'keterangan' => 'Sebutan Kepala Dusun',
            ]);

        return $hasil;
    }

    public function migrasi_2024092051($hasil)
    {
        DB::table('widget')
            ->where('form_admin', 'web/tab/1000')
            ->update(['form_admin' => 'web/agenda']);

        return $hasil;
    }

    public function migrasi_2024092151($hasil)
    {
        $hasil = $hasil && checkAndFixTable('log_notifikasi_admin');

        return $hasil && checkAndFixTable('log_notifikasi_mandiri');
    }
}
