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
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025060171
{
    use Migrator;

    public function up()
    {
        $this->ubahKategoriSlider();
        $this->hapusShortcutTertentu();
        $this->ubahKategoriSlider();
        $this->hapusShortcutTertentu();
        $this->tambahKolomUrutSettings();
        $this->ubahKolomEmail();
        $this->isiSlugBantuanDariNama();
        $this->tambahPengaturanDataLengkapSettings();
        $this->addConfigIdColumn();
        $this->tambahKolomMargaPenduduk();
        $this->createTableActivity();
        $this->refaktorLogLogin();
        $this->tambahPengaturanAnjunganSurat();
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

    public function isiSlugBantuanDariNama()
    {
        Bantuan::whereNull('slug')->get()->each(static function ($bantuan) {
            $baseSlug = Str::slug($bantuan->nama);
            $slug     = $baseSlug;
            $counter  = 1;

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
            'kategori'   => 'Data Lengkap',
            'attribute'  => [
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

    public function tambahKolomMargaPenduduk()
    {
        if (! Schema::hasColumn('tweb_penduduk', 'marga')) {
            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->string('marga')->nullable()->after('suku');
            });
        }
    }

    public function createTableActivity()
    {
        if (! Schema::hasTable('log_activity')) {
            Schema::create('log_activity', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->configId();
                $table->string('log_name')->nullable();
                $table->text('description');
                $table->nullableMorphs('subject', 'subject');
                $table->string('event')->nullable();
                $table->nullableMorphs('causer', 'causer');
                $table->json('properties')->nullable();
                $table->uuid('batch_uuid')->nullable();
                $table->timestamps();
                $table->index('log_name');
            });
        }
    }

    public function refaktorLogLogin()
    {
        if (Schema::hasTable('log_login')) {
            DB::table('log_login')
                ->get()
                ->chunk(500)
                ->each(static function ($chunk) {
                    $userCache = [];

                    $chunk = $chunk->map(static function ($item) use (&$userCache) {
                        $userKey = "{$item->config_id}|{$item->username}";

                        if (! isset($userCache[$userKey])) {
                            $userCache[$userKey] = DB::table('user')
                                ->where('config_id', $item->config_id)
                                ->where('nama', $item->username)
                                ->value('id');
                        }

                        $causerId = $userCache[$userKey] ?? null;

                        return [
                            'config_id'   => $item->config_id,
                            'log_name'    => 'Login',
                            'description' => 'Pengguna berhasil masuk',
                            'event'       => 'Login',
                            'causer_type' => 'App\Models\User',
                            'causer_id'   => $causerId,
                            'properties'  => json_encode([
                                'username'   => $item->username,
                                'ip_address' => $item->ip_address,
                                'user_agent' => $item->user_agent,
                                'referer'    => $item->referer,
                                'lainnya'    => json_decode($item->lainnya, true),
                            ]),
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                        ];
                    });

                    DB::table('log_activity')->insert($chunk->toArray());
                });
        }

        if (Schema::hasTable('login_attempts')) {
            DB::table('login_attempts')
                ->get()
                ->chunk(500)
                ->each(static function ($chunk) {
                    $chunk = $chunk->map(static function ($item) use (&$userCache) {
                        return [
                            'config_id'   => $item->config_id,
                            'log_name'    => 'Login',
                            'description' => 'Pengguna tidak berhasil masuk',
                            'event'       => 'Gagal',
                            'properties'  => json_encode([
                                'username'   => $item->username,
                                'ip_address' => $item->ip_address,
                                'time'       => Carbon::createFromTimestamp($item->time)->format('Y-m-d H:i:s'),
                            ]),
                            'created_at' => Carbon::createFromTimestamp($item->time)->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::createFromTimestamp($item->time)->format('Y-m-d H:i:s'),
                        ];
                    });

                    DB::table('log_activity')->insert($chunk->toArray());
                });
        }

        Schema::dropIfExists('log_login');
        Schema::dropIfExists('login_attempts');
    }

    public function tambahPengaturanAnjunganSurat()
    {
        if (! Schema::hasColumn('anjungan', 'permohonan_surat_tanpa_akun')) {
            Schema::table('anjungan', static function (Blueprint $table) {
                $table->boolean('permohonan_surat_tanpa_akun')->default(0)->after('status');
            });
        }
    }
}
