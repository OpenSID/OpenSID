<?php

class Web_Menu_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function autocomplete(){
		$sql   = "SELECT nama FROM menu";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['nama'].'"';
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (nama LIKE '$kw')";
			//return $search_sql;
			}
		}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND enabled = $kf";
		return $filter_sql;
		}
	}

	function paging($tip=0,$p=1,$o=0){

		$sql      = "SELECT COUNT(id) AS id FROM menu WHERE tipe = ?";
		$sql     .= $this->search_sql();
		$query    = $this->db->query($sql,$tip);
		$row      = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_data($tip=0,$o=0,$offset=0,$limit=500){

		switch($o){
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY urut';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		if($tip==1)
			$sql   = "SELECT * FROM menu WHERE tipe =? ";
		else
			$sql   = "SELECT k.id,k.kategori AS nama FROM kategori k WHERE 1";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql,$tip);
		$data=$query->result_array();

		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;

			if($data[$i]['enabled']==1)
				$data[$i]['aktif']="Yes";
			else
				$data[$i]['aktif']="No";

			$i++;
			$j++;
		}
		return $data;
	}

	function insert($tip=1){

		$data = $_POST;
			//$man = spliti("l]:",$data[$i]['link']);
			//if($man[0]=="[manual ur"){
			//	$data[$i]['link'] = $man[1];
		//	}
		if($data['manual_link']!=""){
			$data['link_tipe'] = 1;
			$data['link'] = $data['manual_link'];
		}
		UNSET($data['manual_link']);

		$data['tipe'] = $tip;
		$data['urut'] = $this->urut_max($tip) + 1;
		$outp = $this->db->insert('menu',$data);
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;

	}

	function update($id=0){


		$data = $_POST;
		/*if($data['manual_link']!=""){
			$data['link'] = "[manual url]:".$data['manual_link'];
			$man = spliti("l]:",$data['link']);
			if($man[0]=="[manual ur"){
				$data['link'] = $man[1];
			}
		}
		UNSET($data['manual_link']);
		*/

		if($data['manual_link']!=""){
			$data['link_tipe'] = 1;
			$data['link'] = $data['manual_link'];
		}else{
			$data['link_tipe'] = 0;
		}

		UNSET($data['manual_link']);

		if($data['link']=="")
			UNSET($data['link']);

		$this->db->where('id',$id);
		$outp = $this->db->update('menu',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM menu WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM menu WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function list_sub_menu($menu=1){

		$sql   = "SELECT * FROM menu WHERE parrent = ? AND tipe = 3 ORDER BY urut";

		$query = $this->db->query($sql,$menu);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;

			if($data[$i]['enabled']==1)
				$data[$i]['aktif']="Yes";
			else
				$data[$i]['aktif']="No";

			$i++;
		}
		return $data;
	}

	function list_link(){

		$sql   = "SELECT a.id,a.judul FROM artikel a WHERE a.id_kategori ='999'";

		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function list_kategori(){

		$sql   = "SELECT k.id,k.kategori AS nama FROM kategori k WHERE 1";

		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$data[$i]['judul']=$data[$i]['nama'];
			$i++;
		}
		return $data;
	}

	function insert_sub_menu($menu=0){
		$data = $_POST;

		/*$man = spliti("l]:",$data[$i]['link']);
		if($man[0]=="[manual ur"){
			$data[$i]['link'] = $man[1];
		}

		if($data['manual_link']!=""){
			$data['link'] = "[manual url]:".$data['manual_link'];
		}
		UNSET($data['manual_link']);
		*/

		if($data['manual_link']!=""){
			$data['link_tipe'] = 1;
			$data['link'] = $data['manual_link'];
		}
		UNSET($data['manual_link']);

		$data['parrent'] = $menu;
		$data['tipe'] = 3;
		$data['urut'] = $this->urut_max(3,$menu) + 1;
		$outp = $this->db->insert('menu',$data);
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}

	function update_sub_menu($id=0){
		$data = $_POST;

		/*$man = spliti("l]:",$data[$i]['link']);
		if($man[0]=="[manual ur"){
			$data[$i]['link'] = $man[1];
		}
		if($data['manual_link']!=""){
			$data['link'] = "[manual url]:".$data['manual_link'];
		}else{


		}

		UNSET($data['manual_link']);
		*/


		if($data['manual_link']!=""){
			$data['link_tipe'] = 1;
			$data['link'] = $data['manual_link'];
		}else{
			$data['link_tipe'] = 0;
		}
		if($data['link']==""){
			UNSET($data['link']);
		}

		$this->db->where('id',$id);
		$outp = $this->db->update('menu',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_sub_menu($id=''){
		$sql  = "DELETE FROM menu WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all_sub_menu(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM menu WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function menu_lock($id='',$val=0){

		$sql  = "UPDATE menu SET enabled=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_menu($id=0){
		$sql   = "SELECT * FROM menu WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function menu_show(){
		$sql   = "SELECT * FROM menu WHERE enabled=?";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;

	}

  function urut_max($tipe,$menu=''){
    $this->db->select_max('urut');
    if ($menu != '')
	    $this->db->where(array('tipe' => 3, 'parrent' => $menu));
	  else
	    $this->db->where('tipe', $tipe);
    $query = $this->db->get('menu');
    $menu = $query->row_array();
    return $menu['urut'];
  }

	function urut_semua($tipe, $menu){
		if ($menu != '') {
			$sql = "SELECT urut, COUNT(*) c FROM menu WHERE tipe = 3 AND parrent = ? GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql, $menu);
	}
		else {
			$sql = "SELECT urut, COUNT(*) c FROM menu WHERE tipe = ? GROUP BY urut HAVING c > 1";
			$query = $this->db->query($sql, $tipe);
		}
		$urut_duplikat = $query->result_array();
		if ($urut_duplikat) {
			$this->db->select("id");
			$this->db->where("tipe", $tipe);
			$this->db->order_by("urut");
			$q = $this->db->get('menu');
			$menus = $q->result_array();
			for ($i=0; $i<count($menus); $i++){
				$this->db->where('id', $menus[$i]['id']);
				$data['urut'] = $i + 1;
				$this->db->update('menu', $data);
			}
		}
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	function urut($id, $arah, $tipe=1, $menu=''){
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
		for ($i=0; $i<count($menus); $i++){
			if ($menus[$i]['id'] == $id) {
				break;
			}
		}

		if ($arah == 1) {
			if ($i >= count($menus) - 1) return;
			$menu2 = $menus[$i+1];
		}
		if ($arah == 2) {
			if ($i <= 0) return;
			$menu2 = $menus[$i-1];
		}

		// Tukar urutan
		$this->db->where('id', $menu2['id']);
		$data = array('urut' => $menu1['urut']);
		$this->db->update('menu', $data);
		$this->db->where('id', $menu1['id']);
		$data = array('urut' => $menu2['urut']);
		$this->db->update('menu', $data);
	}

}
?>
