<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bumindes_umum extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->modul_ini = 301;
	}

	public function index()
	{
		redirect('dokumen_sekretariat/peraturan_desa/3');
	}

}
