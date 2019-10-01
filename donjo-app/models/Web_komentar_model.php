<?php class Web_komentar_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$str = autocomplete_str('komentar', 'komentar');
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (komentar LIKE '$kw' OR komentar LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql= " AND k.enabled = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0, $cas=0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql($cas);
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

	private function list_data_sql($cas=0)
	{
		$sql = "FROM komentar k
			LEFT JOIN artikel a ON k.id_artikel = a.id
			WHERE 1 ";
		if ($cas == 2)
			$sql .= " AND id_artikel = 775";
		else
			$sql .= " AND id_artikel <> 775";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500, $cas=0)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY komentar DESC'; break;
			case 2: $order_sql = ' ORDER BY komentar'; break;
			case 3: $order_sql = ' ORDER BY enabled DESC'; break;
			case 4: $order_sql = ' ORDER BY enabled'; break;
			case 5: $order_sql = ' ORDER BY tgl_upload DESC'; break;
			case 6: $order_sql = ' ORDER BY tgl_upload'; break;

			default:$order_sql = ' ORDER BY tgl_upload DESC';
		}
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT k.*, a.judul as artikel " . $this->list_data_sql($cas);
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if ($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";
			$j++;
		}
		return $data;
	}

	public function list_kategori($tipe=1)
	{
		$sql = "SELECT * FROM kategori WHERE tipe = ?";
		$query = $this->db->query($sql, $tipe);
		return  $query->result_array();
	}

	public function insert()
	{
		$data = $_POST;
		$data['id_user'] = $_SESSION['user'];
		$outp = $this->db->insert('komentar', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update($id=0)
	{
	  $data = $_POST;
	  $data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		$outp = $this->db->update('komentar', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		$sql = "DELETE FROM komentar WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM komentar WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function komentar_lock($id='',$val=0)
	{
		$outp = $this->db->where('id', $id)
			->update('komentar', array(
					'enabled' => $val,
					'updated_at' => date('Y-m-d H:i:s')));
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function get_komentar($id=0)
	{
		$sql = "SELECT a.* FROM komentar a WHERE a.id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

}
?>
