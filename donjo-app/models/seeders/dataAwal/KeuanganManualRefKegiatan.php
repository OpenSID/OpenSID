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

defined('BASEPATH') || exit('No direct script access allowed');

class KeuanganManualRefKegiatan extends CI_Model
{
    public function getData()
    {
        return [
            [
                'id'            => 1,
                'ID_Keg'        => '01.01.01.',
                'Nama_Kegiatan' => 'Penyediaan Penghasilan Tetap dan Tunjangan Kepala Desa',
            ],
            [
                'id'            => 2,
                'ID_Keg'        => '01.01.02.',
                'Nama_Kegiatan' => 'Penyediaan Penghasilan Tetap dan Tunjangan Perangkat Desa',
            ],
            [
                'id'            => 3,
                'ID_Keg'        => '01.01.03.',
                'Nama_Kegiatan' => 'Penyediaan Jaminan Sosial bagi Kepala Desa dan Perangkat Desa',
            ],
            [
                'id'            => 4,
                'ID_Keg'        => '01.01.04.',
                'Nama_Kegiatan' => 'Penyediaan Operasional Pemerintah Desa (ATK, Honor PKPKD dan PPKD dll)',
            ],
            [
                'id'            => 5,
                'ID_Keg'        => '01.01.05.',
                'Nama_Kegiatan' => 'Penyediaan Tunjangan BPD',
            ],
            [
                'id'            => 6,
                'ID_Keg'        => '01.01.06.',
                'Nama_Kegiatan' => 'Penyediaan Operasional BPD (rapat, ATK, Makan Minum, Pakaian Seragam, Listrik dll)',
            ],
            [
                'id'            => 7,
                'ID_Keg'        => '01.01.07.',
                'Nama_Kegiatan' => 'Penyediaan Insentif\\/Operasional RT\\/RW',
            ],
            [
                'id'            => 8,
                'ID_Keg'        => '01.01.92',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Siltap dan Operasional Pemerintahan Desa',
            ],
            [
                'id'            => 9,
                'ID_Keg'        => '01.02.01.',
                'Nama_Kegiatan' => 'Penyediaan Sarana (Aset Tetap) Perkantoran\\/Pemerintahan',
            ],
            [
                'id'            => 10,
                'ID_Keg'        => '01.02.02.',
                'Nama_Kegiatan' => 'Pemeliharaan Gedung\\/Prasarana Kantor Desa',
            ],
            [
                'id'            => 11,
                'ID_Keg'        => '01.02.03.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Gedung\\/Prasarana Kantor Desa **)',
            ],
            [
                'id'            => 12,
                'ID_Keg'        => '01.02.90',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Sarana Prasarana Pemerintahan Desa',
            ],
            [
                'id'            => 13,
                'ID_Keg'        => '01.03.01.',
                'Nama_Kegiatan' => 'Pelayanan Administrasi Umum dan  Kependudukan',
            ],
            [
                'id'            => 14,
                'ID_Keg'        => '01.03.02.',
                'Nama_Kegiatan' => 'Penyusunan, Pendataan, dan Pemutakhiran Profil Desa **)',
            ],
            [
                'id'            => 15,
                'ID_Keg'        => '01.03.03.',
                'Nama_Kegiatan' => 'Pengelolaan Adminstrasi dan Kearsipan Pemerintahan Desa',
            ],
            [
                'id'            => 16,
                'ID_Keg'        => '01.03.04.',
                'Nama_Kegiatan' => 'Penyuluhan dan Penyadaran Masyarakat tentang Kependudukan dan Capil',
            ],
            [
                'id'            => 17,
                'ID_Keg'        => '01.03.05.',
                'Nama_Kegiatan' => 'Pemetaan dan Analisis Kemiskinan Desa secara Partisipatif',
            ],
            [
                'id'            => 18,
                'ID_Keg'        => '01.03.90',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Administrasi Kependudukan, Capil, Statistik dan Kearsipan',
            ],
            [
                'id'            => 19,
                'ID_Keg'        => '01.04.01.',
                'Nama_Kegiatan' => 'Penyelenggaraan Musyawarah Perencanaan Desa\\/Pembahasan APBDes (Reguler)',
            ],
            [
                'id'            => 20,
                'ID_Keg'        => '01.04.02.',
                'Nama_Kegiatan' => 'Penyelenggaraan Musyawaran Desa Lainnya (Musdus, rembug desa Non Reguler)',
            ],
            [
                'id'            => 21,
                'ID_Keg'        => '01.04.03.',
                'Nama_Kegiatan' => 'Penyusunan Dokumen Perencanaan Desa (RPJMDesa\\/RKPDesa dll)',
            ],
            [
                'id'            => 22,
                'ID_Keg'        => '01.04.04.',
                'Nama_Kegiatan' => 'Penyusunan Dokumen Keuangan Desa (APBDes, APBDes Perubahan, LPJ dll)',
            ],
            [
                'id'            => 23,
                'ID_Keg'        => '01.04.05.',
                'Nama_Kegiatan' => 'Pengelolaan Administrasi\\/ Inventarisasi\\/Penilaian Aset Desa',
            ],
            [
                'id'            => 24,
                'ID_Keg'        => '01.04.06.',
                'Nama_Kegiatan' => 'Penyusunan Kebijakan Desa (Perdes\\/Perkades selain Perencanaan\\/Keuangan)',
            ],
            [
                'id'            => 25,
                'ID_Keg'        => '01.04.07.',
                'Nama_Kegiatan' => 'Penyusunan Laporan Kepala Desa, LPPDesa dan Informasi Kepada Masyarakat',
            ],
            [
                'id'            => 26,
                'ID_Keg'        => '01.04.08.',
                'Nama_Kegiatan' => 'Pengembangan Sistem Informasi Desa',
            ],
            [
                'id'            => 27,
                'ID_Keg'        => '01.04.09.',
                'Nama_Kegiatan' => 'Koordinasi\\/Kerjasama Penyelenggaraan Pemerintahan & Pembangunan Desa',
            ],
            [
                'id'            => 28,
                'ID_Keg'        => '01.04.10.',
                'Nama_Kegiatan' => 'Dukungan & Sosialisasi Pelaksanaan Pilkades, Pemilihan Ka. Kewilayahan & BPD',
            ],
            [
                'id'            => 29,
                'ID_Keg'        => '01.04.11.',
                'Nama_Kegiatan' => 'Penyelenggaran Lomba antar Kewilayahan & Pengiriman Kontingen dlm Lomdes',
            ],
            [
                'id'            => 30,
                'ID_Keg'        => '01.04.97',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Tata Praja Pemerintahan, Perencanaan, Keuangan & Pelaporan',
            ],
            [
                'id'            => 31,
                'ID_Keg'        => '01.05.01.',
                'Nama_Kegiatan' => 'Sertifikasi Tanah Kas Desa',
            ],
            [
                'id'            => 32,
                'ID_Keg'        => '01.05.02.',
                'Nama_Kegiatan' => 'Administrasi Pertanahan (Pendaftaran Tanah dan Pemberian Registrasi Agenda Pertanahan)',
            ],
            [
                'id'            => 33,
                'ID_Keg'        => '01.05.03.',
                'Nama_Kegiatan' => 'Fasilitasi Sertifikasi Tanah untuk Masyarakat Miskin',
            ],
            [
                'id'            => 34,
                'ID_Keg'        => '01.05.04.',
                'Nama_Kegiatan' => 'Kegiatan Mediasi Konflik Pertanahan',
            ],
            [
                'id'            => 35,
                'ID_Keg'        => '01.05.05.',
                'Nama_Kegiatan' => 'Kegiatan Penyuluhan Pertanahan',
            ],
            [
                'id'            => 36,
                'ID_Keg'        => '01.05.06.',
                'Nama_Kegiatan' => 'Adminstrasi Pajak Bumi dan Bangunan (PBB)',
            ],
            [
                'id'            => 37,
                'ID_Keg'        => '01.05.07.',
                'Nama_Kegiatan' => 'Penentuan\\/Penegasan Batas\\/patok Tanah Kas Desa',
            ],
            [
                'id'            => 38,
                'ID_Keg'        => '01.05.94',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Pertanahan',
            ],
            [
                'id'            => 39,
                'ID_Keg'        => '02.01.01',
                'Nama_Kegiatan' => 'Penyelenggaran PAUD\\/TK\\/TPA\\/TKA\\/TPQ\\/Madrasah NonFormal Milik Desa (Honor, Pakaian dll)',
            ],
            [
                'id'            => 40,
                'ID_Keg'        => '02.01.02.',
                'Nama_Kegiatan' => 'Dukungan Penyelenggaran PAUD (APE, Sarana PAUD dst)',
            ],
            [
                'id'            => 41,
                'ID_Keg'        => '02.01.03.',
                'Nama_Kegiatan' => 'Penyuluhan dan Pelatihan Pendidikan Bagi Masyarakat',
            ],
            [
                'id'            => 42,
                'ID_Keg'        => '02.01.04.',
                'Nama_Kegiatan' => 'Pemeliharaan Sarana Prasarana Perpustakaan\\/Taman Bacaan\\/Sanggar Belajar Milik Desa',
            ],
            [
                'id'            => 43,
                'ID_Keg'        => '02.01.05.',
                'Nama_Kegiatan' => 'Pemeliharaan Sarana Prasarana PAUD\\/TK\\/TPA\\/TKA\\/TPQ\\/Madrasah Nonformal Milik Desa',
            ],
            [
                'id'            => 44,
                'ID_Keg'        => '02.01.08.',
                'Nama_Kegiatan' => 'Pengelolaan Perpustakaan Milik Desa (Pengadaan Buku, Honor, Taman Baca)',
            ],
            [
                'id'            => 45,
                'ID_Keg'        => '02.01.09.',
                'Nama_Kegiatan' => 'Pengembangan dan Pembinaan Sanggar Seni dan Belajar',
            ],
            [
                'id'            => 46,
                'ID_Keg'        => '02.01.10.',
                'Nama_Kegiatan' => 'Dukungan Pendidikan bagi Siswa Miskin\\/Berprestasi',
            ],
            [
                'id'            => 47,
                'ID_Keg'        => '02.01.92',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Pendidikan',
            ],
            [
                'id'            => 48,
                'ID_Keg'        => '02.02.01.',
                'Nama_Kegiatan' => 'Penyelenggaraan Pos Kesehatan Desa\\/Polindes Milik Desa (obat, Insentif, KB, dsb)',
            ],
            [
                'id'            => 49,
                'ID_Keg'        => '02.02.02.',
                'Nama_Kegiatan' => 'Penyelenggaraan Posyandu (Mkn Tambahan, Kls Bumil, Lamsia, Insentif)',
            ],
            [
                'id'            => 50,
                'ID_Keg'        => '02.02.03.',
                'Nama_Kegiatan' => 'Penyuluhan dan Pelatihan Bidang Kesehatan (Untuk Masy, Tenaga dan Kader Kesehatan dll)',
            ],
            [
                'id'            => 51,
                'ID_Keg'        => '02.02.04.',
                'Nama_Kegiatan' => 'Penyelenggaraan Desa Siaga Kesehatan',
            ],
            [
                'id'            => 52,
                'ID_Keg'        => '02.02.05.',
                'Nama_Kegiatan' => 'Pembinaan Palang Merah Remaja (PMR) Tingkat Desa',
            ],
            [
                'id'            => 53,
                'ID_Keg'        => '02.02.06.',
                'Nama_Kegiatan' => 'Pengasuhan Bersama atau Bina Keluarga Balita (BKB)',
            ],
            [
                'id'            => 54,
                'ID_Keg'        => '02.02.07.',
                'Nama_Kegiatan' => 'Pembinaan dan Pengawasan Upaya Kesehatan Tradisional',
            ],
            [
                'id'            => 55,
                'ID_Keg'        => '02.02.08.',
                'Nama_Kegiatan' => 'Pemeliharaan Sarana Prasarana Posyandu\\/Polindes\\/PKD',
            ],
            [
                'id'            => 56,
                'ID_Keg'        => '02.03.01.',
                'Nama_Kegiatan' => 'Pemeliharaan Jalan Desa',
            ],
            [
                'id'            => 57,
                'ID_Keg'        => '02.03.02.',
                'Nama_Kegiatan' => 'Pemeliharaan Jalan Lingkungan Pemukiman\\/Gang',
            ],
            [
                'id'            => 58,
                'ID_Keg'        => '02.03.03.',
                'Nama_Kegiatan' => 'Pemeliharaan Jalan Usaha Tani',
            ],
            [
                'id'            => 59,
                'ID_Keg'        => '02.03.04.',
                'Nama_Kegiatan' => 'Pemeliharaan Jembatan Desa',
            ],
            [
                'id'            => 60,
                'ID_Keg'        => '02.03.05.',
                'Nama_Kegiatan' => 'Pemeliharaan Prasarana Jalan Desa (Gorong-gorong\\/Selokan\\/Parit\\/Drainase dll)',
            ],
            [
                'id'            => 61,
                'ID_Keg'        => '02.03.06.',
                'Nama_Kegiatan' => 'Pemeliharaan Gedung\\/Prasarana Balai Desa\\/Balai Kemasyarakatan',
            ],
            [
                'id'            => 62,
                'ID_Keg'        => '02.03.07.',
                'Nama_Kegiatan' => 'Pemeliharaan Pemakaman \\/Situs Bersejarah\\/Petilasan Milik Desa',
            ],
            [
                'id'            => 63,
                'ID_Keg'        => '02.03.08.',
                'Nama_Kegiatan' => 'Pemeliharaan Embung Milik Desa',
            ],
            [
                'id'            => 64,
                'ID_Keg'        => '02.03.09.',
                'Nama_Kegiatan' => 'Pemelharaan Monumen\\/Gapura\\/Batas Desa',
            ],
            [
                'id'            => 65,
                'ID_Keg'        => '02.03.10.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitas\\/Peningkatan\\/Pengerasan Jalan Desa **)',
            ],
            [
                'id'            => 66,
                'ID_Keg'        => '02.03.12.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan\\/Pengerasan Jalan Usaha Tani **)',
            ],
            [
                'id'            => 67,
                'ID_Keg'        => '02.03.13.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan\\/Pengerasan Jembatan Milik Desa **)',
            ],
            [
                'id'            => 68,
                'ID_Keg'        => '02.03.14.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Prasarana Jalan Desa (Gorong, selokan dll)',
            ],
            [
                'id'            => 69,
                'ID_Keg'        => '02.03.15.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Balai Desa\\/Balai Kemasyarakatan **)',
            ],
            [
                'id'            => 70,
                'ID_Keg'        => '02.03.17.',
                'Nama_Kegiatan' => 'Pembuatan\\/Pemutakhiran Peta Wilayah dan Sosial Desa **)',
            ],
            [
                'id'            => 71,
                'ID_Keg'        => '02.03.18.',
                'Nama_Kegiatan' => 'Penyusunan Dokumen Perencanaan Tata Ruang Desa',
            ],
            [
                'id'            => 72,
                'ID_Keg'        => '02.03.19.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Embung Desa **)',
            ],
            [
                'id'            => 73,
                'ID_Keg'        => '02.03.20.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Monumen\\/Gapura\\/Batas Desa **)',
            ],
            [
                'id'            => 74,
                'ID_Keg'        => '02.04.01.',
                'Nama_Kegiatan' => 'Dukungan Pelaksanaan Program Pembangunan\\/Rehab Rumah Tidak Layak Huni GAKIN',
            ],
            [
                'id'            => 75,
                'ID_Keg'        => '02.04.90',
                'Nama_Kegiatan' => 'Dukungan Pelaksanaan Program Jambanisasi untuk Keluarga Miskin',
            ],
            [
                'id'            => 76,
                'ID_Keg'        => '02.04.02.',
                'Nama_Kegiatan' => 'Pemeliharaan Sumur Resapan Milik Desa',
            ],
            [
                'id'            => 77,
                'ID_Keg'        => '02.04.03.',
                'Nama_Kegiatan' => 'Pemeliharaan Sumber Air Bersih Milik Desa (Mata Air, Penampung Air, Sumur Bor dll)',
            ],
            [
                'id'            => 78,
                'ID_Keg'        => '02.04.04.',
                'Nama_Kegiatan' => 'Pemeliharaan Sambungan Air Bersih ke Rumah Tangga (Pipanisasi dll)',
            ],
            [
                'id'            => 79,
                'ID_Keg'        => '02.04.05.',
                'Nama_Kegiatan' => 'Pemeliharaan Sanitasi Pemukiman (Gorong-gorong, Selokan, Parit diluar Prasarana Jalan))',
            ],
            [
                'id'            => 80,
                'ID_Keg'        => '02.04.06.',
                'Nama_Kegiatan' => 'Pemeliharaan Fasilitas Jamban Umum\\/MCK Umum dll',
            ],
            [
                'id'            => 81,
                'ID_Keg'        => '02.04.08.',
                'Nama_Kegiatan' => 'Pemeliharaan Sistem Pembuangan Air Limbah (Drainase, Air limbah Rumah Tangga)',
            ],
            [
                'id'            => 82,
                'ID_Keg'        => '02.04.09.',
                'Nama_Kegiatan' => 'Pemeliharaan Taman\\/Taman Bermain Anak Milik Desa',
            ],
            [
                'id'            => 83,
                'ID_Keg'        => '02.04.10.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sumur Resapan **)',
            ],
            [
                'id'            => 84,
                'ID_Keg'        => '02.04.11.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sumber Air Bersih Milik Desa **)',
            ],
            [
                'id'            => 85,
                'ID_Keg'        => '02.04.12.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sambungan Air Bersih ke Rumah Tangga **)',
            ],
            [
                'id'            => 86,
                'ID_Keg'        => '02.04.13.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sanitasi Permukiman **)',
            ],
            [
                'id'            => 87,
                'ID_Keg'        => '02.04.14.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitas\\/Peningkatan Fasilitas Jamban Umum\\/MCK umum, dll **)',
            ],
            [
                'id'            => 88,
                'ID_Keg'        => '02.04.15.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Fasilitas Pengelolaan Sampah **)',
            ],
            [
                'id'            => 89,
                'ID_Keg'        => '02.04.16.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sistem Pembuangan Air Limbah **)',
            ],
            [
                'id'            => 90,
                'ID_Keg'        => '02.04.17.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Taman\\/Taman Bermain Anak Milik Desa **)',
            ],
            [
                'id'            => 91,
                'ID_Keg'        => '02.04.94',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Perumahan Rakyat dan Kawasan Pemukiman',
            ],
            [
                'id'            => 92,
                'ID_Keg'        => '02.05.01.',
                'Nama_Kegiatan' => 'Pengelolaan Hutan Milik Desa',
            ],
            [
                'id'            => 93,
                'ID_Keg'        => '02.05.02.',
                'Nama_Kegiatan' => 'Pengelolaan Lingkungan Hidup Milik Desa',
            ],
            [
                'id'            => 94,
                'ID_Keg'        => '02.05.92',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Kehutanan dan Lingkungan Hidup',
            ],
            [
                'id'            => 95,
                'ID_Keg'        => '02.06.01.',
                'Nama_Kegiatan' => 'Pembuatan Rambu-rambu di Jalan Desa',
            ],
            [
                'id'            => 96,
                'ID_Keg'        => '02.06.02.',
                'Nama_Kegiatan' => 'Penyelenggaraan Informasi Publik Desa (Poster, Baliho Dll)',
            ],
            [
                'id'            => 97,
                'ID_Keg'        => '02.06.03.',
                'Nama_Kegiatan' => 'Pembuatan dan Pengelolaan Jaringan\\/Instalasi Komunikasi dan Informasi Lokal Desa',
            ],
            [
                'id'            => 98,
                'ID_Keg'        => '02.06.92',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Perhubungan, Komunikasi dan Informatika',
            ],
            [
                'id'            => 99,
                'ID_Keg'        => '02.07.01.',
                'Nama_Kegiatan' => 'Pemeiliharaan Sarana dan Prasarana Energi Alternatif Desa',
            ],
            [
                'id'            => 100,
                'ID_Keg'        => '02.07.02.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sarana & Prasarana Energi Alternatif Desa',
            ],
            [
                'id'            => 101,
                'ID_Keg'        => '02.07.93',
                'Nama_Kegiatan' => 'Pembangunan\\/pengadaan instalasi biogas\\/mesin pakan ternak\\/kandang ternak**',
            ],
            [
                'id'            => 102,
                'ID_Keg'        => '02.08.01.',
                'Nama_Kegiatan' => 'Pemeliharaan Sarana dan Prasarana Pariwisata Milik Desa',
            ],
            [
                'id'            => 103,
                'ID_Keg'        => '02.08.02.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sarana dan Prasarana Pariwisata Milik **)',
            ],
            [
                'id'            => 104,
                'ID_Keg'        => '02.08.03.',
                'Nama_Kegiatan' => 'Pengembangan Pariwisata Tingkat Desa',
            ],
            [
                'id'            => 105,
                'ID_Keg'        => '02.08.91',
                'Nama_Kegiatan' => 'Lain-Lain Kegiatan Sub Bidang Pariwisata',
            ],
            [
                'id'            => 106,
                'ID_Keg'        => '03.01.01.',
                'Nama_Kegiatan' => 'Pengadaan\\/Penyelenggaran Pos Keamanan Desa',
            ],
            [
                'id'            => 107,
                'ID_Keg'        => '03.01.02.',
                'Nama_Kegiatan' => 'Penguatan & Peningkatan Kapasitas Tenaga Keamanan\\/Ketertiban oleh Pemdes',
            ],
            [
                'id'            => 108,
                'ID_Keg'        => '03.01.03.',
                'Nama_Kegiatan' => 'Koordinasi Pembinaan Keamanan, Ketertiban & Perlindungan Masy. Skala Lokal Desa',
            ],
            [
                'id'            => 109,
                'ID_Keg'        => '03.01.04.',
                'Nama_Kegiatan' => 'Persiapan Kesiapsiagaan\\/Tanggap Bencana Skala Lokal Desa',
            ],
            [
                'id'            => 110,
                'ID_Keg'        => '03.01.05.',
                'Nama_Kegiatan' => 'Penyediaan Pos Kesiapsiagaan Bencana Skala Lokal Desa',
            ],
            [
                'id'            => 111,
                'ID_Keg'        => '03.01.06.',
                'Nama_Kegiatan' => 'Bantuan Hukum Untuk Aparatur Desa dan Masyarakat Miskin',
            ],
            [
                'id'            => 112,
                'ID_Keg'        => '03.01.92',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Ketenteraman, Ketertiban Umum dan Perlindungan Masyarakat',
            ],
            [
                'id'            => 113,
                'ID_Keg'        => '03.02.01.',
                'Nama_Kegiatan' => 'Pembinaan Group Kesenian dan Kebudayaan Tingkat Desa',
            ],
            [
                'id'            => 114,
                'ID_Keg'        => '03.02.02.',
                'Nama_Kegiatan' => 'Pengiriman Kontingen Group Kesenian & Kebudayaan (Wakil Desa tkt. Kec\\/Kab\\/Kot)',
            ],
            [
                'id'            => 115,
                'ID_Keg'        => '03.02.03.',
                'Nama_Kegiatan' => 'Penyelenggaran Festival Kesenian, Adat\\/Kebudayaan, dan Kegamaan (HUT RI, Raya Keagamaan dll)',
            ],
            [
                'id'            => 116,
                'ID_Keg'        => '03.02.04.',
                'Nama_Kegiatan' => 'Pemeliharaan Sarana Prasarana Kebudayaan, Rumah Adat dan Kegamaan Milik Desa',
            ],
            [
                'id'            => 117,
                'ID_Keg'        => '03.02.05.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi Sarana Prasarana Kebudayaan\\/Rumah Adat\\/Kegamaan Milik Desa **)',
            ],
            [
                'id'            => 118,
                'ID_Keg'        => '03.02.95',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Kebudayaan dan Keagamaan',
            ],
            [
                'id'            => 119,
                'ID_Keg'        => '03.03.01.',
                'Nama_Kegiatan' => 'Pengiriman Kontingen Kepemudaan & Olahraga Sebagai Wakil Desa tkt Kec\\/Kab\\/Kota',
            ],
            [
                'id'            => 120,
                'ID_Keg'        => '03.03.02.',
                'Nama_Kegiatan' => 'Penyelenggaraan Pelatihan Kepemudaan Tingkat Desa',
            ],
            [
                'id'            => 121,
                'ID_Keg'        => '03.03.03.',
                'Nama_Kegiatan' => 'Penyelenggaraan Festival\\/Lomba Kepemudaan dan Olaraga Tingkat Desa',
            ],
            [
                'id'            => 122,
                'ID_Keg'        => '03.03.04.',
                'Nama_Kegiatan' => 'Pemeliharaan Sarana dan Prasarana Kepemudaan dan Olahraga Milik Desa',
            ],
            [
                'id'            => 123,
                'ID_Keg'        => '03.03.05.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Sarana dan Prasarana Kepemudaan & Olahraga Milik Desa',
            ],
            [
                'id'            => 124,
                'ID_Keg'        => '03.03.06.',
                'Nama_Kegiatan' => 'Pembinaan Karangtaruna\\/Klub Kepemudaan\\/Olahraga Tingkat Desa',
            ],
            [
                'id'            => 125,
                'ID_Keg'        => '03.03.90',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Kepemudaan dan Olahraga',
            ],
            [
                'id'            => 126,
                'ID_Keg'        => '03.04.01.',
                'Nama_Kegiatan' => 'Pembinaan Lembaga Adat',
            ],
            [
                'id'            => 127,
                'ID_Keg'        => '03.04.02.',
                'Nama_Kegiatan' => 'Pembinaan LKMD\\/LPM\\/LPMD',
            ],
            [
                'id'            => 128,
                'ID_Keg'        => '03.04.03.',
                'Nama_Kegiatan' => 'Pembinaan PKK',
            ],
            [
                'id'            => 129,
                'ID_Keg'        => '03.04.04.',
                'Nama_Kegiatan' => 'Pelatihan Pembinaan Lembaga Kemasyarakatan',
            ],
            [
                'id'            => 130,
                'ID_Keg'        => '03.04.92',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Kelembagaan Masyarakat',
            ],
            [
                'id'            => 131,
                'ID_Keg'        => '04.01.01.',
                'Nama_Kegiatan' => 'Pemeliharaan Karamba\\/Kolam Perikanan Darat Milik Desa',
            ],
            [
                'id'            => 132,
                'ID_Keg'        => '04.01.02.',
                'Nama_Kegiatan' => 'Pemeliharaan Pelabuhan Perikanan Sungai\\/Kecil Milik Desa',
            ],
            [
                'id'            => 133,
                'ID_Keg'        => '04.01.03.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Karamba\\/Kolam Perikanan Darat Milik Desa',
            ],
            [
                'id'            => 134,
                'ID_Keg'        => '04.01.04.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Pelabuhan Perikanan Sungai\\/Kecil Milik Desa',
            ],
            [
                'id'            => 135,
                'ID_Keg'        => '04.01.05.',
                'Nama_Kegiatan' => 'Bantuan Perikanan (Bibit\\/Pakan\\/dll)',
            ],
            [
                'id'            => 136,
                'ID_Keg'        => '04.01.06.',
                'Nama_Kegiatan' => 'Bimtek\\/Pelatihan\\/Pengenalan TTG untuk Perikanan Darat\\/Nelayan **)',
            ],
            [
                'id'            => 137,
                'ID_Keg'        => '04.01.94',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Kelautan dan Perikanan',
            ],
            [
                'id'            => 138,
                'ID_Keg'        => '04.02.01.',
                'Nama_Kegiatan' => 'Peningkatan Produksi Tanaman Pangan (alat produksi\\/pengelolaan\\/penggilingan)',
            ],
            [
                'id'            => 139,
                'ID_Keg'        => '04.02.02.',
                'Nama_Kegiatan' => 'Peningkatan Produksi Peternakan  (alat produksi\\/pengelolaan\\/kandang)',
            ],
            [
                'id'            => 140,
                'ID_Keg'        => '04.02.03.',
                'Nama_Kegiatan' => 'Penguatan Ketahanan Pangan Tingkat Desa (Lumbung Desa dll)',
            ],
            [
                'id'            => 141,
                'ID_Keg'        => '04.02.04.',
                'Nama_Kegiatan' => 'Pemeliharaan Saluran Irigasi Tersier\\/Sederhana',
            ],
            [
                'id'            => 142,
                'ID_Keg'        => '04.02.05.',
                'Nama_Kegiatan' => 'Pelatihan\\/Bimtek\\/Pengenalan Tekonologi Tepat Guna untuk Pertanian\\/Peternakan',
            ],
            [
                'id'            => 143,
                'ID_Keg'        => '04.02.06',
                'Nama_Kegiatan' => 'Pembangunan Saluran Irigasi Tersier\\/Sederhana',
            ],
            [
                'id'            => 144,
                'ID_Keg'        => '04.02.94',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Pertanian dan Peternakan',
            ],
            [
                'id'            => 145,
                'ID_Keg'        => '04.03.01.',
                'Nama_Kegiatan' => 'Peningkatan Kapasitas Kepala Desa',
            ],
            [
                'id'            => 146,
                'ID_Keg'        => '04.03.02.',
                'Nama_Kegiatan' => 'Peningkatan Kapatitas Perangkat Desa',
            ],
            [
                'id'            => 147,
                'ID_Keg'        => '04.03.03.',
                'Nama_Kegiatan' => 'Peningkatan Kapasitas BPD',
            ],
            [
                'id'            => 148,
                'ID_Keg'        => '04.03.90',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Peningkatan Kapasitas Aparatur Desa',
            ],
            [
                'id'            => 149,
                'ID_Keg'        => '04.04.01.',
                'Nama_Kegiatan' => 'Pelatihan dan Penyuluhan Pemberdayaan Perempuan',
            ],
            [
                'id'            => 150,
                'ID_Keg'        => '04.04.02.',
                'Nama_Kegiatan' => 'Pelatihan dan Penyuluhan Perlindungan Anak',
            ],
            [
                'id'            => 151,
                'ID_Keg'        => '04.04.03.',
                'Nama_Kegiatan' => 'Pelatihan dan Penguatan Penyandang Difable (Penyandang Disabilitas)',
            ],
            [
                'id'            => 152,
                'ID_Keg'        => '04.04.94',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga',
            ],
            [
                'id'            => 153,
                'ID_Keg'        => '04.05.01.',
                'Nama_Kegiatan' => 'Pelatihan Manajemen Koperasi\\/KUD\\/UMKM',
            ],
            [
                'id'            => 154,
                'ID_Keg'        => '04.05.02.',
                'Nama_Kegiatan' => 'Pengembangan Sarana Prasarana Usaha Mikro, Kecil, Menengah dan Koperasi',
            ],
            [
                'id'            => 155,
                'ID_Keg'        => '04.05.03.',
                'Nama_Kegiatan' => 'Pengadaan Teknologi Tepat Guna Untuk Pengembangan Ekonomi Pedesaan Non Pertanian',
            ],
            [
                'id'            => 156,
                'ID_Keg'        => '04.05.93',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Koperasi, Usaha Micro Kecil dan Menengah (UMKM)',
            ],
            [
                'id'            => 157,
                'ID_Keg'        => '04.06.01.',
                'Nama_Kegiatan' => 'Pembentukan BUM Desa (Persiapan dan Pembentukan Awal BUMDesa)',
            ],
            [
                'id'            => 158,
                'ID_Keg'        => '04.06.02.',
                'Nama_Kegiatan' => 'Pelatihan Pengelolaan BUM Desa (Pelatihan yg dilaksanakan oleh Pemdes)',
            ],
            [
                'id'            => 159,
                'ID_Keg'        => '04.06.92',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Dukungan Penanaman Modal',
            ],
            [
                'id'            => 160,
                'ID_Keg'        => '04.07.01.',
                'Nama_Kegiatan' => 'Pemeliharaan Pasar Desa\\/Kios Milik Desa',
            ],
            [
                'id'            => 161,
                'ID_Keg'        => '04.07.02.',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehab Pasar Desa\\/Kios Milik Desa',
            ],
            [
                'id'            => 162,
                'ID_Keg'        => '04.07.03.',
                'Nama_Kegiatan' => 'Pengembangan Industri Kecil Tingkat Desa',
            ],
            [
                'id'            => 163,
                'ID_Keg'        => '04.07.04.',
                'Nama_Kegiatan' => 'Pembentukan\\/Fasilitasi\\/Pelatihan\\/Pendampingan kelompok usaha ekonomi produktif',
            ],
            [
                'id'            => 164,
                'ID_Keg'        => '04.07.92',
                'Nama_Kegiatan' => 'Lain-lain Sub Bidang Perdagangan dan Perindustrian',
            ],
            [
                'id'            => 165,
                'ID_Keg'        => '05.01.01.',
                'Nama_Kegiatan' => 'Kegiatan Penanggulanan Bencana',
            ],
            [
                'id'            => 166,
                'ID_Keg'        => '05.02.01.',
                'Nama_Kegiatan' => 'Penanganan Keadaan Darurat',
            ],
            [
                'id'            => 167,
                'ID_Keg'        => '05.03.01.',
                'Nama_Kegiatan' => 'Penanganan Keadaan Mendesak',
            ],
            [
                'id'            => 168,
                'ID_Keg'        => '01.01.90',
                'Nama_Kegiatan' => 'Penyediaan Tali Asih Kepala Desa',
            ],
            [
                'id'            => 169,
                'ID_Keg'        => '01.01.91',
                'Nama_Kegiatan' => 'Penyediaan Tali Asih Perangkat Desa',
            ],
            [
                'id'            => 170,
                'ID_Keg'        => '01.04.90',
                'Nama_Kegiatan' => 'Penyusunan dan Penetapan Standar Satuan Harga Desa',
            ],
            [
                'id'            => 171,
                'ID_Keg'        => '01.04.91',
                'Nama_Kegiatan' => 'Pengisian\\/Penjaringan\\/Penyaringan Kepala Desa ',
            ],
            [
                'id'            => 172,
                'ID_Keg'        => '01.04.92',
                'Nama_Kegiatan' => 'Pengisian\\/Penjaringan\\/Penyaringan BPD**',
            ],
            [
                'id'            => 173,
                'ID_Keg'        => '01.04.93',
                'Nama_Kegiatan' => 'Penyelenggaraan Pemilihan Kepala Desa Antar Waktu',
            ],
            [
                'id'            => 174,
                'ID_Keg'        => '01.04.94',
                'Nama_Kegiatan' => 'Penyelenggaraan Pengisian Perangkat Desa  ',
            ],
            [
                'id'            => 175,
                'ID_Keg'        => '01.04.95',
                'Nama_Kegiatan' => 'Penyelenggaraan Evaluasi Tingkat Perkembangan Desa ',
            ],
            [
                'id'            => 176,
                'ID_Keg'        => '01.04.96',
                'Nama_Kegiatan' => 'Sosialisasi berbagai peraturan perundang-undangan di tingkat Desa',
            ],
            [
                'id'            => 177,
                'ID_Keg'        => '01.05.90',
                'Nama_Kegiatan' => 'Pengadaan\\/Pelepasan Tanah Kas Desa**',
            ],
            [
                'id'            => 178,
                'ID_Keg'        => '01.05.91',
                'Nama_Kegiatan' => 'Kompensasi\\/Ganti Rugi Lahan terdampak Pembangunan',
            ],
            [
                'id'            => 179,
                'ID_Keg'        => '01.05.92',
                'Nama_Kegiatan' => 'Penetapan dan penegasan batas Desa',
            ],
            [
                'id'            => 180,
                'ID_Keg'        => '01.05.93',
                'Nama_Kegiatan' => 'Penyusunan tata ruang Desa dan peta Desa',
            ],
            [
                'id'            => 181,
                'ID_Keg'        => '02.01.90',
                'Nama_Kegiatan' => 'Pengelolaan dan Pembinaan Anak Sekolah Melalui Pemberian Makanan Tambahan Anak Sekolah (PMTAS)',
            ],
            [
                'id'            => 182,
                'ID_Keg'        => '02.01.91',
                'Nama_Kegiatan' => 'Dukungan Sarana Prasana Pendidikan PAUD\\/TK\\/TPA\\/TKA\\/TPQ\\/Madrasah Non-Formal Bukan Milik Desa (dalam bentuk barang)',
            ],
            [
                'id'            => 183,
                'ID_Keg'        => '02.02.90',
                'Nama_Kegiatan' => 'Pengadaan\\/pembangunan\\/pengembangan\\/ pemeliharaan Sarana dan Prasarana Kesehatan\\/ Air Bersih \\/sanitasi\\/kebersihan lingkungan\\/jambanisasi\\/mandi, cuci, kakus (MCK) **',
            ],
            [
                'id'            => 184,
                'ID_Keg'        => '02.02.91',
                'Nama_Kegiatan' => 'Pemantauan dan Pencegahan Penyalahgunaan Narkoba dan Zat Adiktif Di Desa',
            ],
            [
                'id'            => 185,
                'ID_Keg'        => '02.02.92',
                'Nama_Kegiatan' => 'Fasilitasi Pelayanan Pencegahan dan Penanggulangan Penyakit Endemik, Menular dan Tidak Menular',
            ],
            [
                'id'            => 186,
                'ID_Keg'        => '02.02.93',
                'Nama_Kegiatan' => 'Pembangunan\\/Pengelolaan\\/Pemanfaatan Tanaman Obat Keluarga\\/Apotek Desa',
            ],
            [
                'id'            => 187,
                'ID_Keg'        => '02.02.94',
                'Nama_Kegiatan' => 'Fasilitasi Kegiatan Palang Merah Indonesia (PMI)',
            ],
            [
                'id'            => 188,
                'ID_Keg'        => '02.02.95',
                'Nama_Kegiatan' => 'Pengadaan\\/pembangunan\\/pengembangan\\/ pemeliharaan sarana prasarana alat bantu penyandang disabilitas\\/panti rehabilitasi penyandang disabilitas**',
            ],
            [
                'id'            => 189,
                'ID_Keg'        => '02.02.96',
                'Nama_Kegiatan' => 'Fasilitasi Pelayanan Kesehatan Bagi Masyarakat Miskin',
            ],
            [
                'id'            => 190,
                'ID_Keg'        => '02.02.97',
                'Nama_Kegiatan' => 'Penyelenggaraan Promosi Kesehatan dan Gerakan Hidup Bersih dan Sehat',
            ],
            [
                'id'            => 191,
                'ID_Keg'        => '02.02.98',
                'Nama_Kegiatan' => 'Pengadaan\\/pembangunan\\/pengembangan\\/pemeliharaan sarana prasarana mobil\\/kapal motor untuk ambulance Desa**',
            ],
            [
                'id'            => 192,
                'ID_Keg'        => '02.02.99',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Kesehatan',
            ],
            [
                'id'            => 193,
                'ID_Keg'        => '02.03.90',
                'Nama_Kegiatan' => 'Pembangunan\\/pemeliharaan jalan\\/talud pengaman tebing\\/saluran irigasi\\/energi baru dan terbarukan\\/ pembangkit listrik tenaga mikrohidro\\/lapangan Desa \\/ taman Desa\\/lingkungan permukiman masyarakat Desa**',
            ],
            [
                'id'            => 194,
                'ID_Keg'        => '02.03.91',
                'Nama_Kegiatan' => 'Pembangunan\\/Pengadaan\\/pengembangan\\/pemeliharaan sarana dan prasarana Jasa dan Industri Kecil\\/industri rumah tangga\\/mesin jahit\\/peralatan bengkel kendaraan bermotor\\/mesin bubut untuk mebeler; \\/pemasara',
            ],
            [
                'id'            => 195,
                'ID_Keg'        => '02.03.92',
                'Nama_Kegiatan' => 'Pembangunan kolam ikan dan pembenihan ikan\\/perahu penangkap ikan tempat pelelangan ikan\\/tempat pendaratan kapal penangkap ikan\\/cold storage (gudang pendingin)\\/gudang penyimpan sarana produksi (saprota',
            ],
            [
                'id'            => 196,
                'ID_Keg'        => '02.03.93',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Pemeliharaan\\/Peningkatan Sarana Prasarana Olah Raga\\/ Gedung Serba Guna',
            ],
            [
                'id'            => 197,
                'ID_Keg'        => '02.03.94',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Gedung\\/Prasarana Balai Desa\\/Balai Kemasyarakatan',
            ],
            [
                'id'            => 198,
                'ID_Keg'        => '02.03.95',
                'Nama_Kegiatan' => 'Pembangunan\\/pengembangan\\/pemeliharaan\\/pengelolaan sarana dan prasarana pasar Desa**',
            ],
            [
                'id'            => 199,
                'ID_Keg'        => '02.03.96',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Pekerjaan Umum dan Tata Ruang',
            ],
            [
                'id'            => 200,
                'ID_Keg'        => '02.04.91',
                'Nama_Kegiatan' => 'Penyediaan Kendaraan Pengangkut Sampah, Gerobak Sampah, Tong Sampah, Mesin Pengolah Sampah',
            ],
            [
                'id'            => 201,
                'ID_Keg'        => '02.04.92',
                'Nama_Kegiatan' => 'Pemeliharaan Jaringan Listrik\\/ Penerangan Desa',
            ],
            [
                'id'            => 202,
                'ID_Keg'        => '02.04.93',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Jaringan Listrik\\/ Penerangan Desa',
            ],
            [
                'id'            => 203,
                'ID_Keg'        => '02.05.90',
                'Nama_Kegiatan' => 'Pembuatan terasering\\/pembersihan daerah aliran sungai\\/plesengan sungai**',
            ],
            [
                'id'            => 204,
                'ID_Keg'        => '02.05.91',
                'Nama_Kegiatan' => 'Penanganan kebakaran hutan dan lahan\\/pencegahan abrasi pantai',
            ],
            [
                'id'            => 205,
                'ID_Keg'        => '02.06.90',
                'Nama_Kegiatan' => 'Pembangunan\\/Pengembangan\\/pemeliharaan sarana dan prasarana transportasi\\/informasi\\/ komunikasi\\/terminal Desa',
            ],
            [
                'id'            => 206,
                'ID_Keg'        => '02.06.91',
                'Nama_Kegiatan' => 'Pengadaan\\/pembangunan\\/pengembangan\\/pemeliharaan jaringan internet untuk warga Desa\\/website Desa\\/peralatan pengeras suara (loudspeaker)\\/telepon umum\\/ radio Single Side Band (SSB) ',
            ],
            [
                'id'            => 207,
                'ID_Keg'        => '02.07.90',
                'Nama_Kegiatan' => 'Pengembangan dan Pengelolaan Sarana dan Prasarana Energi Alternatif tingkat Desa',
            ],
            [
                'id'            => 208,
                'ID_Keg'        => '02.07.91',
                'Nama_Kegiatan' => 'Pembangunan sarana dan prasarana Teknologi Tepat Guna',
            ],
            [
                'id'            => 209,
                'ID_Keg'        => '02.07.92',
                'Nama_Kegiatan' => 'Pengadaan\\/pemanfaatan\\/pemeliharaan penggilingan padi\\/peraut kelapa\\/ penepung biji-bijian\\/pencacah pakan terna\\/sangrai\\/ pemotong\\/pengiris buah dan sayuran\\/pompa air\\/traktor mini**',
            ],
            [
                'id'            => 210,
                'ID_Keg'        => '02.07.94',
                'Nama_Kegiatan' => 'Lain-lain Kegiatan Sub Bidang Energi dan Sumber Daya Mineral',
            ],
            [
                'id'            => 211,
                'ID_Keg'        => '02.08.90',
                'Nama_Kegiatan' => 'Pengadaan\\/pembangunan\\/pengembangan\\/pemeliharaan sarana dan prasarana Desa wisata\\/ pondok wisata\\/panggung hiburan\\/ kios cenderamata\\/kios warung makan\\/wahana permainan anak\\/wahana permainan outbound\\/ ta',
            ],
            [
                'id'            => 212,
                'ID_Keg'        => '03.01.90',
                'Nama_Kegiatan' => 'Dukungan Pembinaan Keamanan, Ketertiban, dan Ketentraman Wilayah dan Masyarakat Desa (Seragam dan Op Linmas, BABINSA, BABINKAMTIBMAS)',
            ],
            [
                'id'            => 213,
                'ID_Keg'        => '03.01.91',
                'Nama_Kegiatan' => 'Pembentukan tim keamanan Desa',
            ],
            [
                'id'            => 214,
                'ID_Keg'        => '03.02.90',
                'Nama_Kegiatan' => 'Melestarikan Dan Mengembangkan Gotong Royong Masyarakat Desa (BBGRM)',
            ],
            [
                'id'            => 215,
                'ID_Keg'        => '03.02.91',
                'Nama_Kegiatan' => 'Dukungan Pengelolaan Sarana dan Prasarana Kebudayaan\\/Rumah Adat\\/Keagamaan di Desa **',
            ],
            [
                'id'            => 216,
                'ID_Keg'        => '03.02.92',
                'Nama_Kegiatan' => 'Penyelenggaraan Sedekah Bumi\\/Sedekah Laut\\/Apitan',
            ],
            [
                'id'            => 217,
                'ID_Keg'        => '03.02.93',
                'Nama_Kegiatan' => 'Partisipasi Perayaan Hari Besar Nasional lainnya',
            ],
            [
                'id'            => 218,
                'ID_Keg'        => '03.02.94',
                'Nama_Kegiatan' => 'Pembinaan kerukunan umat beragama',
            ],
            [
                'id'            => 219,
                'ID_Keg'        => '03.04.90',
                'Nama_Kegiatan' => 'Pembentukan\\/Pembinaan Lembaga Kemasyarakatan',
            ],
            [
                'id'            => 220,
                'ID_Keg'        => '03.04.91',
                'Nama_Kegiatan' => 'Pembentukan dan Dukungan Fasilitasi Kader Pembangunan dan Pemberdayaan Masyarakat',
            ],
            [
                'id'            => 221,
                'ID_Keg'        => '04.01.90',
                'Nama_Kegiatan' => 'Pelaksanaan Penanggulangan Hama dan Penyakit Secara Terpadu',
            ],
            [
                'id'            => 222,
                'ID_Keg'        => '04.01.91',
                'Nama_Kegiatan' => 'Pengelolaan Balai Benih Ikan Milik Desa',
            ],
            [
                'id'            => 223,
                'ID_Keg'        => '04.01.92',
                'Nama_Kegiatan' => 'Pemeliharaan Tambatan Perahu\\/Tempat Pelelangan Ikan (TPI) Milik Desa**',
            ],
            [
                'id'            => 224,
                'ID_Keg'        => '04.01.93',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Tambatan Perahu\\/Tempat Pelelangan Ikan (TPI) Milik Desa**',
            ],
            [
                'id'            => 225,
                'ID_Keg'        => '04.02.90',
                'Nama_Kegiatan' => 'Peningkatan Produksi Tanaman Perkebunan (Alat Produksi dan pengolahan perkebunan)',
            ],
            [
                'id'            => 226,
                'ID_Keg'        => '04.02.91',
                'Nama_Kegiatan' => 'Pembangunan\\/Rehabilitasi\\/Peningkatan Balai Pertemuan Kelompok Tani',
            ],
            [
                'id'            => 227,
                'ID_Keg'        => '04.02.92',
                'Nama_Kegiatan' => 'Pembangunan\\/Peningkatan Irigasi Tersier',
            ],
            [
                'id'            => 228,
                'ID_Keg'        => '04.02.93',
                'Nama_Kegiatan' => 'Pelatihan kelompok tani',
            ],
            [
                'id'            => 229,
                'ID_Keg'        => '04.04.90',
                'Nama_Kegiatan' => 'Fasiilitasi Penguatan Kelembagaan Pengarusutamaan Gender dan Anak',
            ],
            [
                'id'            => 230,
                'ID_Keg'        => '04.04.92',
                'Nama_Kegiatan' => 'Peningkatan Kapasitas Kelompok Pemerhati dan Perlindungan Anak',
            ],
            [
                'id'            => 231,
                'ID_Keg'        => '04.04.93',
                'Nama_Kegiatan' => 'Fasilitasi terhadap kelompok-kelompok rentan, kelompok masyarakat miskin, perempuan, anak dan difabel\\/ Pemberian bantuan sosial\\/pemberian santunan kepada keluarga fakir miskin\\/analisis kemiskinan seca',
            ],
            [
                'id'            => 232,
                'ID_Keg'        => '04.04.91',
                'Nama_Kegiatan' => 'Fasilitasi Upaya Perlindungan Perempuan dan Anak Terhadap Tindakan Kekerasan',
            ],
            [
                'id'            => 233,
                'ID_Keg'        => '04.05.90',
                'Nama_Kegiatan' => 'Pengembangan\\/ Promosi Produk Unggulan Desa',
            ],
            [
                'id'            => 234,
                'ID_Keg'        => '04.05.91',
                'Nama_Kegiatan' => 'Pembentukan dan pengembangan usaha ekonomi masyarakat dan\\/atau koperasi',
            ],
            [
                'id'            => 235,
                'ID_Keg'        => '04.05.92',
                'Nama_Kegiatan' => 'Bantuan sarana produksi, distribusi dan pemasaran untuk usaha ekonomi masyarakat**',
            ],
            [
                'id'            => 236,
                'ID_Keg'        => '04.06.90',
                'Nama_Kegiatan' => 'Pembangunan Kantor BUM Desa\\/Sarana Prasarana BUM Desa  (menjadi aset desa)**',
            ],
            [
                'id'            => 237,
                'ID_Keg'        => '04.06.91',
                'Nama_Kegiatan' => 'Pelaksanaan Audit Keuangan BUM Desa, Evaluasi Perkembangan BUM Desa ',
            ],
            [
                'id'            => 238,
                'ID_Keg'        => '04.07.90',
                'Nama_Kegiatan' => 'Pelatihan usaha ekonomi dan Perdagangan',
            ],
            [
                'id'            => 239,
                'ID_Keg'        => '04.07.91',
                'Nama_Kegiatan' => 'Sosialisasi Teknologi Tepat Guna\\/Posyantekdes dan\\/atau antar Desa\\/percontohan Teknologi Tepat Guna untuk produksi pertanian\\/pengembangan sumber energi perdesaan\\/pengemban',
            ],
        ];
    }
}
