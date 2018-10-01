<?php class Analisis_kategori_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$str = autocomplete_str('kategori', 'analisis_kategori_indikator');
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.kategori LIKE '$kw'";
			return $search_sql;
		}
	}

	private function master_sql()
	{
		if (isset($_SESSION['analisis_master']))
		{
			$kf = $_SESSION['analisis_master'];
			$filter_sql = " AND u.id_master = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(id) AS id FROM analisis_kategori_indikator u WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->master_sql();
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

	public function list_data($o=0, $offset=0, $limit=500)
	{
		switch ($o)
		{
			case 3: $order_sql = ' ORDER BY u.kategori'; break;
			case 4: $order_sql = ' ORDER BY u.kategori DESC'; break;
			default:$order_sql = ' ORDER BY u.kategori';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql = "SELECT u.* FROM analisis_kategori_indikator u WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->master_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

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
		$data['id_master'] = $_SESSION['analisis_master'];
		$outp = $this->db->insert('analisis_kategori_indikator', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update($id=0)
	{
		$data = $_POST;
		$data['id_master']=$_SESSION['analisis_master'];
		$this->db->where('id', $id);
		$outp = $this->db->update('analisis_kategori_indikator', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		$sql = "DELETE FROM analisis_kategori_indikator WHERE id = ?";
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
				$sql = "DELETE FROM analisis_kategori_indikator WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function get_analisis_kategori($id=0)
	{
		$sql = "SELECT * FROM analisis_kategori_indikator WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}

	public function get_analisis_master()
	{
		$sql = "SELECT * FROM analisis_master WHERE id = ?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}
}
?>
