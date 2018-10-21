<?php

class Web_kategori_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$data = $this->db->distinct()->
			select('kategori')->
			where('parrent', 0)->
			order_by('kategori')->
			get('kategori')->result_array();
		return autocomplete_data_ke_str($data);
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (kategori LIKE '$kw')";
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
		$sql = " FROM kategori k WHERE parrent = 0";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY kategori'; break;
			case 2: $order_sql = ' ORDER BY kategori DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY urut';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql = "SELECT k.*, k.kategori AS kategori " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data =$query->result_array();

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

	public function insert()
	{
		$data = $_POST;
		$data['enabled'] = 1;
		$data['urut'] = $this->urut_max() + 1;
		$outp = $this->db->insert('kategori', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;

	}

	public function update($id=0)
	{
		$data = $_POST;
		$this->db->where('id',$id);
		$outp = $this->db->update('kategori', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		$sql = "DELETE FROM kategori WHERE id = ?";
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
				$sql = "DELETE FROM kategori WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function list_sub_kategori($kategori=1)
	{
		$sql = "SELECT * FROM kategori WHERE parrent = ? ORDER BY urut";

		$query = $this->db->query($sql, $kategori);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;

			if($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";
		}
		return $data;
	}

	public function list_link()
	{
		$sql = "SELECT a.*
			FROM artikel a
			INNER JOIN kategori k ON a.id_kategori = k.id
			WHERE tipe = '2'";

		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function list_kategori($o="")
	{
		if (empty($o)) $urut = "urut";
		else $urut = $o;

		$sql = "SELECT k.id,k.kategori AS kategori FROM kategori k WHERE 1 ORDER BY $urut";

		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['judul'] = $data[$i]['kategori'];
		}
		return $data;
	}

	public function insert_sub_kategori($kategori=0)
	{
		$data = $_POST;

		$data['parrent'] = $kategori;
		$data['enabled'] = 1;
		$data['urut'] = $this->urut_max($kategori) + 1;
		$outp = $this->db->insert('kategori', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update_sub_kategori($id=0)
	{
		$data = $_POST;

		$this->db->where('id', $id);
		$outp = $this->db->update('kategori', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_sub_kategori($id='')
	{
		$sql = "DELETE FROM kategori WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_all_sub_kategori()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM kategori WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function kategori_lock($id='', $val=0)
	{
		$sql = "UPDATE kategori SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function get_kategori($id=0)
	{
		$sql = "SELECT * FROM kategori WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data;
	}

  private function urut_max($kategori='')
  {
    $this->db->select_max('urut');
    if ($kategori != '')
	    $this->db->where('parrent', $kategori);
	  else
	    $this->db->where('parrent', 0);
    $query = $this->db->get('kategori');
    $kategori = $query->row_array();
    return $kategori['urut'];
  }

	private function urut_semua($kategori)
	{
		if ($kategori != '')
		{
			$sql = "SELECT urut, COUNT(*) c
				FROM kategori
				WHERE parrent = ?
				GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql, $kategori);
		}
		else
		{
			$sql = "SELECT urut, COUNT(*) c
				FROM kategori
				WHERE parrent = 0
				GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql);
		}
		$urut_duplikat = $query->result_array();
		if ($urut_duplikat)
		{
			$this->db->select("id");
			if ($kategori != '')
				$this->db->where("parrent", $kategori);
			else
				$this->db->where("parrent", 0);
			$this->db->order_by("urut");
			$q = $this->db->get('kategori');
			$kategoris = $q->result_array();
			for ($i=0; $i<count($kategoris); $i++)
			{
				$this->db->where('id', $kategoris[$i]['id']);
				$data['urut'] = $i + 1;
				$this->db->update('kategori', $data);
			}
		}
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	public function urut($id, $arah, $kategori='')
	{
		$this->urut_semua($kategori);
		$this->db->where('id', $id);
		$q = $this->db->get('kategori');
		$kategori1 = $q->row_array();

		$this->db->select("id, urut");
		if ($kategori != '')
			$this->db->where(array("parrent" => $kategori));
		else
			$this->db->where(array("parrent" => 0));
		$this->db->order_by("urut");
		$q = $this->db->get('kategori');
		$kategoris = $q->result_array();
		for ($i=0; $i<count($kategoris); $i++)
		{
			if ($kategoris[$i]['id'] == $id)
				break;
		}

		if ($arah == 1)
		{
			if ($i >= count($kategoris) - 1) return;
			$kategori2 = $kategoris[$i + 1];
		}
		if ($arah == 2)
		{
			if ($i <= 0) return;
			$kategori2 = $kategoris[$i - 1];
		}

		// Tukar urutan
		$this->db->where('id', $kategori2['id'])->
			update('kategori', array('urut' => $kategori1['urut']));
		$this->db->where('id', $kategori1['id'])->
			update('kategori', array('urut' => $kategori2['urut']));
	}

}
?>
