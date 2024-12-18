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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Services\Pelanggan;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * VERSI
 *
 * Versi OpenSID
 */
define('VERSION', '2412.0.2');

/**
 * PREMIUM
 *
 * Versi OpenSID Premium
 */
define('PREMIUM', true);

/**
 * VERSI_DATABASE
 * Ubah setiap kali mengubah struktur database atau melakukan proses rilis (tgl 01)
 * Simpan nilai ini di tabel migrasi untuk menandakan sudah migrasi ke versi ini
 * Versi database = [yyyymmdd][nomor urut dua digit]
 * [nomor urut dua digit] : 01 => rilis umum, 51 => rilis bugfix, 71 => rilis premium,
 *
 * Varsi database jika premium = 2025061501, jika umum = 2024101651 (6 bulan setelah rilis premium, namun rilis beta)
 */
define('VERSI_DATABASE', PREMIUM ? '2024121851' : '2025071501');

/**
 * Minimum versi OpenSID yang bisa melakukan migrasi, backup dan restore database ke versi ini
 */
define('MINIMUM_VERSI', PREMIUM ? '2312' : '2407');

// Website Demo OpenSID
define('WEBSITE_DEMO', [
    'beta.opendesa.id',
    'beta2.opensid.or.id',
    'berputar.opendesa.id',
    'devpremium.opendesa.id',
    'opensid-premium.test',
    'opensid.test',
    'pelatihan-opensid.opendesa.id',
]);

// Modul bawaan OpenSID
define('MODUL_BAWAAN', [
    'Anjungan',
    'Analisis',
    'BukuTamu',
    'Kehadiran',
]);

if (! function_exists('cek_anjungan')) {
    /**
     * Cek status anjungan.
     */
    function cek_anjungan(): bool
    {
        // Lewati pengecekan jika web demo dan terdaftar sebagai pengecualian
        if (ENVIRONMENT === 'development' || (config_item('demo_mode') && (in_array(get_domain(APP_URL), WEBSITE_DEMO)))) {
            return true;
        }

        return cache()->rememberForever('license_anjugan', static function () {
            $status = Pelanggan::api_pelanggan_pemesanan();

            return $status->body->tanggal_berlangganan->anjungan == 'aktif';
        });
    }
}

if (! function_exists('module_asset')) {
    /**
     * Mengambil asset dari modul yang sedang aktif.
     *
     * @param mixed $uri
     *
     * @return string
     */
    function module_asset(string $uri)
    {
        $module = strtolower(app('ci')->router->fetch_module());

        return asset("modules/{$module}/{$uri}");
    }
}

if (! function_exists('storage_modules')) {
    /**
     * Mengambil file dari storage modul yang sedang aktif.
     *
     * @param mixed $uri
     *
     * @return string
     */
    function module_storage(string $uri)
    {
        return app('ci')->moduleDirectory . DIRECTORY_SEPARATOR . 'Storage' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $uri);
    }
}

if (! function_exists('module_path')) {
    /**
     * Mengambil path dari modul yang sedang aktif.
     *
     * @param mixed $name
     * @param mixed $path
     *
     * @return string
     */
    function module_path($name, $path)
    {
        $module = $name ? "Modules/{$name}" : app('ci')->moduleDirectory;

        return $module . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}

if (! function_exists('desa_storage')) {
    /**
     * Mengambil file dari storage desa.
     *
     * @param mixed $uri
     *
     * @return string
     */
    function desa_storage(string $uri)
    {
        return DESAPATH . str_replace('/', DIRECTORY_SEPARATOR, $uri);
    }
}
