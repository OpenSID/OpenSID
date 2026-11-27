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

return [

    /*
    |--------------------------------------------------------------------------
    | SSO Akses Panel Admin dari OpenKab
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk endpoint POST /admin/sso-login yang menerima token SSO
    | sekali-pakai dari OpenKab. Token diverifikasi dengan memanggil endpoint
    | verify-token milik OpenKab (OpenKab adalah pemegang private key RS256 dan
    | pencatat pemakaian token), diautentikasi memakai HMAC-SHA256 dengan
    | SSO_CALLBACK_SECRET.
    |
    */

    // Base URL instalasi OpenKab, contoh: https://opendesa.kabkota.go.id
    'openkab_base_url' => env('SSO_OPENKAB_BASE_URL'),

    // Secret bersama untuk otentikasi callback verify-token (wajib >= 32 byte)
    'callback_secret' => env('SSO_CALLBACK_SECRET'),

    // Daftar origin OpenKab yang diizinkan mengakses POST /admin/sso-login (dipisah koma)
    'allowed_origins' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('SSO_ALLOWED_ORIGINS', ''))
    ))),

    // Timeout (detik) saat memanggil endpoint verify-token OpenKab
    'http_timeout' => (int) env('SSO_HTTP_TIMEOUT', 5),
];
