<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan extends Admin_Controller {

  public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('keuangan_model');
		$this->load->model('header_model');
		$this->modul_ini = 201;
	}

  public function laporan()
  {
    $data['tahun_anggaran'] = $this->keuangan_model->tahun_anggaran();
    $data['id_keuangan_master'] = $this->keuangan_model->data_id_keuangan_master();
    $data['data_anggaran'] = $this->keuangan_model->data_anggaran($data['id_keuangan_master']);
    $data['pendapatan_desa'] = $this->keuangan_model->pendapatan_desa($data['id_keuangan_master']);
    $data['realisasi_pendapatan_desa'] = $this->keuangan_model->realisasi_pendapatan_desa($data['id_keuangan_master']);
    // print_r($data['pendapatan_desa']);die();
    $header = $this->header_model->get_data();
    $nav['act_sub'] = 203;
    $this->load->view('header', $header);
    $this->load->view('nav', $nav);
    $this->load->view('keuangan/laporan',$data);
    $this->load->view('footer');
  }

  public function grafik($jenis)
  {
    $header = $this->header_model->get_data();
    $nav['act_sub'] = 203;
    $this->load->view('header', $header);
    $this->load->view('nav', $nav);
    $smt = '1';
    $thn = '2016';
    if ($jenis == 'grafik-R-PD')
    {
      $data = $this->keuangan_model->r_pd($smt, $thn);
      $jp = array();
      foreach ($data['jenis_pendapatan'] as $b)
      {
        $jp[] = "'". $b['Nama_Jenis']. "'";
      }
      $anggaran = array();
      foreach ($data['anggaran'] as $a)
      {
        $anggaran[] = $a['Pagu'];
      }
      $realisasi = array();
      foreach ($data['realisasi'] as $r)
      {
        if(!empty($r['Nilai']) || !is_null($r['Nilai']))
        {
          $realisasi[] =  $r['Nilai'];
        }
        else
        {
          $realisasi[] =  0;
        }
      }
      $data_chart = array(
        'type' => $jenis,
        'smt' => $smt,
        'thn' => $thn,
        'jp' => $jp,
        'anggaran' => $anggaran,
        'realisasi' => $realisasi
      );
      $this->load->view('keuangan/grafik_r_pd', $data_chart);
    }
    $this->load->view('footer');
  }

  public function impor_data()
  {
  	$data['main'] = $this->keuangan_model->list_data();
    $data['form_action'] = site_url("keuangan/proses_impor");
    $header = $this->header_model->get_data();
		$nav['act_sub'] = 202;
    $this->load->view('header', $header);
    $this->load->view('nav', $nav);
		$this->load->view('keuangan/impor_data', $data);
    $this->load->view('footer');
  }

  public function proses_impor()
  {
    if (empty($_FILES['keuangan']['name']))
    {
      $this->session->success = -1;
      $this->session->error_msg = "Tidak ada file untuk diimpor";
      redirect('keuangan/impor_data');
    }
    if ($_POST['jenis_impor'] == 'update')
    {
      $this->keuangan_model->extract_update();
    }
    else
    {
      $this->keuangan_model->extract();
    }
    redirect('keuangan/impor_data');
  }

  public function cek_versi_database()
  {
    $nama = $_FILES['keuangan'];
    $file_parts = pathinfo($nama['name']);
    if ($file_parts['extension'] === 'zip')
    {
      $cek = $this->keuangan_model->cek_keuangan_master($nama);
      if ($cek == -1)
      {
        echo json_encode(2);
      }
      else if ($cek)
      {
        $output =array('id' => $cek->id, 'tahun_anggaran' => $cek->tahun_anggaran);
        echo json_encode($output);
      }
      else
      {
        echo json_encode(0);
      }
    }
    else
    {
      echo json_encode(1);
    }
  }

}
