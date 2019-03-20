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
    $this->modul_ini = 201;
    $this->controller = 'keuangan';
	}

  public function import_data()
  {
    $data['form_action'] = site_url("keuangan/import_data");
    $header = $this->header_model->get_data();
		$nav['act_sub'] = 202;
    $this->load->view('header', $header);
    $this->load->view('nav', $nav);
		$this->load->view('keuangan/import_data', $data);
    $this->load->view('footer');
  }

  public function impor()
	{
		$data['form_action'] = site_url("keuangan/proses_impor");
		$this->load->view('keuangan/impor', $data);
	}

  public function proses_impor()
  {
    print_r($_FILES['keuangan']['tmp_name']);die();
    $this->keuangan_model->impor($_FILES['keuangan']['tmp_name']);

    redirect('keuangan/import_data');
  }
}
