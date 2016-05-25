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
			//$return.= "<r>";
			for($j=0; $j<$num_fields; $j++){
				//$row[$j] = addslashes($row[$j]);
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
			//$return.= "<r>";
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
			//$return.= "<r>";
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
			//$return.= "<r>";
			for($j=0; $j<$num_fields; $j++){
				//$row[$j] = addslashes($row[$j]);
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
		
		$sql   = "DELETE FROM analisis_respon_hasil WHERE id_periode=1";
		$this->db->query($sql);
		
		$sql   = "DELETE FROM analisis_respon WHERE id_periode=1";
		$this->db->query($sql);
		
		$sql   = "SELECT id FROM tweb_keluarga WHERE 1 ORDER BY no_kk";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$sql2   = "SELECT u.*,(SELECT COUNT(id) FROM analisis_parameter WHERE id_indikator = u.id) AS jml FROM analisis_indikator u WHERE id_master = 1 ORDER BY bobot";
			$query2 = $this->db->query($sql2);
			$res=$query2->result_array();
			$j=0;
			while($j<count($res)){
				$updx['id_subjek'] = $data[$i]['id'];
				$updx['id_indikator'] = $res[$j]['id'];
				$jm = $res[$j]['jml'];
				$jm=$jm-1;
				
				$sqlx   = "SELECT id FROM analisis_parameter WHERE id_indikator = ?";
				$queryx = $this->db->query($sqlx,$res[$j]['id']);
				$jaw=$queryx->result_array();
				//print_r($jaw);
				$numbers=rand($jaw[0]['id'],$jaw[$jm]['id']);
				
				$updx['id_parameter'] = $numbers;
				$updx['id_periode'] = 1;
				$outp = $this->db->insert('analisis_respon',$updx);

				$j++;
			}
			
			$sql   = "SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=?";
			$query = $this->db->query($sql,array($data[$i]['id'],1));
			$dx = $query->row_array();
			
			
			$upx['id_master'] =1;
			$upx['akumulasi'] = $dx['jml'];
			$upx['id_subjek'] = $data[$i]['id'];
			$upx['id_periode'] = 1;
			$outp = $this->db->insert('analisis_respon_hasil',$upx);
			$i++;
		}
		
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
		
	}
	
	function lombok(){
		

		$sql   = "SELECT * FROM sheet1 WHERE 1";
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
		
				$sql2   = "SELECT id FROM analisis_indikator ORDER BY id DESC LIMIT 1";
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
                'format'      => 'sql',             
              );

        $backup =& $this->dbutil->backup($prefs); 

        $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.sql';
        $save = base_url().$db_name;

        $this->load->helper('file');
        write_file($save, $backup); 
	$backup .= "i'); #END;";
//echo $backup;

			$b1=Parse_Data($backup,"# TABLE STRUCTURE FOR: analisis_indikator","# TABLE STRUCTURE FOR: data_surat");
			
			$b2=Parse_Data($backup,"# TABLE STRUCTURE FOR: detail_log_penduduk","#END;");
			$backup = $b1.$b2;
        $this->load->helper('download');
        force_download($db_name, $backup); 

		if($backup) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}		
	
	function restore(){
	
		$sql   = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='sid304'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		foreach($data AS $dat){
			$tbl = $dat["TABLE_NAME"];
			mysql_query("DROP TABLE $tbl");
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
    $query .= $sql_line;
    if (substr(rtrim($query), -1) == ';'){
      echo $query;
      $result = mysql_query($query)or die(mysql_error());
      $query = "";
    }
  }
 }
			
		$outp= mysql_query($data);
			echo $data;
		}
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}
	
	function gawe_surat(){
		
		$sql   = "SELECT * FROM tweb_surat_format WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		foreach($data AS $dat){
		
			$string=$dat['url_surat'];
			$mypath="surat\\".$dat['url_surat']."\\";
			$path = "".str_replace("\\","/",$mypath)."/";
			
			if (!file_exists($mypath)) {
				mkdir($mypath, 0777, true);
			}
			
			if (!file_exists(path)) {
				mkdir(path);
			}
			//$ccyymmdd = date("Y-m-d");
			$handle = fopen($path.$dat['url_surat'],'w+');
			fwrite($handle);
			fclose($handle);
		}
		
	}
}
?>
