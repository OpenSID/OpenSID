<?php class Analisis_klasifikasi_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function autocomplete(){
		$sql = "SELECT nama FROM analisis_klasifikasi";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"' .$data[$i]['nama']. '"';
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
			$search_sql= " AND (u.pertanyaan LIKE '$kw' OR u.pertanyaan LIKE '$kw')";
			return $search_sql;
			}
		}
	function master_sql(){		
		if(isset($_SESSION['analisis_master'])){
			$kf = $_SESSION['analisis_master'];
			$filter_sql= " AND u.id_master = $kf";
		return $filter_sql;
		}
	}
	function paging($p=1,$o=0){
		$sql = "SELECT COUNT(id) AS id FROM analisis_klasifikasi u WHERE 1";
		$sql .= $this->search_sql(); 
		$sql .= $this->master_sql(); 
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
			case 1: $order_sql = ' ORDER BY u.minval'; break;
			case 2: $order_sql = ' ORDER BY u.minval DESC'; break;
			case 3: $order_sql = ' ORDER BY u.minval'; break;
			case 4: $order_sql = ' ORDER BY u.minval DESC'; break;
			case 5: $order_sql = ' ORDER BY g.minval'; break;
			case 6: $order_sql = ' ORDER BY g.minval DESC'; break;
			default:$order_sql = ' ORDER BY u.minval';
		}
		
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		
		$sql = "SELECT u.* FROM analisis_klasifikasi u WHERE 1 ";
			
		$sql .= $this->search_sql();
		$sql .= $this->master_sql();
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
		$data['id_master']=$_SESSION['analisis_master'];
		$outp = $this->db->insert('analisis_klasifikasi',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update($id=0){
		$data = $_POST;
		$data['id_master']=$_SESSION['analisis_master'];
		$this->db->where('id',$id);
		$outp = $this->db->update('analisis_klasifikasi',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete($id=''){
		$sql = "DELETE FROM analisis_klasifikasi WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql = "DELETE FROM analisis_klasifikasi WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function get_analisis_klasifikasi($id=0){
		$sql = "SELECT * FROM analisis_klasifikasi WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_analisis_master(){
		$sql = "SELECT * FROM analisis_master WHERE id=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}	
}
?>
