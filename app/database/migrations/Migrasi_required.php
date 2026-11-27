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

use App\Actions\FileManager\PublishFilemanagerAsset;
use App\Actions\Theme\PublishThemeAsset;
use App\Actions\Setting\ImportSetting;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->tambah_ubah_surat_bawaan();
        $this->tambah_ulang_pengaturan();
        $this->versionBuild();
        $this->pasang_symlink_publik();
        $this->refresh_tema_identitas();
        $this->publish_asset_tema_aktif();
        $this->publish_asset_filemanager();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    public function refresh_tema_identitas()
    {
        try {
            theme_scan();
        } catch (Exception $e) {
            logger()->error('Gagal update theme cache: ' . $e->getMessage());
        }

        try {
            cache()->forget('identitas_desa');
        } catch (Exception $e) {
            logger()->error('Gagal hapus cache identitas desa: ' . $e->getMessage());
        }
    }

    public function publish_asset_tema_aktif()
    {
        (new PublishThemeAsset())->handle(theme_active());
    }

    /**
     * Publikasikan aset filemanager (bekas RFM) ke public/vendor/filemanager
     * bila belum lengkap. Instalasi yang diperbarui tidak melewati alur
     * installer/composer publish, sehingga tanpa ini pemilih berkas di
     * editor artikel/surat tampil tanpa CSS/JS.
     */
    public function publish_asset_filemanager(): void
    {
        (new PublishFilemanagerAsset())->handle();
    }

    /**
     * Pasang symlink folder publik desa.
     *
     * Instalasi baru mendapatkannya dari Install::migrations(). Instalasi yang
     * sudah berjalan lalu diperbarui tidak melewati alur installer, sehingga
     * tanpa ini URL desa/upload/... akan mati setelah pembaruan. Kasus lain yang
     * tertutup: folder desa/ dipulihkan dari cadangan setelah link terhapus, dan
     * hosting yang membersihkan public/desa saat deploy.
     *
     * storage:link tidak membuat folder induk; bila public/desa belum ada,
     * symlink() gagal DIAM-DIAM sementara command tetap melaporkan berhasil.
     *
     * Kegagalan symlink tidak boleh menggagalkan migrasi — sebagian hosting
     * melarangnya, dan aplikasi tetap dapat dipakai meski gambar tidak tampil.
     */
    public function pasang_symlink_publik(): void
    {
        // storage:link mencetak "link already exists" untuk setiap link yang
        // sudah ada. Migrasi ini berjalan pada setiap pembaruan, sehingga tanpa
        // penjagaan ini operator desa akan melihat deretan baris ERROR yang
        // sebenarnya tidak menandakan masalah apa pun.
        $adaYangKurang = collect(config('filesystems.links'))
            ->contains(static fn ($tujuan, $link) => ! is_link($link) && is_dir($tujuan));

        if (! $adaYangKurang) {
            return;
        }

        try {
            File::ensureDirectoryExists(public_path('desa'));
            Artisan::call('storage:link', ['--relative' => true]);
        } catch (Exception $e) {
            logger()->error('Gagal memasang symlink folder publik: ' . $e->getMessage());
        }
    }

    public function tambah_ubah_surat_bawaan(): void
    {
        $id = identitas('id');
        restoreSuratBawaanTinyMCE($id);
        restoreSuratBawaanDinasTinyMCE($id);
    }

    public function tambah_ulang_pengaturan(): void
    {
        (new ImportSetting())->handle();

        DB::table('setting_aplikasi')->whereIn('key', [
            'sebutan_pemerintah_desa',
            'compatible_version_general',
            'covid_desa',
            'covid_rss',
            'link_feed',
        ])->delete();

        cache()->flush();
    }

    // Menentukan build version
    protected function versionBuild(): void
    {
        $setting = SettingAplikasi::firstOrCreate(
            ['key' => 'version_build_script'],
            [
                'judul'      => 'Version Build Script',
                'value'      => '-',
                'jenis'      => 'input-text',
                'keterangan' => 'Versi Build Script',
                'option'     => null,
                'attribute'  => json_encode([
                    'disabled' => 'true',
                ]),
                'kategori' => 'sistem',
                'urut'     => 999,
            ]
        );

        (new SettingAplikasi())->flushQueryCache();

        if (null === $setting->value) {
            $version = match (true) {
                Schema::hasColumn('program', 'publikasi') && Schema::hasColumn('tweb_penduduk', 'is_historical') => '2026.02.01',
                Schema::hasTable('notifications') && Schema::hasColumn('anjungan', 'uuid')                       => '2026.01.01',
                Schema::hasColumn('buku_tamu', 'status') && ! Schema::hasTable('notifications')                  => '2025.12.01',
                default                                                                                          => $setting->value
            };

            SettingAplikasi::where('key', 'version_build_script')->update(['value' => $version]);
        }

        $data = SettingAplikasi::where('key', 'version_build_script')->first();

        if ($data) {
            $data->update([
                'attribute' => json_encode([
                    'disabled' => 'true',
                ]),
                'urut' => 999,
            ]);
        }

        (new SettingAplikasi())->flushQueryCache();
    }
};
