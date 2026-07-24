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

namespace App\Services\Module;

use App\Models\Modul;
use App\Models\SettingAplikasi;
use App\Services\Kapabilitas\GerbangFitur;
use App\Traits\ModuleMigrations;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;
use ZipArchive;

/**
 * Pemilik tunggal siklus-hidup modul (add-on-agnostik).
 *
 * Core TIDAK menyimpan daftar nama modul. Sifat tiap modul — apakah butuh
 * entitlement (langganan) dan boleh dihapus — dibaca dari `module.json`-nya,
 * bukan dari konstanta core seperti MODUL_BAWAAN (dihapus). Status entitlement
 * ditanyakan ke {@see GerbangFitur} yang diisi add-on Layanan.
 *
 * Empat status modul yang dipisah tegas (lihat master doc §11):
 *   - terpasang (installed): folder + `module.json` ada di disk → {@see isInstalled()}
 *   - terdaftar (registered): baris `setting_modul`/`grup_akses` (di-seed migrasi add-on)
 *   - aktif/nonaktif (enabled): flag `setting_modul.aktif` (toggle admin)
 *   - berhak (entitled): resolver {@see GerbangFitur} → {@see isEntitled()}
 *
 * Tahap ini menyediakan sisi-baca + entitlement; verb install/uninstall/
 * enable/disable menyusul saat controller di-shim ke service ini.
 */
class ModuleManager
{
    // Mekanik migrasi modul + penegakan min_core (jalankanMigrasiModule,
    // periksaMinCoreModul) — dipakai verb install/uninstall/migrate.
    use ModuleMigrations;

    /**
     * Kunci setting_aplikasi (JSON) berisi peta nama modul → slug menu induk.
     * Dipelihara saat install/uninstall agar {@see hiddenMenuSlugs()} bisa
     * mengenali modul yang folder-nya hilang (baris menu tertinggal) tanpa
     * kolom penanda di setting_modul (reuse tabel, nol perubahan schema).
     */
    private const MENU_MAP_KEY = 'modul_menu_map';

    /**
     * @param string|null   $modulesPath       Direktori dasar modul (berakhiran
     *                                         pemisah opsional). Bila null,
     *                                         di-resolve dari konfigurasi CI3
     *                                         `modules_locations` / `FCPATH` saat
     *                                         dibutuhkan — dapat diinjeksi pada uji.
     * @param (callable(): bool)|null $entitlementBypass Predikat opsional yang, bila
     *                                         `true`, melewati pemeriksaan gate
     *                                         (mis. lingkungan development/demo).
     *                                         Bila null, memakai default runtime
     *                                         core — dapat diinjeksi pada uji.
     * @param ModuleSource|null $source        Sumber berkas paket untuk
     *                                         {@see installFromSource()}. Bila null,
     *                                         diresolusi lewat container saat
     *                                         dibutuhkan — dapat diinjeksi pada uji.
     */
    public function __construct(
        private readonly GerbangFitur $gate,
        private readonly ?string $modulesPath = null,
        private $entitlementBypass = null,
        private readonly ?ModuleSource $source = null,
    ) {}

