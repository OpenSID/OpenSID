<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Notif extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('notif_model');
	}

	public function permohonan_surat()
	{
		$j = $this->notif_model->permohonan_surat_baru();
		if ($j > 0)
		{
			echo $j;
		}
	}

	public function komentar()
	{
		$j = $this->notif_model->komentar_baru();
		if ($j > 0)
		{
			echo $j;
		}
	}

	public function inbox()
	{
		$j = $this->notif_model->inbox_baru();
		if ($j > 0)
		{
			echo $j;
		}
	}
}