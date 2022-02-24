<?php
class First_menu_m extends MY_Model{

	public function __construct()
	{
		parent::__construct();
	}

	private function list_submenu($parrent = 0)
	{
		$data	= $this->db->where(['parrent' => $parrent, 'enabled'=>1])->order_by('urut')->get('menu')->result_array();
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
		$data	= $this->db->where(['parrent' => 0, 'enabled'=>1])->order_by('urut')->get('menu')->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			// 99 adalah link eksternal
			if ($data[$i]['link_tipe'] != 99)
			{
				$data[$i]['link'] = $this->menu_slug($data[$i]['link']);
			}
			$data[$i]['submenu'] = $this->list_submenu($data[$i]['id']);
		}
		return $data;
	}

	private function list_kategori($parrent = 0)
	{
		$data = $this->db
			->where('enabled', 1)
			->where('parrent', $parrent)
			->order_by('urut')
			->get('kategori')
			->result_array();

		return $data;
	}

	public function list_menu_kiri()
	{
		$data	= $this->list_kategori();

		foreach ($data AS $key => $sub_menu) {
			$data[$key]['submenu'] = $this->list_kategori($sub_menu['id']);
		}

		return $data;
	}

}
