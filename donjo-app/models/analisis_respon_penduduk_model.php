<?php class analisis_respon_penduduk_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function autocomplete(){
		$sql   = "SELECT nik FROM tweb_penduduk
		UNION SELECT nama FROM tweb_penduduk WHERE 1  ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"' .$data[$i]['nik']. '"';
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
			$search_sql= " AND (u.nik LIKE '$kw' OR u.nama LIKE '$kw')";
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
	
		$sql      = "SELECT COUNT(u.id) AS id FROM tweb_penduduk u WHERE 1";
		$sql     .= $this->search_sql(); 
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	
	function list_data($o=0,$offset=0,$limit=500){
	
	$per = $this->get_aktif_periode();
		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.id'; break;
			case 2: $order_sql = ' ORDER BY u.id DESC'; break;
			case 3: $order_sql = ' ORDER BY u.id'; break;
			case 4: $order_sql = ' ORDER BY u.id DESC'; break;
			case 5: $order_sql = ' ORDER BY g.id'; break;
			case 6: $order_sql = ' ORDER BY g.id DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}
	
		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		//Main Query
		$sql   = "SELECT u.id,u.nama,u.nik,(SELECT count(id) FROM analisis_respon WHERE id_subjek = u.id AND id_periode = ?) as cek FROM tweb_penduduk u WHERE 1 ";
			
		$sql .= $this->search_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql,$per);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			if($data[$i]['cek'])
			$data[$i]['set'] = "<img src='".base_url()."assets/images/icon/tick.png'>";
			else
			$data[$i]['set'] = "-";
			
			$i++;
			$j++;
		}
		return $data;
	}
	
	function update_kuisioner($id=0){
		$per = $this->get_aktif_periode();
		$id_rb = $_POST['rb'];
		$id_cb = $_POST['cb'];
		$id_ia = $_POST['ia'];
		$id_it = $_POST['it'];
		//print_r($id_ia);
		$sql   = "DELETE FROM analisis_respon WHERE id_subjek = ? AND id_periode=?";
		$this->db->query($sql,array($id,$per));
	//	$res = $query->result_array();
		
			foreach($id_rb as $id_p){
				$p = preg_split("/\./", $id_p);
				//echo $id."</br>";
				$data['id_subjek'] = $id;
				$data['id_periode'] = $this->get_aktif_periode();
				$data['id_indikator'] = $p[0];
				$data['id_parameter'] = $p[1];
				$outp = $this->db->insert('analisis_respon',$data);
			}
		if($id_cb){
			foreach($id_cb as $id_p){
				$p = preg_split("/\./", $id_p);
				//echo $id."</br>";
				$data['id_subjek'] = $id;
				$data['id_periode'] = $this->get_aktif_periode();
				$data['id_indikator'] = $p[1];
				$data['id_parameter'] = $p[0];
				$outp = $this->db->insert('analisis_respon',$data);
			}
		}
			foreach($id_ia as $id_p){
			if($id_p != ""){
				unset($data);
				$indikator = key($id_ia);next($id_ia);
				
		$sql   = "SELECT * FROM  analisis_parameter u WHERE jawaban = ?";
		$query = $this->db->query($sql,$id_p);
		$dx = $query->row_array();
		if(!$dx){
		
				$data['id_indikator'] = $indikator;
				$data['jawaban'] = $id_p;
				$this->db->insert('analisis_parameter',$data);
				unset($data);
				
		$sql   = "SELECT * FROM  analisis_parameter u WHERE jawaban = ?";
		$query = $this->db->query($sql,$id_p);
		$dx = $query->row_array();
		
				$data['id_parameter'] = $dx['id'];
				$data['id_indikator'] = $indikator;
				$data['id_subjek'] = $id;
				$data['id_periode'] = $this->get_aktif_periode();
				$outp = $this->db->insert('analisis_respon',$data);
			
		}else{
		
				unset($data);
				$data['id_indikator'] = $indikator;
				$data['id_parameter'] = $dx['id'];
				$data['id_subjek'] = $id;
				$data['id_periode'] = $this->get_aktif_periode();
				$outp = $this->db->insert('analisis_respon',$data);
		}
		}
		}
			
			foreach($id_it as $id_p){
			if($id_p != ""){
				unset($data);
				$indikator = key($id_it);next($id_it);
				
		$sql   = "SELECT * FROM  analisis_parameter u WHERE jawaban = ?";
		$query = $this->db->query($sql,$id_p);
		$dx = $query->row_array();
		if(!$dx){
		
				$data['id_indikator'] = $indikator;
				$data['jawaban'] = $id_p;
				$this->db->insert('analisis_parameter',$data);
				unset($data);
				
		$sql   = "SELECT * FROM  analisis_parameter u WHERE jawaban = ?";
		$query = $this->db->query($sql,$id_p);
		$dx = $query->row_array();
		
				$data2['id_parameter'] = $dx['id'];
				$data2['id_indikator'] = $indikator;
				$data2['id_subjek'] = $id;
				$data2['id_periode'] = $this->get_aktif_periode();
				$outp = $this->db->insert('analisis_respon',$data2);
			
		}else{
		
				unset($data);
				$data['id_indikator'] = $indikator;
				$data['id_parameter'] = $dx['id'];
				
				$data['id_subjek'] = $id;
				$data['id_periode'] = $this->get_aktif_periode();
				$outp = $this->db->insert('analisis_respon',$data);
		}
		}
		}
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function list_jawab2($id=0,$in=0){	
	$per = $this->get_aktif_periode();
		$sql   = "SELECT s.id as id_parameter,s.jawaban as jawaban,(SELECT count(id_subjek) FROM analisis_respon WHERE id_parameter = s.id AND id_subjek = ? AND id_periode=?) as cek FROM analisis_parameter s WHERE id_indikator = ? ";
		$query = $this->db->query($sql,array($id,$per,$in));
		$data = $query->result_array();
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			
			$i++;
		}
		return $data;
	}
	
	function list_jawab3($id=0,$in=0){	
	$per = $this->get_aktif_periode();
		$sql   = "SELECT s.id as id_parameter,s.jawaban as jawaban FROM analisis_respon r LEFT JOIN analisis_parameter s ON r.id_parameter = s.id WHERE r.id_indikator = ? AND r.id_subjek = ? AND r.id_periode=?";
		$query = $this->db->query($sql,array($in,$id,$per));
		$data = $query->row_array();
		//Formating Output
		return $data;
	}
	
	function list_indikator($id=0){	
		$sql   = "SELECT * FROM  analisis_indikator u WHERE 1 ";
		$sql     .= $this->master_sql(); 
		$query = $this->db->query($sql,$id);
		$data = $query->result_array();
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			if($data[$i]['id_tipe']==1 OR $data[$i]['id_tipe']==2)
			$data[$i]['parameter_respon'] = $this->list_jawab2($id,$data[$i]['id']);
			else
			$data[$i]['parameter_respon'] = $this->list_jawab3($id,$data[$i]['id']);
			
			$i++;
		}
		return $data;
	}

	function insert(){
		$data = $_POST;
		$data['id_master']=$_SESSION['analisis_master'];
		$outp = $this->db->insert('tweb_penduduk',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function update($id=0){
		$data = $_POST;

		$data['id_master']=$_SESSION['analisis_master'];
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete($id=''){
		$sql  = "DELETE FROM tweb_penduduk WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_penduduk WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function get_analisis_respon_penduduk($id=0){
		$sql   = "SELECT * FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_analisis_master(){
		$sql   = "SELECT * FROM analisis_master WHERE id=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}	
	
	function get_subjek($id=0){
		$sql   = "SELECT u.* FROM tweb_penduduk u WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}	
	
	function get_periode(){
		$sql   = "SELECT * FROM analisis_periode WHERE aktif=1 AND id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data  = $query->row_array();
		return $data['nama'];
	}
	
	function get_aktif_periode(){
		$sql   = "SELECT * FROM analisis_periode WHERE aktif=1 AND id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data  = $query->row_array();
		return $data['id'];
	}
		
}

?>
