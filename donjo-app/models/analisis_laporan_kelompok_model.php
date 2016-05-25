<?php class analisis_laporan_kelompok_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function autocomplete(){
		$sql   = "SELECT nama FROM kelompok WHERE 1  ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
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
			$search_sql= " AND (u.no_kk LIKE '$kw' OR p.nama LIKE '$kw')";
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
	
		$sql      = "SELECT COUNT(u.id) AS id FROM kelompok u LEFT JOIN tweb_penduduk p ON u.id_ketua = p.id WHERE 1";
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
		$pembagi = $this->get_analisis_master();
		$pembagi = $pembagi['pembagi'];
		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.id'; break;
			case 2: $order_sql = ' ORDER BY u.id DESC'; break;
			case 3: $order_sql = ' ORDER BY u.id'; break;
			case 4: $order_sql = ' ORDER BY u.id DESC'; break;
			case 5: $order_sql = ' ORDER BY cek'; break;
			case 6: $order_sql = ' ORDER BY cek DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}
	
		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		//Main Query
		$sql   = "SELECT u.*,p.nama as ketua,(SELECT SUM(i.bobot * nilai) FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = u.id AND i.act_analisis=1 AND r.id_periode=?)/$pembagi as cek FROM kelompok u LEFT JOIN tweb_penduduk p ON u.id_ketua = p.id WHERE 1 ";
			
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
			
			if($data[$i]['cek']){
			$data[$i]['nilai'] = $data[$i]['cek'];
			$data[$i]['set'] = "<img src='".base_url()."assets/images/icon/tick.png'>";
			
			$sql = "SELECT nama FROM analisis_klasifikasi WHERE minval < ? AND maxval > ?";
		$query = $this->db->query($sql,array($data[$i]['cek'],$data[$i]['cek']));
	//	echo $sql;
		$row = $query->row_array();
		$data[$i]['klasifikasi'] = $row['nama'];
			}else{
			$data[$i]['nilai'] = "-";
			$data[$i]['set'] = "<img src='".base_url()."assets/images/icon/cross.png'>";
			
		$data[$i]['klasifikasi'] = '-';
			}
			
			$i++;
			$j++;
		}
		return $data;
	}
	
	function list_jawab2($id=0,$in=0){	
	$per = $this->get_aktif_periode();
		$sql   = "SELECT s.id as id_parameter,s.jawaban as jawaban FROM analisis_respon r LEFT JOIN analisis_parameter s ON r.id_parameter = s.id WHERE r.id_subjek = ? AND r.id_periode = ? AND r.id_indikator=?";
		$query = $this->db->query($sql,array($id,$per,$in));
		$data = $query->row_array();
		
			if(empty($data['jawaban']))
			$data['jawaban'] = "";
		
		return $data['jawaban'];
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
			$data[$i]['parameter_laporan'] = $this->list_jawab2($id,$data[$i]['id']);
			else
			$data[$i]['parameter_laporan'] = $this->list_jawab2($id,$data[$i]['id']);
			
			
			if(empty($data[$i]['parameter_laporan']))
			$data[$i]['parameter_laporan'] = "-";
			
			$i++;
		}
		return $data;
	}
	
	function get_analisis_master(){
		$sql   = "SELECT * FROM analisis_master WHERE id=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}	
	
	function get_subjek($id=0){
		$sql   = "SELECT u.*,p.nama AS ketua FROM kelompok u LEFT JOIN tweb_penduduk p ON u.id_ketua = p.id WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}	
	
	function get_aktif_periode(){
		$sql   = "SELECT * FROM analisis_periode WHERE aktif=1 AND id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data  = $query->row_array();
		//echo $data['id'];
		return $data['id'];
	}
		
	function get_periode(){
		$sql   = "SELECT * FROM analisis_periode WHERE aktif=1 AND id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data  = $query->row_array();
		return $data['nama'];
	}
	
}

?>
