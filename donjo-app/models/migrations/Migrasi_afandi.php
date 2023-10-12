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

class Migrasi_afandi extends MY_model
{
    public function up()
    {
        $hasil = true;
        $hasil = $hasil && $this->migrasi_data($hasil);

        return $hasil && $this->migrasi_tabel($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && $this->migrasi_2023101252($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        return $hasil && $this->migrasi_2023101251($hasil);
    }

    protected function migrasi_2023101251($hasil)
    {
        $query = 'update config set kode_desa = SUBSTRING(kode_desa,1, 10), kode_kecamatan = SUBSTRING(kode_kecamatan,1, 6), kode_kabupaten = SUBSTRING(kode_kabupaten,1, 4), kode_propinsi = SUBSTRING(kode_propinsi,1, 2) ';
        DB::statement($query);

        return $hasil;
    }

    protected function migrasi_2023101252($hasil)
    {
        $queryKodeDesa      = 'alter table config MODIFY COLUMN kode_desa varchar(10)';
        $queryKodeKecamatan = 'alter table config MODIFY COLUMN kode_kecamatan varchar(6)';
        $queryKodeKabupaten = 'alter table config MODIFY COLUMN kode_kabupaten varchar(4)';
        $queryKodePropinsi  = 'alter table config MODIFY COLUMN kode_propinsi varchar(2)';
        DB::statement($queryKodeDesa);
        DB::statement($queryKodeKecamatan);
        DB::statement($queryKodeKabupaten);
        DB::statement($queryKodePropinsi);

        return $hasil;
    }
}
