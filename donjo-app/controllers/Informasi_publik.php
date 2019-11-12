<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Informasi_publik extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_dokumen_model');
		$this->load->model('config_model');
		$this->load->model('pamong_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 13;
	}

	public function ekspor()
	{
		$data['form_action'] = site_url("informasi_publik/ekspor_csv");
		$this->load->view('dokumen/dialog_ekspor', $data);
	}

	public function ekspor_csv()
	{
		$filename = 'informasi_publik_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; ");

		// Gunakan file temporer
    $tmpfname = tempnam(sys_get_temp_dir(),'');
		$file = fopen($tmpfname, "w");;

		// Ambil data 
		$data = $this->web_dokumen_model->ekspor_informasi_publik();

		$header = array_keys($data[0]);
		fputcsv($file, $header);
		foreach ($data as $baris){
			fputcsv($file, array_values($baris));
		}
		fclose($file);
    readfile($tmpfname);
	}
}

