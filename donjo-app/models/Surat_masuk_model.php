<?php class Surat_masuk_model extends CI_Model {
    // Konfigurasi untuk library 'upload'
    protected $uploadConfig = array();

	function __construct() {
		parent::__construct();
		// Untuk dapat menggunakan library upload
		$this->load->library('upload');
		// Untuk dapat menggunakan fungsi generator()
		$this->load->helper('donjolib');
        // Helper upload file
		$this->load->helper('pict_helper');
		$this->uploadConfig = array(
			'upload_path' => LOKASI_ARSIP,
			'allowed_types' => 'gif|jpg|jpeg|png|pdf',
			'max_size' => max_upload()*1024,
		);
	}

	function autocomplete(){
		// TODO: tambahkan kata2 dari isi_singkat
		$str = $this->autocomplete_pengirim();
		return $str;
	}

	function autocomplete_pengirim(){
		$data = $this->db->distinct()->select('pengirim')->order_by('pengirim')->get('surat_masuk')->result_array();
		$str = '';
		foreach($data as $item){
			$str .= ",'".$item['pengirim']."'";
		}
		$str = '[' .substr($str, 1). ']';
		return $str;
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.pengirim LIKE '$kw' OR u.isi_singkat LIKE '$kw')";
			return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			if(!empty($kf)) {
				$filter_sql= " AND YEAR(u.tanggal_penerimaan) = $kf";
			}
		return $filter_sql;
		}
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_data_sql() {
		$sql = "
			FROM surat_masuk u WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	function paging($p=1,$o=0){

		$sql = "SELECT COUNT(id) AS id ".$this->list_data_sql();
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_data($o=0,$offset=0,$limit=500){

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY YEAR(u.tanggal_penerimaan) ASC, u.nomor_urut ASC'; break;
			case 2: $order_sql = ' ORDER BY YEAR(u.tanggal_penerimaan) DESC, u.nomor_urut DESC'; break;
			case 3: $order_sql = ' ORDER BY u.tanggal_penerimaan'; break;
			case 4: $order_sql = ' ORDER BY u.tanggal_penerimaan DESC'; break;
			case 5: $order_sql = ' ORDER BY u.pengirim'; break;
			case 6: $order_sql = ' ORDER BY u.pengirim DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$sql   = "SELECT u.* ".$this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_tahun_penerimaan(){
		$query = $this->db->distinct()->select('YEAR(tanggal_penerimaan) AS tahun')->order_by('tanggal_penerimaan DESC')->get('surat_masuk')->result_array();
		return $query;
	}

	/**
	 * Insert data baru ke tabel surat_masuk
	 * @return  void
	 */
	public function insert()
	{
		// Ambil semua data dari var. global $_POST
		$data = $this->input->post(NULL);
		// Normalkan tanggal
		$data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
		$data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);

		// Adakah lampiran yang disertakan?
		$adaLampiran = !empty($_FILES['satuan']['name']);

		// Cek nama berkas user boleh lebih dari 80 karakter (+20 untuk unique id) karena -
		// karakter maksimal yang bisa ditampung kolom surat_masuk.berkas_scan hanya 100 karakter
		if ($adaLampiran && ((strlen($_FILES['satuan']['name']) + 20 ) >= 100))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Nama berkas yang coba Anda unggah terlalu panjang, '.
				'batas maksimal yang diijinkan adalah 80 karakter';
			redirect('surat_masuk');
		}

		$uploadData = NULL;
		$uploadError = NULL;
		// Ada lampiran file
		if ($adaLampiran === TRUE)
		{
			// Tes tidak berisi script PHP
			if(isPHP($_FILES['foto']['tmp_name'], $_FILES['foto']['name'])){
				$_SESSION['error_msg'].= " -> Jenis file ini tidak diperbolehkan ";
				$_SESSION['success']=-1;
				redirect('man_user');
			}
			// Inisialisasi library 'upload'
			$this->upload->initialize($this->uploadConfig);
			// Upload sukses
			if ($this->upload->do_upload('satuan'))
			{
				$uploadData = $this->upload->data();
				// Buat nama file unik agar url file susah ditebak dari browser
				$namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
				// Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
				$fileRenamed = rename(
					$this->uploadConfig['upload_path'].$uploadData['file_name'],
					$this->uploadConfig['upload_path'].$namaFileUnik
				);
				// Ganti nama di array upload jika file berhasil di-rename --
				// jika rename gagal, fallback ke nama asli
				$uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
			}
			// Upload gagal
			else
			{
				$uploadError = $this->upload->display_errors(NULL, NULL);
			}
		}
		// Berkas lampiran
		$data['berkas_scan'] = $adaLampiran && !is_null($uploadData)
			? $uploadData['file_name'] : NULL;
		$indikatorSukses = is_null($uploadError) && $this->db->insert('surat_masuk', $data);
		// Set session berdasarkan hasil operasi
		$_SESSION['success'] = $indikatorSukses ? 1 : -1;
		$_SESSION['error_msg'] = $_SESSION['success'] === 1 ? NULL : ' -> '.$uploadError;
	}

	/**
	 * Update data di tabel surat_masuk
	 * @param   integer  $idSuratMasuk  Id berkas untuk query ke database
	 * @return  void
	 */
	public function update($idSuratMasuk)
	{
		// Ambil semua data dari var. global $_POST
		$data = $this->input->post(NULL);

		$_SESSION['error_msg'] = NULL;

		// Normalkan tanggal
		$data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
		$data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);

		// Ambil nama berkas scan lama dari database
		$berkasLama = $this->getNamaBerkasScan($idSuratMasuk);

		// Lokasi berkas scan lama (absolut)
		$lokasiBerkasLama = $this->uploadConfig['upload_path'].$berkasLama;
		$lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH.$lokasiBerkasLama);

		$indikatorSukses = FALSE;

		// Hapus lampiran lama?
		$hapusLampiranLama = ($data['gambar_hapus'] == 'YA');
		unset($data['gambar_hapus']);

		$uploadData = NULL;
		$uploadError = NULL;

		// Adakah file baru yang akan diupload?
		$adaLampiran = !empty($_FILES['satuan']['name']);

		// Ada lampiran file
		if ($adaLampiran === TRUE)
		{
			// Tes tidak berisi script PHP
			if(isPHP($_FILES['foto']['tmp_name'], $_FILES['satuan']['name'])){
				$_SESSION['error_msg'].= " -> Jenis file ini tidak diperbolehkan ";
				$_SESSION['success']=-1;
				redirect('man_user');
			}
			// Cek nama berkas tidak boleh lebih dari 80 karakter (+20 untuk unique id) karena -
			// karakter maksimal yang bisa ditampung kolom surat_masuk.berkas_scan hanya 100 karakter
			if ((strlen($_FILES['satuan']['name']) + 20 ) >= 100)
			{
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = ' -> Nama berkas yang coba Anda unggah terlalu panjang, '.
				'batas maksimal yang diijinkan adalah 80 karakter';
				redirect('surat_masuk');
			}
			// Inisialisasi library 'upload'
			$this->upload->initialize($this->uploadConfig);
			// Upload sukses
			if ($this->upload->do_upload('satuan'))
			{
				$uploadData = $this->upload->data();
				// Hapus berkas dari disk
				$oldFileRemoved = unlink($lokasiBerkasLama) && !file_exists($lokasiBerkasLama);
				$_SESSION['error_msg'] = ($oldFileRemoved === TRUE)
					? NULL : ' -> Gagal menghapus berkas lama';
				// Buat nama file unik untuk nama file upload
				$namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
				// Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
				$uploadedFileRenamed = rename(
					$this->uploadConfig['upload_path'].$uploadData['file_name'],
					$this->uploadConfig['upload_path'].$namaFileUnik
				);

				$uploadData['file_name'] = ($uploadedFileRenamed === FALSE) ?: $namaFileUnik;

				$data['berkas_scan'] = $uploadData['file_name'];
				// Update database dengan `berkas_scan` berisi nama unik
				$this->db->where('id', $idSuratMasuk);
				$databaseUpdated = $this->db->update('surat_masuk', $data);

				$_SESSION['error_msg'] = ($databaseUpdated === TRUE)
					? NULL : 'Gagal memperbarui data di database';
			}
			// Upload gagal
			else
			{
				$_SESSION['error_msg'] = $this->upload->display_errors(NULL, NULL);
			}
		}
		// Tidak ada file upload
		else
		{
			if ($hapusLampiranLama === TRUE)
			{
				$adaBerkasLamaDiDisk = file_exists($lokasiBerkasLama);
				$oldFileRemoved = $adaBerkasLamaDiDisk && unlink($lokasiBerkasLama);
				$_SESSION['error_msg'] = ($oldFileRemoved === TRUE)
					? NULL : ' -> Gagal menghapus berkas lama';

			}

			$data['berkas_scan'] = NULL;
			$this->db->where('id', $idSuratMasuk);
			$databaseUpdated = $this->db->update('surat_masuk', $data);
			$_SESSION['error_msg'] = ($databaseUpdated === TRUE)
				? NULL : 'Gagal memperbarui data di database';
			$adaBerkasLamaDiDB = !is_null($berkasLama);
		}

		$_SESSION['success'] = is_null($_SESSION['error_msg']) ? 1 : -1;
	}


	function get_surat_masuk($id){
		$surat_masuk = $this->db->where('id',$id)->get('surat_masuk')->row_array();
		return $surat_masuk;
	}

	// TODO: apakah perlu diambil dari tweb_desa_pamong?
	function get_pengolah_disposisi(){
		$this->load->model('wilayah_model');
    $ref_disposisi[] = 'Sekretaris '.ucwords($this->setting->sebutan_desa);
    array_push($ref_disposisi,
      'Kasi Pemerintahan',
      'Kasi Kesejahteraan',
      'Kasi Pelayanan',
      'Kaur Keuangan',
      'Kaur Tata Usaha dan Umum',
      'Kaur Perencanaan');
    $list_dusun = $this->wilayah_model->list_data();
    foreach($list_dusun as $dusun){
    	array_push($ref_disposisi, ucwords($this->setting->sebutan_singkatan_kadus).' '.ucwords(strtolower($dusun['dusun'])));
    };
    return $ref_disposisi;
	}

	/**
	 * Hapus record surat masuk beserta file lampirannya (jika ada)
	 * @param   string  $idSuratMasuk  Id surat masuk
	 * @return  void
	 */
	public function delete($idSuratMasuk)
	{
		// Type check
		$idSuratMasuk = is_string($idSuratMasuk) ? $idSuratMasuk : strval($idSuratMasuk);
		// Redirect ke halaman surat masuk jika Id kosong
		if (empty($idSuratMasuk))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Data yang anda minta tidak ditemukan';
			redirect('surat_masuk');
		}

		$_SESSION['error_msg'] = NULL;

		$namaBerkas = $this->getNamaBerkasScan($idSuratMasuk);

		if (!is_null($namaBerkas))
		{
			$lokasiBerkasLama = $this->uploadConfig['upload_path'].$namaBerkas;
			$lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH.$lokasiBerkasLama);

			if (file_exists($lokasiBerkasLama))
			{
				$hapusLampiranLama = unlink($lokasiBerkasLama);
				$hapusLampiranLama = !file_exists($lokasiBerkasLama);
				$_SESSION['error_msg'] = $hapusLampiranLama === TRUE
					? NULL :' -> Gagal menghapus berkas dari disk';
			}

			if (is_null($_SESSION['error_msg']))
			{
				$hapusRecordDb = $this->db->where('id', $idSuratMasuk)->delete('surat_masuk');
				$_SESSION['error_msg'] = $hapusRecordDb === TRUE
					? NULL : ' -> Gagal menghapus record dari database';
			}
		}
		else
		{
			$hapusRecordDb = $this->db->where('id', $idSuratMasuk)->delete('surat_masuk');
			$_SESSION['error_msg'] = $hapusRecordDb === TRUE
				? NULL : ' -> Gagal menghapus record dari database';
		}

		$_SESSION['success'] = is_null($_SESSION['error_msg']) ? 1 : -1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];
		if(count($id_cb)){
			foreach($id_cb as $id){
				$this->delete($id);
			}
		}
	}

	//! ==============================================================
	//! Helper Methods
	//! ==============================================================
	/**
	 * Ambil nama berkas scan dari database berdasarkan Id surat masuk
	 * @param  string       $idSuratMasuk  Id pada tabel surat_masuk
	 * @param  string       $kolom         Kolom yang akan diambil datanya
	 * @return  string|NULL
	 */
	public function getNamaBerkasScan($idSuratMasuk)
	{
		// Ambil nama berkas dari database
		$sql = "SELECT berkas_scan FROM surat_masuk WHERE id = ? LIMIT 1;";
		$query = $this->db->query($sql, array($idSuratMasuk));
		$namaBerkas = $query->row();
		$namaBerkas = is_object($namaBerkas) ? $namaBerkas->berkas_scan : NULL;
		return $namaBerkas;
	}

}

?>
