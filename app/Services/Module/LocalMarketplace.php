<?php

namespace App\Services\Module;

use RuntimeException;
use Throwable;

/**
 * Marketplace surrogat berbasis repo lokal — simulasi Layanan untuk
 * PENGEMBANGAN. Menjadikan halaman "Paket Tambahan" (controller Plugin)
 * beroperasi atas paket lokal ketika mode "lokal" aktif: katalog, pengajuan
 * (get), dan riwayat pemesanan get/release dilayani dari sini.
 *
 * Marketplace diisi lewat UI: "daftarkan paket" menyalin sumbernya (repo lokal
 * atau paket terpasang) ke gudang `storage/app/dev-marketplace/<Nama>/`, sehingga
 * paket tetap ada di marketplace walau nanti dihapus (bisa diajukan ulang).
 * Repo di bawah `module_dev_repo_base` juga otomatis ikut sebagai paket "hidup".
 *
 * DEV-ONLY & di-`export-ignore` (tak ikut rilis). Guard `class_exists` di inti
 * (Plugin + AppServiceProvider) memastikan rilis tanpa berkas ini jatuh ke
 * jalur Layanan biasa. Kelas ini SENDIRI adalah {@see ModuleSource}: `fetch()`
 * meresolusi paket dari gudang (working-tree) atau repo basis (opsi strategi).
 */
class LocalMarketplace implements ModuleSource
{
    /** Kunci sesi CI untuk state mode + opsi. */
    private const SESI = 'dev_modul_sumber';

    private const ABAIKAN = ['.git', '.github', 'node_modules', 'vendor', '.DS_Store'];

    private const ABAIKAN_TIPE_PREMIUM = 'premium';

    /**
     * Apakah mode marketplace lokal aktif. Default (sesi kosong): aktif bila ada
     * paket terdaftar di gudang atau `module_dev_repo_base` dikonfigurasi.
     */
    public static function aktif(): bool
    {
        $state = self::state();

        if (array_key_exists('lokal', $state)) {
            return (bool) $state['lokal'];
        }

        return self::repoBase() !== '' || is_dir(self::storeDir());
    }

    /**
     * Setel mode + opsi (dipanggil dari tab "Sumber").
     */
    public static function setel(bool $lokal, string $strategy = 'working-tree', string $ref = 'HEAD', bool $fetch = false): void
    {
        self::simpanState([
            'lokal'    => $lokal,
            'strategy' => in_array($strategy, ['working-tree', 'git-archive'], true) ? $strategy : 'working-tree',
            'ref'      => $ref !== '' ? $ref : 'HEAD',
            'fetch'    => $fetch,
        ]);
    }

    /**
     * Opsi aktif (strategi/ref/fetch), dengan default dari config.
     *
     * @return array{strategy: string, ref: string, fetch: bool}
     */
    public function opsi(): array
    {
        $state = self::state();

        return [
            'strategy' => (string) ($state['strategy'] ?? (config_item('module_dev_repo_strategy') ?: 'working-tree')),
            'ref'      => (string) ($state['ref'] ?? (config_item('module_dev_repo_ref') ?: 'HEAD')),
            'fetch'    => array_key_exists('fetch', $state)
                ? (bool) $state['fetch']
                : filter_var(config_item('module_dev_repo_fetch'), FILTER_VALIDATE_BOOLEAN),
        ];
    }

    /**
     * {@see ModuleSource}: sediakan ZIP paket `$name`. Paket terdaftar (gudang)
     * dibungkus working-tree; paket repo basis memakai opsi strategi/ref/fetch.
     */
    public function fetch(string $name, string $locator = ''): string
    {
        if (self::resolveDir(self::storeDir(), $name) !== null) {
            return (new LocalRepoSource(self::storeDir(), 'working-tree'))->fetch($name, '');
        }

        $opsi = $this->opsi();

        return (new LocalRepoSource(self::repoBase(), $opsi['strategy'], $opsi['ref'], $opsi['fetch']))->fetch($name, $locator);
    }

    /**
     * Katalog paket dalam BENTUK API Layanan (`/api/v1/modules`) supaya JS tab
     * "Paket Tersedia"/"Form Pendaftaran" memakainya tanpa perubahan.
     *
     * @return array{data: list<array<string, mixed>>, meta: array{current_page: int, per_page: int, total: int}}
     */
    public function katalog(int $page = 1, string $tipe = ''): array
    {
        // Semua paket lokal diperlakukan gratis; filter "premium" → kosong.
        $data = $tipe === self::ABAIKAN_TIPE_PREMIUM ? [] : array_map(static function (array $m): array {
            return [
                'name'         => $m['name'],
                'url'          => 'local://' . $m['name'],
                'version'      => $m['version'] !== '' ? $m['version'] : '0.0.0',
                'description'  => $m['description'] !== '' ? $m['description'] : 'Paket lokal (marketplace pengembangan).',
                'thumbnail'    => '',
                'price'        => 'Gratis',
                'totalInstall' => 0,
            ];
        }, $this->repos());

        return [
            'data' => $data,
            'meta' => [
                'current_page' => 1,
                'per_page'     => max(1, count($data)),
                'total'        => count($data),
            ],
        ];
    }

