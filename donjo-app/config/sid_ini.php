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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Konfigurasi aplikasi di simpan di tabel setting_aplikasi dan dibaca di
| setting_aplikasi.php.
| File ini berisi setting khusus yang tidak disimpan di database.
| Untuk mengubah letakkan setting yang diinginkan di desa/config/config.php
|--------------------------------------------------------------------------
*/
// Ambil setting SID khusus
define('LOKASI_SID_INI', 'desa/config/');

/*
|--------------------------------------------------------------------------
| Ambil setting konfigurasi dari database
|--------------------------------------------------------------------------
*/
$config['useDatabaseConfig'] = true;

/*
    Uncomment baris berikut untuk menampilkan setting development
    di halaman setting aplikasi.
    Perlu di-setting di sini karena index.php dijalankan
    sesudah pembacaan konfigurasi dari database di setting_model.php
*/
// $config["environment"] = "development";

// Untuk situs yang digunakan untuk demo, seperti https://demosid.opendesa.id
$config['demo_mode'] = false;

// Data id penduduk dan pin layanan mandiri yang digunakan sebagai default akun demo
$config['demo_akun'] = [
    1 => '123456',
    2 => '234561',
    3 => '345612',
];

$config['demo_user'] = [
    'username' => 'admin',
    'password' => 'sid304',
];

/*
|--------------------------------------------------------------------------
| Tingkatan Penggunaaan Database
|--------------------------------------------------------------------------
| 1. Tingkat 1 : Tidak ada batasan
| 2. Tingkat 2 : Provinsi, 1 provinsi bisa banyak kabupaten, kecamatan, dan desa
| 3. Tingkat 3 : Kabupaten, 1 kabupaten bisa banyak kecamatan dan desa (Default)
| 4. Tingkat 4 : Kecamatan, 1 kecamatan bisa banyak desa
*/
$config['db_level'] = 1;

// Delay kirim pesan layanan mandiri web, dalam satuan detik
$config['rentang_kirim_pesan'] = 60;

// ==========================================================================

// Konfigurasi tambahan untuk aplikasi
$extra_app_config = FCPATH . LOKASI_SID_INI . 'config.php';
if (is_file($extra_app_config)) {
    require_once $extra_app_config;
} else {
    // Harus ada config. Config ini tidak dipakai.
    $config['ini'] = '';
}

/**
 * Hapus index.php dari url bila ditemukan .htaccess
 * Untuk menggunakan fitur ini, pastikan konfigurasi apache di server SID
 * mengizinkan penggunaan .htaccess
 */
if (file_exists(FCPATH . '.htaccess') && ENVIRONMENT != 'development') {
    $config['index_page'] = '';
}

// End of file sid_ini.php
// Location: ./application/config/sid_ini.php
