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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Actions\Setting\ImportSetting;
use App\Models\FormatSurat;
use App\Models\SuratDinas;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->tambahKolomQrCodeTteSuratFormat();
        $this->perbesarKolomNamaSuratLog();
        $this->tambahTabelOpenSidSsoLogs();
        $this->tambahPengaturanSsoOpenKab();
        $this->standardisasiJenisPengaturanAplikasi();

        $this->tambahKolomStatusValidasi('tweb_surat_format', 'sumber_penduduk_berulang');
        $this->tambahKolomStatusValidasi('surat_dinas', 'format_nomor_global');
        $this->grandfatherTemplateLama();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    private function tambahKolomQrCodeTteSuratFormat(): void
    {
        if (Schema::hasColumn('tweb_surat_format', 'qr_code_tte')) {
            return;
        }

        Schema::table('tweb_surat_format', static function (Blueprint $table): void {
            $table->boolean('qr_code_tte')->default(false)->after('qr_code');
        });
    }

    // Perbesar kolom nama_surat karena diisi nama file arsip (slug + nik + tanggal + nomor surat)
    // yang bisa melebihi 100 karakter dan menyebabkan gagal insert (SQLSTATE 22001)
    private function perbesarKolomNamaSuratLog(): void
    {
        foreach (['log_surat', 'log_surat_dinas'] as $tabel) {
            $panjang = (int) DB::table('information_schema.columns')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', $tabel)
                ->where('column_name', 'nama_surat')
                ->value('character_maximum_length');

            if ($panjang >= 255) {
                continue;
            }

            Schema::table($tabel, static function (Blueprint $table): void {
                $table->string('nama_surat', 255)->nullable()->change();
            });
        }
    }

    /**
     * Tabel audit append-only untuk percobaan SSO OpenKab -> OpenSID (POST /admin/sso-login).
     */
    private function tambahTabelOpenSidSsoLogs(): void
    {
        if (Schema::hasTable('opensid_sso_logs')) {
            return;
        }

        Schema::create('opensid_sso_logs', static function (Blueprint $table): void {
            $table->id();
            $table->string('status', 20);
            $table->string('reason', 50)->nullable();
            $table->string('jti', 64)->nullable()->index();
            $table->string('openkab_username')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Pengaturan konfigurasi SSO OpenKab -> OpenSID pada tabel setting_aplikasi.
     */
    private function tambahPengaturanSsoOpenKab(): void
    {
        $this->createSettings([
            [
                'judul'      => 'Base URL OpenKab SSO',
                'key'        => 'sso_openkab_base_url',
                'value'      => null,
                'urut'       => null,
                'keterangan' => 'Base URL server OpenKab untuk verifikasi token SSO (contoh: https://opendesa.kabkota.go.id)',
                'jenis'      => 'input-url',
                'option'     => null,
                'attribute'  => null,
                'kategori'   => 'auth',
            ],
            [
                'judul'      => 'Secret Callback SSO',
                'key'        => 'sso_callback_secret',
                'value'      => null,
                'urut'       => null,
                'keterangan' => 'Secret bersama untuk otentikasi callback verify-token SSO (minimal 32 karakter)',
                'jenis'      => 'input-password',
                'option'     => null,
                'attribute'  => null,
                'kategori'   => 'auth',
            ],
            [
                'judul'      => 'Allowed Origins SSO',
                'key'        => 'sso_allowed_origins',
                'value'      => null,
                'urut'       => null,
                'keterangan' => 'Daftar origin OpenKab yang diizinkan mengakses login SSO (pisahkan dengan koma)',
                'jenis'      => 'input-text',
                'option'     => null,
                'attribute'  => null,
                'kategori'   => 'auth',
            ],
            [
                'judul'      => 'Timeout HTTP SSO',
                'key'        => 'sso_http_timeout',
                'value'      => '5',
                'urut'       => null,
                'keterangan' => 'Timeout (detik) saat memanggil endpoint verify-token OpenKab',
                'jenis'      => 'input-number',
                'option'     => null,
                'attribute'  => json_encode(['min' => 1, 'max' => 60]),
                'kategori'   => 'auth',
            ],
        ]);
    }

    private function standardisasiJenisPengaturanAplikasi(): void
    {
        (new ImportSetting())->handle();

        // Ganti option default select-boolean menjadi select-array
        DB::table('setting_aplikasi')
            ->whereIn('jenis', ['boolean', 'select-boolean'])
            ->whereNull('option')
            ->update(['option' => json_encode(\App\Enums\StatusEnum::all())]);

        $mapping = [
            'text'                  => 'input-text',
            'number'                => 'input-number',
            'password'              => 'input-password',
            'upload'                => 'unggah',
            'color'                 => 'input-color',
            'url'                   => 'input-url',
            'boolean'               => 'select-array',
            'select-boolean'        => 'select-array',
            'option'                => 'select-array',
            'select'                => 'select-array',
            'select-multiple-array' => 'select-array',
            'select-multiple-option'=> 'select-array',
            'multiple-option-array' => 'select-array',
            'email'                 => 'input-email',
            'tel'                   => 'input-tel',
            'referensi'             => 'select-model',
        ];

        foreach ($mapping as $oldJenis => $newJenis) {
            DB::table('setting_aplikasi')
                ->where('jenis', $oldJenis)
                ->update(['jenis' => $newJenis]);
        }

        // Penyesuaian khusus jika masih ada jenis 'input'
        DB::table('setting_aplikasi')
            ->where('jenis', 'input')
            ->where(function ($query) {
                $query->where('attribute', 'like', '%type="number"%')
                    ->orWhere('attribute', 'like', '%type=\'number\'%')
                    ->orWhere('key', 'gambar_sinergi_program_perbaris');
            })
            ->update(['jenis' => 'input-number']);

        DB::table('setting_aplikasi')
            ->where('jenis', 'input')
            ->update(['jenis' => 'input-text']);

        // Default jika null atau kosong = input-text
        DB::table('setting_aplikasi')
            ->whereNull('jenis')
            ->orWhere('jenis', '')
            ->update(['jenis' => 'input-text']);

        // Pengaturan dinamis berbasis model (select-model)
        DB::table('setting_aplikasi')
            ->where('key', 'apbdes_tahun')
            ->update([
                'jenis'  => 'select-model',
                'option' => json_encode([
                    'model'    => \App\Models\Keuangan::class,
                    'value'    => 'tahun',
                    'label'    => 'tahun',
                    'orderBy'  => 'tahun',
                    'orderDir' => 'desc',
                    'distinct' => true,
                ]),
                'attribute' => null,
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tampilan_anjungan_slider')
            ->update([
                'jenis'  => 'select-model',
                'option' => json_encode([
                    'model' => \App\Models\Galery::class,
                    'value' => 'id',
                    'label' => 'nama',
                ]),
                'attribute' => json_encode([
                    'where' => ['parrent' => 0, 'enabled' => 1],
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->whereIn('key', [
                'surat_kelahiran_terkait_penduduk',
                'surat_kematian_terkait_penduduk',
                'surat_pindah_keluar_terkait_penduduk',
                'surat_hilang_terkait_penduduk',
                'surat_pindah_masuk_terkait_penduduk',
                'surat_pergi_terkait_penduduk',
            ])
            ->update([
                'jenis'     => 'select-model',
                'attribute' => json_encode([
                    'where'    => ['kunci' => false],
                    'multiple' => true,
                ]),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'tampilan_anjungan')
            ->update([
                'jenis'  => 'select-array',
                'option' => json_encode(\App\Enums\TampilanAnjunganEnum::all(), JSON_FORCE_OBJECT),
            ]);

        DB::table('setting_aplikasi')
            ->where('key', 'offline_mode')
            ->update([
                'jenis'  => 'select-array',
                'option' => json_encode(\App\Enums\OfflineModeEnum::all(), JSON_FORCE_OBJECT),
            ]);
    }

    private function tambahKolomStatusValidasi(string $table, string $afterKolom): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        Schema::table($table, static function (Blueprint $table) use ($afterKolom): void {
            if (! Schema::hasColumn($table->getTable(), 'status_validasi')) {
                $table->tinyInteger('status_validasi')->default(0)->after($afterKolom);
            }
            if (! Schema::hasColumn($table->getTable(), 'pesan_validasi')) {
                $table->longText('pesan_validasi')->nullable()->after('status_validasi');
            }
            if (! Schema::hasColumn($table->getTable(), 'divalidasi_pada')) {
                $table->timestamp('divalidasi_pada')->nullable()->after('pesan_validasi');
            }
        });
    }

    private function grandfatherTemplateLama(): void
    {
        if (Schema::hasTable('tweb_surat_format')) {
            DB::table('tweb_surat_format')
                ->where('jenis', FormatSurat::TINYMCE_DESA)
                ->where('status_validasi', 0)
                ->update(['status_validasi' => 1]);
        }

        if (Schema::hasTable('surat_dinas')) {
            DB::table('surat_dinas')
                ->where('jenis', SuratDinas::TINYMCE_DESA)
                ->where('status_validasi', 0)
                ->update(['status_validasi' => 1]);
        }
    }
};
