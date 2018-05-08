<?php
class Kelompok_master_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function autocomplete(){
		$sql = "SELECT kelompok FROM kelompok_master";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"' .$data[$i]['kelompok']. '"';
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
			$search_sql= " AND (u.kelompok LIKE '$kw' OR u.kelompok LIKE '$kw')";
			return $search_sql;
			}
		}
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.id = $kf";
		return $filter_sql;
		}
	}
	function state_sql(){		
		if(isset($_SESSION['state'])){
			$kf = $_SESSION['state'];
			$filter_sql= " AND u.lock = $kf";
		return $filter_sql;
		}
	}
	function paging($p=1,$o=0){
		$sql = "SELECT COUNT(id) AS id FROM kelompok_master u WHERE 1";
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
	function list_data($o=0,$offset=0,$limit=500){
		
		switch($o){
			case 1: $order_sql = ' ORDER BY u.kelompok'; break;
			case 2: $order_sql = ' ORDER BY u.kelompok DESC'; break;
			case 3: $order_sql = ' ORDER BY u.kelompok'; break;
			case 4: $order_sql = ' ORDER BY u.kelompok DESC'; break;
			case 5: $order_sql = ' ORDER BY g.kelompok'; break;
			case 6: $order_sql = ' ORDER BY g.kelompok DESC'; break;
			default:$order_sql = ' ORDER BY u.kelompok';
		}
		
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		
		$sql = "SELECT u.* FROM kelompok_master u WHERE 1 ";
			
		$sql .= $this->search_sql();
		
		
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			$i++;
			$j++;
		}
		return $data;
	}
	function insert(){
		$data = $_POST;
		$outp = $this->db->insert('kelompok_master',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update($id=0){
		$data = $_POST;
		$this->db->where('id',$id);
		$outp = $this->db->update('kelompok_master',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete($id=''){
		$sql = "DELETE FROM kelompok_master WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql = "DELETE FROM kelompok_master WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function get_kelompok_master($id=0){
		$sql = "SELECT * FROM kelompok_master WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function list_subjek(){
		$sql = "SELECT * FROM kelompok_ref_subjek";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
?>
