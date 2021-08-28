<?php

class Cek_fitur_premium
{
	/** @var CI_Controller */
	protected $ci;

	/**
	 * Jangan jalankan validasi akses untuk spesifik controller.
	 */
	protected $kecuali_controller = [
		'hom_sid', 'identitas_desa', 'pelanggan', 'setting', 'siteman', 'first', 'masuk', 'masuk_ektp', 'bantuan', 'beranda', 'pesan', 'surat',
	];

	/**
	 * Constructor Cek fitur premium
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->ci = get_instance();
	}

	/**
	 * Hook validasi akses.
	 * 
	 * @return mixed
	 */
	public function validasi()
	{
		// Jangan jalankan validasi akses untuk spesifik controller.
		if (in_array($this->ci->router->class, $this->kecuali_controller))
		{
			return;
		}

		// Validasi akses
		if ( ! $this->validasi_akses())
		{
			($this->session->siteman == 1) ? redirect('peringatan') : show_404();
		}
	}

	/**
	 * Validasi akses fitur.
	 * 
	 * @return bool
	 */
	protected function validasi_akses()
	{
		$this->ci->session->unset_userdata('error_premium');
		
		if (empty($token = $this->ci->setting->layanan_opendesa_token))
		{
			$this->ci->session->set_userdata('error_premium', 'Token pelanggan kosong / tidak valid.');

			return false;
		}

		$tokenParts = explode(".", $token);
		$tokenPayload = base64_decode($tokenParts[1]);
		$jwtPayload = json_decode($tokenPayload);

		$date = new DateTime('20' . str_replace('.', '-', $this->ci->setting->current_version) . '-01');
		$version = $date->format('Y-m-d');
		
		if ($this->ci->setting->current_version != preg_replace('/-premium.*|pasca-|-pasca/', '', AmbilVersi()))
		{
			$this->ci->session->set_userdata('error_premium', 'Versi OpenSID tidak sesuai');

			return false;
		}
			
		if (version_compare($jwtPayload->desa_id, kode_wilayah($this->ci->header['desa']['kode_desa']), '!='))
		{
			$this->ci->session->set_userdata('error_premium', ucwords($this->ci->setting->sebutan_desa . ' ' . $this->ci->header['desa']['nama_desa']) . ' tidak terdaftar di layanan.opendesa.id.');

			return false;
		}
		
		if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']))
		{
			return true;
		}
		else if (get_domain($jwtPayload->domain) != get_domain(APP_URL))
		{
			$this->ci->session->set_userdata('error_premium', 'Domain ' . get_domain(APP_URL) . ' tidak terdaftar di layanan.opendesa.id.');

			return false;
		}

		if ($version > $jwtPayload->tanggal_berlangganan->akhir)
		{
			$this->ci->session->set_userdata('error_premium', "Masa aktif berlangganan fitur premium sudah berakhir.");

			return false;
		}

		return true;
	}
}
