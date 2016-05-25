<?php class Laporan_Penduduk_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	
	function autocomplete(){
		$sql   = "SELECT dusun_nama FROM tweb_wil_dusun";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['dusun_nama']. "'";
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
			$search_sql= " AND u.nama LIKE '$kw'";
			return $search_sql;
			}
		}

	function paging($lap=0,$p=1,$o=0){
		
		switch($lap){
			case 0: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_pendidikan u WHERE 1 "; break;
			case 1: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_pekerjaan u WHERE 1 "; break;
			case 2: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_kawin u WHERE 1 "; break;
			case 3: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_agama u WHERE 1 "; break;
			case 4: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_sex u WHERE 1 "; break;
			case 5: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_warganegara u WHERE 1 "; break;
			case 6: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_status u WHERE 1 "; break;
			case 7: $sql      = "SELECT COUNT(id) AS id FROM tweb_golongan_darah u WHERE 1 "; break;
			case 9: $sql      = "SELECT COUNT(id) AS id FROM tweb_cacat u WHERE 1 "; break;
			case 10: $sql      = "SELECT COUNT(id) AS id FROM tweb_sakit_menahun u WHERE 1 "; break;
			case 11: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_sex u WHERE 1 "; break;
			case 12: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_pendidikan_kk u WHERE 1 "; break;
			case 13: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_umur u WHERE status = 1 "; break;
			case 15: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_umur u WHERE status is null "; break;
			case 14: $sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_pendidikan u WHERE left(nama,5)<> 'TAMAT' "; break;
			
			case 21: $sql      = "SELECT COUNT(id) AS id FROM klasifikasi_analisis_keluarga u WHERE jenis='1' "; break;
			case 22: $sql      = "SELECT COUNT(id) AS id FROM ref_raskin u WHERE 1 "; break;
			case 23: $sql      = "SELECT COUNT(id) AS id FROM ref_blt u WHERE 1 "; break;
			case 24: $sql      = "SELECT COUNT(id) AS id FROM ref_bos u WHERE 1 "; break;
			case 25: $sql      = "SELECT COUNT(id) AS id FROM ref_pkh u WHERE 1 "; break;
			case 26: $sql      = "SELECT COUNT(id) AS id FROM ref_jampersal u WHERE 1 "; break;
			case 27: $sql      = "SELECT COUNT(id) AS id FROM ref_bedah_rumah u WHERE 1 "; break;
			
			default:$sql      = "SELECT COUNT(id) AS id FROM tweb_penduduk_pendidikan u WHERE 1 ";
		}
	
		//$sql     .= $this->search_sql();     
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
	
	
	function list_data($lap=0,$o=0,$offset=0,$limit=500){
	
		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.id'; break;
			case 2: $order_sql = ' ORDER BY u.id DESC'; break;
			case 7: $order_sql = ' ORDER BY perempuan'; break;
			case 8: $order_sql = ' ORDER BY perempuan DESC'; break;
			case 3: $order_sql = ' ORDER BY laki'; break;
			case 4: $order_sql = ' ORDER BY laki DESC'; break;
			case 5: $order_sql = ' ORDER BY jumlah'; break;
			case 6: $order_sql = ' ORDER BY jumlah DESC'; break;
			default:$order_sql = ' ORDER BY u.id';
		}
	
		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
		switch($lap){
			case 12: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_pendidikan u WHERE 1 "; 
			break;
			
			case 1: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE pekerjaan_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE pekerjaan_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE pekerjaan_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_pekerjaan u WHERE 1 "; break;
			
			case 2: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_kawin = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_kawin = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_kawin = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_kawin u WHERE 1"; break;
			
			case 3: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE agama_id = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE agama_id = u.id AND sex = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE agama_id = u.id AND sex = 2) AS perempuan FROM tweb_penduduk_agama u WHERE 1"; break;
			
			case 4: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk  WHERE sex = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE sex = u.id AND sex=1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE sex = 2  AND sex=u.id AND status_dasar = 1) AS perempuan FROM tweb_penduduk_sex u WHERE 1"; break;
			
			case 5: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE warganegara_id = u.id AND status_dasar = 1 ) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE warganegara_id = u.id AND sex=1 AND status_dasar=1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE warganegara_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_warganegara u WHERE 1"; break;
			
			case 6: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE status = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE status = u.id AND sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE status = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM  tweb_penduduk_status u WHERE 1"; break;
			
			case 7: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE golongan_darah_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE golongan_darah_id = u.id AND sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE golongan_darah_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_golongan_darah u WHERE 1"; break;
			
			case 9: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE cacat_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE cacat_id = u.id AND  sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE cacat_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_cacat u WHERE 1"; break;
			
			case 10: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE sakit_menahun_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE sakit_menahun_id = u.id AND  sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE sakit_menahun_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_sakit_menahun u WHERE 1"; break;
			
			case 11: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE jamkesmas = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE jamkesmas = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE jamkesmas = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM ref_jamkesmas u WHERE 1"; break;
			
			case 0: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_kk_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_kk_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_kk_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_pendidikan_kk u WHERE 1"; break;
			
			case 13: $sql   = "SELECT u.*, concat( dari, ' - ', sampai) as nama, (SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND status_dasar = 1) AS jumlah, (SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND sex = 1 AND status_dasar = 1) AS laki, (SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_umur u WHERE status=1 "; break;
			
			case 14: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_sedang_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_sedang_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_sedang_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_pendidikan u WHERE left(nama,5)<> 'TAMAT'"; break;
			
			case 15: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= u.dari AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 <= u.sampai) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= u.dari AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 <= u.sampai AND sex=1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= u.dari AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 <= u.sampai AND sex=2) AS perempuan  FROM tweb_penduduk_umur u WHERE status is NULL "; break;
			
			//bagian keluarga
			case 21: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE kelas_sosial = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM klasifikasi_analisis_keluarga u WHERE jenis='1'"; break;
			case 22: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE raskin = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_raskin u WHERE 1 "; break;
			case 23: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE id_blt = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_blt u WHERE 1 "; break;
			case 24: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE id_bos = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_bos u WHERE 1 "; break;
			case 25: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE id_pkh = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_pkh u WHERE 1 "; break;
			case 26: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE id_jampersal = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_jampersal u WHERE 1 "; break;
			case 27: $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE id_bedah_rumah = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_bedah_rumah u WHERE 1 "; break;
		
			default:$sql   = "SELECT u.* FROM tweb_penduduk_pendidikan u WHERE 1 ";
		}
			
		//$sql .= $this->search_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;
		
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		if($lap<=20){
			$sql3 = "SELECT (SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.status_dasar=1) AS jumlah,
			(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.sex = 1 and status_dasar=1) AS laki,
			(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.sex = 2 and status_dasar=1) AS perempuan";
		}else{
			$sql3 = "SELECT (SELECT COUNT(p.id) FROM tweb_keluarga p WHERE 1) AS jumlah,
			(SELECT COUNT(p.id) FROM tweb_keluarga p WHERE 1) AS laki,
			(SELECT COUNT(p.id) FROM tweb_keluarga p WHERE 1) AS perempuan";
		}
		
		$query3 = $this->db->query($sql3);
		$bel = $query3->row_array();
		
		$total['jumlah']=0;
		$bel['no']="";
		$bel['id']="";
		$bel['nama']="TOTAL";
		$total['laki']=0;
		$total['perempuan']=0;
		$j=$offset;
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			
			//if($data[$i]['jumlah']<1)
			//	$data[$i]['jumlah']="-";
			//else
				$total['jumlah']+=$data[$i]['jumlah'];
			
			//if($data[$i]['laki']<1)
			//	$data[$i]['laki']="-";
			//else
				$total['laki']+=$data[$i]['laki'];
			
			//if($data[$i]['perempuan']<1)
			//	$data[$i]['perempuan']="-";
			//else
				$total['perempuan']+=$data[$i]['perempuan'];
				
			$i++;
			$j++;
		}

		$data[$i]['no']="";
		$data[$i]['id']=777;
		$data[$i]['nama']="BELUM MENGISI";
		$data[$i]['jumlah']=$bel['jumlah']-$total['jumlah'];
		$data[$i]['perempuan']=$bel['perempuan']-$total['perempuan'];
		$data[$i]['laki']=$bel['laki']-$total['laki'];
		
				
		$i=0;
		while($i<count($data)){
			$data[$i]['persen']=$data[$i]['jumlah']/$bel['jumlah']*100;
			$data[$i]['persen']=number_format((float)$data[$i]['persen'], 2, '.', '');
			$data[$i]['persen']=$data[$i]['persen']."%";
			
			$data[$i]['persen1']=$data[$i]['laki']/$bel['jumlah']*100;
			$data[$i]['persen1']=number_format((float)$data[$i]['persen1'], 2, '.', '');
			$data[$i]['persen1']=$data[$i]['persen1']."%";
			
			$data[$i]['persen2']=$data[$i]['perempuan']/$bel['jumlah']*100;
			$data[$i]['persen2']=number_format((float)$data[$i]['persen2'], 2, '.', '');
			$data[$i]['persen2']=$data[$i]['persen2']."%";
			
			
			$i++;
		}
		
			$bel['persen']="100%";
			
			$bel['persen1']=$bel['laki']/$bel['jumlah']*100;
			$bel['persen1']=number_format((float)$bel['persen1'], 2, '.', '');
			$bel['persen1']=$bel['persen1']."%";
			
			$bel['persen2']=$bel['perempuan']/$bel['jumlah']*100;
			$bel['persen2']=number_format((float)$bel['persen2'], 2, '.', '');
			$bel['persen2']=$bel['persen2']."%";
			
		$data['total']=$bel;
		return $data;
	}
	
	function get_config(){	
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}
	
	function list_data_rentang(){

		$sql   = "SELECT * FROM tweb_penduduk_umur WHERE status=1 order by dari "; 

		$query = $this->db->query($sql);
		$data=$query->result_array();

		return $data;
	}
	
	function get_rentang($id=0){	
		$sql   = "SELECT * FROM tweb_penduduk_umur WHERE id= $id ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}
	
	function get_rentang_terakhir(){	
		$sql   = "SELECT (case when max(sampai) is null then '0' else (max(sampai)+1) end) as dari FROM tweb_penduduk_umur WHERE status=1 ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}	
	
	function insert_rentang(){
		$data = $_POST;
		$data['status']=1;
		$outp = $this->db->insert('tweb_penduduk_umur',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function update_rentang($id=0){
		$data = $_POST;
		$sql   = "UPDATE tweb_penduduk_umur SET nama='$data[nama]', dari='$data[dari]', sampai='$data[sampai]' WHERE id='$id' ";
		$outp=$this->db->query($sql);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_rentang($id=0){
		$sql   = "DELETE FROM tweb_penduduk_umur WHERE id='$id' ";
		$outp=$this->db->query($sql);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function delete_all_rentang(){
		$id_cb = $_POST['id_cb'];
		
		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_penduduk_umur WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
}

?>
