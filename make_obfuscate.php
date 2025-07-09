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
    'donjo-app/third_party/pelanggan/libraries',
    'donjo-app/third_party/MX',
    'Modules',
];

$exceptDirectory = [
    'Providers',
    'migrations',
    'views',
    'Views',
    'DevelBar',
    'security',
];

$onlyFile = [
    // 'donjo-app/core/AdminModulController.php',
    // 'donjo-app/core/WebModulController.php',
    // 'donjo-app/core/ModulTrait.php',
    'donjo-app/helpers/core_helper.php',

    // 'Modules/Anjungan/Http/Controllers/BackEnd/AnjunganBaseController.php',
    // 'Modules/Anjungan/Http/Controllers/BackEnd/AnjunganController.php',
    // 'Modules/Anjungan/Http/Controllers/BackEnd/AnjunganMenuController.php',
    // 'Modules/Anjungan/Http/Controllers/BackEnd/AnjunganPengaturanController.php',
    
    
    // 'Modules/BukuTamu/Http/Controllers/BackEnd/AnjunganBaseController.php',
    // 'Modules/BukuTamu/Http/Controllers/BackEnd/KeperluanController.php',
    // 'Modules/BukuTamu/Http/Controllers/BackEnd/KepuasanController.php',
    // 'Modules/BukuTamu/Http/Controllers/BackEnd/PertanyaanController.php',
    // 'Modules/BukuTamu/Http/Controllers/BackEnd/TamuController.php',

    'Modules/Pelanggan/Http/Controllers/PelangganController.php',
    'Modules/Pelanggan/Http/Controllers/PendaftaranKerjasamaController.php',
    'Modules/Pelanggan/Services/PelangganService.php',
];

$exceptFile = [
    'general_helper.php',
    'Install.php',
    'ViewServiceProvider.php',
    'Router.php',
];

foreach ($onlyDirectory as $list) {
    cekFile($list, $exceptDirectory, $onlyFile, $exceptFile);
}

function cekFile($onlyDirectory, $exceptDirectory, $onlyFile, $exceptFile)
{
    if ($onlyDirectory) {
        foreach (glob($onlyDirectory . '/*') as $cek) {
            if (is_file($cek) && pathinfo($cek)['extension'] === 'php') {
                // Only File
                if ($onlyFile && ! (in_array($cek, $onlyFile) || preg_match('/' . implode('|', array_map('preg_quote', $onlyFile, array_fill(0, count($onlyFile), '/'))) . '/', basename($cek)))) {
                    continue;
                }
                
                // Except File
                if ($exceptFile && (in_array($cek, $exceptFile) || preg_match('/' . implode('|', array_map('preg_quote', $exceptFile, array_fill(0, count($exceptFile), '/'))) . '/', basename($cek)))) {
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