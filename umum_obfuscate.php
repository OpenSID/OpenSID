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

if (! file_exists('Obfuscator.php')) {
    file_put_contents('Obfuscator.php', file_get_contents('https://raw.githubusercontent.com/pH-7/Obfuscator-Class/master/src/Obfuscator.php'));
}

require 'Obfuscator.php';

$onlyDirectory = [
    'app',
    'donjo-app/core',
    'donjo-app/helpers',
    'donjo-app/controllers',
    'donjo-app/models',
];

$exceptDirectory = [
    'fmandiri',
];

$onlyFile = [
    'Anjungan.php',
    'AnjunganMenu.php',
    'Anjungan.php',
    'Anjungan_menu.php',
    'Anjungan_pengaturan.php',
    'core_helper.php',
];

$exceptFile = [];

foreach ($onlyDirectory as $list) {
    cekFile($list, $exceptDirectory, $onlyFile, $exceptFile);
}

function cekFile($onlyDirectory, $exceptDirectory, $onlyFile, $exceptFile)
{
    if ($onlyDirectory) {
        foreach (glob($onlyDirectory . '/*') as $cek) {
            if (is_file($cek) && pathinfo($cek)['extension'] === 'php') {
                // Only File
                if ($onlyFile && ! (in_array($cek, $onlyFile) || preg_match('/' . implode('|', $onlyFile) . '/', basename($cek)))) {
                    continue;
                }

                // Except File
                if ($exceptFile && (in_array($cek, $exceptFile) || preg_match('/' . implode('|', $exceptFile) . '/', basename($cek)))) {
                    continue;
                }

                if (file_exists($cek)) {
                    $sData           = file_get_contents($cek);
                    $sData           = str_replace(['<?php', '<?', '?>'], '', $sData); // We strip the open/close PHP tags
                    $sObfusationData = new Obfuscator($sData, $cek);
                    file_put_contents($cek, '<?php ' . "\r\n" . $sObfusationData);
                }
            } else {
                // Except Directory
                // contoh 1 : donjo-app/models/migrasi
                // contoh 2 : migrasi
                if ($exceptDirectory && (in_array($cek, $exceptDirectory) || preg_match('/' . implode('|', $exceptDirectory) . '/', basename($cek)))) {
                    continue;
                }

                cekFile($cek, $exceptDirectory, $onlyFile, $exceptFile);
            }
        }
    }
}
