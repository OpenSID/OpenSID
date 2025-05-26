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

use App\Models\SettingAplikasi;
use App\Models\Shortcut;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->ubahKategoriSlider();
        $this->hapusShortcutTertentu();
        $this->tambahKolomUrutSettings();
        $this->ubahKolomEmail();

        $this->tambahKolomMargaPenduduk();
    }

    public function ubahKategoriSlider()
    {
        SettingAplikasi::withoutGlobalScopes()
            ->whereIn('key', ['sumber_gambar_slider', 'jumlah_gambar_slider'])
            ->where('kategori', '!=', 'Slider')
            ->update(['kategori' => 'Slider']);
    }

    public function hapusShortcutTertentu()
    {
        Shortcut::whereIn('raw_query', ['RT', 'RW', 'Dokumen Penduduk'])->delete();
    }

    public function tambahKolomUrutSettings()
    {
        if (! Schema::hasColumn('setting_aplikasi', 'urut')) {
            Schema::table('setting_aplikasi', static function (Blueprint $table) {
                $table->integer('urut')->nullable()->after('value');
            });

            $settings = SettingAplikasi::withoutGlobalScopes()->get();

            foreach ($settings as $setting) {
                $setting->urut = $setting->id;
                $setting->save();
            }
        }

        SettingAplikasi::withoutGlobalScopes()->where('key', 'sebutan_pemerintah_desa')->update(['urut' => 1]);
        SettingAplikasi::withoutGlobalScopes()->where('key', 'sebutan_pj_kepala_desa')->update(['urut' => 2]);
        SettingAplikasi::withoutGlobalScopes()->where('key', 'media_sosial_pemerintah_desa')->update(['urut' => 3]);
        SettingAplikasi::withoutGlobalScopes()->where('key', 'ukuran_lebar_bagan')->update(['urut' => 4]);
    }

    public function ubahKolomEmail()
    {
        Schema::table('config', static function (Blueprint $table) {
            $table->string('email_desa', 100)->change();
        });
    }

    public function tambahKolomMargaPenduduk()
    {
        if (! Schema::hasColumn('tweb_penduduk', 'marga')) {
            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->string('marga')->nullable()->after('suku');
            });
        }
    }
}
