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
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
| https://codeigniter.com/user_guide/general/hooks.html
|
*/

$moduleLocations = $CFG->item('modules_locations');
$hook            = getHooks(['modules_location' => $moduleLocations]);

// DEV-ONLY: saat mode bursa lokal aktif, alihkan base-URL Layanan ke emulator
// in-app SEBELUM controller dibangun (Admin_Controller menyegarkan langganan di
// __construct). Di-guard ENVIRONMENT + class_exists → no-op di rilis (kelas
// PengalihLayananLokal di-export-ignore).
$hook['pre_controller'][] = static function (): void {
    if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) === 'development'
        && class_exists(\App\Services\Layanan\PengalihLayananLokal::class)) {
        // Umum menyediakan helper Laravel (storage_path/base_path) lewat helper CI
        // `illuminate` yang baru di-autoload saat konstruksi controller — SESUDAH
        // pre_controller. Muat lebih dulu agar LocalMarketplace::storeDir() (dan
        // PengalihLayananLokal) tak fatal "undefined function storage_path()".
        // (Di Premium helper ini dari composer autoload → selalu tersedia.)
        if (! function_exists('storage_path') && is_file(APPPATH . 'helpers/illuminate_helper.php')) {
            require_once APPPATH . 'helpers/illuminate_helper.php';
        }
        \App\Services\Layanan\PengalihLayananLokal::terapkan();
    }
};

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP / Console requests from the environment.
|
*/

$app = require __DIR__ . '/../../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$app->run();
