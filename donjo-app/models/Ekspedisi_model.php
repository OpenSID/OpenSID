<?php class Ekspedisi_model extends Surat_keluar_model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$this->db->where('ekspedisi', 1);
		$data = parent::autocomplete();
		return $data;
	}

	public function paging($o=0, $offset=0)
	{
		$this->db->where('ekspedisi', 1);
		$data = parent::paging($o, $offset);
		return $data;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		$this->db->where('ekspedisi', 1);
		$data = parent::list_data($o, $offset, $limit);
		return $data;
	}

	/**
	 * Update data di tabel surat_keluar untuk ekspedisi
	 * @param   integer  $id  Id surat_keluar untuk query ke database
	 * @return  void
	 */
	public function update($id)
	{
		// Ambil semua data dari var. global $_POST
		$post = $this->input->post();
		$data = $this->validasi($post);

		$this->session->error_msg = NULL;

		// Ambil nama berkas scan lama dari database
		$berkas_lama = $this->get_tanda_terima($id);

		// Lokasi berkas scan lama (absolut)
		$lokasi_berkas_lama = $this->uploadConfig['upload_path'].$berkas_lama;
		$lokasi_berkas_lama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH.$lokasi_berkas_lama);

		// Hapus lampiran lama?
		$hapus_lampiran_lama = $post['gambar_hapus'];

		$upload_data = NULL;

		// Adakah file baru yang akan diupload?
		$ada_berkas = !empty($_FILES['tanda_terima']['name']);

		// penerapan transaction karena insert ke 2 tabel
		$this->db->trans_start();

		// Ada lampiran file
		if ($ada_berkas === TRUE)
		{
			// Tes tidak berisi script PHP
			if (isPHP($_FILES['foto']['tmp_name'], $_FILES['tanda_terima']['name']))
			{
				$this->session->error_msg .= " -> Jenis file ini tidak diperbolehkan ";
				$this->session->success = -1;
				redirect('ekspedisi');
			}
			// Cek nama berkas tidak boleh lebih dari 80 karakter (+20 untuk unique id) karena -
			// karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
			if ((strlen($_FILES['tanda_terima']['name']) + 20 ) >= 100)
			{
				$this->session->success = -1;
				$this->session->error_msg = ' -> Nama berkas yang coba Anda unggah terlalu panjang, '.
					'batas maksimal yang diijinkan adalah 80 karakter';
				redirect('ekspedisi');
			}
			// Inisialisasi library 'upload'
			$this->upload->initialize($this->uploadConfig);
			// Upload sukses
			if ($this->upload->do_upload('tanda_terima'))
			{
				$upload_data = $this->upload->data();
				// Hapus berkas dari disk
				// Perhatian: operator 'or' di sini error menggantikan '||'
				$berkas_dihapus = empty($berkas_lama) || (file_exists($lokasi_berkas_lama) && unlink($lokasi_berkas_lama));
				if ( ! $berkas_dihapus) $this->session->error_msg .= ' -> Gagal menghapus berkas lama';
				// Buat nama file unik untuk nama file upload
				$nama_file_unik = tambahSuffixUniqueKeNamaFile($upload_data['file_name']);
				// Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
				$berkas_direname = rename(
					$this->uploadConfig['upload_path'].$upload_data['file_name'],
					$this->uploadConfig['upload_path'].$nama_file_unik
				);

				$data['tanda_terima'] = $berkas_direname ? $nama_file_unik : $upload_data['file_name'];
				// Update database dengan `tanda_terima` berisi nama unik
				$this->db->where('id', $id);
				$hasil = $this->db->update('surat_keluar', $data);
				if ( ! $hasil) $this->session->error_msg .= ' -> Gagal memperbarui data di database';
			}
			// Upload gagal
			else
			{
				$this->session->error_msg .= $this->upload->display_errors(NULL, NULL);
			}
		}
		// Tidak ada file upload
		else
		{
			if ($hapus_lampiran_lama)
			{
				$data['tanda_terima'] = NULL;
				$hasil = file_exists($lokasi_berkas_lama) && unlink($lokasi_berkas_lama);
				if ( ! $hasil) $this->session->error_msg .= ' -> Gagal menghapus berkas lama';
			}
			$this->db->where('id', $id);
			$hasil = $this->db->update('surat_keluar', $data);
			if ( ! $hasil) $this->session->error_msg .= ' -> Gagal memperbarui data di database';
		}

		$this->db->trans_complete();

		$this->session->success = is_null($this->session->error_msg) ? 1 : -1;
	}

	private function validasi($post)
	{
		$data['tanggal_pengiriman'] = tgl_indo_in($post['tanggal_pengiriman']);
		$data['keterangan'] = htmlentities($post['keterangan']);
		return $data;
	}

	public function get_tanda_terima($id)
	{
		$tanda_terima = $this->db
			->select('tanda_terima')
			->where('id', $id)
			->get('surat_keluar')
			->row()->tanda_terima;
		return $tanda_terima;
	}

	public function list_tahun_surat()
	{
		$this->db->where('ekspedisi', 1);
		$data = parent::list_tahun_surat();
		return $data;
	}
}

?>
