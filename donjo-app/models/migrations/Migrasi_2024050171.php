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

use App\Models\SettingAplikasi;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024050171 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        (new Filesystem())->copyDirectory('vendor/tecnickcom/tcpdf/fonts', LOKASI_FONT_DESA);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2024040451($hasil);

        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024040571($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024041671($hasil, $id);
        }

        $hasil = $hasil && $this->migrasi_2024032052($hasil);
        $hasil = $hasil && $this->migrasi_20240401471($hasil);
        $hasil = $hasil && $this->migrasi_2024042351($hasil);
        $hasil = $hasil && $this->migrasi_2024041951($hasil);

        return $hasil && $this->migrasi_2024040271($hasil);
    }

    protected function migrasi_2024032052($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'buku-tanah-kas-desa', 'url' => 'bumindes_tanah_kas_desa/clear'],
            ['url' => 'bumindes_tanah_kas_desa']
        );
    }

    protected function migrasi_20240401471($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'arsip-surat-dinas', 'modul' => 'Arsip Layanan'],
            ['modul' => 'Arsip Surat Dinas']
        );
    }

    protected function migrasi_2024041951($hasil)
    {
        DB::table('setting_aplikasi')->whereIn('key', [
            'mapbox_key',
            'jenis_peta',
            'tampil_luas_peta',
            'min_zoom_peta',
            'max_zoom_peta',
            'tampilkan_tombol_peta',
            'default_tampil_peta_wilayah',
            'default_tampil_peta_infrastruktur',
        ])->update(['kategori' => 'peta']);

        return $hasil;
    }

    protected function migrasi_2024042351($hasil)
    {
        if ($this->cek_indeks('kelompok', 'kode_config')) {
            Schema::table('kelompok', static function ($table) {
                $table->dropUnique('kode_config');
                $table->unique(['config_id', 'kode', 'tipe'], 'config_kode_tipe');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024040271($hasil)
    {
        $penduduk_luar     = SettingAplikasi::where('key', '=', 'form_penduduk_luar')->first();
        $value             = json_decode($penduduk_luar->value, true);
        $value[3]['input'] = 'nama,no_ktp,tempat_lahir,tanggal_lahir,jenis_kelamin,agama,pendidikan_kk,pekerjaan,warga_negara,alamat,golongan_darah,status_perkawinan,tanggal_perkawinan,shdk,no_paspor,no_kitas,nama_ayah,nama_ibu';
        $penduduk_luar->update(['value' => json_encode($value)]);

        return $hasil && $this->migrasi_2024042751($hasil);
    }

    protected function migrasi_2024042751($hasil)
    {
        DB::table('menu')->where('enabled', 2)->update(['enabled' => 0]);

        return $hasil;
    }

    protected function migrasi_2024040571($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Sebutan Anjungan Mandiri',
            'key'        => 'sebutan_anjungan_mandiri',
            'value'      => 'Anjungan [desa] Mandiri',
            'keterangan' => 'Pengaturan sebutan anjungan mandiri',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'anjungan',
        ], $id);
    }

    public function migrasi_2024041671($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Icon Lapak Peta',
            'key'        => 'icon_lapak_peta',
            'value'      => 'fastfood.png',
            'keterangan' => 'Icon penanda Lapak yang ditampilkan pada Peta',
            'jenis'      => 'select-simbol',
            'option'     => json_encode(['model' => 'App\\Models\\Simbol', 'value' => 'simbol', 'label' => 'simbol']),
            'attribute'  => 'class="form-control input-sm select2-icon-img required" data-lokasi="' . base_url(LOKASI_SIMBOL_LOKASI) . '"',
            'kategori'   => 'lapak',
        ], $id);
    }

    protected function migrasi_2024040451($hasil)
    {
        if (! Schema::hasColumn('user', 'batasi_wilayah')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->unsignedTinyInteger('batasi_wilayah')->default(0);
                $table->text('akses_wilayah')->nullable();
            });
        }

        return $hasil;
    }
}
