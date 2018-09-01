<?php class Analisis_statistik_jawaban_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function autocomplete(){
		$sql = "SELECT pertanyaan FROM analisis_indikator";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['pertanyaan']. "'";
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}
	function get_subjek2(){		
		$_subjek = "LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ";
		return $_subjek;
	}
	
	function get_subjek(){		
		$_subjek = "LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ";
		return $_subjek;
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
	function filter_sql(){		
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.act_analisis = $kf";
		return $filter_sql;
		}
	}
	function master_sql(){		
		if(isset($_SESSION['analisis_master'])){
			$kf = $_SESSION['analisis_master'];
			$filter_sql= " AND u.id_master = $kf";
		return $filter_sql;
		}
	}
	function tipe_sql(){		
		if(isset($_SESSION['tipe'])){
			$kf = $_SESSION['tipe'];
			$filter_sql= " AND u.id_tipe = $kf";
		return $filter_sql;
		}
	}
	function kategori_sql(){		
		if(isset($_SESSION['kategori'])){
			$kf = $_SESSION['kategori'];
			$filter_sql= " AND u.id_kategori = $kf";
		return $filter_sql;
		}
	}
	function dusun_sql(){		
		if(isset($_SESSION['dusun'])){
			$kf = $_SESSION['dusun'];
			$dusun_sql= " AND a.dusun = '$kf'";
		return $dusun_sql;
		}
	}
	function rw_sql(){		
		if(isset($_SESSION['rw'])){
			$kf = $_SESSION['rw'];
			$rw_sql= " AND a.rw = '$kf'";
		return $rw_sql;
		}
	}
	function rt_sql(){		
		if(isset($_SESSION['rt'])){
			$kf = $_SESSION['rt'];
			$rt_sql= " AND a.rt = '$kf'";
		return $rt_sql;
		}
	}
	function paging($p=1,$o=0){
		$sql = "SELECT COUNT(id) AS id FROM analisis_indikator u WHERE 1";
		$sql .= $this->search_sql(); 
		$sql .= $this->filter_sql(); 
		$sql .= $this->master_sql(); 
		$sql .= $this->tipe_sql();
		$sql .= $this->kategori_sql();
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
		
		//$_subjek = $this->get_subjek();
		
		$subjek = $_SESSION['subjek_tipe'];
		switch($subjek){
			case 1: $sbj = "LEFT JOIN tweb_penduduk p ON r.id_subjek = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 2: $sbj = "LEFT JOIN tweb_keluarga v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  " ; break;
			case 3: $sbj = "LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 4: $sbj = "LEFT JOIN kelompok v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  "; break;
		}
		
		switch($o){
			case 1: $order_sql = ' ORDER BY u.nomor'; break;
			case 2: $order_sql = ' ORDER BY u.nomor DESC'; break;
			case 3: $order_sql = ' ORDER BY u.pertanyaan'; break;
			case 4: $order_sql = ' ORDER BY u.pertanyaan DESC'; break;
			case 5: $order_sql = ' ORDER BY u.id_kategori'; break;
			case 6: $order_sql = ' ORDER BY u.id_kategori DESC'; break;
			default:$order_sql = ' ORDER BY u.nomor';
		}
		
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		
		$sql = "SELECT u.*,t.tipe AS tipe_indikator,k.kategori AS kategori FROM analisis_indikator u LEFT JOIN analisis_tipe_indikator t ON u.id_tipe = t.id LEFT JOIN analisis_kategori_indikator k ON u.id_kategori = k.id WHERE 1 ";
			
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->master_sql();
		$sql .= $this->tipe_sql();
		$sql .= $this->kategori_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		
		$per = $this->get_aktif_periode();
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			
			$data[$i]['jumlah']= "-";
			
			$sql1 = "SELECT COUNT(DISTINCT r.id_subjek) AS jml FROM analisis_respon r $sbj WHERE r.id_indikator = ? AND r.id_periode = $per AND id_parameter > 0";
			$sql1 .= $this->dusun_sql();
			$sql1 .= $this->rw_sql();
			$sql1 .= $this->rt_sql();
			//$sql1 .= "  GROUP BY r.id_indikator  ";
			$query1 = $this->db->query($sql1,$data[$i]['id']);
			$respon= $query1->row_array();
			$data[$i]['bobot']=$respon['jml'];
			
			$dus = $this->dusun_sql();
			$rw = $this->rw_sql();
			$rt = $this->rt_sql();
			
			$sql2 = "SELECT i.id,i.kode_jawaban,i.jawaban,(SELECT COUNT(r.id_subjek) FROM analisis_respon r $sbj WHERE r.id_parameter = i.id AND r.id_periode = $per $dus $rw $rt ) AS jml_p FROM analisis_parameter i WHERE i.id_indikator = ? ORDER BY i.kode_jawaban ";
			
			
			$query2 = $this->db->query($sql2,$data[$i]['id']);
			$respon2= $query2->result_array();
			$data[$i]['par']=$respon2;
			
			if($data[$i]['act_analisis']==1)
				$data[$i]['act_analisis']="Ya";
			else
				$data[$i]['act_analisis']="Tidak";
			
			if($data[$i]['id_tipe']==3){
				$data[$i]['jumlah'] = 0;
				foreach($respon2 as $par){
					$data[$i]['jumlah'] += $par['jawaban']*$par['jml_p'];
				}
			}
			$i++;
			$j++;
		}
		return $data;
	}
	function list_indikator($id=0){
		//$_subjek = $this->get_subjek();
		
		$subjek = $_SESSION['subjek_tipe'];
		switch($subjek){
			case 1: $sbj = "LEFT JOIN tweb_penduduk p ON r.id_subjek = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 2: $sbj = "LEFT JOIN tweb_keluarga v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  " ; break;
			case 3: $sbj = "LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 4: $sbj = "LEFT JOIN kelompok v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  "; break;
		}
		
		$sql = "SELECT * FROM analisis_parameter WHERE id_indikator = ? ORDER BY kode_jawaban ASC ";
		$query = $this->db->query($sql,$id);
		$data= $query->result_array();
		$per = $this->get_aktif_periode();
		
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			
			$sql = "SELECT COUNT(r.id_subjek) AS jml FROM analisis_respon r $sbj WHERE r.id_parameter = ? AND r.id_periode = $per ";
			$sql .= $this->dusun_sql();
			$sql .= $this->rw_sql();
			$sql .= $this->rt_sql();
			$query = $this->db->query($sql,$data[$i]['id']);
			$respon= $query->row_array();
			
			$data[$i]['nilai']=$respon['jml'];
			
			$i++;
		}
		return $data;
	}
		
	function list_subjek($id=0){
		$per = $this->get_aktif_periode();
		//$sbj = $this->get_subjek2();
		
		$subjek = $_SESSION['subjek_tipe'];
		switch($subjek){
			case 1: $sbj = "LEFT JOIN tweb_penduduk p ON r.id_subjek = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 2: $sbj = "LEFT JOIN tweb_keluarga v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  " ; break;
			case 3: $sbj = "LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id "; break;
			case 4: $sbj = "LEFT JOIN kelompok v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  "; break;
		}
		
		$sql = "SELECT p.id AS id_pend,r.id_subjek,p.nama,p.nik,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = p.id) AS umur,p.sex,a.dusun,a.rw,a.rt FROM analisis_respon r $sbj WHERE r.id_parameter = ? AND r.id_periode = $per" ;
		
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$query = $this->db->query($sql,$id);
		$data= $query->result_array();
		
		$i=0;
		while($i<count($data)){
			
			if($data[$i]['sex']==1)
				$data[$i]['sex']="Laki-laki";
			else
				$data[$i]['sex']="Perempuan";
				
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}
		
	function get_analisis_indikator($id=0){
		$sql = "SELECT * FROM analisis_indikator WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_analisis_master(){
		$sql = "SELECT * FROM analisis_master WHERE id=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		return $query->row_array();
	}	
	function get_analisis_parameter($id=''){
		$sql = "SELECT * FROM analisis_parameter WHERE id=?";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}	
	function list_tipe(){
		$sql = "SELECT * FROM analisis_tipe_indikator";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function list_kategori(){
		$sql = "SELECT u.* FROM analisis_kategori_indikator u WHERE 1";
		$sql .= $this->master_sql();
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function get_aktif_periode(){
		$sql = "SELECT * FROM analisis_periode WHERE aktif=1 AND id_master=?";
		$query = $this->db->query($sql,$_SESSION['analisis_master']);
		$data = $query->row_array();
		return $data['id'];
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
}
