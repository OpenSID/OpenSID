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

namespace Tests\Unit\Issue11042;

use PHPUnit\Framework\TestCase;

/**
 * Helper functions untuk testing Issue 11042
 * Extract dari donjo-app/helpers/opensid_helper.php
 */

if (! function_exists('Tests\Unit\Issue11042\get_nik')) {
    /**
     * Fungsi helper untuk validasi NIK sementara
     * Dikutip dari donjo-app/helpers/opensid_helper.php
     *
     * @param string $nik NIK yang akan divalidasi
     * @return string Mengembalikan NIK jika tidak diawali 0, atau '0' jika diawali 0
     */
    function get_nik($nik = '0')
    {
        if (substr($nik, 0, 1) !== '0') {
            return $nik;
        }

        return '0';
    }
}

if (! function_exists('Tests\Unit\Issue11042\get_nokk')) {
    /**
     * Fungsi helper untuk validasi NOKK sementara (Alias dari get_nik)
     * Dikutip dari donjo-app/helpers/opensid_helper.php
     *
     * @param string $nokk NOKK yang akan divalidasi
     * @return string Mengembalikan NOKK jika tidak diawali 0, atau '0' jika diawali 0
     */
    function get_nokk($nokk = '0')
    {
        return get_nik($nokk);
    }
}
