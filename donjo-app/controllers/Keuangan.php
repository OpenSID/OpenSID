<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan extends CI_Controller {

  public function __construct()
	{
    parent::__construct();
    session_start();
    $this->load->model('user_model');
    $grup = $this->user_model->sesi_grup($_SESSION['sesi']);
    if ($grup != 1 AND $grup != 2)
    {
      if (empty($grup))
        $_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
      else
        unset($_SESSION['request_uri']);
      redirect('siteman');
    }

    $this->load->model('header_model');
    $this->load->model('keuangan_model');
    $this->modul_ini = 201;
    $this->controller = 'keuangan';
	}

  public function widget()
  {
    $header = $this->header_model->get_data();
    $nav['act_sub'] = 203;
    $this->load->view('header', $header);
    $this->load->view('nav', $nav);
    $this->load->view('keuangan/widget');
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
    $inputFiles = $_FILES['keuangan']['tmp_name'];
    $ekstensi_diperbolehkan	= array('mde');
    $nama = $_FILES['keuangan']['name'];
    $x = explode('.', $nama);
    $ekstensi = strtolower(end($x));
    $ukuran	= $_FILES['keuangan']['size'];
    $file_tmp = $_FILES['keuangan']['tmp_name'];

    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
      if (move_uploaded_file($file_tmp, 'upload/keuangan/'.$nama)) {
        $file = 'upload/keuangan/'.$nama;
        $this->keuangan_model->convertMDE($file);
        redirect('keuangan/import_data');
      }
    }else{
      echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
    }
  }
}
