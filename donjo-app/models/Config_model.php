<?php class Config_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_data()
	{
		$query = $this->db->select('*')->limit(1)->get('config')->row_array();
		return $query;
	}

	public function insert()
	{
		$data = $_POST;
		$data['id'] = 1; // Hanya ada satu row data desa
		// Data lokasi peta default. Diperlukan untuk menampilkan widget peta lokasi
		$data['lat'] = '-8.488005310891758';
		$data['lng'] = '116.0406072534065';
		$data['zoom'] = '19';
		$data['map_tipe'] = 'roadmap';
		unset($data['old_logo']);
		$outp = $this->db->insert('config',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	public function update($id=0)
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';

		$data = $_POST;
		$data['logo'] = $this->uploadLogo();

		if (!empty($data['logo'])) {
			// Ada logo yang berhasil diunggah --> simpan ukuran 100 x 100
			$tipe_file = TipeFile($_FILES['logo']);
			$dimensi = array("width"=>100, "height"=>100);
			resizeImage(LOKASI_LOGO_DESA.$data['logo'], $tipe_file, $dimensi);
			// Hapus berkas logo lama
		  if (!empty($data['old_logo'])) unlink(LOKASI_LOGO_DESA.$data['old_logo']);
		} else {
			unset($data['logo']);
		}
		unset($data['file_logo']);
		unset($data['old_logo']);
		$this->db->where('id',$id)->update('config',$data);

		$pamong['pamong_nama'] = $data['nama_kepala_desa'];
		$pamong['pamong_nip'] = $data['nip_kepala_desa'];
		$this->db->where('pamong_id','707');
		$outp = $this->db->update('tweb_desa_pamong',$pamong);

		if(!$outp) $_SESSION['success']=-1;
	}

	/*
		Returns:
			- success: nama berkas yang diunggah
			- fail: NULL
	*/
	private function uploadLogo()
	{
		$this->load->library('upload');
		$this->uploadConfig = array(
			'upload_path' => LOKASI_LOGO_DESA,
			'allowed_types' => 'gif|jpg|jpeg|png',
			'max_size' => max_upload()*1024,
		);
		// Adakah berkas yang disertakan?
		$adaBerkas = !empty($_FILES['logo']['name']);
		if ($adaBerkas !== TRUE) {
			return NULL;
		}
		// Tes tidak berisi script PHP
		if(isPHP($_FILES['logo']['tmp_name'], $_FILES['logo']['name'])){
			$_SESSION['error_msg'].= " -> Jenis file ini tidak diperbolehkan ";
			$_SESSION['success']=-1;
			redirect('hom_desa/konfigurasi');
		}

		$uploadData = NULL;
		// Inisialisasi library 'upload'
		$this->upload->initialize($this->uploadConfig);
		// Upload sukses
		if ($this->upload->do_upload('logo')) {
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
		else {
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = $this->upload->display_errors(NULL, NULL);
		}
		return (!empty($uploadData)) ? $uploadData['file_name'] : NULL;
	}

	public function update_kantor()
	{
		$data = $_POST;
		$id = "1";
		$this->db->where('id',$id);
		$outp = $this->db->update('config',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	public function update_wilayah()
	{
		$data = $_POST;
		$id = "1";
		$this->db->where('id',$id);
		$outp = $this->db->update('config',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

}
