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

namespace App\Actions\Desa;

use App\Models\Config;
use App\Services\AppKeyReEncrypter;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class RotateAppKeyAction
{
    public function __construct(
        protected AppKeyReEncrypter $reEncrypter
    ) {
    }

    /**
     * Jalankan proses rotasi APP_KEY.
     *
     * @throws Exception
     */
    public function execute(bool $dryRun = false, bool $skipUploads = false, ?Config $dbConfig = null): array
    {
        $appKeyFile   = base_path('desa/app_key');
        $dbConfigFile = base_path('desa/config/database.php');

        // Pastikan file key ada dan tidak kosong SEBELUM memanggil get_app_key(), karena
        // get_app_key() memiliki side-effect: auto-generate & menulis key baru ke file jika
        // file hilang/kosong. Tanpa guard ini, key asli bisa tertimpa sebelum backup dibuat.
        if (! File::exists($appKeyFile) || trim(File::get($appKeyFile)) === '') {
            throw new Exception(
                'File desa/app_key tidak ditemukan atau kosong. Rotasi dibatalkan untuk mencegah ' .
                'kehilangan key asli. Pulihkan file key dari backup terlebih dahulu.'
            );
        }

        $oldAppKey = get_app_key();
        $dbConfig ??= Config::first();

        // 1. Validasi awal
        if (! $dbConfig) {
            throw new Exception('Data config desa tidak ditemukan di database.');
        }

        if (! empty($dbConfig->app_key) && $dbConfig->app_key !== $oldAppKey) {
            throw new Exception("Mismatch key! Key di desa/app_key ({$oldAppKey}) tidak sama dengan key di config.app_key ({$dbConfig->app_key}).");
        }

        // 2. Generate key baru
        $newAppKey = set_app_key();

        $backupTimestamp = date('Ymd_His');
        $backupDir       = base_path("storage/backups/app_key_rotate_{$backupTimestamp}");

        $emptyUploadStats = ['renamed_files_count' => 0, 'updated_records_count' => 0, 'skipped_records_count' => 0, 'renamed_files' => []];

        if ($dryRun) {
            $uploadStats = $skipUploads ? $emptyUploadStats : $this->reEncrypter->reEncryptUploads($oldAppKey, $newAppKey, true);

            return [
                'success'      => true,
                'dry_run'      => true,
                'old_key'      => $oldAppKey,
                'new_key'      => $newAppKey,
                'backup_dir'   => $backupDir,
                'upload_stats' => $uploadStats,
            ];
        }

        // 3. Buat folder backup. Backup berisi kredensial (password DB) sehingga
        // permission-nya dibatasi hanya bisa dibaca pemilik proses.
        File::makeDirectory($backupDir, 0755, true, true);

        // $appKeyFile sudah dipastikan ada & tidak kosong oleh guard di awal method.
        File::copy($appKeyFile, $backupDir . '/app_key.bak');
        @chmod($backupDir . '/app_key.bak', 0600);

        if (File::exists($dbConfigFile)) {
            File::copy($dbConfigFile, $backupDir . '/database.php.bak');
            @chmod($backupDir . '/database.php.bak', 0600);
        }

        DB::beginTransaction();

        // Diinisialisasi sebelum try agar renamed_files tetap accessible di catch block
        // untuk keperluan reverse-rename file fisik bila terjadi kegagalan.
        $uploadStats   = $emptyUploadStats;
        $dbColumnStats = ['updated_records_count' => 0];

        try {
            // 4. Re-encrypt password DB
            $this->reEncrypter->reEncryptDatabasePassword($oldAppKey, $newAppKey);

            // 5. Re-encrypt upload data
            if (! $skipUploads) {
                $uploadStats = $this->reEncrypter->reEncryptUploads($oldAppKey, $newAppKey, false);
            }

            // 5b. Re-encrypt kolom DB yang memakai cast encrypted:array (APP_KEY)
            $dbColumnStats = $this->reEncrypter->reEncryptDatabaseColumns($oldAppKey, $newAppKey, false);

            // 6. Update config.app_key mirror (baris yang sama dengan yang divalidasi di atas)
            $dbConfig->update(['app_key' => $newAppKey]);

            // 7. Tulis desa/app_key (Langkah terakhir). Argumen ke-3 `true` adalah $lock
            // (LOCK_EX), bukan flag append seperti pada file_put_contents() native.
            File::put($appKeyFile, $newAppKey, true);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            // Kembalikan file fisik upload yang sudah ter-rename sebelum kegagalan terjadi.
            // File::move() berada di luar transaksi DB sehingga tidak ikut ter-rollback otomatis.
            foreach ($uploadStats['renamed_files'] ?? [] as $rename) {
                if (File::exists($rename['to']) && ! File::exists($rename['from'])) {
                    File::move($rename['to'], $rename['from']);
                }
            }

            // Restore file backup bila ada kegagalan
            if (File::exists($backupDir . '/app_key.bak')) {
                File::copy($backupDir . '/app_key.bak', $appKeyFile);
            }

            if (File::exists($backupDir . '/database.php.bak')) {
                File::copy($backupDir . '/database.php.bak', $dbConfigFile);
            }

            throw new Exception('Rotasi APP_KEY gagal dan telah di-rollback. Error: ' . $e->getMessage(), 0, $e);
        }

        // 8. Clear cache & session. Sesi aktif tidak di-flush secara eksplisit di sini;
        // invalidasinya mengandalkan kegagalan dekripsi cookie sesi dengan key baru.
        try {
            cache()->flush();
            if (function_exists('resetCacheDesa')) {
                resetCacheDesa();
            }
        } catch (Throwable $ex) {
            // Ignore cache clear error
        }

        return [
            'success'         => true,
            'dry_run'         => false,
            'old_key'         => $oldAppKey,
            'new_key'         => $newAppKey,
            'backup_dir'      => $backupDir,
            'upload_stats'    => $uploadStats,
            'db_column_stats' => $dbColumnStats,
        ];
    }
}
