<?php
class Kelompok_master_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('kelompok', 'kelompok_master');
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.kelompok LIKE '$kw' OR u.kelompok LIKE '$kw')";
			return $search_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(id) AS id " . $this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = "FROM kelompok_master u WHERE 1 ";
		$sql .= $this->search_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		switch ($o)
		{
			case 3: $order_sql = ' ORDER BY u.kelompok'; break;
			case 4: $order_sql = ' ORDER BY u.kelompok DESC'; break;
			default:$order_sql = ' ORDER BY u.kelompok';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT u.* " . $this->list_data_sql();

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

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
		$outp = $this->db->insert('kelompok_master', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id=0)
	{
		$data = $_POST;
		$this->db->where('id', $id);
		$outp = $this->db->update('kelompok_master', $data);
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kelompok_master');

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

	public function get_kelompok_master($id=0)
	{
		$sql = "SELECT * FROM kelompok_master WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}

	public function list_subjek()
	{
		$sql = "SELECT * FROM kelompok_ref_subjek";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
?>
