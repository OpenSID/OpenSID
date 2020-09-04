<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Informasi_publik extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('web_dokumen_model');
		$this->load->model('config_model');
		$this->load->model('log_ekspor_model');
	}

	public function ekspor()
	{
		$data['form_action'] = site_url("informasi_publik/ekspor_csv");
		$data['log_semua'] = $this->log_ekspor_model->log_terakhir('informasi_publik', 1);
		$data['log_perubahan'] = $this->log_ekspor_model->log_terakhir('informasi_publik', 2);
		$this->load->view('dokumen/dialog_ekspor', $data);
	}

	public function ekspor_csv()
	{
		$filename = 'informasi_publik_'.date('Ymd').'.csv';
		// Gunakan file temporer
		$tmpfname = tempnam(sys_get_temp_dir(),'');
		// Siapkan daftar berkas untuk dimasukkan ke zip
		$berkas = array();
		$berkas[] = array(
			'nama' => $filename,
			'file' => $tmpfname
		);
		// Folder untuk berkas dokumen dalam zip
		$berkas[] = array(
			'nama' => 'dir',
			'file' => 'berkas'
		);

		// Ambil data dan berkas infoemasi publik
		$file = fopen($tmpfname, "w");;
		$data = $this->web_dokumen_model->ekspor_informasi_publik($this->input->post('data_ekspor'), $this->input->post('tgl_dari'));

		$header = array_keys($data[0]);
		fputcsv($file, $header);
		foreach ($data as $baris){
			fputcsv($file, array_values($baris));
			// Masukkan berkas ke dalam folder dalam zip
			$berkas[] = array(
				'nama' => 'berkas/' . $baris['satuan'],
				'file' => FCPATH . LOKASI_DOKUMEN . $baris['satuan']
			);
		}
		fclose($file);

		// Tulis log ekspor
		$log = array(
			'kode_ekspor' => 'informasi_publik',
			'semua' => $this->input->post('data_ekspor'),
			'total' => count($data)
		);
		$this->log_ekspor_model->tulis_log($log);

		# Masukkan semua berkas ke dalam zip
		$berkas_zip = masukkan_zip($berkas);
		# Unduh berkas zip
		$data = $this->config_model->get_data();
		header("Content-Description: File Transfer");
		header('Content-disposition: attachment; filename=informasi_publik_' . $data['kode_desa'] . '_' . date("d-m-Y") . '.zip');
		header('Content-type: application/zip');
		readfile($berkas_zip);
  }
}