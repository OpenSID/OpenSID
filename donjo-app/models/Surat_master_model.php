<?php class Surat_master_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'tweb_surat_format');
	}


	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (u.nama LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND u.jenis = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) AS jml ". $this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = " FROM tweb_surat_format u WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.nomor'; break;
			case 2: $order_sql = ' ORDER BY u.nomor DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY u.kode_surat'; break;
			case 6: $order_sql = ' ORDER BY u.kode_surat DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$sql = "SELECT u.* " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function insert()
	{
		$data = $_POST;
		$this->validasi_surat($data);

		$pemohon_surat = $data['pemohon_surat'];
		unset($data['pemohon_surat']);
		$data['url_surat'] = str_replace(" ", "_", $data['nama']);
		$data['url_surat'] = "surat_".strtolower($data['url_surat']);
		/** pastikan belum ada url suratnya */
		if ($this->isExist($data['url_surat']))
		{
			$_SESSION['success'] = -2;
			return;
		}
		$outp = $this->db->insert('tweb_surat_format', $data);
		$raw_path = "template-surat/raw/";

		// Folder untuk surat ini
		$folder_surat = LOKASI_SURAT_DESA.$data['url_surat']."/";
		if (!file_exists($folder_surat))
		{
			mkdir($folder_surat, 0777, true);
		}

		if ($pemohon_surat == 'warga')
		{
			$template = "template.rtf";
			$form = "form.raw";
		}
		else
		{
			$template = "template_non_warga.rtf";
			$form = "form_non_warga.raw";
		}

		// index.html untuk menutup akses ke folder melalui browser
		copy($raw_path."index.html", $folder_surat."index.html");

		//doc
		copy($raw_path.$template, $folder_surat.$data['url_surat'].".rtf");

		//form
		$file = $raw_path.$form;
		$handle = fopen($file, 'r');
		$buffer = stream_get_contents($handle);
		$berkas = $folder_surat.$data['url_surat'].".php";
		$handle = fopen($berkas, 'w+');
		$buffer = str_replace("[nama_surat]","Surat $data[nama]", $buffer);
		fwrite($handle, $buffer);
		fclose($handle);

		if ($pemohon_surat == 'warga')
		{
			// cetak
			$file = $raw_path."print.raw";
			$handle = fopen($file, 'r');
			$buffer = stream_get_contents($handle);
			$berkas = $folder_surat."print_".$data['url_surat'].".php";
			$handle = fopen($berkas, 'w+');
			$nama_surat = strtoupper($data['nama']);
			$buffer = str_replace("[nama_surat]","SURAT $nama_surat", $buffer);
			fwrite($handle, $buffer);
			fclose($handle);
		} else {
			// data untuk form
			copy($raw_path."data_form_non_warga.raw", $folder_surat."data_form_".$data['url_surat'].".php");
		}

		status_sukses($outp); //Tampilkan Pesan
	}

	private function validasi_surat(&$data)
	{
		$data['nama'] = alfanumerik_spasi($data['nama']);
	}

	public function update($id=0)
	{
		$data = $_POST;
		$this->validasi_surat($data);
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_surat_format', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function upload($url="")
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';

		// Folder desa untuk surat ini
		$folder_surat = LOKASI_SURAT_DESA.$url."/";
		if (!file_exists($folder_surat))
		{
			mkdir($folder_surat, 0755, true);
		}
		// index.html untuk menutup akses ke folder melalui browser
		copy("template-surat/raw/"."index.html", $folder_surat."index.html");

		$nama_file_rtf = $url . ".rtf";
		$this->uploadBerkas('rtf', $folder_surat, 'foto', 'surat_master', $nama_file_rtf);
		$this->salin_lampiran($url, $folder_surat);
	}

	// Lampiran surat perlu disalin ke folder surata di LOKASI_SURAT_DESA, karena
	// file lampiran surat dianggap ada di folder yang sama dengan tempat template surat RTF
	private function salin_lampiran($url, $folder_surat)
	{
		$this->load->model('surat_model');
		$surat = $this->surat_model->get_surat($url);
		if (!$surat['lampiran']) return;

		// $lampiran_surat dalam bentuk seperti "f-1.08.php,f-1.25.php"
		$daftar_lampiran = explode(",", $surat['lampiran']);
		foreach ($daftar_lampiran as $lampiran)
		{
			if (!file_exists($folder_surat.$lampiran))
			{
				copy("template-surat/".$url."/".$lampiran, $folder_surat.$lampiran);
			}
		}
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;
		// Surat jenis sistem (nilai 1) tidak bisa dihapus
		$outp = $this->db->where('id', $id)->where('jenis <>', 1)->delete('tweb_surat_format');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=true);
		}
	}

	public function get_surat_format($id=0)
	{
		$sql = "SELECT * FROM tweb_surat_format WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

  public function get_kode_isian($surat)
  {
		// Lokasi instalasi SID mungkin di sub-folder
    include FCPATH . '/vendor/simple_html_dom.php';
    $path_bawaan = FCPATH . "/template-surat/".$surat['url_surat']."/". $surat['url_surat'].".php";
    $path_lokal = FCPATH . LOKASI_SURAT_DESA .$surat['url_surat']."/".$surat['url_surat'].".php";
    if (file_exists($path_lokal))
	    $html = file_get_html($path_lokal);
		else if (file_exists($path_bawaan))
			$html = file_get_html($path_bawaan);
		else return array();
    // Kumpulkan semua isian (tag input) di form surat
    // Asumsi di form surat, struktur input seperti ini
    // <tr>
    // 		<th>Keterangan Isian</th>
    // 		<td><input><td>
    // </tr>
    $inputs = array();
    foreach ($html->find('input') as $input)
    {
      if ($input->type == 'hidden')
      {
        continue;
			}
			if ($input->title == 'Pilih Tanggal')
			{
				$inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;
				continue;
			}
			if ($input->type == 'radio')
			{
				$inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;
				continue;
			}
			if ($input->id == 'jam_1')
			{
				$inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;
				continue;
			}
			if ($input->id == 'input_group')
			{
				$inputs[$input->name] = $input->parent->parent->parent->children[0]->innertext;
				continue;
			}
      $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
		}
    foreach ($html->find('textarea') as $input)
    {
      if ($input->type == 'hidden')
      {
        continue;
      }
      $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
		}
    foreach ($html->find('select') as $input)
     {
      if ($input->type == 'hidden')
      {
        continue;
      }
      $inputs[$input->name] = $input->parent->parent->children[0]->innertext;
		}

    $html->clear();
    unset($html);
    return $inputs;
  }

	public function favorit($id=0, $k=0)
	{
		if ($k == 1)
			$sql = "UPDATE tweb_surat_format SET favorit = 0 WHERE id = ?";
		else
			$sql = "UPDATE tweb_surat_format SET favorit = 1 WHERE id = ?";

		$outp = $this->db->query($sql, $id);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function lock($id=0, $k=0)
	{
		if ($k == 1)
			$sql = "UPDATE tweb_surat_format SET kunci = 0 WHERE id = ?";
		else
			$sql = "UPDATE tweb_surat_format SET kunci = 1 WHERE id = ?";

		$outp = $this->db->query($sql, $id);

		status_sukses($outp); //Tampilkan Pesan
	}

	// Tambahkan surat desa jika folder surat tidak ada di surat master
	public function impor_surat_desa()
	{
		$folder_surat_desa = glob(LOKASI_SURAT_DESA.'*' , GLOB_ONLYDIR);
		foreach ($folder_surat_desa as $surat)
		{
			$surat = str_replace(LOKASI_SURAT_DESA, '', $surat);
			$hasil = $this->db->where('url_surat', $surat)->get('tweb_surat_format');
			if ($hasil->num_rows() == 0)
			{
				$data = array();
				$data['jenis'] = 2;
				$data['url_surat'] = $surat;
				$data['nama'] = ucwords(trim(str_replace(array("surat","-","_"), ' ', $surat)));
				$sql = $this->db->insert_string('tweb_surat_format', $data) . " ON DUPLICATE KEY UPDATE jenis = VALUES(jenis), nama = VALUES(nama)";
				$this->db->query($sql);
			}
		}
	}

	/***
		* @return
			- success: nama berkas yang diunggah
			- fail: NULL
	*/
	private function uploadBerkas($allowed_types, $upload_path, $lokasi, $redirect, $nama_file)
	{
		// Untuk dapat menggunakan library upload
		$this->load->library('upload');
		// Untuk dapat menggunakan fungsi generator()
		$this->load->helper('donjolib');
		$this->upload_config = array(
			'upload_path' => $upload_path,
			'allowed_types' => $allowed_types,
			'max_size' => max_upload()*1024,
			'file_name' => $nama_file,
			'overwrite' => TRUE
		);
		// Adakah berkas yang disertakan?
		$ada_berkas = !empty($_FILES[$lokasi]['name']);
		if ($ada_berkas !== TRUE)
		{
			return NULL;
		}
		// Tes tidak berisi script PHP
		if (isPHP($_FILES[$lokasi]['tmp_name'], $_FILES[$lokasi]['name']))
		{
			$_SESSION['error_msg'] .= " -> Jenis file ini tidak diperbolehkan ";
			$_SESSION['success'] = -1;
			redirect($redirect);
		}

		$upload_data = NULL;
		// Inisialisasi library 'upload'
		$this->upload->initialize($this->upload_config);
		// Upload sukses
		if ($this->upload->do_upload($lokasi))
		{
			$upload_data = $this->upload->data();
		}
		// Upload gagal
		else
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = $this->upload->display_errors(NULL, NULL);
		}
		return (!empty($upload_data)) ? $upload_data['file_name'] : NULL;
	}

	private function isExist($url_surat)
	{
		$sudahAda = $this->db->select('count(*) ada')
				->where(array('url_surat' => $url_surat))
				->get('tweb_surat_format')->row_array();
		return $sudahAda['ada'];
	}

	public function get_syarat_surat($id=1)
	{
		$data = $this->db->select('r.ref_syarat_id, r.ref_syarat_nama')
			->where('surat_format_id', $id)
			->from('syarat_surat s')
			->join('ref_syarat_surat r', 's.ref_syarat_id = r.ref_syarat_id')
			->order_by('ref_syarat_id')
			->get()->result_array();
		return $data;
	}
}

?>
