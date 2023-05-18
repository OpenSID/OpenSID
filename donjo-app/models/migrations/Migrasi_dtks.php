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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dtks extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->tambahMenuSatuDataDanSubMenuDTKSK($hasil);
        $hasil = $hasil && $this->modifyRTMTable($hasil);
        $hasil = $hasil && $this->createDTKSTable($hasil);
        $hasil = $hasil && $this->createDTKSAnggotaTable($hasil);
        $hasil = $hasil && $this->createDTKSPengaturanProgramTable($hasil);
        $hasil = $hasil && $this->createLampiranFotoTable($hasil);
        $hasil = $hasil && $this->addIDKeluargaInDTKSTable($hasil);

        return $hasil && true;
    }

    protected function tambahMenuSatuDataDanSubMenuDTKSK($hasil)
    {
        // tambah menu satu data dan submenu dtks
        $SATU_DATA_MODUL_ID = 352;
        $DTKS_MODUL_ID      = 353;

        $hasil = $hasil && $this->tambah_modul([
            'id'         => $SATU_DATA_MODUL_ID,
            'modul'      => 'Satu Data',
            'url'        => '',
            'aktif'      => '1',
            'ikon'       => 'fa-globe',
            'urut'       => '180',
            'level'      => '1',
            'parent'     => '0',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-globe',
        ]);

        return $hasil && $this->tambah_modul([
            'id'         => $DTKS_MODUL_ID,
            'modul'      => 'DTKS',
            'url'        => 'dtks',
            'aktif'      => '1',
            'ikon'       => 'fa-exchange',
            'urut'       => '1',
            'level'      => '2',
            'parent'     => $SATU_DATA_MODUL_ID,
            'hidden'     => '0',
            'ikon_kecil' => 'fa-exchange',
        ]);
    }

    protected function modifyRTMTable($hasil)
    {
        $fields = [
            [
                'nama'   => 'terdaftar_dtks',
                'detail' => 'TINYINT(1) NOT NULL DEFAULT 0',
            ],
        ];

        foreach ($fields as $item) {
            if (! $this->db->field_exists($item['nama'], 'tweb_rtm')) {
                $hasil = $hasil && $this->db->query("ALTER TABLE tweb_rtm ADD {$item['nama']} {$item['detail']}");
            }
        }

        return $hasil;
    }

    protected function createDTKSTable($hasil)
    {
        return $this->db->query("CREATE TABLE IF NOT EXISTS `dtks` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `is_draft` TINYINT(1) NOT NULL DEFAULT '1',
            `id_rtm` INT(11) NULL,

            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            -- untuk menampung versi
            `versi_kuisioner` VARCHAR(20) NULL DEFAULT NULL,

            `catatan` TEXT NULL DEFAULT NULL,

            -- 2021 I. PENGENALAN TEMPAT / 2022 I. Keterangan Tempat
            `kode_provinsi` VARCHAR(100) NULL DEFAULT NULL,
            `kode_kabupaten` VARCHAR(100) NULL DEFAULT NULL,
            `kode_kecamatan` VARCHAR(100) NULL DEFAULT NULL,
            `kode_desa` VARCHAR(100) NULL DEFAULT NULL,
            -- `rt` VARCHAR(100) NULL DEFAULT NULL,
            -- `rw` VARCHAR(100) NULL DEFAULT NULL,
            -- `dusun` VARCHAR(100) NULL DEFAULT NULL,
            `kode_sls_non_sls` VARCHAR(4) NULL DEFAULT NULL,
            `kode_sub_sls` VARCHAR(2) NULL DEFAULT NULL,
            `nama_sls_non_sls` VARCHAR(100) NULL DEFAULT NULL,
            `no_urut_bangunan_tinggal` VARCHAR(3) NULL DEFAULT NULL,
            `no_urut_keluarga_verif` VARCHAR(3) NULL DEFAULT NULL,
            `status_keluarga` VARCHAR(1) NULL DEFAULT NULL,
            `kode_landmark_wilkerstat` VARCHAR(6) NULL DEFAULT NULL,
            -- `no_kk` VARCHAR(16) NULL DEFAULT NULL,
            `kd_kk` VARCHAR(2) NULL DEFAULT NULL,

            `no_urut_ruta` VARCHAR(15) NULL DEFAULT NULL,
            -- `nik_krt` VARCHAR(16) NULL DEFAULT NULL,
            -- `alamat` TEXT NULL DEFAULT NULL,
            -- `lat` VARCHAR(20) NULL DEFAULT NULL,
            -- `lng` VARCHAR(20) NULL DEFAULT NULL,
            -- `telepon` VARCHAR(20) NULL DEFAULT NULL,
            -- `hp` VARCHAR(20) NULL DEFAULT NULL,
            -- `email` VARCHAR(100) NULL DEFAULT NULL,

            -- 2021 II.   KETERANGAN PETUGAS DAN RESPONDEN / 2022 II.   KETERANGAN PETUGAS
            `tanggal_pencacahan` DATE NULL DEFAULT NULL,
            `nama_petugas_pencacahan` VARCHAR(100) NULL DEFAULT NULL,
            `kode_petugas_pencacahan` VARCHAR(5) NULL DEFAULT NULL,

            `tanggal_pemeriksaan` DATE NULL DEFAULT NULL,
            `nama_pemeriksa` VARCHAR(100) NULL DEFAULT NULL,
            `kode_pemeriksa` VARCHAR(5) NULL DEFAULT NULL,

            `nama_responden` VARCHAR(100) NULL DEFAULT NULL,
            `kd_hasil_pencacahan_ruta` VARCHAR(2) NULL DEFAULT NULL,

            `tanggal_pendataan` DATE NULL DEFAULT NULL,
            `nama_ppl` VARCHAR(100) NULL DEFAULT NULL,
            `kode_ppl` VARCHAR(4) NULL DEFAULT NULL,

            `nama_pml` VARCHAR(100) NULL DEFAULT NULL,
            `kode_pml` VARCHAR(3) NULL DEFAULT NULL,

            `kd_hasil_pendataan_keluarga` VARCHAR(2) NULL DEFAULT NULL,
            `no_hp_responden` VARCHAR(16) NULL DEFAULT NULL,

            -- 2021 III.   KETERANGAN PERUMAHAN
            `kd_stat_bangunan_tinggal` VARCHAR(2) NULL DEFAULT NULL,
            `kd_stat_lahan_tinggal` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sertiv_lahan_milik` VARCHAR(2) NULL DEFAULT NULL,
            `luas_lantai` int(4) NULL DEFAULT NULL,
            `kd_jenis_lantai_terluas` VARCHAR(2) NULL DEFAULT NULL,
            `kd_jenis_dinding` VARCHAR(2) NULL DEFAULT NULL,
            `kd_kondisi_dinding` VARCHAR(2) NULL DEFAULT NULL,
            `kd_jenis_atap` VARCHAR(2) NULL DEFAULT NULL,
            `kd_kondisi_atap` VARCHAR(2) NULL DEFAULT NULL,
            `jumlah_kamar_tidur` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sumber_air_minum` VARCHAR(2) NULL DEFAULT NULL,
            `kd_jarak_sumber_air_ke_tpl` VARCHAR(2) NULL DEFAULT NULL,
            `kd_memperoleh_air_minum` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sumber_penerangan_utama` VARCHAR(2) NULL DEFAULT NULL,
            `kd_daya_terpasang` VARCHAR(2) NULL DEFAULT NULL,
            `kd_daya_terpasang2` VARCHAR(2) NULL DEFAULT NULL,
            `kd_daya_terpasang3` VARCHAR(2) NULL DEFAULT NULL,
            `kode_pelanggan_daya` VARCHAR(16) NULL DEFAULT NULL,
            `kd_bahan_bakar_memasak` VARCHAR(2) NULL DEFAULT NULL,
            `kd_fasilitas_tempat_bab` VARCHAR(2) NULL DEFAULT NULL,
            `kd_jenis_kloset` VARCHAR(2) NULL DEFAULT NULL,
            `kd_pembuangan_akhir_tinja` VARCHAR(2) NULL DEFAULT NULL,

            -- 2022 KEIKUTSERTAAN PROGRAM, KEPEMILIKAN ASET, DAN LAYANAN
            -- 2021 V. KEPEMILIKAN ASET, LAYANAN, DAN KEIKUTSERTAAN PROGRAM
            -- 1.memiliki sendiri
            `kd_tabung_gas_3_kg` VARCHAR(2) NULL DEFAULT NULL,
            `kd_tabung_gas_5_5_kg` VARCHAR(2) NULL DEFAULT NULL,
            `kd_tabung_gas_12_kg` VARCHAR(2) NULL DEFAULT NULL,
            `kd_lemari_es` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ac` VARCHAR(2) NULL DEFAULT NULL,
            `kd_pemanas_air` VARCHAR(2) NULL DEFAULT NULL,
            `kd_telepon_rumah` VARCHAR(2) NULL DEFAULT NULL,
            `kd_televisi` VARCHAR(2) NULL DEFAULT NULL,
            `kd_perhiasan_10_gr_emas` VARCHAR(2) NULL DEFAULT NULL,
            `kd_rek_aktif` VARCHAR(2) NULL DEFAULT NULL,
            `kd_komputer_laptop` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sepeda_motor` VARCHAR(2) NULL DEFAULT NULL,
            `kd_mobil` VARCHAR(2) NULL DEFAULT NULL,
            `kd_perahu` VARCHAR(2) NULL DEFAULT NULL,
            `kd_kapal_perahu_motor` VARCHAR(2) NULL DEFAULT NULL,
            `kd_featured_phone` VARCHAR(2) NULL DEFAULT NULL,
            `kd_smartphone` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sepeda` VARCHAR(2) NULL DEFAULT NULL,
            -- 2.memiliki
            `kd_lahan` VARCHAR(2) NULL DEFAULT NULL,
            `luas_lahan` int(5) NULL DEFAULT NULL,
            `kd_ada_sertiv_lahan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_rumah_ditempat_lain` VARCHAR(2) NULL DEFAULT NULL,
            -- 3.memiliki
            `jumlah_sapi` int(2) NULL DEFAULT NULL,
            `jumlah_kerbau` int(2) NULL DEFAULT NULL,
            `jumlah_kuda` int(2) NULL DEFAULT NULL,
            `jumlah_babi` int(2) NULL DEFAULT NULL,
            `jumlah_kambing_domba` int(2) NULL DEFAULT NULL,
            `jumlah_unggas` int(2) NULL DEFAULT NULL,
            `jumlah_ikan` int(2) NULL DEFAULT NULL,
            `jumlah_lainnya` int(2) NULL DEFAULT NULL,
            -- 4,5,6,7
            `kd_ada_art_usaha_sendiri_bersama` VARCHAR(2) NULL DEFAULT NULL,
            `kd_internet_sebulan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_pengeluaran_pulsa_dan_data` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ada_art_lanjut_usia` VARCHAR(2) NULL DEFAULT NULL,
            -- 9.
            `kd_bss_bnpt` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_bss_bnpt` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_bss_bnpt` YEAR NULL DEFAULT NULL,
            `kd_pkh` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_pkh` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_pkh` YEAR NULL DEFAULT NULL,
            `kd_bst_covid19` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_bst_covid19` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_bst_covid19` YEAR NULL DEFAULT NULL,
            `kd_blt_dana_desa` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_blt_dana_desa` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_blt_dana_desa` YEAR NULL DEFAULT NULL,
            `kd_subsidi_listrik` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_subsidi_listrik` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_subsidi_listrik` YEAR NULL DEFAULT NULL,
            `kd_asuransi_lain` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_asuransi_lain` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_asuransi_lain` YEAR NULL DEFAULT NULL,
            `kd_bantuan_pemprov` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_bantuan_pemprov` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_bantuan_pemprov` YEAR NULL DEFAULT NULL,
            `kd_bantuan_pemkabkot` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_bantuan_pemkabkot` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_bantuan_pemkabkot` YEAR NULL DEFAULT NULL,
            `kd_bantuan_pemdes` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_bantuan_pemdes` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_bantuan_pemdes` YEAR NULL DEFAULT NULL,
            `kd_bantuan_pemda` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_bantuan_pemda` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_bantuan_pemda` YEAR NULL DEFAULT NULL,
            `kd_bantuan_masyarakat` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_bantuan_masyarakat` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_bantuan_masyarakat` YEAR NULL DEFAULT NULL,
            `kd_subsidi_pupuk` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_subsidi_pupuk` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_subsidi_pupuk` YEAR NULL DEFAULT NULL,
            `kd_subsidi_lpg` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_subsidi_lpg` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_subsidi_lpg` YEAR NULL DEFAULT NULL,

            -- 2021 VI. INFORMASI DETIL RUMAH TANGGA
            `kd_konsumsi_daging` VARCHAR(2) NULL DEFAULT NULL,
            `kd_makan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_beli_pakaian_baru` VARCHAR(2) NULL DEFAULT NULL,
            `kd_bayar_biaya_pengobatan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_bahasa_wawancara` VARCHAR(2) NULL DEFAULT NULL,
            `tulis_bahasa_daerah` VARCHAR(100) NULL DEFAULT NULL,

            PRIMARY KEY (`id`),
            CONSTRAINT FK_dtks_rtm FOREIGN KEY (id_rtm) REFERENCES tweb_rtm(id) ON UPDATE CASCADE ON DELETE SET NULL
        )
        ");

        return $hasil;
    }

    protected function createDTKSAnggotaTable($hasil)
    {
        return $hasil && $this->db->query("CREATE TABLE IF NOT EXISTS `dtks_anggota` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `id_dtks` INT(11) NULL,
            `id_penduduk` INT(11) NULL,
            `id_keluarga` INT(10) NULL,
            -- `nama` VARCHAR(100) NOT NULL,
            -- `nik` VARCHAR(16) NOT NULL,
            -- `no_kk` VARCHAR(16) NOT NULL,

            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            --  IV. KETERANGAN SOSIAL EKONOMI ANGGOTA RUMAH TANGGA
            --  Ket. Keberadaan (7 kolom : 4/24,25,26,27,28,29,30)
            `kd_ket_keberadaan_art` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_meninggal` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_meninggal` YEAR NULL DEFAULT NULL,
            `kd_punya_akta_meniggal` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_pindah_tempat` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_pindah_tempat` YEAR NULL DEFAULT NULL,
            `kd_tempat_tinggal_saat_ini` VARCHAR(2) NULL DEFAULT NULL,
            `bulan_masuk_ruta` VARCHAR(2) NULL DEFAULT NULL,
            `tahun_masuk_ruta` YEAR NULL DEFAULT NULL,
            `kd_alasan_masuk_ruta` VARCHAR(2) NULL DEFAULT NULL,

            -- Identitas Umum (9 kolom:5,6,7,8,9,10,11,12,14)
            `kd_hubungan_dg_krt` VARCHAR(2) NULL DEFAULT NULL,
            `kd_hubungan_dg_kk` VARCHAR(2) NULL DEFAULT NULL,
            `kd_jenis_kelamin` VARCHAR(2) NULL DEFAULT NULL,
            -- `tgl_lahir` DATE NOT NULL,
            -- `umur` TINYINT(2) NULL DEFAULT NULL COMMENT \"digunakan ketika dtks tidak lagi berstatus draft\",
            -- `kd_stat_perkawinan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_punya_aktanikah_cerai` VARCHAR(2) NULL DEFAULT NULL,
            -- `kd_status_kehamilan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_punya_kartuid` VARCHAR(2) NULL DEFAULT NULL,

            -- Mengalami Kesulitan/Gangguan (8 kolom:13a-h)
            `kd_sulit_penglihatan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_pendengaran` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_jalan_naiktangga` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_gerak_tangan_jari` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_belajar_intelektual` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_ingat_konsentrasi` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_perilaku_emosi` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_paham_bicara_kom` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sulit_mandiri` VARCHAR(2) NULL DEFAULT NULL,
            `kd_sering_sedih_depresi` VARCHAR(2) NULL DEFAULT NULL,

            -- Memiliki Penyakit (2 kolom:15,16)
            `kd_memiliki_perawat` VARCHAR(2) NULL DEFAULT NULL,
            `kd_merokok_sebulan_akhir` VARCHAR(2) NULL DEFAULT NULL,
            `kd_penyakit_kronis_menahun` VARCHAR(2) NULL DEFAULT NULL,

            -- Pendidikan(4 kolom:17,18,19,20)
            `kd_partisipasi_sekolah` VARCHAR(2) NULL DEFAULT NULL,
            `kd_pendidikan_tertinggi` VARCHAR(2) NULL DEFAULT NULL,
            `kd_kelas_tertinggi` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ijazah_tertinggi` VARCHAR(2) NULL DEFAULT NULL,

            -- Pekerjaan (6 kolom:21a-d (+npwp),22,23)
            `kd_bekerja_seminggu_lalu` VARCHAR(2) NULL DEFAULT NULL,
            `jumlah_jam_kerja_seminggu_lalu` VARCHAR(2) NULL DEFAULT NULL,
            `pendapatan_sebulan_terakhir` BIGINT(14) NULL DEFAULT NULL,
            `kd_punya_npwp` VARCHAR(2) NULL DEFAULT NULL,
            `npwp` VARCHAR(15) NULL DEFAULT NULL,
            `kd_lapangan_usaha_pekerjaan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_kedudukan_di_pekerjaan` VARCHAR(2) NULL DEFAULT NULL,

            -- Tumbuh Kembang Anak (5 kolom:31,32,33,34,35)
            `kd_gizi_seimbang` VARCHAR(2) NULL DEFAULT NULL,
            `kd_imunasasi_lengkap` VARCHAR(2) NULL DEFAULT NULL,
            `kd_bantuan_pempus` VARCHAR(2) NULL DEFAULT NULL,
            `kd_bantuan_pemkot` VARCHAR(2) NULL DEFAULT NULL,
            `kd_bantuan_pemdes` VARCHAR(2) NULL DEFAULT NULL,

            -- Keikutsertaan dalam Program (6 kolom:36,37,38,39,40,41)
            `kd_jamkes_setahun` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ikut_pbijkn_bpjssehat` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ikut_bpjssehat_nonpbi` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ikut_jamsostek_bpjsk` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ikut_pip` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ikut_prakerja` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ikut_kur` VARCHAR(2) NULL DEFAULT NULL,
            `kd_ikut_umi` VARCHAR(2) NULL DEFAULT NULL,
            `jumlah_jamket_kerja` VARCHAR(2) NULL DEFAULT NULL,

            -- ART USAHA SENDIRI BERSAMA
            `is_usaha_sendiri_bersama` TINYINT(1) NOT NULL DEFAULT 0,
            `kd_punya_usaha_sendiri_bersama` VARCHAR(2) NULL DEFAULT NULL,
            `jumlah_usaha_sendiri_bersama` TINYINT(2) NULL DEFAULT NULL,
            `kd_lapangan_usaha_dr_usaha` VARCHAR(2) NULL DEFAULT NULL,
            `tulis_lapangan_usaha_dr_usaha` VARCHAR(191) NOT NULL DEFAULT '',
            `tulis_lapangan_usaha_pekerjaan` VARCHAR(191) NOT NULL DEFAULT '',
            `jumlah_pekerja_dibayar` TINYINT(3) NULL DEFAULT NULL,
            `jumlah_pekerja_tidak_dibayar` TINYINT(2) NULL DEFAULT NULL,
            `kd_kepemilikan_ijin_usaha` VARCHAR(2) NULL DEFAULT NULL,
            `kd_omset_usaha_perbulan` VARCHAR(2) NULL DEFAULT NULL,
            `kd_guna_internet_usaha` VARCHAR(2) NULL DEFAULT NULL,


            PRIMARY KEY (`id`),
            CONSTRAINT FK_dtks_dtks_anggota FOREIGN KEY (id_dtks) REFERENCES dtks(id) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT FK_pend_dtks_anggota FOREIGN KEY (id_penduduk) REFERENCES tweb_penduduk(id) ON UPDATE CASCADE ON DELETE SET NULL,
            CONSTRAINT FK_kel_dtks_anggota FOREIGN KEY (id_keluarga) REFERENCES tweb_keluarga(id) ON UPDATE CASCADE ON DELETE SET NULL
        )
        ");
    }

    protected function createDTKSPengaturanProgramTable($hasil)
    {
        return $hasil && $this->db->query('CREATE TABLE IF NOT EXISTS `dtks_pengaturan_program` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `versi_kuisioner` INT(11) NOT NULL,
            `kode` VARCHAR(25) NOT NULL,
            `id_bantuan` INT(11) NULL,
            `nilai_default` VARCHAR(50) NULL DEFAULT NULL,
            `target_table` VARCHAR(100) NOT NULL,
            `target_field` TEXT NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            PRIMARY KEY (`id`),
            UNIQUE (versi_kuisioner, kode),
            CONSTRAINT FK_dtks_p_program FOREIGN KEY (id_bantuan) REFERENCES program(id) ON UPDATE CASCADE ON DELETE CASCADE
        )
        ');
    }

    protected function createLampiranFotoTable($hasil)
    {
        // Buat folder desa/upload/dtks apabila belum ada
        if (! file_exists(LOKASI_FOTO_DTKS)) {
            mkdir(LOKASI_FOTO_DTKS, 0755);
        }

        $hasil = $hasil && $this->db->query('CREATE TABLE IF NOT EXISTS `dtks_lampiran` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `id_rtm` INT(11) NULL,
            `judul` VARCHAR(30) NOT NULL,
            `keterangan` VARCHAR(100) NOT NULL,
            `foto` TEXT NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            PRIMARY KEY (`id`),
            CONSTRAINT FK_dtks_lampiran_rtm FOREIGN KEY (id_rtm) REFERENCES tweb_rtm(id) ON UPDATE CASCADE ON DELETE SET NULL
        )
        ');

        return $hasil && $this->db->query('CREATE TABLE IF NOT EXISTS `dtks_ref_lampiran` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `id_dtks` INT(11) NOT NULL,
            `id_lampiran` INT(11) NOT NULL,

            PRIMARY KEY (`id`),
            CONSTRAINT FK_ref_lampiran_dtks FOREIGN KEY (id_dtks) REFERENCES dtks(id) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT FK_lampiran_dtks FOREIGN KEY (id_lampiran) REFERENCES dtks_lampiran(id) ON UPDATE CASCADE ON DELETE CASCADE
        )
        ');
    }

    protected function addIDKeluargaInDTKSTable($hasil)
    {
        if (! $this->db->field_exists('id_keluarga', 'dtks')) {
            $fields = [
                'id_keluarga' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'null'       => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge->add_column('dtks', $fields, 'id_rtm');
            $hasil = $hasil && $this->tambahForeignKey('FK_kel_dtks', 'dtks', 'id_keluarga', 'tweb_keluarga', 'id');
        }

        return $hasil;
    }
}
