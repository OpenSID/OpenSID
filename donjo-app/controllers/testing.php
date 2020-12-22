public function testing()
	{
		$peserta = '1';
		$sasaran = 4;

		//$data = $this->program_bantuan_model->cek_peserta($peserta, $sasaran);
		if ($sasaran == 4)
		{
			$this->load->model('kelompok_model');
			$kelompok = $this->kelompok_model->get_kelompok($peserta);
			$data = $kelompok['kode'];
		}

		echo json_encode($data, true);

	}
