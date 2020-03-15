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
		if ($_SESSION['mandiri'] != 1) 
		{
			redirect('first');
		}
		$_SESSION['success'] = 1;
		$res = $this->lapor_model->insert();
		// cek kalau berhasil disimpan dalam database
		if ($res) 
		{
			$sukses = 1;
			$pesan = 'Laporan anda telah berhasil dikirim dan akan segera diproses.';
		} 
		else 
		{
			$_SESSION['post'] = $_POST;
			if (!empty($_SESSION['validation_error'])) 
			{
				$sukses = 0;
				$pesan = validation_errors();
			} 
			else
			{
				$sukses = 0;
				$pesan = 'Laporan anda gagal dikirim. Silakan ulangi lagi.';
			}
		}
		$respon = [
			'sukses' => $sukses,
			'pesan' => $pesan
		];

		echo json_encode($respon);
	}
}
