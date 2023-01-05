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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

$route['data-kelompok/(:any)'] = WEB . '/kelompok/detail/$1';
$route['data-lembaga/(:any)']  = WEB . '/lembaga/detail/$1';
$route['status-idm/(:num)']    = WEB . '/idm/index/$1';
$route['status-idm/(:num)']    = WEB . '/idm/index/$1';
$route['pemerintah']           = WEB . '/pemerintah';

// SDGS
$route['status-sdgs']    = WEB . '/sdgs/index';
$route['peta']           = WEB . '/peta/index';
$route['peraturan-desa'] = WEB . '/peraturan/index';

// Tampil Assets
$route['tampil/(:any)'] = 'dokumen_web/tampil/$1';
$route['unduh/(:any)']  = 'dokumen_web/unduh/$1';
// Buku Tamu
$route['buku-tamu/jawaban/(:num)/(:num)'] = WEB . '/buku_tamu/jawaban/$1/$2';
$route['buku-tamu/kepuasan/(:num)']       = WEB . '/buku_tamu/kepuasan/$1';
$route['buku-tamu/kepuasan']              = WEB . '/buku_tamu/kepuasan';
$route['buku-tamu/registrasi']            = WEB . '/buku_tamu/registrasi';
$route['buku-tamu']                       = WEB . '/buku_tamu/index';
