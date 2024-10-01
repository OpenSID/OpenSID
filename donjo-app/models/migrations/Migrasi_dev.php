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

use App\Models\PendudukMandiri;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dev extends MY_model
{
    public function up()
    {
        $hasil = true;
        $hasil = $hasil && $this->migrasi_2024031451($hasil);
        $hasil = $hasil && $this->migrasi_2024031851($hasil);
        $hasil = $hasil && $this->migrasi_2024032051($hasil);

        return $hasil && true;
    }

    protected function migrasi_2024031451($hasil)
    {
        if (! $this->db->field_exists('input', 'log_surat')) {
            $hasil = $hasil && $this->db->query('ALTER TABLE `log_surat` ADD COLUMN `input` LONGTEXT NULL AFTER `pemohon`');
        }

        return $hasil;
    }

    protected function migrasi_2024031851($hasil)
    {
        PendudukMandiri::whereDoesntHave('penduduk')->delete();
        $hasil && $this->tambahForeignKey('tweb_penduduk_mandiri_penduduk_fk', 'tweb_penduduk_mandiri', 'id_pend', 'tweb_penduduk', 'id', false, true);

        return $hasil;
    }

    protected function migrasi_2024032051($hasil)
    {
        DB::table('setting_modul')->where('slug', 'beranda')->delete();

        return $hasil;
    }
}