    /**
     * Isi marketplace lokal: paket terdaftar (gudang) + repo hidup di bawah
     * `module_dev_repo_base`, digabung & dedup per nama (terdaftar diutamakan).
     *
     * @return list<array{name: string, folder: string, is_git: bool, head: string, version: string, description: string, installed: bool, terdaftar: bool}>
     */
    public function repos(): array
    {
        $daftar = [];

        foreach ($this->pindaiDir(self::storeDir()) as $entri) {
            $entri['terdaftar']    = true;
            $daftar[$entri['name']] = $entri;
        }

        foreach ($this->pindaiDir(self::repoBase()) as $entri) {
            if (! isset($daftar[$entri['name']])) {
                $entri['terdaftar']     = false;
                $daftar[$entri['name']] = $entri;
            }
        }

        return array_values($daftar);
    }

    /**
     * Kandidat paket yang bisa didaftarkan ke marketplace: paket terpasang
     * (folder `Modules/`) + repo di bawah basis — yang belum ada di gudang.
     *
     * @return list<array{name: string, path: string, asal: string}>
     */
    public function kandidat(): array
    {
        $sudah  = [];
        foreach ($this->pindaiDir(self::storeDir()) as $entri) {
            $sudah[$entri['name']] = true;
        }

        $kandidat = [];
        foreach ([['dir' => self::modulesDir(), 'asal' => 'terpasang'], ['dir' => self::repoBase(), 'asal' => 'repo']] as $sumber) {
            foreach ($this->pindaiDir($sumber['dir']) as $entri) {
                if (isset($sudah[$entri['name']])) {
                    continue;
                }
                $kandidat[$entri['name'] . '|' . $sumber['asal']] = [
                    'name' => $entri['name'],
                    'path' => rtrim($sumber['dir'], '/\\') . '/' . $entri['folder'],
                    'asal' => $sumber['asal'],
                ];
            }
        }

        return array_values($kandidat);
    }

    /**
     * Daftarkan paket ke marketplace: salin sumbernya ke gudang (snapshot yang
     * tahan-hapus). `$path` = folder paket berisi `module.json`.
     *
     * @throws RuntimeException bila path/manifest tak valid.
     */
    public function daftarkan(string $path): string
    {
        $path = rtrim($path, '/\\');
        if ($path === '' || ! is_dir($path) || ! is_file($path . '/module.json')) {
            throw new RuntimeException('Path paket tidak valid (folder berisi module.json diperlukan).');
        }

        $meta = json_decode((string) file_get_contents($path . '/module.json'), true);
        $name = is_array($meta) ? (string) ($meta['name'] ?? basename($path)) : basename($path);

        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new RuntimeException('Nama paket pada module.json tidak valid.');
        }

        $tujuan = self::storeDir() . '/' . $name;
        $this->hapusDir($tujuan);
        $this->salinDir($path, $tujuan);

