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

use App\Models\Config;
use Modules\Pelanggan\Services\PelangganService;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * PREMIUM
 *
 * Versi OpenSID Premium
 */
define('PREMIUM', true);

/**
 * Minimum versi OpenSID yang bisa melakukan migrasi, backup dan restore database ke versi ini
 */
define('MINIMUM_VERSI', PREMIUM ? '2512' : '2612');

// Website Demo OpenSID
define('WEBSITE_DEMO', [
    'beta.opendesa.id',
    'beta2.opensid.or.id',
    'berputar.opendesa.id',
    'devpremium.opendesa.id',
    'opensid-premium.test',
    'opensid.test',
    'pelatihan-opensid.opendesa.id',
    'localhost',
    '127.0.0.1',
]);

// Modul bawaan OpenSID
define('MODUL_BAWAAN', [
    'Anjungan',
    'Analisis',
    'BukuTamu',
    'Kehadiran',
    'Pelanggan',
    'Lapak',
]);

if (! function_exists('cek_anjungan')) {
    /**
     * Cek status anjungan.
     */
    function cek_anjungan(): bool
    {
        if (ENVIRONMENT === 'development' || (config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO))) {
            return true;
        }

        if (cache()->has('anjungan_aktif')) {
            return cache('anjungan_aktif');
        }

        $status = PelangganService::apiPelangganPemesanan();

        $isAktif = isset($status->body->tanggal_berlangganan->anjungan) && $status->body->tanggal_berlangganan->anjungan === 'aktif';

        if ($isAktif) {
            cache()->forever('anjungan_aktif', true);
        }

        return $isAktif;
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
        return DESAPATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $uri);
    }
}

function set_app_key(): string
{
    return 'base64:' . base64_encode(random_bytes(32));
}

function get_app_key(): string
{
    $app_key = file_get_contents(DESAPATH . 'app_key');

    if ($app_key === '' || $app_key === false) {
        $app_key = set_app_key();
        file_put_contents(DESAPATH . 'app_key', $app_key);
    }

    return trim($app_key);
}

if (! function_exists('identitas')) {
    /**
     * Get identitas desa.
     *
     * @return object|string
     */
    function identitas(?string $params = null)
    {
        $identitas = cache()->remember('identitas_desa', 604800, static fn () => Config::appKey()->first());

        if ($params) {
            return $identitas->{$params};
        }

        return $identitas;
    }
}

if (! function_exists('isSiapPakai')) {
    /**
     * Cek apakah digunakan untuk desa siap pakai.
     * 1. Jika siappakai true, maka tampilkan pesan error.
     * 2. Jika pengguna biasa, maka lanjutkan ke halaman yang dituju.
     *
     * @return void
     */
    function isSiapPakai()
    {
        if (cache('siappakai')) {
            $pesan = 'Anda tidak memiliki akses untuk halaman tersebut!';
            set_session('error', $pesan);
            session_error($pesan);

            redirect(ci()->controller);
        }
    }
}
