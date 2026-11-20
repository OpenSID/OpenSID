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

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ServeFileController;
use App\Http\Controllers\Sso\SsoLoginController;
use Illuminate\Support\Facades\Route;

// Captcha
Route::get('captcha', static fn () => (new App\Libraries\Captcha())->make())->name('captcha');

// Asset tema & modul (fallback default divalidasi ketat di controller)
Route::get('theme_asset/{theme}', [AssetController::class, 'serveTheme']);
Route::get('module_asset/{module}', [AssetController::class, 'serveModule']);

// File upload desa (signed URL) — untuk impor/arsip privat
Route::get('storage-desa', [ServeFileController::class, 'index'])
    ->name('storage.desa')
    ->middleware('signed');

// Fallback publik desa/upload|logo|pengaturan|anjungan saat symlink
// public/desa/* tidak tersedia (shared hosting tanpa FollowSymLinks).
// Web server tetap fast-path bila symlink ada (RewriteCond !-f di .htaccess).
Route::get('desa/{path}', [ServeFileController::class, 'fallback'])
    ->where('path', '(upload|logo|pengaturan|anjungan)/.*')
    ->name('desa.asset');

// SSO akses panel admin dari OpenKab (stateless, tanpa CSRF token pre-login)
Route::post('admin/sso-login', SsoLoginController::class)
    ->middleware('throttle:sso-login')
    ->name('sso.login');

Route::middleware('admin')
    ->group(base_path('routes/admin.php'));
