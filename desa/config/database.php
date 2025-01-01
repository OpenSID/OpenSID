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

//
// Letakkan username, password dan database sebetulnya di file ini.
// File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
// -------------------------------------------------------------------------

// Data Konfigurasi MySQL yang disesuaikan

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
// $db['default']['password'] = 'eyJpdiI6InhCWmhFWDc3K0NCR2h3SkdCc0FLTlE9PSIsInZhbHVlIjoiY0tEYkVlZXBkYlFhU0Jmbys4WjdzQT09IiwibWFjIjoiNDhiNTljN2VkYzJiZTFlMzVmY2Q2OTE0NWRkZTk3MGEzZjE2Y2NmYzJkM2Q3NGE0MWJkMjY3NWVjNDgwZjc0NyIsInRhZyI6IiJ9';
$db['default']['password'] = 'eyJpdiI6InhCWmhFWDc3K0NCR2h3SkdCc0FLTlE9PSIsInZhbHVlIjoiY0tEYkVlZXBkYlFhU0Jmbys4WjdzQT09IiwibWFjIjoiNDhiNTljN2VkYzJiZTFlMzVmY2Q2OTE0NWRkZTk3MGEzZjE2Y2NmYzJkM2Q3NGE0MWJkMjY3NWVjNDgwZjc0NyIsInRhZyI6IiJ9';
$db['default']['port']     = 3306;
// $db['default']['database'] = 'a1';
// $db['default']['database'] = 'premium';
// $db['default']['database'] = 'akas';
// $db['default']['database'] = 'database_baru';
// $db['default']['database'] = 'akas2';
// $db['default']['database'] = 'db_gabungan';
// $db['default']['database'] = 'coba';
// $db['default']['database'] = 'baru';
// $db['default']['database'] = 'peta';
// $db['default']['database'] = 'gabungan_desa';
// $db['default']['database'] = 'multi_desa';
// $db['default']['database'] = 'cek_error';
// $db['default']['database'] = '6441';
// $db['default']['database'] = 'desayosowilangon_sid';
// $db['default']['database'] = 'error_kematian';
// $db['default']['database'] = 'baru1';
// $db['default']['database'] = 'log';
// $db['default']['database'] = 'akas1';
// $db['default']['database'] = 'rarang';
// $db['default']['database'] = 'lagi_baru_baru';
// $db['default']['database'] = 'akas23';
// $db['default']['database'] = 'rejo';
// $db['default']['database'] = 'sarwono';
// $db['default']['database'] = 'pulungkeke';
$db['default']['database'] = 'umum22';
// $db['default']['database'] = 'akas';
// $db['default']['database'] = 'backupakas';
// $db['default']['database'] = 'baruakas';
// $db['default']['database'] = 'install_akas';
// $db['default']['database'] = 'restore_surat';
// $db['default']['database'] = 'jawalaut';
// $db['default']['database'] = 'cek_ariandi';
// $db['default']['database'] = 'install';
// $db['default']['database'] = 'rejoakton';
// $db['default']['database'] = 'adiw_sid';
// $db['default']['database'] = '2305';
// $db['default']['database'] = 'egine';
// $db['default']['database'] = 'baru_lagi';
// $db['default']['database'] = 'widget';
// $db['default']['database'] = 'test2308';

/*
| Untuk setting koneksi database 'Strict Mode'
| Sesuaikan dengan ketentuan hosting
*/
$db['default']['stricton'] = true;
// $db['default']['dbcollat'] = 'utf8_german2_ci';
