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

class Keuangan_grafik_model extends CI_model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('keuangan_model');
    }

    public function rp_apbd_widget($thn, $opt = false)
    {
        $this->db->select('Akun, Nama_Akun');
        $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');

        if ($opt) {
            $this->db->where("Akun NOT LIKE '1.%'");
            $this->db->where("Akun NOT LIKE '2.%'");
            $this->db->where("Akun NOT LIKE '3.%'");
            $this->db->where("Akun NOT LIKE '7.%'");
        } else {
            $this->db->where("Akun NOT LIKE '1.%'");
            $this->db->where("Akun NOT LIKE '7.%'");
        }

        $this->db->where('tahun_anggaran', $thn);
        $this->db->order_by('Akun', 'asc');
        $this->db->group_by('Akun');
        $this->db->group_by('Nama_Akun');
        $data['jenis_pelaksanaan'] = $this->db->get('keuangan_master')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('jenis_pelaksanaan');
        $data['anggaran'] = $this->db->get('keuangan_ta_rab_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai) AS realisasi');
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->where('Tahun', $thn);
        $data['realisasi_pendapatan'] = $this->db->get('keuangan_ta_tbp_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        //$this->db->where('keuangan_ta_spp.Jn_SPP', 'LS');
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $data['realisasi_belanja'] = $this->db->get('keuangan_ta_spp_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->where('keuangan_ta_spp.Jn_SPP', 'UM');
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $data['realisasi_belanja_um'] = $this->db->get('keuangan_ta_spp_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai) AS realisasi');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $data['realisasi_belanja_spj'] = $this->db->get('keuangan_ta_spj_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai) AS realisasi');
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->where('Tahun', $thn);
        $data['realisasi_bunga'] = $this->db->get('keuangan_ta_mutasi')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Kredit) AS realisasi');
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->where('Tahun', $thn);
        $data['realisasi_biaya'] = $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(debet) AS realisasi');
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->where('Tahun', $thn);
        $data['realisasi_jurnal'] = $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();

        return $data;
    }

    public function r_pd_widget($thn, $opt = false)
    {
        $this->db->select('keuangan_ref_rek3.Jenis, keuangan_ref_rek3.Nama_Jenis');
        $this->db->join('keuangan_ref_rek2', 'keuangan_ref_rek2.id_keuangan_master = keuangan_master.id', 'left');
        $this->db->join('keuangan_ref_rek3', 'keuangan_ref_rek3.Kelompok = keuangan_ref_rek2.Kelompok', 'left');

        if ($opt) {
            $this->db->where("keuangan_ref_rek3.Jenis LIKE '4.%'");
        } else {
            $this->db->where("keuangan_ref_rek3.Jenis NOT LIKE '1.%'");
            $this->db->where("keuangan_ref_rek3.Jenis NOT LIKE '5.%'");
            $this->db->where("keuangan_ref_rek3.Jenis NOT LIKE '6.%'");
            $this->db->where("keuangan_ref_rek3.Jenis NOT LIKE '7.%'");
        }

        $this->db->where("keuangan_ref_rek3.Nama_Jenis NOT LIKE '%Hutang%'");
        $this->db->where("keuangan_ref_rek3.Nama_Jenis NOT LIKE '%Ekuitas SAL%'");

        $this->db->where('tahun_anggaran', $thn);
        $this->db->order_by('keuangan_ref_rek3.Jenis', 'asc');
        $data['jenis_pendapatan'] = $this->db->get('keuangan_master')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', '4.', 'after');
        $this->db->group_by('jenis_pendapatan');
        $this->db->where('Tahun', $thn);
        $data['anggaran'] = $this->db->get('keuangan_ta_rab_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', '4.', 'after');
        $this->db->group_by('jenis_pendapatan');
        $this->db->where('Tahun', $thn);
        $data['realisasi_pendapatan'] = $this->db->get('keuangan_ta_tbp_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', '4.', 'after');
        $this->db->group_by('jenis_pendapatan');
        $this->db->where('Tahun', $thn);
        $data['realisasi_bunga'] = $this->db->get('keuangan_ta_mutasi')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(Kredit) AS realisasi');
        $this->db->like('Kd_Rincian', '4.', 'after');
        $this->db->group_by('jenis_pendapatan');
        $this->db->where('Tahun', $thn);
        $data['realisasi_jurnal'] = $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();

        return $data;
    }

    public function r_bd_widget($thn, $opt = false)
    {
        $this->db->select('Kd_Bid, Nama_Bidang');
        $this->db->join('keuangan_ta_bidang', 'keuangan_ta_bidang.id_keuangan_master = keuangan_master.id', 'left');
        if ($opt) {
            $this->db->where("Kd_Bid NOT LIKE '01%'");
            $this->db->where("Kd_Bid NOT LIKE '02%'");
            $this->db->where("Kd_Bid NOT LIKE '03%'");
        } else {
            $this->db->where("Kd_Bid NOT LIKE '01%'");
        }
        $this->db->where('Tahun', $thn);

        $this->db->order_by('Kd_Bid', 'asc');
        $data['jenis_belanja'] = $this->db->get('keuangan_master')->result_array();
        // Perlu ditambahkan baris berikut untuk memaksa menampilkan semua bidang di grafik keuangan
        // TODO: lihat apakah bisa diatasi langsung di script penampilan
        if (! $opt) {
            array_unshift($data['jenis_belanja'], ['Kd_Bid' => '03', 'Nama_Bidang' => 'ROW_SPACER']);
            array_unshift($data['jenis_belanja'], ['Kd_Bid' => '02', 'Nama_Bidang' => 'ROW_SPACER']);
        }

        $this->db->select('LEFT(Kd_Keg, 10) AS jenis_belanja, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->group_by('jenis_belanja');
        $this->db->where('Tahun', $thn);
        $data['anggaran'] = $this->db->get('keuangan_ta_rab_rinci')->result_array();

        $this->db->select('LEFT(Kd_Keg, 10) AS jenis_belanja, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->group_by('jenis_belanja');
        $data['realisasi_belanja'] = $this->db->get('keuangan_ta_spp_rinci')->result_array();

        $this->db->select('LEFT(Kd_Keg, 10) AS jenis_belanja, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->where('keuangan_ta_spp.Jn_SPP', 'UM');
        $this->db->group_by('jenis_belanja');
        $data['realisasi_belanja_um'] = $this->db->get('keuangan_ta_spp_rinci')->result_array();

        $this->db->select('LEFT(Kd_Keg, 10) AS jenis_belanja, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('jenis_belanja');
        $data['realisasi_belanja_spj'] = $this->db->get('keuangan_ta_spj_rinci')->result_array();

        $this->db->select('LEFT(Kd_Keg, 10) AS jenis_belanja, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->group_by('jenis_belanja');
        $this->db->where('Tahun', $thn);
        $data['realisasi_bunga'] = $this->db->get('keuangan_ta_mutasi')->result_array();

        $this->db->select('LEFT(Kd_Keg, , 10) AS jenis_belanja, SUM(keuangan_ta_jurnal_umum_rinci.Debet) AS realisasi');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->group_by('jenis_belanja');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        $data['realisasi_belanja_jurnal'] = $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();

        return $data;
    }

    /*
      lap_rp_apbd merupakan fungsi Akhir (Main) dari semua sub dan sub-sub fungsi :

      Sub fungsi Pendapatan
      1.1 sub-sub fungsi : Pagu Pendapatan
      1.2 sub-sub fungsi : Realisasi Pendapatan

      Sub fungsi Belanja
      2.1 sub-sub fungsi : Pagu Belanja
      2.2 sub-sub fungsi : Realisasi Belanja

      Sub fungsi Pembiayaan Masuk
      3.1 sub-sub fungsi : Pagu Pembiayaan Masuk
      3.1 sub-sub fungsi : Realisasi Pembiayaan Masuk

      Sub fungsi Pembiayaan Keluar
      4.1 sub-sub fungsi : Pagu Pembiayaan Keluar
      4.2 sub-sub fungsi : Realisasi Pembiayaan Keluar
    */

    //Query Laporan Pelaksanaan Realisasi
    public function lap_rp_apbd($thn, $smt1 = false)
    {
        $this->db->select('Akun, Nama_Akun, id_keuangan_master');
        $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
        $this->db->where("Akun = '4.'");
        $this->db->where('tahun_anggaran', $thn);
        $data['pendapatan'] = $this->db->get('keuangan_master')->result_array();

        foreach ($data['pendapatan'] as $i => $p) {
            $data['pendapatan'][$i]['anggaran']         = $this->pagu_akun($p['Akun'], $thn);
            $data['pendapatan'][$i]['realisasi']        = $this->real_akun_pendapatan($p['Akun'], $thn, $smt1);
            $data['pendapatan'][$i]['realisasi_bunga']  = $this->real_akun_pendapatan_bunga($p['Akun'], $thn, $smt1);
            $data['pendapatan'][$i]['realisasi_jurnal'] = $this->real_akun_pendapatan_jurnal($p['Akun'], $thn, $smt1);
            $data['pendapatan'][$i]['sub_pendapatan']   = $this->get_subval_pendapatan($p['id_keuangan_master'], $p['Akun'], $thn, $smt1);
        }

        $this->db->select('Akun, Nama_Akun, id_keuangan_master');
        $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
        $this->db->where("Akun = '5.'");
        $this->db->where('tahun_anggaran', $thn);
        $data['belanja'] = $this->db->get('keuangan_master')->result_array();

        foreach ($data['belanja'] as $i => $p) {
            $data['belanja'][$i]['anggaran']         = $this->pagu_akun($p['Akun'], $thn);
            $data['belanja'][$i]['realisasi']        = $this->real_akun_belanja($p['Akun'], $thn, $smt1);
            $data['belanja'][$i]['realisasi_um']     = $this->real_akun_belanja_um($p['Akun'], $thn, $smt1);
            $data['belanja'][$i]['realisasi_spj']    = $this->real_akun_belanja_spj($p['Akun'], $thn, $smt1);
            $data['belanja'][$i]['realisasi_bunga']  = $this->real_akun_belanja_bunga($p['Akun'], $thn, $smt1);
            $data['belanja'][$i]['realisasi_jurnal'] = $this->real_akun_belanja_jurnal($p['Akun'], $thn, $smt1);
            $data['belanja'][$i]['sub_belanja']      = $this->get_subval_belanja($p['id_keuangan_master'], $p['Akun'], $thn, $smt1);
        }

        $this->db->select('Kd_Bid, Nama_Bidang, id_keuangan_master');
        $this->db->join('keuangan_ta_bidang', 'keuangan_ta_bidang.id_keuangan_master = keuangan_master.id', 'left');
        $this->db->where('tahun_anggaran', $thn);
        $data['belanja_bidang'] = $this->db->get('keuangan_master')->result_array();

        foreach ($data['belanja_bidang'] as $i => $p) {
            $data['belanja_bidang'][$i]['anggaran']         = $this->pagu_akun_bidang($p['Kd_Bid'], $thn);
            $data['belanja_bidang'][$i]['realisasi']        = $this->real_akun_belanja_bidang($p['Kd_Bid'], $thn, $smt1);
            $data['belanja_bidang'][$i]['realisasi_um']     = $this->real_akun_belanja_bidang_um($p['Kd_Bid'], $thn, $smt1);
            $data['belanja_bidang'][$i]['realisasi_spj']    = $this->real_akun_belanja_spj_bidang($p['Kd_Bid'], $thn, $smt1);
            $data['belanja_bidang'][$i]['realisasi_bunga']  = $this->real_akun_belanja_bunga_bidang($p['Kd_Bid'], $thn, $smt1);
            $data['belanja_bidang'][$i]['realisasi_jurnal'] = $this->real_akun_belanja_bidang_jurnal($p['Kd_Bid'], $thn, $smt1);
            $data['belanja_bidang'][$i]['sub_belanja']      = $this->get_subval_belanja_bidang($p['id_keuangan_master'], $p['Kd_Bid'], $thn, $smt1);
        }

        $this->db->select('Akun, Nama_Akun, id_keuangan_master');
        $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
        $this->db->where("Akun = '6.'");
        $this->db->where('tahun_anggaran', $thn);
        $data['pembiayaan'] = $this->db->get('keuangan_master')->result_array();

        foreach ($data['pembiayaan'] as $i => $p) {
            $data['pembiayaan'][$i]['anggaran']       = $this->pagu_akun($p['Akun'], $thn);
            $data['pembiayaan'][$i]['realisasi']      = $this->real_akun_pembiayaan($p['Akun'], $thn, $smt1);
            $data['pembiayaan'][$i]['sub_pembiayaan'] = $this->get_subval_pembiayaan($p['id_keuangan_master'], $p['Akun'], $thn, $smt1);
        }

        $this->db->select('Akun, Nama_Akun, id_keuangan_master');
        $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
        $this->db->where("Akun = '6.'");
        $this->db->where('tahun_anggaran', $thn);
        $data['pembiayaan_keluar'] = $this->db->get('keuangan_master')->result_array();

        foreach ($data['pembiayaan_keluar'] as $i => $p) {
            $data['pembiayaan_keluar'][$i]['anggaran']              = $this->pagu_akun($p['Akun'], $thn);
            $data['pembiayaan_keluar'][$i]['realisasi']             = $this->real_akun_pembiayaan_keluar($p['Akun'], $thn, $smt1);
            $data['pembiayaan_keluar'][$i]['sub_pembiayaan_keluar'] = $this->get_subval_pembiayaan_keluar($p['id_keuangan_master'], $p['Akun'], $thn, $smt1);
        }

        return $data;
    }

    private function pagu_akun($akun, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_akun_bidang($akun, $thn)
    {
        $this->db->select('LEFT(Kd_Keg, 10) AS Akun, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('LEFT(Kd_Keg, 10)', $akun, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function real_akun_pendapatan($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_tbp_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_tbp', 'keuangan_ta_tbp.No_Bukti = keuangan_ta_tbp_rinci.No_Bukti', 'left');
            $this->db->where('keuangan_ta_tbp.Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_tbp.Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
    }

    private function real_akun_pendapatan_bunga($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    private function real_akun_pendapatan_jurnal($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(keuangan_ta_jurnal_umum_rinci.Kredit) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    private function real_akun_belanja($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        //$this->db->where('keuangan_ta_spp.Jn_SPP', 'LS');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_akun_belanja_um($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->where('keuangan_ta_spp.Jn_SPP', 'UM');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    public function real_akun_belanja_jurnal($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(keuangan_ta_jurnal_umum_rinci.Debet) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    public function real_akun_belanja_bidang_jurnal($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('keuangan_ta_jurnal_umum_rinci.Kd_Keg AS kelompok, SUM(keuangan_ta_jurnal_umum_rinci.Debet) AS realisasi');
        $this->db->like('keuangan_ta_jurnal_umum_rinci.Kd_Keg', $kelompok, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('kelompok');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    public function real_akun_subbelanja_jurnal($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Akun, SUM(keuangan_ta_jurnal_umum_rinci.Debet) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    private function real_akun_belanja_spj($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_spj_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spj', 'keuangan_ta_spj.No_SPJ = keuangan_ta_spj_rinci.No_SPJ', 'left');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spj_rinci')->result_array();
    }

    private function real_akun_belanja_bunga($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    private function real_akun_belanja_bidang($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Keg, 10) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('LEFT(Kd_Keg, 10)', $akun, 'after');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        //$this->db->where('keuangan_ta_spp.Jn_SPP', 'LS');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_akun_belanja_bidang_um($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Keg, 10) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('LEFT(Kd_Keg, 10)', $akun, 'after');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->where('keuangan_ta_spp.Jn_SPP', 'UM');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_akun_belanja_spj_bidang($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Keg, 10) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('LEFT(Kd_Keg, 10)', $akun, 'after');
        $this->db->where('keuangan_ta_spj_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spj', 'keuangan_ta_spj.No_SPJ = keuangan_ta_spj_rinci.No_SPJ', 'left');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spj_rinci')->result_array();
    }

    private function real_akun_belanja_bunga_bidang($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Keg, 10) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('LEFT(Kd_Keg, 10)', $akun, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    private function real_akun_pembiayaan($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_akun_pembiayaan_keluar($akun, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $akun, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Akun');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function get_subval_pendapatan($id_keuangan_master, $akun, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $data = $this->db->get('keuangan_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']         = $this->pagu_subval_pendapatan($d['Kelompok'], $thn);
            $data[$i]['realisasi']        = $this->real_subval_pendapatan($d['Kelompok'], $thn, $smt1);
            $data[$i]['realisasi_bunga']  = $this->real_subval_bunga($d['Kelompok'], $thn, $smt1);
            $data[$i]['realisasi_jurnal'] = $this->real_subval_jurnal($d['Kelompok'], $thn, $smt1);
            $data[$i]['sub_pendapatan2']  = $this->sub_pendapatan2($id_keuangan_master, $d['Kelompok'], $thn, $smt1);
        }

        return $data;
    }

    private function get_subval_belanja($id_keuangan_master, $akun, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $data = $this->db->get('keuangan_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']         = $this->pagu_subval_belanja($d['Kelompok'], $thn);
            $data[$i]['realisasi']        = $this->real_subval_belanja($d['Kelompok'], $thn, $smt1);
            $data[$i]['realisasi_jurnal'] = $this->real_akun_belanja_jurnal($d['Kelompok'], $thn, $smt1);
            $data[$i]['realisasi_um']     = $this->real_subval_belanja_um($d['Kelompok'], $thn, $smt1);
            $data[$i]['realisasi_spj']    = $this->real_subval_belanja_spj($d['Kelompok'], $thn, $smt1);
            $data[$i]['realisasi_bunga']  = $this->real_subval_belanja_bunga($d['Kelompok'], $thn, $smt1);
            $data[$i]['sub_belanja2']     = $this->sub_belanja2($id_keuangan_master, $d['Kelompok'], $thn, $smt1);
        }

        return $data;
    }

    private function get_subval_belanja_bidang($id_keuangan_master, $akun, $thn, $smt1 = false)
    {
        $this->db->select('Kd_Keg, Nama_Kegiatan');
        $this->db->like('LEFT(Kd_Keg, 10)', $akun, 'after');
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $this->db->order_by('Kd_Keg');
        $data = $this->db->get('keuangan_ta_kegiatan')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']         = $this->pagu_subval_belanja_bidang($d['Kd_Keg'], $thn);
            $data[$i]['realisasi']        = $this->real_subval_belanja_bidang($d['Kd_Keg'], $thn, $smt1);
            $data[$i]['realisasi_um']     = $this->real_subval_belanja_bidang_um($d['Kd_Keg'], $thn, $smt1);
            $data[$i]['realisasi_spj']    = $this->real_subval_belanja_spj_bidang($d['Kd_Keg'], $thn, $smt1);
            $data[$i]['realisasi_bunga']  = $this->real_subval_belanja_bunga_bidang($d['Kd_Keg'], $thn, $smt1);
            $data[$i]['realisasi_jurnal'] = $this->real_subval_belanja_jurnal($d['Kd_Keg'], $thn, $smt1);
        }

        return $data;
    }

    private function get_subval_pembiayaan($id_keuangan_master, $akun, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $this->db->where('Kelompok', '6.1.');
        $data = $this->db->get('keuangan_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']        = $this->pagu_subval_pembiayaan($d['Kelompok'], $thn);
            $data[$i]['realisasi']       = $this->real_subval_pembiayaan($d['Kelompok'], $thn, $smt1);
            $data[$i]['sub_pembiayaan2'] = $this->sub_pembiayaan2($id_keuangan_master, $d['Kelompok'], $thn, $smt1);
        }

        return $data;
    }

    private function get_subval_pembiayaan_keluar($id_keuangan_master, $akun, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $this->db->where('Kelompok', '6.2.');
        $data = $this->db->get('keuangan_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']               = $this->pagu_subval_pembiayaan_keluar($d['Kelompok'], $thn);
            $data[$i]['realisasi']              = $this->real_subval_pembiayaan_keluar($d['Kelompok'], $thn, $smt1);
            $data[$i]['sub_pembiayaan_keluar2'] = $this->sub_pembiayaan_keluar2($id_keuangan_master, $d['Kelompok'], $thn, $smt1);
        }

        return $data;
    }

    private function pagu_subval_pendapatan($kelompok, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_subval_belanja($kelompok, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_subval_belanja_bidang($kelompok, $thn)
    {
        $this->db->select('Kd_Keg AS Kelompok, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Keg', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Kd_Keg');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_subval_pembiayaan($kelompok, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_subval_pembiayaan_keluar($kelompok, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function real_subval_pendapatan($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('keuangan_ta_tbp_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_tbp', 'keuangan_ta_tbp.No_Bukti = keuangan_ta_tbp_rinci.No_Bukti', 'left');
            $this->db->where('keuangan_ta_tbp.Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_tbp.Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
    }

    private function real_subval_bunga($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    private function real_subval_jurnal($kelompok, $thn, $smt1 = false, $kolom = 'Kredit')
    {
        $this->db->select("LEFT(Kd_Rincian, 4) AS Kelompok, SUM(keuangan_ta_jurnal_umum_rinci.{$kolom}) AS realisasi");
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    private function real_subval_belanja($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        //$this->db->where('keuangan_ta_spp.Jn_SPP', 'LS');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_subval_belanja_um($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->where('keuangan_ta_spp.Jn_SPP', 'UM');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_subval_belanja_bidang($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kd_Keg AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Keg', $kelompok, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        //$this->db->where('keuangan_ta_spp.Jn_SPP', 'LS');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_subval_belanja_bidang_um($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kd_Keg AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Keg', $kelompok, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->where('keuangan_ta_spp.Jn_SPP', 'UM');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_subval_belanja_spj($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('keuangan_ta_spj_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spj', 'keuangan_ta_spj.No_SPJ = keuangan_ta_spj_rinci.No_SPJ', 'left');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_spj_rinci')->result_array();
    }

    private function real_subval_belanja_spj_bidang($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kd_Keg AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Keg', $kelompok, 'after');
        $this->db->where('keuangan_ta_spj_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spj', 'keuangan_ta_spj.No_SPJ = keuangan_ta_spj_rinci.No_SPJ', 'left');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_spj_rinci')->result_array();
    }

    private function real_subval_belanja_bunga($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    private function real_subval_belanja_bunga_bidang($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kd_Keg AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Keg', $kelompok, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    public function real_subval_belanja_jurnal($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('keuangan_ta_jurnal_umum_rinci.Kd_Keg AS kelompok, SUM(keuangan_ta_jurnal_umum_rinci.Debet) AS realisasi');
        $this->db->like('keuangan_ta_jurnal_umum_rinci.Kd_Keg', $kelompok, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('kelompok');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    private function real_subval_pembiayaan($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(keuangan_ta_jurnal_umum_rinci.Kredit) AS realisasi');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    private function real_subval_pembiayaan_keluar($kelompok, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $kelompok, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kelompok');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function sub_pendapatan2($id_keuangan_master, $kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', $kelompok);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $data = $this->db->get('keuangan_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']         = $this->pagu_pendapatan2($d['Jenis'], $thn);
            $data[$i]['realisasi']        = $this->real_pendapatan2($d['Jenis'], $thn, $smt1);
            $data[$i]['realisasi_bunga']  = $this->real_pendapatan_bunga2($d['Jenis'], $thn, $smt1);
            $data[$i]['realisasi_jurnal'] = $this->real_pendapatan_jurnal2($d['Jenis'], $thn, $smt1);
        }

        return $data;
    }

    private function sub_belanja2($id_keuangan_master, $kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', $kelompok);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $data = $this->db->get('keuangan_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']         = $this->pagu_belanja2($d['Jenis'], $thn);
            $data[$i]['realisasi']        = $this->real_belanja2($d['Jenis'], $thn, $smt1);
            $data[$i]['realisasi_um']     = $this->real_belanja2_um($d['Jenis'], $thn, $smt1);
            $data[$i]['realisasi_spj']    = $this->real_belanja2_spj($d['Jenis'], $thn, $smt1);
            $data[$i]['realisasi_bunga']  = $this->real_belanja2_bunga($d['Jenis'], $thn, $smt1);
            $data[$i]['realisasi_jurnal'] = $this->real_akun_subbelanja_jurnal($d['Jenis'], $thn, $smt1); // cek jurnal
        }

        return $data;
    }

    private function sub_pembiayaan2($id_keuangan_master, $kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', '6.1.');
        $this->db->where('Kelompok', $kelompok);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $data = $this->db->get('keuangan_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']  = $this->pagu_pembiayaan2($d['Jenis'], $thn);
            $data[$i]['realisasi'] = $this->real_pembiayaan2($d['Jenis'], $thn, $smt1);
        }

        return $data;
    }

    private function sub_pembiayaan_keluar2($id_keuangan_master, $kelompok, $thn, $smt1 = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', '6.2.');
        $this->db->where('Kelompok', $kelompok);
        $this->db->where('id_keuangan_master', $id_keuangan_master);
        $data = $this->db->get('keuangan_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']  = $this->pagu_pembiayaan_keluar2($d['Jenis'], $thn);
            $data[$i]['realisasi'] = $this->real_pembiayaan_keluar2($d['Jenis'], $thn, $smt1);
        }

        return $data;
    }

    private function pagu_pendapatan2($jenis, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_belanja2($jenis, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_pembiayaan2($jenis, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function pagu_pembiayaan_keluar2($jenis, $thn)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(AnggaranStlhPAK) AS pagu');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_rab_rinci')->result_array();
    }

    private function real_pendapatan2($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('keuangan_ta_tbp_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_tbp', 'keuangan_ta_tbp.No_Bukti = keuangan_ta_tbp_rinci.No_Bukti', 'left');
            $this->db->where('keuangan_ta_tbp.Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_tbp.Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
    }

    private function real_pendapatan_bunga2($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    private function real_pendapatan_jurnal2($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(keuangan_ta_jurnal_umum_rinci.Kredit) AS realisasi');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kd_Rincian');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    private function real_belanja2($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        //$this->db->where('keuangan_ta_spp.Jn_SPP', 'LS');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_belanja2_um($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        $this->db->where('keuangan_ta_spp.Jn_SPP', 'UM');
        if ($smt1) {
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function real_belanja2_spj($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
        $this->db->join('keuangan_ta_spj', 'keuangan_ta_spj.No_SPJ = keuangan_ta_spj_rinci.No_SPJ', 'left');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('keuangan_ta_spj_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->where('keuangan_ta_spj.Tgl_SPJ >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spj.Tgl_SPJ <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_spj_rinci')->result_array();
    }

    private function real_belanja2_bunga($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('Tahun', $thn);
        if ($smt1) {
            $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
            $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_mutasi')->result_array();
    }

    private function real_pembiayaan2($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(keuangan_ta_jurnal_umum_rinci.Kredit) AS realisasi');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Kd_Rincian');

        return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
    }

    private function real_pembiayaan_keluar2($jenis, $thn, $smt1 = false)
    {
        $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
        $this->db->like('Kd_Rincian', $jenis, 'after');
        $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
        if ($smt1) {
            $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
            $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
            $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
        }
        $this->db->group_by('Jenis');

        return $this->db->get('keuangan_ta_spp_rinci')->result_array();
    }

    private function data_widget_pendapatan($tahun, $opt = false)
    {
        if ($opt) {
            $raw_data       = $this->r_pd_widget($tahun, $opt       = true);
            $res_pendapatan = [];
            $tmp_pendapatan = [];

            foreach ($raw_data['jenis_pendapatan'] as $r) {
                $tmp_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_bunga'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }
        } else {
            $raw_data       = $this->r_pd_widget($tahun, $opt       = false);
            $res_pendapatan = [];
            $tmp_pendapatan = [];

            foreach ($raw_data['jenis_pendapatan'] as $r) {
                $tmp_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi_pendapatan'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_bunga'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi_bunga'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_jurnal'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi_jurnal'] = ($r['realisasi'] ?: 0);
            }
        }

        foreach ($tmp_pendapatan as $key => $value) {
            $res_pendapatan[] = $value;
        }

        return $res_pendapatan;
    }

    private function data_widget_belanja($tahun, $opt = false)
    {
        if ($opt) {
            $raw_data    = $this->r_bd_widget($tahun, $opt    = true);
            $res_belanja = [];
            $tmp_belanja = [];

            foreach ($raw_data['jenis_belanja'] as $r) {
                $tmp_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_um'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_spj'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_bunga'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi1'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_biaya'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi'] = ($r['realisasi'] ?: 0);
            }
        } else {
            $raw_data    = $this->r_bd_widget($tahun, $opt    = false);
            $res_belanja = [];
            $tmp_belanja = [];

            foreach ($raw_data['jenis_belanja'] as $r) {
                $tmp_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi_belanja'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_um'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi_belanja_um'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_spj'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi_belanja_spj'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_bunga'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi_bunga'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_biaya'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi_biaya'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_jurnal'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi_belanja_jurnal'] = ($r['realisasi'] ?: 0);
            }
        }

        foreach ($tmp_belanja as $key => $value) {
            $res_belanja[] = $value;
        }

        return $res_belanja;
    }

    private function data_widget_pelaksanaan($tahun, $opt = false)
    {
        if ($opt) {
            $raw_data        = $this->rp_apbd_widget($tahun, $opt        = true);
            $res_pelaksanaan = [];
            $tmp_pelaksanaan = [];

            foreach ($raw_data['jenis_pelaksanaan'] as $r) {
                $tmp_pelaksanaan[$r['Akun']]['nama'] = $r['Nama_Akun'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_um'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_spj'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_bunga'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi1'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_jurnal'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi1'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_biaya'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }
        } else {
            $raw_data        = $this->rp_apbd_widget($tahun, $opt        = false);
            $res_pelaksanaan = [];
            $tmp_pelaksanaan = [];

            foreach ($raw_data['jenis_pelaksanaan'] as $r) {
                $tmp_pelaksanaan[$r['Akun']]['nama'] = $r['Nama_Akun'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_pendapatan'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_belanja'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_um'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_belanja_um'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja_spj'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_belanja_spj'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_bunga'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_bunga'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_jurnal'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_jurnal'] = ($r['realisasi'] ?: 0);
            }

            foreach ($raw_data['realisasi_biaya'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_biaya'] = ($r['realisasi'] ?: 0);
            }
        }

        foreach ($tmp_pelaksanaan as $key => $value) {
            $res_pelaksanaan[] = $value;
        }

        return $res_pelaksanaan;
    }

    public function widget_keuangan($tahun = null)
    {
        if (null === $tahun) {
            $tahun = date('Y');
        }
        $thn = $this->keuangan_model->list_tahun_anggaran();
        if (empty($thn)) {
            return null;
        }

        if (! in_array($tahun, $thn)) {
            $tahun = $thn[0];
        }

        $raw_data = $this->data_keuangan_tema($tahun);

        foreach ($raw_data as $keys => $raws) {
            foreach ($raws as $raw) {
                $data         = $this->raw_perhitungan($raw);
                $data['nama'] = $raw['nama'];

                $res[$tahun][$keys][] = $data;
            }
        }

        return [
            //Encode ke JSON
            'data'  => json_encode($res),
            'tahun' => $this->keuangan_model->list_tahun_anggaran(),
            //Cari tahun anggaran terbaru (terbesar secara value)
            'tahun_terbaru' => $this->keuangan_model->list_tahun_anggaran()[0],
        ];
    }

    private function data_keuangan_tema($tahun)
    {
        $data['res_pelaksanaan']            = $this->data_widget_pelaksanaan($tahun, $opt            = false);
        $data['res_pelaksanaan']['laporan'] = 'APBDes ' . $tahun . ' Pelaksanaan';
        $data['res_pendapatan']             = $this->data_widget_pendapatan($tahun, $opt             = false);
        $data['res_pendapatan']['laporan']  = 'APBDes ' . $tahun . ' Pendapatan';
        $data['res_belanja']                = $this->data_widget_belanja($tahun, $opt                = false);
        $data['res_belanja']['laporan']     = 'APBDes ' . $tahun . ' Pembelanjaan';

        return $data;
    }

    public function grafik_keuangan_tema($tahun = null)
    {
        if (null === $tahun) {
            $tahun = date('Y');
        }
        $thn = $this->keuangan_model->list_tahun_anggaran();
        if (empty($thn)) {
            return null;
        }

        if (! in_array($tahun, $thn)) {
            $tahun = $thn[0];
        }
        $raw_data = $this->data_keuangan_tema($tahun);

        foreach ($raw_data as $keys => $raws) {
            foreach ($raws as $key => $raw) {
                if ($key == 'laporan') {
                    $result['data_widget'][$keys]['laporan'] = $raw;

                    continue;
                }

                $data          = $this->raw_perhitungan($raw);
                $data['judul'] = $raw['nama'];

                $result['data_widget'][$keys][] = $data;
            }
        }
        $result['tahun'] = $tahun;

        return $result;
    }

    public function raw_perhitungan($raw)
    {
        if ($raw['nama'] === 'PEMBIAYAAN') {
            $penerimaan_pembiayaan   = $raw['realisasi'] + $raw['realisasi_pendapatan'] + ($raw['realisasi_belanja'] - $raw['realisasi_belanja_um']) + $raw['realisasi_belanja_spj'] + $raw['realisasi_bunga'] + $raw['realisasi_jurnal'] + $raw['realisasi_biaya'];
            $pengeluaraan_pembiayaan = $raw['anggaran'] - $penerimaan_pembiayaan;

            $data['anggaran']  = $penerimaan_pembiayaan - $pengeluaraan_pembiayaan;
            $data['realisasi'] = $penerimaan_pembiayaan;
        } else {
            $data['anggaran']  = $raw['anggaran'];
            $data['realisasi'] = $raw['realisasi'] + $raw['realisasi_pendapatan'] + ($raw['realisasi_belanja'] - $raw['realisasi_belanja_um']) + $raw['realisasi_belanja_spj'] + $raw['realisasi_bunga'] + $raw['realisasi_jurnal'] + $raw['realisasi_biaya'] + $raw['realisasi_belanja_jurnal'];
        }

        if ($data['anggaran'] != 0 && $data['realisasi'] != 0) {
            $data['persen'] = $data['realisasi'] / $data['anggaran'] * 100;
        } elseif ($data['realisasi'] != 0) {
            $data['persen'] = 100;
        } else {
            $data['persen'] = 0;
        }
        $data['persen'] = round($data['persen'], 2);

        return $data;
    }
}
