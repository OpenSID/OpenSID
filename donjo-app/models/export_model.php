<?php class Export_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function export_dasar(){
	$return = "";
	$result = mysql_query('SELECT * FROM tweb_penduduk WHERE 1');
	$num_fields = mysql_num_fields($result);
	$return.= "<penduduk>\r\n";
	for($i = 0; $i < $num_fields; $i++){
		while($row = mysql_fetch_row($result)){
			
			for($j=0; $j<$num_fields; $j++){
				
				if (isset($row[$j])) { $return.= $row[$j] ; } else { $return.= ''; }
				if ($j<($num_fields-1)) { $return.= '+'; }
			}
			$return.= "\r\n";
		}
	}
	$return.="</penduduk>\r\n";
		
	$result = mysql_query('SELECT * FROM tweb_keluarga WHERE 1');
	$num_fields = mysql_num_fields($result);
	$return.= "<keluarga>\r\n";
	for($i = 0; $i < $num_fields; $i++){
		while($row = mysql_fetch_row($result)){
			
			for($j=0; $j<$num_fields; $j++){
				if (isset($row[$j])) { $return.= $row[$j] ; } else { $return.= ''; }
				if ($j<($num_fields-1)) { $return.= '+'; }
			}
			$return.= "\r\n";
		}
	}
	$return.="</keluarga>\r\n";
				
	$result = mysql_query('SELECT * FROM tweb_wil_clusterdesa WHERE 1');
	$num_fields = mysql_num_fields($result);
	$return.= "<cluster>\r\n";
	for($i = 0; $i < $num_fields; $i++){
		while($row = mysql_fetch_row($result)){
			
			for($j=0; $j<$num_fields; $j++){
				if (isset($row[$j])) { $return.= $row[$j] ; } else { $return.= ''; }
				if ($j<($num_fields-1)) { $return.= '+'; }
			}
			$return.= "\r\n";
		}
	}
	$return.="</cluster>";
		
	$result = mysql_query('SELECT * FROM tweb_wil_clusterdesa WHERE 1');
	$num_fields = mysql_num_fields($result);
	Header('Content-type: application/octet-stream');
	Header('Content-Disposition: attachment; filename=data_dasar('.date("d-m-Y").').sid');
	echo $return;
	}
	function export_akp(){
	$return = "";
	$result = mysql_query('SELECT * FROM analisis_keluarga WHERE 1');
	$num_fields = mysql_num_fields($result);
	$return.= "<akpkeluarga>\r\n";
	for($i = 0; $i < $num_fields; $i++){
		while($row = mysql_fetch_row($result)){
			
			for($j=0; $j<$num_fields; $j++){
				
				if (isset($row[$j])) { $return.= $row[$j] ; } else { $return.= ''; }
				if ($j<($num_fields-1)) { $return.= '+'; }
			}
			$return.= "\r\n";
		}
	}
	$return.="</akpkeluarga>\r\n";
	Header('Content-type: application/octet-stream');
	Header('Content-Disposition: attachment; filename=data_akp('.date("d-m-Y").').sid');
	echo $return;
	}
	function analisis(){
		
		$sql = "DELETE FROM analisis_respon_hasil WHERE id_periode=1";
		$this->db->query($sql);
		
		$sql = "DELETE FROM analisis_respon WHERE id_periode=1";
		$this->db->query($sql);
		
		$sql = "SELECT u.id FROM tweb_penduduk u WHERE (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= '2' AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= '17' ORDER BY id LIMIT 87";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		$i=0;
		while($i<count($data)){
			$sql2 = "SELECT u.*,(SELECT COUNT(id) FROM analisis_parameter WHERE id_indikator = u.id) AS jml FROM analisis_indikator u WHERE id_master = 1 ORDER BY bobot";
			$query2 = $this->db->query($sql2);
			$res=$query2->result_array();
			$j=0;
			while($j<count($res)){
				$updx['id_subjek'] = $data[$i]['id'];
				$updx['id_indikator'] = $res[$j]['id'];
				$jm = $res[$j]['jml'];
				$jm=$jm-1;
				
				$sqlx = "SELECT id FROM analisis_parameter WHERE id_indikator = ?";
				$queryx = $this->db->query($sqlx,$res[$j]['id']);
				$jaw=$queryx->result_array();
				
				$numbers=rand($jaw[0]['id'],$jaw[$jm]['id']);
				
				$updx['id_parameter'] = $numbers;
				$updx['id_periode'] = 1;
				$outp = $this->db->insert('analisis_respon',$updx);
				$j++;
			}
			
			
			
			
			
			
			$upx['id_master'] =1;
			
			$upx['id_subjek'] = $data[$i]['id'];
			$upx['id_periode'] = 1;
			$outp = $this->db->insert('analisis_respon_hasil',$upx);
			$i++;
		}
		
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
		
	}
	function analisis2(){
		
		$sql = "DELETE FROM analisis_respon_hasil WHERE id_periode=2";
		$this->db->query($sql);
		
		$sql = "DELETE FROM analisis_respon WHERE id_periode=2";
		$this->db->query($sql);
		
		$sql = "SELECT u.id FROM tweb_penduduk u WHERE (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= '21' ORDER BY id limit 75";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		$i=0;
		while($i<count($data)){
			$sql2 = "SELECT u.*,(SELECT COUNT(id) FROM analisis_parameter WHERE id_indikator = u.id) AS jml FROM analisis_indikator u WHERE id_master = 2 ORDER BY bobot";
			$query2 = $this->db->query($sql2);
			$res=$query2->result_array();
			$j=0;
			while($j<count($res)){
				$updx['id_subjek'] = $data[$i]['id'];
				$updx['id_indikator'] = $res[$j]['id'];
				$jm = $res[$j]['jml'];
				$jm=$jm-1;
				
				$sqlx = "SELECT id FROM analisis_parameter WHERE id_indikator = ?";
				$queryx = $this->db->query($sqlx,$res[$j]['id']);
				$jaw=$queryx->result_array();
				
				$numbers=rand($jaw[0]['id'],$jaw[$jm]['id']);
				
				$updx['id_parameter'] = $numbers;
				$updx['id_periode'] = 2;
				$outp = $this->db->insert('analisis_respon',$updx);
				$j++;
			}
			
			
			
			
			
			
			$upx['id_master'] =2;
			
			$upx['id_subjek'] = $data[$i]['id'];
			$upx['id_periode'] = 2;
			$outp = $this->db->insert('analisis_respon_hasil',$upx);
			$i++;
		}
		
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
		
	}
	function lombok(){
		$sql = "SELECT * FROM sheet1 WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		$i=0;
		while($i<count($data)){
		
				$upx['id_master'] =4;
				$upx['id_kategori'] = $data[$i]['id_kat'];
				$upx['pertanyaan'] = $data[$i]['indi'];
				$upx['bobot'] = 1;
				$upx['act_analisis'] = 1;
				$upx['id_tipe'] = 1;
				$outp = $this->db->insert('analisis_indikator',$upx);
		
				$sql2 = "SELECT id FROM analisis_indikator ORDER BY id DESC LIMIT 1";
				$query2 = $this->db->query($sql2);
				$res=$query2->row_array();
			
				$updx['id_indikator'] = $res['id'];
		
				$updx['nilai'] = 1;
				$updx['jawaban'] = $data[$i]['C'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 2;
				$updx['jawaban'] = $data[$i]['D'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 3;
				$updx['jawaban'] = $data[$i]['E'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 4;
				$updx['jawaban'] = $data[$i]['F'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 5;
				$updx['jawaban'] = $data[$i]['G'];
				$outp = $this->db->insert('analisis_parameter',$updx);
					
		
			$i++;
		}
		
		
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
		
	}
	function backup(){
		$this->load->dbutil();
 $prefs = array( 
 'format' => 'sql', 
 );
 $backup =& $this->dbutil->backup($prefs); 
 $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.sql';
 $save = base_url().$db_name;
 $this->load->helper('file');
 write_file($save, $backup); 
	$backup .= "i'); #END;";
			$b1=Parse_Data($backup,"# TABLE STRUCTURE FOR: analisis_indikator","# TABLE STRUCTURE FOR: data_surat");
			
			$b2=Parse_Data($backup,"# TABLE STRUCTURE FOR: detail_log_penduduk","#END;");
			$backup = $b1.$b2;
 $this->load->helper('download');
 force_download($db_name, $backup); 
		if($backup) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}		
	function restore(){
		$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='sid'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		foreach($data AS $dat){
			$tbl = $dat["TABLE_NAME"];
			
		}
		
		$data = "";
		$in = "";
		$outp = "";
		$filename = $_FILES['userfile']['tmp_name'];
		if ($filename!=''){	
			$lines = file($filename);
		$query = "";
		foreach($lines as $sql_line){
		 if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
			$query = $sql_line;
			if (substr(rtrim($query), -1) == ';'){
			 
			 $result = $this->db->query($query);
			 
			}
		 }
		 }
			
		
			
		}
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}
	function export_excel(){
		$sql = "SELECT u.*,a.dusun,a.rw,a.rt,d.no_kk AS no_kk FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id LEFT JOIN tweb_keluarga d ON u.id_kk = d.id WHERE 1 ";
			
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		return $data;
	}
}