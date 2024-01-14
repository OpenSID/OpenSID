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

use App\Models\Galery;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dev extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2024011251($hasil);

        return $hasil && $this->migrasi_2024011471($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024011371($hasil, $id);
        }

        // Migrasi tanpa config_id

        return $hasil && $this->migrasi_2024011051($hasil);
    }

    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }

    protected function migrasi_2024011051($hasil)
    {
        // ubah status enabled menjadi 0 untuk nonaktif, sebelumnya 2
        Galery::where(['enabled' => 2])->update(['enabled' => 0]);

        return $hasil && $this->ubah_modul(
            ['slug' => 'galeri', 'url' => 'gallery/clear'],
            ['url' => 'gallery']
        );
    }

    protected function migrasi_2024011251($hasil)
    {
        $hasil = $hasil && $this->hapus_foreign_key('lokasi', 'pembangunan_lokasi_fk', 'pembangunan');

        return $hasil && $this->tambahForeignKey('pembangunan_lokasi_cluster_fk', 'pembangunan', 'id_lokasi', 'tweb_wil_clusterdesa', 'id', true);
    }

    protected function migrasi_2024011371($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Artikel Statis / Halaman',
            'key'        => 'artikel_statis',
            'value'      => json_encode(['statis', 'agenda', 'keuangan']),
            'keterangan' => 'Artikel Statis / Halaman yang akan ditampilkan pada halaman utama.',
            'kategori'   => 'conf_web',
            'jenis'      => 'multiple-option-key',
            'option'     => json_encode([
                'statis'   => 'Halaman Statis',
                'agenda'   => 'Agenda',
                'keuangan' => 'Keuangan',
            ]),
        ], $id);
    }

    protected function migrasi_2024011471($hasil)
    {
        if (! $this->db->field_exists('tampilan', 'artikel')) {
            $hasil = $hasil && $this->db->query("ALTER TABLE `artikel` ADD COLUMN `tampilan` TINYINT(4) NULL DEFAULT '1' AFTER `hit`");
        }

        return $hasil;
    }
}
