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
     * Kategori notifikasi yang tersedia
     *
     * Format:
     * 'key' => [
     *     'slug'    => 'slug-kategori',
     *     'label'   => 'Nama Kategori',
     *     'icon'    => 'fa-icon-name',
     *     'color'   => '#hexcolor (opsional)',
     *     'route'   => 'route.name',
     *     'modul'   => 'nama-modul (untuk permission check)',
     *     'query'   => 'query_string (opsional)',
     * ]
     */
    'categories' => [
        'surat' => [
            'slug'  => 'surat',
            'label' => 'Permohonan Surat',
            'icon'  => 'fa-print',
            'color' => '#3498db',
            'route' => 'permohonan_surat_admin',
            'modul' => 'permohonan-surat',
        ],

        'permohonansurat' => [
            'slug'  => 'permohonansurat',
            'label' => 'Permohonan Surat Masuk',
            'icon'  => 'fa-bell-o',
            'color' => '#e74c3c',
            'route' => 'keluar.masuk',
            'modul' => 'arsip-layanan',
        ],

        'komentar' => [
            'slug'  => 'komentar',
            'label' => 'Komentar',
            'icon'  => 'fa-commenting-o',
            'color' => '#9b59b6',
            'route' => 'komentar',
            'modul' => 'komentar',
            'query' => 'status=' . App\Models\Komentar::UNREAD,
        ],

        'inbox' => [
            'slug'  => 'inbox',
            'label' => 'Pesan Masuk',
            'icon'  => 'fa-envelope-o',
            'color' => '#f39c12',
            'route' => 'mailbox',
            'modul' => 'kotak-pesan',
        ],

        'opendkpesan' => [
            'slug'  => 'opendkpesan',
            'label' => 'Komunikasi OpenDK',
            'icon'  => 'fa-university',
            'color' => '#1abc9c',
            'route' => 'opendk_pesan.clear',
            'modul' => 'pesan',
        ],

        'buku_tamu' => [
            'slug'  => 'buku_tamu',
            'label' => 'Buku Tamu',
            'icon'  => 'fa-book',
            'color' => '#27ae60',
            'route' => 'buku_tamu',
            'modul' => 'data-tamu',
            'query' => 'status=' . Modules\BukuTamu\Models\TamuModel::BARU,
        ],
    ],
];
