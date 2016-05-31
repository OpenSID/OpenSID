<?php class Surat_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function list_surat(){
		$sql   = "SELECT * FROM tweb_surat_format";
		$query = $this->db->query($sql);
		$data = $query->result_array();	
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['nama']=($i+1).") ".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}	
	
	function list_surat2(){
		$sql   = "SELECT * FROM tweb_surat_format";
		$query = $this->db->query($sql);
		$data = $query->result_array();	
		return $data;
	}	
	
	function list_penduduk(){
		$sql   = "SELECT u.id,nik,nama,w.dusun,w.rw,w.rt FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id WHERE u.status = 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']= "RT-".$data[$i]['rt'].", RW-".$data[$i]['rw']." ".$data[$i]['dusun'];
			$i++;
		}
		return $data;
	}

	function list_penduduk_perempuan(){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1 AND sex=2";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}
			
	function list_penduduk_laki(){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1 AND sex=1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}
	
	function list_penduduk_ex($id=0){
		$sql   = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1 AND id NOT IN(?)";
		$query = $this->db->query($sql,$id);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}
		
	function get_penduduk($id=0){
		$sql   = "SELECT `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,u.id_kk AS id_kk,
		`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,
		(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` 
		from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,
		`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,
		(select `tweb_penduduk`.`nama` AS `nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` 
		from ((((((((`tweb_penduduk` `u` 
		left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) 
		left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) 
		left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) 
		left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) 
		left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) 
		left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) 
		left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) 
		left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`))) 
		WHERE u.id = ?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		
			$data['alamat']='';
			
			if($data['rt'] != "-")
				$data['alamat']="RT-".$data['rt'];
			
			if($data['rw'] != "-")
				$data['alamat']=$data['alamat']." RW-".$data['rw'];
			
			if($data['dusun'] != "-")
				$data['alamat']=$data['alamat']." Dusun ".ununderscore($data['dusun']);
				
		return $data;
	}
			
	function list_anggota($id=0,$nik=0){
		$sql   = "SELECT * FROM tweb_penduduk WHERE id_kk = ? AND nik <> ?";
		$query = $this->db->query($sql,array($id,$nik));
		$data  = $query->result_array();
		//echo $sql;
		return $data;
	}
		
	function pengikut(){
		$id_cb = $_POST['id_cb'];
		$outp="";
		if(count($id_cb)){
			foreach($id_cb as $id){
				//$id = '''."$id".''';
				$outp = $outp.$id.',';
			}
			$outp = $outp.'7070';
		
		
		
		$sql   = "select `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` AS `nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`))) WHERE u.nik IN($outp)";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		}
		return $data;
	}
	
	function list_pamong(){
		$sql   = "SELECT u.* FROM tweb_desa_pamong u WHERE pamong_status=1 ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		return $data;
	}
	
	function get_data_surat($id=0){
		$sql   = "select `u`.*,g.nama AS gol_darah,`x`.`nama` AS `sex`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` AS `nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`))) left join tweb_golongan_darah g on u.golongan_darah_id=g.id WHERE u.id = ?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_data_desa(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	function get_pamong($id=0){
		$sql   = "SELECT u.* FROM tweb_desa_pamong u WHERE pamong_id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_data_pribadi($id=0){
		$sql   = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn, n.nama as agama,c.rw,c.rt,c.dusun,(DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( u.tanggallahir ) ) , '%Y' ) +0) as umur, sex.nama as sex  FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id  left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id  left join tweb_cacat m on u.cacat_id=m.id   left join tweb_wil_clusterdesa c on u.id_cluster=c.id   left join tweb_penduduk_warganegara w on u.warganegara_id=w.id  left join tweb_penduduk_agama n on u.agama_id=n.id LEFT JOIN tweb_penduduk_sex sex ON u.sex=sex.id WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_data_kk($id=0){
		$sql   = "SELECT b.nik_kepala, b.no_kk,b.id AS id_kk, c.nama as kepala_kk, d.* FROM tweb_penduduk a LEFT JOIN tweb_keluarga b ON a.id_kk=b.id LEFT JOIN tweb_penduduk c ON b.nik_kepala=c.id LEFT JOIN tweb_wil_clusterdesa d ON c.id_cluster=d.id WHERE a.id=? ";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
		
	function get_penduduk_ortu($id=0){
		$sql   = "SELECT u.* FROM tweb_penduduk u WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_data_istri($id=0){
		$sql   = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn,c.rw,c.rt,c.dusun, n.nama as agama FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id  left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id  left join tweb_cacat m on u.cacat_id=m.id left join tweb_penduduk_warganegara w on u.warganegara_id=w.id  left join tweb_wil_clusterdesa c on u.id_cluster=c.id  left join tweb_penduduk_agama n on u.agama_id=n.id  WHERE u.id=(SELECT id FROM tweb_penduduk WHERE id_kk=(SELECT id_kk FROM tweb_penduduk WHERE id=$id AND kk_level=1) AND kk_level=3 limit 1)";
		$query = $this->db->query($sql);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_data_suami($id=0){
		$sql   = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn, n.nama as agama,c.rw,c.rt,c.dusun,(DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( u.tanggallahir ) ) , '%Y' ) +0) as umur  FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id  left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id  left join tweb_cacat m on u.cacat_id=m.id   left join tweb_wil_clusterdesa c on u.id_cluster=c.id   left join tweb_penduduk_warganegara w on u.warganegara_id=w.id  left join tweb_penduduk_agama n on u.agama_id=n.id  WHERE u.id=(SELECT id FROM tweb_penduduk WHERE id_kk=(SELECT id_kk FROM tweb_penduduk WHERE id=$id AND kk_level=3) AND kk_level=1 limit 1 )";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	
	function get_data_ayah($id=0){
		$sql   = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn,c.rw,c.rt,c.dusun, n.nama as agama FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id  left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id  left join tweb_cacat m on u.cacat_id=m.id left join tweb_penduduk_warganegara w on u.warganegara_id=w.id  left join tweb_wil_clusterdesa c on u.id_cluster=c.id  left join tweb_penduduk_agama n on u.agama_id=n.id  WHERE u.nik=(SELECT ayah_nik from tweb_penduduk where id='$id') or (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=1) limit 1";
		$query = $this->db->query($sql);
		$data  = $query->row_array();
		return $data;
	}
		

	function get_data_ibu($id=0){
		$sql   = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn,c.rw,c.rt,c.dusun, n.nama as agama  FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id  left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id  left join tweb_cacat m on u.cacat_id=m.id  left join tweb_penduduk_warganegara w on u.warganegara_id=w.id  left join tweb_wil_clusterdesa c on u.id_cluster=c.id   left join tweb_penduduk_agama n on u.agama_id=n.id   WHERE u.nik=(SELECT ibu_nik from tweb_penduduk where id=?)  or (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=3) limit 1";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}
	function get_dusun($dusun=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rt = '0' AND rw = '0'";
		$query = $this->db->query($sql,$dusun);
		return $query->row_array();
	}
		
	function get_rw($dusun='',$rw=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = '0'";
		$query = $this->db->query($sql,array($dusun,$rw));
		return $query->row_array();
	}

	function get_rt($dusun='',$rw='',$rt=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = ?";
		$query = $this->db->query($sql,array($dusun,$rw,$rt));
		return $query->row_array();
	}
	
	function get_surat($url=''){
		$sql   = "SELECT * FROM tweb_surat_format WHERE url_surat = ?";
		$query = $this->db->query($sql,$url);
		return $query->row_array();
	}
		
	function coba($url=''){
	
		$g=$_POST['pamong'];
		$u=$_SESSION['user'];
		$z=$_POST['nomor'];
		
		$id = $_POST['nik'];
		$input = $_POST;
		$tgl = tgl_indo(date("Y m d"));
		$thn = date("Y");
		$individu = $this->get_data_surat($id);
		$config = $this->get_data_desa();
		$surat = $this->get_surat($url);
		
		$tgllhr = strtoupper(tgl_indo($individu['tanggallahir']));
		$individu['nama'] = strtoupper($individu['nama']);
		$individu['tempatlahir'] = strtoupper($individu['tempatlahir']);
		
		$mypath="surat\\$url\\";
		$mypath_arsip="surat\\arsip\\";
		$path = "".str_replace("\\","/",$mypath);
		$path_arsip = "".str_replace("\\","/",$mypath_arsip);
		$file = $path."$url.rtf";
		if(is_file($file)){
			$handle = fopen($file,'r');
			$buffer = stream_get_contents($handle);
			
			//PRINSIP FUNGSI
			//-> [kata_template] -> akan digantikan dengan data di bawah ini (sebelah kanan)
			
			//DATA SURAT
			$buffer=str_replace("[kode_surat]","$surat[kode_surat]",$buffer);
			$buffer=str_replace("[judul_surat]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[tgl_surat]","$tgl",$buffer);
			$buffer=str_replace("[tahun]","$thn",$buffer);
			
			//DATA DARI FORM INPUT SURAT
			$buffer=str_replace("[nomor_surat]","$input[nomor]",$buffer);
			$buffer=str_replace("[nomor_sorat]","$input[nomor]",$buffer);
			$buffer=str_replace("[mulai_berlaku]","$input[berlaku_dari]",$buffer);
			$buffer=str_replace("[tgl_akhir]","$input[berlaku_sampai]",$buffer);
			$buffer=str_replace("[jabatan]","$input[jabatan]",$buffer);
			$buffer=str_replace("[nama_pamong]","$input[pamong]",$buffer);
			$buffer=str_replace("[keterangan]","$input[keterangan]",$buffer);
			$buffer=str_replace("[keperluan]","$input[keperluan]",$buffer);
			
			//DATA DARI KONFIGURASI DESA
			$buffer=str_replace("[kode_desa]","$config[kode_desa]",$buffer);
			$buffer=str_replace("[nama_kab]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kec]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_kepala_camat]","$config[nama_kepala_camat]",$buffer);
			$buffer=str_replace("[nip_kepala_camat]","$config[nip_kepala_camat]",$buffer);
			$buffer=str_replace("[nama_des]","$config[nama_desa]",$buffer);
			$buffer=str_replace("[pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[alamat_des]","$config[alamat_kantor] Kode Pos : $config[kode_pos]",$buffer);
			
			//DATA DARI TABEL PENDUDUK
			//jika data kurang lengkap bisa di tambahkan dari fungsi "get_data_surat" pada file ini baris 151 
			$buffer=str_replace("[alamat]","RT $individu[rt] / RW $individu[rw] $individu[dusun]",$buffer);
			$buffer=str_replace("[nama_ayah]","$individu[nama_ibu]",$buffer);
			$buffer=str_replace("[nama_ibu]","$individu[nama_ibu]",$buffer);
			$buffer=str_replace("[nama]","$individu[nama]",$buffer);
			$buffer=str_replace("[sex]","$individu[sex]",$buffer);
			$buffer=str_replace("[agama]","$individu[agama]",$buffer);
			$buffer=str_replace("[gol_darah]","$individu[gol_darah]",$buffer);
			$buffer=str_replace("[pekerjaan]","$individu[pekerjaan]",$buffer);
			$buffer=str_replace("[warga_negara]","$individu[warganegara]",$buffer);
			$buffer=str_replace("[no_ktp]","$individu[nik]",$buffer);
			$buffer=str_replace("*usia","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[usia]","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[no_kk]","$individu[no_kk]",$buffer);
			$buffer=str_replace("[ttl]","$individu[tempatlahir]/$tgllhr",$buffer);
			
			foreach ($input as $key => $entry){
				$buffer=str_replace("[$key]",$entry,$buffer);
			}
			
			$berkas_arsip = $path_arsip.$url."_".$individu['nik']."_".date("Y-m-d").".rtf";
			$handle = fopen($berkas_arsip,'w+');
			fwrite($handle,$buffer);
			fclose($handle);
			$_SESSION['success']=8;
			header("location:".base_url($berkas_arsip));
		}
		
	}
	
}
