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

namespace Tests\Unit\SecurityUpload;

/**
 * Helper functions untuk testing Security Upload
 *
 * File ini berisi function helpers yang diperlukan untuk menjalankan
 * unit test di folder SecurityUpload. Helpers ini di-extract dari:
 * - donjo-app/helpers/opensid_helper.php
 *
 * Functions:
 * - get_extension()
 * - isPHP()
 */

if (! function_exists('Tests\Unit\SecurityUpload\get_extension')) {
    /**
     * Ekstraksi extension dari filename
     *
     * @param string $filename
     * @return string Extension dengan leading dot, e.g. '.png'
     */
    function get_extension($filename): string
    {
        $ext = explode('.', strtolower($filename));
        return '.' . end($ext);
    }
}

if (! function_exists('Tests\Unit\SecurityUpload\isPHP')) {
    /**
     * Deteksi apakah file berisi kode script PHP
     *
     * NOTE: Implementasi ini HARUS sinkron dengan donjo-app/helpers/opensid_helper.php::isPHP().
     * Disalin di sini karena helper asli berada di global namespace dan memiliki
     * dependency terhadap bootstrap CodeIgniter, sehingga sulit di-load langsung
     * di unit test PHPUnit yang berjalan terisolasi.
     *
     * Strategi keamanan:
     * - Pattern scan dijalankan pada SEMUA file (tidak ada early-return berdasarkan
     *   magic bytes) untuk mencegah polyglot bypass (file gambar valid yang
     *   menyisipkan kode PHP).
     * - Hanya 1 MB pertama yang dibaca (hemat memori).
     *
     * @param string $file     Path lengkap ke file yang akan diperiksa
     * @param string $filename Nama file (digunakan untuk ekstraksi extension)
     *
     * @return bool True jika file adalah PHP atau mengandung kode PHP, False sebaliknya
     */
    function isPHP($file, $filename): bool
    {
        $ext = get_extension($filename);
        if ($ext === '.php') {
            return true;
        }

        $handle = fopen($file, 'rb');
        if (!$handle) {
            return false;
        }

        // Polyglot files biasanya menyisipkan PHP setelah magic bytes (di awal)
        // EXIF data juga terletak di awal file, jadi 1MB sudah cukup
        $buffer = fread($handle, 1024 * 1024);
        fclose($handle);

        if (empty($buffer)) {
            return false;
        }

        // Jalankan pattern matching pada SEMUA file untuk deteksi polyglot
        // Tidak ada early-return berdasarkan tipe atau magic bytes

        // Deteksi PHP opening tags dengan variasi spacing/newline/parenthesis
        // Menangkap: <?php, <?= dengan atau tanpa spasi, <?=(variable)
        if (preg_match('/<\?php[\s\(\r\n]|<\?=[\s\$]/i', $buffer)) {
            return true;
        }

        // Deteksi short tag di akhir file
        if (preg_match('/<\?php$|<\?=$/i', $buffer)) {
            return true;
        }

        // Deteksi script tag dengan language=php attribute
        if (preg_match('/<script\s+language\s*=\s*["\']?php["\']?/i', $buffer)) {
            return true;
        }

        // Deteksi halt compiler (sering digunakan dalam polyglot/obfuscator)
        if (preg_match('/__halt_compiler\s*\(/i', $buffer)) {
            return true;
        }

        return false;
    }
}
