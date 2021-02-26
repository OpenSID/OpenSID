<?php class Permohonan_surat_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('referensi_model');
	}

	public function insert($data)
	{
		$outp = $this->db->insert('permohonan_surat', $data);
		return $outp;
	}

	public function delete($id_permohonan)
	{
		$outp = $this->db->where('id', $id_permohonan)
			->delete('permohonan_surat');
		if (!$outp)
			$this->session->set_userdata('success', -1);
	}

	public function update($id_permohonan, $data)
	{
		$outp = $this->db
			->where('id', $id_permohonan)
			->update('permohonan_surat', $data);
		return $outp;
	}

	public function autocomplete()
	{
		$data = $this->db->select('n.nik')
			->from('permohonan_surat u')
			->join('tweb_penduduk n', 'u.id_pemohon = n.id', 'left')
			->get()->result_array();

		$outp = '';
		foreach ($data as $baris)
		{
			$outp .= ",'" .$baris['nik']. "'";
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';

		return $outp;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (n.nik LIKE '$kw' OR n.nama LIKE '$kw')";
			return $search_sql;
			}
		}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND u.status = '".$kf."'";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$list_data_sql = $this->list_data_sql($log);
		$sql = "SELECT COUNT(*) AS jml ".$list_data_sql;
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
		$sql = "FROM permohonan_surat u
			LEFT JOIN tweb_penduduk n ON u.id_pemohon = n.id
			LEFT JOIN tweb_surat_format s ON u.id_surat = s.id
			WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.updated_at'; break;
			case 2: $order_sql = ' ORDER BY u.updated_at DESC'; break;
			default:$order_sql = ' ORDER BY u.updated_at DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$select_sql = "SELECT u.*, u.status as status_id, n.nama AS nama, n.nik AS nik, s.nama as jenis_surat ";
		$list_data_sql = $this->list_data_sql();
		$sql = $select_sql." ".$list_data_sql;

		$sql .= $order_sql;
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		$data = $query->result_array();
		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$data[$i]['status'] = $this->referensi_model->list_ref_flip(STATUS_PERMOHONAN)[$data[$i]['status']];
			$j++;
		}
		return $data;
	}

	public function list_permohonan_perorangan($id_pemohon)
	{
		$data = $this->db
			->select('u.*, u.status as status_id, n.nama AS nama, n.nik AS nik, s.nama as jenis_surat')
			->where('id_pemohon', $id_pemohon)
			->from('permohonan_surat u')
			->join('tweb_penduduk n', 'u.id_pemohon = n.id', 'left')
			->join('tweb_surat_format s', 'u.id_surat = s.id', 'left')
			->order_by('updated_at', 'DESC')
			->get()->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$data[$i]['status'] = $this->referensi_model->list_ref_flip(STATUS_PERMOHONAN)[$data[$i]['status']];
			$j++;
		}
		return $data;
	}

	public function get_permohonan($id_permohonan)
	{
		$data = $this->db
			->where('id', $id_permohonan)
			->get('permohonan_surat')
			->row_array();
		return $data;
	}

	public function list_data_status($id)
	{
		$this->db->select('id, status');
		$this->db->from('permohonan_surat');
		$this->db->where('id', $id);

		return $this->db->get()->row_array();
	}

	public function update_status($id, $data)
	{
		$this->db->where('id', $id);
		$outp = $this->db->update('permohonan_surat', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function ambil_isi_form($isian_form)
	{
		$isian_form = json_decode($isian_form, true);
		$hapus = array('url_surat', 'url_remote', 'nik', 'id_surat', 'nomor', 'pilih_atas_nama', 'pamong', 'pamong_nip', 'jabatan', 'pamong_id');
		foreach ($hapus as $kolom)
		{
			unset($isian_form[$kolom]);
		}
		return $isian_form;
	}

	public function get_syarat_permohonan($id)
	{
		$permohonan = $this->db->where('id', $id)
			->get('permohonan_surat')
			->row_array();
		$syarat_permohonan = json_decode($permohonan['syarat'], true);
		$dok_syarat = array_values($syarat_permohonan);
		if ($dok_syarat) $this->db->where_in('id', $dok_syarat);
		$dokumen_kelengkapan = $this->db
			->select('id, nama')
			->get('dokumen')
			->result_array();
		$dok_syarat = array();
		foreach ($dokumen_kelengkapan as $dok)
		{
			$dok_syarat[$dok['id']] = $dok['nama'];
		}
		$syarat_surat = $this->surat_master_model->get_syarat_surat($permohonan['id_surat']);
		for ($i = 0; $i < count($syarat_surat); $i++)
		{
			$dok_id = $syarat_permohonan[$syarat_surat[$i]['ref_syarat_id']];
			$syarat_surat[$i]['dok_id'] = $dok_id;
			$syarat_surat[$i]['dok_nama'] = $dok_syarat[$dok_id];
		}

		return $syarat_surat;
	}

}
