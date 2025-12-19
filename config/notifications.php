<?php

/*
 * Konfigurasi Kategori Notifikasi
 * ================================
 * 
 * File ini mendefinisikan semua kategori notifikasi yang tersedia di OpenSID.
 * Untuk menambah kategori baru, cukup tambahkan entri baru di array 'categories' di bawah.
 * 
 * Tidak perlu edit NotificationService atau Admin_Controller!
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
            'slug'    => 'surat',
            'label'   => 'Permohonan Surat',
            'icon'    => 'fa-print',
            'color'   => '#3498db',
            'route'   => 'permohonan_surat_admin',
            'modul'   => 'permohonan-surat',
        ],

        'permohonansurat' => [
            'slug'    => 'permohonansurat',
            'label'   => 'Permohonan Surat Masuk',
            'icon'    => 'fa-bell-o',
            'color'   => '#e74c3c',
            'route'   => 'keluar.masuk',
            'modul'   => 'arsip-layanan',
        ],

        'komentar' => [
            'slug'    => 'komentar',
            'label'   => 'Komentar',
            'icon'    => 'fa-commenting-o',
            'color'   => '#9b59b6',
            'route'   => 'komentar',
            'modul'   => 'komentar',
            'query'   => 'status=' . \App\Models\Komentar::UNREAD,
        ],

        'inbox' => [
            'slug'    => 'inbox',
            'label'   => 'Pesan Masuk',
            'icon'    => 'fa-envelope-o',
            'color'   => '#f39c12',
            'route'   => 'mailbox',
            'modul'   => 'kotak-pesan',
        ],

        'opendkpesan' => [
            'slug'    => 'opendkpesan',
            'label'   => 'Komunikasi OpenDK',
            'icon'    => 'fa-university',
            'color'   => '#1abc9c',
            'route'   => 'opendk_pesan.clear',
            'modul'   => 'pesan',
        ],

        'buku_tamu' => [
            'slug'    => 'buku_tamu',
            'label'   => 'Buku Tamu',
            'icon'    => 'fa-book',
            'color'   => '#27ae60',
            'route'   => 'buku_tamu',
            'modul'   => 'data-tamu',
            'query'   => 'status=' . \Modules\BukuTamu\Models\TamuModel::BARU,
        ],
    ],
];
