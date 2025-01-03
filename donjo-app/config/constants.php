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
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  || define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') || define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   || define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  || define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                          || define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                    || define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')      || define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') || define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                  || define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')             || define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')           || define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')      || define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/**
 * https://stackoverflow.com/questions/11792268/how-to-set-proper-codeigniter-base-url
 * Define APP_URL Dynamically
 * Write this at the bottom of index.php
 *
 * Automatic base url
 */
define('APP_URL', (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || FORCE_HTTPS == true) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));

/**
 * Custom path folder
 */
define('WEB', 'fweb');
define('MANDIRI', 'fmandiri');
define('ADMIN', 'fadmin');
define('KEHADIRAN', 'kehadiran');

/**
 * Custom path directory
 */
define('DESAPATH', 'desa' . DIRECTORY_SEPARATOR);
define('DESAPATHPERMISSION', 0755);
define('RESOURCESPATH', 'resources' . DIRECTORY_SEPARATOR);
define('STORAGEPATH', 'storage' . DIRECTORY_SEPARATOR);
define('BACKUPPATH', 'backup_inkremental' . DIRECTORY_SEPARATOR);
define('VENDORPATH', 'vendor' . DIRECTORY_SEPARATOR);

/**
 * Folder dan file pada folder sistem.
 */
// Folder
define('LOKASI_ASSET_IMAGES', 'assets/css/images/');
define('LOKASI_ASSET_FRONT_IMAGES', 'assets/front/css/images/');
define('LOKASI_FILES_LOGO', 'assets/files/logo/');
define('LOKASI_SISIPAN_DOKUMEN', 'assets/files/sisipan/');
define('LOKASI_SIMBOL_LOKASI_DEF', 'assets/images/gis/point/');
define('PENDAPAT', 'assets/images/layanan_mandiri/');
define('LOKASI_ICON_MENU_ANJUNGAN_DEFAULT', 'assets/modules/anjungan/images/');
define('LOKASI_SURAT_SISTEM', 'template-surat/');
define('DEFAULT_LOKASI_LAMPIRAN_SURAT', 'storage/app/template/lampiran/');
define('DEFAULT_LOKASI_LAMPIRAN_SURAT_DINAS', 'storage/app/template/surat-dinas/lampiran/');
define('DEFAULT_LOKASI_EKSPOR', 'storage/app/template/ekspor/');
define('DEFAULT_LOKASI_IMPOR', 'storage/app/template/impor/');

// File
define('DEFAULT_LATAR_SITEMAN', 'assets/css/images/latar_login.jpg');
define('DEFAULT_LATAR_KEHADIRAN', 'assets/css/images/latar_login_mandiri.jpg');
define('DEFAULT_LATAR_WEBSITE', 'assets/front/css/images/latar_website.jpg');
define('GAMBAR_QRCODE', 'assets/images/opensid.png');
define('LOGO_GARUDA', 'assets/images/garuda.png');
define('LOGO_BSRE', 'assets/images/bsre.png');
define('STEMPEL', 'assets/images/layanan/stempel.png');
define('LAYANAN_LOGO', 'assets/images/layanan/logo.png');

/**
 * Folder dan file pada folder desa.
 * Untuk folder yang ada di difine perlu didaftarkan juga di config/installer.php agar dibuat otomatis jika tidak ditemukan.
 */
// Folder
define('LOKASI_LOGO_DESA', 'desa/logo/');
define('LOKASI_ARSIP', 'desa/arsip/');
define('LOKASI_CACHE', 'desa/cache/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('LOKASI_LAMPIRAN_SURAT_DESA', 'desa/template-surat/lampiran/');
define('LOKASI_LAMPIRAN_SURAT_DINAS_DESA', 'desa/template-surat/surat-dinas/lampiran/');
define('LOKASI_TEMA_DESA', 'desa/themes/');
define('LOKASI_UPLOAD', 'desa/upload/');
define('LOKASI_USER_PICT', 'desa/upload/user_pict/');
define('LOKASI_FOTO_KELOMPOK', 'desa/upload/kelompok/');
define('LOKASI_FOTO_LEMBAGA', 'desa/upload/lembaga/');
define('LOKASI_GALERI', 'desa/upload/galeri/');
define('LOKASI_FOTO_ARTIKEL', 'desa/upload/artikel/');
define('FOTO_TIDAK_TERSEDIA', 'images/404-image-not-found.jpg');
define('LOKASI_FOTO_BUKU_TAMU', 'desa/upload/buku_tamu/');
define('LOKASI_FOTO_LOKASI', 'desa/upload/gis/lokasi/');
define('LOKASI_FOTO_AREA', 'desa/upload/gis/area/');
define('LOKASI_FOTO_GARIS', 'desa/upload/gis/garis/');
define('LOKASI_DOKUMEN', 'desa/upload/dokumen/');
define('LOKASI_PENGESAHAN', 'desa/upload/pengesahan/');
define('LOKASI_WIDGET', 'desa/widgets/');
define('LOKASI_GAMBAR_WIDGET', 'desa/upload/widgets/');
define('LOKASI_KEUANGAN_ZIP', 'desa/upload/keuangan/');
define('LOKASI_MEDIA', 'desa/upload/media/');
define('LOKASI_SIMBOL_LOKASI', 'desa/upload/gis/lokasi/point/');
define('LOKASI_SINKRONISASI_ZIP', 'desa/upload/sinkronisasi/');
define('LOKASI_PRODUK', 'desa/upload/produk/');
define('LOKASI_PENGADUAN', 'desa/upload/pengaduan/');
define('LOKASI_VAKSIN', 'desa/upload/vaksin/');
define('LOKASI_PENDAFTARAN', 'desa/upload/pendaftaran');
define('LOKASI_ICON_MENU_ANJUNGAN', 'desa/anjungan/menu/');
define('LATAR_LOGIN', 'desa/pengaturan/siteman/images/');
define('LOKASI_FOTO_DTKS', 'desa/upload/dtks/');
define('LOKASI_FONT_DESA', 'desa/upload/fonts/');
define('LOKASI_ICON_SOSMED', 'desa/upload/sosmed/');
define('LOKASI_SINERGI_PROGRAM', 'desa/upload/widgets/');
define('CONFIG_THEMES', 'desa/upload/themes/');

// File
define('LATAR_SITEMAN', 'desa/pengaturan/siteman/images/latar_login.jpg');
define('LATAR_KEHADIRAN', 'desa/pengaturan/siteman/images/latar_login_mandiri.jpg');
define('FONT_SYSTEM_TINYMCE', ['Andale Mono', 'Arial', 'Arial Black', 'Bookman Old Style', 'Comic Sans MS', 'Courier New', 'Georgia', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Trebuchet MS', 'Verdana']);

// Pesan Notifikasi
define('SYARAT_SANDI', 'Harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus');

// Info Sistem
define('EKSTENSI_WAJIB', serialize([
    'curl',
    'fileinfo',
    'gd',
    'iconv',
    'json',
    'mbstring',
    'mysqli',
    'mysqlnd',
    'tidy',
    'zip',
    'exif',
]));
define('minPhpVersion', '8.1.0');
define('maxPhpVersion', '8.2.0');
define('minMySqlVersion', '5.6.0');
define('maxMySqlVersion', '8.0.0');
define('minMariaDBVersion', '10.3.0');
