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

defined('BASEPATH') || exit('No direct script access allowed');

// ------------------------------------------------------------------------

if (! function_exists('image_base64_encode')) {
    function image_base64_encode($image)
    {
        $type = pathinfo($image, PATHINFO_EXTENSION);
        $data = file_get_contents($image);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}

// ------------------------------------------------------------------------

if (! function_exists('check_ci_version')) {
    function check_ci_version($url)
    {
        if (! $ci_version = @file_get_contents($url)) {
            return false;
        }

        $ci_version = htmlentities($ci_version);

        preg_match("/CI_VERSION',\\s'(.*)'\\)/", $ci_version, $matches);

        if (count($matches) && version_compare($matches[1], CI_VERSION, '>')) {
            return $matches[1];
        }

        return false;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('check_develbar_version')) {
    function check_develbar_version($url)
    {
        if (! $develbar = @file_get_contents($url)) {
            return false;
        }

        $develbar = json_decode($develbar, true);

        if (version_compare($develbar['version'], DevelBar::VERSION, '>')) {
            return $develbar['version'];
        }

        return false;
    }
}
