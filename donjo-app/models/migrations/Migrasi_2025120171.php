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

use App\Enums\AktifEnum;
use App\Enums\StatusEnum;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Database\Seeders\DataAwal\SettingAplikasi as SettingAplikasiSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025120171
{
    use Migrator;

    public function up()
    {
        $this->ubahDataShortcut();
        $this->buatKolomConfigIdOtpToken();
        $this->tambahSettingAplikasi();
        $this->tambahKolomStatusBukuTamu();
        $this->tambahPengaturanMasaAktifTidakAktif();
        $this->allowNullSyaratPermohonanSurat();
        $this->createSecurityTables();
        $this->pengaturanHariLiburKehadiran();
        $this->tambahKolomQRCodeTte();
        $this->pindahkanPengaturanLayarAnjungan();
        $this->migrateAnjunganTipeToArray();

        shortcut_cache();
    }

    public function ubahDataShortcut()
    {
        DB::table('shortcut')->where('raw_query', 'Verifikasi Layanan Mandiri')->update(['raw_query' => 'Verifikasi Layanan Mandiri (Semua)']);
    }

    /**
     * Menambahkan pengaturan masa berlaku akun tidak aktif.
     *
     * @return void
     */
    public function tambahPengaturanMasaAktifTidakAktif()
    {
        $this->createSetting([
            'judul'      => 'Masa Akun untuk Login',
            'key'        => 'masa_akun_pengguna',
            'value'      => AktifEnum::AKTIF,
            'keterangan' => 'Aktifkan masa berlaku akun pengguna untuk login.',
            'jenis'      => 'boolean',
            'option'     => null,
            'kategori'   => 'auth',
            'urut'       => 1,
            'attribute'  => null,
        ]);

        $this->createSetting([
            'judul'      => 'Masa akun tidak aktif (hari)',
            'key'        => 'masa_akun_tidak_aktif',
            'value'      => 30,
            'keterangan' => 'Batas waktu dalam hari sebuah akun pengguna dianggap tidak aktif. Setelah melewati batas ini, akun dapat dinonaktifkan secara otomatis oleh sistem.',
            'jenis'      => 'input-number',
            'option'     => null,
            'kategori'   => 'auth',
            'urut'       => 2,
            'attribute'  => json_encode([
                'class' => 'required',
                'min'   => 1,
                'step'  => 1,
            ]),
        ]);

        $this->createSetting([
            'judul'      => 'Trigger Nonaktifkan Akun Otomatis',
            'key'        => 'jenis_trigger_nonaktifkan_akun',
            'value'      => 'manual',
            'keterangan' => 'Trigger untuk menjalankan proses nonaktifkan akun otomatis berdasarkan masa tidak aktif.',
            'jenis'      => 'option',
            'option'     => json_encode([
                'manual' => 'Manual',
                'cron'   => 'Cron Job',
            ]),
            'kategori'  => 'auth',
            'urut'      => 3,
            'attribute' => null,
        ]);
    }

    public function buatKolomConfigIdOtpToken()
    {
        if (! Schema::hasColumn('otp_token', 'config_id')) {
            Schema::table('otp_token', static function ($table) {
                $table->configId();
            });
        }
    }

    public function tambahSettingAplikasi()
    {
        $seeder     = new SettingAplikasiSeeder();
        $dataSeeder = collect($seeder->getData())
            ->whereNotIn('key', $seeder->unusedKeys())
            ->pluck('key')
            ->toArray();

        $dataDatabase                = SettingAplikasi::pluck('key')->toArray();
        $settingTidakAda             = array_diff($dataSeeder, $dataDatabase);
        $settingAplikasiTidakLengkap = collect($seeder->getData())->whereIn('key', $settingTidakAda)->values()->toArray();

        if (count($settingAplikasiTidakLengkap) > 0) {
            foreach ($settingAplikasiTidakLengkap as $setting) {
                $this->createSetting([
                    'judul'      => $setting['judul'],
                    'key'        => $setting['key'],
                    'value'      => $setting['value'],
                    'keterangan' => $setting['keterangan'],
                    'jenis'      => $setting['jenis'],
                    'option'     => $setting['option'],
                    'attribute'  => $setting['attribute'],
                    'kategori'   => $setting['kategori'],
                ]);

                logger()->info("Setting aplikasi '{$setting['key']}' telah ditambahkan.");
            }
        }

        (new SettingAplikasi())->flushQueryCache();
    }

    public function tambahKolomStatusBukuTamu()
    {
        if (! Schema::hasColumn('buku_tamu', 'status')) {
            Schema::table('buku_tamu', static function ($table) {
                $table->tinyInteger('status')->after('keperluan')->default(0)->comment('0: Baru, 1: Selesai');
            });
        }
    }

    public function allowNullSyaratPermohonanSurat()
    {
        Schema::table('permohonan_surat', static function ($table) {
            $table->text('syarat')->nullable()->change();
        });

        // bersihkan data syarat yang tidak valid menjadi null
        DB::table('permohonan_surat')
            ->where(static function ($query) {
                $query
                    ->where('syarat', 'null')
                    ->orWhere('syarat', '"null"')
                    ->orWhere('syarat', '{}')
                    ->orWhere('syarat', '"{}"')
                    ->orWhere('syarat', '[]')
                    ->orWhere('syarat', '"[]"');
            })
            ->where('config_id', identitas('id'))
            ->update(['syarat' => null]);
    }

    /**
     * Buat tabel untuk penyimpanan data security scanner
     */
    public function createSecurityTables()
    {
        if (! Schema::hasTable('security_reports')) {
            Schema::create('security_reports', static function ($table) {
                $table->id();
                $table->configId();
                $table->string('filename');
                $table->enum('type', ['integrity', 'scan']);
                $table->longText('data');
                $table->timestamps();

                $table->index(['config_id', 'type', 'created_at']);
            });
        }

        if (! Schema::hasTable('security_baselines')) {
            Schema::create('security_baselines', static function ($table) {
                $table->id();
                $table->configId();
                $table->timestamp('generated_at');
                $table->string('version', 10)->default('1.0');
                $table->string('target_directory');
                $table->json('excluded_dirs')->nullable();
                $table->json('statistics');
                $table->longText('files');
                $table->timestamps();

                $table->index(['config_id', 'generated_at']);
            });
        }
    }

    public function pengaturanHariLiburKehadiran()
    {
        $this->createSetting([
            'judul'      => 'Ikuti Hari Libur Terdaftar',
            'key'        => 'ikuti_hari_libur_terdaftar',
            'value'      => StatusEnum::TIDAK,
            'urut'       => 10,
            'keterangan' => 'Jika diaktifkan, jam kerja akan otomatis berubah menjadi "Libur" ketika bertepatan dengan hari libur terdaftar.',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'   => 'Kehadiran',
            'attribute'  => json_encode([
                'class' => 'required',
            ]),
        ]);
    }

    public function tambahKolomQRCodeTte()
    {
        try {
            if (! Schema::hasColumn('tweb_surat_format', 'qr_code_tte')) {
                Schema::table('tweb_surat_format', static function (Blueprint $table) {
                    $table->boolean('qr_code_tte')->default(false)->after('qr_code');
                });
                Log::info('Berhasil menambahkan kolom qr_code_tte pada tabel tweb_surat_format.');
            }
        } catch (Exception $e) {
            Log::error('Gagal menambahkan kolom qr_code_tte: ' . $e->getMessage());
        }
    }

    public function pindahkanPengaturanLayarAnjungan()
    {
        if (! Schema::hasColumn('anjungan', 'orientasi_layar')) {
            Schema::table('anjungan', static function (Blueprint $table) {
                $table->boolean('orientasi_layar')->default(1)->after('permohonan_surat_tanpa_akun');
            });

            $orientasiLayar = setting('anjungan_layar');

            DB::table('anjungan')->where('config_id', identitas('id'))->where('tipe', 1)->update([
                'orientasi_layar' => $orientasiLayar == 1,
            ]);

            DB::table('setting_aplikasi')->where('key', 'anjungan_layar')->delete();
        }
    }

    private function migrateAnjunganTipeToArray()
    {
        Schema::table('anjungan', static function (Blueprint $table) {
            $table->text('tipe')->nullable()->change();
        });

        DB::table('anjungan')
            ->whereRaw("CAST(tipe AS CHAR) REGEXP '^[0-9]+$'")
            ->where('config_id', identitas('id'))
            ->update([
                'tipe' => DB::raw("CONCAT('[', tipe, ']')"),
            ]);
    }
}
