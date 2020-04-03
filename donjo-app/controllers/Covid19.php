<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Covid19 extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('covid19_model');
		$this->modul_ini = 206;
	}

	
	public function index($p = 1)
	{
		if (isset($_POST['per_page']))
			$_SESSION['per_page'] = $_POST['per_page'];

		$data = $this->covid19_model->get_rincian($p);
		$data['per_page'] = $_SESSION['per_page'];

		$nav['act'] = 206;
		$header = $this->header_model->get_data();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('covid19/pendataan', $data);
		$this->load->view('footer');
	}

	public function form_terdata()
	{
		
		$data['list_penduduk'] = $this->covid19_model->list_penduduk();

		if (isset($_POST['terdata']))
		{
			$data['individu'] = $this->covid19_model->get_terdata($_POST['terdata']);
		}
		else
		{
			$data['individu'] = NULL;
		}
		$nav['act'] = 206;
		$header = $this->header_model->get_data();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);

		$data['form_action'] = site_url("covid19/add_terdata");
		$this->load->view('covid19/form_terdata', $data);
		$this->load->view('footer');
	}

	public function add_terdata()
	{
		$this->covid19_model->add_terdata($_POST);
		redirect("covid19");
	}

}
