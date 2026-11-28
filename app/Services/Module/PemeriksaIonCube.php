<?php

declare(strict_types=1);

namespace App\Services\Module;

use RuntimeException;
use Throwable;

/**
 * Pemeriksa IonCube Loader untuk add-on terenkripsi (HANYA rilis Umum; overlay pipeline).
 *
 * Inti Umum terbuka dan TIDAK butuh IonCube. Add-on berbayar (Pelanggan, Anjungan, Buku Tamu) disajikan Layanan
 * terenkripsi IonCube, sehingga server pemasangnya wajib punya IonCube Loader untuk versi PHP-nya
 * (dokumentasi/RENCANA_RILIS_UMUM_2701_2709.md §6, tugas T18). Tanpa Loader, memuat satu berkas terenkripsi
 * mematikan SELURUH halaman (`die` dari stub IonCube), jadi dua penjaga dipasang:
 *
 * 1. PASANG  — `pastikanLoaderUntukModul()` menolak pemasangan modul terenkripsi dengan pesan jelas
 *    (dipanggil `ModuleManager::installFromSource` setelah ekstrak; kegagalan membuang ekstrak / memulihkan cadangan).
 * 2. BOOT    — `lewatiModulTanpaLoader()` dipanggil sebelum modul dimuat: modul terenkripsi tanpa Loader dilewati
 *    (tidak didaftarkan) dan autoload kelas `Modules\<Nama>\` diblokir, supaya `class_exists()` penjaga di inti
 *    tak ikut memicu berkas terenkripsi.
 *
 * Deteksi terenkripsi: bendera `requires_ioncube` di manifest, atau kepala berkas PHP (1 KB pertama) memuat stub
 * pemuat IonCube ("ionCube Loader") / penanda `//ICB0`.
 */
final class PemeriksaIonCube
{
    private const MAKS_BERKAS_PINDAI = 3000;

    /** @var array<string, true> */
    private static array $dilewati = [];

    /** @var list<string> prefiks namespace yang diblokir autoload-nya */
    private static array $prefiks = [];

    private static bool $autoloadDibungkus = false;

    public static function loaderTersedia(): bool
    {
        return extension_loaded('ionCube Loader');
    }

    public static function versiLoader(): ?string
    {
        if (self::loaderTersedia() && function_exists('ioncube_loader_version')) {
            return (string) ioncube_loader_version();
        }

        return null;
    }

    public static function berkasTerenkripsi(string $berkas): bool
    {
        if (! is_file($berkas) || ! is_readable($berkas)) {
            return false;
        }

        $kepala = @file_get_contents($berkas, false, null, 0, 1024);
        if ($kepala === false || $kepala === '') {
            return false;
        }

        return stripos($kepala, 'ioncube loader') !== false
            || preg_match('/^<\?php\s*\/\/ICB0/', $kepala) === 1;
    }

    /**
     * @param bool $penuh true = pindai SELURUH berkas PHP modul (saat pasang); false = hanya manifest dan provider
     *                    (saat boot, murah: tiap permintaan)
     */
    public static function modulTerenkripsi(string $dir, bool $penuh = false): bool
    {
        $dir = rtrim($dir, '/\\');

        $manifest = $dir . '/module.json';
        if (is_file($manifest)) {
            $data = json_decode((string) @file_get_contents($manifest), true);
            if (is_array($data) && ! empty($data['requires_ioncube'])) {
                return true;
            }
        }

        $nama = basename($dir);
        foreach (["{$dir}/Providers/{$nama}ServiceProvider.php", "{$dir}/App/Providers/{$nama}ServiceProvider.php"] as $provider) {
            if (self::berkasTerenkripsi($provider)) {
                return true;
            }
        }

        if (! $penuh || ! is_dir($dir)) {
            return false;
        }

        $dihitung = 0;
        $iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($iter as $berkas) {
            if (! $berkas->isFile() || strtolower($berkas->getExtension()) !== 'php') {
                continue;
            }
            if (++$dihitung > self::MAKS_BERKAS_PINDAI) {
                break;
            }
            if (self::berkasTerenkripsi($berkas->getPathname())) {
                return true;
            }
        }

        return false;
    }