    /**
     * Direktori dasar tempat modul terpasang (dengan pemisah akhir).
     */
    public function modulesPath(): string
    {
        $path = $this->modulesPath;

        if ($path === null && function_exists('config_item')) {
            $path = array_keys((array) (config_item('modules_locations') ?: []))[0] ?? null;
        }

        if ($path === null) {
            $path = defined('FCPATH') ? FCPATH . 'Modules/' : 'Modules/';
        }

        return rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * Apakah modul terpasang di disk (folder + `module.json`)?
     */
    public function isInstalled(string $name): bool
    {
        return $name !== '' && is_file($this->manifestPath($name));
    }

    /**
     * Manifest `module.json` sebuah modul, atau `[]` bila tak ada/tak valid.
     *
     * @return array<string, mixed>
     */
    public function manifest(string $name): array
    {
        $path = $this->manifestPath($name);
        if (! is_file($path)) {
            return [];
        }

        $meta = json_decode((string) file_get_contents($path), true);

        return is_array($meta) ? $meta : [];
    }

    /**
     * Seluruh modul terpasang, dipetakan nama → manifest.
     *
     * @return array<string, array<string, mixed>>
     */
    public function installed(): array
    {
        $base    = $this->modulesPath();
        $modules = [];

        foreach (glob($base . '*', GLOB_ONLYDIR) ?: [] as $dir) {
            if (is_file($dir . DIRECTORY_SEPARATOR . 'module.json')) {
                $name           = basename($dir);
                $modules[$name] = $this->manifest($name);
            }
        }

        return $modules;
    }

    /**
     * Apakah modul mensyaratkan entitlement (langganan)?
     *
     * Dibaca dari `module.json` (`requires_entitlement`, default `false`).
     * Modul yang tak mendeklarasikannya diperlakukan sebagai modul terbuka
     * (gratis) — premium harus opt-in eksplisit.
     */
    public function requiresEntitlement(string $name): bool
    {
        return (bool) ($this->manifest($name)['requires_entitlement'] ?? false);
    }

    /**
     * Kunci fitur entitlement modul untuk {@see GerbangFitur}, atau `null`
     * bila modul tak butuh entitlement.
     *
     * Default kunci = manifest `entitlement`, jatuh ke slug modul (nama huruf
     * kecil) bila tak dieksplisitkan.
     */
    public function entitlementFeature(string $name): ?string
    {
        if (! $this->requiresEntitlement($name)) {
            return null;
        }

        $feature = $this->manifest($name)['entitlement'] ?? null;

        return $feature ? (string) $feature : strtolower($name);
    }

    /**
     * Apakah modul berhak digunakan (langganan aktif)?
     *
     * Modul tanpa syarat entitlement → selalu `true`. Selain itu: lingkungan
     * development/demo dilewati (lihat {@see bypassEntitlement()}), lalu
     * ditanyakan ke {@see GerbangFitur}; tanpa resolver add-on → `false`.
     */
    public function isEntitled(string $name): bool
    {
        $feature = $this->entitlementFeature($name);

        if ($feature === null) {
            return true;
        }

        return $this->bypassEntitlement() || $this->gate->mengizinkan($feature);
    }

    /**
     * Apakah pemeriksaan entitlement dilewati untuk lingkungan ini?
     *
     * Mempertahankan perilaku lama {@see \App\Traits\ModulTrait}: lewati di
     * `development`, atau saat `demo_mode` pada domain demo resmi. Di-guard agar
     * aman di konteks tanpa CI3 (mis. `php artisan`, uji unit).
     */
    private function bypassEntitlement(): bool
    {
        if ($this->entitlementBypass !== null) {
            return (bool) ($this->entitlementBypass)();
        }

        if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) === 'development') {
            return true;
        }

        if (! function_exists('config_item') || ! function_exists('get_domain')
            || ! defined('APP_URL') || ! defined('WEBSITE_DEMO')) {
            return false;
        }

