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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services\Layanan;

use App\Services\Module\LocalMarketplace;

/**
 * Pengalih base-URL Layanan → emulator lokal (PENGEMBANGAN).
 *
 * Saat mode bursa lokal aktif ({@see LocalMarketplace::aktif()}), kedua base-URL
 * yang dibaca seluruh pemanggil Layanan dialihkan ke emulator in-app
 * (`site_url('layanan-lokal')`):
 *   - `config_item('server_layanan')` (klien langganan Pelanggan)
 *   - `config('bursa.url_penyedia')`  (bursa modul & tema)
 *
 * Karena semua pemanggil (PHP Guzzle **dan** blade/JS browser) membaca kedua
 * config ini, satu pengalihan menutup keduanya TANPA mengubah kode modul yang
 * byte-identik. Dipanggil dari hook CI3 **`pre_controller`** — sebelum
 * `Admin_Controller::__construct` menyegarkan langganan — sehingga self-HTTP
 * modul menembak emulator, bukan Layanan nyata.
 *
 * DEV-ONLY & di-`export-ignore`: hook di `hooks.php` di-guard
 * `ENVIRONMENT==='development'` + `class_exists`, sehingga rilis tanpa berkas ini
 * berperilaku persis seperti semula (Layanan nyata).
 */
class PengalihLayananLokal
{
    /** Segmen rute emulator (lihat controller `Layanan_lokal` + `Routes/Web/dev.php`). */
    private const RUTE_EMULATOR = 'layanan-lokal';

    /**
     * URL Layanan NYATA sebelum dialihkan (untuk ditampilkan di UI dev, mis. label
     * radio "Layanan (server nyata)" pada tab Sumber — agar tak menampilkan URL
     * emulator akibat override request-scoped ini). Diisi saat {@see terapkan()}.
     */
    public static ?string $urlLayananAsli = null;

    /**
     * Alihkan base-URL Layanan ke emulator lokal bila mode bursa lokal aktif.
     * No-op bila mode Layanan (nyata) dipilih atau adapter dev absen.
     *
     * Aman dipanggil sejak `pre_controller`: memakai singleton `CI_Config`
     * langsung (bukan `get_instance()`/sesi yang belum ada di titik itu).
     */
    public static function terapkan(): void
    {
        if (! class_exists(LocalMarketplace::class) || ! LocalMarketplace::aktif()) {
            return;
        }

        // Singleton CI_Config (tersedia sejak pre_controller; get_instance() belum).
        $config = load_class('Config', 'core');

        // Simpan URL nyata sebelum override, untuk ditampilkan di UI dev.
        self::$urlLayananAsli = (string) ($config->item('server_layanan') ?: '');

        $base = $config->site_url(self::RUTE_EMULATOR);

        // CI3: dibaca klien langganan + blade (config_item('server_layanan')).
        $config->set_item('server_layanan', $base);

        // Laravel: dibaca bursa modul & tema (config('bursa.url_penyedia')).
        if (function_exists('config')) {
            config(['bursa.url_penyedia' => $base]);
        }
    }

    /**
     * URL Layanan nyata untuk ditampilkan di UI dev — nilai asli bila sudah
     * dialihkan (mode lokal aktif), selain itu config saat ini (belum di-override).
     */
    public static function urlLayananAsli(): string
    {
        if (self::$urlLayananAsli !== null) {
            return self::$urlLayananAsli;
        }

        return function_exists('config_item') ? (string) (config_item('server_layanan') ?? '') : '';
    }
}
