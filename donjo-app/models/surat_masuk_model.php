<?php class surat_masuk_model extends CI_Model {

	protected
		// Konfigurasi untuk library 'upload'
		$uploadConfig = array();

	function __construct() {
		parent::__construct();
		$this->load->library('upload');

		$this->uploadConfig = array(
			'upload_path' => LOKASI_ARSIP,
			'allowed_types' => 'gif|jpg|png',
			'max_size' => 2048,
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
	 * [insert description]
	 * @return  [type]  [description]
	 */
	public function insert()
	{
		$data = $this->input->post(NULL);
		$data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
		$data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);

		// Adakah lampiran yang disertakan?
		$adaLampiran = !empty($_FILES['satuan']['name']);

		$uploadData = NULL;
		$uploadError = NULL;
		// Ada lampiran file
		if ($adaLampiran === TRUE)
		{
			$this->upload->initialize($this->uploadConfig);
			// Upload sukses
			if ($this->upload->do_upload('satuan'))
			{
				$uploadData = $this->upload->data();
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
		$_SESSION['error_msg'] = $_SESSION['success'] === 1 ? NULL : ' ->'.$uploadError;		
	}

	/**
	 * [update description]
	 * @param  integer  $idBerkasScan  [description]
	 * @return  [type]   [description]
	 */
	function update($idBerkasScan = 0) {
		$data = $this->input->post(NULL);
		$data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
		$data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);
		// Berkas scan lama
		$sql = "SELECT berkas_scan FROM surat_masuk WHERE id = ?";
		$dbQuery = $this->db->query($sql, array($idBerkasScan));
		$berkasLama = $dbQuery->row();
		$berkasLama = is_object($berkasLama) ? $berkasLama->berkas_scan : NULL;
		$data['berkas_scan'] = $berkasLama;

		// Lokasi berkas scan lama (absolut)
		$lokasiBerkasLama = $this->uploadConfig['upload_path'].$berkasLama;

		$uploadData = NULL;
		$uploadError = NULL;
		// Ada lampiran file
		if ($adaLampiran === TRUE)
		{
			$this->upload->initialize($this->uploadConfig);
			// Upload sukses
			if ($this->upload->do_upload('satuan'))
			{
				$uploadData = $this->upload->data();
			}
			// Upload gagal
			else
			{
				$uploadError = $this->upload->display_errors(NULL, NULL);
				$data['berkas_scan'] = is_null($berkasLama) ? NULL : $berkasLama;
			}
		}
		// Hapus lampiran lama?
		$hapusLampiranLama = $data['gambar_hapus'];
		unset($data['gambar_hapus']);
		// die(print_r($data));
		$data['berkas_scan'] = $adaLampiran && !is_null($uploadData)
			? $uploadData['file_name'] : $berkasLama;

		if ($hapusLampiranLama) {
			unlink($lokasiBerkasLama);
			$data['berkas_scan'] = NULL;
		}
		$newData = array(
			'nomor_urut' => $data['nomor_urut'],
			'tanggal_penerimaan' => $data['tanggal_penerimaan'],
			'nomor_surat' => $data['nomor_surat'],
			'kode_surat' => $data['kode_surat'],
			'tanggal_surat' => $data['tanggal_surat'],
			'pengirim' => $data['pengirim'],
			'isi_singkat' => $data['isi_singkat'],
			'disposisi_kepada' => $data['disposisi_kepada'],
			'isi_disposisi' => $data['isi_disposisi'],
			'berkas_scan' => $data['berkas_scan']
		);

		// die(print_r($newData));

		$this->db->where('id', $idBerkasScan);
		$indikatorSukses = $this->db->update('surat_masuk', $newData);

		$_SESSION['success'] = $indikatorSukses ? 1 : -1;
		$_SESSION['error_msg'] = $_SESSION['success'] === 1
			? NULL : ' ->'.$uploadError;
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

	function _upload_gambar($old_document=''){
		$lokasi_file = $_FILES['satuan']['tmp_name'];
		if (!empty($lokasi_file)){
			$nama_file = $_FILES['satuan']['name'];
			$nama_file   = time().'-'.urlencode($nama_file); 	 // normalkan nama file
			UploadKeLokasi(LOKASI_ARSIP,$lokasi_file,$nama_file,$old_document);
			return $nama_file;
		}
	}

	function delete($id=''){
		$_SESSION['success'] = 1;
		$outp = $this->db->where('id',$id)->delete('surat_masuk');
		if(!$outp) $_SESSION['success'] = -1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];
		if(count($id_cb)){
			foreach($id_cb as $id){
				$this->delete($id);
			}
		}
	}

	/**
	 * Ambil data kolom berdasarkan id
	 * @param  string       $idBerkasScan  Id pada tabel surat_masuk
	 * @param  string       $kolom         Kolom yang akan diambil datanya
	 * @return  mixed|NULL
	 */
	public function getNamaBerkasScan($idBerkasScan)
	{
		$sql = "SELECT berkas_scan FROM surat_masuk WHERE id = ?";
		$dbQuery = $this->db->query($sql, array($idBerkasScan));
		return $dbQuery->row();
	}
}

?>
