<?php class Plan_point_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'point');
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (nama LIKE '$kw')";
			return $search_sql;
			}
		}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND enabled = $kf";
			return $filter_sql;
		}
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
		$sql = " FROM point WHERE tipe = 0 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		switch($o){
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY id';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT * " . $this->list_data_sql();
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
	private function validasi($post)
	{
		$data['nama'] = nomor_surat_keputusan($post['nama']);
		$data['simbol'] = $post['simbol'];
		return $data;
	}


	public function insert()
	{
		$data = $this->validasi($this->input->post());
		$outp = $this->db->insert('point', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id=0)
	{
		$data = $this->validasi($this->input->post());
		$this->db->where('id', $id);
		$outp = $this->db->update('point', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('point');

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

	public function list_sub_point($point=1)
	{
		$sql = "SELECT * FROM point WHERE parrent = ? AND tipe = 2 ";

		$query = $this->db->query($sql, $point);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			if ($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";
		}
		return $data;
	}

	public function insert_sub_point($parrent=0)
	{
		$data = $this->validasi($this->input->post());
		$data['parrent'] = $parrent;
		$data['tipe'] = 2;
		$outp = $this->db->insert('point', $data);
		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_sub_point($id=0)
	{
		$data = $this->validasi($this->input->post());
		$this->db->where('id',$id);
		$outp = $this->db->update('point', $data);
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_sub_point($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('point');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_all_sub_point()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete_sub_point($id, $semua=true);
		}
	}

	public function point_lock($id='', $val=0)
	{
		$sql = "UPDATE point SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_point($id=0)
	{
		$sql = "SELECT * FROM point WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function list_simbol()
	{
		$sql = "SELECT * FROM gis_simbol WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

}
?>
