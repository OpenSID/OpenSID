<?php

namespace App\Services\Module;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;
use ZipArchive;

/**
 * Bursa paket surrogat berbasis GUDANG ZIP — simulasi Layanan untuk
 * PENGEMBANGAN. Sama seperti Layanan, bursa paket lokal hanyalah **kumpulan
 * berkas ZIP paket**. Halaman "Paket Tambahan" (controller Plugin) beroperasi
 * atasnya ketika mode "lokal" aktif: katalog, pengajuan (get), dan riwayat
 * pemesanan get/release dilayani dari sini.
 *
 * Diisi lewat UI dengan **menempel URL repo** (mis. `https://github.com/OpenSID/
 * modul-anjungan`): sistem mengunduh ZIP repo (via `gh` untuk repo privat) ke
 * gudang `storage/app/dev-marketplace/<Nama>.zip` + sidecar `<Nama>.json`.
 * Mendukung verifikasi paket garapan orang lain tanpa checkout lokal. Bisa juga
 * mendaftarkan dari folder lokal (snapshot working-tree).
 *
 * DEV-ONLY & di-`export-ignore` (tak ikut rilis). Guard `class_exists` di inti
 * (Plugin + AppServiceProvider) memastikan rilis tanpa berkas ini jatuh ke
 * jalur Layanan biasa. Kelas ini SENDIRI adalah {@see ModuleSource}: `fetch()`
 * menyajikan ZIP tersimpan dari gudang.
 */
class LocalMarketplace implements ModuleSource
{
    /** Kunci sesi CI untuk state mode. */
    private const SESI = 'dev_modul_sumber';

    private const ABAIKAN_TIPE_PREMIUM = 'premium';

    /**
     * Apakah mode bursa paket lokal aktif. Default (sesi kosong): aktif bila
     * gudang sudah berisi paket.
     */
    public static function aktif(): bool
    {
        $state = self::state();

        if (array_key_exists('lokal', $state)) {
            return (bool) $state['lokal'];
        }

        return (glob(self::storeDir() . '/*.zip') ?: []) !== [];
    }

    /**
     * Setel mode (dipanggil dari tab "Sumber").
     */
    public static function setel(bool $lokal): void
    {
        $state          = self::state();
        $state['lokal'] = $lokal;
        self::simpanState($state);
    }

    /**
     * {@see ModuleSource}: sediakan ZIP paket `$name` dari gudang (salinan tmp
     * agar berkas gudang tak terpindah saat diekstrak inti).
     */
    public function fetch(string $name, string $locator = ''): string
    {
        $zip = self::storeDir() . '/' . $name . '.zip';
        if (! is_file($zip)) {
            throw new RuntimeException("Paket {$name} tak ada di bursa paket lokal. Daftarkan dulu lewat tab Sumber.");
        }

        $tmp = rtrim(sys_get_temp_dir(), '/\\') . '/mp-' . $name . '-' . uniqid('', true) . '.zip';
        if (! @copy($zip, $tmp)) {
            throw new RuntimeException("Gagal menyiapkan ZIP paket {$name}.");
        }

        return $tmp;
    }

    /**
     * Katalog paket dalam BENTUK API Layanan (`/api/v1/modules`) supaya JS tab
     * "Paket Tersedia"/"Form Pendaftaran" memakainya tanpa perubahan.
     *
     * @return array{data: list<array<string, mixed>>, meta: array{current_page: int, per_page: int, total: int}}
     */
    public function katalog(int $page = 1, string $tipe = ''): array
    {
        // Thumbnail bawaan (ikon kubus paket) sebagai data-URI — mandiri, tak
        // butuh berkas aset, mengganti <img src=""> yang tampil sebagai ikon rusak.
        $thumb = 'data:image/svg+xml;base64,' . base64_encode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 24 24" fill="none" '
            . 'stroke="#3c8dbc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">'
            . '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>'
            . '<polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>'
        );

