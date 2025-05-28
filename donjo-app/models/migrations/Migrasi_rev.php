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


use App\Models\Bantuan;
use App\Enums\AktifEnum;
use App\Models\Shortcut;
use App\Traits\Migrator;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

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
        $this->isiSlugBantuanDariNama();
        $this->tambahPengaturanDataLengkapSettings();
        $this->addConfigIdColumn();
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
        if (!Schema::hasColumn('setting_aplikasi', 'urut')) {
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

    public function isiSlugBantuanDariNama()
    {
        Bantuan::whereNull('slug')->get()->each(function ($bantuan) {
            $baseSlug = Str::slug($bantuan->nama);
            $slug = $baseSlug;
            $counter = 1;
            while (Bantuan::where('slug', $slug)->where('id', '!=', $bantuan->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $bantuan->slug = $slug;
            $bantuan->save();
        });
    }

    public function tambahPengaturanDataLengkapSettings()
    {
        $this->createSetting([
            'judul'      => 'Tgl Data Lengkap Aktif',
            'key'        => 'tgl_data_lengkap_aktif',
            'value'      => AktifEnum::TIDAK_AKTIF,
            'keterangan' => 'Aktif / Non-aktif data tanggal sudah lengkap',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'  => 'Data Lengkap',
            'attribute' => [
                'class' => 'required',
            ],
        ]);
    }

    public function addConfigIdColumn()
    {
        if (! Schema::hasColumn('tweb_penduduk_map', 'config_id')) {
            Schema::table('tweb_penduduk_map', static function (Blueprint $table) {
                $table->configId();
            });

            DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_map', 'tweb_penduduk.id', '=', 'tweb_penduduk_map.id')
                ->update(['tweb_penduduk_map.config_id' => DB::raw('tweb_penduduk.config_id')]);
        }

        if (! Schema::hasColumn('dtks_ref_lampiran', 'config_id')) {
            Schema::table('dtks_ref_lampiran', static function (Blueprint $table) {
                $table->configId();
            });

            DB::table('dtks_ref_lampiran')
                ->leftJoin('dtks_lampiran', 'dtks_ref_lampiran.id_lampiran', '=', 'dtks_lampiran.id')
                ->update(['dtks_ref_lampiran.config_id' => DB::raw('dtks_lampiran.config_id')]);
        }

        if (! Schema::hasColumn('analisis_respon', 'config_id')) {
            Schema::table('analisis_respon', static function (Blueprint $table) {
                $table->configId();
            });

            DB::table('analisis_respon')
                ->leftJoin('analisis_periode', 'analisis_respon.id_periode', '=', 'analisis_periode.id')
                ->update(['analisis_respon.config_id' => DB::raw('analisis_periode.config_id')]);
        }

        // TODO: Apakah tabel ini masih digunakan?
        if (! Schema::hasColumn('analisis_partisipasi', 'config_id')) {
            Schema::table('analisis_partisipasi', static function (Blueprint $table) {
                $table->configId();
            });

            DB::table('analisis_partisipasi')
                ->leftJoin('analisis_periode', 'analisis_respon.id_periode', '=', 'analisis_periode.id')
                ->update(['analisis_respon.config_id' => DB::raw('analisis_periode.config_id')]);
        }
    }
}
