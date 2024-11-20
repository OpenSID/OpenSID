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

define('VERSION', '2411.0.2');

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
define('VERSI_DATABASE', PREMIUM ? '2024112051' : '2025061501');

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

if (! function_exists('cek_anjungan')) {
    /**
     * - Fungsi validasi anjungan.
     */
    function cek_anjungan(): bool
    {
        // Lewati pengecekan jika web demo dan terdaftar sebagai pengecualian
        if (config_item('demo_mode') && (in_array(get_domain(APP_URL), WEBSITE_DEMO))) {
            return true;
        }

        return cache()->rememberForever('license_anjugan', static function () {
            $status = Pelanggan::api_pelanggan_pemesanan();

            return $status->body->tanggal_berlangganan->anjungan == 'aktif';
        });
    }
}
