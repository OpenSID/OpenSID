<?php
class Keuangan_grafik_model extends CI_model {

  public function __construct()
  {
    parent::__construct();
  }

  // Post Format Transparansi Anggaran Data
  // Query Grafik
  public function rp_apbd($smt, $thn)
  {
    $this->db->select('DISTINCT(Akun), Nama_Akun');
    $this->db->where("Akun = '4.' OR Akun = '5.' OR Akun = '6.'");
    $data['jenis_belanja'] = $this->db->get('keuangan_ref_rek1')->result_array();

    $this->db->select("LEFT(Kd_Rincian, 2) AS jenis_belanja");
    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('jenis_belanja');
    $data['anggaran'] = $this->db->get('keuangan_ta_rab_rinci')->result_array();

    $data['realisasi'] = array(
      $this->total_realisasi_pendapatan($thn)[0],
      $this->total_realisasi_belanja($thn)[0]
    );

    return $data;
  }

  public function r_pd($smt, $thn)
  {
    $this->db->select('Nama_Jenis, Jenis');
    $this->db->join('keuangan_ref_rek3', 'keuangan_ref_rek3.Jenis = LEFT(keuangan_ta_rab_rinci.Kd_Rincian, 6)', 'left');
    $this->db->where("Jenis LIKE '4.%'");
    $this->db->where('Tahun', $thn);
    $this->db->order_by('Jenis');
    $this->db->group_by('Jenis');
    $this->db->group_by('Nama_Jenis');
    $data['jenis_pendapatan'] = $this->db->get('keuangan_ta_rab_rinci')->result_array();

    $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(AnggaranStlhPAK) AS Pagu');
    $this->db->like('Kd_Rincian', '4.', 'after');
    $this->db->order_by('jenis_pendapatan', 'asc');
    $this->db->group_by('jenis_pendapatan');
    $this->db->where('Tahun', $thn);
    $data['anggaran'] = $this->db->get('keuangan_ta_rab_rinci')->result_array();

    $this->db->join('keuangan_ta_tbp_rinci', 'LEFT(keuangan_ta_tbp_rinci.Kd_Rincian, 6) = LEFT(keuangan_ta_rab_rinci.Kd_Rincian, 6)', 'left');
    $this->db->select('LEFT(keuangan_ta_rab_rinci.Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->where('keuangan_ta_rab_rinci.Tahun', $thn);
    $this->db->like('LEFT(keuangan_ta_rab_rinci.Kd_Rincian, 2)', '4.', 'after');
    $this->db->group_by('Jenis');
    $data['realisasi'] = $this->db->get('keuangan_ta_rab_rinci`')->result_array();

    return $data;
  }

  public function r_bd($smt, $thn)
  {
    $this->db->select('Kd_Bid, Nama_Bidang, id_keuangan_master');
    $this->db->join('keuangan_ta_bidang', 'keuangan_ta_bidang.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where('Tahun', $thn);
    $this->db->order_by('Kd_Bid', 'asc');
    $data['bidang'] = $this->db->get('keuangan_master')->result_array();

    $this->db->select('LEFT(keuangan_ta_rab_rinci.Kd_Keg, 10) AS Kode_Bid, SUM(AnggaranStlhPAK) AS Pagu');
    $this->db->join('keuangan_ta_rab_rinci', 'LEFT(keuangan_ta_rab_rinci.Kd_Keg, 10) = keuangan_ta_bidang.Kd_Bid', 'left');
    $this->db->group_by('Kode_Bid');
    $this->db->order_by('Kode_Bid', 'asc');
    $this->db->where('keuangan_ta_bidang.Tahun', $thn);
    $data['anggaran'] = $this->db->get('keuangan_ta_bidang')->result_array();

    $this->db->select('LEFT(keuangan_ta_spp_rinci.Kd_Keg, 10) AS Kode_Bid, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp_rinci', 'LEFT(keuangan_ta_spp_rinci.Kd_Keg, 10) = keuangan_ta_bidang.Kd_Bid', 'left');
    $this->db->where('keuangan_ta_bidang.Tahun', $thn);
    $this->db->group_by('Kode_Bid');
    $this->db->order_by('Kode_Bid', 'asc');
    $data['realisasi'] = $this->db->get('keuangan_ta_bidang')->result_array();
    return $data;
  }

  public function r_pembiayaan($smt, $thn)
  {
    $this->db->select('Nama_Kelompok');
    $this->db->join('keuangan_ref_rek2', 'keuangan_ref_rek2.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where('tahun_anggaran', $thn);
    $this->db->like('Kelompok', '6.', 'after');
    $data['pembiayaan'] = $this->db->get('keuangan_master')->result_array();

    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(AnggaranStlhPAK) AS Pagu');
    $this->db->like('LEFT(Kd_Rincian, 2)', '6.', 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    $this->db->order_by('Kelompok', 'asc');
    $data['anggaran'] = $this->db->get('keuangan_ta_rab_rinci')->result_array();

    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS Nilai');
    $this->db->like('LEFT(Kd_Rincian, 2)', '6.', 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    $this->db->order_by('Kelompok', 'asc');
    $data['realisasi'] = $this->db->get('keuangan_ta_spj_rinci')->result_array();

    return $data;
  }

  //Query Laporan Pelaksanaan Realisasi
  public function lap_rp_apbd($smt, $thn)
  {
    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '4.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['pendapatan'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['pendapatan'] as $i => $p)
    {
      $data['pendapatan'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['pendapatan'][$i]['realisasi'] = $this->real_akun_pendapatan($p['Akun'], $thn);
      $data['pendapatan'][$i]['realisasi_bunga'] = $this->real_akun_pendapatan_bunga($p['Akun'], $thn);
      $data['pendapatan'][$i]['sub_pendapatan'] = $this->get_subval_pendapatan($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '5.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['belanja'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['belanja'] as $i => $p)
    {
      $data['belanja'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['belanja'][$i]['realisasi'] = $this->real_akun_belanja($p['Akun'], $thn);
      $data['belanja'][$i]['sub_belanja'] = $this->get_subval_belanja($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '6.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['pembiayaan'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['pembiayaan'] as $i => $p)
    {
      $data['pembiayaan'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['pembiayaan'][$i]['realisasi'] = $this->real_akun_pembiayaan($p['Akun'], $thn);
      $data['pembiayaan'][$i]['sub_pembiayaan'] = $this->get_subval_pembiayaan($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '6.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['pembiayaan_keluar'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['pembiayaan_keluar'] as $i => $p)
    {
      $data['pembiayaan_keluar'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['pembiayaan_keluar'][$i]['realisasi'] = $this->real_akun_pembiayaan_keluar($p['Akun'], $thn);
      $data['pembiayaan_keluar'][$i]['sub_pembiayaan_keluar'] = $this->get_subval_pembiayaan_keluar($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    return $data;
  }

  //Query Laporan Pelaksanaan Realisasi
  public function lap_rp_apbd_smt1($smt, $thn)
  {
    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '4.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['pendapatan'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['pendapatan'] as $i => $p)
    {
      $data['pendapatan'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['pendapatan'][$i]['realisasi'] = $this->real_akun_pendapatan_smt1($p['Akun'], $thn);
      $data['pendapatan'][$i]['realisasi_bunga'] = $this->real_akun_pendapatan_bunga_smt1($p['Akun'], $thn);
      $data['pendapatan'][$i]['sub_pendapatan'] = $this->get_subval_pendapatan_smt1($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '5.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['belanja'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['belanja'] as $i => $p)
    {
      $data['belanja'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['belanja'][$i]['realisasi'] = $this->real_akun_belanja_smt1($p['Akun'], $thn);
      $data['belanja'][$i]['sub_belanja'] = $this->get_subval_belanja_smt1($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '6.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['pembiayaan'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['pembiayaan'] as $i => $p)
    {
      $data['pembiayaan'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['pembiayaan'][$i]['realisasi'] = $this->real_akun_pembiayaan_smt1($p['Akun'], $thn);
      $data['pembiayaan'][$i]['sub_pembiayaan'] = $this->get_subval_pembiayaan_smt1($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    $this->db->select('Akun, Nama_Akun, id_keuangan_master');
    $this->db->join('keuangan_ref_rek1', 'keuangan_ref_rek1.id_keuangan_master = keuangan_master.id', 'left');
    $this->db->where("Akun = '6.'");
    $this->db->where('tahun_anggaran', $thn);
    $data['pembiayaan_keluar'] = $this->db->get('keuangan_master')->result_array();
    foreach ($data['pembiayaan_keluar'] as $i => $p)
    {
      $data['pembiayaan_keluar'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['pembiayaan_keluar'][$i]['realisasi'] = $this->real_akun_pembiayaan_keluar_smt1($p['Akun'], $thn);
      $data['pembiayaan_keluar'][$i]['sub_pembiayaan_keluar'] = $this->get_subval_pembiayaan_keluar_smt1($p['id_keuangan_master'], $p['Akun'], $thn);
    }

    return $data;
  }

  private function total_realisasi_pendapatan($thn)
  {
    $this->db->select('SUM(Nilai) AS realisasi');
    $this->db->where('Tahun', $thn);
    return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
  }

  private function total_realisasi_belanja($thn)
  {
    $this->db->select('SUM(Nilai) AS realisasi');
    $this->db->where('Tahun', $thn);
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function pagu_akun($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(AnggaranStlhPAK) AS pagu');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_rab_rinci')->result_array();
  }

  private function real_akun_pendapatan($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
  }

  private function real_akun_pendapatan_smt1($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_tbp', 'keuangan_ta_tbp.No_Bukti = keuangan_ta_tbp_rinci.No_Bukti', 'left');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('keuangan_ta_tbp.Tahun', $thn);
    $this->db->where('keuangan_ta_tbp.Tgl_Bukti >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_tbp.Tgl_Bukti <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
  }

  private function real_akun_pendapatan_bunga($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_mutasi')->result_array();
  }

  private function real_akun_pendapatan_bunga_smt1($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
    $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_mutasi')->result_array();
  }

  private function real_akun_belanja($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_akun_belanja_smt1($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_akun_pembiayaan($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_akun_pembiayaan_smt1($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_akun_pembiayaan_keluar($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_akun_pembiayaan_keluar_smt1($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function get_subval_pendapatan($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_pendapatan($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_pendapatan($d['Kelompok'], $thn);
      $data[$i]['realisasi_bunga'] = $this->real_subval_bunga($d['Kelompok'], $thn);
      $data[$i]['sub_pendapatan2'] = $this->sub_pendapatan2($id_keuangan_master, $d['Kelompok'], $thn);
    }
    return $data;
  }

  private function get_subval_pendapatan_smt1($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_pendapatan($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_pendapatan_smt1($d['Kelompok'], $thn);
      $data[$i]['realisasi_bunga'] = $this->real_subval_bunga_smt1($d['Kelompok'], $thn);
      $data[$i]['sub_pendapatan2'] = $this->sub_pendapatan2_smt1($id_keuangan_master, $d['Kelompok'], $thn);
    }
    return $data;
  }

  private function get_subval_belanja($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_belanja($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_belanja($d['Kelompok'], $thn);
      $data[$i]['sub_belanja2'] = $this->sub_belanja2($id_keuangan_master, $d['Kelompok'], $thn);
    }
    return $data;
  }

  private function get_subval_belanja_smt1($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_belanja($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_belanja_smt1($d['Kelompok'], $thn);
      $data[$i]['sub_belanja2'] = $this->sub_belanja2_smt1($id_keuangan_master, $d['Kelompok'], $thn);
    }
    return $data;
  }

  private function get_subval_pembiayaan($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $this->db->where('Kelompok', '6.1.');
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_pembiayaan($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_pembiayaan($d['Kelompok'], $thn);
      $data[$i]['sub_pembiayaan2'] = $this->sub_pembiayaan2($id_keuangan_master, $d['Kelompok'], $thn);
    }
    return $data;
  }

  private function get_subval_pembiayaan_smt1($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $this->db->where('Kelompok', '6.1.');
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_pembiayaan($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_pembiayaan_smt1($d['Kelompok'], $thn);
      $data[$i]['sub_pembiayaan2'] = $this->sub_pembiayaan2_smt1($id_keuangan_master, $d['Kelompok'], $thn);
    }
    return $data;
  }

  private function get_subval_pembiayaan_keluar($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $this->db->where('Kelompok', '6.2.');
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_pembiayaan_keluar($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_pembiayaan_keluar($d['Kelompok'], $thn);
      $data[$i]['sub_pembiayaan_keluar2'] = $this->sub_pembiayaan_keluar2($id_keuangan_master, $d['Kelompok'], $thn);
    }
    return $data;
  }

  private function get_subval_pembiayaan_keluar_smt1($id_keuangan_master, $akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $this->db->where('Kelompok', '6.2.');
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_subval_pembiayaan_keluar($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval_pembiayaan_keluar_smt1($d['Kelompok'], $thn);
      $data[$i]['sub_pembiayaan_keluar2'] = $this->sub_pembiayaan_keluar2_smt1($id_keuangan_master, $d['Kelompok'], $thn);
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

  private function real_subval_pendapatan($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
  }

  private function real_subval_pendapatan_smt1($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_tbp', 'keuangan_ta_tbp.No_Bukti = keuangan_ta_tbp_rinci.No_Bukti', 'left');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('keuangan_ta_tbp.Tahun', $thn);
    $this->db->where('keuangan_ta_tbp.Tgl_Bukti >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_tbp.Tgl_Bukti <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_tbp_rinci')->result_array();
  }

  private function real_subval_bunga($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_mutasi')->result_array();
  }

  private function real_subval_bunga_smt1($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
    $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_mutasi')->result_array();
  }

  private function real_subval_belanja($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_subval_belanja_smt1($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_subval_pembiayaan($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Kredit) AS realisasi');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
  }

  private function real_subval_pembiayaan_smt1($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(keuangan_ta_jurnal_umum_rinci.Kredit) AS realisasi');
    $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('keuangan_ta_jurnal_umum.Tahun', $thn);
    $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
  }

  private function real_subval_pembiayaan_keluar($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_subval_pembiayaan_keluar_smt1($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function sub_pendapatan2($id_keuangan_master, $kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_pendapatan2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_pendapatan2($d['Jenis'], $thn);
      $data[$i]['realisasi_bunga'] = $this->real_pendapatan_bunga2($d['Jenis'], $thn);
    }
    return $data;
  }

  private function sub_pendapatan2_smt1($id_keuangan_master, $kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_pendapatan2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_pendapatan2_smt1($d['Jenis'], $thn);
      $data[$i]['realisasi_bunga'] = $this->real_pendapatan_bunga2_smt1($d['Jenis'], $thn);
    }
    return $data;
  }

  private function sub_belanja2($id_keuangan_master,$kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_belanja2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_belanja2($d['Jenis'], $thn);
    }
    return $data;
  }

  private function sub_belanja2_smt1($id_keuangan_master,$kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_belanja2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_belanja2_smt1($d['Jenis'], $thn);
    }
    return $data;
  }

  private function sub_pembiayaan2($id_keuangan_master,$kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', '6.1.');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_pembiayaan2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_pembiayaan2($d['Jenis'], $thn);
    }
    return $data;
  }

  private function sub_pembiayaan2_smt1($id_keuangan_master,$kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', '6.1.');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_pembiayaan2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_pembiayaan2_smt1($d['Jenis'], $thn);
    }
    return $data;
  }

  private function sub_pembiayaan_keluar2($id_keuangan_master,$kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', '6.2.');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_pembiayaan_keluar2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_pembiayaan_keluar2($d['Jenis'], $thn);
    }
    return $data;
  }

  private function sub_pembiayaan_keluar2_smt1($id_keuangan_master,$kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', '6.2.');
    $this->db->where('Kelompok', $kelompok);
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    foreach ($data as $i => $d)
    {
      $data[$i]['anggaran'] = $this->pagu_pembiayaan_keluar2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_pembiayaan_keluar2_smt1($d['Jenis'], $thn);
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

  private function real_pendapatan2($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_tbp_rinci`')->result_array();
  }

  private function real_pendapatan2_smt1($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_tbp', 'keuangan_ta_tbp.No_Bukti = keuangan_ta_tbp_rinci.No_Bukti', 'left');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('keuangan_ta_tbp.Tahun', $thn);
    $this->db->where('keuangan_ta_tbp.Tgl_Bukti >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_tbp.Tgl_Bukti <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_tbp_rinci`')->result_array();
  }

  private function real_pendapatan_bunga2($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_mutasi`')->result_array();
  }

  private function real_pendapatan_bunga2_smt1($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->where('Tgl_Bukti >=', '01/01/$thn 00:00:00');
    $this->db->where('Tgl_Bukti <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_mutasi`')->result_array();
  }

  private function real_belanja2($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_belanja2_smt1($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_pembiayaan2($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Kredit) AS realisasi');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
  }

  private function real_pembiayaan2_smt1($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(keuangan_ta_jurnal_umum_rinci.Kredit) AS realisasi');
    $this->db->join('keuangan_ta_jurnal_umum', 'keuangan_ta_jurnal_umum.NoBukti = keuangan_ta_jurnal_umum_rinci.NoBukti', 'left');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('keuangan_ta_jurnal_umum_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_jurnal_umum.Tanggal >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_jurnal_umum.Tanggal <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Kd_Rincian');
    return $this->db->get('keuangan_ta_jurnal_umum_rinci')->result_array();
  }

  private function real_pembiayaan_keluar2($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function real_pembiayaan_keluar2_smt1($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->join('keuangan_ta_spp', 'keuangan_ta_spp.No_SPP = keuangan_ta_spp_rinci.No_SPP', 'left');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('keuangan_ta_spp_rinci.Tahun', $thn);
    $this->db->where('keuangan_ta_spp.Tgl_SPP >=', '01/01/$thn 00:00:00');
    $this->db->where('keuangan_ta_spp.Tgl_SPP <=', '06/31/$thn 00:00:00');
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_spp_rinci')->result_array();
  }

  private function data_widget_pendapatan($semester = 1, $tahun)
  {
    $raw_data = $this->r_pd('1', $tahun);
    $res_pendapatan = array();
    $tmp_pendapatan = array();
    foreach ($raw_data['jenis_pendapatan'] as $r)
    {
      $tmp_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
    }

    foreach ($raw_data['anggaran'] as $r)
    {
      $tmp_pendapatan[$r['jenis_pendapatan']]['anggaran'] = ($r['Pagu'] ? $r['Pagu'] : 0);
    }

    foreach ($raw_data['realisasi'] as $r)
    {
      $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi'] = ($r['Nilai'] ? $r['Nilai'] : 0);
    }

    foreach ($tmp_pendapatan as $key => $value)
    {
      array_push($res_pendapatan, $value);
    }

    return $res_pendapatan;
  }

  private function data_widget_belanja($semester = 1, $tahun)
  {
    $raw_data = $this->r_bd('1', $tahun);
    $res_belanja = array();
    $tmp_belanja = array();
    foreach ($raw_data['bidang'] as $r)
    {
      $tmp_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
    }

    foreach ($raw_data['anggaran'] as $r)
    {
      $tmp_belanja[$r['Kode_Bid']]['anggaran'] = ($r['Pagu'] ? $r['Pagu'] : 0);
    }

    foreach ($raw_data['realisasi'] as $r)
    {
      $tmp_belanja[$r['Kd_Bid']]['realisasi'] = ($r['Nilai'] ? $r['Nilai'] : 0);
    }

    foreach ($tmp_belanja as $key => $value)
    {
      array_push($res_belanja, $value);
    }

    return $res_belanja;
  }

  private function data_widget_pelaksanaan($semester = 1, $tahun)
  {
    $raw_data = $this->rp_apbd('1', $tahun);

    $res_pelaksanaan = array();
    $nama = array(
      'PENDAPATAN' => '(PA) Pendapatan Desa',
      'BELANJA' => '(PA) Belanja Desa',
      'PEMBIAYAAN' => '(PA) Pembiayaan Desa',
    );

    for ($i = 0; $i < count($raw_data['jenis_belanja']); $i++)
    {
      $row = array(
        'nama' => $nama[$raw_data['jenis_belanja'][$i]['Nama_Akun']],
        'anggaran' => ($raw_data['anggaran'][$i]['AnggaranStlhPAK'] ? $raw_data['anggaran'][$i]['AnggaranStlhPAK'] : 0),
        'realisasi' => ($raw_data['realisasi'][$i]['Nilai'] ? $raw_data['realisasi'][$i]['Nilai'] : 0),
      );
      array_push($res_pelaksanaan, $row);
    }

    return $res_pelaksanaan;
  }

  public function widget_keuangan()
  {
    $data = $this->keuangan_model->list_tahun_anggaran();

    foreach ($data as $tahun)
    {
      $res[$tahun]['res_pelaksanaan'] = $this->data_widget_pelaksanaan('1', $tahun);
      $res[$tahun]['res_pendapatan'] = $this->data_widget_pendapatan('1', $tahun);;
      $res[$tahun]['res_belanja'] = $this->data_widget_belanja('1', $tahun);
    }

    $result = array(
      //Encode ke JSON
      'data' => json_encode($res),
      'tahun' => $this->keuangan_model->list_tahun_anggaran(),
      //Cari tahun anggaran terbaru (terbesar secara value)
      'tahun_terbaru' => $this->keuangan_model->list_tahun_anggaran()[0]
    );

    return $result;
  }

  private function data_keuangan_tema($tahun)
  {
    $data['res_pelaksanaan'] = $this->data_widget_pelaksanaan('1', $tahun);
    $data['res_pelaksanaan']['laporan'] = 'Pelaksanaan Tahun '. $tahun;
    $data['res_pendapatan'] = $this->data_widget_pendapatan('1', $tahun);
    $data['res_pendapatan']['laporan'] = 'Pendapatan Tahun '. $tahun;
    $data['res_belanja'] = $this->data_widget_belanja('1', $tahun);
    $data['res_belanja']['laporan'] = 'Belanja Tahun '. $tahun;

    return $data;
  }

  public function grafik_keuangan_tema($tahun = NULL)
  {
    if (is_null($tahun)) $tahun = date('Y');
    $thn = $this->keuangan_model->list_tahun_anggaran();
    if (empty($thn))
    {
      return null;
    }

    if (!in_array($tahun, $thn))
    {
      $tahun = $thn[0];
    }
    $raw_data = $this->data_keuangan_tema($tahun);
    foreach ($raw_data as $keys => $raws)
    {
      foreach ($raws as $key => $raw)
      {
        if ($key == 'laporan')
        {
          $result['data_widget'][$keys]['laporan'] = $raw;
          continue;
        }
        $data['judul'] = $raw['nama'];
        $data['anggaran'] = $raw['anggaran'];
        $data['realisasi'] = $raw['realisasi'];

        if ($data['anggaran'] != 0 && $data['realisasi'] != 0)
        {
          $data['persen'] = $data['realisasi'] / $data['anggaran'] * 100;
        }
        elseif ($data['realisasi'] != 0)
        {
          $data['persen'] = 100;
        }
        else
        {
          $data['persen'] = 0;
        }
        $data['persen'] = round($data['persen'], 2);

        $result['data_widget'][$keys][] = $data;
      }
    }
    $result['tahun'] = $tahun;
    return $result;
  }
}
