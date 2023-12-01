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
        return $hasil && $this->migrasi_2023120151($hasil);
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
        return $hasil && $this->migrasi_2023112852($hasil);
    }

    protected function migrasi_2023112852($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'klasifikasi-surat', 'url' => 'klasifikasi/clear'],
            ['url' => 'klasifikasi']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'polygon', 'url' => 'polygon/clear'],
            ['url' => 'polygon']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'area', 'url' => 'area/clear'],
            ['url' => 'area']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'garis', 'url' => 'garis/clear'],
            ['url' => 'garis']
        );
    }
    protected function migrasi_2023120151($hasil)
    {        
        $this->tambahIndeks('klasifikasi_surat', 'config_id, kode', 'UNIQUE', true);        
        return $hasil;
    }
    protected function migrasi_xxxxxxxxxx($hasil)
    {
        return $hasil;
    }
}
