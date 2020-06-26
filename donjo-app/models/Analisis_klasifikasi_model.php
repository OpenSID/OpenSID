<?php class Analisis_klasifikasi_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'analisis_klasifikasi');
	}

	public function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw')";
			return $search_sql;
		}
	}

	public function master_sql()
	{
		if (isset($_SESSION['analisis_master']))
		{
			$kf = $_SESSION['analisis_master'];
			$filter_sql= " AND u.id_master = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(id) AS id FROM analisis_klasifikasi u WHERE 1";
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
			case 1: $order_sql = ' ORDER BY u.minval'; break;
			case 2: $order_sql = ' ORDER BY u.minval DESC'; break;
			case 3: $order_sql = ' ORDER BY u.minval'; break;
			case 4: $order_sql = ' ORDER BY u.minval DESC'; break;
			case 5: $order_sql = ' ORDER BY g.minval'; break;
			case 6: $order_sql = ' ORDER BY g.minval DESC'; break;
			default:$order_sql = ' ORDER BY u.minval';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT u.* FROM analisis_klasifikasi u WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->master_sql();
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

	private function validasi_data($post)
	{
		$data = array();
		$data['nama'] = nomor_surat_keputusan($post['nama']);
		$data['minval'] = bilangan_titik($post['minval']);
		$data['maxval'] = bilangan_titik($post['maxval']);
		return $data;
	}

	public function insert()
	{
		$data = $this->validasi_data($this->input->post());
		$data['id_master'] = $this->session->analisis_master;
		$outp = $this->db->insert('analisis_klasifikasi', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id=0)
	{
		$data = $this->validasi_data($this->input->post());
		$data['id_master'] = $this->session->analisis_master;
		$this->db->where('id',$id);
		$outp = $this->db->update('analisis_klasifikasi', $data);
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('analisis_klasifikasi');

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

	public function get_analisis_klasifikasi($id=0)
	{
		$sql = "SELECT * FROM analisis_klasifikasi WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function get_analisis_master()
	{
		$sql = "SELECT * FROM analisis_master WHERE id = ?";
		$query = $this->db->query($sql, $_SESSION['analisis_master']);
		return $query->row_array();
	}
}
?>
