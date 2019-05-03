<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sekretariat extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->modul_ini = 15;
	}

	public function index()
	{
		redirect('surat_masuk/clear');
	}

}
