<?php class Analisis_periode_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function autocomplete(){
		$sql = "SELECT nama FROM analisis_periode";
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
	function state_sql(){		
		if(isset($_SESSION['state'])){
			$kf = $_SESSION['state'];
			$filter_sql= " AND u.id_state = $kf";
		return $filter_sql;
		}
	}
	function paging($p=1,$o=0){
		$sql = "SELECT COUNT(id) AS id FROM analisis_periode u WHERE 1";
		$sql .= $this->search_sql(); 
		$sql .= $this->master_sql(); 
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
			case 1: $order_sql = ' ORDER BY u.id'; break;
			case 2: $order_sql = ' ORDER BY u.id DESC'; break;
			case 3: $order_sql = ' ORDER BY u.id'; break;
			case 4: $order_sql = ' ORDER BY u.id DESC'; break;
			case 5: $order_sql = ' ORDER BY g.id'; break;
			case 6: $order_sql = ' ORDER BY g.id DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}
		
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		
		$sql = "SELECT u.*,s.nama AS status FROM analisis_periode u LEFT JOIN analisis_ref_state s ON u.id_state = s.id WHERE 1 ";
			
		$sql .= $this->search_sql();
		$sql .= $this->master_sql();
		$sql .= $this->state_sql(); 
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			
			if($data[$i]['aktif']==1)
			$data[$i]['aktif'] = "<img src='".base_url()."assets/images/icon/tick.png'>";
			else
			$data[$i]['aktif'] = "";
			
			$i++;
			$j++;
		}
		return $data;
	}
	function insert(){
		$data = $_POST;
		$dp = $data['duplikasi'];
		unset($data['duplikasi']);
		
		if($dp == 1){
			$sqld = "SELECT id FROM analisis_periode WHERE id_master=? ORDER BY id DESC LIMIT 1";
			$queryd = $this->db->query($sqld,$_SESSION['analisis_master']);
			$dpd = $queryd->row_array();
			$sblm = $dpd['id'];

		}
		
		
		
		$akt =array();
		$data['id_master']=$_SESSION['analisis_master'];
		if($data['aktif']==1){
			$akt['aktif']=2;
			$this->db->where('id_master',$_SESSION['analisis_master']);
			$this->db->update('analisis_periode',$akt);
		}
		$outp = $this->db->insert('analisis_periode',$data);
		
		if($dp == 1){
			$sqld = "SELECT id FROM analisis_periode WHERE id_master=? ORDER BY id DESC LIMIT 1";
			$queryd = $this->db->query($sqld,$_SESSION['analisis_master']);
			$dpd = $queryd->row_array();
			$skrg = $dpd['id'];
			
			
			$sql 	= "SELECT id_subjek,id_indikator,id_parameter FROM analisis_respon WHERE id_periode = ? "; 
			$query 	= $this->db->query($sql,$sblm);
			$data	= $query->result_array();
			
			$i=0;
			while($i<count($data)){
				$data[$i]['id_periode'] = $skrg;
				$i++;
			}
			$outp = $this->db->insert_batch('analisis_respon',$data);
			$this->load->model('analisis_respon_model');
			$this->analisis_respon_model->pre_update($skrg);
		}
		
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update($id=0){
		$data = $_POST;
		$akt =array();
		
		$data['id_master']=$_SESSION['analisis_master'];
		if($data['aktif']==1){
			$akt['aktif']=2;
			$this->db->where('id_master',$_SESSION['analisis_master']);
			$this->db->update('analisis_periode',$akt);
		}
		$data['id_master']=$_SESSION['analisis_master'];
		$this->db->where('id',$id);
		$outp = $this->db->update('analisis_periode',$data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete($id=''){
		$sql = "DELETE FROM analisis_periode WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql = "DELETE FROM analisis_periode WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function get_analisis_periode($id=0){
		$sql = "SELECT * FROM analisis_periode WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_analisis_master(){
		$sql = "SELECT * FROM analisis_master WHERE id=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}	
	function list_state(){
		$sql = "SELECT * FROM analisis_ref_state";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
