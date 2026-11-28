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

namespace App\Services;

use App\Libraries\Checker;
use App\Models\Artikel;
use App\Models\Config;
use App\Models\Dokumen;
use App\Models\Galery;
use App\Models\LogPenduduk;
use App\Models\Pembangunan;
use App\Models\PembangunanDokumentasi;
use App\Models\Penduduk;
use App\Models\PendudukMandiri;
use App\Models\Pengaduan;
use App\Models\SecurityBaseline;
use App\Models\SecurityReport;
use App\Models\SinergiProgram;
use App\Models\Widget;
use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class AppKeyReEncrypter
{
    /**
     * Re-encrypt password database pada desa/config/database.php.
     */
    public function reEncryptDatabasePassword(string $oldAppKey, string $newAppKey): void
    {
        $databaseConfigFile = base_path('desa/config/database.php');

        if (! File::exists($databaseConfigFile)) {
            return;
        }

        $configPassword = (new Config())->getConnection()->getConfig('password');

        if (empty($configPassword)) {
            return;
        }

        $plainPassword = $configPassword;

        // Coba decrypt dengan oldAppKey
        try {
            $rawOldKey     = base64_decode(Str::after($oldAppKey, 'base64:'));
            $oldEncrypter  = new Encrypter($rawOldKey, 'AES-256-CBC');
            $plainPassword = $oldEncrypter->decrypt($configPassword);
        } catch (Throwable $e) {
            try {
                $plainPassword = decrypt($configPassword);
            } catch (Throwable $ex) {
                $plainPassword = $configPassword;
            }
        }

        // Encrypt dengan key baru
        $rawNewKey            = base64_decode(Str::after($newAppKey, 'base64:'));
        $newEncrypter         = new Encrypter($rawNewKey, 'AES-256-CBC');
        $newEncryptedPassword = $newEncrypter->encrypt($plainPassword);

        // Verifikasi hasil re-encrypt bisa didekripsi kembali menjadi password yang sama,
        // supaya kegagalan decrypt "kebetulan valid" (padding AES) tidak lolos tanpa terdeteksi.
        if ($newEncrypter->decrypt($newEncryptedPassword) !== $plainPassword) {
            throw new Exception('Verifikasi re-encrypt password database gagal.');
        }

        // Update desa/config/database.php
        if (function_exists('updateConfigFile')) {
            updateConfigFile('password', $newEncryptedPassword);
        } else {
            $configContent = File::get($databaseConfigFile);
            $pattern       = '/(\$db\[\'default\'\]\[\'password\'\]\s*=\s*\'.*\';)/';
            $newKeyStr     = "\$db['default']['password'] = '{$newEncryptedPassword}';";

            if (preg_match($pattern, $configContent)) {
                $configContent = preg_replace($pattern, $newKeyStr, $configContent);
            } else {
                $configContent .= PHP_EOL . $newKeyStr;
            }

            File::put($databaseConfigFile, $configContent);
        }
    }

    /**
     * Re-encrypt file Checker di filesystem & database.
     */
    public function reEncryptUploads(string $oldAppKey, string $newAppKey, bool $dryRun = false): array
    {
        $renamedFiles   = [];
        $updatedRecords = 0;
        $skippedRecords = 0;

        $targets = [
            [
                'model'   => Dokumen::class,
                'columns' => ['satuan'],
                'path'    => base_path('desa/upload/dokumen'),
            ],
            [
                'model'   => Artikel::class,
                'columns' => ['gambar', 'gambar1', 'gambar2', 'gambar3', 'dokumen'],
                'path'    => base_path('desa/upload/artikel'),
            ],
            [
                'model'   => Galery::class,
                'columns' => ['gambar'],
                'path'    => base_path('desa/upload/galeri'),
            ],
            [
                'model'   => Penduduk::class,
                'columns' => ['foto'],
                'path'    => base_path('desa/upload/user_pict'),
            ],
            [
                'model'   => LogPenduduk::class,
                'columns' => ['file_bukti'],
                'path'    => base_path('desa/upload/dokumen'),
            ],
            [
                'model'   => PendudukMandiri::class,
                'columns' => ['foto'],
                'path'    => base_path('desa/upload/user_pict'),
            ],
            [
                'model'   => Pembangunan::class,
                'columns' => ['foto'],
                'path'    => base_path('desa/upload/pembangunan'),
            ],
            [
                'model'   => PembangunanDokumentasi::class,
                'columns' => ['gambar'],
                'path'    => base_path('desa/upload/pembangunan'),
            ],
            [
                'model'   => Pengaduan::class,
                'columns' => ['foto'],
                'path'    => base_path('desa/upload/pengaduan'),
            ],
            [
                'model'   => SinergiProgram::class,
                'columns' => ['gambar'],
                'path'    => base_path('desa/upload/sinergi'),
            ],
            [
                'model'   => Widget::class,
                'columns' => ['foto'],
                'path'    => base_path('desa/upload/widget'),
            ],
        ];

        foreach ($targets as $target) {
            $modelClass = $target['model'];
            if (! class_exists($modelClass)) {
                continue;
            }

            $columns   = $target['columns'];
            $uploadDir = $target['path'];

            $records = $modelClass::query()->get();

            foreach ($records as $record) {
                $updates    = [];
                $hasSkipped = false;

                foreach ($columns as $column) {
                    $filename = $record->getAttribute($column);

                    if (empty($filename) || ! is_string($filename)) {
                        continue;
                    }

                    $checkerOld = new Checker($oldAppKey, $filename);
                    if (! $checkerOld->isValid()) {
                        $hasSkipped = true;

                        continue;
                    }

                    $originalName          = $checkerOld->getCurrentName();
                    [$oldHash, $cleanName] = explode('_', (string) $originalName, 2) + ['', $originalName];

                    if (! empty($cleanName)) {
                        $checkerNew  = new Checker($newAppKey, $cleanName);
                        $newFilename = $checkerNew->encrypt();

                        $updates[$column] = $newFilename;

                        if ($uploadDir && File::isDirectory($uploadDir)) {
                            $prefixes = ['', 'kecil_', 'sedang_'];

                            foreach ($prefixes as $prefix) {
                                $oldFilePhys = $uploadDir . '/' . $prefix . $filename;
                                $newFilePhys = $uploadDir . '/' . $prefix . $newFilename;

                                if (File::exists($oldFilePhys)) {
                                    $renamedFiles[] = [
                                        'from' => $oldFilePhys,
                                        'to'   => $newFilePhys,
                                    ];

                                    if (! $dryRun) {
                                        File::move($oldFilePhys, $newFilePhys);
                                    }
                                }
                            }
                        }
                    }
                }

                if (! empty($updates)) {
                    $updatedRecords++;
                    if (! $dryRun) {
                        $record->update($updates);
                    }
                } elseif ($hasSkipped) {
                    $skippedRecords++;
                }
            }
        }

        return [
            'renamed_files_count'   => count($renamedFiles),
            'updated_records_count' => $updatedRecords,
            'skipped_records_count' => $skippedRecords,
            'renamed_files'         => $renamedFiles,
        ];
    }

    /**
     * Re-encrypt kolom database yang memakai cast `encrypted:array` (APP_KEY).
     */
    public function reEncryptDatabaseColumns(string $oldAppKey, string $newAppKey, bool $dryRun = false): array
    {
        $updatedRecords = 0;

        $rawOldKey    = base64_decode(Str::after($oldAppKey, 'base64:'));
        $oldEncrypter = new Encrypter($rawOldKey, 'AES-256-CBC');
        $rawNewKey    = base64_decode(Str::after($newAppKey, 'base64:'));
        $newEncrypter = new Encrypter($rawNewKey, 'AES-256-CBC');

        $targets = [
            ['model' => SecurityReport::class, 'columns' => ['data']],
            ['model' => SecurityBaseline::class, 'columns' => ['files']],
        ];

        foreach ($targets as $target) {
            $modelClass = $target['model'];
            if (! class_exists($modelClass)) {
                continue;
            }

            foreach ($modelClass::query()->get() as $record) {
                $updates = [];

                foreach ($target['columns'] as $column) {
                    // Baca blob mentah, bypass cast agar tidak auto-decrypt dengan key aktif
                    $raw = $record->getRawOriginal($column);

                    if (empty($raw) || ! is_string($raw)) {
                        continue;
                    }

                    try {
                        // Cast `encrypted:array` menyimpan payload lewat encrypt($json, false)/
                        // decrypt($value, false) (unserialize=false, lihat HasAttributes::
                        // fromEncryptedString/castAttributeAsEncryptedString) — bukan PHP serialize.
                        $plain            = $oldEncrypter->decrypt($raw, false);
                        $updates[$column] = $newEncrypter->encrypt($plain, false);
                    } catch (Throwable $e) {
                        continue;
                    }
                }

                if (! empty($updates)) {
                    $updatedRecords++;
                    if (! $dryRun) {
                        // Update via query builder, bukan $record->update(), agar cast model
                        // tidak meng-enkripsi ulang dengan APP_KEY lama yang masih aktif di request ini
                        $modelClass::query()->where('id', $record->id)->update($updates);
                    }
                }
            }
        }

        return [
            'updated_records_count' => $updatedRecords,
        ];
    }
}
