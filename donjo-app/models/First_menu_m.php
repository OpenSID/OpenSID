<?php
class First_menu_m extends MY_Model{

	public function __construct()
	{
		parent::__construct();
	}

	private function list_submenu($menu_id)
	{
		$data	= $this->db->select('*')->where(array('parrent'=>$menu_id, 'enabled'=>1, 'tipe'=>3))->order_by('urut')->get('menu')->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			// 99 adalah link eksternal
			if ($data[$i]['link_tipe']!=99)
			{
				$data[$i]['link'] = $this->menu_slug($data[$i]['link']);
			}
		}

		return $data;
	}

	public function list_menu_atas()
	{
		$sql = "SELECT m.* FROM menu m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 1 order by urut asc";
		$query	= $this->db->query($sql);
		$data	= $query->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			if ($data[$i]['link_tipe'] != 99)
			{
				$data[$i]['link'] = $this->menu_slug($data[$i]['link']);
			}
			$data[$i]['submenu'] = $this->list_submenu($data[$i]['id']);
		}
		return $data;
	}

	private function list_subkategori($kategori_id)
	{
		$data	= $this->db->select('*')->select('kategori as nama')->where(array('parrent'=>$kategori_id, 'enabled'=>1))->order_by('urut')->get('kategori')->result_array();
		return $data;
	}

	public function list_menu_kiri()
	{
		$sql = "SELECT m.*, m.kategori as nama FROM kategori m WHERE m.parrent = 0 AND m.enabled = 1 AND m.kategori <> 'teks_berjalan' order by urut asc";
		$query = $this->db->query($sql);
		$data	= $query->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['submenu'] = $this->list_subkategori($data[$i]['id']);
		}
		return $data;
	}

}
