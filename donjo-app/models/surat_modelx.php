<?php class Surat_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function list_surat(){
		$sql = "SELECT * FROM tweb_surat_format WHERE kunci = 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();	
		
		$i=0;
		while($i<count($data)){
			$data[$i]['nama']=($i+1).") ".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}	
	function list_surat2(){
		$sql = "SELECT * FROM tweb_surat_format WHERE kunci = 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();	
		return $data;
	}	
	function list_surat_fav(){
		$sql = "SELECT * FROM tweb_surat_format WHERE kunci = 0 AND favorit = 1";
		$query = $this->db->query($sql);
		$data = $query->result_array();	
		return $data;
	}	
	function list_penduduk(){
		$sql = "SELECT u.id,nik,nama,w.dusun,w.rw,w.rt FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id WHERE u.status = 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']= "RT-".$data[$i]['rt'].", RW-".$data[$i]['rw']." ".$data[$i]['dusun'];
			$i++;
		}
		return $data;
	}
	function list_penduduk_perempuan(){
		$sql = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1 AND sex=2";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}
			
	function list_penduduk_laki(){
		$sql = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1 AND sex=1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}
	function list_penduduk_ex($id=0){
		$sql = "SELECT id,nik,nama FROM tweb_penduduk WHERE status = 1 AND id NOT IN(?)";
		$query = $this->db->query($sql,$id);
		$data=$query->result_array();
		
		
		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']="Alamat :".$data[$i]['nama'];
			$i++;
		}
		return $data;
	}
		
	function get_penduduk($id=0){
		$sql = "SELECT `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,u.id_kk AS id_kk,
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
		$data = $query->row_array();
		
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
		$sql = "SELECT u.*,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,x.nama AS pendidikan,h.nama AS hubungan FROM tweb_penduduk u LEFT JOIN tweb_penduduk_pendidikan_kk x ON u.pendidikan_kk_id = x.id LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id WHERE u.id_kk = ? AND u.nik <> ?";
		$query = $this->db->query($sql,array($id,$nik));
		$data = $query->result_array();
		
		return $data;
	}
		
	function pengikut(){
		$id_cb = $_POST['id_cb'];
		$outp="";
		if(count($id_cb)){
			foreach($id_cb as $id){
				
				$outp = $outp.$id.',';
			}
			$outp = $outp.'7070';
		
		
		
		$sql = "select `u`.`id` AS `id`,`u`.`nama` AS `nama`,`x`.`nama` AS `sex`,`u`.`tempatlahir` AS `tempatlahir`,`u`.`tanggallahir` AS `tanggallahir`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`u`.`nik` AS `nik`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` AS `nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk`,hb.nama AS hubungan from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`)) LEFT JOIN tweb_penduduk_hubungan hb ON u.kk_level = hb.id ) WHERE u.nik IN($outp)";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		}
		return $data;
	}
	function list_pamong(){
		$sql = "SELECT u.* FROM tweb_desa_pamong u WHERE pamong_status=1 ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}
	function get_data_surat($id=0){
		$sql = "select `u`.*,g.nama AS gol_darah,`x`.`nama` AS `sex`,(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `u`.`id`)) AS `umur`,`w`.`nama` AS `status_kawin`,`f`.`nama` AS `warganegara`,`a`.`nama` AS `agama`,`d`.`nama` AS `pendidikan`,`j`.`nama` AS `pekerjaan`,`c`.`rt` AS `rt`,`c`.`rw` AS `rw`,`c`.`dusun` AS `dusun`,`k`.`no_kk` AS `no_kk`,(select `tweb_penduduk`.`nama` AS `nama` from `tweb_penduduk` where (`tweb_penduduk`.`id` = `k`.`nik_kepala`)) AS `kepala_kk` from ((((((((`tweb_penduduk` `u` left join `tweb_penduduk_sex` `x` on((`u`.`sex` = `x`.`id`))) left join `tweb_penduduk_kawin` `w` on((`u`.`status_kawin` = `w`.`id`))) left join `tweb_penduduk_agama` `a` on((`u`.`agama_id` = `a`.`id`))) left join `tweb_penduduk_pendidikan_kk` `d` on((`u`.`pendidikan_kk_id` = `d`.`id`))) left join `tweb_penduduk_pekerjaan` `j` on((`u`.`pekerjaan_id` = `j`.`id`))) left join `tweb_wil_clusterdesa` `c` on((`u`.`id_cluster` = `c`.`id`))) left join `tweb_keluarga` `k` on((`u`.`id_kk` = `k`.`id`))) left join `tweb_penduduk_warganegara` `f` on((`u`.`warganegara_id` = `f`.`id`))) left join tweb_golongan_darah g on u.golongan_darah_id=g.id WHERE u.id = ?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_data_desa(){
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	function get_pamong($id=0){
		$sql = "SELECT u.* FROM tweb_desa_pamong u WHERE pamong_id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_data_pribadi($id=0){
		$sql = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn, n.nama as agama,c.rw,c.rt,c.dusun,(DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( u.tanggallahir ) ) , '%Y' ) +0) as umur, sex.nama as sex FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id left join tweb_cacat m on u.cacat_id=m.id left join tweb_wil_clusterdesa c on u.id_cluster=c.id left join tweb_penduduk_warganegara w on u.warganegara_id=w.id left join tweb_penduduk_agama n on u.agama_id=n.id LEFT JOIN tweb_penduduk_sex sex ON u.sex=sex.id WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_data_kk($id=0){
		$sql = "SELECT b.nik_kepala, b.no_kk,b.id AS id_kk, c.nama as kepala_kk, d.* FROM tweb_penduduk a LEFT JOIN tweb_keluarga b ON a.id_kk=b.id LEFT JOIN tweb_penduduk c ON b.nik_kepala=c.id LEFT JOIN tweb_wil_clusterdesa d ON c.id_cluster=d.id WHERE a.id=? ";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
		
	function get_penduduk_ortu($id=0){
		$sql = "SELECT u.* FROM tweb_penduduk u WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_data_istri($id=0){
		$sql = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn,c.rw,c.rt,c.dusun, n.nama as agama FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id left join tweb_cacat m on u.cacat_id=m.id left join tweb_penduduk_warganegara w on u.warganegara_id=w.id left join tweb_wil_clusterdesa c on u.id_cluster=c.id left join tweb_penduduk_agama n on u.agama_id=n.id WHERE u.id=(SELECT id FROM tweb_penduduk WHERE id_kk=(SELECT id_kk FROM tweb_penduduk WHERE id=$id AND kk_level=1) AND kk_level=3 limit 1)";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}
	function get_data_suami($id=0){
		$sql = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn, n.nama as agama,c.rw,c.rt,c.dusun,(DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( u.tanggallahir ) ) , '%Y' ) +0) as umur FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id left join tweb_cacat m on u.cacat_id=m.id left join tweb_wil_clusterdesa c on u.id_cluster=c.id left join tweb_penduduk_warganegara w on u.warganegara_id=w.id left join tweb_penduduk_agama n on u.agama_id=n.id WHERE u.id=(SELECT id FROM tweb_penduduk WHERE id_kk=(SELECT id_kk FROM tweb_penduduk WHERE id=$id AND kk_level=3) AND kk_level=1 limit 1 )";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_data_ayah($id=0){
		$sql = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn,c.rw,c.rt,c.dusun, n.nama as agama FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id left join tweb_cacat m on u.cacat_id=m.id left join tweb_penduduk_warganegara w on u.warganegara_id=w.id left join tweb_wil_clusterdesa c on u.id_cluster=c.id left join tweb_penduduk_agama n on u.agama_id=n.id WHERE u.nik=(SELECT ayah_nik from tweb_penduduk where id='$id') or (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=1) limit 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}
		
	function get_data_ibu($id=0){
		$sql = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn,c.rw,c.rt,c.dusun, n.nama as agama FROM tweb_penduduk u left join tweb_penduduk_hubungan h on u.kk_level=h.id left join tweb_keluarga k on u.id_kk=k.id left join tweb_penduduk p on k.nik_kepala=p.id left join tweb_golongan_darah g on u.golongan_darah_id=g.id left join tweb_penduduk_pendidikan d on u.pendidikan_id=d.id left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id left join tweb_cacat m on u.cacat_id=m.id left join tweb_penduduk_warganegara w on u.warganegara_id=w.id left join tweb_wil_clusterdesa c on u.id_cluster=c.id left join tweb_penduduk_agama n on u.agama_id=n.id WHERE u.nik=(SELECT ibu_nik from tweb_penduduk where id=?) or (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=3) limit 1";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}
	function get_dusun($dusun=''){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rt = '0' AND rw = '0'";
		$query = $this->db->query($sql,$dusun);
		return $query->row_array();
	}
		
	function get_rw($dusun='',$rw=''){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = '0'";
		$query = $this->db->query($sql,array($dusun,$rw));
		return $query->row_array();
	}
	function get_rt($dusun='',$rw='',$rt=''){
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ? AND rt = ?";
		$query = $this->db->query($sql,array($dusun,$rw,$rt));
		return $query->row_array();
	}
	function get_surat($url=''){
		$sql = "SELECT * FROM tweb_surat_format WHERE url_surat = ?";
		$query = $this->db->query($sql,$url);
		return $query->row_array();
	}
		
	function cobasss($url=''){
		$g=$_POST['pamong'];
		$u=$_SESSION['user'];
		$z=$_POST['nomor'];
		
		$id = $_POST['nik'];
		$input = $_POST;
		$input_key = array_keys($_POST);
		$tgl = tgl_indo(date("Y m d"));
		$thn = date("Y");
		$individu = $this->get_data_surat($id);
		
		$config = $this->get_data_desa();
		$surat = $this->get_surat($url);
		
		$tgllhr = strtoupper(tgl_indo($individu['tanggallahir']));
		$individu['nama'] = strtoupper($individu['nama']);
		$individu['tempatlahir'] = strtoupper($individu['tempatlahir']);
		
		if($individu['rt'][0] != "0" AND $individu['rt'][0] < 10)
			$individu['rt'] = "0".$individu['rt'];
		
		if($individu['rw'][0] != "0" AND $individu['rw'][0] < 10)
			$individu['rw'] = "0".$individu['rw'];
		
		$mypath="surat\\$url\\";
		$mypath_arsip="surat\\arsip\\";
		$path = "".str_replace("\\","/",$mypath);
		$path_arsip = "".str_replace("\\","/",$mypath_arsip);
		$file = $path."$url.rtf";
		if(is_file($file)){
			$handle = fopen($file,'r');
			$buffer = stream_get_contents($handle);

			$buffer=str_replace("[kode_surat]","$surat[kode_surat]",$buffer);
			$buffer=str_replace("[judul_surat]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[JUDUL_SURAT]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[tgl_surat]","$tgl",$buffer);
			$buffer=str_replace("[tahun]","$thn",$buffer);
			
			$buffer=str_replace("[nomor_surat]","$input[nomor]",$buffer);
			$buffer=str_replace("[nomor_sorat]","$input[nomor]",$buffer);
			
			if($input['berlaku_dari'] == "")
				$input['berlaku_dari'] = "..........................";
			
			if($input['berlaku_sampai'] == "")
				$input['berlaku_sampai'] = "........................";
			
			
			$buffer=str_replace("[mulai_berlaku]","$input[berlaku_dari]",$buffer);
			$buffer=str_replace("[tgl_akhir]","$input[berlaku_sampai]",$buffer);
			
			
			$buffer=str_replace("[jabatan]","$input[jabatan]",$buffer);
			$buffer=str_replace("[nama_pamong]","$input[pamong]",$buffer);
			$buffer=str_replace("[keterangan]","$input[keterangan]",$buffer);
			$buffer=str_replace("[keperluan]","$input[keperluan]",$buffer);
			$buffer=str_replace("[tujuan]","$input[tujuan]",$buffer);
			
			$buffer=str_replace("[kode_desa]","$config[kode_desa]",$buffer);
			$buffer=str_replace("[nama_kab]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kec]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_des]","$config[nama_desa]",$buffer);
			$buffer=str_replace("[NAMA_KAB]",strtoupper($config['nama_kabupaten']),$buffer);
			$buffer=str_replace("[NAMA_KEC]",strtoupper($config['nama_kecamatan']),$buffer);
			$buffer=str_replace("[NAMA_DES]",strtoupper($config['nama_desa']),$buffer);
			$buffer=str_replace("[nama_kepala_camat]","$config[nama_kepala_camat]",$buffer);
			$buffer=str_replace("[kades]","$config[nama_kepala_desa]",$buffer);
			$buffer=str_replace("[nip_kepala_camat]","$config[nip_kepala_camat]",$buffer);
			$buffer=str_replace("[pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[alamat_des]","$config[alamat_kantor] Pos : $config[kode_pos]",$buffer);
			
			$buffer=str_replace("[alamat]","RT $individu[rt] / RW $individu[rw] $individu[dusun]",$buffer);
			$buffer=str_replace("[rt]","$individu[rt]",$buffer);
			$buffer=str_replace("[rw]","$individu[rw]",$buffer);
			$buffer=str_replace("[dusun]","$individu[dusun]",$buffer);
			$buffer=str_replace("[nama_ayah]","$individu[nama_ayah]",$buffer);
			$buffer=str_replace("[nama_ibu]","$individu[nama_ibu]",$buffer);
			$buffer=str_replace("[nik_ayah]","$individu[ayah_nik]",$buffer);
			$buffer=str_replace("[nik_ibu]","$individu[ibu_nik]",$buffer);
			$buffer=str_replace("[nama]","$individu[nama]",$buffer);
			$buffer=str_replace("[sex]","$individu[sex]",$buffer);
			$buffer=str_replace("[agama]","$individu[agama]",$buffer);
			$buffer=str_replace("[status_kawin]","$individu[status_kawin]",$buffer);
			$buffer=str_replace("[gol_darah]","$individu[gol_darah]",$buffer);
			$buffer=str_replace("[pekerjaan]","$individu[pekerjaan]",$buffer);
			$buffer=str_replace("[warga_negara]","$individu[warganegara]",$buffer);
			$buffer=str_replace("[no_ktp]","$individu[nik]",$buffer);
			$buffer=str_replace("[nik]","$individu[nik]",$buffer);
			$buffer=str_replace("*usia","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[usia]","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[no_kk]","$individu[no_kk]",$buffer);
			$buffer=str_replace("[ttl]","$individu[tempatlahir]/$tgllhr",$buffer);
			
			
			//PENGIKUT
			$pxnama = "";
			$pxnik = "";
			$pxhubungan = "";
			$pxusia = "";
			if(isset($_POST['id_cb'])){
				$pengikut = $this->pengikut();
				$nom = 1;
				foreach($pengikut AS $pgkt){
					$pxnama .= $pgkt['nama']."\line \line ";
					$pxnik .= $pgkt['nik']."\line \line ";
					$pxhubungan .= $pgkt['hubungan']."\line \line ";
					$pxusia .= $pgkt['umur']." Thn\line \line ";
					$pxtglahir .= $pgkt['tanggallahir']."\line \line ";
					$pxtmplahir .= $pgkt['tempatlahir']."\line \line ";
					$pxttl .= $pgkt['tempatlahir'].", ".tgl_indo($pgkt['tanggallahir'])."\line ";
					$pxttl2 .= $pgkt['tempatlahir'].", ".rev_tgl($pgkt['tanggallahir'])."\line ";
					$pxno .= $nom."\line \line ";
					
					$nom++;
				}
				
				$buffer=str_replace("[px_nama]","$pxnama",$buffer);
				$buffer=str_replace("[px_nik]","$pxnik",$buffer);
				$buffer=str_replace("[px_hubungan]","$pxhubungan",$buffer);
				$buffer=str_replace("[px_usia]","$pxusia",$buffer);
				$buffer=str_replace("[px_tempatlahir]","$pxtglahir",$buffer);
				$buffer=str_replace("[px_tanggallahir]","$pxtmplahir",$buffer);
				$buffer=str_replace("[px_ttl]","$pxttl",$buffer);
				$buffer=str_replace("[px_ttl2]","$pxttl2",$buffer);
				$buffer=str_replace("[no]","$pxno",$buffer);
				
				
			}
			
			unset($input['id_cb']);
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
	function coba($url=''){
		$g=$_POST['pamong'];
		$u=$_SESSION['user'];
		$z=$_POST['nomor'];
		
		$id = $_SESSION['nik'];
		$individu = $this->get_data_surat($id);
		
		$ayah = $_SESSION['nik_ayah'];
		$ayah = $this->get_data_surat($ayah);
		
		$ibu = $_SESSION['nik_ibu'];
		$ibu = $this->get_data_surat($ibu);
		
		$input = $_POST;
		$tgl = tgl_indo(date("Y m d"));
		$thn = date("Y");
		$config = $this->get_data_desa();
		$surat = $this->get_surat($url);
		
		$tgllhr = strtoupper(tgl_indo($individu['tanggallahir']));
		$individu['nama'] = strtoupper($individu['nama']);
		$individu['tempatlahir'] = strtoupper($individu['tempatlahir']);
		
		if($individu['rt'][0] != "0" AND $individu['rt'][0] < 10)
			$individu['rt'] = "0".$individu['rt'];
		
		if($individu['rw'][0] != "0" AND $individu['rw'][0] < 10)
			$individu['rw'] = "0".$individu['rw'];
		
		$ayah_tgllhr = strtoupper(tgl_indo($ayah['tanggallahir']));
		$ayah['nama'] = strtoupper($ayah['nama']);
		$ayah['tempatlahir'] = strtoupper($ayah['tempatlahir']);
		
		if($ayah['rt'][0] != "0" AND $ayah['rt'][0] < 10)
			$ayah['rt'] = "0".$ayah['rt'];
		
		if($ayah['rw'][0] != "0" AND $ayah['rw'][0] < 10)
			$ayah['rw'] = "0".$ayah['rw'];
		
		$ibu_tgllhr = strtoupper(tgl_indo($ibu['tanggallahir']));
		$ibu['nama'] = strtoupper($ibu['nama']);
		$ibu['tempatlahir'] = strtoupper($ibu['tempatlahir']);
		
		if($ibu['rt'][0] != "0" AND $ibu['rt'][0] < 10)
			$ibu['rt'] = "0".$ibu['rt'];
		
		if($ibu['rw'][0] != "0" AND $ibu['rw'][0] < 10)
			$ibu['rw'] = "0".$ibu['rw'];
		
		$mypath="surat\\$url\\";
		$mypath_arsip="surat\\arsip\\";
		$path = "".str_replace("\\","/",$mypath);
		$path_arsip = "".str_replace("\\","/",$mypath_arsip);
		$file = $path."$url.rtf";
		if(is_file($file)){
			$handle = fopen($file,'r');
			$buffer = stream_get_contents($handle);
			
			
			foreach ($input as $key => $entry){
				$buffer=str_replace("[$key]",$entry,$buffer);
			}
			$buffer=str_replace("[kode_surat]","$surat[kode_surat]",$buffer);
			$buffer=str_replace("[judul_surat]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[JUDUL_SURAT]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[tgl_surat]","$tgl",$buffer);
			$buffer=str_replace("[tahun]","$thn",$buffer);
			
			
			$buffer=str_replace("[nomor_surat]","$input[nomor]",$buffer);
			$buffer=str_replace("[nomor_sorat]","$input[nomor]",$buffer);
			$buffer=str_replace("[mulai_berlaku]","$input[berlaku_dari]",$buffer);
			$buffer=str_replace("[tgl_akhir]","$input[berlaku_sampai]",$buffer);
			$buffer=str_replace("[jabatan]","$input[jabatan]",$buffer);
			$buffer=str_replace("[nama_pamong]","$input[pamong]",$buffer);
			$buffer=str_replace("[keterangan]","$input[keterangan]",$buffer);
			$buffer=str_replace("[keperluan]","$input[keperluan]",$buffer);
			$buffer=str_replace("[tujuan]","$input[tujuan]",$buffer);
			$buffer=str_replace("[hari]","$input[hari]",$buffer);
			$buffer=str_replace("[tgl_keg]","$input[tgl_keg]",$buffer);
			$buffer=str_replace("[waktu]","$input[waktu]",$buffer);
			$buffer=str_replace("[jenis_keg]","$input[jenis_keg]",$buffer);
			$buffer=str_replace("[lokasi_keg]","$input[lokasi_keg]",$buffer);
			$buffer=str_replace("[bidang_keg]","$input[bidang_keg]",$buffer);
			$buffer=str_replace("[alamat_sekarang]","$input[alamat_sekarang]",$buffer);
			
			
			$buffer=str_replace("[kode_desa]","$config[kode_desa]",$buffer);
			$buffer=str_replace("[nama_kab]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kec]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_des]","$config[nama_desa]",$buffer);
			$buffer=str_replace("[NAMA_KAB]",strtoupper($config['nama_kabupaten']),$buffer);
			$buffer=str_replace("[NAMA_KEC]",strtoupper($config['nama_kecamatan']),$buffer);
			$buffer=str_replace("[NAMA_DES]",strtoupper($config['nama_desa']),$buffer);
			$buffer=str_replace("[nama_kepala_camat]","$config[nama_kepala_camat]",$buffer);
			$buffer=str_replace("[kades]","$config[nama_kepala_desa]",$buffer);
			$buffer=str_replace("[nip_kepala_camat]","$config[nip_kepala_camat]",$buffer);
			$buffer=str_replace("[pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[alamat_des]","$config[alamat_kantor] Pos : $config[kode_pos]",$buffer);
			
			
			$buffer=str_replace("[jabatan]","$input[jabatan]",$buffer);
			$buffer=str_replace("[nama_pamong]","$input[pamong]",$buffer);
			$buffer=str_replace("[keterangan]","$input[keterangan]",$buffer);
			$buffer=str_replace("[keperluan]","$input[keperluan]",$buffer);
			$buffer=str_replace("[tujuan]","$input[tujuan]",$buffer);
			
			$buffer=str_replace("[kode_desa]","$config[kode_desa]",$buffer);
			$buffer=str_replace("[nama_kab]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kec]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_des]","$config[nama_desa]",$buffer);
			$buffer=str_replace("[NAMA_KAB]",strtoupper($config['nama_kabupaten']),$buffer);
			$buffer=str_replace("[NAMA_KEC]",strtoupper($config['nama_kecamatan']),$buffer);
			$buffer=str_replace("[NAMA_DES]",strtoupper($config['nama_desa']),$buffer);
			$buffer=str_replace("[nama_kepala_camat]","$config[nama_kepala_camat]",$buffer);
			$buffer=str_replace("[kades]","$config[nama_kepala_desa]",$buffer);
			$buffer=str_replace("[nip_kepala_camat]","$config[nip_kepala_camat]",$buffer);
			$buffer=str_replace("[pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[alamat_des]","$config[alamat_kantor] Pos : $config[kode_pos]",$buffer);
			
			$buffer=str_replace("[alamat]","$individu[dusun] RT $individu[rt] / RW $individu[rw]",$buffer);
			$buffer=str_replace("[rt]","$individu[rt]",$buffer);
			$buffer=str_replace("[rw]","$individu[rw]",$buffer);
			$buffer=str_replace("[dusun]","$individu[dusun]",$buffer);
			$buffer=str_replace("[nama_ayah]","$individu[nama_ayah]",$buffer);
			$buffer=str_replace("[nama_ibu]","$individu[nama_ibu]",$buffer);
			$buffer=str_replace("[nik_ayah]","$individu[ayah_nik]",$buffer);
			$buffer=str_replace("[nik_ibu]","$individu[ibu_nik]",$buffer);
			$buffer=str_replace("[nama]","$individu[nama]",$buffer);
			$buffer=str_replace("[sex]","$individu[sex]",$buffer);
			$buffer=str_replace("[agama]","$individu[agama]",$buffer);
			$buffer=str_replace("[status_kawin]","$individu[status_kawin]",$buffer);
			$buffer=str_replace("[gol_darah]","$individu[gol_darah]",$buffer);
			$buffer=str_replace("[pekerjaan]","$individu[pekerjaan]",$buffer);
			$buffer=str_replace("[pendidikan]","$individu[pendidikan]",$buffer);
			$buffer=str_replace("[warga_negara]","$individu[warganegara]",$buffer);
			$buffer=str_replace("[no_ktp]","$individu[nik]",$buffer);
			$buffer=str_replace("[hubungan]","$individu[hubungan]",$buffer);
			$buffer=str_replace("[nik]","$individu[nik]",$buffer);
			$buffer=str_replace("*usia","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[usia]","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[no_kk]","$individu[no_kk]",$buffer);
			$buffer=str_replace("[ttl]","$individu[tempatlahir]/$tgllhr",$buffer);
			$buffer=str_replace("[nama_lahir]","$input[nama_lahir]",$buffer);
			$buffer=str_replace("[nik_lahir]","$input[nik_lahir]",$buffer);
			$buffer=str_replace("[nama_mati]","$input[nama_mati]",$buffer);
			$buffer=str_replace("[nik_mati]","$input[nik_mati]",$buffer);
			$buffer=str_replace("[nama_doc]","$input[nama_doc]",$buffer);
			$buffer=str_replace("[dokumen]","$input[dokumen]",$buffer);
			$buffer=str_replace("[alamat_nikah]","$input[alamat_nikah]",$buffer);
			$buffer=str_replace("[tgl_nikah]","$input[tgl_nikah]",$buffer);
			$buffer=str_replace("[nama_wali]","$input[nama_wali]",$buffer);
			$buffer=str_replace("[nik_wali]","$input[nik_wali]",$buffer);
			$buffer=str_replace("[alamat_wali]","$input[alamat_wali]",$buffer);
			$buffer=str_replace("[kelamin_wali]","$input[kelamin_wali]",$buffer);
			$buffer=str_replace("[hubungan_wali]","$input[hubungan_wali]",$buffer);
			$buffer=str_replace("[agama_wali]","$input[agama_wali]",$buffer);
			$buffer=str_replace("[tptlhr_wali]","$input[tptlhr_wali]",$buffer);
			$buffer=str_replace("[tgllhr_wali]","$input[tgllhr_wali]",$buffer);
			$buffer=str_replace("[pekerjaan_wali]","$input[pekerjaan_wali]",$buffer);
			$buffer=str_replace("[sebab_wali]","$input[sebab_wali]",$buffer);
			$rp_ayah=Rupiah($input['ayah_penghasilan'])." (".Rpt($input['ayah_penghasilan']).")";
			$rp_ibu=Rupiah($input['ibu_penghasilan'])." (".Rpt($input['ibu_penghasilan']).")";
			
			$buffer=str_replace("[ayah_penghasilan]","$rp_ayah",$buffer);
			$buffer=str_replace("[ibu_penghasilan]","$rp_ibu",$buffer);
			$total = $input['ibu_penghasilan']+$input['ayah_penghasilan'];
			
			$rpt = Rpt($total);
			$total = Rupiah($total);
			$buffer=str_replace("[total]","$total",$buffer);
			$buffer=str_replace("[rphuruf]","$rpt",$buffer);
			
			
			$buffer=str_replace("[sekolah]","$input[sekolah]",$buffer);
			$buffer=str_replace("[jurusan]","$input[jurusan]",$buffer);
			$buffer=str_replace("[kelas]","$input[kelas]",$buffer);
			
			$buffer=str_replace("[ayah_alamat]","$ayah[dusun] RT $ayah[rt] / RW $ayah[rw]",$buffer);
			$buffer=str_replace("[ayah_rt]","$ayah[rt]",$buffer);
			$buffer=str_replace("[ayah_rw]","$ayah[rw]",$buffer);
			$buffer=str_replace("[ayah_dusun]","$ayah[dusun]",$buffer);
			$buffer=str_replace("[ayah_nama_ayah]","$ayah[nama_ayah]",$buffer);
			$buffer=str_replace("[ayah_nama_ibu]","$ayah[nama_ibu]",$buffer);
			$buffer=str_replace("[ayah_nik_ayah]","$ayah[ayah_nik]",$buffer);
			$buffer=str_replace("[ayah_nik_ibu]","$ayah[ibu_nik]",$buffer);
			$buffer=str_replace("[ayah_nama]","$ayah[nama]",$buffer);
			$buffer=str_replace("[ayah_sex]","$ayah[sex]",$buffer);
			$buffer=str_replace("[ayah_agama]","$ayah[agama]",$buffer);
			$buffer=str_replace("[ayah_status_kawin]","$ayah[status_kawin]",$buffer);
			$buffer=str_replace("[ayah_gol_darah]","$ayah[gol_darah]",$buffer);
			$buffer=str_replace("[ayah_pekerjaan]","$ayah[pekerjaan]",$buffer);
			$buffer=str_replace("[ayah_pendidikan]","$ayah[pendidikan]",$buffer);
			$buffer=str_replace("[ayah_warga_negara]","$ayah[warganegara]",$buffer);
			$buffer=str_replace("[ayah_no_ktp]","$ayah[nik]",$buffer);
			$buffer=str_replace("[ayah_nik]","$ayah[nik]",$buffer);
			$buffer=str_replace("*ayah_usia","$ayah[umur] Tahun",$buffer);
			$buffer=str_replace("[ayah_usia]","$ayah[umur] Tahun",$buffer);
			$buffer=str_replace("[ayah_no_kk]","$ayah[no_kk]",$buffer);
			$buffer=str_replace("[ayah_ttl]","$ayah[tempatlahir]/$ayah_tgllhr",$buffer);
			
			$buffer=str_replace("[ibu_alamat]","$ibu[dusun] RT $ibu[rt] / RW $ibu[rw]",$buffer);
			$buffer=str_replace("[ibu_rt]","$ibu[rt]",$buffer);
			$buffer=str_replace("[ibu_rw]","$ibu[rw]",$buffer);
			$buffer=str_replace("[ibu_dusun]","$ibu[dusun]",$buffer);
			$buffer=str_replace("[ibu_nama_ayah]","$ibu[nama_ayah]",$buffer);
			$buffer=str_replace("[ibu_nama_ibu]","$ibu[nama_ibu]",$buffer);
			$buffer=str_replace("[ibu_nik_ayah]","$ibu[ibu_nik]",$buffer);
			$buffer=str_replace("[ibu_nik_ibu]","$ibu[ibu_nik]",$buffer);
			$buffer=str_replace("[ibu_nama]","$ibu[nama]",$buffer);
			$buffer=str_replace("[ibu_sex]","$ibu[sex]",$buffer);
			$buffer=str_replace("[ibu_agama]","$ibu[agama]",$buffer);
			$buffer=str_replace("[ibu_status_kawin]","$ibu[status_kawin]",$buffer);
			$buffer=str_replace("[ibu_gol_darah]","$ibu[gol_darah]",$buffer);
			$buffer=str_replace("[ibu_pekerjaan]","$ibu[pekerjaan]",$buffer);
			$buffer=str_replace("[ibu_pendidikan]","$ibu[pendidikan]",$buffer);
			$buffer=str_replace("[ibu_warga_negara]","$ibu[warganegara]",$buffer);
			$buffer=str_replace("[ibu_no_ktp]","$ibu[nik]",$buffer);
			$buffer=str_replace("[ibu_nik]","$ibu[nik]",$buffer);
			$buffer=str_replace("*ibu_usia","$ibu[umur] Tahun",$buffer);
			$buffer=str_replace("[ibu_usia]","$ibu[umur] Tahun",$buffer);
			$buffer=str_replace("[ibu_no_kk]","$ibu[no_kk]",$buffer);
			$buffer=str_replace("[ibu_ttl]","$ibu[tempatlahir]/$ibu_tgllhr",$buffer);
			
			$buffer=str_replace("[kua]","$input[kua]",$buffer);
			$buffer=str_replace("[nomor_nikah]","$input[nomor_nikah]",$buffer);
			
			$buffer=str_replace("[hari_lahir]","$input[hari_lahir]",$buffer);
			$buffer=str_replace("[tgl_lahir]","$input[tgl_lahir]",$buffer);
			$buffer=str_replace("[jam_lahir]","$input[jam_lahir]",$buffer);
			$buffer=str_replace("[tpt_lahir]","$input[tpt_lahir]",$buffer);
			$buffer=str_replace("[sex_lahir]","$input[sex_lahir]",$buffer);
			$buffer=str_replace("[hub_lapor]","$input[hub_lapor]",$buffer);
			
			
			$buffer=str_replace("[hari_mati]","$input[hari_mati]",$buffer);
			$buffer=str_replace("[tgl_mati]","$input[tgl_mati]",$buffer);
			$buffer=str_replace("[jam_mati]","$input[jam_mati]",$buffer);
			$buffer=str_replace("[tpt_mati]","$input[tpt_mati]",$buffer);
			$buffer=str_replace("[sebab_mati]","$input[sebab_mati]",$buffer);
			
			$buffer=str_replace("[nama_baru1]","$input[nama_baru1]",$buffer);
			$buffer=str_replace("[tpt_baru1]","$input[tpt_baru1]",$buffer);
			$buffer=str_replace("[tgl_baru1]","$input[tgl_baru1]",$buffer);
			$buffer=str_replace("[hubkel_baru1]","$input[hubkel_baru1]",$buffer);
			$buffer=str_replace("[nama_baru2]","$input[nama_baru2]",$buffer);
			$buffer=str_replace("[tpt_baru2]","$input[tpt_baru2]",$buffer);
			$buffer=str_replace("[tgl_baru2]","$input[tgl_baru2]",$buffer);
			$buffer=str_replace("[hubkel_baru2]","$input[hubkel_baru2]",$buffer);
			$buffer=str_replace("[nama_baru3]","$input[nama_baru3]",$buffer);
			$buffer=str_replace("[tpt_baru3]","$input[tpt_baru3]",$buffer);
			$buffer=str_replace("[tgl_baru3]","$input[tgl_baru3]",$buffer);
			$buffer=str_replace("[hubkel_baru3]","$input[hubkel_baru3]",$buffer);
			$buffer=str_replace("[nama_baru4]","$input[nama_baru4]",$buffer);
			$buffer=str_replace("[tpt_baru4]","$input[tpt_baru4]",$buffer);
			$buffer=str_replace("[tgl_baru4]","$input[tgl_baru4]",$buffer);
			$buffer=str_replace("[hubkel_baru4]","$input[hubkel_baru4]",$buffer);
			$buffer=str_replace("[nama_baru5]","$input[nama_baru5]",$buffer);
			$buffer=str_replace("[tpt_baru5]","$input[tpt_baru5]",$buffer);
			$buffer=str_replace("[tgl_baru5]","$input[tgl_baru5]",$buffer);
			$buffer=str_replace("[hubkel_baru5]","$input[hubkel_baru5]",$buffer);
			$buffer=str_replace("[nama_baru6]","$input[nama_baru6]",$buffer);
			$buffer=str_replace("[tpt_baru6]","$input[tpt_baru6]",$buffer);
			$buffer=str_replace("[tgl_baru6]","$input[tgl_baru6]",$buffer);
			$buffer=str_replace("[hubkel_baru6]","$input[hubkel_baru6]",$buffer);
			$buffer=str_replace("[saksi_baru1]","$input[saksi_baru1]",$buffer);
			$buffer=str_replace("[saksi_baru2]","$input[saksi_baru2]",$buffer);
			
			$buffer=str_replace("[pria_status]","$input[pria_status]",$buffer);
			$buffer=str_replace("[wanita_status]","$input[wanita_status]",$buffer);
			$buffer=str_replace("[istri_lama]","$input[istri_lama]",$buffer);
			$buffer=str_replace("[nama_calon]","$input[nama_calon]",$buffer);
			$buffer=str_replace("[binti]","$input[binti]",$buffer);
			$buffer=str_replace("[alamat_calon]","$input[alamat_calon]",$buffer);
			$buffer=str_replace("[tpt_lahir_calon]","$input[tpt_lahir_calon]",$buffer);
			$buffer=str_replace("[tgl_lahir_calon]","$input[tgl_lahir_calon]",$buffer);
			$buffer=str_replace("[warga_negara_calon]","$input[warga_negara_calon]",$buffer);
			$buffer=str_replace("[agama_calon]","$input[agama_calon]",$buffer);
			$buffer=str_replace("[kerja_calon]","$input[kerja_calon]",$buffer);
			$buffer=str_replace("[hari_nikah]","$input[hari_nikah]",$buffer);
			$buffer=str_replace("[tgl_nikah]","$input[tgl_nikah]",$buffer);
			$buffer=str_replace("[jam_nikah]","$input[jam_nikah]",$buffer);
			$buffer=str_replace("[tpt_nikah]","$input[tpt_nikah]",$buffer);
			$buffer=str_replace("[mahar_nikah]","$input[mahar_nikah]",$buffer);
			
			$buffer=str_replace("[kartu_beda]","$input[kartu_beda]",$buffer);
			$buffer=str_replace("[identitas_beda]","$input[identitas_beda]",$buffer);
			$buffer=str_replace("[nama_beda]","$input[nama_beda]",$buffer);
			$buffer=str_replace("[tempatlahir]","$input[tempatlahir]",$buffer);
			$buffer=str_replace("[tgllahir]","$input[tgllahir]",$buffer);
			
			foreach ($input as $key => $entry){
				$buffer=str_replace("[$key]",$entry,$buffer);
			}
			
			$buffer=str_replace("[kode_surat]","$surat[kode_surat]",$buffer);
			$buffer=str_replace("[judul_surat]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[JUDUL_SURAT]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[tgl_surat]","$tgl",$buffer);
			$buffer=str_replace("[tahun]","$thn",$buffer);
			
			$buffer=str_replace("[nomor_surat]","$input[nomor]",$buffer);
			$buffer=str_replace("[nomor_sorat]","$input[nomor]",$buffer);
			
			if($input['berlaku_dari'] == "")
				$input['berlaku_dari'] = "..........................";
			
			if($input['berlaku_sampai'] == "")
				$input['berlaku_sampai'] = "........................";
			
			
			$buffer=str_replace("[mulai_berlaku]","$input[berlaku_dari]",$buffer);
			$buffer=str_replace("[tgl_akhir]","$input[berlaku_sampai]",$buffer);
			
			
			//PENGIKUT
			$pxnama = "";
			$pxnik = "";
			$pxhubungan = "";
			$pxusia = "";
			if(isset($_POST['id_cb'])){
				$pengikut = $this->pengikut();
				$nom = 1;
				foreach($pengikut AS $pgkt){
					$pxnama .= $pgkt['nama']."\line \line ";
					$pxnik .= $pgkt['nik']."\line \line ";
					$pxhubungan .= $pgkt['hubungan']."\line \line ";
					$pxusia .= $pgkt['umur']." Thn\line \line ";
					$pxtglahir .= $pgkt['tanggallahir']."\line \line ";
					$pxtmplahir .= $pgkt['tempatlahir']."\line \line ";
					$pxttl .= $pgkt['tempatlahir'].", ".tgl_indo($pgkt['tanggallahir'])."\line ";
					$pxttl2 .= $pgkt['tempatlahir'].", ".rev_tgl($pgkt['tanggallahir'])."\line ";
					$pxno .= $nom."\line \line ";
					
					$nom++;
				}
				
				$buffer=str_replace("[px_nama]","$pxnama",$buffer);
				$buffer=str_replace("[px_nik]","$pxnik",$buffer);
				$buffer=str_replace("[px_hubungan]","$pxhubungan",$buffer);
				$buffer=str_replace("[px_usia]","$pxusia",$buffer);
				$buffer=str_replace("[px_tempatlahir]","$pxtglahir",$buffer);
				$buffer=str_replace("[px_tanggallahir]","$pxtmplahir",$buffer);
				$buffer=str_replace("[px_ttl]","$pxttl",$buffer);
				$buffer=str_replace("[px_ttl2]","$pxttl2",$buffer);
				$buffer=str_replace("[no]","$pxno",$buffer);
				
				
			}
			
			unset($input['id_cb']);
			
			$berkas_arsip = $path_arsip.$url."_".$individu['nik']."_".date("Y-m-d").".rtf";
			$handle = fopen($berkas_arsip,'w+');
			fwrite($handle,$buffer);
			fclose($handle);
			$_SESSION['success']=8;
			header("location:".base_url($berkas_arsip));
		}
		
	}
}