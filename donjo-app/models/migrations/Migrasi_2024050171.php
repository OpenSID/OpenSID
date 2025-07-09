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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Modul;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024050171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $this->migrasi_tabel();

        (new Filesystem())->copyDirectory('vendor/tecnickcom/tcpdf/fonts', LOKASI_FONT_DESA);

        $this->migrasi_data();
    }

    protected function migrasi_tabel()
    {
        $this->migrasi_2024040451();
    }

    // Migrasi perubahan data
    protected function migrasi_data()
    {
        $this->migrasi_2024040571();
        $this->migrasi_2024041671();
        $this->migrasi_2024032052();
        $this->migrasi_20240401471();
        $this->migrasi_2024042351();
        $this->migrasi_2024041951();

        $this->migrasi_2024040271();
    }

    protected function migrasi_2024032052()
    {
        Modul::where('slug', 'buku-tanah-kas-desa')->update(['url' => 'bumindes_tanah_kas_desa']);
    }

    protected function migrasi_20240401471()
    {
        Modul::where('slug', 'arsip-surat-dinas')->update(['url' => 'arsip_surat_dinas']);
    }

    protected function migrasi_2024041951()
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
    }

    protected function migrasi_2024042351()
    {
        if ($this->cek_indeks('kelompok', 'kode_config')) {
            Schema::table('kelompok', static function ($table) {
                $table->dropUnique('kode_config');
                $table->unique(['config_id', 'kode', 'tipe'], 'config_kode_tipe');
            });
        }
    }

    protected function migrasi_2024040271()
    {
        $penduduk_luar = SettingAplikasi::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('key', '=', 'form_penduduk_luar')->first();
        if ($penduduk_luar) {
            $value             = json_decode($penduduk_luar->value, true);
            $value[3]['input'] = 'nama,no_ktp,tempat_lahir,tanggal_lahir,jenis_kelamin,agama,pendidikan_kk,pekerjaan,warga_negara,alamat,golongan_darah,status_perkawinan,tanggal_perkawinan,shdk,no_paspor,no_kitas,nama_ayah,nama_ibu';
            $penduduk_luar->update(['value' => json_encode($value)]);
        }

        $this->migrasi_2024042751();
    }

    protected function migrasi_2024042751()
    {
        DB::table('menu')->where('enabled', 2)->update(['enabled' => 0]);
    }

    protected function migrasi_2024040571()
    {
        $this->createSetting([
            'judul'      => 'Sebutan Anjungan Mandiri',
            'key'        => 'sebutan_anjungan_mandiri',
            'value'      => 'Anjungan [desa] Mandiri',
            'keterangan' => 'Pengaturan sebutan anjungan mandiri',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'anjungan',
        ]);
    }

    public function migrasi_2024041671()
    {
        $this->createSetting([
            'judul'      => 'Icon Lapak Peta',
            'key'        => 'icon_lapak_peta',
            'value'      => 'fastfood.png',
            'keterangan' => 'Icon penanda Lapak yang ditampilkan pada Peta',
            'jenis'      => 'select-simbol',
            'option'     => json_encode(['model' => 'App\\Models\\Simbol', 'value' => 'simbol', 'label' => 'simbol']),
            'attribute'  => 'class="form-control input-sm select2-icon-img required" data-lokasi="' . base_url(LOKASI_SIMBOL_LOKASI) . '"',
            'kategori'   => 'lapak',
        ]);
    }

    protected function migrasi_2024040451()
    {
        if (! Schema::hasColumn('user', 'batasi_wilayah')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->unsignedTinyInteger('batasi_wilayah')->default(0);
                $table->text('akses_wilayah')->nullable();
            });
        }
    }
}
