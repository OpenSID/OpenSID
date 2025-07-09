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

defined('BASEPATH') || exit('No direct script access.');

$config = [

    'kecamatan' => [
        'kode'   => '730819',
        'desa'   => 22,
        'awalan' => 2000,
    ],

    'desa' => [
        'jumlah' => 3,
    ],

    'wilayah' => [
        'dusun' => [
            'min' => 1,
            'max' => 3,
        ],
        'rw' => [
            'min' => 2,
            'max' => 5,
        ],
        'rt' => [
            'min' => 3,
            'max' => 7,
        ],
    ],

    'penduduk' => [
        'min' => 1000,
        'max' => 2000,
    ],

    'keluarga' => [
        'anggota' => [
            'min' => 1,
            'max' => 5,
        ],
        'rentang_awal' => 2020,
    ],

    'bantuan' => [
        'program' => [
            'min' => 5,
            'max' => 10,
        ],
        'peserta' => [
            'min' => 20,
            'max' => 50,
        ],
        'sasaran' => [
            // penduduk
            1 => [
                'Bantuan Pangan',
                'Bantuan Tunai',
                'Jaminan Kesehatan Nasional (JKN)',
                'Program Vaksinasi Massal',
                'Bantuan Obat-obatan',
                'Bantuan Kesejahteraan Lansia',
                'Bantuan Perlindungan Sosial Anak',
                'Bantuan Pendidikan',
                'Bantuan Perumahan',
                'Bantuan Pengangguran',
                'Bantuan Keterampilan Kerja',
                'Bantuan Kesehatan Jiwa',
                'Bantuan Modal Usaha',
                'Bantuan Perawatan Lanjut Usia',
                'Bantuan Pelatihan Profesi',
                'Bantuan Kesehatan Ibu dan Anak',
                'Bantuan Keuangan untuk Pelajar',
                'Bantuan Rehabilitasi Fisik',
                'Bantuan Konseling Psikologis',
                'Bantuan Pelatihan Kewirausahaan',
                'Bantuan Pekerja Migran',
                'Bantuan Keamanan Pangan',
                'Bantuan Kebutuhan Disabilitas',
                'Bantuan Kesehatan Gigi dan Mulut',
                'Bantuan Program Hamil Sehat',
            ],

            // keluarga
            2 => [
                'Bantuan Pangan',
                'Bantuan Tunai',
                'Bantuan Pendidikan',
                'Program Pendidikan Gratis',
                'Bantuan Kesehatan Anak',
                'Bantuan Perlindungan Sosial Keluarga',
                'Bantuan Kesehatan Ibu Hamil',
                'Bantuan Kesehatan Balita',
                'Bantuan Kesehatan Lansia',
                'Bantuan Perumahan Keluarga',
                'Bantuan Perlindungan Sosial Disabilitas',
                'Bantuan Kebutuhan Bayi',
                'Bantuan Kebutuhan Anak',
                'Bantuan Pelatihan Parenting',
                'Bantuan Penyuluhan Keluarga',
                'Bantuan Konseling Keluarga',
                'Bantuan Layanan Psikososial',
                'Bantuan Rehabilitasi Rumah',
                'Bantuan Pendidikan Nonformal',
                'Bantuan Pengasuhan Anak',
                'Bantuan Penempatan Kerja',
                'Bantuan Dana Usaha Keluarga',
                'Bantuan Kualitas Air Rumah',
                'Bantuan Pengelolaan Sampah',
                'Bantuan Perbaikan Infrastruktur',
            ],

            // rtm
            3 => [
                'Subsidi Listrik',
                'Subsidi Harga Bahan Pokok',
                'Kredit Usaha',
                'Pendampingan Usaha',
                'Pelatihan Kewirausahaan',
                'Bantuan Energi Terbarukan',
                'Bantuan Infrastruktur',
                'Bantuan Air Bersih',
                'Bantuan Sanitasi',
                'Bantuan Akses Internet',
                'Bantuan Pertanian',
                'Bantuan Perikanan',
                'Bantuan Perkebunan',
                'Bantuan Industri Kecil Menengah',
                'Bantuan Pemulihan Ekonomi Lokal',
                'Bantuan Pengembangan Produk',
                'Bantuan Pemasaran Produk',
                'Bantuan Keamanan Lingkungan',
                'Bantuan Peningkatan Kualitas Produk',
                'Bantuan Sertifikasi Produk',
                'Bantuan Promosi Produk',
                'Bantuan Pengolahan Produk',
                'Bantuan Teknologi Produksi',
                'Bantuan Kelembagaan Kelompok',
                'Bantuan Pengelolaan Keuangan',
            ],

            // kelompok
            4 => [
                'Bantuan Benih Unggul',
                'Bantuan Pupuk Subsidi',
                'Bantuan Alat Pertanian',
                'Pelatihan Pertanian Organik',
                'Bantuan Irigasi',
                'Bantuan Pengendalian Hama',
                'Bantuan Pemasaran Produk Tani',
                'Bantuan Peningkatan Kapasitas Kelompok Tani',
                'Bantuan Keuangan untuk Usaha Tani',
                'Bantuan Penyuluhan Pertanian',
                'Bantuan Pembangunan Infrastruktur Pertanian',
                'Bantuan Pendampingan Teknis Kelompok Tani',
                'Bantuan Pengolahan Hasil Pertanian',
                'Bantuan Program Peningkatan Produktivitas Tani',
                'Bantuan Program Diversifikasi Pertanian',
                'Bantuan Pembiayaan Investasi Pertanian',
                'Bantuan Pengelolaan Sumber Daya Alam Pertanian',
                'Bantuan Pengembangan Agribisnis',
                'Bantuan Penyediaan Sarana Produksi',
                'Bantuan Konservasi Lahan Pertanian',
                'Bantuan Program Perbaikan Infrastruktur Irigasi',
                'Bantuan Program Penyediaan Alat Pertanian Modern',
                'Bantuan Pelatihan Keahlian Pertanian',
                'Bantuan Program Pasar Tani',
                'Bantuan Program Riset Pertanian',
            ],
        ],
    ],

    'kelompok' => [
        'master' => [
            'min'  => 1,
            'max'  => 3,
            'tipe' => [
                1 => [
                    'Kelompok Tani',
                    'Kelompok Pemuda',
                    'Kelompok Pedagang',
                    'Kelompok Seni dan Budaya',
                    'Kelompok Olahraga',
                    'Kelompok Usaha Kecil Menengah (UKM)',
                    'Kelompok Pendidikan Masyarakat',
                    'Kelompok Peternakan',
                    'Kelompok Nelayan',
                    'Kelompok Lingkungan Hidup',
                    'Kelompok Kesehatan',
                    'Kelompok Pemberdayaan Ekonomi',
                    'Kelompok Pariwisata Desa',
                    'Kelompok Pendidikan Anak',
                    'Kelompok Koperasi',
                    'Kelompok Kebudayaan Lokal',
                    'Kelompok Kerajinan Tangan',
                    'Kelompok Pertanian Organik',
                ],

                2 => [

                ],
            ],
        ],

        'kelompok' => [
            'min' => 2,
            'max' => 7,
        ],

        'anggota' => [
            'min' => 5,
            'max' => 15,
        ],
    ],
];
