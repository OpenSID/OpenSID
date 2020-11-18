<?php class Lapor_model extends CI_Model {

	/**
	 * Gunakan model ini untuk memindahkan semua method terkait laporan layanan mandiri.
	 * Saat ini laporan layanan mandiri masih bercampur dengan komentar artikel, dan
	 * seharusnya dipisah.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Simpan laporan yang dikirim oleh pengguna layanan mandiri
	 */
	public function insert()
	{
		$data['komentar'] = strip_tags(" Permohonan Surat ".$_POST["nama_surat"]." - ".$_POST["hp"]." - ".$_POST["komentar"]);
		/** ambil dari data session saja */
		$data['owner'] = $_SESSION['nama'];
		$data['email'] = $_SESSION['nik'];

		// load library form_validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('komentar', 'Laporan');
		$this->form_validation->set_rules('owner', 'Nama', 'required');
		$this->form_validation->set_rules('email', 'NIK', 'required');

		if ($this->form_validation->run() == TRUE)
		{
			unset($_SESSION['validation_error']);
			$data['status'] = 2;
			$data['id_artikel'] = 775; //id_artikel untuk laporan layanan mandiri
			$outp = $this->db->insert('komentar', $data);
		}
		else
		{
			$_SESSION['validation_error'] = 'Form tidak terisi dengan benar';
			$_SESSION['success'] = -1;
		}

		status_sukses($outp); //Tampilkan Pesan
	}

	public function autocomplete()
	{
		$sql = "SELECT ref_syarat_nama FROM ref_syarat_surat";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$out = '';
		for ($i=0; $i < count($data); $i++)
		{
			$out .= ",'".$data[$i]['ref_syarat_nama']."'";
		}
		return '['.strtolower(substr($out, 1)).']';
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$keyword = $_SESSION['cari'];
			$keyword = '%'.$this->db->escape_like_str($keyword).'%';
			$search_sql = " AND (u.ref_syarat_nama LIKE '$keyword' OR u.ref_syarat_nama LIKE '$keyword')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$filter = $_SESSION['filter'];
			$filter_sql = " AND u.ref_syarat_id = $filter";
			return $filter_sql;
		}
	}

	public function paging($page = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $page;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = " FROM ref_syarat_surat u, ref_syarat_surat g WHERE u.ref_syarat_id = g.ref_syarat_id ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($order = 0, $offset = 0, $limit = 500)
	{
		// Ordering sql
		switch($order)
		{
			case 1 :
				$order_sql = ' ORDER BY u.ref_syarat_nama';
				break;
			case 2:
				$order_sql = ' ORDER BY u.ref_syarat_nama DESC';
				break;
			case 3 :
				$order_sql = ' ORDER BY u.ref_syarat_id';
				break;
			case 4:
				$order_sql = ' ORDER BY u.ref_syarat_id DESC';
				break;
			default:
				$order_sql = ' ORDER BY u.ref_syarat_id';
		}
		// Paging sql
		$paging_sql = ' LIMIT '.$offset.','.$limit;
		// Query utama
		$sql = "SELECT u.*, g.ref_syarat_nama as grup " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		// Formating output
		$j = $offset;
		for ($i=0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	/**
	 * Insert user baru ke database
	 * @return  void
	 */
	public function insert_ref_surat()
	{
		$_SESSION['error_msg'] = NULL;
		$_SESSION['success'] = 1;

		$data = $this->input->post(NULL);

		$data['ref_syarat_nama'] = strip_tags($data['ref_syarat_nama']);

		if (!$this->db->insert('ref_syarat_surat', $data))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Gagal memperbarui data di database';
		}
	}

	public function update($id=0)
	{
		$data = $_POST;
		$this->db->where('ref_syarat_id', $id);
		$outp = $this->db->update('ref_syarat_surat', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($idUser = '')
	{
		$sql = "DELETE FROM ref_syarat_surat WHERE ref_syarat_id = ?";
		$hasil = $this->db->query($sql, array($idUser));

    if ($hasil)
		{
	    $_SESSION['error_msg'] = 'Sukses menghapus data';
			$_SESSION['success'] = 1;
		}
	}

	public function delete_all()
	{
    $id_cb = $_POST['id_cb'];
    // Cek apakah ada data yang dicentang atau dipilih
    if (!is_null($id_cb))
    {
      foreach ($id_cb as $id)
      {
        $this->delete($id);
      }
    }
    else
    {
      $_SESSION['error_msg'] = 'Tidak ada data yang dipilih';
      $_SESSION['success'] = -1;
    }
	}

	public function get_surat($id = 0)
	{
		$sql = "SELECT * FROM ref_syarat_surat WHERE ref_syarat_id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function get_surat_ref_all()
	{
		$this->db->select('*')
		         ->from('ref_syarat_surat');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_current_surat_ref($id)
	{
		$this->db->select('*')
				 ->from('tweb_surat_format')
				 ->join('syarat_surat', "tweb_surat_format.id = syarat_surat.surat_format_id")
				 ->join('ref_syarat_surat', "ref_syarat_surat.ref_syarat_id = syarat_surat.ref_syarat_id")
				 ->where('syarat_surat.surat_format_id',$id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function update_syarat_surat($surat_format_id = FALSE, $syarat_surat, $mandiri = 0)
	{
		if (empty($surat_format_id))
		{
			return FALSE;
		}

		$this->hapus_syarat($surat_format_id);

		if ($mandiri == 1)
		{
			// Tambahkan syarat baru yg dipilih
			foreach ($syarat_surat as $syarat)
			{
				$data = array('ref_syarat_id' => $syarat, 'surat_format_id' => $surat_format_id);
				$result = $this->db->insert('syarat_surat', $data);
			}
		}
	}

	private function hapus_syarat($id = 0)
	{
		// Hapus semua syarat surat berdasarkan surat_format_id
		$this->db
			->where('surat_format_id', $id)
			->delete('syarat_surat');
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
		copy("surat/raw/"."index.html", $folder_surat."index.html");

		$nama_file_rtf = $url . ".rtf";
		$this->uploadBerkas('rtf', $folder_surat, 'foto', 'surat_master', $nama_file_rtf);
		$this->salin_lampiran($url, $folder_surat);
	}

}
?>