    public static function pesanLoaderHilang(string $nama): string
    {
        return sprintf(
            'Modul %s dilindungi IonCube dan membutuhkan IonCube Loader untuk PHP %s, yang belum terpasang di server ini. '
            . 'Pasang IonCube Loader (https://www.ioncube.com/loaders.php) lalu ulangi pemasangan. '
            . 'Situs tetap berjalan tanpa modul ini.',
            $nama,
            PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION
        );
    }

    /**
     * Penjaga PASANG.
     *
     * @throws RuntimeException bila modul terenkripsi dan Loader tidak ada
     */
    public static function pastikanLoaderUntukModul(string $nama, string $dir): void
    {
        if (self::loaderTersedia()) {
            return;
        }

        if (self::modulTerenkripsi($dir, true)) {
            throw new RuntimeException(self::pesanLoaderHilang($nama));
        }
    }

    /**
     * Penjaga BOOT: panggil sebelum memuat modul-modul.
     *
     * @param iterable<string> $direktoriModul daftar path direktori modul
     */
    public static function lewatiModulTanpaLoader(iterable $direktoriModul): void
    {
        if (self::loaderTersedia()) {
            return;
        }

        foreach ($direktoriModul as $dir) {
            $nama = basename((string) $dir);
            if (isset(self::$dilewati[$nama]) || ! self::modulTerenkripsi((string) $dir)) {
                continue;
            }

            self::$dilewati[$nama] = true;
            self::$prefiks[]       = "Modules\\{$nama}\\";

            try {
                error_log('[OpenSID] ' . self::pesanLoaderHilang($nama));
            } catch (Throwable) {
                // pencatatan tak boleh menggagalkan boot
            }
        }

        if (self::$prefiks !== []) {
            self::bungkusAutoload();
        }
    }

    /**
     * Penjaga RUTE (T31): saring daftar berkas rute modul (`<Modules>/<Nama>/Routes/web.php|api.php`) sebelum di-`require`.
     *
     * Pemuat rute (`vendor/opensid/router` `Hook.php` pada 2701–2705, `donjo-app/hooks/RouterHook.php` pada 2706+) berjalan di `pre_system`
     * dan me-`require` SETIAP `Routes/web.php` modul tanpa melewati penjaga boot. Berkas terenkripsi tanpa Loader mematikan seluruh situs,
     * jadi modul terenkripsi dibuang dari daftar saat Loader tak ada (penentuan memakai manifest/provider saja, tak bergantung urutan boot).
     *
     * @param  array<int, string>  $berkas
     * @return list<string>
     */
    public static function saringRute(array $berkas): array
    {
        if (self::loaderTersedia()) {
            return array_values($berkas);
        }

        $hasil = [];
        foreach ($berkas as $b) {
            if (self::modulTerenkripsi(dirname((string) $b, 2))) {
                continue;
            }
            $hasil[] = $b;
        }

        return $hasil;
    }

    public static function dilewati(string $nama): bool
    {
        return isset(self::$dilewati[$nama]);
    }

    /**
     * @return list<string> nama modul terenkripsi yang dilewati karena Loader tak ada
     */
    public static function modulDilewati(): array
    {
        return array_keys(self::$dilewati);
    }

    private static function bungkusAutoload(): void
    {
        if (self::$autoloadDibungkus || ! class_exists(\Composer\Autoload\ClassLoader::class, false)) {
            return;
        }

        foreach (\Composer\Autoload\ClassLoader::getRegisteredLoaders() as $loader) {
            if (! isset($loader->getPrefixesPsr4()['Modules\\'])) {
                continue;
            }

            spl_autoload_unregister([$loader, 'loadClass']);
            spl_autoload_register(static function (string $kelas) use ($loader): void {
                foreach (self::$prefiks as $prefiks) {
                    if (strncmp($kelas, $prefiks, strlen($prefiks)) === 0) {
                        return;
                    }
                }
                $loader->loadClass($kelas);
            }, true, true);
        }

        self::$autoloadDibungkus = true;
    }
}
