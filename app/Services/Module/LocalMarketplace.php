<?php

namespace App\Services\Module;

use Throwable;

/**
 * Marketplace surrogat berbasis repo lokal — simulasi Layanan untuk
 * PENGEMBANGAN. Menjadikan halaman "Paket Tambahan" (controller Plugin)
 * beroperasi atas repo lokal ketika mode "lokal" aktif: katalog, pengajuan
 * (get), dan riwayat pemesanan get/release dilayani dari sini.
 *
 * DEV-ONLY & di-`export-ignore` (tak ikut rilis). Guard `class_exists` di inti
 * (Plugin + AppServiceProvider) memastikan rilis tanpa berkas ini jatuh ke
 * jalur Layanan biasa.
 *
 * State (mode + opsi strategi/ref/fetch) disimpan di sesi CI; riwayat pemesanan
 * disimpan sebagai berkas JSON di `storage/app` (bukan tabel — nol jejak skema).
 */
class LocalMarketplace
{
    /** Kunci sesi CI untuk state mode + opsi. */
    private const SESI = 'dev_modul_sumber';

    private const ABAIKAN_TIPE_PREMIUM = 'premium';

    /**
     * Apakah mode marketplace lokal aktif. Default (sesi kosong): aktif bila
     * `module_dev_repo_base` dikonfigurasi. Toggle di tab "Sumber" menimpanya.
     */
    public static function aktif(): bool
    {
        $state = self::state();

        if (array_key_exists('lokal', $state)) {
            return (bool) $state['lokal'];
        }

        return self::repoBase() !== '';
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
     * Sumber modul lokal yang dibangun dari basis repo + opsi aktif.
     */
    public function sumber(): LocalRepoSource
    {
        $opsi = $this->opsi();

        return new LocalRepoSource(self::repoBase(), $opsi['strategy'], $opsi['ref'], $opsi['fetch']);
    }

    /**
     * Katalog modul dalam BENTUK API Layanan (`/api/v1/modules`) supaya JS tab
     * "Paket Tersedia"/"Form Pendaftaran" memakainya tanpa perubahan.
     *
     * @return array{data: list<array<string, mixed>>, meta: array{current_page: int, per_page: int, total: int}}
     */
    public function katalog(int $page = 1, string $tipe = ''): array
    {
        // Semua modul lokal diperlakukan gratis; filter "premium" → kosong.
        $data = $tipe === self::ABAIKAN_TIPE_PREMIUM ? [] : array_map(static function (array $m): array {
            return [
                'name'         => $m['name'],
                'url'          => 'local://' . $m['name'],
                'version'      => $m['version'] !== '' ? $m['version'] : '0.0.0',
                'description'  => $m['description'] !== '' ? $m['description'] : 'Modul lokal (repo pengembangan).',
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
     * Modul yang tersedia sebagai repo di bawah `module_dev_repo_base`.
     *
     * @return list<array{name: string, folder: string, is_git: bool, head: string, version: string, description: string, installed: bool}>
     */
    public function repos(): array
    {
        $base = self::repoBase();
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
     * Ajukan (GET) sebuah modul dari marketplace lokal: pasang via sumber lokal
     * dan catat pesanannya. Melempar bila gagal.
     */
    public function ajukan(string $name): bool
    {
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new \RuntimeException('Nama modul tidak valid.');
        }

        $opsi = $this->opsi();
        $baru = app(ModuleManager::class)->installFromSource($name, '', $this->sumber());

        $this->catat($name, 'terpasang', $opsi);

        return $baru;
    }

    /**
     * Catat pelepasan (RELEASE) modul — dipanggil inti setelah uninstall di
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

    private static function fileLog(): string
    {
        return storage_path('app/dev-modul-pesanan.json');
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
