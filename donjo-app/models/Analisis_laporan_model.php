<?php class Analisis_laporan_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function autocomplete(){
		$sql = "SELECT no_kk FROM tweb_keluarga
		UNION SELECT t.nama FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1 ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"' .$data[$i]['no_kk']. '"';
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			//$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$cari. '%';

			$subjek = $_SESSION['subjek_tipe'];
			switch($subjek){
				case 1: $search_sql= " AND (u.nik LIKE '$kw' OR u.nama LIKE '$kw')"; break;
				case 2: $search_sql= " AND (u.no_kk LIKE '$kw' OR p.nama LIKE '$kw')"; break;
				case 3: $search_sql= " AND ((u.no_kk LIKE '$kw' OR p.nama LIKE '$kw') OR ((SELECT COUNT(id) FROM tweb_penduduk WHERE nik LIKE '$kw' AND id_rtm = u.id) > 1) OR ((SELECT COUNT(id) FROM tweb_penduduk WHERE nama LIKE '$kw' AND id_rtm = u.id) > 1))"; break;
				case 4: $search_sql= " AND (u.nama LIKE '$kw' OR p.nama LIKE '$kw')"; break;
				default: return null;
			}
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
		
	function dusun_sql(){		
		if(isset($_SESSION['dusun'])){
			$kf = $_SESSION['dusun'];
			$dusun_sql= " AND c.dusun = '$kf'";
		return $dusun_sql;
		}
	}
	function rw_sql(){		
		if(isset($_SESSION['rw'])){
			$kf = $_SESSION['rw'];
			$rw_sql= " AND c.rw = '$kf'";
		return $rw_sql;
		}
	}
	function rt_sql(){		
		if(isset($_SESSION['rt'])){
			$kf = $_SESSION['rt'];
			$rt_sql= " AND c.rt = '$kf'";
		return $rt_sql;
		}
	}
	function klasifikasi_sql(){		
		if(isset($_SESSION['klasifikasi'])){
			$kf = $_SESSION['klasifikasi'];
			$klasifikasi_sql= " AND k.id = '$kf' ";
		return $klasifikasi_sql;
		}
	}
	function jawab_sql(){		
		if(isset($_SESSION['jawab'])){
			$per = $this->get_aktif_periode();
			$kf = $_SESSION['jawab'];
			$jmkf =  $_SESSION['jmkf'];
			$jawab_sql= "AND x.id_parameter IN ($kf) AND ((SELECT COUNT(id_parameter) FROM analisis_respon WHERE id_subjek = u.id AND id_periode = $per AND id_parameter IN ($kf)) = $jmkf) ";
		return $jawab_sql;
		}
	}
	function paging($p=1,$o=0){
		$subjek = $_SESSION['subjek_tipe'];
		$master = $this->get_analisis_master();
		$id_kelompok = $master['id_kelompok'];
		
		$per = $this->get_aktif_periode();
		$pembagi = $this->get_analisis_master();
		$pembagi = $pembagi['pembagi']+0;
		
		switch($subjek){
			case 1: $sql = "SELECT COUNT(DISTINCT u.id) AS id FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id"; break;
			case 2: $sql = "SELECT COUNT(DISTINCT u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk p ON u.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id"; break;
			case 3: $sql = "SELECT COUNT(DISTINCT u.id) AS id FROM tweb_rtm u LEFT JOIN tweb_penduduk p ON u.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id"; break;
			case 4: $sql = "SELECT COUNT(DISTINCT u.id) AS id FROM kelompok u LEFT JOIN tweb_penduduk p ON u.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id"; break;
			
			default: return null;
			
		}
		
		if(isset($_SESSION['jawab'])){
			
			$sql .= " LEFT JOIN analisis_respon x ON u.id = x.id_subjek";
			$sql .= " LEFT JOIN analisis_respon_hasil h ON u.id = h.id_subjek LEFT JOIN analisis_klasifikasi k ON h.akumulasi/$pembagi >= k.minval AND h.akumulasi/$pembagi <= k.maxval WHERE h.id_periode = ? AND x.id_periode = ? AND k.id_master = ? ";
			$sql .= $this->search_sql(); 
			$sql .= $this->klasifikasi_sql(); 
			$sql .= $this->dusun_sql(); 
			$sql .= $this->rw_sql(); 
			$sql .= $this->rt_sql(); 
			$sql .= $this->jawab_sql();
			$query = $this->db->query($sql,array($per,$per,$_SESSION['analisis_master']));
		}else{
			$sql .= " LEFT JOIN analisis_respon_hasil h ON u.id = h.id_subjek LEFT JOIN analisis_klasifikasi k ON h.akumulasi/$pembagi >= k.minval AND h.akumulasi/$pembagi <= k.maxval WHERE h.id_periode = ? AND k.id_master =?";
			$sql .= $this->search_sql(); 
			$sql .= $this->klasifikasi_sql(); 
			$sql .= $this->dusun_sql(); 
			$sql .= $this->rw_sql(); 
			$sql .= $this->rt_sql(); 
			$sql .= $this->jawab_sql(); 
			$query = $this->db->query($sql,array($per,$_SESSION['analisis_master']));
		}
		
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
		$per = $this->get_aktif_periode();
		$pembagi = $this->get_analisis_master();
		$pembagi = $pembagi['pembagi']+0;

		switch($o){
			case 1: $order_sql = ' ORDER BY u.id'; break;
			case 2: $order_sql = ' ORDER BY u.id DESC'; break;
			case 3: $order_sql = ' ORDER BY u.id'; break;
			case 4: $order_sql = ' ORDER BY u.id DESC'; break;
			case 5: $order_sql = ' ORDER BY cek'; break;
			case 6: $order_sql = ' ORDER BY cek DESC'; break;
			default:$order_sql = '';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		$subjek = $_SESSION['subjek_tipe'];
		switch($subjek){
			case 1: $sql = "SELECT u.id,u.nik AS uid,u.nama,c.dusun,c.rw,c.rt,u.sex,h.akumulasi/$pembagi AS cek,k.nama AS klasifikasi FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id "; break;
			
			case 2: $sql = "SELECT u.id,u.no_kk AS uid,p.nama,c.dusun,c.rw,c.rt,p.sex,h.akumulasi/$pembagi AS cek,k.nama AS klasifikasi FROM tweb_keluarga u LEFT JOIN tweb_penduduk p ON u.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id " ; break;
			
			case 3: $sql = "SELECT u.id,u.no_kk AS uid,p.nama,c.dusun,c.rw,c.rt,p.sex,h.akumulasi/$pembagi AS cek,k.nama AS klasifikasi FROM tweb_rtm u LEFT JOIN tweb_penduduk p ON u.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id "; break;
			
			case 4: $sql = "SELECT u.id,u.kode AS nid,u.nama,p.sex,c.dusun,c.rw,c.rt,h.akumulasi/$pembagi AS cek,k.nama AS klasifikasi FROM kelompok u LEFT JOIN tweb_penduduk p ON u.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id "; break;
			
			default: return null;
			
		}
		
		if(isset($_SESSION['jawab'])){
			$sql .= "LEFT JOIN analisis_respon x ON u.id = x.id_subjek ";
			$sql .= "LEFT JOIN analisis_respon_hasil h ON u.id = h.id_subjek LEFT JOIN analisis_klasifikasi k ON h.akumulasi/$pembagi > k.minval AND h.akumulasi/$pembagi <= k.maxval ";
			$sql .= "WHERE h.id_periode = ? AND x.id_periode = ? AND k.id_master = ? ";
			$sql .= $this->search_sql();
			$sql .= $this->klasifikasi_sql(); 
			$sql .= $this->dusun_sql(); 
			$sql .= $this->rw_sql(); 
			$sql .= $this->rt_sql(); 
			$sql .= $this->jawab_sql(); 
			$sql .= " GROUP BY u.id ";
			$sql .= $order_sql;
			$sql .= $paging_sql;
			$query = $this->db->query($sql,array($per,$per,$_SESSION['analisis_master']));
		}else{
			$sql .= "LEFT JOIN analisis_respon_hasil h ON u.id = h.id_subjek LEFT JOIN analisis_klasifikasi k ON h.akumulasi/$pembagi > k.minval AND h.akumulasi/$pembagi <= k.maxval ";
			$sql .= "WHERE h.id_periode = ? AND k.id_master = ?";
			$sql .= $this->search_sql();
			$sql .= $this->klasifikasi_sql(); 
			$sql .= $this->dusun_sql(); 
			$sql .= $this->rw_sql(); 
			$sql .= $this->rt_sql(); 
			$sql .= $order_sql;
			$sql .= $paging_sql;
			$query = $this->db->query($sql,array($per,$_SESSION['analisis_master']));
		}
		$data=$query->result_array();
		
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			
			if($data[$i]['cek']){
				$data[$i]['nilai'] = $data[$i]['cek'];
				$data[$i]['set'] = "<img src='".base_url()."assets/images/icon/tick.png'>";
				
			}else{
				$data[$i]['nilai'] = "-";
				$data[$i]['set'] = "<img src='".base_url()."assets/images/icon/cross.png'>";
				
				$data[$i]['klasifikasi'] = '-';
			}
			
			$data[$i]['jk'] = "-";
			if($data[$i]['sex'] == 1)
				$data[$i]['jk'] = "L";
			else
				$data[$i]['jk'] = "P";
			
			$i++;
			$j++;
		}
		return $data;
	}
	function list_jawab2($id=0,$in=0){	
		$per = $this->get_aktif_periode();
		$sql = "SELECT s.id as id_parameter,s.jawaban as jawaban,s.nilai FROM analisis_respon r LEFT JOIN analisis_parameter s ON r.id_parameter = s.id WHERE r.id_subjek = ? AND r.id_periode = ? AND r.id_indikator=?";
		$query = $this->db->query($sql,array($id,$per,$in));
		$data = $query->row_array();
		
		if(empty($data['jawaban'])){
			$data['jawaban'] = "-";
			$data['nilai'] = "0";
		}
		return $data;
	}
	function list_indikator($id=0){	
		$jmkf    = $this->group_parameter();
		$cb="";
		if(count($jmkf)){
			foreach($jmkf as $jm){
				$cb .= $jm['id_jmkf'].",";
			}
		}
		$cb = $cb."7777777";
		
		$sql = "SELECT u.*,(SELECT COUNT(id) FROM analisis_indikator WHERE id = u.id AND id IN($cb)) AS cek FROM analisis_indikator u WHERE 1 ";
		$sql .= $this->master_sql();
 		$sql .= " ORDER BY u.nomor ASC"; 
		$query = $this->db->query($sql,$id);
		$data = $query->result_array();
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			
			$ret = $this->list_jawab2($id,$data[$i]['id']);
			$data[$i]['jawaban'] = $ret['jawaban'];
			$data[$i]['nilai'] = $ret['nilai'];
			$data[$i]['poin'] = $data[$i]['bobot']*$ret['nilai'];
			$i++;
		}
		return $data;
	}
	function get_total($id=0){	
		$per = $this->get_aktif_periode();
		$sql = "SELECT akumulasi FROM analisis_respon_hasil u WHERE id_subjek = ? AND id_periode = ? ";
		$query = $this->db->query($sql,array($id,$per));
		$data = $query->row_array();
		return $data['akumulasi'];
	}
	function get_analisis_master(){
		$sql = "SELECT * FROM analisis_master WHERE id=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}	
	function get_subjek($id=0){
		
		$subjek = $_SESSION['subjek_tipe'];
		switch($subjek){
			case 1: $sql = "SELECT u.id,u.nik AS nid,u.nama,u.sex,c.dusun,c.rw,c.rt FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id WHERE u.id = ? "; break;
			
			case 2: $sql = "SELECT u.id,u.no_kk AS nid,p.nama,p.sex,c.dusun,c.rw,c.rt FROM tweb_keluarga u LEFT JOIN tweb_penduduk p ON u.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id WHERE u.id = ? " ; break;
			
			case 3: $sql = "SELECT u.id,u.no_kk AS nid,p.nama,p.sex,c.dusun,c.rw,c.rt FROM tweb_rtm u LEFT JOIN tweb_penduduk p ON u.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id WHERE u.id = ? "; break;
			
			case 4: $sql = "SELECT u.id,u.kode AS nid,u.nama,p.sex,c.dusun,c.rw,c.rt FROM kelompok u LEFT JOIN tweb_penduduk p ON u.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id WHERE u.id = ? "; break;
			
			default: return null;
			
		}
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}	
	
	function multi_jawab($p=0,$o=0){
		$master 		= $this->get_analisis_master();
		
		if(isset($_SESSION['jawab']))
			$kf = $_SESSION['jawab'];
		else
			$kf = "7777777";
			
		switch($o){
			case 1: $order_sql = ' ORDER BY u.id'; break;
			case 2: $order_sql = ' ORDER BY u.id DESC'; break;
			case 3: $order_sql = ' ORDER BY u.id'; break;
			case 4: $order_sql = ' ORDER BY u.id DESC'; break;
			default:
		}
		$asign_sql = ' AND i.asign = 1';
		$order_sql = ' ORDER BY u.nomor,i.kode_jawaban ASC';
		
		$sql = "SELECT u.pertanyaan,u.nomor,i.jawaban,i.id AS id_jawaban,i.kode_jawaban,(SELECT count(id) FROM analisis_parameter WHERE id IN ($kf) AND id = i.id) AS cek FROM analisis_indikator u LEFT JOIN analisis_parameter i ON u.id = i.id_indikator WHERE u.id_master = ? "; 
		$sql .= $asign_sql;
		$sql .= $order_sql;
		$query 	= $this->db->query($sql,$master);
		$data	= $query->result_array();
		
		$i=0;
		while($i<count($data)){
			$data[$i]['no'] 	= $i+1;
			//$data[$i]['cek'] 	= null;
			$i++;
		}
		return $data;
	}
	function group_parameter(){
		if(isset($_SESSION['jawab'])){
			$idcb = $_SESSION['jawab'];
			$sql = "SELECT DISTINCT(id_indikator) AS id_jmkf FROM analisis_parameter WHERE id IN($idcb)";
			$query = $this->db->query($sql);
			$data = $query->result_array();
			return $data;
		}else{
			return null;
		}
	}
	function get_aktif_periode(){
		$sql = "SELECT * FROM analisis_periode WHERE aktif=1 AND id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data = $query->row_array();
		return $data['id'];
	}

	function get_periode(){
		$sql = "SELECT * FROM analisis_periode WHERE aktif=1 AND id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data = $query->row_array();
		return $data['nama'];
	}
	function list_dusun(){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function list_rw($dusun=''){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND dusun = ? AND rw <> '0'";
		$query = $this->db->query($sql,$dusun);
		$data=$query->result_array();
		return $data;
	}
				
	function list_rt($dusun='',$rw=''){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rw = ? AND dusun = ? AND rt <> '0'";
		$query = $this->db->query($sql,array($rw,$dusun));
		$data=$query->result_array();
		return $data;
	}
	function list_klasifikasi(){
		$sql = "SELECT * FROM analisis_klasifikasi WHERE id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data = $query->result_array();
		return $data;
	}
		
}
