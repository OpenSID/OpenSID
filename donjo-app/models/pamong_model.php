<?php class Pamong_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function list_data(){
		$sql   = "SELECT u.* FROM tweb_desa_pamong u WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}
		
	function autocomplete(){
		$sql   = "SELECT pamong_nama FROM tweb_desa_pamong
					UNION SELECT pamong_nip FROM tweb_desa_pamong
					UNION SELECT pamong_nik FROM tweb_desa_pamong";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['pamong_nama']. "'";
			$i++;
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		return $outp;
	}
	
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.pamong_nama LIKE '$kw' OR u.pamong_nip LIKE '$kw' OR u.pamong_nik LIKE '$kw')";
			return $search_sql;
			}
		}
		
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.pamong_status = $kf";
		return $filter_sql;
		}
	}
	
	function get_data($id=0){
	
		$sql   = "SELECT * FROM tweb_desa_pamong WHERE pamong_id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	 }
	
	function insert(){
		$nip    		= penetration($this->input->post('pamong_nip'));
		$nama        	= penetration($this->input->post('pamong_nama'));
		$nik        		= penetration($this->input->post('pamong_nik'));
		$jabatan  	= penetration($this->input->post('jabatan'));
		$status  		= penetration($this->input->post('pamong_status'));
		
		$sql = "INSERT INTO tweb_desa_pamong (pamong_nama,pamong_nip,pamong_nik,jabatan,pamong_status,pamong_tgl_terdaftar)
				VALUES (?,?,?,?,?,NOW())";
				
		$outp = $this->db->query($sql, array($nama,$nip,$nik,$jabatan,$status));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function update($id=0){
		$nip    			= $this->input->post('pamong_nip');
		$nama        		= penetration($this->input->post('pamong_nama'));
		$nik        		= $this->input->post('pamong_nik');
		$jabatan  			= penetration($this->input->post('jabatan'));
		$status  			= $this->input->post('pamong_status');
		
		$sql  = "UPDATE tweb_desa_pamong SET pamong_nama=?,pamong_nip=?,pamong_nik=?,jabatan=?,pamong_status=? WHERE pamong_id=?";	
		$outp = $this->db->query($sql, array($nama,$nip,$nik,$jabatan,$status,$id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete($id=''){
		$sql  = "DELETE FROM tweb_desa_pamong WHERE pamong_id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_desa_pamong WHERE pamong_id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
}

?>
