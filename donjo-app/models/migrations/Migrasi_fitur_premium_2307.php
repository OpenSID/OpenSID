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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2307 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2306', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        // Data perlu dihapus karena ada perubahan struktur tabel
        $hasil = $hasil && $this->migrasi_2023060451($hasil);
        $hasil = $hasil && $this->migrasi_2023060452($hasil);

        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        // $config_id = DB::table('config')->pluck('id')->toArray();

        // foreach ($config_id as $id) {
        //     $hasil = $hasil && $this->migrasi_xxxxxxxxxx($hasil, $id);
        // }

        // Migrasi tanpa config_id
        return $hasil && $this->migrasi_xxxxxxxxxx($hasil);
    }

    protected function migrasi_2023060451($hasil)
    {
        DB::table('log_penduduk')->whereNotIn('id_pend', static function ($q) {
            return $q->select('id')->from('tweb_penduduk');
        })->delete();

        return $hasil;
    }

    protected function migrasi_2023060452($hasil)
    {
        $db    = $this->db->database;
        $query = "
            SELECT COUNT(1) ConstraintSudahAda
            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = 'log_penduduk'
            AND CONSTRAINT_NAME = 'fk_tweb_penduduk'
        ";
        $checkConstraint = DB::select($query, [$db])[0];
        if ($checkConstraint->ConstraintSudahAda <= 0) {
            DB::statement('alter table log_penduduk add CONSTRAINT fk_tweb_penduduk foreign key (id_pend) REFERENCES tweb_penduduk(id) ON UPDATE CASCADE ON DELETE CASCADE');
        }

        return $hasil;
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }
}
