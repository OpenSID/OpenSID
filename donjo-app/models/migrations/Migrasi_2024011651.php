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
use App\Models\Suplemen;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024011651 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && $this->migrasi_2024011251($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi tanpa config_id
        $hasil = $this->migrasi_2024011451($hasil);
        $hasil = $this->migrasi_2024011551($hasil);

        $hasil = $hasil && $this->migrasi_2024011051($hasil);

        return $hasil && $this->migrasi_2024011052($hasil);
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

    protected function migrasi_2024011052($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'pemerintah-desa', 'url' => 'pengurus/clear'],
            ['url' => 'pengurus']
        );
    }

    protected function migrasi_2024011251($hasil)
    {
        $hasil = $hasil && $this->hapus_foreign_key('lokasi', 'pembangunan_lokasi_fk', 'pembangunan');

        return $hasil && $this->tambahForeignKey('pembangunan_lokasi_cluster_fk', 'pembangunan', 'id_lokasi', 'tweb_wil_clusterdesa', 'id', true);
    }

    protected function migrasi_2024011451($hasil)
    {
        $tanpaSlug = Suplemen::whereNull('slug')->get();
        if ($tanpaSlug) {
            foreach ($tanpaSlug as $slug) {
                $slug->update();
            }
        }

        return $hasil;
    }

    protected function migrasi_2024011551($hasil)
    {
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_tanah', 'FK_mutasi_inventaris_tanah', 'mutasi_inventaris_tanah');
        $hasil && $this->tambahForeignKey('mutasi_inventaris_tanah_inventaris_tanah_fk', 'mutasi_inventaris_tanah', 'id_inventaris_tanah', 'inventaris_tanah', 'id', true);
        $hasil = $hasil && $this->hapus_foreign_key('suplemen', 'suplemen_terdata_ibfk_1', 'suplemen_terdata');
        $hasil = $hasil && $this->tambahForeignKey('suplemen_terdata_suplemen_fk', 'suplemen_terdata', 'id_suplemen', 'suplemen', 'id', true);

        // hapus salah satu foreignkey karena dobel
        return $hasil && $this->hapus_foreign_key('tweb_penduduk', 'id_pend_fk', 'tweb_penduduk_mandiri');
    }
}
