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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access.');

/*
|--------------------------------------------------------------------------
| Developer's Toolbar
|--------------------------------------------------------------------------
|
| This option allows you to enable the developer's Toolbar
|
*/
$config['enable_develbar'] = true;

/*
|--------------------------------------------------------------------------
| Check for update
|--------------------------------------------------------------------------
|
| This option allows you to check if there is any new version for CodeIgniter
| if this option is set to TRUE, it will slow down the page loading
|
*/
$config['check_update'] = true;

$config['profiler_key_expiration_time'] = 1800; // sec

$config['documentation_link'] = 'http://www.codeigniter.com/userguide3/';

$config['ci_website'] = 'http://www.codeigniter.com';

$config['ci_download_link'] = 'http://www.codeigniter.com/download';

$config['ci_update_uri'] = 'https://raw.githubusercontent.com/bcit-ci/CodeIgniter/develop/system/core/CodeIgniter.php';

$config['develbar_update_uri'] = 'https://raw.githubusercontent.com/JCSama/CodeIgniter-develbar/master/version.json';

$config['develbar_download_link'] = 'https://github.com/JCSama/CodeIgniter-develbar';

/*
|--------------------------------------------------------------------------
| Debug Sections
|--------------------------------------------------------------------------
|
| This option allows you to enable specific sections into the Developer's Toolbar
|
*/
$config['develbar_sections'] = [
    'Benchmarks'   => true,
    'Memory Usage' => true,
    'Request'      => true,
    'Database'     => true,
    'Hooks'        => true,
    'Ajax'         => true,
    'Libraries'    => true,
    'Helpers'      => true,
    'Views'        => true,
    'Config'       => true,
    'Session'      => true,
    'Models'       => true,
];
