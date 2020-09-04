<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('wilayah_model');
	}

	public function list_rw($dusun = '')
	{
		$list_rw = $this->wilayah_model->list_rw($dusun);
		echo json_encode($list_rw);
	}

	public function list_rt($dusun = '', $rw = '-')
	{
		$list_rt = $this->wilayah_model->list_rt($dusun, $rw);
		echo json_encode($list_rt);
	}
}