        // Semua paket lokal diperlakukan gratis; filter "premium" → kosong.
        $data = $tipe === self::ABAIKAN_TIPE_PREMIUM ? [] : array_map(static function (array $m) use ($thumb): array {
            return [
                'name'         => $m['name'],
                'url'          => 'local://' . $m['name'],
                'version'      => $m['version'] !== '' ? $m['version'] : '0.0.0',
                'description'  => $m['description'] !== '' ? $m['description'] : 'Paket lokal (bursa paket pengembangan).',
                'thumbnail'    => $thumb,
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
     * Isi marketplace: paket ZIP tersimpan di gudang (dari sidecar).
     *
     * @return list<array{name: string, version: string, description: string, sumber: string, ref: string, waktu: string, installed: bool}>
     */
    public function repos(): array
    {
        $daftar = [];

        foreach (glob(self::storeDir() . '/*.json') ?: [] as $sidecar) {
            $meta = json_decode((string) file_get_contents($sidecar), true);
            if (! is_array($meta) || empty($meta['name'])) {
                continue;
            }

            $name = (string) $meta['name'];

            $daftar[] = [
                'name'        => $name,
                'version'     => (string) ($meta['version'] ?? ''),
                'description' => (string) ($meta['description'] ?? ''),
                'sumber'      => (string) ($meta['sumber'] ?? ''),
                'ref'         => (string) ($meta['ref'] ?? ''),
                'waktu'       => (string) ($meta['waktu'] ?? ''),
                'installed'   => is_dir(self::modulesDir() . $name),
            ];
        }

        return $daftar;
    }

    /**
     * Kandidat pendaftaran-lokal: paket terpasang (`Modules/`) yang belum ada di
     * gudang — untuk snapshot cepat tanpa URL.
     *
     * @return list<array{name: string, path: string}>
     */
    public function kandidat(): array
    {
        $sudah = [];
        foreach ($this->repos() as $entri) {
            $sudah[$entri['name']] = true;
        }

        $kandidat = [];
        $modulesDir = rtrim(self::modulesDir(), '/\\');
        if ($modulesDir !== '' && is_dir($modulesDir)) {
            foreach (glob($modulesDir . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
                if (! is_file($dir . '/module.json')) {
                    continue;
                }
                $meta = json_decode((string) file_get_contents($dir . '/module.json'), true);
                $name = is_array($meta) ? (string) ($meta['name'] ?? basename($dir)) : basename($dir);
                if (isset($sudah[$name])) {
                    continue;
                }
                $kandidat[$name] = ['name' => $name, 'path' => $dir];
            }
        }

        return array_values($kandidat);
    }

    /**
     * Daftarkan paket dari URL repo (mis. GitHub) ke marketplace: unduh ZIP repo
     * dan simpan ke gudang. `$ref` opsional (cabang/tag; default cabang utama).
     *
     * @throws RuntimeException bila URL/unduhan/manifest tak valid.
     */
    public function daftarkanUrl(string $url, string $ref = ''): string
    {
        [$owner, $repo, $refDariUrl] = $this->uraikanUrlGithub($url);
        $ref = $ref !== '' ? $ref : $refDariUrl;

        $tmp = rtrim(sys_get_temp_dir(), '/\\') . '/mp-unduh-' . uniqid('', true) . '.zip';

        try {
            $this->unduhZipGithub($owner, $repo, $ref, $tmp);

            return $this->simpanKeGudang($tmp, 'url:' . $owner . '/' . $repo, $ref ?: 'default');
        } finally {
            @unlink($tmp);
        }
    }

    /**
     * Daftarkan paket dari folder lokal (snapshot working-tree) ke bursa paket.
     *
     * @throws RuntimeException bila path/manifest tak valid.
     */
    public function daftarkanLokal(string $path): string
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

        // Bungkus working-tree jadi ZIP (top folder <Nama>-src/) lalu simpan.
        $zip = (new LocalRepoSource(dirname($path), 'working-tree'))->fetch($name);

        try {
            return $this->simpanKeGudang($zip, 'lokal:' . $path, '');
        } finally {
            @unlink($zip);
        }
    }

    /**
     * Batalkan pendaftaran paket dari bursa paket (hapus ZIP + sidecar).
     */
    public function batalDaftar(string $name): void
    {
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new RuntimeException('Nama paket tidak valid.');
        }

        @unlink(self::storeDir() . '/' . $name . '.zip');
        @unlink(self::storeDir() . '/' . $name . '.json');
    }

    /**
     * Ajukan (GET) sebuah paket dari bursa paket lokal: pasang via ZIP gudang
     * dan catat pesanannya. Melempar bila gagal.
     */
    public function ajukan(string $name): bool
    {
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new RuntimeException('Nama paket tidak valid.');
        }

        $baru = app(ModuleManager::class)->installFromSource($name, '', $this);

        $this->catat($name, 'terpasang');

        return $baru;
    }

    /**
     * Catat pelepasan (RELEASE) paket — dipanggil inti setelah uninstall di
     * mode lokal, agar riwayat get/release lengkap.
     */
    public function lepas(string $name): void
    {
        $this->catat($name, 'dihapus');
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
     * Pindahkan ZIP terunduh/terbungkus ke gudang sebagai `<Nama>.zip` + sidecar.
     * Nama & versi dibaca dari `module.json` di dalam ZIP.
     *
     * @throws RuntimeException bila ZIP tak memuat module.json valid.
     */
    private function simpanKeGudang(string $zipPath, string $sumber, string $ref): string
    {
        $meta = $this->bacaManifestZip($zipPath);
        $name = (string) ($meta['name'] ?? '');
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new RuntimeException('ZIP tidak memuat module.json dengan nama paket yang valid.');
        }

        $store = self::storeDir();
        @mkdir($store, 0777, true);

        if (! @copy($zipPath, $store . '/' . $name . '.zip')) {
            throw new RuntimeException("Gagal menyimpan ZIP paket {$name} ke gudang.");
        }

        @file_put_contents($store . '/' . $name . '.json', json_encode([
            'name'        => $name,
            'version'     => (string) ($meta['version'] ?? ''),
            'description' => (string) ($meta['description'] ?? ''),
            'sumber'      => $sumber,
            'ref'         => $ref,
            'waktu'       => date('Y-m-d H:i:s'),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $name;
    }

    /**
     * Baca module.json dari folder puncak sebuah ZIP paket.
     *
     * @return array<string, mixed>
     */
    private function bacaManifestZip(string $zipPath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('Berkas ZIP tidak dapat dibuka.');
        }

        $isi = null;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = (string) $zip->getNameIndex($i);
            if (preg_match('#^[^/]+/module\.json$#', $entry)) {
                $isi = $zip->getFromIndex($i);
                break;
            }
        }
        $zip->close();

        if ($isi === null || $isi === false) {
            throw new RuntimeException('ZIP tidak memuat module.json di folder puncak paket.');
        }

        $meta = json_decode((string) $isi, true);

        return is_array($meta) ? $meta : [];
    }

    /**
     * Uraikan URL repo GitHub → [owner, repo, ref]. Mendukung bentuk
     * `https://github.com/o/r`, `.../o/r.git`, `.../o/r/tree/<ref>`, `git@…`.
     *
     * @return array{0: string, 1: string, 2: string}
     */
    private function uraikanUrlGithub(string $url): array
    {
        $url = trim($url);

        // URL penuh GitHub (https://…, git@…, dengan/atau tanpa /tree/<ref>).
        if (preg_match('#github\.com[/:]([^/]+)/([^/]+?)(?:\.git)?(?:/tree/([^/\s]+))?/?$#i', $url, $m)) {
            return [$m[1], $m[2], $m[3] ?? ''];
        }

        // Bentuk ringkas "owner/repo[/tree/<ref>]" (dari prefix https://github.com/).
        if (preg_match('#^([^/\s]+)/([^/\s]+?)(?:\.git)?(?:/tree/([^/\s]+))?/?$#', $url, $m)) {
            return [$m[1], $m[2], $m[3] ?? ''];
        }

        throw new RuntimeException('URL repo GitHub tidak dikenali (contoh: OpenSID/modul-anjungan).');
    }

    /**
     * Unduh ZIP arsip repo GitHub ke `$tujuan`. Utamakan `gh` (mendukung repo
     * privat via auth pengguna); jika absen, HTTP dengan `GITHUB_TOKEN`.
     *
     * @throws RuntimeException bila unduhan gagal.
     */
    private function unduhZipGithub(string $owner, string $repo, string $ref, string $tujuan): void
    {
        $path = "repos/{$owner}/{$repo}/zipball" . ($ref !== '' ? '/' . $ref : '');

        try {
            $proc = new Process(['gh', 'api', $path]);
            $proc->setTimeout(180);
            $proc->run();

            if ($proc->isSuccessful()) {
                $keluaran = $proc->getOutput();
                if ($keluaran !== '' && @file_put_contents($tujuan, $keluaran) !== false) {
                    return;
                }
            }

            $ghError = trim($proc->getErrorOutput());
        } catch (Throwable $e) {
            $ghError = $e->getMessage();
        }

        // Fallback HTTP (repo publik, atau privat bila GITHUB_TOKEN diset).
        $token = (string) getenv('GITHUB_TOKEN');
        $req   = $token !== '' ? Http::withToken($token) : Http::withHeaders([]);
        $resp  = $req->withOptions(['sink' => $tujuan, 'timeout' => 180])
            ->get("https://api.github.com/repos/{$owner}/{$repo}/zipball" . ($ref !== '' ? '/' . $ref : ''));

        if (! $resp->successful() || ! is_file($tujuan) || filesize($tujuan) === 0) {
            throw new RuntimeException('Gagal mengunduh ZIP repo (gh: ' . $ghError
                . '; HTTP ' . $resp->status() . '). Untuk repo privat, pastikan `gh auth login` atau GITHUB_TOKEN.');
        }
    }

    private function catat(string $name, string $status): void
    {
        $file = self::fileLog();
        $isi  = [];
        if (is_file($file)) {
            $decoded = json_decode((string) file_get_contents($file), true);
            $isi     = is_array($decoded) ? $decoded : [];
        }

        $isi[] = [
            'name'   => $name,
            'status' => $status,
            'waktu'  => date('Y-m-d H:i:s'),
        ];

        @file_put_contents($file, json_encode($isi, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
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
