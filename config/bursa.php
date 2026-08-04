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
 | Laravel) agar berkas config yang sama bisa dimuat di core tanpa .env/phpoption
 | (mis. rilis Umum yang tak membundel phpoption). Fallback ke $_ENV/$_SERVER
 | untuk mode Dotenv immutable (Premium).
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
    | URL penyedia bursa add-on
    |--------------------------------------------------------------------------
    |
    | Basis URL penyedia katalog/instalasi add-on (bursa modul & tema). Core
    | OSS memakai nilai netral yang DAPAT DI-OVERRIDE ini alih-alih meng-hardcode
    | vendor tertentu, sehingga rilis pihak ketiga bisa mengarahkan bursa ke
    | penyedianya sendiri lewat env `BURSA_URL_PENYEDIA`. Default menunjuk ke
    | penyedia OpenDesa hanya sebagai nilai bawaan.
    |
    */

    'url_penyedia' => $bacaEnv('BURSA_URL_PENYEDIA', 'https://layanan.opendesa.id'),

    /*
    |--------------------------------------------------------------------------
    | Path endpoint bootstrap token
    |--------------------------------------------------------------------------
    |
    | Path relatif dari url_penyedia yang mengembalikan daftar modul yang harus
    | dipasang otomatis saat token Layanan pertama kali disimpan. Override via
    | env `BURSA_BOOTSTRAP_PATH` bila penyedia pihak ketiga memakai path berbeda.
    |
    */

    'bootstrap_path' => $bacaEnv('BURSA_BOOTSTRAP_PATH', '/api/v1/token/bootstrap'),
];
