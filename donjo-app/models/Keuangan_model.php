<?php
class Keuangan_model extends CI_model {

  private $zip_file = '';
  private $id_keuangan_master = NULL;
  private $data_siskeudes = array(
    'keuangan_ref_bank_desa' => 'Ref_Bank_Desa.csv',
    'keuangan_ref_bel_operasional' => 'Ref_Bel_Operasional.csv',
    'keuangan_ref_bidang' => 'Ref_Bidang.csv',
    'keuangan_ref_bunga' => 'Ref_Bunga.csv',
    'keuangan_ref_desa' => 'Ref_Desa.csv',
    'keuangan_ref_kecamatan' => 'Ref_Kecamatan.csv',
    'keuangan_ref_kegiatan' => 'Ref_Kegiatan.csv',
    'keuangan_ref_korolari' => 'Ref_Korolari.csv',
    'keuangan_ref_neraca_close' => 'Ref_NeracaClose.csv',
    'keuangan_ref_perangkat' => 'Ref_Perangkat.csv',
    'keuangan_ref_potongan' => 'Ref_Potongan.csv',
    'keuangan_ref_rek1' => 'Ref_Rek1.csv',
    'keuangan_ref_rek2' => 'Ref_Rek2.csv',
    'keuangan_ref_rek3' => 'Ref_Rek3.csv',
    'keuangan_ref_rek4' => 'Ref_Rek4.csv',
    'keuangan_ref_sbu' => 'Ref_SBU.csv',
    'keuangan_ref_sumber' => 'Ref_Sumber.csv',
    'keuangan_ta_anggaran' => 'Ta_Anggaran.csv',
    'keuangan_ta_anggaran_log' => 'Ta_AnggaranLog.csv',
    'keuangan_ta_anggaran_rinci' => 'Ta_AnggaranRinci.csv',
    'keuangan_ta_bidang' => 'Ta_Bidang.csv',
    'keuangan_ta_jurnal_umum' => 'Ta_JurnalUmum.csv',
    'keuangan_ta_jurnal_umum_rinci' => 'Ta_JurnalUmumRinci.csv',
    'keuangan_ta_kegiatan' => 'Ta_Kegiatan.csv',
    'keuangan_ta_mutasi' => 'Ta_Mutasi.csv',
    'keuangan_ta_pajak' => 'Ta_Pajak.csv',
    'keuangan_ta_pajak_rinci' => 'Ta_PajakRinci.csv',
    'keuangan_ta_pemda' => 'Ta_Pemda.csv',
    'keuangan_ta_pencairan' => 'Ta_Pencairan.csv',
    'keuangan_ta_perangkat' => 'Ta_Perangkat.csv',
    'keuangan_ta_rab' => 'Ta_RAB.csv',
    'keuangan_ta_rab_rinci' => 'Ta_RABRinci.csv',
    'keuangan_ta_rab_sub' => 'Ta_RABSub.csv',
    'keuangan_ta_rpjm_bidang' => 'Ta_RPJM_Bidang.csv',
    'keuangan_ta_rpjm_kegiatan' => 'Ta_RPJM_Kegiatan.csv',
    'keuangan_ta_rpjm_misi' => 'Ta_RPJM_Misi.csv',
    'keuangan_ta_rpjm_pagu_indikatif' => 'Ta_RPJM_Pagu_Indikatif.csv',
    'keuangan_ta_rpjm_pagu_tahunan' => 'Ta_RPJM_Pagu_Tahunan.csv',
    'keuangan_ta_rpjm_sasaran' => 'Ta_RPJM_Sasaran.csv',
    'keuangan_ta_rpjm_tujuan' => 'Ta_RPJM_Tujuan.csv',
    'keuangan_ta_rpjm_visi' => 'Ta_RPJM_Visi.csv',
    'keuangan_ta_saldo_awal' => 'Ta_SaldoAwal.csv',
    'keuangan_ta_spj' => 'Ta_SPJ.csv',
    'keuangan_ta_spjpot' => 'Ta_SPJPot.csv',
    'keuangan_ta_spj_bukti' => 'Ta_SPJBukti.csv',
    'keuangan_ta_spj_rinci' => 'Ta_SPJRinci.csv',
    'keuangan_ta_spj_sisa' => 'Ta_SPJSisa.csv',
    'keuangan_ta_spp' => 'Ta_SPP.csv',
    'keuangan_ta_sppbukti' => 'Ta_SPPBukti.csv',
    'keuangan_ta_spppot' => 'Ta_SPPPot.csv',
    'keuangan_ta_spp_rinci' => 'Ta_SPPRinci.csv',
    'keuangan_ta_sts' => 'Ta_STS.csv',
    'keuangan_ta_sts_rinci' => 'Ta_STSRinci.csv',
    'keuangan_ta_tbp' => 'Ta_TBP.csv',
    'keuangan_ta_tbp_rinci' => 'Ta_TBPRinci.csv',
    'keuangan_ta_triwulan' => 'Ta_Triwulan.csv',
    'keuangan_ta_triwulan_rinci' => 'Ta_TriwulanArsip.csv'
  );

  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('donjolib');
    $this->load->helper('pict_helper');
    $this->uploadConfig = array(
      'upload_path' => LOKASI_KEUANGAN_ZIP,
      'allowed_types' => 'zip',
      'max_size' => max_upload()*1024,
    );
  }

  // $file = nama file yg akan diproses
  private function extract_file($file)
  {
    $data = $this->get_csv($this->zip_file, $file);
    $count = count($data);
    for ($i=0; $i<$count; $i++)
    {
      if (empty($data[$i]) or !array_filter($data[$i]))
        unset($data[$i]);
      else
        $data[$i]['id_keuangan_master'] = $this->id_keuangan_master;
    }
    return $data;
  }

  /**
    https://stackoverflow.com/questions/7391969/in-memory-download-and-extract-zip-archive
    https://www.php.net/manual/en/function.str-getcsv.php
    https://bugs.php.net/bug.php?id=55763

    Contoh yg dihasilkan:

    Array
    (
        [0] => Array
            (
                [Kd_Bid] => 01
                [Nama_Bidang] => Bidang Penyelenggaraan Pemerintah Desa
            )

        [1] => Array
            (
                [Kd_Bid] => 02
                [Nama_Bidang] => Bidang Pelaksanaan Pembangunan Desa
            )
    )
  */
  private function get_csv($zip_file, $file_in_zip)
  {
    # read the file's data:
    $path = sprintf('zip://%s#%s', $zip_file, $file_in_zip);
    $file_data = file_get_contents($path);
    $file_data = preg_split('/[\r\n]{1,2}(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $file_data);
    $csv = array_map('str_getcsv', $file_data);
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv); # remove column header
    return($csv);
  }

  private function get_versi_database()
  {
    $csv_versi = $this->get_csv($this->zip_file, 'Ref_Version.csv');
    if ($csv_versi)
      return $csv_versi[0]['Versi'];
    else
      return false;
  }

  private function get_tahun_anggaran()
  {
    $csv_anggaran = $this->get_csv($this->zip_file, 'Ta_Anggaran.csv');
    if ($csv_anggaran)
      return $csv_anggaran[0]['Tahun'];
    else
      return false;
  }

  private function get_keuangan_master()
  {
    $this->zip_file = $_FILES['keuangan']['tmp_name'];
    if (!empty($this->id_keuangan_master)) return;

    $data_master = array(
      'versi_database' => $this->get_versi_database(),
      'tahun_anggaran' => $this->get_tahun_anggaran(),
      'tanggal_impor' => date("Y-m-d"),
      'aktif' => 1
    );
    $this->db->insert('keuangan_master', $data_master);
    $this->id_keuangan_master = $this->db->insert_id();
  }

  public function extract()
  {
    $_SESSION['success'] = 1;
    $this->get_keuangan_master();

    foreach ($this->data_siskeudes as $tabel_opensid => $file_siskeudes)
    {
      $data_tabel_siskeudes = $this->extract_file($file_siskeudes);
      if (!empty($data_tabel_siskeudes))
      {
        if (!$this->db->insert_batch($tabel_opensid, $data_tabel_siskeudes))
          $_SESSION['success'] = -1;
      }
    }
  }

  public function extract_update()
  {
    $this->id_keuangan_master = (int)str_replace('"', "", $_POST["id_keuangan_master"]);
    $tables = array_keys($this->data_siskeudes);
    $this->db->where('id_keuangan_master', $this->id_keuangan_master);
    $this->db->delete($tables);
    $this->extract();
    $this->db->where('id', $this->id_keuangan_master)
      ->update('keuangan_master', array('tanggal_impor' => date("Y-m-d")));
  }

  private function cek_file_valid()
  {
    return $this->get_versi_database() and $this->get_tahun_anggaran();
  }

  public function cek_keuangan_master($file)
  {
    $this->upload->initialize($this->uploadConfig);
    $this->zip_file = $_FILES['keuangan']['tmp_name'];
    if (!$this->cek_file_valid())
    {
      return -1;
    }
    $this->db->where('versi_database', $this->get_versi_database());
    $this->db->where('tahun_anggaran', $this->get_tahun_anggaran());
    $result = $this->db->get('keuangan_master')->row();
    return $result;
  }

  public function list_data()
  {
    $data = $this->db->select('*')->order_by('tanggal_impor')->get('keuangan_master')->result_array();
    for ($i=0; $i<count($data); $i++)
    {
      $data[$i]['no'] = $i + 1;
    }
    return $data;
  }

  public function tahun_anggaran()
  {
    $data = $this->db->select('*')->get('keuangan_master')->row();
    return $data->tahun_anggaran;
  }

  public function data_id_keuangan_master()
  {
    $data = $this->db->select('*')->order_by('tanggal_impor')->get('keuangan_master')->row();
    return $data->id;
  }

  public function data_anggaran($id_keuangan_master)
  {
    $this->db->select_sum('Anggaran');
    $this->db->select_sum('AnggaranPAK');
    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $result = $this->db->get('keuangan_ta_anggaran')->row();
    return $result;
  }

  public function pendapatan_desa($id_keuangan_master)
  {
    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $this->db->like('KD_Rincian', '4.1.', 'after');
    $result = $this->db->get('keuangan_ta_anggaran')->row();
    return $result;
  }

  public function realisasi_pendapatan_desa($id_keuangan_master)
  {
    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('id_keuangan_master', $id_keuangan_master);
    $this->db->like('Kd_Rincian', '4.1.', 'after');
    $result = $this->db->get('keuangan_ta_rab')->row();
    return $result;
  }

  public function widget_keuangan()
  {
    $this->db->where('aktif', 1);
    $keuangan_master = $this->db->select('*')->get('keuangan_master')->row();

    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('id_keuangan_master', $keuangan_master->id);
    $this->db->like('KD_Rincian', '4.1.', 'after');
    $pendapatan_desa = $this->db->get('keuangan_ta_anggaran')->row();

    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('id_keuangan_master', $keuangan_master->id);
    $this->db->like('Kd_Rincian', '4.1.', 'after');
    $realisasi_pendapatan_desa = $this->db->get('keuangan_ta_rab')->row();

    $data['realisasi_pendapatan_desa'] = $realisasi_pendapatan_desa->AnggaranStlhPAK;
    $data['pendapatan_desa'] = $pendapatan_desa->AnggaranStlhPAK;
    $data['tahun_anggaran'] = $keuangan_master->tahun_anggaran;
    return $data;
  }

  // Post Format Transparansi Anggaran Data
  // Query Grafik
  public function rp_apbd($smt, $thn)
  {
    $this->db->select('Akun, Nama_Akun');
    $this->db->where("Akun = '4.' OR Akun = '5.' OR Akun = '6.'");
    $data['jenis_belanja'] = $this->db->get('keuangan_ref_rek1')->result_array();

    $this->db->select("LEFT(Kd_Rincian, 2) AS jenis_belanja");
    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('jenis_belanja');
    $data['anggaran'] = $this->db->get('keuangan_ta_anggaran_rinci')->result_array();

    $this->db->select('Akun');  
    $this->db->select_sum('Nilai');  
    $this->db->join('keuangan_ta_spj_rinci', 'LEFT(keuangan_ta_spj_rinci.Kd_Rincian, 2) = keuangan_ref_rek1.Akun', 'left');
    $this->db->where("Akun = '4.' OR Akun = '5.' OR Akun = '6.'");
    $this->db->where('keuangan_ta_spj_rinci.Tahun', $thn);
    $this->db->group_by('Akun');
    $this->db->order_by('Akun', 'asc');
    $data['realisasi'] = $this->db->get('keuangan_ref_rek1')->result_array();

    return $data;
  }

  public function r_pd($smt, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->like('Kelompok', '4.', 'after');
    $data['jenis_pendapatan'] = $this->db->get('keuangan_ref_rek3')->result_array();

    $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(AnggaranStlhPAK) AS Pagu');
    $this->db->like('Kd_Rincian', '4.', 'after');
    $this->db->order_by('jenis_pendapatan', 'asc');
    $this->db->group_by('jenis_pendapatan');
    $this->db->where('Tahun', $thn);
    $data['anggaran'] = $this->db->get('keuangan_ta_anggaran_rinci')->result_array();

    $this->db->select('LEFT(keuangan_ta_anggaran_rinci.Kd_Rincian, 6) AS jenis_pendapatan, SUM(Nilai) AS Nilai');
    $this->db->join('keuangan_ta_spj_rinci', 'keuangan_ta_spj_rinci.Kd_Rincian = keuangan_ta_anggaran_rinci.Kd_Rincian', 'left');
    $this->db->like('keuangan_ta_anggaran_rinci.Kd_Rincian', '4.', 'after');
    $this->db->order_by('jenis_pendapatan', 'asc');
    $this->db->group_by('jenis_pendapatan');
    $this->db->where('keuangan_ta_anggaran_rinci.Tahun', $thn);
    $data['realisasi'] = $this->db->get('keuangan_ta_anggaran_rinci')->result_array();

    return $data;
  }

  public function r_bd($smt, $thn)
  {
    $this->db->select('Kd_Bid, Nama_Bidang');
    $this->db->where('Tahun', $thn);
    $this->db->order_by('Kd_Bid', 'asc');
    $data['bidang'] = $this->db->get('keuangan_ta_bidang')->result_array();

    $this->db->select('keuangan_ta_bidang.Kd_Bid');
    $this->db->select_sum('Pagu');
    $this->db->join('keuangan_ta_kegiatan', 'keuangan_ta_kegiatan.Kd_Bid = keuangan_ta_bidang.Kd_Bid', 'left');
    $this->db->group_by('keuangan_ta_bidang.Kd_Bid');
    $this->db->order_by('keuangan_ta_bidang.Kd_Bid', 'asc');
    $this->db->where('keuangan_ta_bidang.Tahun', $thn);
    $data['anggaran'] = $this->db->get('keuangan_ta_bidang')->result_array();

    $this->db->select("keuangan_ta_kegiatan.Kd_Bid");
    $this->db->select_sum('Nilai');
    $this->db->join('keuangan_ta_kegiatan', 'keuangan_ta_kegiatan.Kd_Bid = keuangan_ta_bidang.Kd_Bid', 'left');
    $this->db->join('keuangan_ta_spj_rinci', 'keuangan_ta_spj_rinci.Kd_Keg = keuangan_ta_kegiatan.Kd_Keg', 'left');  
    $this->db->join('keuangan_ta_spj', 'keuangan_ta_spj.No_Spj = keuangan_ta_spj_rinci.No_Spj', 'left');    
    $this->db->group_by('keuangan_ta_kegiatan.Kd_Bid');
    $this->db->order_by('keuangan_ta_kegiatan.Kd_Bid', 'asc');
    $this->db->where('keuangan_ta_bidang.Tahun', $thn);
    $data['realisasi'] = $this->db->get('keuangan_ta_bidang')->result_array();
    return $data;
  }

  public function r_pembiayaan($smt, $thn)
  {
    $this->db->select('Nama_Kelompok');
    $this->db->like('Kelompok', '6.', 'after');
    $data['pembiayaan'] = $this->db->get('keuangan_ref_rek2')->result_array();

    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(AnggaranStlhPAK) AS Pagu');
    $this->db->like('LEFT(Kd_Rincian, 2)', '6.', 'after');
    $this->db->group_by('Kelompok');
    $this->db->order_by('Kelompok', 'asc');
    $data['anggaran'] = $this->db->get('keuangan_ta_anggaran_rinci')->result_array();

    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->like('LEFT(Kd_Rincian, 2)', '6.', 'after');
    $this->db->group_by('Kelompok');
    $this->db->order_by('Kelompok', 'asc');
    $data['realisasi'] = $this->db->get('keuangan_ta_spj_rinci')->result_array();

    return $data;
  }

  //Query Laporan Pelaksanaan Realisasi
  public function lap_rp_apbd($smt, $thn)
  {
    $this->db->select('Akun, Nama_Akun');
    $this->db->where("Akun = '4.' OR Akun = '5.' OR Akun = '6.'");
    $data['laporan'] = $this->db->get('keuangan_ref_rek1')->result_array();
    $i=0;
    foreach ($data['laporan'] as $p) 
    {
      $data['laporan'][$i]['total_anggaran'] = $this->total_anggaran($thn);
      $data['laporan'][$i]['total_realisasi'] = $this->total_realisasi($thn);
      $data['laporan'][$i]['anggaran'] = $this->pagu_akun($p['Akun'], $thn);
      $data['laporan'][$i]['realisasi'] = $this->real_akun($p['Akun'], $thn);
      $data['laporan'][$i]['sub_pendapatan'] = $this->getSubVal($p['Akun'], $thn);
      $i++;
    }
    return $data;
  }

  private function total_anggaran($thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(AnggaranStlhPAK) AS pagu');
    $this->db->where('Tahun', $thn);
    return $this->db->get('keuangan_ta_anggaran_rinci')->result_array();
  }

  private function total_realisasi($thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->where('Tahun', $thn);
    return $this->db->get('keuangan_ta_spj_rinci')->result_array();
  }

  private function pagu_akun($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(AnggaranStlhPAK) AS pagu');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_anggaran_rinci')->result_array();
  }

  private function real_akun($akun, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $akun, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Akun');
    return $this->db->get('keuangan_ta_spj_rinci')->result_array();
  }

  private function getSubVal($akun, $thn)
  {
    $this->db->select('Kelompok, Nama_Kelompok');
    $this->db->where('Akun', $akun);
    $data = $this->db->get('keuangan_ref_rek2')->result_array();
    $i=0;
    foreach ($data as $d) 
    {
      $data[$i]['anggaran'] = $this->pagu_subval($d['Kelompok'], $thn);
      $data[$i]['realisasi'] = $this->real_subval($d['Kelompok'], $thn);
      $data[$i]['sub_pendapatan2'] = $this->sub_pendapatan2($d['Kelompok'], $thn);
      $i++;
    }
    return $data;
  }

  private function pagu_subval($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(AnggaranStlhPAK) AS pagu');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_anggaran_rinci')->result_array();
  }

  private function real_subval($kelompok, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $kelompok, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Kelompok');
    return $this->db->get('keuangan_ta_spj_rinci')->result_array();
  }

  private function sub_pendapatan2($kelompok, $thn)
  {
    $this->db->select('Kelompok, Jenis, Nama_Jenis');
    $this->db->where('Kelompok', $kelompok);
    $data = $this->db->get('keuangan_ref_rek3')->result_array();
    $i=0;
    foreach ($data as $d) 
    {
      $data[$i]['anggaran'] = $this->pagu_pendapatan2($d['Jenis'], $thn);
      $data[$i]['realisasi'] = $this->real_pendapatan2($d['Jenis'], $thn);
      $i++;
    }
    return $data;
  }

  private function pagu_pendapatan2($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(AnggaranStlhPAK) AS pagu');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_anggaran_rinci')->result_array();
  }

  private function real_pendapatan2($jenis, $thn)
  {
    $this->db->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai) AS realisasi');
    $this->db->like('Kd_Rincian', $jenis, 'after');
    $this->db->where('Tahun', $thn);
    $this->db->group_by('Jenis');
    return $this->db->get('keuangan_ta_spj_rinci')->result_array();
  }
}
