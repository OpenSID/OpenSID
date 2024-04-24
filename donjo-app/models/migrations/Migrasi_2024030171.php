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

use App\Imports\KlasifikasiSuratImports;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024030171 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024020651($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024020652($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024021351($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024022271($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2024020551($hasil);
        $hasil = $hasil && $this->migrasi_2024130201($hasil);

        return $hasil && $this->migrasi_2024210201($hasil);
    }

    protected function migrasi_2024020551($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'buku-lembaran-dan-berita-desa', 'url' => 'lembaran_desa/clear'],
            ['url' => 'lembaran_desa']
        );

        DB::table('setting_modul')
            ->whereIn('slug', ['log-penduduk', 'catatan-peristiwa'])
            ->update(['slug' => 'peristiwa']);

        return $hasil;
    }

    public function migrasi_2024020651($hasil, $id)
    {
        if (! DB::table('widget')->where('config_id', $id)->where('isi', 'jam_kerja.php')->exists()) {
            DB::table('widget')->insert([
                'config_id'    => $id,
                'isi'          => 'jam_kerja.php',
                'enabled'      => 2,
                'judul'        => 'Jam Kerja',
                'jenis_widget' => 1,
                'urut'         => DB::table('widget')->where('config_id', $id)->latest('urut')->value('urut') + 1,
                'form_admin'   => null,
                'setting'      => null,
                'foto'         => null,
            ]);
        }

        return $hasil;
    }

    public function migrasi_2024020652($hasil, $id)
    {
        if (DB::table('kategori')->where('config_id', $id)->count() === 0) {
            DB::table('kategori')->insert([
                'config_id' => $id,
                'kategori'  => 'Berita Desa',
                'tipe'      => 1,
                'urut'      => DB::table('kategori')->where('config_id', $id)->latest('urut')->value('urut') + 1,
                'enabled'   => 1,
                'parrent'   => 0,
                'slug'      => 'berita-desa',
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2024021351($hasil, $id)
    {
        DB::table('setting_aplikasi')
            ->where('config_id', $id)
            ->where('key', 'ukuran_lebar_bagan')
            ->update(['kategori' => 'Pemerintah Desa']);

        return $hasil;
    }

    protected function migrasi_2024130201($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'buku-eskpedisi', 'url' => 'ekspedisi/clear'],
            ['url' => 'ekspedisi']
        );
    }

    protected function migrasi_2024210201($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'administrasi-penduduk', 'url' => 'bumindes_penduduk_induk/clear'],
            ['url' => 'bumindes_penduduk_induk']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'buku-mutasi-penduduk', 'url' => 'bumindes_penduduk_mutasi/clear'],
            ['url' => 'bumindes_penduduk_mutasi']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'buku-penduduk-sementara', 'url' => 'bumindes_penduduk_sementara/clear'],
            ['url' => 'bumindes_penduduk_sementara']
        );
    }

    protected function migrasi_2024022271($hasil, $id)
    {
        $hasil = $hasil && $this->dbforge->modify_column('klasifikasi_surat', [
            'nama' => [
                'type' => 'TEXT',
                'null' => false,
            ],
        ]);

        if (DB::table('klasifikasi_surat')->where('config_id', $id)->count() === 0) {
            (new KlasifikasiSuratImports())->import();
        }

        return $hasil;
    }
}
