<?php

class Web_menu_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$str = autocomplete_str('nama', 'menu');
		return $str;
	}

	private function search_sql($tip)
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

	public function paging($tip=0, $p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql();
		$query = $this->db->query($sql,$tip);
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
		$sql = " FROM menu WHERE tipe = ? ";
		$sql .= $this->search_sql($tip);
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($tip=0, $o=0, $offset=0, $limit=500)
	{
		switch($o)
		{
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY urut';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql = "SELECT * " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql, $tip);
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

	public function insert($tip=1)
	{
		$data = $_POST;
		$data['tipe'] = $tip;
		$data['urut'] = $this->urut_max($tip) + 1;
		$data['nama'] = strip_tags($data['nama']);
		$outp = $this->db->insert('menu',$data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;

	}

	public function update($id=0)
	{
		$data = $_POST;
		$data['nama'] = strip_tags($data['nama']);
		if ($data['link']=="")
			UNSET($data['link']);

		$this->db->where('id', $id);
		$outp = $this->db->update('menu', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		$sql = "DELETE FROM menu WHERE id = ? OR parrent = ?";
		$outp = $this->db->query($sql, array($id, $id));

		if (!$outp) $_SESSION['success'] = -1;
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id);
		}
	}

	public function list_sub_menu($menu=1)
	{
		$sql = "SELECT * FROM menu WHERE parrent = ? AND tipe = 3 ORDER BY urut";

		$query = $this->db->query($sql, $menu);
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

	public function list_link()
	{
		// '999' adalah id_kategori untuk artikel statis
		$sql = "SELECT a.id,a.judul FROM artikel a WHERE a.id_kategori = '999'";

		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function insert_sub_menu($menu=0)
	{
		$data = $_POST;
		$data['parrent'] = $menu;
		$data['tipe'] = 3;
		$data['urut'] = $this->urut_max(3, $menu) + 1;
		$outp = $this->db->insert('menu', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update_sub_menu($id=0)
	{
		$data = $_POST;
		if ($data['link'] == "")
		{
			UNSET($data['link']);
		}

		$this->db->where('id', $id);
		$outp = $this->db->update('menu', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_sub_menu($id='')
	{
		$sql = "DELETE FROM menu WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_all_sub_menu()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM menu WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function menu_lock($id='',$val=0)
	{
		$sql = "UPDATE menu SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function get_menu($id=0)
	{
		$sql = "SELECT * FROM menu WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data;
	}

  private function urut_max($tipe, $menu='')
  {
    $this->db->select_max('urut');
    if ($menu != '')
	    $this->db->where(array('tipe' => 3, 'parrent' => $menu));
	  else
	    $this->db->where('tipe', $tipe);
    $query = $this->db->get('menu');
    $menu = $query->row_array();
    return $menu['urut'];
  }

	private function urut_semua($tipe, $menu)
	{
		if ($menu != '')
		{
			$sql = "SELECT urut, COUNT(*) c
				FROM menu WHERE tipe = 3 AND parrent = ?
				GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql, $menu);
			$urut_duplikat = $query->result_array();
			$belum_diurut = $this->db->
				where('tipe', 3)->
				where('parrent', $menu)->
				where('urut IS NULL')->
				limit(1)->get('menu')->row_array();
			if ($urut_duplikat OR $belum_diurut)
			{
				$q = $this->db->select("id")
					->where("tipe", 3)
					->where('parrent', $menu)
					->order_by("urut")
					->get('menu');
				$menus = $q->result_array();
			}
		}
		else
		{
			$sql = "SELECT urut, COUNT(*) c
				FROM menu WHERE tipe = ?
				GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql, $tipe);
			$urut_duplikat = $query->result_array();
			$belum_diurut = $this->db->
				where('tipe', $tipe)->
				where('urut IS NULL')->
				limit(1)->get('menu')->row_array();
			if ($urut_duplikat OR $belum_diurut)
			{
				$q = $this->db->select("id")
					->where("tipe", $tipe)
					->order_by("urut")
					->get('menu');
				$menus = $q->result_array();
			}
		}
		for ($i=0; $i<count($menus); $i++)
		{
			$this->db->where('id', $menus[$i]['id']);
			$data['urut'] = $i + 1;
			$this->db->update('menu', $data);
		}
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	public function urut($id, $arah, $tipe=1, $menu='')
	{
		$this->urut_semua($tipe, $menu);
		$this->db->where('id', $id);
		$q = $this->db->get('menu');
		$menu1 = $q->row_array();

		$this->db->select("id, urut");
		if ($menu != '')
			$this->db->where(array("tipe" => 3, "parrent" => $menu));
		else
			$this->db->where("tipe", $tipe);
		$this->db->order_by("urut");
		$q = $this->db->get('menu');
		$menus = $q->result_array();
		for ($i=0; $i<count($menus); $i++)
		{
			if ($menus[$i]['id'] == $id)
				break;
		}

		if ($arah == 1)
		{
			if ($i >= count($menus) - 1) return;
			$menu2 = $menus[$i + 1];
		}
		if ($arah == 2)
		{
			if ($i <= 0) return;
			$menu2 = $menus[$i - 1];
		}

		// Tukar urutan
		$this->db->where('id', $menu2['id'])->
			update('menu', array('urut' => $menu1['urut']));
		$this->db->where('id', $menu1['id'])->
			update('menu', array('urut' => $menu2['urut']));
	}

}
?>
