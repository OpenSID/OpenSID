<?php
class Analisis_master_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$str = autocomplete_str('nama', 'analisis_master');
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
			}
		}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND u.subjek_tipe = $kf";
			return $filter_sql;
		}
	}

	private function state_sql()
	{
		if (isset($_SESSION['state']))
		{
			$kf = $_SESSION['state'];
			$filter_sql = " AND u.lock = $kf";
		return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(id) AS id FROM analisis_master u WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->state_sql();
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
			case 1: $order_sql = ' ORDER BY u.lock'; break;
			case 2: $order_sql = ' ORDER BY u.lock DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY s.subjek'; break;
			case 6: $order_sql = ' ORDER BY s.subjek DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT u.*,s.subjek FROM analisis_master u LEFT JOIN analisis_ref_subjek s ON u.subjek_tipe = s.id WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->state_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		$j = $offset;
		for ($i=0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j+1;
			if ($data[$i]['lock'] == 1)
				$data[$i]['lock'] = "<img src='".base_url()."assets/images/icon/unlock.png'>";
			else
				$data[$i]['lock'] = "<img src='".base_url()."assets/images/icon/lock.png'>";
			$j++;
		}
		return $data;
	}

	public function insert()
	{
		$data = $_POST;
		$outp = $this->db->insert('analisis_master', $data);

		if ($outp)
			$_SESSION['success'] = 1;
		else
			$_SESSION['success'] = -1;
	}

	public function update($id=0)
	{
		$data = $_POST;
		// Kolom yang tidak boleh diubah untuk analisis sistem
		if ($this->is_analisis_sistem($id))
		{
			unset($data['subjek_tipe']);
			unset($data['lock']);
			unset($data['format_impor']);
		}
		$this->db->where('id',$id);
		$outp = $this->db->update('analisis_master', $data);
		if ($outp)
			$_SESSION['success'] = 1;
		else
			$_SESSION['success'] = -1;
	}

	public function is_analisis_sistem($id)
	{
		$jenis = $this->db->select('jenis')->where('id', $id)
			->get('analisis_master')->row()->jenis;
		return $jenis == 1;
	}

	public function delete($id='')
	{
		if ($this->is_analisis_sistem($id)) return; // Jangan hapus analisis sistem

		$this->sub_delete($id);

		$sql = "DELETE FROM analisis_master WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp)
			$_SESSION['success'] = 1;
		else
			$_SESSION['success'] = -1;
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$this->delete($id);
			}
			$outp = true;
		}
		else $outp = false;

		if ($outp)
			$_SESSION['success'] = 1;
		else
			$_SESSION['success'] = -1;
	}

	private function sub_delete($id='')
	{
		$sql = "DELETE FROM analisis_parameter WHERE id_indikator IN(SELECT id FROM analisis_indikator WHERE id_master = ?)";
		$this->db->query($sql, $id);

		$sql = "DELETE FROM analisis_respon WHERE id_periode IN(SELECT id FROM analisis_periode WHERE id_master = ?)";
		$this->db->query($sql, $id);

		$sql = "DELETE FROM analisis_kategori_indikator WHERE id_master = ?";
		$this->db->query($sql, $id);

		$sql = "DELETE FROM analisis_klasifikasi WHERE id_master = ?";
		$this->db->query($sql, $id);

		$sql = "DELETE FROM analisis_respon_hasil WHERE id_master = ?";
		$this->db->query($sql, $id);

		$sql = "DELETE FROM analisis_partisipasi WHERE id_master = ?";
		$this->db->query($sql, $id);

		$sql = "DELETE FROM analisis_periode WHERE id_master = ?";
		$this->db->query($sql, $id);

		$sql = "DELETE FROM analisis_indikator WHERE id_master = ?";
		$this->db->query($sql, $id);
	}

	public function get_analisis_master($id=0)
	{
		$sql = "SELECT * FROM analisis_master WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function list_subjek()
	{
		$sql = "SELECT * FROM analisis_ref_subjek";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function list_kelompok()
	{
		$sql = "SELECT * FROM kelompok_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function list_analisis_child()
	{
		$sql = "SELECT * FROM analisis_master WHERE subjek_tipe = 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
