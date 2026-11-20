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

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class RestoreFolderDesa
{
    /**
     * File konfigurasi lokal yang dipertahankan saat opsi "Pertahankan kredensial
     * lokal" aktif. config/database.php, app_key, dan .env berisi kredensial dan
     * kunci enkripsi spesifik instalasi ini — arsip dari instalasi lain akan
     * menimpanya (memutus koneksi database lokal) jika tidak dijaga.
     */
    private const FILES_TO_PRESERVE = ['config/database.php', 'app_key', '.env'];

    /**
     * Ekstrak arsip backup folder desa ke $extractPath.
     *
     * Saat $preserveKredensial aktif, file konfigurasi lokal (config/database.php,
     * app_key, .env) disimpan sebelum ekstraksi lalu dikembalikan sesudahnya, dan
     * config.app_key di database disinkronkan dengannya — sehingga arsip dari
     * instalasi berbeda tidak menimpa koneksi database & kunci enkripsi lokal.
     *
     * @param string $zipPath           Lokasi file arsip .zip hasil upload
     * @param string $extractPath        Direktori tujuan (folder desa)
     * @param bool   $preserveKredensial Pertahankan kredensial lokal (restore lintas-instalasi)
     *
     * @throws Exception jika arsip gagal dibuka
     */
    public function restore(string $zipPath, string $extractPath, bool $preserveKredensial): void
    {
        $extractPath = rtrim($extractPath, '/\\') . DIRECTORY_SEPARATOR;

        // Restore normal: ekstrak apa adanya (menimpa seluruh isi folder desa).
        if (! $preserveKredensial) {
            $this->ekstrak($zipPath, $extractPath);

            return;
        }

        // Simpan isi file konfigurasi lokal sebelum ekstraksi menimpanya.
        $stashed = [];
        foreach (self::FILES_TO_PRESERVE as $relative) {
            $full = $extractPath . str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (is_file($full)) {
                $stashed[$full] = file_get_contents($full);
            }
        }

        try {
            $this->ekstrak($zipPath, $extractPath);

            // Kembalikan file konfigurasi lokal yang ditimpa oleh isi arsip.
            $this->pulihkanStash($stashed);

            // Sinkronkan config.app_key di database dengan desa/app_key lokal yang
            // dipertahankan, agar cekConfig() tidak mendeteksi mismatch app_key
            // (yang akan tampak seperti gagal koneksi database).
            $this->sinkronAppKey($extractPath);

            $this->bersihkanCache();
        } catch (Exception $e) {
            // Selalu kembalikan file konfigurasi lokal meski ekstraksi gagal,
            // agar database.php/app_key tidak tertinggal dalam kondisi arsip.
            $this->pulihkanStash($stashed);

            throw $e;
        }
    }

    private function ekstrak(string $zipPath, string $extractPath): void
    {
        $zip = new ZipArchive();
        $res = $zip->open($zipPath);
        if ($res !== true) {
            throw new Exception("Gagal membuka arsip zip (kode: {$res})");
        }

        $zip->extractTo($extractPath);
        $zip->close();
    }

    /**
     * @param array<string, string> $stashed peta path absolut => isi file
     */
    private function pulihkanStash(array $stashed): void
    {
        foreach ($stashed as $full => $content) {
            file_put_contents($full, $content);
        }
    }

    private function sinkronAppKey(string $extractPath): void
    {
        $appKeyFile = $extractPath . 'app_key';
        if (! is_file($appKeyFile)) {
            return;
        }

        $localKey = trim((string) file_get_contents($appKeyFile));
        if ($localKey !== '') {
            DB::table('config')->update(['app_key' => $localKey]);
        }
    }

    private function bersihkanCache(): void
    {
        cache()->flush();

        foreach (glob(storage_path('framework/views') . DIRECTORY_SEPARATOR . '*.php') ?: [] as $file) {
            @unlink($file);
        }
    }
}
