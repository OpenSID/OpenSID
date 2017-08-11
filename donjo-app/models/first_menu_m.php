<?php
class First_Menu_M extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function list_submenu($menu_id){
		$data	= $this->db->select('*')->where(array('parrent'=>$menu_id,'enabled'=>1,'tipe'=>3))->order_by('urut')->get('menu')->result_array();
		return $data;
	}
	function list_menu_atas(){
		$sql	= "SELECT m.* FROM menu m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 1 order by urut asc";
		$query	= $this->db->query($sql);
		$data	= $query->result_array();
		$url	= site_url()."first/";
		$i		= 0;
		while($i<count($data)){
			$data[$i]['submenu'] = $this->list_submenu($data[$i]['id']);
			$i++;
		}
		return $data;
	}
	function list_menu_kiri(){
		$sql	= "SELECT m.*,m.kategori AS nama FROM kategori m WHERE m.parrent =0 AND m.enabled = 1 AND m.kategori <> 'teks_berjalan' ORDER BY urut";
		$query	= $this->db->query($sql);
		$data	= $query->result_array();
		$url	= site_url()."first/kategori/";
		$i		= 0;
		while($i<count($data)){
				$data[$i]['menu'] = "<li><a href='$url".$data[$i]['id']."'>".$data[$i]['nama']."</a>";
				$sql2   = "SELECT s.*,s.kategori AS nama FROM kategori s WHERE s.parrent = ? AND s.enabled = 1 ORDER BY urut";
				$query = $this->db->query($sql2,$data[$i]['id']);
				$data2=$query->result_array();
				if($data2){
					$data[$i]['menu'] = $data[$i]['menu']."<ul>";
					$j=0;
					while($j<count($data2)){
							$data[$i]['menu'] = $data[$i]['menu']."<li><a href='$url".$data2[$j]['id']."'>".$data2[$j]['nama']."</a></li>";
						$j++;
					}
					$data[$i]['menu'] = $data[$i]['menu']."</ul>";
				}
				$data[$i]['menu'] = $data[$i]['menu']."</li>";
			$i++;
		}
		return $data;
	}
}