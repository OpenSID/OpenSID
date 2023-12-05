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

class Migrasi_fitur_premium_2401 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2312', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && $this->migrasi_2023120351($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023120552($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023120451($hasil);

        return $hasil && $this->migrasi_2023120553($hasil);
    }

    protected function migrasi_2023120451($hasil)
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

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'garis', 'url' => 'garis/clear'],
            ['url' => 'garis']
        );

        $hasil = $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'line', 'url' => 'line/clear'],
            ['url' => 'line']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'arsip-layanan', 'url' => 'keluar/clear/masuk'],
            ['url' => 'keluar/clear']
        );
    }

    protected function migrasi_2023120351($hasil)
    {
        $this->tambahIndeks('klasifikasi_surat', 'config_id, kode', 'UNIQUE', true);

        return $hasil;
    }

    protected function migrasi_2023120552($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul' => 'Notifikasi Reset PIN',
            'key'   => 'notifikasi_reset_pin',
            'value' => 'HALO [nama],
            BERIKUT ADALAH KODE PIN YANG BARU SAJA DIHASILKAN,
            KODE PIN INI SANGAT RAHASIA
            JANGAN BERIKAN KODE PIN KEPADA SIAPA PUN,
            TERMASUK PIHAK YANG MENGAKU DARI DESA ANDA.
            KODE PIN: [pin]
            JIKA BUKAN ANDA YANG MELAKUKAN RESET PIN TERSEBUT
            SILAHKAN LAPORKAN KEPADA OPERATOR DESA
            LINK : [website]',
            'keterangan' => 'Pesan notifikasi reset PIN',
            'jenis'      => 'textarea',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ], $id);
    }

    protected function migrasi_2023120553($hasil)
    {
        DB::table('tweb_penduduk')->where('kk_level', 0)->update(['kk_level' => null]);

        return $hasil;
    }
}
