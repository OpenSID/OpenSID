<?php class Keluar_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$sql = array();
		$sql[] = '('.$this->db->select('no_surat')
							->from("log_surat")
							->get_compiled_select()
							.')';
		$sql[] = '('.$this->db->select('n.nama')
							->from("log_surat u")
							->join("tweb_penduduk n", "u.id_pend = n.id", "left")
							->get_compiled_select()
							.')';
		$sql[] = '('.$this->db->select('p.nama')
							->from("log_surat u")
							->join("tweb_desa_pamong s", "u.id_pamong = s.pamong_id", "left")
							->join("tweb_penduduk p", "s.id_pend = p.id", "left")
							->get_compiled_select()
							.')';
		$sql = implode('
		UNION
		', $sql);
		$data = $this->db->query($sql)->result_array();
		$str = autocomplete_data_ke_str($data);
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (u.no_surat LIKE '$kw' OR n.nama LIKE '$kw' OR
					s.pamong_nama like '$kw' OR p.nama like '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			if ($kf == "0")
				$filter_sql = "";
			else
				$filter_sql = " AND YEAR(u.tanggal) = '".$kf."'";
			return $filter_sql;
		}
	}

	private function jenis_sql()
	{
		if (isset($_SESSION['jenis']))
		{
			$kf = $_SESSION['jenis'];
			if (empty($kf))
				$sql = "";
			else
				$sql = " AND k.nama = '".$kf."'";
			return $sql;
		}
	}

	private function filterku_sql($nik='')
	{
		if (empty($nik)) return "";
		$kf = $nik;
		$filterku_sql= " AND u.id_pend = '".$kf."'";
		return $filterku_sql;
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql();
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
		$sql = " FROM log_surat u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			LEFT JOIN tweb_surat_format k ON u.id_format_surat = k.id
			LEFT JOIN tweb_desa_pamong s ON u.id_pamong = s.pamong_id
			LEFT JOIN tweb_penduduk p ON s.id_pend = p.id
			LEFT JOIN user w ON u.id_user = w.id
			WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->jenis_sql();
		return $sql;
	}

	// $limit = 0 mengambil semua
	public function list_data($o=0, $offset=0, $limit=0)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.no_surat * 1'; break;
			case 2: $order_sql = ' ORDER BY u.no_surat * 1 DESC'; break;
			case 3: $order_sql = ' ORDER BY nama'; break;
			case 4: $order_sql = ' ORDER BY nama DESC'; break;
			case 5: $order_sql = ' ORDER BY u.tanggal'; break;
			case 6: $order_sql = ' ORDER BY u.tanggal DESC'; break;

			default:$order_sql = ' ORDER BY u.tanggal DESC';
		}

		//Paging SQL
		$paging_sql = ($limit > 0 ) ? ' LIMIT ' .$offset. ',' .$limit : '';

		//Main Query
		$select_sql = "SELECT u.*, n.nama AS nama, w.nama AS nama_user, n.nik AS nik, k.nama AS format, k.url_surat as berkas, k.kode_surat as kode_surat, s.id_pend as pamong_id_pend, s.pamong_nama AS pamong, p.nama as nama_pamong_desa ";

		$sql = $select_sql . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j+1;
			$data[$i]['t'] = $data[$i]['id_pend'];

			if ($data[$i]['id_pend'] == -1)
				$data[$i]['id_pend'] = "Masuk";
			else
				$data[$i]['id_pend'] = "Keluar";
			if (!empty($data[$i]['pamong_id_pend']))
				// Pamong desa
				$data[$i]['pamong'] = $data[$i]['nama_pamong_desa'];

			$j++;
		}
		return $data;
	}

	public function list_data_keterangan($id)
	{
		$this->db->select('id, keterangan');
		$this->db->from('log_surat');
		$this->db->where('id', $id);

		return $this->db->get()->row_array();
	}

	public function update_keterangan($id, $data)
	{
		$this->db->where('id', $id);
		$outp = $this->db->update('log_surat', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function paging_perorangan($nik='', $p=1, $o=0)
	{
		if (!empty($nik))
		{
			$sql = "SELECT count(*) as jml " . $this->list_data_perorangan_sql($nik);
			$query  = $this->db->query($sql);
			$row = $query->row_array();
			$jml_data = $row['jml'];
		}
		else
			$jml_data = 0;

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_perorangan_sql($nik)
	{
		$sql = " FROM log_surat u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			LEFT JOIN tweb_surat_format k ON u.id_format_surat = k.id
			LEFT JOIN tweb_desa_pamong s ON u.id_pamong = s.pamong_id
			LEFT JOIN user w ON u.id_user = w.id
			WHERE 1 ";
		$sql .= $this->filterku_sql($nik);
		return $sql;
	}

	public function list_data_perorangan($nik='', $o=0, $offset=0, $limit=500)
	{
		if (empty($nik)) return array();

		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.no_surat * 1'; break;
			case 2: $order_sql = ' ORDER BY u.no_surat * 1 DESC'; break;
			case 3: $order_sql = ' ORDER BY nama'; break;
			case 4: $order_sql = ' ORDER BY nama DESC'; break;
			case 5: $order_sql = ' ORDER BY u.tanggal'; break;
			case 6: $order_sql = ' ORDER BY u.tanggal DESC'; break;

			default:$order_sql = ' ORDER BY u.tanggal DESC';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$select_sql = "SELECT u.*, n.nama AS nama, w.nama AS nama_user, n.nik AS nik, k.nama AS format, k.url_surat as berkas, s.pamong_nama AS pamong ";

		$sql = $select_sql . $this->list_data_perorangan_sql($nik);
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no']=$j+3;
			$j++;
		}
		return $data;
	}

	public function nama_surat_arsip($url, $nik, $nomor)
	{
		// Nama surat untuk surat keterangan di mana NIK = 1234567890123456 dan
		// nomor surat = 503/V.58.IV.135/III pada tanggal 27 Juli 2016 akan seperti ini:
		// surat_ket_pengantar_1234567890123456_2016-07-27_503-V.58.IV.135-III.rtf
		$nomor_surat = str_replace("'", '', $nomor);
		$nomor_surat = preg_replace('/[^a-zA-Z0-9.	]/', '-', $nomor_surat);
		$nama_surat = $url."_".$nik."_".date("Y-m-d")."_".$nomor_surat.".rtf";
		return $nama_surat;
	}

	public function log_surat($data_log_surat)
	{
		$url_surat = $data_log_surat['url_surat'];
		$nama_surat = $data_log_surat['nama_surat'];
		unset($data_log_surat['url_surat']);
		unset($data_log_surat['pamong_nama']);

		foreach ($data_log_surat as $key => $val)
		{
			$data[$key] = $val;
		}

		$sql = "SELECT id FROM tweb_surat_format WHERE url_surat = ?";
		$query = $this->db->query($sql, $url_surat);
		if ($query->num_rows() > 0)
		{
			$pam = $query->row_array();
			$data['id_format_surat'] = $pam['id'];
		}
		else
		{
			$data['id_format_surat'] = $url_surat;
		}

		$data['id_pamong'] = $data_log_surat['id_pamong'];
		if ($data['id_pamong'] == '')
			$data['id_pamong'] = 1;

		$data['bulan'] = date('m');
		$data['tahun'] = date('Y');
		$data['tanggal'] = date('Y-m-d H:i:s');
		//print_r($data);
		if (!empty($nama_surat))
		/**
			Ekspor Dok:
			Penambahan atau update log disesuaikan dengan file surat yang tersimpan di arsip,
			sehingga hanya ada satu entri di log surat untuk setiap versi surat di arsip.
			File surat disimpan di arsip untuk setiap URL-NIK-nomor surat-tanggal yang unik,
			lihat fungsi nama_surat_arsip (kolom nama_surat di tabel log_surat).
			Entri itu akan berisi timestamp (pencetakan) terakhir untuk file surat yang bersangkutan.
		*/
		{
			$log_id = $this->db->select('id')->from('log_surat')->where('nama_surat', $nama_surat)->limit(1)->get()->row()->id;
		}
		else
		// Cetak:
		// Sama dengan aturan Ekspor Dok, hanya URL-NIK-nomor surat-tanggal diambil dari data kolom
		{
			$log_id = $this->db->select('id')->from('log_surat')->
				where('id_format_surat', $data['id_format_surat'])->
				where('id_pend', $data['id_pend'])->
				where('no_surat', $data['no_surat'])->
				where('DATE_FORMAT(tanggal, "%Y-%m-%d") =', date('Y-m-d'))->
				limit(1)->get()->row()->id;
		}
		if ($log_id)
		{
			$this->db->where('id', $log_id);
			$this->db->update('log_surat', $data);
		}
		else
		{
			$this->db->insert('log_surat', $data);
		}

	}

	public function grafik()
	{
		$data = $this->db
				->select('f.nama, COUNT(l.id) as jumlah')
				->from('log_surat l')
				->join('tweb_surat_format f', 'l.id_format_surat=f.id', 'left')
				->group_by('f.nama')
				->get()
				->result_array();
		return $data;
	}

	public function update($id=0)
	{
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='')
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';
		$arsip = $this->db->select('nama_surat, lampiran')->
			where('id',$id)->
			get('log_surat')->
			row_array();
		$berkas_surat = pathinfo($arsip['nama_surat'], PATHINFO_FILENAME);
		unlink(LOKASI_ARSIP.$berkas_surat.".rtf");
		unlink(LOKASI_ARSIP.$berkas_surat.".pdf");
		if (!empty($arsip['lampiran'])) unlink(LOKASI_ARSIP.$arsip['lampiran']);

		if (!$this->db->where('id', $id)->delete('log_surat'))
		{	// Jika query delete terjadi error
			$_SESSION['success'] = -1;								// Maka, nilai success jadi -1, untuk memunculkan notifikasi error
			$error = $this->db->error();
			$_SESSION['error_msg'] = $error['message']; // Pesan error ditampung disession
		}
	}

	public function list_penduduk()
	{
		$sql = "SELECT id, nik, nama FROM tweb_penduduk WHERE status = 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
		}
		return $data;
	}

	public function jml_surat_keluar()
	{
		$jml = $this->db->select('count(*) as jml')->get('log_surat')->row()->jml;
		return $jml;
	}

	public function list_tahun_surat()
	{
		$query = $this->db->distinct()->
			select('YEAR(tanggal) AS tahun')->
			order_by('YEAR(tanggal)','DESC')->
			get('log_surat')->result_array();
		return $query;
	}

	public function list_jenis_surat()
	{
		$query = $this->db->distinct()->
			select('k.nama as nama_surat')->
			from('log_surat u')->
			join('tweb_surat_format k', 'u.id_format_surat = k.id', 'left')->
			order_by('nama_surat')->
			get()->result_array();
		return $query;
	}

}
?>
