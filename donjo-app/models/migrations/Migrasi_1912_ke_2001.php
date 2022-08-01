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

class Migrasi_1912_ke_2001 extends CI_model
{
    public function up()
    {
        $this->siskeudes_2019();
        // Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
        $this->db->query('ALTER TABLE user MODIFY COLUMN last_login datetime NULL');
    }

    private function siskeudes_2019()
    {
        // Ubah tabel keuangan untuk Siskeudes 2019
        if (! $this->db->field_exists('Kd_SubRinci', 'keuangan_ta_anggaran')) {
            $this->db->query('ALTER TABLE keuangan_ta_anggaran ADD Kd_SubRinci varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_anggaran_log ADD No_Perkades varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_anggaran_log ADD Petugas varchar(80) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_anggaran_log add Tanggal varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_anggaran_log MODIFY COLUMN UserID VARCHAR(50) NOT NULL');
            $this->db->query('ALTER TABLE keuangan_ta_kegiatan add Jbt_PPTKD varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_kegiatan add Kd_Sub varchar(30) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_kegiatan add Nilai BIGINT UNSIGNED');
            $this->db->query('ALTER TABLE keuangan_ta_kegiatan add NilaiPAK BIGINT UNSIGNED');
            $this->db->query('ALTER TABLE keuangan_ta_kegiatan add Satuan VARCHAR(30)');
            $this->db->query('ALTER TABLE keuangan_ta_kegiatan MODIFY COLUMN Kd_Bid varchar(100) NULL');
        }
        if (! $this->db->field_exists('ID_Bank', 'keuangan_ref_bank_desa')) {
            $this->db->query('ALTER TABLE keuangan_ref_bank_desa ADD ID_Bank varchar(10) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ref_bank_desa MODIFY COLUMN Alamat_Pemilik varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ref_bank_desa MODIFY COLUMN Nama_Pemilik varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ref_bank_desa MODIFY COLUMN No_Identitas varchar(20) NULL');
        $this->db->query('ALTER TABLE keuangan_ref_bank_desa MODIFY COLUMN No_Telepon varchar(20) NULL');
        if (! $this->db->field_exists('Jns_Kegiatan', 'keuangan_ref_kegiatan')) {
            $this->db->query('ALTER TABLE keuangan_ref_kegiatan ADD Jns_Kegiatan tinyint(5)');
            $this->db->query('ALTER TABLE keuangan_ref_kegiatan ADD Kd_Sub varchar(30) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ref_kegiatan MODIFY COLUMN Kd_Bid varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ref_korolari MODIFY COLUMN Jenis varchar(30) NULL');
        if (! $this->db->field_exists('ID_Bank', 'keuangan_ta_mutasi')) {
            $this->db->query('ALTER TABLE keuangan_ta_mutasi ADD ID_Bank varchar(10) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_mutasi MODIFY COLUMN Keterangan varchar(200) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_mutasi MODIFY COLUMN Kd_Bank varchar(100) NULL');
        if (! $this->db->field_exists('ID_Bank', 'keuangan_ta_pajak')) {
            $this->db->query('ALTER TABLE keuangan_ta_pajak ADD ID_Bank varchar(10) NULL');
        }
        if (! $this->db->field_exists('NTPN', 'keuangan_ta_pajak')) {
            $this->db->query('ALTER TABLE keuangan_ta_pajak ADD NTPN varchar(30) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_pemda MODIFY COLUMN Logo MEDIUMBLOB NULL');
        if (! $this->db->field_exists('ID_Bank', 'keuangan_ta_pencairan')) {
            $this->db->query('ALTER TABLE keuangan_ta_pencairan ADD ID_Bank varchar(10) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_pencairan ADD Kunci varchar(10) NULL');
        }
        if (! $this->db->field_exists('Kd_SubRinci', 'keuangan_ta_rab')) {
            $this->db->query('ALTER TABLE keuangan_ta_rab ADD Kd_SubRinci varchar(10) NULL');
        }
        if (! $this->db->field_exists('Kd_Sub', 'keuangan_ta_rpjm_kegiatan')) {
            $this->db->query('ALTER TABLE keuangan_ta_rpjm_kegiatan ADD Kd_Sub varchar(30) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_rpjm_kegiatan MODIFY COLUMN Kd_Bid varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_rpjm_misi MODIFY COLUMN Uraian_Misi varchar(250) NULL');
        if (! $this->db->field_exists('No_ID', 'keuangan_ta_rpjm_pagu_tahunan')) {
            $this->db->query('ALTER TABLE keuangan_ta_rpjm_pagu_tahunan ADD No_ID varchar(20) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_rpjm_visi MODIFY COLUMN Uraian_Visi varchar(250) NULL');
        if (! $this->db->field_exists('Kd_SubRinci', 'keuangan_ta_sppbukti')) {
            $this->db->query('ALTER TABLE keuangan_ta_sppbukti ADD Kd_SubRinci varchar(10) NULL');
        }
        if (! $this->db->field_exists('No_SPP', 'keuangan_ta_sppbukti')) {
            $this->db->query('ALTER TABLE keuangan_ta_sppbukti ADD No_SPP varchar(100) NULL');
        }
        if (! $this->db->field_exists('Rek_Bank', 'keuangan_ta_sppbukti')) {
            $this->db->query('ALTER TABLE keuangan_ta_sppbukti ADD Rek_Bank varchar(100) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_sppbukti MODIFY COLUMN Keterangan varchar(200) NULL');
        if (! $this->db->field_exists('Kd_SubRinci', 'keuangan_ta_spp_rinci')) {
            $this->db->query('ALTER TABLE keuangan_ta_spp_rinci ADD Kd_SubRinci varchar(10) NULL');
        }
        if (! $this->db->field_exists('ID_Bank', 'keuangan_ta_tbp')) {
            $this->db->query('ALTER TABLE keuangan_ta_tbp ADD ID_Bank varchar(10) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_tbp MODIFY COLUMN Uraian varchar(250) NULL');
        if (! $this->db->field_exists('Kd_SubRinci', 'keuangan_ta_tbp_rinci')) {
            $this->db->query('ALTER TABLE keuangan_ta_tbp_rinci ADD Kd_SubRinci varchar(10) NULL');
        }
        if (! $this->db->field_exists('Agt', 'keuangan_ta_triwulan')) {
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Jan varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Peb varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Mar varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Apr varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Mei varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Jun varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Jul varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Agt varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Sep varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Okt varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Nop varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Des varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan ADD Kd_SubRinci varchar(10) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_triwulan MODIFY COLUMN Tw1Rinci varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_triwulan MODIFY COLUMN Tw2Rinci varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_triwulan MODIFY COLUMN Tw3Rinci varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_triwulan MODIFY COLUMN Tw4Rinci varchar(100) NULL');
        if (! $this->db->field_exists('Agt', 'keuangan_ta_triwulan_rinci')) {
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Jan varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Peb varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Mar varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Apr varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Mei varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Jun varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Jul varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Agt varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Sep varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Okt varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Nop varchar(100) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci ADD Des varchar(100) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci MODIFY COLUMN Tw1Rinci varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci MODIFY COLUMN Tw2Rinci varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci MODIFY COLUMN Tw3Rinci varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_triwulan_rinci MODIFY COLUMN Tw4Rinci varchar(100) NULL');
        // Sesuaikan tabel keuangan dengan sql_mode STRICT_TRANS_TABLES
        $this->db->query('ALTER TABLE keuangan_ta_spj_rinci MODIFY COLUMN Alamat varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ref_bank_desa MODIFY COLUMN Kantor_Cabang varchar(100) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_pajak MODIFY COLUMN Keterangan varchar(250) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_pencairan MODIFY COLUMN Keterangan varchar(250) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_spp MODIFY COLUMN Keterangan varchar(250) NULL');
        $this->db->query('ALTER TABLE keuangan_ta_pemda MODIFY COLUMN Logo MEDIUMBLOB NULL');
        // Sesuaikan dengan data 2019
        $this->db->query('ALTER TABLE keuangan_ta_rpjm_tujuan MODIFY COLUMN Uraian_Tujuan varchar(250)');
        if (! $this->db->field_exists('Kunci', 'keuangan_ta_spj')) {
            $this->db->query('ALTER TABLE keuangan_ta_spj ADD Kunci varchar(10) NULL');
        }
        if (! $this->db->field_exists('Kd_SubRinci', 'keuangan_ta_spj_bukti')) {
            $this->db->query('ALTER TABLE keuangan_ta_spj_bukti ADD Kd_SubRinci varchar(10) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_spj_bukti MODIFY COLUMN Keterangan varchar(250)');
        if (! $this->db->field_exists('Kd_SubRinci', 'keuangan_ta_spj_rinci')) {
            $this->db->query('ALTER TABLE keuangan_ta_spj_rinci ADD Kd_SubRinci varchar(10) NULL');
        }
        $this->db->query('ALTER TABLE keuangan_ta_rpjm_sasaran MODIFY COLUMN Uraian_Sasaran varchar(250)');
        if (! $this->db->field_exists('F10', 'keuangan_ta_spp')) {
            $this->db->query('ALTER TABLE keuangan_ta_spp ADD F10 varchar(10) NULL');
            $this->db->query('ALTER TABLE keuangan_ta_spp ADD F11 varchar(10) NULL');
        }
    }
}
