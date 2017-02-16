<?php
class import_model extends CI_Model{
	function __construct(){
		parent::__construct();
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$this->load->helper('excel');
	}
	function import_siak(){
		
		$_SESSION['success']=-1;
		$vdir_upload = "surat/arsip/";
		$vfile_upload_dk = $vdir_upload.$_FILES["file_dk"]["name"];
		move_uploaded_file($_FILES["file_dk"]["tmp_name"], $vfile_upload_dk);
		
		$vfile_upload_bw = $vdir_upload.$_FILES["file_bw"]["name"];
		move_uploaded_file($_FILES["file_bw"]["tmp_name"], $vfile_upload_bw);
		
		if(is_file($vfile_upload_dk)){
			
			if(is_file($vfile_upload_dk)){
				$data = file_get_contents($vfile_upload_dk);
				$convert = explode("\n", $data);
				$strSQL = "TRUNCATE tweb_keluarga";
				$this->db->query($strSQL);
				$strSQL = "TRUNCATE tweb_penduduk";
				$this->db->query($strSQL);
				$strSQL = "TRUNCATE tweb_wil_clusterdesa";
				$this->db->query($strSQL);
				
				$kk = array();
				$j=0;
				$x="";
				
				$dusun = 0;
				$rw = 0;
				$rt = 0;
				$nKK = 0;
				
				$strSQLKK = "";
				
				for ($i=0;$i<count($convert);$i++){
					$item = explode(";",trim(str_replace("\"","",$convert[$i])));
					
					if($j>0){
						if(strlen($convert[$i])>10){
							$strDusun = str_replace("  "," ",trim($item[5]));
							//$strDusun = str_replace(" ","_",$strDusun);
							
							$strRT = trim($item[3]);
							
							$strSQL = "SELECT id FROM tweb_wil_clusterdesa WHERE dusun='".fixSQL($strDusun)."' AND rw='0' AND rt='0' ";
							$result = $this->db->query($strSQL);
							if($result->num_rows() > 0){
								
							}else{
								
								$strSQL="INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES('0','0','".fixSQL($strDusun)."')";
								if($this->db->query($strSQL)){
									$dusun++;
								}
							}
							
							$strSQL = "SELECT id FROM tweb_wil_clusterdesa WHERE dusun='".fixSQL($strDusun)."' AND rw='-' AND rt='0'";
							$result = $this->db->query($strSQL);
							if($result->num_rows() > 0){
							}else{
								$strSQL="INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES('0','-','".fixSQL($strDusun)."')";
								if($this->db->query($strSQL)){
									$rw++;
								}
							}
							
							$strSQL = "SELECT id FROM tweb_wil_clusterdesa WHERE dusun='".fixSQL($strDusun)."' AND rw='-' AND rt='".fixSQL($strRT)."' LIMIT 1";
							$result = $this->db->query($strSQL);
							if($result->num_rows() > 0){
								$rs =$result->row(0);
								$id_wil = $rs->id;
							}else{
								
								$strSQL = "INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES('".fixSQL($strRT)."','-','".fixSQL($strDusun)."')";
								$result = $this->db->query($strSQL);
								if($result){
									$strSQL = "SELECT id FROM tweb_wil_clusterdesa WHERE dusun='".fixSQL($strDusun)."' AND rw='-' AND rt='".fixSQL($strRT)."' LIMIT 1";
									$result = $this->db->query($strSQL);
									if($result->num_rows() > 0){
										$rs =$result->row(0);
										$id_wil = $rs->id;
									}									
								}
								$rt++;
							}
							
							if($id_wil > 0){
								$post_data = array('tgl_daftar'=>"".date("Y-m-d")."",'no_kk'=>"".fixSQL($item[0])."",'nik_kepala'=>"".fixSQL($item[23])."");
								$this->db->trans_start();
								if($this->db->insert('tweb_keluarga',$post_data)){
									$this->db->trans_complete();
									$nKK++;
									$strSQL = "SELECT id FROM tweb_keluarga WHERE ((no_kk='".fixSQL($item[0])."') AND (nik_kepala='".fixSQL($item[23])."')) LIMIT 1";
									$result = $this->db->query($strSQL);
									if($result->num_rows() > 0){
										$rs =$result->row(0);
										$id_kk = $rs->id;
									}									
								}
								$kk[$item[0]] = array($id_kk,"".$id_wil."","".$item[2]."");
							}
							
						}
					}
					if(trim(strtolower($convert[$i]))=="begindata"){
						$j++;
					}
					if($j>0){
						$j++;
					}
					
				}
				
				$strInfo = "
				<div>
					<dl>
						<dt>Dusun</dt><dd>".$dusun."</dd>
						<dt>RW</dt><dd>".$rw."</dd>
						<dt>RT</dt><dd>".$rt."</dd>
						<dt>Data KK</dt><dd>".$nKK."</dd>
					</dl>
				</div>
				";
			
			}			 
		}
		
		if(is_file($vfile_upload_bw)){
			
			if(is_file($vfile_upload_bw)){
				$data = file_get_contents($vfile_upload_bw);
				$convert1 = explode("\n", $data);
				
				$j=0;
				$strSQL = "INSERT INTO tweb_penduduk (`nama`, `nik`, `id_kk`, `kk_level`, `id_rtm`, `rtm_level`, ";
				$strSQL .= "`sex`, `tempatlahir`, `tanggallahir`, ";
				$strSQL .= "`agama_id`, `pendidikan_kk_id`, `pendidikan_id`, ";
				$strSQL .= "`pendidikan_sedang_id`, `pekerjaan_id`, `status_kawin`, ";
				$strSQL .= "`warganegara_id`, `dokumen_pasport`, `dokumen_kitas`, ";
				$strSQL .= "`ayah_nik`, `ibu_nik`, `nama_ayah`, `nama_ibu`, ";
				$strSQL .= "`foto`, `golongan_darah_id`, `id_cluster`, `status`, ";
				$strSQL .= "`alamat_sebelumnya`, `alamat_sekarang`, `status_dasar`,"; 
				$strSQL .= "`hamil`, `cacat_id`, `sakit_menahun_id`, `jamkesmas`, ";
				$strSQL .= "`akta_lahir`, `akta_perkawinan`, `tanggalperkawinan`, ";
				$strSQL .= "`akta_perceraian`, `tanggalperceraian`) VALUES\n";
				for ($i=0;$i<count($convert1);$i++){
					
					if($j>0){
						if(strlen($convert1[$i])>25){
							$item = explode(";",trim(str_replace("\"","",$convert1[$i])));
							
							
							if($j>0){
								if($item[20]==1){
									if(array_key_exists($item[36],$kk)){
										$strSQLX = "UPDATE tweb_keluarga SET nik_kepala='".fixSQL($item[0])."' WHERE no_kk='".$item[36]."'";
										$this->db->query($strSQLX);
									}
								}
								if(array_key_exists($item[36],$kk)){
									$id_kk = $kk[$item[36]][0];
									$id_cluster = $kk[$item[36]][1];
									$alamat = $kk[$item[36]][2];
								}else{
									$id_kk = 0;
									$id_cluster = 0;
									$alamat = "";
								}
								$strSQL .= "('".fixSQL($item[5])."','".fixSQL($item[0])."','".fixSQL($id_kk)."','".fixSQL($item[20])."',";
								$strSQL .= "'-','-',";
								$strSQL .= "'".fixSQL($item[6])."','".fixSQL($item[7])."','".fixSQL(date("Y-m-d",strtotime($item[8])))."',";
								$strSQL .= "'".fixSQL($item[12])."','".fixSQL($item[23])."','".fixSQL($item[23])."',";
								$strSQL .= "'".fixSQL($item[23])."','".fixSQL($item[24])."','".fixSQL($item[13])."',";
								$strSQL .= "'1','".fixSQL($item[3])."','-',";
								$strSQL .= "'-','-','".fixSQL($item[28])."','".fixSQL($item[26])."',";
								$strSQL .= "'','".fixSQL($item[11])."','".fixSQL($id_cluster)."',1,";
								$strSQL .= "'','".fixSQL($alamat)."',1,
								'0','".fixSQL($item[21])."','".fixSQL($item[22])."','',
								'".fixSQL($item[9])."','".fixSQL($item[14])."','".fixSQL($item[16])."',
								'".fixSQL($item[17])."','".fixSQL($item[19])."'),";
							}
						}
					}
					
					if(trim(strtolower($convert1[$i]))=="begindata"){
						$j++;
					}
					if($j>0){
						$j++;
					}
				}
				$strSQL = rtrim($strSQL,",");
				if($this->db->query($strSQL)){
					$strSQL = "UPDATE `tweb_keluarga` k SET k.nik_kepala = ( SELECT p.id FROM tweb_penduduk p WHERE p.id_kk = k.id AND p.kk_level =1 ) WHERE k.id >0";
					if($this->db->query($strSQL)){
						$_SESSION['success']=1;
					}
				}
			}
		}
	}
		
	function import_excel(){
		
		//if($_FILES['userfile']['type'] == "application/vnd.ms-excel"){
		$gagal=0;
		$baris2="";
			$a="DROP TABLE IF EXISTS impor";
			$this->db->query($a);
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		
		$baris = $data->rowcount($sheet_index=0);
		
		$a="CREATE TABLE IF NOT EXISTS impor ( 
		dusun varchar(50) NOT NULL DEFAULT 0,
		rw varchar(10) NOT NULL DEFAULT 0, 
		rt varchar(10) NOT NULL DEFAULT 0, 
		nama varchar(50) NOT NULL, 
		nik varchar(16) NOT NULL, 
		sex tinyint(1) unsigned DEFAULT NULL, 
		tempatlahir varchar(50) NOT NULL,
		tanggallahir date NOT NULL, 
		agama_id int(1) unsigned NOT NULL, 
		pendidikan_kk_id int(1) unsigned NOT NULL,
		pendidikan_id int(1) unsigned NOT NULL,
		pendidikan_sedang_id int(1) unsigned NOT NULL,
		pekerjaan_id int(1) unsigned NOT NULL, 
		status_kawin tinyint(1) unsigned NOT NULL, 
		kk_level tinyint(1) NOT NULL DEFAULT 0,
		warganegara_id int(1) unsigned NOT NULL, 
		nama_ayah varchar(50) NOT NULL, 
		nama_ibu varchar(50) NOT NULL,
		golongan_darah_id int(1) NOT NULL, 
		jamkesmas int(1) NOT NULL DEFAULT 2, 
		id_kk varchar(16) NOT NULL DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;";
		$this->db->query($a);
		$a="TRUNCATE tweb_wil_clusterdesa";
		$this->db->query($a);
		$a="TRUNCATE tweb_keluarga";
		$this->db->query($a);
		$a="TRUNCATE tweb_penduduk";
		$this->db->query($a);
		
		$baris2 ="";
		$j=0;
		for ($i=2; $i<=$baris; $i++){
			$dusun = $data->val($i, 1);
			$rw = $data->val($i, 2);
			$rt = $data->val($i, 3);
			
			$nama = $data->val($i, 4);
			if($nama!=""){
				$nama = '"'.$nama.'"';
			}
			
			$id_kk= $data->val($i, 5);
			$nik = $data->val($i, 6);
			$sex = $data->val($i, 7);
			$tempatlahir= $data->val($i, 8);
			if($tempatlahir!=""){
				$tempatlahir = '"'.$tempatlahir.'"';
			}else{
				$tempatlahir = '"-"';
			}
			$tanggallahir= $data->val($i, 9);
			
			if(strlen($tanggallahir)>0){
				$tanggallahir = date("Y-m-d",strtotime($tanggallahir));
			}else{
				$tanggallahir = date("Y-m-d");
			}
			
			if($tanggallahir[2] == "/" OR $tanggallahir[4] == "/"){
				$tanggallahir = str_replace('/','-', $tanggallahir);
			}
			
			$dusun = str_replace('_',' ', $dusun);
			$dusun = strtoupper($dusun);
			$dusun = str_replace('DUSUN ','', $dusun);
			$dusun = str_replace('dusun ','', $dusun);
			$dusun = str_replace('Dusun ','', $dusun);
			$dusun = str_replace('DUSUN','', $dusun);
			$dusun = str_replace('dusun','', $dusun);
			$dusun = str_replace('Dusun','', $dusun);
			if($tanggallahir[2] == "-"){
				$tanggallahir = rev_tgl($tanggallahir);
			}
			
			$agama_id= $data->val($i, 10);
			$pendidikan_kk_id= $data->val($i, 11);
			$pendidikan_sedang_id= $data->val($i, 12);
			if($pendidikan_sedang_id=="")
				$pendidikan_sedang_id=18;
			
			$pekerjaan_id= $data->val($i, 13);
			$status_kawin= $data->val($i, 14);
			$kk_level= $data->val($i, 15);
			$warganegara_id= 1;
			
			$nama_ayah= $data->val($i,17);
			if($nama_ayah!=""){
				$nama_ayah = '"'.$nama_ayah.'"';
			}else{
				$nama_ayah = '"-"';
			}
			$nama_ibu= $data->val($i,18);
			if($nama_ibu!=""){
				$nama_ibu = '"'.$nama_ibu.'"';
			}else{
				$nama_ibu = '"-"';
			}
			
			$golongan_darah_id= $data->val($i, 19);
			
			
			$nik = preg_replace("/[^0-9]+/", "", $nik);
			$id_kk = preg_replace("/[^0-9]+/", "", $id_kk);
			
			 
			$sql="INSERT INTO impor(dusun,rw,rt,nama,nik,sex,tempatlahir,tanggallahir,agama_id,pendidikan_kk_id, pendidikan_sedang_id,pekerjaan_id,status_kawin,kk_level,warganegara_id,nama_ayah,nama_ibu,golongan_darah_id,id_kk) VALUES ('$dusun','$rw','$rt',$nama,'$nik',$sex,$tempatlahir,'$tanggallahir','$agama_id','$pendidikan_kk_id','$pendidikan_sedang_id','$pekerjaan_id','$status_kawin','$kk_level','$warganegara_id',$nama_ayah,$nama_ibu,'$golongan_darah_id','$id_kk');";
			
			
			if($nama!="" AND $nik!="" AND $id_kk!="" AND $dusun!=""){
				$h = $this->db->query($sql);
			}else{
				$gagal++;
				$baris2 .=$i.",";
			}
			$h = null;	
			$sukses = $baris - $gagal - 1;
			}
			if($gagal==0)
				$baris2 ="tidak ada data yang gagal di import.";
				
			
				$query="INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) select * from (
					SELECT rt, rw, dusun from impor GROUP BY rw,rt,dusun
					union SELECT '0' as rt, '0' as rw, dusun from impor GROUP BY dusun
					union SELECT '0' as rt, '-' as rw, dusun from impor GROUP BY dusun
					union SELECT '-' as rt, '-' as rw, dusun from impor GROUP BY dusun
					union SELECT '-' as rt, rw as rw, dusun from impor GROUP BY rw,dusun
					union SELECT '0' as rt, rw as rw, dusun from impor GROUP BY rw,dusun
					 ORDER BY rw,rt,dusun ASC) as temp";
				$hasil = $this->db->query($query);
			
				$query="INSERT INTO tweb_keluarga(no_kk) SELECT DISTINCT(id_kk) AS no_kk FROM impor";
				$hasil = $this->db->query($query);
			
				$query="INSERT INTO tweb_penduduk(nama,nik,id_kk,kk_level,sex,tempatlahir,tanggallahir,agama_id,pendidikan_kk_id,pendidikan_sedang_id,pekerjaan_id,status_kawin,warganegara_id,nama_ayah,nama_ibu,golongan_darah_id,id_cluster,status) SELECT nama,nik,(SELECT id FROM tweb_keluarga WHERE no_kk=a.id_kk) as id_kk,kk_level,sex,tempatlahir,tanggallahir,agama_id,pendidikan_kk_id,pendidikan_sedang_id,pekerjaan_id,status_kawin,warganegara_id,nama_ayah,nama_ibu,golongan_darah_id,(SELECT id FROM tweb_wil_clusterdesa where dusun=a.dusun AND rw=a.rw AND rt=a.rt) as id_cluster,'1' as status from impor a;";
				$hasil = $this->db->query($query);
			
				$sql="SELECT id FROM tweb_keluarga";
				if ($a=$this->db->query($sql)){
						$hsl = $a->result_array();
						foreach($hsl AS $hsl2){
							$idnya=($hsl2['id']);
							$kirim = "UPDATE tweb_keluarga SET nik_kepala=(SELECT id FROM tweb_penduduk where kk_level='1' AND id_kk=$idnya LIMIT 1) WHERE id=$idnya";
							$query=$this->db->query($kirim);
						}
					}
			$a="DROP TABLE impor";
			$this->db->query($a);
			$a="DELETE FROM tweb_wil_clusterdesa WHERE dusun = '' OR rt = '' OR rw='';";
			$this->db->query($a);
			$a="DELETE FROM tweb_keluarga WHERE nik_kepala = '' OR nik_kepala is null;";
			$this->db->query($a);
			$a="DELETE FROM tweb_penduduk WHERE nama = '' AND nik = '';";
			$this->db->query($a);
			$a="ALTER TABLE tweb_penduduk ENGINE = InnoDB ROW_FORMAT = DYNAMIC;";
			
			$a="ALTER TABLE tweb_keluarga ENGINE = InnoDB ROW_FORMAT = DYNAMIC;";
			
			
			$_SESSION['gagal']=$gagal;
			$_SESSION['sukses']=$sukses;
			$_SESSION['baris']=$baris2;
			
			if($gagal==0) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
			
			
		//}else{$_SESSION['success']=-1;}
	}
	function import_dasar(){
		$data = "";
		$in = "";
		$outp = "";
		$filename = $_FILES['userfile']['tmp_name'];
		if ($filename!=''){	
			$lines = file($filename);
			foreach ($lines as $line){$data .= $line;}
			$penduduk=Parse_Data($data,"<penduduk>","</penduduk>");
			$keluarga=Parse_Data($data,"<keluarga>","</keluarga>");
			$cluster=Parse_Data($data,"<cluster>","</cluster>");
			
			$penduduk=explode("\r\n",$penduduk);
			$keluarga=explode("\r\n",$keluarga);
			$cluster=explode("\r\n",$cluster);
			
			$inset = "INSERT INTO tweb_penduduk VALUES ";
			for($a=1;$a<(count($penduduk)-1);$a++){
				$p = preg_split("/\+/", $penduduk[$a]);
				$in .= "(";
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = $this->db->query($inset.$in);
			
			
			$in = "";
			$inset = "INSERT INTO tweb_wil_clusterdesa VALUES ";
			for($a=1;$a<(count($cluster)-1);$a++){
				$p = preg_split("/\+/", $cluster[$a]);
				$in .= "(";
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = $this->db->query($inset.$in);
			
			$in = "";
			$inset = "INSERT INTO tweb_keluarga VALUES ";
			for($a=1;$a<(count($keluarga)-1);$a++){
				$p = preg_split("/\+/", $keluarga[$a]);
				$in .= "(";
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = $this->db->query($inset.$in);
		}
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}
	function import_akp(){
		$id_desa = $_SESSION['user'];
		$data = "";
		$in = "";
		$outp = "";
		$filename = $_FILES['userfile']['tmp_name'];
		if ($filename!=''){	
			$lines = file($filename);
			foreach ($lines as $line){$data .= $line;}
			$penduduk=Parse_Data($data,"<akpkeluarga>","</akpkeluarga>");
			
			$penduduk=explode("\r\n",$penduduk);
			
			$inset = "INSERT INTO analisis_keluarga VALUES ";
			for($a=1;$a<(count($penduduk)-1);$a++){
				$p = preg_split("/\+/", $penduduk[$a]);
				$in .= "(".$id_desa;
				for($j=0;$j<(count($p));$j++){
					$in .= ',"'.$p[$j].'"';
				}
				$in .= "),";
			}
			$x = strlen($in);
			$in[$x-1] =";";
			$outp = $this->db->query($inset.$in);
			
		}
		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}
	
	
	function ppls_individu(){
		$a="DELETE FROM tweb_penduduk WHERE status = 2;";
		$this->db->query($a);
		
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		
		$sheet=0;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);
		
		for ($i=2; $i<=$baris; $i++){
			
			for ($j=1; $j<=$kolom;$j++){
				$rt = "";
				$dusun = "";
				$dusun2 = "";
				$temp = $data->val($i,$j,$sheet);
				if($j==11){
					$p = strlen($temp);
					if(is_numeric($temp[$p-1])){
						
						$rt = $temp[$p-3].$temp[$p-2].$temp[$p-1];
						$dusun = explode(" ",$temp);
						$dusun2 = $dusun[0];if($dusun[1]!="RT"){$dusun2 = $dusun2." ".$dusun[1];}
						
					}else{
						
						$rt = $temp[3].$temp[4].$temp[5];
						$dusun = explode(" ",$temp);
						$dusun2 = $dusun[2];if(isset($dusun[3])){$dusun2 = $dusun2." ".$dusun[3];}
					}
					$rt2 = $rt*1;
					
				}elseif($j==17){
					
					$tlahir = $data->val($i,16,$sheet)."-".$data->val($i,17,$sheet)."-1";
					
				}else{
					
				}
				
				if($j==1)
					$j+=9;
			}
				$sql 		= "SELECT id FROM tweb_wil_clusterdesa WHERE rt = ? OR rt = ?";
				$query 		= $this->db->query($sql,array($rt,$rt2));
				$cluster 	= $query->row_array();
				if($cluster)
					$id_cluster = $cluster['id'];
				else
					$id_cluster = 0;
				$penduduk = "";
				$penduduk['id_cluster']		= $id_cluster;
				$penduduk['status']			= 2;
				$penduduk['nama']			= $data->val($i,13,$sheet);
				$penduduk['nik']			= $data->val($i,12,$sheet);
				$penduduk['id_rtm']			= $data->val($i,1,$sheet);
				$penduduk['tanggallahir']	= $tlahir;
				$penduduk['rtm_level']		= 2;
				$penduduk['nik']			= $data->val($i,25,$sheet);
				$penduduk['kk_level']		= $data->val($i,14,$sheet);
				$penduduk['sex']			= $data->val($i,15,$sheet);
				$penduduk['pendidikan_id']			= $data->val($i,22,$sheet);
				$penduduk['pendidikan_kk_id']			= $data->val($i,22,$sheet);
				
				$outp = $this->db->insert('tweb_penduduk',$penduduk);
		}
		
		$a="TRUNCATE tweb_rtm; ";
		$this->db->query($a);
		
		$a="INSERT INTO tweb_rtm (no_kk) SELECT distinct(id_rtm) AS no_kk FROM tweb_penduduk WHERE tweb_penduduk.status=2 AND tweb_penduduk.id_rtm <> 0; ";
		$this->db->query($a);
	
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function pbdt_individu(){
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		
		$sheet=0;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);
		
		$gg=0;
		for ($i=2; $i<=$baris; $i++){
			$id_rtm			= $data->val($i,31,$sheet);
			$rtm_level		= $data->val($i,7,$sheet);
			if($rtm_level > 1)$rtm_level=2;
			$nik			= $data->val($i,9,$sheet);
			
			$sql 	= "SELECT nama FROM tweb_penduduk WHERE nik = ?";
			$query 	= $this->db->query($sql,$nik);
			$pdd	= $query->row_array();
			
			$nama = "--> GAGAL";
			if($pdd){
				
				$upd['id_rtm'] 		= $id_rtm; 
				$upd['rtm_level'] 	= $rtm_level; 
				
				$this->db->where('nik',$nik);
				$outp = $this->db->update('tweb_penduduk',$upd);
				$nama = $pdd['nama'];
				
				echo "<a>".$id_rtm." ".$rtm_level." ".$nik." ".$nama."</a><br>";
			}else{
				
				$penduduk = "";
				$penduduk['id_cluster']		= 0;
				$penduduk['status']			= 2;
				$penduduk['nama']			= $data->val($i,8,$sheet);
				$penduduk['nik']			= $nik;
				$penduduk['id_rtm']			= $id_rtm;
				$penduduk['rtm_level']		= $rtm_level;
				
				$outp = $this->db->insert('tweb_penduduk',$penduduk);
				
				echo "<a style='color:#f00;'>".$id_rtm." ".$rtm_level." ".$nik." ".$nama."</a><br>";
				
				$gg++;
			}
			
			
		}
		
		$a="TRUNCATE tweb_rtm; ";
		$this->db->query($a);
		
		$a="INSERT INTO tweb_rtm (id,no_kk,nik_kepala) SELECT distinct(id_rtm) AS no_kk,id_rtm,id FROM tweb_penduduk WHERE tweb_penduduk.id_rtm > 0 AND rtm_level = 1; ";
		$outp = $this->db->query($a);
		
		$_SESSION['ggl'] = $gg;
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
			
		echo "<br>JUMLAH GAGAL : $gg</br>";
		echo "<a href='".site_url()."database/import_ppls'>LANJUT</a>";
	}
	
	function ppls_rumahtangga(){
		
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		
		$sheet=0;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);
		
		
		for ($i=2; $i<=$baris; $i++){
			
			
				$penduduk = "";
				
				
				$penduduk['nama']			= $data->val($i,12,$sheet);
				$penduduk['id_rtm']			= $data->val($i,1,$sheet);
				
				
				//$outp = $this->db->insert('tweb_penduduk',$penduduk);
				$upd['rtm_level'] = 1;
				
			$this->db->where('id_rtm',$penduduk['id_rtm']	);
			$this->db->where('nama',$penduduk['nama']	);
			$outp = $this->db->update('tweb_penduduk',$upd);
			
		}
		
		$sql = "SELECT id,no_kk FROM tweb_rtm WHERE 1 ";
			
		$query = $this->db->query($sql);
		$rtm=$query->result_array();
		
		
		$i=0;
		while($i<count($rtm)){
			$o = $rtm[$i]['id'];
			$q = $rtm[$i]['no_kk'];
			$a="UPDATE tweb_penduduk SET id_rtm = $o WHERE id_rtm = $q; ";
			$this->db->query($a);
			$i++;
		}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function pbdt_rumahtangga(){
		
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		
		$sheet=0;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);
		
		
		for ($i=2; $i<=$baris; $i++){
			
				$penduduk = "";
				$penduduk['nama']			= $data->val($i,12,$sheet);
				$penduduk['id_rtm']			= $data->val($i,1,$sheet);
				
				//$outp = $this->db->insert('tweb_penduduk',$penduduk);
				$upd['rtm_level'] = 1;
				
			$this->db->where('id_rtm',$penduduk['id_rtm']	);
			$this->db->where('nama',$penduduk['nama']	);
			$outp = $this->db->update('tweb_penduduk',$upd);
			
		}
		
		$sql = "SELECT id,no_kk FROM tweb_rtm WHERE 1 ";
			
		$query = $this->db->query($sql);
		$rtm=$query->result_array();
		
		
		$i=0;
		while($i<count($rtm)){
			$o = $rtm[$i]['id'];
			$q = $rtm[$i]['no_kk'];
			$a="UPDATE tweb_penduduk SET id_rtm = $o WHERE id_rtm = $q; ";
			$this->db->query($a);
			$i++;
		}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
	
	function persil(){
		$data = new Spreadsheet_Excel_Reader($_FILES['persil']['tmp_name']);
		
		$sheet=0;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);
									
		for ($i=2; $i<=$baris; $i++){
			$upd['nik'] = $data->val($i,2,$sheet);
			$upd['nama'] = $data->val($i,3,$sheet);
			$upd['persil_jenis_id'] = $data->val($i,4,$sheet);
			$upd['id_clusterdesa'] = $data->val($i,5,$sheet);
			$upd['luas'] = $data->val($i,6,$sheet);
			$upd['kelas'] = $data->val($i,7,$sheet);
			$upd['no_sppt_pbb'] = $data->val($i,8,$sheet);
			$upd['persil_peruntukan_id'] = $data->val($i,9,$sheet);
			
			$outp = $this->db->insert('data_persil',$upd);
		}
		
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}
}