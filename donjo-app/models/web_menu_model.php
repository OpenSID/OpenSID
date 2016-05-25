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
			return $search_sql;
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
			default:$order_sql = ' ORDER BY id';
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
	
		$sql   = "SELECT * FROM menu WHERE parrent = ? AND tipe = 3 ";
			
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
	
		$sql   = "SELECT a.* FROM artikel a INNER JOIN kategori k ON a.id_kategori=k.id WHERE tipe ='2'";
			
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
		UNSET($data['manual_link']);
		
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
	
	function list_menu_atas(){
	
		//$sql   = "SELECT m.*,s.nama as sub_menu,s.link as s_link FROM menu m LEFT JOIN menu s ON m.id = s.parrent WHERE m.parrent = 1 AND m.enabled = 1 AND (s.enabled = 1 OR s.enabled IS NULL) AND m.tipe = 1";
			
		$sql   = "SELECT m.* FROM menu m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 1";
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		$url = site_url("first");
		$i=0;
		while($i<count($data)){
				$data[$i]['menu'] = "<li><a href='$url/".$data[$i]['link']."'>".$data[$i]['nama']."</a>";
				
				$sql2   = "SELECT s.* FROM menu s WHERE s.parrent = ? AND s.enabled = 1 AND s.tipe = 3";
				$query = $this->db->query($sql2,$data[$i]['id']);
				$data2=$query->result_array();
				
				if($data2){
					$data[$i]['menu'] = $data[$i]['menu']."<ul>";
					$j=0;
					while($j<count($data2)){
						$data[$i]['menu'] = $data[$i]['menu']."<li><a href='$url/".$data2[$j]['link']."'>".$data2[$j]['nama']."</a></li>";
						$j++;
					}
					$data[$i]['menu'] = $data[$i]['menu']."</ul>";
				}
				$data[$i]['menu'] = $data[$i]['menu']."</li>";
			$i++;
		}
		return $data;
	}
	
	function list_menu_kiri(){
	
		//$sql   = "SELECT m.*,s.nama as sub_menu,s.link as s_link FROM menu m LEFT JOIN menu s ON m.id = s.parrent WHERE m.parrent = 1 AND m.enabled = 1 AND (s.enabled = 1 OR s.enabled IS NULL) AND m.tipe = 1";
			
		$sql   = "SELECT m.* FROM menu m WHERE m.parrent = 1 AND m.enabled = 1 AND m.tipe = 2";
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		$url = site_url("first");
		$i=0;
		while($i<count($data)){
				$data[$i]['menu'] = "<li><a href='$url/".$data[$i]['link']."'>".$data[$i]['nama']."</a>";
				
				$sql2   = "SELECT s.* FROM menu s WHERE s.parrent = ? AND s.enabled = 1 AND s.tipe = 3";
				$query = $this->db->query($sql2,$data[$i]['id']);
				$data2=$query->result_array();
				
				if($data2){
					$data[$i]['menu'] = $data[$i]['menu']."<ul>";
					$j=0;
					while($j<count($data2)){
						$data[$i]['menu'] = $data[$i]['menu']."<li><a href='$url/".$data2[$j]['link']."'>".$data2[$j]['nama']."</a></li>";
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
?>
