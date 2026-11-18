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

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Mekanik pemasangan modul add-on: menjalankan migrasi paket & menegakkan
 * kompatibilitas `min_core`.
 *
 * Dipisah dari {@see Migrator} (yang permukaannya luas: FK/indeks/setting_modul
 * dll.) agar bisa dipakai ulang oleh {@see \App\Services\Module\ModuleManager}
 * tanpa menyeret seluruh trait. {@see Migrator} sendiri memakai trait ini
 * sehingga pemakai lamanya (migrasi/seeder/controller) tetap mendapat metode
 * ini apa adanya.
 */
trait ModuleMigrations
{
    /**
     * Periksa kompatibilitas core minimum untuk sebuah modul add-on.
     *
     * Membaca `min_core` dari `module.json` di direktori modul dan
     * membandingkannya dengan {@see VERSION} core. Mengembalikan pesan galat
     * bila core terlalu lama, atau null bila kompatibel / tak relevan.
     *
     * Dilewati di lingkungan development agar PoC & uji lokal (core dev yang
     * memuat seam tapi versinya belum dinaikkan) tetap bisa memasang add-on.
     *
     * @param string $moduleDir Direktori modul terekstrak (berisi module.json)
     */
    protected function periksaMinCoreModul(string $moduleDir): ?string
    {
        // Baca via constant() (bukan konstanta langsung) agar tak di-constant-fold
        // oleh analisstatik; lingkungan development melewati penegakan.
        if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) === 'development') {
            return null;
        }

        return $this->pesanMinCoreTakTerpenuhi($moduleDir);
    }

    /**
     * Logika murni min_core (tanpa bypass lingkungan): mengembalikan pesan galat
     * bila {@see VERSION} core lebih lama dari `min_core` di module.json modul,
     * atau null bila kompatibel / tak relevan.
     *
     * @param string $moduleDir Direktori modul terekstrak (berisi module.json)
     */
    protected function pesanMinCoreTakTerpenuhi(string $moduleDir): ?string
    {
        $manifest = rtrim($moduleDir, '/\\') . '/module.json';
        if (! is_file($manifest)) {
            return null;
        }

        $meta    = json_decode((string) file_get_contents($manifest), true);
        $minCore = is_array($meta) ? ($meta['min_core'] ?? null) : null;
        if (empty($minCore)) {
            return null;
        }

        if (version_compare(VERSION, (string) $minCore, '<')) {
            return "Modul membutuhkan OpenSID minimal versi {$minCore}; versi terpasang " . VERSION . '.';
        }

        return null;
    }

    /**
     * Jalankan migrasi modul.
     */
    private function jalankanMigrasiModule(string $name, string $action = 'up'): void
    {
        Log::info("Migrasi Module {$name}");

        $modulesDirectory = array_keys(config_item('modules_locations') ?? [])[0] ?? '';
        $dirOld           = "{$modulesDirectory}/{$name}/Database/Migrations";
        $dirNew           = "{$modulesDirectory}/{$name}/database/migrations";

        if (is_dir($dirOld)) {
            $directoryTable = $dirOld;
        } elseif (is_dir($dirNew)) {
            $directoryTable = $dirNew;
        } else {
            return;
        }

        $migrations = File::files($directoryTable);

        if ($action === 'up') {
            usort($migrations, static fn ($a, $b): int => strcmp($a->getFilename(), $b->getFilename()));
        } else {
            usort($migrations, static fn ($a, $b): int => strcmp($b->getFilename(), $a->getFilename()));
        }

        foreach ($migrations as $migrate) {
            $migrateFile = require $migrate->getPathname();

            match ($action) {
                'down'  => $migrateFile->down(),
                default => $migrateFile->up(),
            };

            Log::info("Migrasi {$action} {$migrate->getFilename()} berhasil dijalankan.");
        }

        cache()->flush();
    }
}
