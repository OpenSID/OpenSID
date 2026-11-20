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
    // Try to predict the zip size up front and send a Content-Length header
    'predict_size' => env('ZIPSTREAM_PREDICT_SIZE', true),

    // Compression method used only if we don't (or can't) predict the zip size
    'compression_method' => env('ZIPSTREAM_COMPRESSION_METHOD', 'store'),

    // Remove all non-ascii characters from filenames
    'ascii_filenames' => env('ZIPSTREAM_ASCII_FILENAMES', true),

    // What to do when a file with the same zip path is added twice. Options are 'skip', 'replace', 'rename'
    'conflict_strategy' => env('ZIPSTREAM_CONFLICT_STRATEGY', 'skip'),

    // Don't allow 'Text.txt' and 'text.TXT' by default
    'case_insensitive_conflicts' => env('ZIPSTREAM_CASE_INSENSITIVE_CONFLICTS', true),

    // AWS configs for S3 files
    'aws'     => [
        'credentials'             => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY')
        ],
        'version'                 => 'latest',
        'endpoint'                => env('AWS_ENDPOINT'),
        'use_path_style_endpoint' => env('ZIPSTREAM_AWS_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false)),
        'region'                  => env('ZIPSTREAM_AWS_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))
    ],

    // https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials_anonymous.html
    'aws_anonymous_client' => env('AWS_ANONYMOUS', false)
];
