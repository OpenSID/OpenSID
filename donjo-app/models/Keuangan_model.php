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

  // Daftar tahun anggaran tersimpan, diurut tahun terkini di atas
  public function list_tahun_anggaran()
  {
    $data = $this->db->select('tahun_anggaran')
      ->order_by('tahun_anggaran DESC')
      ->get('keuangan_master')->result_array();
    return array_column($data, 'tahun_anggaran');
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

  public function data_anggaran_tahun($thn)
  {
    $this->db->select_sum('Anggaran');
    $this->db->select_sum('AnggaranPAK');
    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('Tahun', $thn);
    $result = $this->db->get('keuangan_ta_anggaran')->row();
    return $result;
  }

  public function data_grafik_utama($thn)
  {
    $this->db->select_sum('AnggaranStlhPAK');
    $this->db->where('Tahun', $thn);
    $result['anggaran'] = $this->db->get('keuangan_ta_anggaran')->row();

    $this->db->select_sum('Nilai');
    $this->db->where('Tahun', $thn);
    $result['realisasi'] = $this->db->get('keuangan_ta_spj_rinci')->row();
    
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

  public function artikel_statis_keuangan()
  {
    $this->db->select('id, judul');
    $this->db->where(array(
      'id_kategori' => 1001
    ));
    $results = $this->db->get('artikel')->result_array();
    $link = array();
    foreach ($results as $result)
    {
      $link['artikel/'.$result['id']] = $result['judul'];
    }
    return $link;
  }
}