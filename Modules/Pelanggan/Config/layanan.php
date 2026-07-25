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

/*
 | Pembaca env yang portabel lintas-core: memakai getenv() (bukan helper env()
 | Laravel) agar modul yang sama bisa dijatuhkan ke core tanpa .env/phpoption
 | (mis. rilis Umum). Fallback ke $_ENV/$_SERVER untuk mode Dotenv immutable.
 */
$bacaEnv = static function (string $kunci, $bawaan = null) {
    $nilai = getenv($kunci);

    if ($nilai === false) {
        $nilai = $_ENV[$kunci] ?? $_SERVER[$kunci] ?? null;
    }

    return $nilai === false || $nilai === null ? $bawaan : $nilai;
};

return [

    /*
    |--------------------------------------------------------------------------
    | Verifikasi Token Berlangganan
    |--------------------------------------------------------------------------
    |
    | Token berlangganan dari server layanan (OpenDesa) saat ini belum
    | ditandatangani (alg=none), sehingga verifikasi signature kriptografis
    | belum bisa diaktifkan dari sisi klien. Begitu server layanan menandatangani
    | token (RS256) dan public key-nya tersedia, isi JWT_PUBLIC_KEY & JWT_ALGO
    | pada .env agar Modules\Pelanggan\Services\TokenDecoder otomatis memverifikasi
    | signature alih-alih hanya memvalidasi struktur token.
    |
    */

    'jwt_public_key' => $bacaEnv('JWT_PUBLIC_KEY'),

    'jwt_algo' => $bacaEnv('JWT_ALGO', 'RS256'),

    /*
    |--------------------------------------------------------------------------
    | Tolak Token alg=none
    |--------------------------------------------------------------------------
    |
    | Saat verifikasi signature belum aktif, tetap tolak token dengan header
    | alg=none untuk menutup vektor forgery yang paling mudah dieksploitasi.
    |
    */

    'tolak_alg_none' => filter_var($bacaEnv('LAYANAN_TOLAK_ALG_NONE', true), FILTER_VALIDATE_BOOLEAN),

];