        return (bool) config_item('demo_mode')
            && in_array(get_domain(constant('APP_URL')), (array) constant('WEBSITE_DEMO'));
    }

    /**
     * Apakah modul boleh dihapus lewat UI?
     *
     * Dibaca dari `module.json` (`removable`, default `true`). Modul bundled
     * menyetel `false` — menggantikan guard MODUL_BAWAAN (dihapus) yang hardcoded.
     */
    public function isRemovable(string $name): bool
    {
        return (bool) ($this->manifest($name)['removable'] ?? true);
    }

    /**
     * Nama modul terpasang yang TAK boleh dihapus lewat UI (`removable:false`).
     *
     * Menggantikan daftar MODUL_BAWAAN (dihapus) hardcoded pada UI paket terpasang.
     *
     * @return list<string>
     */
    public function nonRemovable(): array
    {
        $names = [];

        foreach (array_keys($this->installed()) as $name) {
            if (! $this->isRemovable($name)) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Apakah modul dikelola fitur Paket / bursa paket (add-on)?
     *
     * Dibaca dari `module.json` (`marketplace`, **default `true`**). Modul inti
     * OSS (Analisis/Kehadiran/Lapak) & infrastruktur (Pelanggan) MENYATAKAN
     * `marketplace:false` agar dikecualikan dari tab Paket Tersedia/Terpasang;
     * add-on (mis. Anjungan/BukuTamu/DTSEN) — termasuk yang dipasang dari repo
     * eksternal tanpa mendeklarasikan flag — dikelola secara default. Opt-out ini
     * membuat add-on tak "hilang" dari Paket Terpasang hanya karena manifes-nya
     * lupa menyetel flag.
     */
    public function isMarketplaceManaged(string $name): bool
    {
        return (bool) ($this->manifest($name)['marketplace'] ?? true);
    }

    /**
     * Nama modul TERPASANG yang dikelola marketplace (add-on) — sumber daftar
     * tab Paket Terpasang. Modul OSS/infrastruktur dikecualikan.
     *
     * @return list<string>
     */
    public function installedMarketplace(): array
    {
        return array_values(array_filter(
            array_keys($this->installed()),
            fn (string $name): bool => $this->isMarketplaceManaged($name)
        ));
    }

    /**
     * Slug menu induk (`setting_modul`) milik modul.
     *
     * Dibaca dari `module.json` (`menu_slug`); jatuh ke konvensi nama huruf
     * kecil bila tak dideklarasikan (mis. `Lapak`→`lapak`). Modul yang slug
     * menunya menyimpang dari nama (mis. `BukuTamu`→`buku-tamu`) WAJIB
     * mendeklarasikan `menu_slug`.
     */
    public function menuSlug(string $name): string
    {
        $slug = $this->manifest($name)['menu_slug'] ?? null;

        return $slug ? (string) $slug : strtolower($name);
    }

    /**
     * Aktifkan modul: nyalakan flag `aktif` pada menu induk + submenunya.
     */
    public function enable(string $name): void
    {
        $this->setMenuAktif($name, Modul::UNLOCK);
    }

    /**
     * Nonaktifkan modul: matikan flag `aktif` pada menu induk + submenunya.
     */
    public function disable(string $name): void
    {
        $this->setMenuAktif($name, Modul::LOCK);
    }

    /**
     * Apakah modul aktif (menu induknya menyala)?
     */
    public function isEnabled(string $name): bool
    {
        $parent = Modul::where('slug', $this->menuSlug($name))->first();

        return $parent !== null && ! $parent->isLock();
    }

    /**
     * Apakah modul benar-benar dapat dipakai: terpasang ∧ aktif ∧ berhak.
     */
    public function isUsable(string $name): bool
    {
        return $this->isInstalled($name) && $this->isEnabled($name) && $this->isEntitled($name);
    }

    /**
     * Peta nama modul → slug menu induk yang dipersist (lihat {@see MENU_MAP_KEY}).
     *
     * @return array<string, string>
     */
    public function menuMap(): array
    {
        $row = SettingAplikasi::where('key', self::MENU_MAP_KEY)->first();
        if ($row === null || empty($row->value)) {
            return [];
        }

        $map = json_decode((string) $row->value, true);

        return is_array($map) ? $map : [];
    }

    /**
     * Slug menu yang harus disembunyikan karena modul pemiliknya tak terpasang
     * lagi (folder hilang tapi baris menu tertinggal) — dari peta yang dipersist.
     *
     * @return list<string>
     */
    public function hiddenMenuSlugs(): array
    {
        return $this->hiddenMenuSlugsFor($this->menuMap());
    }

    /**
     * Logika murni penyembunyian: dari peta nama→slug, kembalikan slug milik
     * modul yang tak lagi terpasang di disk.
     *
     * @param array<string, string> $map
     *
     * @return list<string>
     */
    public function hiddenMenuSlugsFor(array $map): array
    {
        $hidden = [];

        foreach ($map as $name => $slug) {
            if (! $this->isInstalled((string) $name)) {
                $hidden[] = (string) $slug;
            }
        }

        return $hidden;
    }

    /**
     * Jalankan migrasi modul terpasang (tanpa penegakan min_core).
     *
     * @param string $direction `up` atau `down`
     */
    public function migrate(string $name, string $direction = 'up'): void
    {
        $this->jalankanMigrasiModule($name, $direction);
    }

    /**
     * Pasang modul dari sebuah {@see ModuleSource}: ambil ZIP, ekstrak (dengan
     * cadangan bila memperbarui modul lama), lalu {@see install()}.
     *
     * Ini alur pasang add-on yang sebenarnya (web & CLI): _dari mana_ paket
     * berasal ditentukan sumber terinjeksi/terikat — Layanan di produksi, repo
     * lokal saat pengembangan.
     *
     * @param string            $name           Nama modul (mis. `Anjungan`).
     * @param string            $locator        Petunjuk lokasi khusus-sumber (mis. URL Layanan).
     * @param ModuleSource|null $sourceOverride Sumber khusus untuk pemanggilan ini
     *                                          (mengabaikan sumber terikat) — dipakai
     *                                          panel pengembangan yang memilih sumber
     *                                          per-pasang.
     *
     * @return bool `true` bila ini instalasi pertama (bukan pembaruan).
     *
     * @throws RuntimeException|Throwable
     */
    public function installFromSource(string $name, string $locator = '', ?ModuleSource $sourceOverride = null): bool
    {
        $modulDir  = $this->modulesPath() . $name;
        $isBaru    = ! is_dir($modulDir);
        $backupDir = null;

        if (! $isBaru) {
            $backupDir = $modulDir . '_backup_' . time();
            if (! @rename($modulDir, $backupDir)) {
                throw new RuntimeException("Gagal membuat cadangan modul {$name} sebelum pembaruan.");
            }
        }

        $zipPath = ($sourceOverride ?? $this->source())->fetch($name, $locator);

        try {
            $this->extractPackage($name, $zipPath);
            $this->install($name);
        } catch (Throwable $e) {
            // Pulihkan cadangan bila pembaruan gagal (buang sisa ekstrak parsial).
            if ($backupDir !== null && is_dir($backupDir)) {
                File::deleteDirectory($modulDir);
                @rename($backupDir, $modulDir);
            }

            throw $e;
        } finally {
            if (is_file($zipPath)) {
                @unlink($zipPath);
            }
        }

        if ($backupDir !== null && is_dir($backupDir)) {
            File::deleteDirectory($backupDir);
        }

        return $isBaru;
    }

    /**
     * Pasang modul yang folder-nya SUDAH ada di disk: tegakkan min_core lalu
     * migrasi up. Implementasi tunggal dipakai `Plugin::pasang` (web) &
     * `Install_modul::pasang` (CLI).
     *
     * @throws RuntimeException bila core lebih lama dari `min_core` modul.
     */
    public function install(string $name): void
    {
        if ($pesan = $this->periksaMinCoreModul($this->modulesPath() . $name)) {
            throw new RuntimeException($pesan);
        }

        $this->migrate($name, 'up');
        $this->rememberMenuSlug($name);
    }

    /**
     * Copot modul: migrasi down; opsional hapus berkasnya dari disk.
     */
    public function uninstall(string $name, bool $removeFiles = false): void
    {
        $this->migrate($name, 'down');
        $this->forgetMenuSlug($name);

        if ($removeFiles && function_exists('forceRemoveDir')) {
            forceRemoveDir($this->modulesPath() . $name);
        }
    }

    /**
     * Laporkan instalasi modul ke server Layanan (telemetri; kegagalan tak fatal,
     * hanya dicatat di log). Implementasi tunggal untuk web & CLI.
     */
    public function reportInstall(string $name, string $version): void
    {
        try {
            $token    = token_bursa();
            $response = Http::withToken($token)->post(
                config('bursa.url_penyedia') . '/api/v1/modules/install',
                [
                    'module_name'   => $name,
                    'version'       => $version,
                    'domain'        => request()->getSchemeAndHttpHost(),
                    'tanggal_waktu' => date('Y-m-d H:i:s'),
                ]
            );

            log_message('notice', "reportInstall {$name}: " . $response->body());
        } catch (\Exception $e) {
            log_message('error', "reportInstall {$name}: " . $e->getMessage());
        }
    }

    /**
     * Sumber berkas paket aktif (terinjeksi atau diresolusi dari container).
     */
    private function source(): ModuleSource
    {
        return $this->source ?? app(ModuleSource::class);
    }

    /**
     * Ekstrak ZIP paket ke direktori modul, pindahkan folder puncaknya ke
     * tujuan bernama `$name`. Menolak entri path-traversal (Zip Slip).
     *
     * @throws RuntimeException
     */
    private function extractPackage(string $name, string $zipPath): void
    {
        $modulesDir = $this->modulesPath();
        $target     = $modulesDir . $name;

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException("Gagal membuka berkas ZIP paket {$name}.");
        }

        if (function_exists('validate_zip_entries') && ($entry = validate_zip_entries($zip)) !== true) {
            $zip->close();

            throw new RuntimeException("Paket {$name} mengandung path ilegal ({$entry}).");
        }

        $subfolder = rtrim((string) $zip->getNameIndex(0), '/');
        $source    = $modulesDir . $subfolder;
        $zip->extractTo($modulesDir);
        $zip->close();

        if ($subfolder === '' || ! is_dir($source)) {
            throw new RuntimeException("Ekstraksi paket {$name} gagal: direktori sumber tak ditemukan.");
        }

        if ($source === $target) {
            return;
        }

        if (is_dir($target)) {
            File::deleteDirectory($target);
        }

        if (! @rename($source, $target)) {
            throw new RuntimeException("Gagal memindahkan direktori paket {$name}.");
        }
    }

    /**
     * Set flag `aktif` menu induk modul (via slug) + submenunya.
     */
    private function setMenuAktif(string $name, int $aktif): void
    {
        $parent = Modul::where('slug', $this->menuSlug($name))->first();
        if ($parent === null) {
            return;
        }

        Modul::where('id', $parent->id)->update(['aktif' => $aktif]);
        Modul::where('parent', $parent->id)->update(['aktif' => $aktif]);

        cache()->flush();
    }

    /**
     * Catat slug menu modul ke peta yang dipersist (dipanggil saat install,
     * ketika folder + manifest masih tersedia untuk membaca `menu_slug`).
     */
    private function rememberMenuSlug(string $name): void
    {
        $map        = $this->menuMap();
        $map[$name] = $this->menuSlug($name);
        $this->saveMenuMap($map);
    }

    /**
     * Buang entri modul dari peta menu (dipanggil saat uninstall).
     */
    private function forgetMenuSlug(string $name): void
    {
        $map = $this->menuMap();
        if (array_key_exists($name, $map)) {
            unset($map[$name]);
            $this->saveMenuMap($map);
        }
    }

    /**
     * Simpan peta nama→slug ke setting_aplikasi (JSON), dibuat bila belum ada.
     *
     * @param array<string, string> $map
     */
    private function saveMenuMap(array $map): void
    {
        $value = json_encode($map);
        $row   = SettingAplikasi::where('key', self::MENU_MAP_KEY)->first();

        if ($row) {
            $row->update(['value' => $value]);
        } else {
            SettingAplikasi::create(['key' => self::MENU_MAP_KEY, 'value' => $value]);
        }

        (new SettingAplikasi())->flushQueryCache();
    }

    /**
     * Path absolut ke `module.json` sebuah modul.
     */
    private function manifestPath(string $name): string
    {
        return $this->modulesPath() . $name . DIRECTORY_SEPARATOR . 'module.json';
    }
}
