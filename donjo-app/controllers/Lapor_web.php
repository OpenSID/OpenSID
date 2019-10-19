<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lapor_web extends Web_Controller
{

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('lapor_model');
		$this->load->model('config_model');
	}

	/**
	 * Kirim laporan pengguna layanan mandiri
	 *
	 * Diakses dari web untuk pengguna layanan mandiri
	 * Tidak memerlukan login pengguna modul admin
	 */
	public function insert()
	{
		if ($_SESSION['mandiri'] != 1) {
			redirect('first');
		}

		$_SESSION['success'] = 1;
		$res = $this->lapor_model->insert();
		$data['data_config'] = $this->config_model->get_data();
		// cek kalau berhasil disimpan dalam database
		if ($res) {
			//$this->session->set_flashdata('flash_message', 'Laporan anda telah berhasil dikirim dan akan segera diproses.');
			$message = 'Laporan anda telah berhasil dikirim dan akan segera diproses.';
			$sukses = 1;
		} else {
			$_SESSION['post'] = $_POST;
			if (!empty($_SESSION['validation_error'])) {
				$this->session->set_flashdata('flash_message', validation_errors());
				$message = 'Bidang Laporan dibutuhkan';
				$sukses = 0;
			} else {
				//	$this->session->set_flashdata('flash_message', 'Laporan anda gagal dikirim. Silakan ulangi lagi.');
				$message = 'Laporan anda gagal dikirim. Silakan ulangi lagi.';
				$sukses = 0;
			}
		}

		$respon = [
			'sukses' => $sukses,
			'message' => $message
		];

		echo json_encode($respon);
	}
}
