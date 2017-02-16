<?php
?><?php class plan_area_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function autocomplete(){
		$sql = "SELECT nama FROM area";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
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
			$search_sql= " AND l.nama LIKE '$kw'";
			return $search_sql;
			}
		}
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND l.enabled = $kf";
		return $filter_sql;
		}
	}
	function polygon_sql(){		
		if(isset($_SESSION['polygon'])){
			$kf = $_SESSION['polygon'];
			$polygon_sql= " AND p.id = $kf";
		return $polygon_sql;
		}
	}
	function subpolygon_sql(){		
		if(isset($_SESSION['subpolygon'])){
			$kf = $_SESSION['subpolygon'];
			$subpolygon_sql= " AND m.id = $kf";
		return $subpolygon_sql;
		}
	}
	function paging($p=1,$o=0){
		$sql = "SELECT COUNT(l.id) AS id FROM area l LEFT JOIN polygon p ON l.ref_polygon = p.id LEFT JOIN polygon m ON p.parrent = m.id WHERE 1 ";
		$sql .= $this->search_sql(); 
		$sql .= $this->filter_sql();
		$sql .= $this->polygon_sql(); 
		$sql .= $this->subpolygon_sql(); 
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
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY id';
		}
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		$sql = "SELECT l.*,p.nama AS kategori,m.nama AS jenis,p.simbol AS simbol,p.color AS color FROM area l LEFT JOIN polygon p ON l.ref_polygon = p.id LEFT JOIN polygon m ON p.parrent = m.id ";
			
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->polygon_sql();
		$sql .= $this->subpolygon_sql(); 
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
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
	function insert(){
		
		 $data = $_POST;
		 $area_file = $_FILES['foto']['tmp_name'];
		 $tipe_file = $_FILES['foto']['type'];
		 $nama_file = $_FILES['foto']['name'];
		 if (!empty($area_file)){
			if ($tipe_file == "image/jpg" OR $tipe_file == "image/jpeg"){
				Uploadarea($nama_file);
				$data['foto'] = $nama_file;
				$outp = $this->db->insert('area',$data);
			}
		}else{
			unset($data['foto']);
			$outp = $this->db->insert('area',$data);
		}
		
		if($outp)
			$_SESSION['success']=1;
		else
			$_SESSION['success']=-1;
	}
	function update($id=0){
		 $data = $_POST;
		 $area_file = $_FILES['foto']['tmp_name'];
		 $tipe_file = $_FILES['foto']['type'];
		 $nama_file = $_FILES['foto']['name'];
		 if (!empty($area_file)){
			if ($tipe_file == "image/jpg" OR $tipe_file == "image/jpeg"){
				Uploadarea($nama_file);
				$data['foto'] = $nama_file;
				$this->db->where('id',$id);
				$outp = $this->db->update('area',$data);
			}
		}else{
			unset($data['foto']);
			$this->db->where('id',$id);
			$outp = $this->db->update('area',$data);
		}
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
 }
	function delete($id=''){
		$sql = "DELETE FROM area WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql = "DELETE FROM area WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function list_polygon(){
		$sql = "SELECT * FROM polygon WHERE tipe = 2 ";
		
		if(isset($_SESSION['subpolygon'])){
			$kf = $_SESSION['subpolygon'];
			$sql .= " AND parrent = $kf";
		}
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function list_subpolygon(){
		$sql = "SELECT * FROM polygon WHERE tipe = 0 ";
		
		if(isset($_SESSION['polygon'])){
			
			$sqlx = "SELECT * FROM polygon WHERE id = ?";
			$query = $this->db->query($sqlx,$_SESSION['polygon']);
			$temp=$query->row_array();
		
			$kf = $temp['parrent'];
			
			
		}
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function area_lock($id='',$val=0){
		
		$sql = "UPDATE area SET enabled=? WHERE id=?";
		$outp = $this->db->query($sql, array($val,$id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function get_area($id=0){
		$sql = "SELECT * FROM area WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function update_position($id=0){
		$data = $_POST;
		$this->db->where('id',$id);
		$outp = $this->db->update('area',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
			
	function list_dusun(){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function get_desa(){
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
}
?>