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

  public function widget()
  {
    $data['tahun_anggaran'] = $this->keuangan_model->tahun_anggaran();
    $data['anggaran_keuangan'] = $this->keuangan_model->anggaran_keuangan();
    $data['anggaranPAK'] = $this->keuangan_model->anggaranPAK();
    $data['anggaranStlhPAK'] = $this->keuangan_model->anggaranStlhPAK();
    $data['data_grafik'] = $this->keuangan_model->data_grafik();
    $header = $this->header_model->get_data();
    $nav['act_sub'] = 203;
    $this->load->view('header', $header);
    $this->load->view('nav', $nav);
    $this->load->view('keuangan/widget',$data);
    $this->load->view('footer');
  }

  public function import_data()
  {
    $data['form_action'] = site_url("keuangan/proses_impor");
    $header = $this->header_model->get_data();
		$nav['act_sub'] = 202;
    $this->load->view('header', $header);
    $this->load->view('nav', $nav);
		$this->load->view('keuangan/import_data', $data);
    $this->load->view('footer');
  }

  public function proses_impor()
  {
    // print_r($_FILES);die();
    $data['versi_database'] = $_POST['versi_database'];
    $data['tahun_anggaran'] = $_POST['tahun_anggaran'];
    $inputFiles = $_FILES['keuangan']['tmp_name'];
    $ekstensi_diperbolehkan	= array('zip');
    $nama = $_FILES['keuangan'];

    if($_FILES['keuangan']['name'] !='')
            			{
                    $this->keuangan_model->extract($nama);
                  }
            		        	                
  }

  public function cekVersiDatabase()
  {
    $cek = $this->keuangan_model->cekMasterKeuangan('V'.$_POST['versi_database'],$_POST['tahun_anggaran']);
    if ($cek) {
      echo json_encode(1);
    }else{
      echo json_encode(0);
    }
  }
}
