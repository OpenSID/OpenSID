<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2210_ke_2211 extends MY_model
{
    const SATU_DATA_MODUL_ID = 337;
    const DTKS_MODUL_ID = 338;
    
    private $engine = 'InnoDB';

    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->tambahSubMenuDTKSK($hasil);
        $hasil = $hasil && $this->modifyRTMTable($hasil);
        $hasil = $hasil && $this->tambahTabelDTKSAnggota($hasil);
        $hasil = $hasil && $this->tambahTabelDTKSAnakDalamTanggunganLuarRuta($hasil);
        $hasil = $hasil && $this->tambahTabelDTKSARTUsaha($hasil);
       
        return $hasil;
    }

    protected function tambahSubMenuDTKSK($hasil)
    {
        $hasil = $hasil && $this->tambah_modul([
            'id'         => self::SATU_DATA_MODUL_ID,
            'modul'      => 'Satu Data',
            'url'        => '',
            'aktif'      => '1',
            'ikon'       => 'fa-info',
            'urut'       => '180',
            'level'      => '1',
            'parent'     => '0',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-info',
        ]);
        $hasil = $hasil && $this->tambah_modul([
            'id'         => self::DTKS_MODUL_ID,
            'modul'      => 'DTKS',
            'url'        => 'dtks/clear',
            'aktif'      => '1',
            'ikon'       => 'fa-info',
            'urut'       => '1',
            'level'      => '2',
            'parent'     => self::SATU_DATA_MODUL_ID,
            'hidden'     => '0',
            'ikon_kecil' => 'fa-info',
        ]);
        
        return $hasil;
    }

    protected function modifyRTMTable($hasil)
    {
        $fields = [
            [
                'nama'   => 'satuan_lingkungan_setempat', 
                'detail' => 'VARCHAR(50) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'no_urut_ruta', 
                'detail' => 'VARCHAR(20) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'tgl_verivali', 
                'detail' => 'DATE NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'nama_petugas_verivali', 
                'detail' => 'VARCHAR(100) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kode_petugas_verivali', 
                'detail' => 'VARCHAR(20) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'tgl_pemeriksaan', 
                'detail' => 'DATE NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'nama_pemeriksa', 
                'detail' => 'VARCHAR(100) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kode_pemeriksa', 
                'detail' => 'VARCHAR(20) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_hasil_verivali_kpm', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'nama_responden', 
                'detail' => 'VARCHAR(100) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_stat_bangunan_tinggal', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_stat_lahan_tinggal', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'luas_lantai', 
                'detail' => 'INT(4) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_jenis_lantai_terluas', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_jenis_dinding', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_kondisi_dinding', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_jenis_atap', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_kondisi_atap', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'jumlah_kamar_tidur', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_sumber_air_minum', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kode_pelanggan_air_minum', 
                'detail' => 'VARCHAR(25) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_cara_memperoleh_air_minum', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_sumber_penerangan_utama', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_daya_terpasamg', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kode_pelanggan_daya', 
                'detail' => 'VARCHAR(25) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_bahan_bakar_memasak', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kode_pelanggan_bb_memasak', 
                'detail' => 'VARCHAR(25) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_fasilitas_tempat_bab', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_jenis_kloset', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'kd_pembuangan_akhir_tinja', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_tabung_gas_5_5_kg_lebih', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_lemari_es', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_ac', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_pemanas_air', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_telepon', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_televisi', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_perhiasan_10_gr_emas', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_komputer_laptop', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_sepeda', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_sepeda_motor', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_mobil', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_perahu', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_motor_tempel', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_perahu_motor', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_kapal', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_lahan', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'luas_lahan', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_rumah_ditempat_lain', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'jumlah_sapi', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'jumlah_kerbau', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'jumlah_kuda', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'jumlah_babi', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'jumlah_kambing_domba', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_art_usaha_sendiri_bersama', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_kks_kps', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_kip_bsm', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_kis_bpjs_jamkesmas', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_bpjs_kesehatan_mandiri', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_jamsostek_bpjs_ktenagakrjaan', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_asuransi_kesehatan_lainnya', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_pkh', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_raskin', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'ada_kur', 
                'detail' => 'INT(2) NULL DEFAULT NULL'
            ],
            [
                'nama'   => 'updated_at',
                'detail' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            ]

        ];
        foreach($fields as $item){
            if (! $this->db->field_exists($item['nama'], 'tweb_rtm')) {
                $hasil = $hasil && $this->db->query("ALTER TABLE tweb_rtm ADD {$item['nama']} {$item['detail']}");
            }

        }
        return $hasil;
    }

    protected function tambahTabelDTKSAnggota($hasil)
    {
        $hasil = $hasil && $this->db->query("CREATE TABLE 
        IF NOT EXISTS tweb_dtks_art (
            `id`              INT(11) NOT NULL AUTO_INCREMENT,
            `id_penduduk`     INT(11) NOT NULL,
            `id_rtm`          INT(11) NOT NULL,
            `kd_hubungan_dg_krt`               INT(2) NULL DEFAULT NULL,
            `kd_hubungan_dg_kepala_k`          INT(2) NULL DEFAULT NULL,
            `kd_jenis_kelamin`                 INT(2) NULL DEFAULT NULL,
            `kd_status_perkawinan`             INT(2) NULL DEFAULT NULL,
            `kd_kpmilikan_aktanikah_aktacerai` INT(2) NULL DEFAULT NULL,
            `kd_trcantum_dlm_kk`               INT(2) NULL DEFAULT NULL,
            `sum_kode_kpmilikan_kartuid`       INT(2) NULL DEFAULT NULL,
            `kd_status_kehamilan`              INT(2) NULL DEFAULT NULL,
            `kd_jenis_cacat`                   INT(2) NULL DEFAULT NULL,
            `kd_penyakit_kronis_menahun`       INT(2) NULL DEFAULT NULL,
            `kd_partisipasi_sekolah`           INT(2) NULL DEFAULT NULL,
            `kd_pendidikan_tertinggi`          INT(2) NULL DEFAULT NULL,
            `kd_kelas_tertinggi`               INT(2) NULL DEFAULT NULL,
            `kd_ijazah_tertinggi`              INT(2) NULL DEFAULT NULL,
            `kd_bekerja_seminggu_lalu`         INT(2) NULL DEFAULT NULL,
            `jumlah_jam_kerja_seminggu_lalu`   INT(2) NULL DEFAULT NULL,
            `kd_lapangan_usaha_pekerjaan`      INT(2) NULL DEFAULT NULL,
            `kd_kedudukan_di_pekerjaan`        INT(2) NULL DEFAULT NULL,
            `kd_ket_keberadaan_art`            INT(2) NULL DEFAULT NULL,
            `ada_kps_kks`                      INT(2) NULL DEFAULT NULL,
            `ada_kis_pbijkn`                   INT(2) NULL DEFAULT NULL,
            `ada_kip_bsm`                      INT(2) NULL DEFAULT NULL,
            `ada_pkh`                          INT(2) NULL DEFAULT NULL,
            `ada_raskin_rastra`                INT(2) NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE INDEX `id_penduduk` (`id_penduduk`) USING BTREE,

            CONSTRAINT FK_dtksk_art_id_penduduk FOREIGN KEY (id_penduduk) REFERENCES tweb_penduduk(id) 
                ON UPDATE CASCADE ON DELETE RESTRICT,
            CONSTRAINT FK_dtksk_art_id_rtm FOREIGN KEY (id_rtm) REFERENCES tweb_rtm(id) 
                ON UPDATE CASCADE ON DELETE RESTRICT

        ) ENGINE={$this->engine} AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        ");

        return $hasil;
    }

    protected function tambahTabelDTKSAnakDalamTanggunganLuarRuta($hasil)
    {
        $hasil = $hasil && $this->db->query("CREATE TABLE 
        IF NOT EXISTS tweb_dtks_adt_luar_ruta (
            `id`            INT(11) NOT NULL AUTO_INCREMENT,
            `id_rtm`        INT(11) NOT NULL,
            `nama_anak`     VARCHAR(80) NOT NULL,
            `nisn_ktm`      VARCHAR(25) NOT NULL,
            `alamat`        TEXT NOT NULL,
            `nik`           VARCHAR(16) NOT NULL,
            `nama_sekolah`  VARCHAR(100) NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            
            CONSTRAINT FK_dtksk_adtlr_id_rtm FOREIGN KEY (id_rtm) REFERENCES tweb_rtm(id) 
                ON UPDATE CASCADE ON DELETE RESTRICT

        ) ENGINE={$this->engine} AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        ");

        return $hasil;
    }

    protected function tambahTabelDTKSARTUsaha($hasil)
    {
        $hasil = $hasil && $this->db->query("CREATE TABLE 
        IF NOT EXISTS tweb_dtks_art_usaha (
            `id`            INT(11) NOT NULL AUTO_INCREMENT,
            `id_dtks_art`       INT(11) NOT NULL,
            `nama_lapangan_usaha_lainnya`   VARCHAR(50) NULL DEFAULT NULL,
            `jumlah_pekerja`                INT(2) NULL DEFAULT NULL,
            `ada_tempat_usaha`              INT(2) NULL DEFAULT NULL,
            `kd_omset_usaha_perbulan`       INT(2) NULL DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE INDEX `id_dtks_art` (`id_dtks_art`) USING BTREE,
            
            CONSTRAINT FK_dtksk_art_usaha_art FOREIGN KEY (id_dtks_art) REFERENCES tweb_dtks_art(id) 
                ON UPDATE CASCADE ON DELETE RESTRICT

        ) ENGINE={$this->engine} AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        ");

        return $hasil;
    }
}