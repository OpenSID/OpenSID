<?

class Master_analisis_penduduk_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
		
	function autocomplete(){
		$sql   = "SELECT nama FROM master_analisis_penduduk";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['nama'].'"';
			$i++;
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		return $outp;
	}
	
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.aktif = $kf";
		return $filter_sql;
		}
	}
	
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.nama LIKE '$kw'";
			return $search_sql;
			}
		}
	
	function get_data($id=0){
		$sql   = "SELECT * FROM master_analisis_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	        }
		
	 function get_data_sub($id=0){
		$sql   = "SELECT * FROM sub_analisis_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	        }	
		
			
	function list_data(){
		$sql   = "SELECT u.*,(SELECT COUNT(id) FROM sub_analisis_penduduk WHERE id_master = u.id) AS jml_sub_analisis FROM master_analisis_penduduk u WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			
			if($data[$i]['aktif']==1)
				$data[$i]['aktif_text'] = "Aktif";
			else
				$data[$i]['aktif_text'] = "Tidak Aktif";
			
			if($data[$i]['jml_sub_analisis']<1)
				$data[$i]['jml_sub_analisis'] = "Belum Diisi";
			else
				$data[$i]['jml_sub_analisis'] = $data[$i]['jml_sub_analisis']." Jawaban";
				
			$i++;
		}
		return $data;
	}
	
	function list_data_sub($id=0){
		$sql   = "SELECT u.* FROM sub_analisis_penduduk u WHERE id_master = ?";
		
		$query = $this->db->query($sql,$id);
		$data  = $query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}
	
	function insert(){
        	$sql   = "SELECT * FROM master_analisis_penduduk WHERE id='$_POST[id]'";	
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		if(count($data)){
        		$outp = '';
        	}else{
        		$outp = $this->db->insert('master_analisis_penduduk',$_POST);
        	}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-2;
	}
	
	function insert_subx($id=0){
		$data = $_POST;
		$data['id_master'] = $id;
		
		$p = preg_split("/[#]+/", $data['sub_analisis_penduduk']);
		//print_r($p);
		//echo count($p);
		
		$i=0;
		while($i<count($p)){
			$outp = $this->db->insert('sub_analisis_penduduk',$data);
			$i++;
		}
		
		//echo $data['sub_analisis_penduduk'];
		//$outp = $this->db->insert('sub_analisis_penduduk',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function insert_sub($id=0){
		$sub = $_POST['sub'];
		$nilai = $_POST['nilai'];
		//$data['id_master'] = $id;
		
		$i=0;
		if(count($sub)){
			foreach($sub as $jawaban){
				$data['id_master'] = $id;
				$data['nama'] = $jawaban;
				$data['nilai'] = $nilai[$i];
				if($jawaban !=""){
					$outp = $this->db->insert('sub_analisis_penduduk',$data);
					$i++;
				}
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update($id=0){
		$this->db->where('id',$id);
		$outp = $this->db->update('master_analisis_penduduk',$_POST);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update_sub($id=0){
		$this->db->where('id',$id);
		$outp = $this->db->update('sub_analisis_penduduk',$_POST);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function locking($id=0,$val=0){
		$this->db->where('id',$id);
		$data['aktif'] = $val;
		$outp = $this->db->update('master_analisis_penduduk',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete($id=''){
		$sql  = "DELETE FROM master_analisis_penduduk WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_sub($id=''){
		$sql  = "DELETE FROM sub_analisis_penduduk WHERE id=?";
		$outp = $this->db->query($sql,array($id));
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function delete_all(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM master_analisis_penduduk WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
}

?>
