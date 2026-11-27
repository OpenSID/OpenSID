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

use App\Enums\FormatNoRtmEnum;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SettingAplikasi;
use App\Models\Modul;

return new class () extends Migration {
    use Migrator;

    /**
     * Daftarkan modul "Acak Data" dengan slug `acak-data` secara upgrade-safe
     * (OpenSID/premium#6769):
     * - Instalasi yang sudah pernah menjalankan Migrasi_2026073071 versi lama
     *   (slug `acak`) di-rename ke `acak-data`, status `aktif` yang sudah
     *   diset admin tetap dipertahankan.
     * - Instalasi yang belum punya baris sama sekali (baru, atau upgrade dari
     *   versi sebelum modul ini ada) dibuatkan baris baru dengan default
     *   nonaktif & hidden, sama seperti kontrak di AcakModulGatingTest.
     */
    public function up(): void
    {
        $this->tambahKolomPaksaRestoreLogRestoreDesa();
        $this->buatTabelLogAcak();
        $this->tambahPengaturanNoRtm();
        $this->perbaikiIdYoutubeAnjungan();
        $this->createTablePasswordHistory();
        $this->tambahPengaturanMaksimalImporDataPenduduk();
        $this->tambahModulLaporanPerkembangan();
        $this->tambahModulAcakDatabase();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    /**
     * Kolom penanda opsi "Pertahankan kredensial lokal" pada restore folder desa.
     * Saat aktif, ekstraksi tidak menimpa config/database.php, app_key, dan .env
     * lokal — untuk restore folder desa dari instalasi berbeda.
     */
    private function tambahKolomPaksaRestoreLogRestoreDesa(): void
    {
        if (Schema::hasColumn('log_restore_desa', 'paksa_restore')) {
            return;
        }

        Schema::table('log_restore_desa', static function (Blueprint $table): void {
            $table->boolean('paksa_restore')->default(false)->after('status');
        });
    }

    /**
     * Tabel pelacak job latar belakang Acak Data (progres & hasil), agar proses
     * yang lama tidak lagi timeout di gateway (nginx/FPM) seperti aliran SSE.
     */
    private function buatTabelLogAcak(): void
    {
        if (Schema::hasTable('log_acak')) {
            return;
        }

        Schema::create('log_acak', static function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('config_id')->index('log_acak_config_fk');
            $table->string('seed', 191)->nullable();
            $table->string('foto', 20)->nullable();
            $table->string('foto_konten', 20)->nullable();
            $table->string('surat', 20)->nullable();
            $table->integer('status')->default(0);
            $table->string('step', 191)->nullable();
            $table->longText('hasil')->nullable();
            $table->text('error')->nullable();
            $table->integer('pid_process')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    public function tambahPengaturanNoRtm(): void
    {
        $this->createSetting([
            'judul'      => 'Format Nomor Rumah Tangga',
            'key'        => 'format_no_rtm',
            'value'      => FormatNoRtmEnum::ANGKA,
            'keterangan' => 'Format yang digunakan untuk penomoran nomor rumah tangga',
            'jenis'      => 'select-array',
            'option'     => json_encode(FormatNoRtmEnum::toOptionArray()),
            'kategori'   => 'Rumah Tangga',
            'attribute'  => json_encode([]),
        ]);
    }

    /**
     * Issue #11438 — video anjungan gagal diputar.
     *
     * Nilai bawaan pengaturan "anjungan_youtube" sebelumnya berupa URL embed
     * penuh (https://www.youtube.com/embed/...), bukan ID video 11 karakter.
     * Frontend menyusun src iframe sebagai
     * "https://www.youtube.com/embed/{setting('anjungan_youtube')}", sehingga
     * instalasi yang belum pernah menyimpan ulang pengaturan ini lewat form
     * mendapat src ganda ("embed/https://...") dan video gagal diputar.
     */
    private function perbaikiIdYoutubeAnjungan(): void
    {
        $setting = SettingAplikasi::where('key', 'anjungan_youtube')->first();

        if (! $setting || ! $setting->value) {
            return;
        }

        $id = ambil_id_youtube($setting->value);

        if ($id !== $setting->value) {
            $setting->update(['value' => $id]);
            (new SettingAplikasi())->flushQueryCache();
        }
    }

    public function createTablePasswordHistory(): void
    {
        if (Schema::hasTable('password_histories')) {
            return;
        }

        Schema::create('password_histories', function (Blueprint $table) {
            $table->id();
            $table->configId();
            $table->morphs('authenticatable');
            $table->string('password');
            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['config_id', 'authenticatable_type', 'authenticatable_id', 'created_at'],
                'password_histories_config_authenticatable_index'
            );
        });
    }

    public function tambahPengaturanMaksimalImporDataPenduduk()
    {
        $this->createSetting([
            'judul'      => 'Maksimal impor data penduduk',
            'key'        => 'impor_data_penduduk',
            'value'      => '2',
            'urut'       => 1,
            'keterangan' => 'Maksimal impor data penduduk (MB)',
            'jenis'      => 'input',
            'option'     => null,
            'kategori'   => 'Data Lengkap',
            'attribute'  => null,
        ]);
    }

    public function tambahModulLaporanPerkembangan(): void
    {
        $this->createModul([
            'modul'       => 'Laporan Perkembangan',
            'slug'        => 'laporan-perkembangan',
            'url'         => 'laporan-perkembangan',
            'aktif'       => 1,
            'ikon'        => 'fa-line-chart',
            'ikon_kecil'  => 'fa-line-chart',
            'urut'        => 5,
            'level'       => 2,
            'hidden'      => 0,
            'parent_slug' => 'statistik',
        ]);
    }

    public function tambahModulAcakDatabase()
    {
        $this->createModul([
            'modul'       => 'Acak Data',
            'slug'        => 'acak-data',
            'url'         => 'acak-data',
            'aktif'       => 0,
            'ikon'        => 'fa-random',
            'urut'        => 99,
            'level'       => 1,
            'hidden'      => Modul::SHOW,
            'parent_slug' => 'pengaturan',
        ]);
    }
};
