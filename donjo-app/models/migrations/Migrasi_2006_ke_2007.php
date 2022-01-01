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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2006_ke_2007 extends CI_model
{
    public function up()
    {
        // Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
        $this->db->query('ALTER TABLE area MODIFY COLUMN id_cluster INT(11) NULL DEFAULT NULL');
        $this->db->query('ALTER TABLE area MODIFY COLUMN foto VARCHAR(100) NULL DEFAULT NULL');
        $this->db->query('ALTER TABLE area MODIFY COLUMN path TEXT NULL');
        $this->data_apbdes_manual();
        $this->konfigurasi_web();
        $this->konfigurasi_qrcode();
    }

    private function data_apbdes_manual()
    {

        // Update Menu Keuangan - perbaikan urutan sub menu
        $this->db->where('id', 202)
            ->set('urut', '1')
            ->update('setting_modul');

        // Update Menu Keuangan - perbaikan urutan sub menu
        $this->db->where('id', 203)
            ->set('urut', '2')
            ->update('setting_modul');

        // Update Menu Keuangan - Tambah menu Input Apbdes Manual
        $query = "
			INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `parent`, `hidden`, `ikon_kecil`) VALUES
			('209', 'Input Data', 'keuangan_manual/manual_apbdes', '1', 'fa-keyboard-o', '3', '2', '201', '0', 'fa-keyboard-o'),
			('210', 'Laporan Manual', 'keuangan_manual/laporan_manual', '1', 'fa-bar-chart', '4', '2', '201', '0', 'fa-bar-chart')
			ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), level = VALUES(level), parent = VALUES(parent), hidden = VALUES(hidden);
		";
        $this->db->query($query);

        //insert keuangan_manual_rinci
        if (! $this->db->table_exists('keuangan_manual_rinci')) {
            $query = '
			CREATE TABLE IF NOT EXISTS `keuangan_manual_rinci` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Akun` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Nilai_Anggaran` varchar(100) NOT NULL,
				`Nilai_Realisasi` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)';
            $this->db->query($query);
        }

        //insert keuangan_manual_ref_rek1
        if (! $this->db->table_exists('keuangan_manual_ref_rek1')) {
            $query = '
			CREATE TABLE IF NOT EXISTS `keuangan_manual_ref_rek1` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`Akun` varchar(100) NOT NULL,
				`Nama_Akun` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)';
            $this->db->query($query);
        }

        //insert keuangan_manual_ref_rek2
        if (! $this->db->table_exists('keuangan_manual_ref_rek2')) {
            $query = '
			CREATE TABLE IF NOT EXISTS `keuangan_manual_ref_rek2` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`Akun` varchar(100) NOT NULL,
				`Kelompok` varchar(100) NOT NULL,
				`Nama_Kelompok` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)';
            $this->db->query($query);
        }

        //insert keuangan_manual_ref_rek3
        if (! $this->db->table_exists('keuangan_manual_ref_rek3')) {
            $query = '
			CREATE TABLE IF NOT EXISTS `keuangan_manual_ref_rek3` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`Kelompok` varchar(100) NOT NULL,
				`Jenis` varchar(100) NOT NULL,
				`Nama_Jenis` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)';
            $this->db->query($query);
        }

        //insert keuangan_manual_ref_bidang
        if (! $this->db->table_exists('keuangan_manual_ref_bidang')) {
            $query = '
			CREATE TABLE IF NOT EXISTS `keuangan_manual_ref_bidang` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`Kd_Bid` varchar(50) NOT NULL,
				`Nama_Bidang` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)';
            $this->db->query($query);
        }

        //insert keuangan_manual_ref_kegiatan
        if (! $this->db->table_exists('keuangan_manual_ref_kegiatan')) {
            $query = '
			CREATE TABLE IF NOT EXISTS `keuangan_manual_ref_kegiatan` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`ID_Keg` varchar(100) NOT NULL,
				`Nama_Kegiatan` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			)';
            $this->db->query($query);
        }

        //insert keuangan_manual_rinci_tpl
        if (! $this->db->table_exists('keuangan_manual_rinci_tpl')) {
            $query = '
			CREATE TABLE IF NOT EXISTS `keuangan_manual_rinci_tpl` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`Tahun` varchar(100) NOT NULL,
				`Kd_Akun` varchar(100) NOT NULL,
				`Kd_Keg` varchar(100) NOT NULL,
				`Kd_Rincian` varchar(100) NOT NULL,
				`Nilai_Anggaran` varchar(100) NOT NULL,
				`Nilai_Realisasi` varchar(100) NOT NULL,
				PRIMARY KEY (`id`)
			)';
            $this->db->query($query);
        }

        //insert keuangan_manual_ref_bidang
        $this->db->truncate('keuangan_manual_ref_bidang');
        $query = "INSERT INTO `keuangan_manual_ref_bidang` (`id`, `Kd_Bid`, `Nama_Bidang`) VALUES
		(1, '00.0000.01', 'BIDANG PENYELENGGARAN PEMERINTAHAN DESA'),
		(2, '00.0000.02', 'BIDANG PELAKSANAAN PEMBANGUNAN DESA'),
		(3, '00.0000.03', 'BIDANG PEMBINAAN KEMASYARAKATAN'),
		(4, '00.0000.04', 'BIDANG PEMBERDAYAAN MASYARAKAT'),
		(5, '00.0000.05', 'BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA')";

        $this->db->query($query);

        //insert keuangan_manual_ref_kegiatan
        $this->db->truncate('keuangan_manual_ref_kegiatan');
        $query = "INSERT INTO `keuangan_manual_ref_kegiatan` (`id`, `ID_Keg`, `Nama_Kegiatan`) VALUES
		(1, '01.01.01.', 'Penyediaan Penghasilan Tetap dan Tunjangan Kepala Desa'),
		(2, '01.01.02.', 'Penyediaan Penghasilan Tetap dan Tunjangan Perangkat Desa'),
		(3, '01.01.03.', 'Penyediaan Jaminan Sosial bagi Kepala Desa dan Perangkat Desa'),
		(4, '01.01.04.', 'Penyediaan Operasional Pemerintah Desa (ATK, Honor PKPKD dan PPKD dll)'),
		(5, '01.01.05.', 'Penyediaan Tunjangan BPD'),
		(6, '01.01.06.', 'Penyediaan Operasional BPD (rapat, ATK, Makan Minum, Pakaian Seragam, Listrik dll)'),
		(7, '01.01.07.', 'Penyediaan Insentif/Operasional RT/RW'),
		(8, '01.01.92', 'Lain-lain Sub Bidang Siltap dan Operasional Pemerintahan Desa'),
		(9, '01.02.01.', 'Penyediaan Sarana (Aset Tetap) Perkantoran/Pemerintahan'),
		(10, '01.02.02.', 'Pemeliharaan Gedung/Prasarana Kantor Desa'),
		(11, '01.02.03.', 'Pembangunan/Rehabilitasi/Peningkatan Gedung/Prasarana Kantor Desa **)'),
		(12, '01.02.90', 'Lain-lain Sub Bidang Sarana Prasarana Pemerintahan Desa'),
		(13, '01.03.01.', 'Pelayanan Administrasi Umum dan  Kependudukan'),
		(14, '01.03.02.', 'Penyusunan, Pendataan, dan Pemutakhiran Profil Desa **)'),
		(15, '01.03.03.', 'Pengelolaan Adminstrasi dan Kearsipan Pemerintahan Desa'),
		(16, '01.03.04.', 'Penyuluhan dan Penyadaran Masyarakat tentang Kependudukan dan Capil'),
		(17, '01.03.05.', 'Pemetaan dan Analisis Kemiskinan Desa secara Partisipatif'),
		(18, '01.03.90', 'Lain-lain Sub Bidang Administrasi Kependudukan, Capil, Statistik dan Kearsipan'),
		(19, '01.04.01.', 'Penyelenggaraan Musyawarah Perencanaan Desa/Pembahasan APBDes (Reguler)'),
		(20, '01.04.02.', 'Penyelenggaraan Musyawaran Desa Lainnya (Musdus, rembug desa Non Reguler)'),
		(21, '01.04.03.', 'Penyusunan Dokumen Perencanaan Desa (RPJMDesa/RKPDesa dll)'),
		(22, '01.04.04.', 'Penyusunan Dokumen Keuangan Desa (APBDes, APBDes Perubahan, LPJ dll)'),
		(23, '01.04.05.', 'Pengelolaan Administrasi/ Inventarisasi/Penilaian Aset Desa'),
		(24, '01.04.06.', 'Penyusunan Kebijakan Desa (Perdes/Perkades selain Perencanaan/Keuangan)'),
		(25, '01.04.07.', 'Penyusunan Laporan Kepala Desa, LPPDesa dan Informasi Kepada Masyarakat'),
		(26, '01.04.08.', 'Pengembangan Sistem Informasi Desa'),
		(27, '01.04.09.', 'Koordinasi/Kerjasama Penyelenggaraan Pemerintahan & Pembangunan Desa'),
		(28, '01.04.10.', 'Dukungan & Sosialisasi Pelaksanaan Pilkades, Pemilihan Ka. Kewilayahan & BPD'),
		(29, '01.04.11.', 'Penyelenggaran Lomba antar Kewilayahan & Pengiriman Kontingen dlm Lomdes'),
		(30, '01.04.97', 'Lain-lain Sub Bidang Tata Praja Pemerintahan, Perencanaan, Keuangan & Pelaporan'),
		(31, '01.05.01.', 'Sertifikasi Tanah Kas Desa'),
		(32, '01.05.02.', 'Administrasi Pertanahan (Pendaftaran Tanah dan Pemberian Registrasi Agenda Pertanahan)'),
		(33, '01.05.03.', 'Fasilitasi Sertifikasi Tanah untuk Masyarakat Miskin'),
		(34, '01.05.04.', 'Kegiatan Mediasi Konflik Pertanahan'),
		(35, '01.05.05.', 'Kegiatan Penyuluhan Pertanahan'),
		(36, '01.05.06.', 'Adminstrasi Pajak Bumi dan Bangunan (PBB)'),
		(37, '01.05.07.', 'Penentuan/Penegasan Batas/patok Tanah Kas Desa'),
		(38, '01.05.94', 'Lain-lain Sub Bidang Pertanahan'),
		(39, '02.01.01', 'Penyelenggaran PAUD/TK/TPA/TKA/TPQ/Madrasah NonFormal Milik Desa (Honor, Pakaian dll)'),
		(40, '02.01.02.', 'Dukungan Penyelenggaran PAUD (APE, Sarana PAUD dst)'),
		(41, '02.01.03.', 'Penyuluhan dan Pelatihan Pendidikan Bagi Masyarakat'),
		(42, '02.01.04.', 'Pemeliharaan Sarana Prasarana Perpustakaan/Taman Bacaan/Sanggar Belajar Milik Desa'),
		(43, '02.01.05.', 'Pemeliharaan Sarana Prasarana PAUD/TK/TPA/TKA/TPQ/Madrasah Nonformal Milik Desa'),
		(44, '02.01.08.', 'Pengelolaan Perpustakaan Milik Desa (Pengadaan Buku, Honor, Taman Baca)'),
		(45, '02.01.09.', 'Pengembangan dan Pembinaan Sanggar Seni dan Belajar'),
		(46, '02.01.10.', 'Dukungan Pendidikan bagi Siswa Miskin/Berprestasi'),
		(47, '02.01.92', 'Lain-lain Kegiatan Sub Bidang Pendidikan'),
		(48, '02.02.01.', 'Penyelenggaraan Pos Kesehatan Desa/Polindes Milik Desa (obat, Insentif, KB, dsb)'),
		(49, '02.02.02.', 'Penyelenggaraan Posyandu (Mkn Tambahan, Kls Bumil, Lamsia, Insentif)'),
		(50, '02.02.03.', 'Penyuluhan dan Pelatihan Bidang Kesehatan (Untuk Masy, Tenaga dan Kader Kesehatan dll)'),
		(51, '02.02.04.', 'Penyelenggaraan Desa Siaga Kesehatan'),
		(52, '02.02.05.', 'Pembinaan Palang Merah Remaja (PMR) Tingkat Desa'),
		(53, '02.02.06.', 'Pengasuhan Bersama atau Bina Keluarga Balita (BKB)'),
		(54, '02.02.07.', 'Pembinaan dan Pengawasan Upaya Kesehatan Tradisional'),
		(55, '02.02.08.', 'Pemeliharaan Sarana Prasarana Posyandu/Polindes/PKD'),
		(56, '02.03.01.', 'Pemeliharaan Jalan Desa'),
		(57, '02.03.02.', 'Pemeliharaan Jalan Lingkungan Pemukiman/Gang'),
		(58, '02.03.03.', 'Pemeliharaan Jalan Usaha Tani'),
		(59, '02.03.04.', 'Pemeliharaan Jembatan Desa'),
		(60, '02.03.05.', 'Pemeliharaan Prasarana Jalan Desa (Gorong-gorong/Selokan/Parit/Drainase dll)'),
		(61, '02.03.06.', 'Pemeliharaan Gedung/Prasarana Balai Desa/Balai Kemasyarakatan'),
		(62, '02.03.07.', 'Pemeliharaan Pemakaman /Situs Bersejarah/Petilasan Milik Desa'),
		(63, '02.03.08.', 'Pemeliharaan Embung Milik Desa'),
		(64, '02.03.09.', 'Pemelharaan Monumen/Gapura/Batas Desa'),
		(65, '02.03.10.', 'Pembangunan/Rehabilitas/Peningkatan/Pengerasan Jalan Desa **)'),
		(66, '02.03.12.', 'Pembangunan/Rehabilitasi/Peningkatan/Pengerasan Jalan Usaha Tani **)'),
		(67, '02.03.13.', 'Pembangunan/Rehabilitasi/Peningkatan/Pengerasan Jembatan Milik Desa **)'),
		(68, '02.03.14.', 'Pembangunan/Rehabilitasi/Peningkatan Prasarana Jalan Desa (Gorong, selokan dll)'),
		(69, '02.03.15.', 'Pembangunan/Rehabilitasi/Peningkatan Balai Desa/Balai Kemasyarakatan **)'),
		(70, '02.03.17.', 'Pembuatan/Pemutakhiran Peta Wilayah dan Sosial Desa **)'),
		(71, '02.03.18.', 'Penyusunan Dokumen Perencanaan Tata Ruang Desa'),
		(72, '02.03.19.', 'Pembangunan/Rehabilitasi/Peningkatan Embung Desa **)'),
		(73, '02.03.20.', 'Pembangunan/Rehabilitasi/Peningkatan Monumen/Gapura/Batas Desa **)'),
		(74, '02.04.01.', 'Dukungan Pelaksanaan Program Pembangunan/Rehab Rumah Tidak Layak Huni GAKIN'),
		(75, '02.04.90', 'Dukungan Pelaksanaan Program Jambanisasi untuk Keluarga Miskin'),
		(76, '02.04.02.', 'Pemeliharaan Sumur Resapan Milik Desa'),
		(77, '02.04.03.', 'Pemeliharaan Sumber Air Bersih Milik Desa (Mata Air, Penampung Air, Sumur Bor dll)'),
		(78, '02.04.04.', 'Pemeliharaan Sambungan Air Bersih ke Rumah Tangga (Pipanisasi dll)'),
		(79, '02.04.05.', 'Pemeliharaan Sanitasi Pemukiman (Gorong-gorong, Selokan, Parit diluar Prasarana Jalan))'),
		(80, '02.04.06.', 'Pemeliharaan Fasilitas Jamban Umum/MCK Umum dll'),
		(81, '02.04.08.', 'Pemeliharaan Sistem Pembuangan Air Limbah (Drainase, Air limbah Rumah Tangga)'),
		(82, '02.04.09.', 'Pemeliharaan Taman/Taman Bermain Anak Milik Desa'),
		(83, '02.04.10.', 'Pembangunan/Rehabilitasi/Peningkatan Sumur Resapan **)'),
		(84, '02.04.11.', 'Pembangunan/Rehabilitasi/Peningkatan Sumber Air Bersih Milik Desa **)'),
		(85, '02.04.12.', 'Pembangunan/Rehabilitasi/Peningkatan Sambungan Air Bersih ke Rumah Tangga **)'),
		(86, '02.04.13.', 'Pembangunan/Rehabilitasi/Peningkatan Sanitasi Permukiman **)'),
		(87, '02.04.14.', 'Pembangunan/Rehabilitas/Peningkatan Fasilitas Jamban Umum/MCK umum, dll **)'),
		(88, '02.04.15.', 'Pembangunan/Rehabilitasi/Peningkatan Fasilitas Pengelolaan Sampah **)'),
		(89, '02.04.16.', 'Pembangunan/Rehabilitasi/Peningkatan Sistem Pembuangan Air Limbah **)'),
		(90, '02.04.17.', 'Pembangunan/Rehabilitasi/Peningkatan Taman/Taman Bermain Anak Milik Desa **)'),
		(91, '02.04.94', 'Lain-lain Kegiatan Sub Bidang Perumahan Rakyat dan Kawasan Pemukiman'),
		(92, '02.05.01.', 'Pengelolaan Hutan Milik Desa'),
		(93, '02.05.02.', 'Pengelolaan Lingkungan Hidup Milik Desa'),
		(94, '02.05.92', 'Lain-lain Kegiatan Sub Bidang Kehutanan dan Lingkungan Hidup'),
		(95, '02.06.01.', 'Pembuatan Rambu-rambu di Jalan Desa'),
		(96, '02.06.02.', 'Penyelenggaraan Informasi Publik Desa (Poster, Baliho Dll)'),
		(97, '02.06.03.', 'Pembuatan dan Pengelolaan Jaringan/Instalasi Komunikasi dan Informasi Lokal Desa'),
		(98, '02.06.92', 'Lain-lain Kegiatan Sub Bidang Perhubungan, Komunikasi dan Informatika'),
		(99, '02.07.01.', 'Pemeiliharaan Sarana dan Prasarana Energi Alternatif Desa'),
		(100, '02.07.02.', 'Pembangunan/Rehabilitasi/Peningkatan Sarana & Prasarana Energi Alternatif Desa'),
		(101, '02.07.93', 'Pembangunan/pengadaan instalasi biogas/mesin pakan ternak/kandang ternak**'),
		(102, '02.08.01.', 'Pemeliharaan Sarana dan Prasarana Pariwisata Milik Desa'),
		(103, '02.08.02.', 'Pembangunan/Rehabilitasi/Peningkatan Sarana dan Prasarana Pariwisata Milik **)'),
		(104, '02.08.03.', 'Pengembangan Pariwisata Tingkat Desa'),
		(105, '02.08.91', 'Lain-Lain Kegiatan Sub Bidang Pariwisata'),
		(106, '03.01.01.', 'Pengadaan/Penyelenggaran Pos Keamanan Desa'),
		(107, '03.01.02.', 'Penguatan & Peningkatan Kapasitas Tenaga Keamanan/Ketertiban oleh Pemdes'),
		(108, '03.01.03.', 'Koordinasi Pembinaan Keamanan, Ketertiban & Perlindungan Masy. Skala Lokal Desa'),
		(109, '03.01.04.', 'Persiapan Kesiapsiagaan/Tanggap Bencana Skala Lokal Desa'),
		(110, '03.01.05.', 'Penyediaan Pos Kesiapsiagaan Bencana Skala Lokal Desa'),
		(111, '03.01.06.', 'Bantuan Hukum Untuk Aparatur Desa dan Masyarakat Miskin'),
		(112, '03.01.92', 'Lain-lain Kegiatan Sub Bidang Ketenteraman, Ketertiban Umum dan Perlindungan Masyarakat'),
		(113, '03.02.01.', 'Pembinaan Group Kesenian dan Kebudayaan Tingkat Desa'),
		(114, '03.02.02.', 'Pengiriman Kontingen Group Kesenian & Kebudayaan (Wakil Desa tkt. Kec/Kab/Kot)'),
		(115, '03.02.03.', 'Penyelenggaran Festival Kesenian, Adat/Kebudayaan, dan Kegamaan (HUT RI, Raya Keagamaan dll)'),
		(116, '03.02.04.', 'Pemeliharaan Sarana Prasarana Kebudayaan, Rumah Adat dan Kegamaan Milik Desa'),
		(117, '03.02.05.', 'Pembangunan/Rehabilitasi Sarana Prasarana Kebudayaan/Rumah Adat/Kegamaan Milik Desa **)'),
		(118, '03.02.95', 'Lain-lain Kegiatan Sub Bidang Kebudayaan dan Keagamaan'),
		(119, '03.03.01.', 'Pengiriman Kontingen Kepemudaan & Olahraga Sebagai Wakil Desa tkt Kec/Kab/Kota'),
		(120, '03.03.02.', 'Penyelenggaraan Pelatihan Kepemudaan Tingkat Desa'),
		(121, '03.03.03.', 'Penyelenggaraan Festival/Lomba Kepemudaan dan Olaraga Tingkat Desa'),
		(122, '03.03.04.', 'Pemeliharaan Sarana dan Prasarana Kepemudaan dan Olahraga Milik Desa'),
		(123, '03.03.05.', 'Pembangunan/Rehabilitasi/Peningkatan Sarana dan Prasarana Kepemudaan & Olahraga Milik Desa'),
		(124, '03.03.06.', 'Pembinaan Karangtaruna/Klub Kepemudaan/Olahraga Tingkat Desa'),
		(125, '03.03.90', 'Lain-lain Kegiatan Sub Bidang Kepemudaan dan Olahraga'),
		(126, '03.04.01.', 'Pembinaan Lembaga Adat'),
		(127, '03.04.02.', 'Pembinaan LKMD/LPM/LPMD'),
		(128, '03.04.03.', 'Pembinaan PKK'),
		(129, '03.04.04.', 'Pelatihan Pembinaan Lembaga Kemasyarakatan'),
		(130, '03.04.92', 'Lain-lain Sub Bidang Kelembagaan Masyarakat'),
		(131, '04.01.01.', 'Pemeliharaan Karamba/Kolam Perikanan Darat Milik Desa'),
		(132, '04.01.02.', 'Pemeliharaan Pelabuhan Perikanan Sungai/Kecil Milik Desa'),
		(133, '04.01.03.', 'Pembangunan/Rehabilitasi/Peningkatan Karamba/Kolam Perikanan Darat Milik Desa'),
		(134, '04.01.04.', 'Pembangunan/Rehabilitasi/Peningkatan Pelabuhan Perikanan Sungai/Kecil Milik Desa'),
		(135, '04.01.05.', 'Bantuan Perikanan (Bibit/Pakan/dll)'),
		(136, '04.01.06.', 'Bimtek/Pelatihan/Pengenalan TTG untuk Perikanan Darat/Nelayan **)'),
		(137, '04.01.94', 'Lain-lain Kegiatan Sub Bidang Kelautan dan Perikanan'),
		(138, '04.02.01.', 'Peningkatan Produksi Tanaman Pangan (alat produksi/pengelolaan/penggilingan)'),
		(139, '04.02.02.', 'Peningkatan Produksi Peternakan  (alat produksi/pengelolaan/kandang)'),
		(140, '04.02.03.', 'Penguatan Ketahanan Pangan Tingkat Desa (Lumbung Desa dll)'),
		(141, '04.02.04.', 'Pemeliharaan Saluran Irigasi Tersier/Sederhana'),
		(142, '04.02.05.', 'Pelatihan/Bimtek/Pengenalan Tekonologi Tepat Guna untuk Pertanian/Peternakan'),
		(143, '04.02.06', 'Pembangunan Saluran Irigasi Tersier/Sederhana'),
		(144, '04.02.94', 'Lain-lain Kegiatan Sub Bidang Pertanian dan Peternakan'),
		(145, '04.03.01.', 'Peningkatan Kapasitas Kepala Desa'),
		(146, '04.03.02.', 'Peningkatan Kapatitas Perangkat Desa'),
		(147, '04.03.03.', 'Peningkatan Kapasitas BPD'),
		(148, '04.03.90', 'Lain-lain Kegiatan Sub Bidang Peningkatan Kapasitas Aparatur Desa'),
		(149, '04.04.01.', 'Pelatihan dan Penyuluhan Pemberdayaan Perempuan'),
		(150, '04.04.02.', 'Pelatihan dan Penyuluhan Perlindungan Anak'),
		(151, '04.04.03.', 'Pelatihan dan Penguatan Penyandang Difable (Penyandang Disabilitas)'),
		(152, '04.04.94', 'Lain-lain Kegiatan Sub Bidang Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga'),
		(153, '04.05.01.', 'Pelatihan Manajemen Koperasi/KUD/UMKM'),
		(154, '04.05.02.', 'Pengembangan Sarana Prasarana Usaha Mikro, Kecil, Menengah dan Koperasi'),
		(155, '04.05.03.', 'Pengadaan Teknologi Tepat Guna Untuk Pengembangan Ekonomi Pedesaan Non Pertanian'),
		(156, '04.05.93', 'Lain-lain Sub Bidang Koperasi, Usaha Micro Kecil dan Menengah (UMKM)'),
		(157, '04.06.01.', 'Pembentukan BUM Desa (Persiapan dan Pembentukan Awal BUMDesa)'),
		(158, '04.06.02.', 'Pelatihan Pengelolaan BUM Desa (Pelatihan yg dilaksanakan oleh Pemdes)'),
		(159, '04.06.92', 'Lain-lain Kegiatan Sub Bidang Dukungan Penanaman Modal'),
		(160, '04.07.01.', 'Pemeliharaan Pasar Desa/Kios Milik Desa'),
		(161, '04.07.02.', 'Pembangunan/Rehab Pasar Desa/Kios Milik Desa'),
		(162, '04.07.03.', 'Pengembangan Industri Kecil Tingkat Desa'),
		(163, '04.07.04.', 'Pembentukan/Fasilitasi/Pelatihan/Pendampingan kelompok usaha ekonomi produktif'),
		(164, '04.07.92', 'Lain-lain Sub Bidang Perdagangan dan Perindustrian'),
		(165, '05.01.01.', 'Kegiatan Penanggulanan Bencana'),
		(166, '05.02.01.', 'Penanganan Keadaan Darurat'),
		(167, '05.03.01.', 'Penanganan Keadaan Mendesak'),
		(168, '01.01.90', 'Penyediaan Tali Asih Kepala Desa'),
		(169, '01.01.91', 'Penyediaan Tali Asih Perangkat Desa'),
		(170, '01.04.90', 'Penyusunan dan Penetapan Standar Satuan Harga Desa'),
		(171, '01.04.91', 'Pengisian/Penjaringan/Penyaringan Kepala Desa '),
		(172, '01.04.92', 'Pengisian/Penjaringan/Penyaringan BPD**'),
		(173, '01.04.93', 'Penyelenggaraan Pemilihan Kepala Desa Antar Waktu'),
		(174, '01.04.94', 'Penyelenggaraan Pengisian Perangkat Desa  '),
		(175, '01.04.95', 'Penyelenggaraan Evaluasi Tingkat Perkembangan Desa '),
		(176, '01.04.96', 'Sosialisasi berbagai peraturan perundang-undangan di tingkat Desa'),
		(177, '01.05.90', 'Pengadaan/Pelepasan Tanah Kas Desa**'),
		(178, '01.05.91', 'Kompensasi/Ganti Rugi Lahan terdampak Pembangunan'),
		(179, '01.05.92', 'Penetapan dan penegasan batas Desa'),
		(180, '01.05.93', 'Penyusunan tata ruang Desa dan peta Desa'),
		(181, '02.01.90', 'Pengelolaan dan Pembinaan Anak Sekolah Melalui Pemberian Makanan Tambahan Anak Sekolah (PMTAS)'),
		(182, '02.01.91', 'Dukungan Sarana Prasana Pendidikan PAUD/TK/TPA/TKA/TPQ/Madrasah Non-Formal Bukan Milik Desa (dalam bentuk barang)'),
		(183, '02.02.90', 'Pengadaan/pembangunan/pengembangan/ pemeliharaan Sarana dan Prasarana Kesehatan/ Air Bersih /sanitasi/kebersihan lingkungan/jambanisasi/mandi, cuci, kakus (MCK) **'),
		(184, '02.02.91', 'Pemantauan dan Pencegahan Penyalahgunaan Narkoba dan Zat Adiktif Di Desa'),
		(185, '02.02.92', 'Fasilitasi Pelayanan Pencegahan dan Penanggulangan Penyakit Endemik, Menular dan Tidak Menular'),
		(186, '02.02.93', 'Pembangunan/Pengelolaan/Pemanfaatan Tanaman Obat Keluarga/Apotek Desa'),
		(187, '02.02.94', 'Fasilitasi Kegiatan Palang Merah Indonesia (PMI)'),
		(188, '02.02.95', 'Pengadaan/pembangunan/pengembangan/ pemeliharaan sarana prasarana alat bantu penyandang disabilitas/panti rehabilitasi penyandang disabilitas**'),
		(189, '02.02.96', 'Fasilitasi Pelayanan Kesehatan Bagi Masyarakat Miskin'),
		(190, '02.02.97', 'Penyelenggaraan Promosi Kesehatan dan Gerakan Hidup Bersih dan Sehat'),
		(191, '02.02.98', 'Pengadaan/pembangunan/pengembangan/pemeliharaan sarana prasarana mobil/kapal motor untuk ambulance Desa**'),
		(192, '02.02.99', 'Lain-lain Kegiatan Sub Bidang Kesehatan'),
		(193, '02.03.90', 'Pembangunan/pemeliharaan jalan/talud pengaman tebing/saluran irigasi/energi baru dan terbarukan/ pembangkit listrik tenaga mikrohidro/lapangan Desa / taman Desa/lingkungan permukiman masyarakat Desa**'),
		(194, '02.03.91', 'Pembangunan/Pengadaan/pengembangan/pemeliharaan sarana dan prasarana Jasa dan Industri Kecil/industri rumah tangga/mesin jahit/peralatan bengkel kendaraan bermotor/mesin bubut untuk mebeler; /pemasara'),
		(195, '02.03.92', 'Pembangunan kolam ikan dan pembenihan ikan/perahu penangkap ikan tempat pelelangan ikan/tempat pendaratan kapal penangkap ikan/cold storage (gudang pendingin)/gudang penyimpan sarana produksi (saprota'),
		(196, '02.03.93', 'Pembangunan/Rehabilitasi/Pemeliharaan/Peningkatan Sarana Prasarana Olah Raga/ Gedung Serba Guna'),
		(197, '02.03.94', 'Pembangunan/Rehabilitasi/Peningkatan Gedung/Prasarana Balai Desa/Balai Kemasyarakatan'),
		(198, '02.03.95', 'Pembangunan/pengembangan/pemeliharaan/pengelolaan sarana dan prasarana pasar Desa**'),
		(199, '02.03.96', 'Lain-lain Kegiatan Sub Bidang Pekerjaan Umum dan Tata Ruang'),
		(200, '02.04.91', 'Penyediaan Kendaraan Pengangkut Sampah, Gerobak Sampah, Tong Sampah, Mesin Pengolah Sampah'),
		(201, '02.04.92', 'Pemeliharaan Jaringan Listrik/ Penerangan Desa'),
		(202, '02.04.93', 'Pembangunan/Rehabilitasi/Peningkatan Jaringan Listrik/ Penerangan Desa'),
		(203, '02.05.90', 'Pembuatan terasering/pembersihan daerah aliran sungai/plesengan sungai**'),
		(204, '02.05.91', 'Penanganan kebakaran hutan dan lahan/pencegahan abrasi pantai'),
		(205, '02.06.90', 'Pembangunan/Pengembangan/pemeliharaan sarana dan prasarana transportasi/informasi/ komunikasi/terminal Desa'),
		(206, '02.06.91', 'Pengadaan/pembangunan/pengembangan/pemeliharaan jaringan internet untuk warga Desa/website Desa/peralatan pengeras suara (loudspeaker)/telepon umum/ radio Single Side Band (SSB) '),
		(207, '02.07.90', 'Pengembangan dan Pengelolaan Sarana dan Prasarana Energi Alternatif tingkat Desa'),
		(208, '02.07.91', 'Pembangunan sarana dan prasarana Teknologi Tepat Guna'),
		(209, '02.07.92', 'Pengadaan/pemanfaatan/pemeliharaan penggilingan padi/peraut kelapa/ penepung biji-bijian/pencacah pakan terna/sangrai/ pemotong/pengiris buah dan sayuran/pompa air/traktor mini**'),
		(210, '02.07.94', 'Lain-lain Kegiatan Sub Bidang Energi dan Sumber Daya Mineral'),
		(211, '02.08.90', 'Pengadaan/pembangunan/pengembangan/pemeliharaan sarana dan prasarana Desa wisata/ pondok wisata/panggung hiburan/ kios cenderamata/kios warung makan/wahana permainan anak/wahana permainan outbound/ ta'),
		(212, '03.01.90', 'Dukungan Pembinaan Keamanan, Ketertiban, dan Ketentraman Wilayah dan Masyarakat Desa (Seragam dan Op Linmas, BABINSA, BABINKAMTIBMAS)'),
		(213, '03.01.91', 'Pembentukan tim keamanan Desa'),
		(214, '03.02.90', 'Melestarikan Dan Mengembangkan Gotong Royong Masyarakat Desa (BBGRM)'),
		(215, '03.02.91', 'Dukungan Pengelolaan Sarana dan Prasarana Kebudayaan/Rumah Adat/Keagamaan di Desa **'),
		(216, '03.02.92', 'Penyelenggaraan Sedekah Bumi/Sedekah Laut/Apitan'),
		(217, '03.02.93', 'Partisipasi Perayaan Hari Besar Nasional lainnya'),
		(218, '03.02.94', 'Pembinaan kerukunan umat beragama'),
		(219, '03.04.90', 'Pembentukan/Pembinaan Lembaga Kemasyarakatan'),
		(220, '03.04.91', 'Pembentukan dan Dukungan Fasilitasi Kader Pembangunan dan Pemberdayaan Masyarakat'),
		(221, '04.01.90', 'Pelaksanaan Penanggulangan Hama dan Penyakit Secara Terpadu'),
		(222, '04.01.91', 'Pengelolaan Balai Benih Ikan Milik Desa'),
		(223, '04.01.92', 'Pemeliharaan Tambatan Perahu/Tempat Pelelangan Ikan (TPI) Milik Desa**'),
		(224, '04.01.93', 'Pembangunan/Rehabilitasi/Peningkatan Tambatan Perahu/Tempat Pelelangan Ikan (TPI) Milik Desa**'),
		(225, '04.02.90', 'Peningkatan Produksi Tanaman Perkebunan (Alat Produksi dan pengolahan perkebunan)'),
		(226, '04.02.91', 'Pembangunan/Rehabilitasi/Peningkatan Balai Pertemuan Kelompok Tani'),
		(227, '04.02.92', 'Pembangunan/Peningkatan Irigasi Tersier'),
		(228, '04.02.93', 'Pelatihan kelompok tani'),
		(229, '04.04.90', 'Fasiilitasi Penguatan Kelembagaan Pengarusutamaan Gender dan Anak'),
		(230, '04.04.92', 'Peningkatan Kapasitas Kelompok Pemerhati dan Perlindungan Anak'),
		(231, '04.04.93', 'Fasilitasi terhadap kelompok-kelompok rentan, kelompok masyarakat miskin, perempuan, anak dan difabel/ Pemberian bantuan sosial/pemberian santunan kepada keluarga fakir miskin/analisis kemiskinan seca'),
		(232, '04.04.91', 'Fasilitasi Upaya Perlindungan Perempuan dan Anak Terhadap Tindakan Kekerasan'),
		(233, '04.05.90', 'Pengembangan/ Promosi Produk Unggulan Desa'),
		(234, '04.05.91', 'Pembentukan dan pengembangan usaha ekonomi masyarakat dan/atau koperasi'),
		(235, '04.05.92', 'Bantuan sarana produksi, distribusi dan pemasaran untuk usaha ekonomi masyarakat**'),
		(236, '04.06.90', 'Pembangunan Kantor BUM Desa/Sarana Prasarana BUM Desa  (menjadi aset desa)**'),
		(237, '04.06.91', 'Pelaksanaan Audit Keuangan BUM Desa, Evaluasi Perkembangan BUM Desa '),
		(238, '04.07.90', 'Pelatihan usaha ekonomi dan Perdagangan'),
		(239, '04.07.91', 'Sosialisasi Teknologi Tepat Guna/Posyantekdes dan/atau antar Desa/percontohan Teknologi Tepat Guna untuk produksi pertanian/pengembangan sumber energi perdesaan/pengemban')";

        $this->db->query($query);

        //insert keuangan_manual_ref_rek1
        $this->db->truncate('keuangan_manual_ref_rek1');
        $query = "INSERT INTO `keuangan_manual_ref_rek1` (`id`, `Akun`, `Nama_Akun`) VALUES
		(1, '1.', 'ASET'),
		(2, '2.', 'KEWAJIBAN'),
		(3, '3.', 'EKUITAS'),
		(4, '4.', 'PENDAPATAN'),
		(5, '5.', 'BELANJA'),
		(6, '6.', 'PEMBIAYAAN'),
		(7, '7.', 'NON ANGGARAN')";

        $this->db->query($query);

        //insert keuangan_manual_ref_rek2
        $this->db->truncate('keuangan_manual_ref_rek2');
        $query = "INSERT INTO `keuangan_manual_ref_rek2` (`id`, `Akun`, `Kelompok`, `Nama_Kelompok`) VALUES
		(1, '1.', '1.1.', 'Aset Lancar'),
		(2, '1.', '1.2.', 'Investasi'),
		(3, '1.', '1.3.', 'Aset Tetap'),
		(4, '1.', '1.4.', 'Dana Cadangan'),
		(5, '1.', '1.5.', 'Aset Tidak Lancar Lainnya'),
		(6, '2.', '2.1.', 'Kewajiban Jangka Pendek'),
		(7, '3.', '3.1.', 'Ekuitas'),
		(8, '4.', '4.1.', 'Pendapatan Asli Desa'),
		(9, '4.', '4.2.', 'Pendapatan Transfer'),
		(10, '4.', '4.3.', 'Pendapatan Lain-lain'),
		(11, '5.', '5.1.', 'Belanja Pegawai'),
		(12, '5.', '5.2.', 'Belanja Barang dan Jasa'),
		(13, '5.', '5.3.', 'Belanja Modal'),
		(14, '5.', '5.4.', 'Belanja Tidak Terduga'),
		(15, '6.', '6.1.', 'Penerimaan Pembiayaan'),
		(16, '6.', '6.2.', 'Pengeluaran Pembiayaan'),
		(17, '7.', '7.1.', 'Perhitungan Fihak Ketiga')";

        $this->db->query($query);

        //insert keuangan_manual_ref_rek3
        $this->db->truncate('keuangan_manual_ref_rek3');
        $query = "INSERT INTO `keuangan_manual_ref_rek3` (`id`, `Kelompok`, `Jenis`, `Nama_Jenis`) VALUES
		(1, '1.1.', '1.1.1.', 'Kas dan Bank'),
		(2, '1.1.', '1.1.2.', 'Piutang'),
		(3, '1.1.', '1.1.3.', 'Persediaan'),
		(4, '1.2.', '1.2.1.', 'Penyertaan Modal Pemerintah Desa'),
		(5, '1.3.', '1.3.1.', 'Tanah'),
		(6, '1.3.', '1.3.2.', 'Peralatan dan Mesin'),
		(7, '1.3.', '1.3.3.', 'Gedung dan Bangunan'),
		(8, '1.3.', '1.3.4.', 'Jalan, Irigasi dan Jaringan'),
		(9, '1.3.', '1.3.5.', 'Aset Tetap Lainnya'),
		(10, '1.3.', '1.3.6.', 'Konstruksi Dalam Pengerjaan'),
		(11, '1.3.', '1.3.7.', 'Aset Tak Berwujud'),
		(12, '1.3.', '1.3.8.', 'Akumulasi Penyusutan Aktiva Tetap'),
		(13, '1.4.', '1.4.1.', 'Dana Cadangan'),
		(14, '1.5.', '1.5.1.', 'Tagihan Piutang Penjualan Angsuran'),
		(15, '1.5.', '1.5.2.', 'Tagihan Tuntutan Ganti Kerugian Daerah'),
		(16, '1.5.', '1.5.3.', 'Kemitraan dengan Pihak Ketiga'),
		(17, '1.5.', '1.5.4.', 'Aktiva Tidak Berwujud'),
		(18, '1.5.', '1.5.5.', 'Aset Lain-lain'),
		(19, '2.1.', '2.1.1.', 'Hutang Perhitungan Pihak Ketiga'),
		(20, '2.1.', '2.1.2.', 'Hutang Bunga'),
		(21, '2.1.', '2.1.3.', 'Hutang Pajak'),
		(22, '2.1.', '2.1.4.', 'Pendapatan Diterima Dimuka'),
		(23, '2.1.', '2.1.5.', 'Bagian Lancar Hutang Jangka Panjang'),
		(24, '2.1.', '2.1.6.', 'Hutang Jangka Pendek Lainnya'),
		(25, '3.1.', '3.1.1.', 'Ekuitas'),
		(26, '3.1.', '3.1.2.', 'Ekuitas SAL'),
		(27, '4.1.', '4.1.1.', 'Hasil Usaha Desa'),
		(28, '4.1.', '4.1.2.', 'Hasil Aset Desa'),
		(29, '4.1.', '4.1.3.', 'Swadaya, Partisipasi dan Gotong Royong'),
		(30, '4.1.', '4.1.4.', 'Lain-Lain Pendapatan Asli Desa'),
		(31, '4.2.', '4.2.1.', 'Dana Desa'),
		(32, '4.2.', '4.2.2.', 'Bagi Hasil Pajak dan Retribusi'),
		(33, '4.2.', '4.2.3.', 'Alokasi Dana Desa'),
		(34, '4.2.', '4.2.4.', 'Bantuan Keuangan Provinsi'),
		(35, '4.2.', '4.2.5.', 'Bantuan Keuangan Kabupaten/Kota'),
		(36, '4.3.', '4.3.1.', 'Penerimaan dari Hasil Kerjasama Antar Desa'),
		(37, '4.3.', '4.3.2.', 'Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga'),
		(38, '4.3.', '4.3.3.', 'Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa'),
		(39, '4.3.', '4.3.4.', 'Hibah dan Sumbangan dari Pihak Ketiga'),
		(40, '4.3.', '4.3.5.', 'Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya'),
		(41, '4.3.', '4.3.6.', 'Bunga Bank'),
		(42, '4.3.', '4.3.9.', 'Lain-lain Pendapatan Desa Yang Sah'),
		(43, '5.1.', '5.1.1.', 'Penghasilan Tetap dan Tunjangan Kepala Desa'),
		(44, '5.1.', '5.1.2.', 'Penghasilan Tetap dan Tunjangan Perangkat Desa'),
		(45, '5.1.', '5.1.3.', 'Jaminan Sosial Kepala Desa dan Perangkat Desa'),
		(46, '5.1.', '5.1.4.', 'Tunjangan BPD'),
		(47, '5.2.', '5.2.1.', 'Belanja Barang Perlengkapan'),
		(48, '5.2.', '5.2.2.', 'Belanja Jasa Honorarium'),
		(49, '5.2.', '5.2.3.', 'Belanja Perjalanan Dinas'),
		(50, '5.2.', '5.2.4.', 'Belanja Jasa Sewa'),
		(51, '5.2.', '5.2.5.', 'Belanja Operasional Perkantoran'),
		(52, '5.2.', '5.2.6.', 'Belanja Pemeliharaan'),
		(53, '5.2.', '5.2.7.', 'Belanja Barang dan Jasa yang Diserahkan kepada Masyarakat'),
		(54, '5.3.', '5.3.1.', 'Belanja Modal Pengadaan Tanah'),
		(55, '5.3.', '5.3.2.', 'Belanja Modal Pengadaan Peralatan, Mesin dan Alat Berat'),
		(56, '5.3.', '5.3.3.', 'Belanja Modal Kendaraan'),
		(57, '5.3.', '5.3.4.', 'Belanja Modal Gedung, Bangunan dan Taman'),
		(58, '5.3.', '5.3.5.', 'Belanja Modal Jalan/Prasarana Jalan'),
		(59, '5.3.', '5.3.6.', 'Belanja Modal Jembatan'),
		(60, '5.3.', '5.3.7.', 'Belanja Modal Irigasi/Embung/Drainase/Air Limbah/Persampahan'),
		(61, '5.3.', '5.3.8.', 'Belanja Modal Jaringan/Instalasi'),
		(62, '5.3.', '5.3.9.', 'Belanja Modal Lainnya'),
		(63, '5.4.', '5.4.1.', 'Belanja Tidak Terduga'),
		(64, '6.1.', '6.1.1.', 'SILPA Tahun Sebelumnya'),
		(65, '6.1.', '6.1.2.', 'Pencairan Dana Cadangan'),
		(66, '6.1.', '6.1.3.', 'Hasil Penjualan Kekayaan Desa Yang Dipisahkan'),
		(67, '6.1.', '6.1.9.', 'Penerimaan Pembiayaan Lainnya'),
		(68, '6.2.', '6.2.1.', 'Pembentukan Dana Cadangan'),
		(69, '6.2.', '6.2.2.', 'Penyertaan Modal Desa'),
		(70, '6.2.', '6.2.9.', 'Pengeluaran Pembiayaan Lainnya'),
		(71, '7.1.', '7.1.1.', 'Perhitungan PFK - Potongan Pajak'),
		(72, '7.1.', '7.1.2.', 'Perhitungan PFK - Potongan Pajak Daerah'),
		(73, '7.1.', '7.1.3.', 'Perhitungan PFK - Uang Muka dan Jaminan')";

        $this->db->query($query);

        //insert keuangan_manual_rinci_tpl
        $this->db->truncate('keuangan_manual_rinci_tpl');
        $query = "INSERT INTO `keuangan_manual_rinci_tpl` (`id`, `Tahun`, `Kd_Akun`, `Kd_Keg`, `Kd_Rincian`, `Nilai_Anggaran`, `Nilai_Realisasi`) VALUES
		(1, '2020', '4.PENDAPATAN', '', '4.1.1. Hasil Usaha Desa', '0', '0'),
		(2, '2020', '4.PENDAPATAN', '', '4.1.2. Hasil Aset Desa', '0', '0'),
		(3, '2020', '4.PENDAPATAN', '', '4.1.3. Swadaya, Partisipasi dan Gotong Royong', '0', '0'),
		(4, '2020', '4.PENDAPATAN', '', '4.1.4. Lain-Lain Pendapatan Asli Desa', '0', '0'),
		(5, '2020', '4.PENDAPATAN', '', '4.2.1. Dana Desa', '0', '0'),
		(6, '2020', '4.PENDAPATAN', '', '4.2.2. Bagi Hasil Pajak dan Retribusi', '0', '0'),
		(7, '2020', '4.PENDAPATAN', '', '4.2.3. Alokasi Dana Desa', '0', '0'),
		(8, '2020', '4.PENDAPATAN', '', '4.2.4. Bantuan Keuangan Provinsi', '0', '0'),
		(9, '2020', '4.PENDAPATAN', '', '4.2.5. Bantuan Keuangan Kabupaten/Kota', '0', '0'),
		(10, '2020', '4.PENDAPATAN', '', '4.3.1. Penerimaan dari Hasil Kerjasama Antar Desa', '0', '0'),
		(11, '2020', '4.PENDAPATAN', '', '4.3.2. Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga', '0', '0'),
		(12, '2020', '4.PENDAPATAN', '', '4.3.3. Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa', '0', '0'),
		(13, '2020', '4.PENDAPATAN', '', '4.3.4. Hibah dan Sumbangan dari Pihak Ketiga', '0', '0'),
		(14, '2020', '4.PENDAPATAN', '', '4.3.5. Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya', '0', '0'),
		(15, '2020', '4.PENDAPATAN', '', '4.3.6. Bunga Bank', '0', '0'),
		(16, '2020', '4.PENDAPATAN', '', '4.3.9. Lain-lain Pendapatan Desa Yang Sah', '0', '0'),
		(17, '2020', '5.BELANJA', '00.0000.01 BIDANG PENYELENGGARAN PEMERINTAHAN DESA', '5.0.0', '0', '0'),
		(18, '2020', '5.BELANJA', '00.0000.02 BIDANG PELAKSANAAN PEMBANGUNAN DESA', '5.0.0', '0', '0'),
		(19, '2020', '5.BELANJA', '00.0000.03 BIDANG PEMBINAAN KEMASYARAKATAN', '5.0.0', '0', '0'),
		(20, '2020', '5.BELANJA', '00.0000.04 BIDANG PEMBERDAYAAN MASYARAKAT', '5.0.0', '0', '0'),
		(21, '2020', '5.BELANJA', '00.0000.05 BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA', '5.0.0', '0', '0'),
		(22, '2020', '6.PEMBIAYAAN', '', '6.1.1. SILPA Tahun Sebelumnya', '0', '0'),
		(23, '2020', '6.PEMBIAYAAN', '', '6.1.2. Pencairan Dana Cadangan', '0', '0'),
		(24, '2020', '6.PEMBIAYAAN', '', '6.1.3. Hasil Penjualan Kekayaan Desa Yang Dipisahkan', '0', '0'),
		(25, '2020', '6.PEMBIAYAAN', '', '6.1.9. Penerimaan Pembiayaan Lainnya', '0', '0'),
		(26, '2020', '6.PEMBIAYAAN', '', '6.2.1. Pembentukan Dana Cadangan', '0', '0'),
		(27, '2020', '6.PEMBIAYAAN', '', '6.2.2. Penyertaan Modal Desa', '0', '0'),
		(28, '2020', '6.PEMBIAYAAN', '', '6.2.9. Pengeluaran Pembiayaan Lainnya', '0', '0')";

        $this->db->query($query);
    }

    private function konfigurasi_web()
    {
        // Ambil config code provinsi
        $this->load->model('config_model');
        $desa = $this->config_model->get_data();
        // Tambah menu Admin Web -> Konfigurasi
        $query = "
			INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `parent`, `hidden`, `ikon_kecil`) VALUES
			('211', 'Pengaturan', 'setting/web', '1', 'fa-gear', '11', '4', '13', '0', 'fa-gear')
			ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), level = VALUES(level), parent = VALUES(parent), hidden = VALUES(hidden);
		";
        $this->db->query($query);

        // Tambah parameter konfigurasi (sebelumnya parameter conf ini ada di /desa/config/config.php)
        $query = "
			INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES
			(31, 'daftar_penerima_bantuan', '1', 'Apakah akan tampilkan daftar penerima bantuan di statistik halaman muka', 'boolean', 'conf_web'),
			(32, 'apbdes_footer', '1', 'Apakah akan tampilkan grafik APBDes di halaman muka', 'boolean', 'conf_web'),
			(33, 'apbdes_footer_all', '0', 'Apakah akan tampilkan grafik APBDes di semua halaman', 'boolean', 'conf_web'),
			(34, 'apbdes_manual_input', '1', 'Apakah akan tampilkan grafik APBDes yang diinput secara manual', 'boolean', 'conf_web'),
			(35, 'covid_data', '1', 'Apakah akan tampilkan status Covid-19 Provinsi di halaman muka', 'boolean', 'conf_web'),
			(36, 'covid_desa', '1', 'Apakah akan tampilkan status Covid-19 Desa di halaman muka', 'boolean', 'conf_web'),
			(37, 'covid_rss', '0', 'Apakah akan tampilkan RSS Covid-19 di halaman muka', 'boolean', 'conf_web'),
			(38, 'provinsi_covid', '{$desa['kode_propinsi']}', 'Kode provinsi status Covid-19 ', 'int', 'conf_web'),
			(39, 'statistik_chart_3d', '1', 'Apakah akan tampilkan Statistik Chart 3D', 'boolean', 'conf_web')
			ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)";
        $this->db->query($query);
    }

    private function konfigurasi_qrcode()
    {
        // Tambah menu Pengaturan -> Hasilkan QRCode
        $query = "
			INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `hidden`, `ikon_kecil`, `parent`) VALUES
			(212, 'QR Code', 'setting/qrcode/clear', 1, 'fa-qrcode', 6, 1, 0, 'fa-qrcode', 11)
			ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), level = VALUES(level), parent = VALUES(parent), hidden = VALUES(hidden);
		";
        $this->db->query($query);
    }
}
