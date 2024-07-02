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
        // Uncomment pada rilis rev terakhir
        // return $hasil && $this->buat_tabel_migrations($hasil);

        $hasil = $hasil && $this->migrasi_2023120351($hasil);

        return $hasil && $this->migrasi_2023120752($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023120552($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023120554($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023120451($hasil);
        $hasil = $hasil && $this->migrasi_2023120553($hasil);

        return $hasil && $this->migrasi_2023120751($hasil);
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

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'widget', 'url' => 'web_widget/clear'],
            ['url' => 'web_widget']
        );

        $hasil = $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'line', 'url' => 'line/clear'],
            ['url' => 'line']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'point', 'url' => 'point/clear'],
            ['url' => 'point']
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

    protected function migrasi_2023120554($hasil, $config_id)
    {
        return $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Simbol',
            'slug'       => 'simbol',
            'url'        => 'simbol',
            'aktif'      => 1,
            'ikon'       => 'fa-location-arrow',
            'urut'       => 3,
            'level'      => 1,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-location-arrow',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => $config_id, 'slug' => 'simbol'])->row()->id,
        ]);
    }

    protected function migrasi_2023120751($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'data-suplemen', 'url' => 'suplemen/clear'],
            ['url' => 'suplemen']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'wilayah-administratif', 'url' => 'wilayah/clear'],
            ['url' => 'wilayah']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'pengunjung', 'url' => 'pengunjung/clear'],
            ['url' => 'pengunjung']
        );
    }

    protected function migrasi_2023120752($hasil)
    {
        $this->db->query('ALTER TABLE config MODIFY path LONGTEXT DEFAULT NULL;');

        return $hasil;
    }

    protected function buat_tabel_migrations($hasil)
    {
        if (! Schema::hasTable('migrations')) {
            Schema::create('migrations', static function (Blueprint $table): void {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }

        return $hasil;
    }
}
