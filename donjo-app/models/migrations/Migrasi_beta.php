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
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_beta
{
    use Migrator;

    public function up()
    {
        $this->replaceViewPendudukHidup();
        $this->addNewFieldToPembangunan();
        $this->updateSumberDanaPembangunan();
        $this->pengaturanPenerimaBantuan();

        // konfigurasi kebutuhan OTP
        $this->tambahkanKolomOtp();
        $this->buatTableOtpToken();
        $this->tambahPengaturanOtp();

        $this->createOneTimePasswordTable();
        $this->tambahPengaturanOtp2FA();
    }

    protected function addNewFieldToPembangunan()
    {
        if (! Schema::hasColumn('pembangunan', 'realisasi_anggaran')) {
            Schema::table('pembangunan', static function (Blueprint $table) {
                $table->bigInteger('realisasi_anggaran')->nullable()->default(0);
            });
        }

        if (! Schema::hasColumn('pembangunan', 'silpa')) {
            Schema::table('pembangunan', static function (Blueprint $table) {
                $table->bigInteger('silpa')->nullable()->default(0);
            });
        }
    }

    protected function updateSumberDanaPembangunan()
    {
        if (Schema::hasColumn('pembangunan', 'sumber_dana')) {
            Schema::table('pembangunan', static function (Blueprint $table) {
                $table->text('sumber_dana')->nullable()->change();
            });

            DB::table('pembangunan')->whereNotNull('sumber_dana')->get()->each(static function ($row) {
                $val = $row->sumber_dana;

                $decoded = json_decode($val, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    DB::table('pembangunan')
                        ->where('id', $row->id)
                        ->update([
                            'sumber_dana' => json_encode([$val]),
                        ]);
                }
            });
        }
    }

    public function replaceViewPendudukHidup()
    {
        // Penduduk Hidup
        DB::statement('CREATE OR REPLACE VIEW `penduduk_hidup` AS select `tweb_penduduk`.`id` AS `id`,`tweb_penduduk`.`config_id` AS `config_id`,`tweb_penduduk`.`nama` AS `nama`,`tweb_penduduk`.`nik` AS `nik`,`tweb_penduduk`.`id_kk` AS `id_kk`,`tweb_penduduk`.`kk_level` AS `kk_level`,`tweb_penduduk`.`id_rtm` AS `id_rtm`,`tweb_penduduk`.`rtm_level` AS `rtm_level`,`tweb_penduduk`.`sex` AS `sex`,`tweb_penduduk`.`tempatlahir` AS `tempatlahir`,`tweb_penduduk`.`tanggallahir` AS `tanggallahir`,`tweb_penduduk`.`agama_id` AS `agama_id`,`tweb_penduduk`.`pendidikan_kk_id` AS `pendidikan_kk_id`,`tweb_penduduk`.`pendidikan_sedang_id` AS `pendidikan_sedang_id`,`tweb_penduduk`.`pekerjaan_id` AS `pekerjaan_id`,`tweb_penduduk`.`status_kawin` AS `status_kawin`,`tweb_penduduk`.`warganegara_id` AS `warganegara_id`,`tweb_penduduk`.`dokumen_pasport` AS `dokumen_pasport`,`tweb_penduduk`.`dokumen_kitas` AS `dokumen_kitas`,`tweb_penduduk`.`ayah_nik` AS `ayah_nik`,`tweb_penduduk`.`ibu_nik` AS `ibu_nik`,`tweb_penduduk`.`nama_ayah` AS `nama_ayah`,`tweb_penduduk`.`nama_ibu` AS `nama_ibu`,`tweb_penduduk`.`foto` AS `foto`,`tweb_penduduk`.`golongan_darah_id` AS `golongan_darah_id`,`tweb_penduduk`.`id_cluster` AS `id_cluster`,`tweb_penduduk`.`status` AS `status`,`tweb_penduduk`.`alamat_sebelumnya` AS `alamat_sebelumnya`,`tweb_penduduk`.`alamat_sekarang` AS `alamat_sekarang`,`tweb_penduduk`.`status_dasar` AS `status_dasar`,`tweb_penduduk`.`hamil` AS `hamil`,`tweb_penduduk`.`cacat_id` AS `cacat_id`,`tweb_penduduk`.`sakit_menahun_id` AS `sakit_menahun_id`,`tweb_penduduk`.`akta_lahir` AS `akta_lahir`,`tweb_penduduk`.`akta_perkawinan` AS `akta_perkawinan`,`tweb_penduduk`.`tanggalperkawinan` AS `tanggalperkawinan`,`tweb_penduduk`.`akta_perceraian` AS `akta_perceraian`,`tweb_penduduk`.`tanggalperceraian` AS `tanggalperceraian`,`tweb_penduduk`.`cara_kb_id` AS `cara_kb_id`,`tweb_penduduk`.`telepon` AS `telepon`,`tweb_penduduk`.`tanggal_akhir_paspor` AS `tanggal_akhir_paspor`,`tweb_penduduk`.`no_kk_sebelumnya` AS `no_kk_sebelumnya`,`tweb_penduduk`.`ktp_el` AS `ktp_el`,`tweb_penduduk`.`status_rekam` AS `status_rekam`,`tweb_penduduk`.`waktu_lahir` AS `waktu_lahir`,`tweb_penduduk`.`tempat_dilahirkan` AS `tempat_dilahirkan`,`tweb_penduduk`.`jenis_kelahiran` AS `jenis_kelahiran`,`tweb_penduduk`.`kelahiran_anak_ke` AS `kelahiran_anak_ke`,`tweb_penduduk`.`penolong_kelahiran` AS `penolong_kelahiran`,`tweb_penduduk`.`berat_lahir` AS `berat_lahir`,`tweb_penduduk`.`panjang_lahir` AS `panjang_lahir`,`tweb_penduduk`.`tag_id_card` AS `tag_id_card`,`tweb_penduduk`.`created_at` AS `created_at`,`tweb_penduduk`.`created_by` AS `created_by`,`tweb_penduduk`.`updated_at` AS `updated_at`,`tweb_penduduk`.`updated_by` AS `updated_by`,`tweb_penduduk`.`id_asuransi` AS `id_asuransi`,`tweb_penduduk`.`no_asuransi` AS `no_asuransi`,`tweb_penduduk`.`email` AS `email`,`tweb_penduduk`.`email_token` AS `email_token`,`tweb_penduduk`.`email_tgl_kadaluarsa` AS `email_tgl_kadaluarsa`,`tweb_penduduk`.`email_tgl_verifikasi` AS `email_tgl_verifikasi`,`tweb_penduduk`.`telegram` AS `telegram`,`tweb_penduduk`.`telegram_token` AS `telegram_token`,`tweb_penduduk`.`telegram_tgl_kadaluarsa` AS `telegram_tgl_kadaluarsa`,`tweb_penduduk`.`telegram_tgl_verifikasi` AS `telegram_tgl_verifikasi`,`tweb_penduduk`.`bahasa_id` AS `bahasa_id`,`tweb_penduduk`.`ket` AS `ket`,`tweb_penduduk`.`negara_asal` AS `negara_asal`,`tweb_penduduk`.`tempat_cetak_ktp` AS `tempat_cetak_ktp`,`tweb_penduduk`.`tanggal_cetak_ktp` AS `tanggal_cetak_ktp`,`tweb_penduduk`.`pekerja_migran` AS `pekerja_migran`,`tweb_penduduk`.`suku` AS `suku`,`tweb_penduduk`.`marga` AS `marga`,`tweb_penduduk`.`adat` AS `adat`,`tweb_penduduk`.`bpjs_ketenagakerjaan` AS `bpjs_ketenagakerjaan`,`tweb_penduduk`.`hubung_warga` AS `hubung_warga` from `tweb_penduduk` where `tweb_penduduk`.`status_dasar` = 1');
    }

    public function pengaturanPenerimaBantuan()
    {
        $this->createSetting([
            'judul'      => 'Sembunyikan Nama Penerima Bantuan',
            'key'        => 'sembunyikan_nama_penerima_bantuan',
            'value'      => StatusEnum::TIDAK,
            'urut'       => 10,
            'keterangan' => 'Sembunyikan nama penerima bantuan. Jika diaktifkan, nama penerima bantuan akan disembunyikan atau disensor pada daftar penerima bantuan penduduk dan keluarga.',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'   => 'Website',
            'attribute'  => json_encode([
                'class' => 'required',
            ]),
        ]);

        $this->createSetting([
            'judul'      => 'Sembunyikan Alamat Penerima Bantuan',
            'key'        => 'sembunyikan_alamat_penerima_bantuan',
            'value'      => StatusEnum::TIDAK,
            'urut'       => 11,
            'keterangan' => 'Sembunyikan alamat penerima bantuan. Jika diaktifkan, alamat penerima bantuan akan disembunyikan atau disensor pada daftar penerima bantuan penduduk dan keluarga.',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'   => 'Website',
            'attribute'  => json_encode([
                'class' => 'required',
            ]),
        ]);
    }

    public function tambahkanKolomOtp()
    {
        try {
            if (! Schema::hasColumn('user', 'otp_enabled')) {
                Schema::table('user', static function (Blueprint $table) {
                    $table->boolean('otp_enabled')->default(false)->after('active');
                });
            }
            if (! Schema::hasColumn('user', 'otp_channel')) {
                Schema::table('user', static function (Blueprint $table) {
                    $table->enum('otp_channel', ['email', 'telegram', 'both'])->nullable()->after('otp_enabled');
                });
            }
            if (! Schema::hasColumn('user', 'otp_identifier')) {
                Schema::table('user', static function (Blueprint $table) {
                    $table->string('otp_identifier', 255)->nullable()->after('otp_channel');
                });
            }
            if (! Schema::hasColumn('user', 'telegram_chat_id')) {
                Schema::table('user', static function (Blueprint $table) {
                    $table->string('telegram_chat_id', 100)->nullable()->after('otp_identifier');
                });
            }
        } catch (Exception $e) {
            log_message('error', 'Gagal menambahkan kolom OTP: ' . $e->getMessage());
            set_session('warning', 'Gagal menambahkan kolom OTP');
        }
    }

    public function buatTableOtpToken()
    {
        try {
            if (! Schema::hasTable('otp_token')) {
                Schema::create('otp_token', static function (Blueprint $table) {
                    $table->increments('id');
                    $table->unsignedMediumInteger('user_id');
                    $table->string('token_hash', 255);
                    $table->enum('channel', ['email', 'telegram']);
                    $table->string('identifier', 255);
                    $table->enum('purpose', ['activation', 'login'])->default('login');
                    $table->timestamp('expires_at');
                    $table->integer('attempts')->default(0);

                    $table->index(['user_id', 'expires_at']);
                    $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
                });
            }
        } catch (Exception $e) {
            log_message('error', 'Gagal membuat table otp token: ' . $e->getMessage());
            set_session('warning', 'Gagal membuat table otp token');
        }
    }

    public function tambahPengaturanOtp()
    {
        $this->createSetting([
            'judul'      => 'Login OTP',
            'key'        => 'login_otp',
            'value'      => AktifEnum::AKTIF,
            'keterangan' => 'Aktifkan fitur login dengan OTP (One-Time Password) untuk keamanan tambahan',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'   => 'auth',
            'attribute'  => json_encode([]),
        ]);

        $this->createSetting([
            'judul'      => 'Masa Berlaku OTP (Menit)',
            'key'        => 'otp_expiry_minutes',
            'value'      => 5,
            'keterangan' => 'Durasi masa berlaku OTP dalam menit.',
            'jenis'      => 'input-number',
            'option'     => null,
            'kategori'   => 'auth',
            'attribute'  => json_encode([
                'class' => 'required',
                'min'   => 1,
                'max'   => 60,
                'step'  => 1,
            ]),
        ]);

        $this->createSetting([
            'judul'      => 'Waktu Tunggu Kirim Ulang OTP (Detik)',
            'key'        => 'otp_resend_cooldown',
            'value'      => 30,
            'keterangan' => 'Durasi waktu tunggu (cooldown) dalam detik sebelum pengguna dapat meminta kirim ulang kode OTP.',
            'jenis'      => 'input-number',
            'option'     => null,
            'kategori'   => 'auth',
            'attribute'  => json_encode([
                'class' => 'required',
                'min'   => 15,
                'max'   => 120,
                'step'  => 1,
            ]),
        ]);
    }

    public function createOneTimePasswordTable()
    {
        if (! Schema::hasColumn('user', 'two_factor_enabled')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->boolean('two_factor_enabled')->default(false);
            });
        }

        if (! Schema::hasTable('one_time_passwords')) {
            Schema::create('one_time_passwords', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->configId();
                $table->string('password');
                $table->text('origin_properties')->nullable();
                $table->dateTime('expires_at');
                $table->morphs('authenticatable');
                $table->timestamps();
            });
        }
    }

    public function tambahPengaturanOtp2FA()
    {
        $this->createSetting([
            'judul'      => 'Maksimal Percobaan OTP',
            'key'        => 'otp_max_trials',
            'value'      => 3,
            'keterangan' => 'Jumlah maksimal percobaan memasukkan kode OTP sebelum diblokir sementara.',
            'jenis'      => 'input-number',
            'option'     => null,
            'kategori'   => 'auth',
            'attribute'  => json_encode([
                'class' => 'required',
                'min'   => 1,
                'max'   => 5,
                'step'  => 1,
            ]),
        ]);
    }
}
