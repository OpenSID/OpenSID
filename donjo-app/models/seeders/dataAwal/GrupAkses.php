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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class GrupAkses extends CI_Model
{
    public function getData()
    {
        return [
            [
                'grup'  => 'Satgas Covid-19',
                'slug'  => 'statistik',
                'akses' => 0,
            ],
            [
                'grup'  => 'Satgas Covid-19',
                'slug'  => 'statistik-kependudukan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Satgas Covid-19',
                'slug'  => 'kesehatan',
                'akses' => 0,
            ],
            [
                'grup'  => 'Satgas Covid-19',
                'slug'  => 'pendataan',
                'akses' => 7,
            ],
            [
                'grup'  => 'Satgas Covid-19',
                'slug'  => 'pemantauan',
                'akses' => 7,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'home',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kependudukan',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'statistik',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'layanan-surat',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'bantuan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pertanahan',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaturan-peta',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pemetaan',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'hubung-warga',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaturan',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'admin-web',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'layanan-mandiri',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'sekretariat',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'identitas-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pemerintah-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'wilayah-administratif',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'keluarga',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'rumah-tangga',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kelompok',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'data-suplemen',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'calon-pemilih',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'statistik-kependudukan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'laporan-bulanan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'laporan-kelompok-rentan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaturan-surat',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'cetak-surat',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'arsip-layanan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kirim-pesan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'daftar-kontak',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'modul',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'artikel',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'widget',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'menu',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'komentar',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'galeri',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'informasi-publik',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'media-sosial',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'slider',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kotak-pesan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pendaftar-layanan-mandiri',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'surat-masuk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'surat-keluar',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'inventaris',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'peta',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'klasifikasi-surat',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'teks-berjalan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kategori',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'log-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis-kategori',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis-indikator',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis-klasifikasi',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis-periode',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis-respon',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis-laporan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'analisis-statistik-jawaban',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'api-inventaris-asset',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'api-inventaris-gedung',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'api-inventaris-gedung-1',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'api-inventaris-jalan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'api-inventaris-kontruksi',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'api-inventaris-peralatan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'api-inventaris-tanah',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'inventaris-asset',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'inventaris-gedung',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'inventaris-jalan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'inventaris-kontruksi',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'inventaris-peralatan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'laporan-inventaris',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'plan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'point',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'garis',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'line',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'area',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'polygon',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kategori-kelompok',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'produk-hukum',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'informasi-publik-1',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'daftar-persyaratan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'permohonan-surat',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'status-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'info-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'keuangan',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'impor-data',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'laporan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengunjung',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kesehatan',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pendataan',
                'akses' => 7,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pemantauan',
                'akses' => 7,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'input-data',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'laporan-manual',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaturan-web',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'qr-code',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'daftar-persil',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-administrasi-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'administrasi-umum',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'administrasi-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'administrasi-pembangunan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-eskpedisi',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-lembaran-dan-berita-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'anjungan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaturan-layanan-mandiri',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-mutasi-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-rekapitulasi-jumlah-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-penduduk-sementara',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-ktp-dan-kk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'admin-web',
                'akses' => 0,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'artikel',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'widget',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'menu',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'komentar',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'galeri',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'media-sosial',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'slider',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'teks-berjalan',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'pengunjung',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'pengaturan-web',
                'akses' => 7,
            ],
            [
                'grup'  => 'Kontributor',
                'slug'  => 'admin-web',
                'akses' => 0,
            ],
            [
                'grup'  => 'Kontributor',
                'slug'  => 'artikel',
                'akses' => 3,
            ],
            [
                'grup'  => 'Kontributor',
                'slug'  => 'komentar',
                'akses' => 3,
            ],
            [
                'grup'  => 'Kontributor',
                'slug'  => 'galeri',
                'akses' => 3,
            ],
            [
                'grup'  => 'Kontributor',
                'slug'  => 'slider',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-tanah-kas-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'master-analisis',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaturan-analisis',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-tanah-di-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pendapat',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-inventaris-dan-kekayaan-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-rencana-kerja-pembangunan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'administrasi-pembangunan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'c-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'lapak',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'aplikasi',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengguna',
                'akses' => '1',
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'database',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'info-sistem',
                'akses' => 3,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'kategori',
                'akses' => 7,
            ],
            [
                'grup'  => 'Redaksi',
                'slug'  => 'lapak',
                'akses' => 7,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'laporan-apbdes',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'sinkronisasi',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'lembaga-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kategori-lembaga',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'bumindes-kegiatan-pembangunan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'laporan-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'bumindes-kader',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaduan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'bumindes-hasil-pembangunan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'vaksin',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pembangunan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'arsip-desa',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kehadiran',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'rekapitulasi',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'hari-libur',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'jam-kerja',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'kehadiran-pengaduan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'opendk',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pesan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'grup-kontak',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'stunting',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'daftar-anjungan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'anjungan-menu',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pengaturan-anjungan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'alasan-keluar',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'gawai-layanan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'satu-data',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'dtks',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'buku-tamu',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'data-tamu',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'data-kepuasan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'data-pertanyaan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'data-keperluan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'optimasi-gambar',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'layanan-pelanggan',
                'akses' => 3,
            ],
            [
                'grup'  => 'Operator',
                'slug'  => 'pendaftaran-kerjasama',
                'akses' => 3,
            ],
        ];
    }
}
