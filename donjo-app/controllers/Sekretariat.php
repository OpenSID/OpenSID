<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Sekretariat extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('header_model');
		$this->modul_ini = 15;
	}

	function index(){
		redirect('surat_masuk/clear');
	}

}
