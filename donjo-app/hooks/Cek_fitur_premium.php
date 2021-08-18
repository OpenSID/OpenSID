<?php

use Esyede\Curly;

require_once APPPATH . '/libraries/Curly.php';

class Cek_fitur_premium
{
	/** @var CI_Controller */
	protected $ci;

	/** @var \Esyede\Curly */
	protected $client;

	/**
	 * Constructor Cek fitur premium
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->ci = get_instance();
		$this->client = new Curly();
	}

	/**
	 * Hook validasi akses.
	 * 
	 * @return mixed
	 */
	public function validasi()
	{
		// Jangan jalankan validasi akses untuk spesifik controller.
		if (in_array($this->ci->router->class, ['pelanggan', 'setting', 'siteman', 'first']))
		{
			return;
		}

		// Validasi akses
		if ( ! $this->validasi_akses())
		{
			redirect("pelanggan/peringatan");
		}
	}

	/**
	 * Validasi akses fitur.
	 * 
	 * @return bool
	 */
	protected function validasi_akses()
	{
		if (empty($token = $this->ci->setting->layanan_opendesa_token)) {
			$this->ci->session->set_userdata('error_status_langganan', 'Token pelanggan kosong / tidak valid.');

			return false;
		}

		$tokenParts = explode(".", $token);
		$tokenPayload = base64_decode($tokenParts[1]);
		$jwtPayload = json_decode($tokenPayload);

		$date = new DateTime('20' . str_replace('.', '-', $this->ci->setting->current_version) . '-01');
		$date->modify('last day of this month');
		$version = $date->format('Y-m-d');
		
		if (($version > $jwtPayload->tanggal_berlangganan->akhir) && ($jwtPayload->tanggal_berlangganan->akhir < $version))
		{
			$this->ci->session->set_userdata('error_status_langganan', "Masa aktif berlangganan fitur premium sudah berakhir.");

			return false;
		}
			
		if (version_compare($jwtPayload->desa_id, kode_wilayah($this->ci->header['desa']['kode_desa']), '!='))
		{
			$this->ci->session->set_userdata('error_status_langganan', "Kode desa tidak cocok dengan yang ada di layanan.opendesa.id.");

			return false;
		}

		if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']))
		{
			return true;
		} else if ($jwtPayload->domain != substr(base_url(), 0, -1))
		{
			$this->ci->session->set_userdata('error_status_langganan', "Domain desa tidak cocok dengan yang ada di layanan.opendesa.id.");

			return false;
		}

		return true;
	}
}
