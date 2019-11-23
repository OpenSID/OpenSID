<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Api_informasi_publik extends Api_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('web_dokumen_model');
		$this->load->model('config_model');
		$this->load->model('log_ekspor_model');
		$this->modul_ini = 13;
	}

	public function index()
	{
		redirect('ppid');
	}

	public function ppid()
	{
		$this->log_request();
		$get = $this->input->get();
		$tgl_dari = $get['tgl_dari'];
		if (!empty($tgl_dari) and !validate_date($tgl_dari))
		{
			$json_send = array("status" => "fail",
				"data" => array("tgl_dari" => "tgl_dari harus tanggal dalam format d-m-Y"
				)
			);
		}
		else 
		{
			$jenis_kirim = empty($get['tgl_dari']) ? 'semua' : 'perubahan'; 
			$data = $this->web_dokumen_model->data_ppid($tgl_dari);
			$json_send = array("status" => "success",
				"data" => array("ppid" => $data,
					"tanggal" => date('d-m-Y h:i:s', time()),
					"pengiriman" => $jenis_kirim,
					"tgl_dari" => $tgl_dari,
					"total data" => count($data)
				)
			);
		}		
		header('Content-Type: application/json');
		echo json_encode($json_send);
	}

}