        return $name;
    }

    /**
     * Batalkan pendaftaran paket dari marketplace (hapus dari gudang).
     */
    public function batalDaftar(string $name): void
    {
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new RuntimeException('Nama paket tidak valid.');
        }

        $dir = self::resolveDir(self::storeDir(), $name);
        if ($dir !== null) {
            $this->hapusDir($dir);
        }
    }

    /**
     * Ajukan (GET) sebuah paket dari marketplace lokal: pasang via sumber ini
     * dan catat pesanannya. Melempar bila gagal.
     */
    public function ajukan(string $name): bool
    {
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new RuntimeException('Nama paket tidak valid.');
        }

        $baru = app(ModuleManager::class)->installFromSource($name, '', $this);

        $this->catat($name, 'terpasang', $this->opsi());

        return $baru;
    }

    /**
     * Catat pelepasan (RELEASE) paket — dipanggil inti setelah uninstall di
     * mode lokal, agar riwayat get/release lengkap.
     */
    public function lepas(string $name): void
    {
        $this->catat($name, 'dihapus', $this->opsi());
    }

    /**
     * Riwayat pemesanan get/release (terbaru dulu).
     *
     * @return list<array<string, mixed>>
     */
    public function pesanan(): array
    {
        $file = self::fileLog();
        if (! is_file($file)) {
            return [];
        }

        $isi = json_decode((string) file_get_contents($file), true);

        return is_array($isi) ? array_reverse($isi) : [];
    }

    /**
     * @param array{strategy: string, ref: string, fetch: bool} $opsi
     */
    private function catat(string $name, string $status, array $opsi): void
    {
        $file = self::fileLog();
        $isi  = [];
        if (is_file($file)) {
            $decoded = json_decode((string) file_get_contents($file), true);
            $isi     = is_array($decoded) ? $decoded : [];
        }

        $isi[] = [
            'name'     => $name,
            'status'   => $status,
            'strategy' => $opsi['strategy'],
            'ref'      => $opsi['ref'],
            'fetch'    => $opsi['fetch'],
            'waktu'    => date('Y-m-d H:i:s'),
        ];

        @file_put_contents($file, json_encode($isi, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Pindai sebuah direktori untuk subfolder paket (punya module.json).
     *
     * @return list<array{name: string, folder: string, is_git: bool, head: string, version: string, description: string, installed: bool}>
     */
    private function pindaiDir(string $base): array
    {
        $base = rtrim($base, '/\\');
        if ($base === '' || ! is_dir($base)) {
            return [];
        }

        $daftar = [];

        foreach (glob($base . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
            $manifest = $dir . '/module.json';
            if (! is_file($manifest)) {
                continue;
            }

            $meta  = json_decode((string) file_get_contents($manifest), true);
            $meta  = is_array($meta) ? $meta : [];
            $name  = (string) ($meta['name'] ?? basename($dir));
            $isGit = is_dir($dir . '/.git');

            $daftar[] = [
                'name'        => $name,
                'folder'      => basename($dir),
                'is_git'      => $isGit,
                'head'        => $isGit ? $this->gitHead($dir) : '',
                'version'     => (string) ($meta['version'] ?? ''),
                'description' => (string) ($meta['description'] ?? ''),
                'installed'   => is_dir(self::modulesDir() . $name),
            ];
        }

        return $daftar;
    }

    /**
     * Resolusi folder paket `$name` di bawah `$base` (varian nama repo modul).
     */
    private static function resolveDir(string $base, string $name): ?string
    {
        $base = rtrim($base, '/\\');
        if ($base === '' || ! is_dir($base)) {
            return null;
        }

        foreach ([$name, 'modul-' . strtolower($name), strtolower($name)] as $kandidat) {
            $dir = $base . '/' . $kandidat;
            if (is_file($dir . '/module.json')) {
                return $dir;
            }
        }

        return null;
    }

    private function salinDir(string $src, string $dst): void
    {
        @mkdir($dst, 0777, true);

        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iter as $item) {
            $rel = substr((string) $item->getPathname(), strlen($src) + 1);

            $segments = explode(DIRECTORY_SEPARATOR, $rel);
            if (array_intersect($segments, self::ABAIKAN) !== []) {
                continue;
            }

            $target = $dst . '/' . $rel;
            if ($item->isDir()) {
                @mkdir($target, 0777, true);
            } else {
                @mkdir(dirname($target), 0777, true);
                @copy((string) $item->getPathname(), $target);
            }
        }
    }

    private function hapusDir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iter as $item) {
            $item->isDir() ? @rmdir((string) $item->getPathname()) : @unlink((string) $item->getPathname());
        }

        @rmdir($dir);
    }

    private function gitHead(string $dir): string
    {
        $out  = [];
        $code = 0;
        @exec('git -C ' . escapeshellarg($dir) . ' rev-parse --short HEAD 2>/dev/null', $out, $code);

        return $code === 0 ? trim((string) ($out[0] ?? '')) : '';
    }

    private static function repoBase(): string
    {
        return rtrim((string) config_item('module_dev_repo_base'), '/\\');
    }

    private static function modulesDir(): string
    {
        return (string) (array_keys(config_item('modules_locations') ?? [])[0] ?? '');
    }

    private static function storeDir(): string
    {
        return storage_path('app/dev-marketplace');
    }

    private static function fileLog(): string
    {
        return storage_path('app/dev-marketplace-pesanan.json');
    }

    /**
     * @return array<string, mixed>
     */
    private static function state(): array
    {
        $sesi = self::sesi();
        if ($sesi === null) {
            return [];
        }

        $state = $sesi->userdata(self::SESI);

        return is_array($state) ? $state : [];
    }

    /**
     * @param array<string, mixed> $state
     */
    private static function simpanState(array $state): void
    {
        self::sesi()?->set_userdata(self::SESI, $state);
    }

    /**
     * Sesi CI3 (hidup di konteks web; null di artisan murni).
     */
    private static function sesi(): ?object
    {
        try {
            $ci = function_exists('get_instance') ? get_instance() : null;
        } catch (Throwable) {
            return null;
        }

        return $ci && isset($ci->session) ? $ci->session : null;
    }
}
