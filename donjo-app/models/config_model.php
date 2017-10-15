<?php class Config_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	
	function install(){
		
		$CI = get_instance();
		$CI->load->database();
		$db =$CI->db->database;;
		
		$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA=? AND TABLE_NAME <> 'impor'";
		$query = $this->db->query($sql,$db);
		$data=$query->result_array();
		if(count($data) != 77){
			return 0;
		}else{
			return 1;
		}
	}
	function initial(){
		
		$CI = get_instance();
		$CI->load->database();
		$db =$CI->db->database;;
		
		$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA=? AND TABLE_NAME <> 'impor'";
		$query = $this->db->query($sql,$db);
		$data=$query->result_array();
		if(count($data) != 77){
			$myhome = $_SERVER['MYSQL_HOME'];
			$filename = 'sid.install';
			$templine = '';
			$lines = file($filename);
			foreach ($lines as $line){
				if (substr($line, 0, 2) == '--' || $line == '')
					continue;
				$templine .= $line;
				if (substr(trim($line), -1, 1) == ';'){
					$this->db->query($templine); 
					$templine = '';
				}
			}
			$passwd = generator();
			$out['pass'] = $passwd;
			$idsid = hash_password($passwd);
			
			$skrg = date("Y-m-d H:i:s");
			$macid = $this->sysinfo();
			$ids="user:admin\r\npass:".$passwd."\r\nidr:".$idsid."\r\nids:".$macid;
			$handle = fopen('../install.sid','w+');
			fwrite($handle,$ids);
			fclose($handle);
			
			$reg['regid'] = $idsid;
			$reg['macid'] = $macid;
			$this->db->where('id','1');
			$this->db->update('config',$reg);
			
			$sql = "INSERT INTO user VALUES (1,'admin','$idsid',1,'admin@localhost','$skrg',1,'Administrator','ADMIN','0123456789','','$idsid');";
			$this->db->query($sql);

			$this->initsurat();
			$this->gawe_surat();
			return $out;
		}else{
			return NULL;
		}
	}
	
	function do_reg(){
		$url = 'http://register.sid.web.id/doreg/index.php';
		
		$sql = "SELECT * FROM config WHERE id=1";
		$query = $this->db->query($sql);
		$data=$query->row_array();
		
		$fields = array(

			'desa' => 			urlencode($data['nama_desa']),
			'kecamatan' => 		urlencode($data['nama_kecamatan']),
			'kabupaten' => 		urlencode($data['nama_kabupaten']),
			'propinsi' => 		urlencode($data['nama_propinsi']),
			'geo' => 			urlencode($data['lat'].','.$data['lng']),
			'kode_wilayah' => 	urlencode($data['kode_propinsi'].'-'.$data['kode_kabupaten'].'-'.$data['kode_kecamatan'].'-'.$data['kode_desa']),
			'regid' => 			urlencode($data['regid']),
			'macid' => 			urlencode($data['macid']),
			'email' => 			urlencode($data['email_desa'])
		);
		$fields_string ="";
		foreach($fields as $key=>$value) {$fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch,CURLOPT_MUTE, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, false);
		curl_exec($ch);
		curl_close($ch);
	}
	
	function gawe_surat(){
		
		$sql = "SELECT kunci,favorit FROM tweb_surat_format WHERE 1;";
		$query = $this->db->query($sql);
		
		//if(!$query){
			$sql = "SELECT * FROM tweb_surat_format WHERE 1";
			$query = $this->db->query($sql);
			$data=$query->result_array();
			
			foreach($data AS $dat){
			
				$string=$dat['url_surat'];
				$mypath="surat\\".$dat['url_surat']."\\";
				$path = "".str_replace("\\","/",$mypath)."/";
				
				if (!file_exists($mypath)) {
					mkdir($mypath, 0777, true);
				}
				
				if (!file_exists($path)) {
					mkdir($path);
				}
				$raw="surat\\raw\\";
				$raw_path = "".str_replace("\\","/",$raw);
				$file = $raw_path."template.rtf";
				$handle = fopen($file,'r');
				
				$buffer = stream_get_contents($handle);
				
				$handle = fopen($path.$dat['url_surat'].'.rtf','w+');
				fwrite($handle,$buffer);
				fclose($handle);
			}
		//}
		
	}
	function initsurat(){
		$sql = "SELECT kunci,favorit FROM tweb_surat_format WHERE 1;";
		$query = $this->db->query($sql);
		if(!$query){
			$sql = "ALTER TABLE tweb_surat_format ADD kunci TINYINT(1) NOT NULL DEFAULT '0', ADD favorit TINYINT( 1 ) NOT NULL DEFAULT '0'";
			$this->db->query($sql);
		}
		$sql = "SELECT id_pend FROM dokumen WHERE 1;";
		$query = $this->db->query($sql);
		if(!$query){
			$sql = "ALTER TABLE dokumen ADD id_pend INT NOT NULL DEFAULT '0' AFTER id";
			$this->db->query($sql);
		}
	}
	
	function get_data(){
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	function insert(){
		$outp = $this->db->insert('config',$_POST);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update($id=0){
		$data = $_POST;
		$lokasi_file = $_FILES['logo']['tmp_name'];
		$tipe_file = $_FILES['logo']['type'];
		$nama_file = $_FILES['logo']['name'];
		$old_logo = $data['old_logo'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				unset($data['logo']);
			} else {
				UploadLogo($nama_file,$old_logo,$tipe_file);
				$data['logo'] = $nama_file;
			}
		}else{
			unset($data['logo']);
		}
		unset($data['file_logo']);
		unset($data['old_logo']);
		$this->db->where('id',$id);
		$outp = $this->db->update('config',penetration($data));
		$pamong['pamong_nama'] = $data['nama_kepala_desa'];
		$pamong['pamong_nip'] = $data['nip_kepala_desa'];
		$this->db->where('pamong_id','707');
		$outp = $this->db->update('tweb_desa_pamong',$pamong);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
		
	function update_kantor(){
		$data = $_POST;
		$id = "1";
		$this->db->where('id',$id);
		$outp = $this->db->update('config',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function update_wilayah(){
		$data = $_POST;
		$id = "1";
		$this->db->where('id',$id);
		$outp = $this->db->update('config',$data);
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	function kosong_pend(){
		$a="TRUNCATE tweb_wil_clusterdesa";
	$this->db->query($a);
		$a="TRUNCATE tweb_keluarga";
	$this->db->query($a);
		$a="TRUNCATE tweb_rtm";
	$this->db->query($a);
		
		$a="TRUNCATE tweb_penduduk";
	$this->db->query($a);
		
		$a="TRUNCATE log_penduduk";
	$this->db->query($a);
		
		$a="TRUNCATE log_surat";
	$this->db->query($a);
		
		$a="TRUNCATE log_perubahan_penduduk";
	$this->db->query($a);
		
		$a="TRUNCATE log_bulanan";
	$this->db->query($a);
		
		$a="TRUNCATE garis";
	$this->db->query($a);
		
		$a="TRUNCATE lokasi";
	$this->db->query($a);
		
		$a="TRUNCATE area";
	$this->db->query($a);
		
		$a="TRUNCATE point";
	$this->db->query($a);
		
		$a="TRUNCATE line";
	$this->db->query($a);
		
		$a="TRUNCATE polygon";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_master";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_indikator";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_parameter";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_periode";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_respon";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_respon_hasil";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_klasifikasi";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_kategori_indikator";
	$this->db->query($a);
		
		$a="TRUNCATE analisis_respon_bukti";
	$this->db->query($a);
		
		$a="TRUNCATE tweb_penduduk_mandiri";
	$this->db->query($a);
		$a="TRUNCATE kelompok";
	$this->db->query($a);
		
		$a="TRUNCATE kelompok_anggota";
	$this->db->query($a);
		$a="TRUNCATE data_persil";
	$this->db->query($a);
		$a="TRUNCATE tweb_penduduk_map";
	$this->db->query($a);
		$a="TRUNCATE sys_traffic";
	$this->db->query($a);
	}
	function kosong_web(){
		$a="TRUNCATE tweb_wil_clusterdesa";
	$this->db->query($a);
		$a="TRUNCATE tweb_keluarga";
	$this->db->query($a);
		$a="TRUNCATE tweb_penduduk";
	$this->db->query($a);
	}
	function upgrade(){
		$a="DROP TABLE tweb_rtm";
	$this->db->query($a);
		$a="DROP TABLE hasil_analisis_keluarga";
	$this->db->query($a);
		$a="DROP TABLE analisis_keluarga";
	$this->db->query($a);
		
		$a="DROP TABLE klasifikasi_analisis_keluarga";
	$this->db->query($a);
		
		$a="DROP TABLE master_analisis_keluarga";
	$this->db->query($a);
		
		$a="DROP TABLE sub_analisis_keluarga";
	$this->db->query($a);
		
		$a="DROP TABLE tipe_analisis";
	$this->db->query($a);
		
		$a="DROP TABLE tweb_rtm_hubungan";
	$this->db->query($a);
		
		$a="UPDATE tweb_penduduk SET id_rtm = 0, rtm_level = 0 WHERE 1";
	$this->db->query($a);
		
		$a="TRUNCATE tweb_rtm";
	$this->db->query($a);
		$a="TRUNCATE hasil_analisis_keluarga";
	$this->db->query($a);
		$a="TRUNCATE analisis_keluarga";
	$this->db->query($a);
		
		
		if($b) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function sysinfo(){
		exec('systeminfo',$ret);
		for($i=0;$i<(count($ret));$i++){
			$d = str_replace(" ","",$ret[$i]);
			$d.="*";
			$pd=Parse_Data($d,'ProductID:','*');
			if($pd != "")
				$pd1 = $pd;
			$pd=Parse_Data($d,'SystemModel:','*');
			if($pd != "")
				$pd2 = $pd;
			$pd=Parse_Data($d,'BIOSVersion:','*');
			if($pd != "")
				$pd3 = $pd;
		}
		return $pd1;
	}
	function patch(){
		$a = "UPDATE analisis_indikator SET act_analisis=0 WHERE id_tipe IN(2,3,4)";
		$this->db->query($a);
		$this->cls();
		
		$b 		= "SELECT id FROM tweb_wil_clusterdesa WHERE rt <> '-' AND rt <> 0 AND rw <> 0 AND rw <> '-'";
		//$query 	= $this->db->query($sql);
		//$data	= $query->result_array();
		
	}
	function opt(){
		$a="OPTIMIZE TABLE analisis_indikator, analisis_kategori_indikator, analisis_klasifikasi, analisis_master, analisis_parameter, analisis_partisipasi, analisis_periode, analisis_ref_state, analisis_ref_subjek, analisis_respon, analisis_respon_hasil, analisis_tipe_indikator, area, artikel, config, data_persil, data_persil_jenis, data_persil_log, data_persil_peruntukan, detail_log_penduduk, dokumen, gambar_gallery, garis, gis_simbol, inbox, kategori, kelompok, kelompok_anggota, kelompok_master, komentar, kontak, kontak_grup, line, log_bulanan, log_penduduk, log_perubahan_penduduk, log_surat, lokasi, media_sosial, menu, outbox, point, polygon, program, program_peserta, recent_status, ref_bedah_rumah, ref_blt, ref_jamkesmas, ref_kelas_sosial, ref_pkh, ref_raskin, sentitems, setting_modul, setting_sms, sys_traffic, tweb_alamat_sekarang, tweb_desa_pamong, tweb_keluarga, tweb_penduduk, tweb_penduduk_mandiri, tweb_penduduk_map, tweb_penduduk_umur, tweb_rtm, tweb_surat_atribut, tweb_surat_format, tweb_wil_clusterdesa, user;";
		$this->db->query($a);
	}
	function cls(){
		
		$sql 	= "SELECT * FROM analisis_parameter WHERE asign = 1 ORDER BY id_indikator";
		$query 	= $this->db->query($sql);
		$data	= $query->result_array();
		
		$i=0;
		$m=0;
		while($i<count($data)){
			$jwb 	= $data[$i]['jawaban'];
			$id 	= $data[$i]['id'];
			
			$sql1 		= "SELECT max(kode_jawaban) AS nil FROM analisis_parameter WHERE id_indikator = ?";
			$query1 	= $this->db->query($sql1,$data[$i]['id_indikator']);
			$m			= $query1->row_array();
			$n = ($m['nil']+1) - $data[$i]['kode_jawaban'];
			
			
			$up ['nilai'] = $n;
			$this->db->where('id',$id);
			$outp = $this->db->update('analisis_parameter',$up);
			$j 		= explode(". ",$jwb);
			if(count($j) > 1){				
				$upd ['jawaban'] = $j[1];
				$this->db->where('id',$id);
				$outp = $this->db->update('analisis_parameter',$upd);
			}
			$i++;
		}
	}
}