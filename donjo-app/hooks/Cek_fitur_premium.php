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
		// Jalankan untuk spesifik fitur premium
		if (in_array($this->ci->router->class, ['pelanggan', 'setting']))
		{
			return;
		}

		// Validasi akses
		if ( ! $this->validasi_akses())
		{
			redirect("pelanggan/peringatan");
		}
	}

	protected function validasi_akses()
	{
		if (empty($token = $this->ci->setting->layanan_opendesa_token)) {
			$this->ci->session->set_userdata('error_status_langganan', 'Token pelanggan kosong / tidak valid');

			return false;
		}

		$host = $this->ci->setting->layanan_opendesa_server;

		$response = $this->ci->cache->pakai_cache(function () use ($host, $token) {
			// request ke api layanan.opendesa.id
			return $this->client->post(
				"{$host}/api/v1/key/check",
				[
					'kode_desa' => kode_wilayah($this->ci->header['desa']['kode_desa']),
					'domain' => substr(base_url(), 0, -1),
				],
				[
					CURLOPT_HTTPHEADER => [
						"X-Requested-With: XMLHttpRequest",
						"Authorization: Bearer {$token}",
					]
				]
			);
		}, 'validasi_langganan', 24 * 60 * 60);

		if ($response->header->http_code === 401)
		{
			$this->ci->cache->hapus_cache_untuk_semua('validasi_langganan');
			$this->ci->session->set_userdata('error_status_langganan', "Masa aktif berlangganan fitur premium sudah berakhir.");

			return false;
		}

		if ($response->header->http_code != 200)
		{
			$this->ci->cache->hapus_cache_untuk_semua('validasi_langganan');
			$this->ci->session->set_userdata('error_status_langganan', "{$response->header->http_code} | {$response->body->messages->error}");

			return false;
		}

		return true;
	}
}
