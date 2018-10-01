<?php class Analisis_indikator_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('analisis_master_model');
	}

	public function autocomplete()
	{
		$str = autocomplete_str('pertanyaan', 'analisis_indikator');
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.pertanyaan LIKE '$kw' OR u.pertanyaan LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.act_analisis = $kf";
		return $filter_sql;
		}
	}

	private function master_sql()
	{
		if (isset($_SESSION['analisis_master']))
		{
			$kf = $_SESSION['analisis_master'];
			$filter_sql= " AND u.id_master = $kf";
			return $filter_sql;
		}
	}

	private function tipe_sql()
	{
		if (isset($_SESSION['tipe']))
		{
			$kf = $_SESSION['tipe'];
			$filter_sql= " AND u.id_tipe = $kf";
			return $filter_sql;
		}
	}

	private function kategori_sql()
	{
		if (isset($_SESSION['kategori']))
		{
			$kf = $_SESSION['kategori'];
			$filter_sql= " AND u.id_kategori = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(id) AS id FROM analisis_indikator u WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->master_sql();
		$sql .= $this->tipe_sql();
		$sql .= $this->kategori_sql();
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
			case 1: $order_sql = ' ORDER BY u.nomor'; break;
			case 2: $order_sql = ' ORDER BY u.nomor DESC'; break;
			case 3: $order_sql = ' ORDER BY u.pertanyaan'; break;
			case 4: $order_sql = ' ORDER BY u.pertanyaan DESC'; break;
			case 5: $order_sql = ' ORDER BY u.id_kategori'; break;
			case 6: $order_sql = ' ORDER BY u.id_kategori DESC'; break;
			default:$order_sql = ' ORDER BY u.nomor';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql = "SELECT u.*,t.tipe AS tipe_indikator,k.kategori AS kategori FROM analisis_indikator u LEFT JOIN analisis_tipe_indikator t ON u.id_tipe = t.id LEFT JOIN analisis_kategori_indikator k ON u.id_kategori = k.id WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->master_sql();
		$sql .= $this->tipe_sql();
		$sql .= $this->kategori_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no']=$j+1;
			if ($data[$i]['act_analisis'] == 1)
				$data[$i]['act_analisis']="Ya";
			else
				$data[$i]['act_analisis']="Tidak";
			$j++;
		}
		return $data;
	}

	public function insert()
	{
		// Analisis sistem tidak boleh diubah
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) return;

		$data = $_POST;
		if ($data['id_tipe'] != 1)
		{
			$data['act_analisis'] = 2;
			$data['bobot'] = 0;
		}

		$data['id_master'] = $_SESSION['analisis_master'];
		$outp = $this->db->insert('analisis_indikator', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	private function update_indikator_sistem($id)
	{
		// Hanya kolom yang boleh diubah untuk analisis sistem
		$data['is_publik'] = $_POST['is_publik'];
		$this->db->where('id',$id)->update('analisis_indikator', $data);
		$_SESSION['success'] = 1;
	}

	public function update($id=0)
	{
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master']))
		{
			$this->update_indikator_sistem($id);
			return;
		}

		$data = $_POST;
		if ($data['id_tipe'] != 1)
		{
			$data['act_analisis'] = 2;
			$data['bobot'] = 0;
		}

		if ($data['id_tipe'] == 3 OR $data['id_tipe'] == 4)
		{
			$sql = "DELETE FROM analisis_parameter WHERE id_indikator=?";
			$this->db->query($sql, $id);

		}

		$data['id_master'] = $_SESSION['analisis_master'];
		$this->db->where('id', $id);
		$outp = $this->db->update('analisis_indikator', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		// Analisis sistem tidak boleh dihapus
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) return;

		$sql = "DELETE FROM analisis_indikator WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_all()
	{
		// Analisis sistem tidak boleh diubah
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) return;

		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM analisis_indikator WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function p_insert($in='')
	{
		// Analisis sistem tidak boleh diubah
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) return;

		$data = $_POST;
		$data['id_indikator'] = $in;
		$outp = $this->db->insert('analisis_parameter', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function p_update($id=0)
	{
		$data = $_POST;
		// Analisis sistem hanya kolom tertentu boleh diubah
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])){
			unset($data['kode_jawaban']);
			unset($data['jawaban']);
		}
		$this->db->where('id',$id);
		$outp = $this->db->update('analisis_parameter', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function p_delete($id='')
	{
		// Analisis sistem tidak boleh dihapus
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) return;

		$sql = "DELETE FROM analisis_parameter WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function p_delete_all()
	{
		// Analisis sistem tidak boleh diubah
		if ($this->analisis_master_model->is_analisis_sistem($_SESSION['analisis_master'])) return;

		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM analisis_parameter WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function list_indikator($id=0)
	{
		$sql = "SELECT * FROM analisis_parameter WHERE id_indikator = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function get_analisis_indikator($id=0)
	{
		$sql = "SELECT * FROM analisis_indikator WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	// TODO: ganti semua method get_analisis_master menggunakan yg di analisis_master_model
	public function get_analisis_master()
	{
		$sql = "SELECT * FROM analisis_master WHERE id = ?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}

	public function get_analisis_parameter($id='')
	{
		$sql = "SELECT * FROM analisis_parameter WHERE id = ?";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}

	public function list_tipe()
	{
		$sql = "SELECT * FROM analisis_tipe_indikator";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	// TODO: pindahkan ke analisis_kategori_model
	public function list_kategori()
	{
		$sql = "SELECT u.* FROM analisis_kategori_indikator u WHERE 1";
		$sql .= $this->master_sql();
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
?>
