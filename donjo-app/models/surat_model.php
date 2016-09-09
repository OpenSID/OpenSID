<?php class Surat_Model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model('surat_master_model');
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
		$sql   = "SELECT u.*,g.nama AS gol_darah,x.nama AS sex,
			(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
			w.nama AS status_kawin,f.nama AS warganegara,a.nama AS agama,d.nama AS pendidikan,h.nama AS hubungan,j.nama AS pekerjaan,c.rt AS rt,c.rw AS rw,c.dusun AS dusun,k.no_kk AS no_kk,
			(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
			from tweb_penduduk u
			left join tweb_penduduk_sex x on u.sex = x.id
			left join tweb_penduduk_kawin w on u.status_kawin = w.id
			left join tweb_penduduk_hubungan h on u.kk_level = h.id
			left join tweb_penduduk_agama a on u.agama_id = a.id
			left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
			left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
			left join tweb_wil_clusterdesa c on u.id_cluster = c.id
			left join tweb_keluarga k on u.id_kk = k.id
			left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
			left join tweb_golongan_darah g on u.golongan_darah_id = g.id
			WHERE u.id = ?";
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
		$sql   = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn, n.nama as agama,c.rw,c.rt,c.dusun,(DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( u.tanggallahir ) ) , '%Y' ) +0) as umur, sex.nama as sex
			FROM tweb_penduduk u
			left join tweb_penduduk_hubungan h on u.kk_level=h.id
			left join tweb_keluarga k on u.id_kk=k.id
			left join tweb_penduduk p on k.nik_kepala=p.id
			left join tweb_golongan_darah g on u.golongan_darah_id=g.id
			left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id=d.id
			left join tweb_penduduk_pekerjaan r on u.pekerjaan_id=r.id
			left join tweb_cacat m on u.cacat_id=m.id
			left join tweb_wil_clusterdesa c on u.id_cluster=c.id
			left join tweb_penduduk_warganegara w on u.warganegara_id=w.id
			left join tweb_penduduk_agama n on u.agama_id=n.id
			LEFT JOIN tweb_penduduk_sex sex ON u.sex=sex.id
			WHERE u.id=?";
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

	function get_data_penduduk($id=0){
		$sql   = "SELECT u.* FROM tweb_penduduk u WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function get_data_istri($id=0){
		$sql = "SELECT u.id
			FROM tweb_penduduk u
			WHERE u.id=(SELECT id FROM tweb_penduduk WHERE id_kk=(SELECT id_kk FROM tweb_penduduk WHERE id=$id AND kk_level=1) AND kk_level=3 limit 1)";
		$query = $this->db->query($sql);
		$data  = $query->row_array();

		$istri_id = $data['id'];
		$istri = $this->get_data_pribadi($istri_id);
		return $istri;
	}

	function get_data_suami($id=0){
		$sql = "SELECT u.id
			FROM tweb_penduduk u
			WHERE u.id=(SELECT id FROM tweb_penduduk WHERE id_kk=(SELECT id_kk FROM tweb_penduduk WHERE id=$id AND kk_level=3) AND kk_level=1 limit 1 )";
		$query = $this->db->query($sql);
		$data  = $query->row_array();

		$suami_id = $data['id'];
		$suami = $this->get_data_pribadi($suami_id);
		return $suami;
	}

	function get_data_suami_atau_istri($individu=[]) {
		if ($individu['sex'] == "LAKI-LAKI") {
			return $this->get_data_istri($individu['id']);
		} else {
			return $this->get_data_suami($individu['id']);
		}
	}

	function get_data_ayah($id=0){
		$penduduk = $this->get_data_penduduk($id);
		// Cari berdasarkan ayah_nik dulu
		$sql = "SELECT u.id
			FROM tweb_penduduk u
			WHERE u.nik=? limit 1";
		$query = $this->db->query($sql,$penduduk['ayah_nik']);
		$data  = $query->row_array();

		// Kalau tidak ada, cari kepala keluarga pria kalau penduduknya seorang anak dalam keluarga
		if (!$data['id'] AND $penduduk['kk_level'] == 4 ) {
			$sql = "SELECT u.id
				FROM tweb_penduduk u
				WHERE (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=1 AND u.sex=1) limit 1";
			$query = $this->db->query($sql);
			$data  = $query->row_array();
		}
		$ayah_id = $data['id'];
		$ayah = $this->get_data_pribadi($ayah_id);
		return $ayah;
	}

	function get_data_ibu($id=0){
		$penduduk = $this->get_data_penduduk($id);
		// Cari berdasarkan ibu_nik dulu
		$sql = "SELECT u.id
			FROM tweb_penduduk u
			WHERE u.nik=? limit 1";
		$query = $this->db->query($sql,$penduduk['ibu_nik']);
		$data  = $query->row_array();

		// Kalau tidak ada, cari istri keluarga kalau penduduknya seorang anak dalam keluarga
		if (!$data['id'] AND $penduduk['kk_level'] == 4 ) {
			$sql = "SELECT u.id
				FROM tweb_penduduk u
				WHERE (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=3) limit 1";
			$query = $this->db->query($sql, $id);
			$data  = $query->row_array();
		}
		$ibu_id = $data['id'];
		$ibu = $this->get_data_pribadi($ibu_id);
		return $ibu;
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

	function bersihkan_kode_isian($buffer_in){
	  $buffer_out = "";
	  $in = 0;
	  while ($in < strlen($buffer_in)){
	    switch ($buffer_in[$in]) {
	      case "[":
	        # Ambil kode isian, hilangkan karakter bukan alpha
	        $kode_isian = $buffer_in[$in];
	        $in++;
	        while ($buffer_in[$in] != "]" AND $in < strlen($buffer_in)) {
	          $kode_isian .= $buffer_in[$in];
	          $in++;
	        }
	        if ($in < strlen($buffer_in)) {
	          $kode_isian .= $buffer_in[$in];
	          $in++;
	        }
	        // Ganti karakter non-alphanumerik supaya bisa di-cek
	        $kode_isian = preg_replace('/[^a-zA-Z0-9_\{\}\[\]\-]/', '#', $kode_isian);
	        // Regex ini untuk membersihkan kode isian dari karakter yang dimasukkan oleh Word
	        // Regex ini disusun berdasarkan RTF yang dihasilkan oleh Word 2011 di Mac.
	        // Perlu diverifikasi regex ini berlaku juga untuk RTF yang dihasilkan oleh versi Word lain.
	        $regex = "/(\}.?#)|rtlch.?#|fcs.?#+|afs.?\d#+|f\d*?\d#|fs\d*?\d#|af\d*?\d#+|ltrch#+|insrsid\d*?\d#+|charrsid\d*?\d#+|#+/";
	        $kode_isian = preg_replace($regex, "", $kode_isian);
	        $buffer_out .= $kode_isian;
	        break;

	      default:
	        # Ambil isi yang bukan bagian dari kode isian
	        $buffer_out .= $buffer_in[$in];
	        $in++;
	        break;
	    }
	  }
	  return $buffer_out;
	}

	function surat_rtf_khusus($url, $input, &$buffer, $config, $individu, $ayah, $ibu) {
		$alamat_desa = "Desa ".$config[nama_desa].", Kecamatan ".$config[nama_kecamatan].", Kabupaten ".$config[nama_kabupaten];
		// Proses surat yang membutuhkan pengambilan data khusus
		switch ($url) {
			case 'surat_persetujuan_mempelai':
				# Data suami
				if ($input['id_suami']) {
					$suami = $this->get_data_surat($input['id_suami']);
					$buffer=str_replace("[form_nama_suami]",$suami['nama'],$buffer);
					$buffer=str_replace("[form_bin_suami]",$suami['nama_ayah'],$buffer);
					$buffer=str_replace("[form_tempatlahir_suami]",$suami['tempatlahir'],$buffer);
					$buffer=str_replace("[form_tanggallahir_suami]",tgl_indo_dari_str($suami['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_suami]",$suami['warganegara'],$buffer);
					$buffer=str_replace("[form_agama_suami]",$suami['agama'],$buffer);
					$buffer=str_replace("[form_pekerjaan_suami]",$suami['pekerjaan'],$buffer);
					$buffer=str_replace("[form_tempat_tinggal_suami]","RT $suami[rt] / RW $suami[rw] $suami[dusun] $alamat_desa",$buffer);
				}
				if ($input['id_istri']) {
					$istri = $this->get_data_surat($input['id_istri']);
					$buffer=str_replace("[form_nama_istri]",$istri['nama'],$buffer);
					$buffer=str_replace("[form_bin_istri]",$istri['nama_ayah'],$buffer);
					$buffer=str_replace("[form_tempatlahir_istri]",$istri['tempatlahir'],$buffer);
					$buffer=str_replace("[form_tanggallahir_istri]",tgl_indo_dari_str($istri['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_istri]",$istri['warganegara'],$buffer);
					$buffer=str_replace("[form_agama_istri]",$istri['agama'],$buffer);
					$buffer=str_replace("[form_pekerjaan_istri]",$istri['pekerjaan'],$buffer);
					$buffer=str_replace("[form_tempat_tinggal_istri]","RT $istri[rt] / RW $istri[rw] $istri[dusun] $alamat_desa",$buffer);
				}
				break;

			case 'surat_ket_kelahiran':
				# Data suami
				$suami = $this->get_data_suami($individu['id']);
				$buffer=str_replace("[nama_suami]",$suami['nama'],$buffer);
				$buffer=str_replace("[nik_suami]",$suami['nik'],$buffer);
				$buffer=str_replace("[usia_suami]","$suami[umur] Tahun",$buffer);
				$buffer=str_replace("[pekerjaan_suami]",$suami['pek'],$buffer);
				$buffer=str_replace("[alamat_suami]","RT $suami[rt] / RW $suami[rw] $suami[dusun]",$buffer);
				break;

			case 'surat_permohonan_cerai':
				# Data istri
				$istri = $this->get_data_istri($individu['id']);
				$buffer=str_replace("[nama_istri]",$istri['nama'],$buffer);
				$buffer=str_replace("[nik_istri]",$istri['nik'],$buffer);
				$buffer=str_replace("[tempatlahir_istri]","$istri[tempatlahir]",$buffer);
				$buffer=str_replace("[tanggallahir_istri]",tgl_indo_dari_str($istri['tanggallahir']),$buffer);
				$buffer=str_replace("[pekerjaan_istri]",$istri['pek'],$buffer);
				$buffer=str_replace("[agama_istri]",$istri['agama'],$buffer);
				$buffer=str_replace("[alamat_istri]","RT $istri[rt] / RW $istri[rw] $istri[dusun]",$buffer);
				break;

			case 'surat_ket_orangtua':
				# Data orang tua apabila warga desa
				if ($ayah) {
					$buffer=str_replace("[form_nama_ayah]",$ayah['nama'],$buffer);
					$buffer=str_replace("[form_tempat_lahir_ayah]",$ayah['tempatlahir'],$buffer);
					$buffer=str_replace("[form_tgl_lahir_ayah]",tgl_indo_dari_str($ayah['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ayah]",$ayah['wn'],$buffer);
					$buffer=str_replace("[form_agama_ayah]",$ayah['agama'],$buffer);
					$buffer=str_replace("[form_pekerjaan_ayah]",$ayah['pek'],$buffer);
					$buffer=str_replace("[form_tempat_tinggal_ayah]","RT $ayah[rt] / RW $ayah[rw] $ayah[dusun] $alamat_desa",$buffer);
				}
				if ($ibu) {
					$buffer=str_replace("[form_nama_ibu]",$ibu['nama'],$buffer);
					$buffer=str_replace("[form_tempat_lahir_ibu]",$ibu['tempatlahir'],$buffer);
					$buffer=str_replace("[form_tgl_lahir_ibu]",tgl_indo_dari_str($ibu['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ibu]",$ibu['wn'],$buffer);
					$buffer=str_replace("[form_agama_ibu]",$ibu['agama'],$buffer);
					$buffer=str_replace("[form_pekerjaan_ibu]",$ibu['pek'],$buffer);
					$buffer=str_replace("[form_tempat_tinggal_ibu]","RT $ibu[rt] / RW $ibu[rw] $ibu[dusun] $alamat_desa",$buffer);
				}
				break;

			case 'surat_ket_asalusul':
				# Data orang tua apabila warga desa
				if ($ayah) {
					$buffer=str_replace("[form_nama_ayah]",$ayah['nama'],$buffer);
					$buffer=str_replace("[form_tempatlahir_ayah]",$ayah['tempatlahir'],$buffer);
					$buffer=str_replace("[form_tanggallahir_ayah]",tgl_indo_dari_str($ayah['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ayah]",$ayah['wn'],$buffer);
					$buffer=str_replace("[form_agama_ayah]",$ayah['agama'],$buffer);
					$buffer=str_replace("[form_pek_ayah]",$ayah['pek'],$buffer);
					$buffer=str_replace("[form_alamat_ayah]","RT $ayah[rt] / RW $ayah[rw] $ayah[dusun]",$buffer);
				}
				if ($ibu) {
					$buffer=str_replace("[form_nama_ibu]",$ibu['nama'],$buffer);
					$buffer=str_replace("[form_tempatlahir_ibu]",$ibu['tempatlahir'],$buffer);
					$buffer=str_replace("[form_tanggallahir_ibu]",tgl_indo_dari_str($ibu['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ibu]",$ibu['wn'],$buffer);
					$buffer=str_replace("[form_agama_ibu]",$ibu['agama'],$buffer);
					$buffer=str_replace("[form_pek_ibu]",$ibu['pek'],$buffer);
					$buffer=str_replace("[form_alamat_ibu]","RT $ibu[rt] / RW $ibu[rw] $ibu[dusun]",$buffer);
				}
				break;

			case 'surat_ket_kematian_suami_istri':
				# Data suami atau istri apabila warga desa
				if ($individu['sex'] == "LAKI-LAKI") {
					$buffer=str_replace("[suami_atau_istri]","suami",$buffer);
				} else {
					$buffer=str_replace("[suami_atau_istri]","istri",$buffer);
				}
				$suami_atau_istri = $this->get_data_suami_atau_istri($individu);
				if ($suami_atau_istri) {
					$buffer=str_replace("[form_nama]",$suami_atau_istri['nama'],$buffer);
					$buffer=str_replace("[form_tempat_lahir]",$suami_atau_istri['tempatlahir'],$buffer);
					$buffer=str_replace("[form_tanggal_lahir]",tgl_indo_dari_str($suami_atau_istri['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn]",$suami_atau_istri['wn'],$buffer);
					$buffer=str_replace("[form_agama]",$suami_atau_istri['agama'],$buffer);
					$buffer=str_replace("[form_pekerjaan]",$suami_atau_istri['pek'],$buffer);
					$buffer=str_replace("[form_tempat_tinggal]","RT $suami_atau_istri[rt] / RW $suami_atau_istri[rw] $suami_atau_istri[dusun]",$buffer);
				}
				break;

			default:
				# code...
				break;
		}
	}

	function surat_rtf($url='', $input){
		// Ambil data
		$id = $input['nik'];
		$tgl = tgl_indo(date("Y m d"));
		$thn = date("Y");
		$individu = $this->get_data_surat($id);
		$ayah = $this->get_data_ayah($id);
		$ibu = $this->get_data_ibu($id);
		$config = $this->get_data_desa();
		$surat = $this->get_surat($url);

		$tgllhr = strtoupper(tgl_indo($individu['tanggallahir']));
		$individu['nama'] = strtoupper($individu['nama']);
		$individu['tempatlahir'] = strtoupper($individu['tempatlahir']);

		// Pakai surat ubahan desa apabila ada
		$file = SuratExportDesa($url);
		if($file == ""){
			$file = "surat/$url/$url.rtf";
		}

		if(is_file($file)){
			$handle = fopen($file,'r');
			$buffer = stream_get_contents($handle);
			$buffer = $this->bersihkan_kode_isian($buffer);

			//PRINSIP FUNGSI
			//-> [kata_template] -> akan digantikan dengan data di bawah ini (sebelah kanan)

			// Proses surat yang membutuhkan pengambilan data khusus
			$this->surat_rtf_khusus($url, $input, $buffer, $config, $individu, $ayah, $ibu);

			//DATA SURAT
			$buffer=str_replace("[kode_surat]","$surat[kode_surat]",$buffer);
			$buffer=str_replace("[judul_surat]",strtoupper("surat ".$surat['nama']),$buffer);
			$buffer=str_replace("[tgl_surat]","$tgl",$buffer);
			$buffer=str_replace("[tahun]","$thn",$buffer);

			//DATA DARI KONFIGURASI DESA
			$buffer=str_replace("[kode_desa]","$config[kode_desa]",$buffer);
			$buffer=str_replace("[nama_kab]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kabupaten]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kec]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_kecamatan]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_provinsi]","$config[nama_propinsi]",$buffer);
			$buffer=str_replace("[nama_kepala_camat]","$config[nama_kepala_camat]",$buffer);
			$buffer=str_replace("[nip_kepala_camat]","$config[nip_kepala_camat]",$buffer);
			$buffer=str_replace("[nama_des]","$config[nama_desa]",$buffer);
			$buffer=str_replace("[pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[kode_pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[alamat_des]","$config[alamat_kantor] Kode Pos : $config[kode_pos]",$buffer);
			$buffer=str_replace("[alamat_desa]","$config[alamat_kantor] Kode Pos : $config[kode_pos]",$buffer);

			//DATA DARI TABEL PENDUDUK
			//jika data kurang lengkap bisa di tambahkan dari fungsi "get_data_surat" pada file ini
			$buffer=str_replace("[alamat]","RT $individu[rt] / RW $individu[rw] $individu[dusun]",$buffer);
			$buffer=str_replace("[nama_ayah]","$individu[nama_ayah]",$buffer);
			$buffer=str_replace("[nama_ibu]","$individu[nama_ibu]",$buffer);
			$buffer=str_replace("[kepala_kk]","$individu[kepala_kk]",$buffer);
			$buffer=str_replace("[nama]","$individu[nama]",$buffer);
			$buffer=str_replace("[sex]","$individu[sex]",$buffer);
			$buffer=str_replace("[agama]","$individu[agama]",$buffer);
			$buffer=str_replace("[hubungan]","$individu[hubungan]",$buffer);
			$buffer=str_replace("[gol_darah]","$individu[gol_darah]",$buffer);
			$buffer=str_replace("[status]","$individu[status_kawin]",$buffer);
			$buffer=str_replace("[pendidikan]","$individu[pendidikan]",$buffer);
			$buffer=str_replace("[pekerjaan]","$individu[pekerjaan]",$buffer);
			$buffer=str_replace("[warga_negara]","$individu[warganegara]",$buffer);
			$buffer=str_replace("[no_ktp]","$individu[nik]",$buffer);
			$buffer=str_replace("*usia","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[usia]","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[no_kk]","$individu[no_kk]",$buffer);
			$buffer=str_replace("[ibu_nik]","$individu[ibu_nik]",$buffer);
			$buffer=str_replace("[ayah_nik]","$individu[ayah_nik]",$buffer);
			$buffer=str_replace("[tempatlahir]","$individu[tempatlahir]",$buffer);
			$buffer=str_replace("[tanggallahir]","$tgllhr",$buffer);
			$buffer=str_replace("[ttl]","$individu[tempatlahir]/$tgllhr",$buffer);
			$buffer=str_replace("[tempat_tgl_lahir]","$individu[tempatlahir]/$tgllhr",$buffer);

			// DATA AYAH dan IBU
			$buffer=str_replace("[d_nama_ibu]","$ibu[nama]",$buffer);
			$buffer=str_replace("[d_nik_ibu]","$ibu[nik]",$buffer);
			$buffer=str_replace("[d_tempatlahir_ibu]","$ibu[tempatlahir]",$buffer);
			$buffer=str_replace("[d_tanggallahir_ibu]",tgl_indo_dari_str($ibu['tanggallahir']),$buffer);
			$buffer=str_replace("[d_warganegara_ibu]","$ibu[wn]",$buffer);
			$buffer=str_replace("[d_agama_ibu]","$ibu[agama]",$buffer);
			$buffer=str_replace("[d_pekerjaan_ibu]","$ibu[pek]",$buffer);
			$buffer=str_replace("[d_alamat_ibu]","RT $ibu[rt] / RW $ibu[rw] $ibu[dusun]",$buffer);
			$buffer=str_replace("[d_nama_ayah]","$ayah[nama]",$buffer);
			$buffer=str_replace("[d_nik_ayah]","$ayah[nik]",$buffer);
			$buffer=str_replace("[d_tempatlahir_ayah]","$ayah[tempatlahir]",$buffer);
			$buffer=str_replace("[d_tanggallahir_ayah]",tgl_indo_dari_str($ayah['tanggallahir']),$buffer);
			$buffer=str_replace("[d_warganegara_ayah]","$ayah[wn]",$buffer);
			$buffer=str_replace("[d_agama_ayah]","$ayah[agama]",$buffer);
			$buffer=str_replace("[d_pekerjaan_ayah]","$ayah[pek]",$buffer);
			$buffer=str_replace("[d_alamat_ayah]","RT $ayah[rt] / RW $ayah[rw] $ayah[dusun]",$buffer);

			//DATA DARI FORM INPUT SURAT
			// Kode isian yang disediakan pada SID CRI 3.04
			$buffer=str_replace("[nomor_surat]","$input[nomor]",$buffer);
			$buffer=str_replace("[nomor_sorat]","$input[nomor]",$buffer);
			$buffer=str_replace("[mulai_berlaku]",tgl_indo(date('Y m d',strtotime($input[berlaku_dari]))),$buffer);
			$buffer=str_replace("[tgl_akhir]",tgl_indo(date('Y m d',strtotime($input[berlaku_sampai]))),$buffer);
			$buffer=str_replace("[jabatan]","$input[jabatan]",$buffer);
			$buffer=str_replace("[nama_pamong]","$input[pamong]",$buffer);
			$buffer=str_replace("[keterangan]","$input[keterangan]",$buffer);
			$buffer=str_replace("[keperluan]","$input[keperluan]",$buffer);
			// $input adalah isian form surat. Kode isian dari form bisa berbentuk [form_isian]
			// sesuai dengan panduan, atau boleh juga langsung [isian] saja
			$isian_tanggal = array("berlaku_dari", "berlaku_sampai", "tanggal", "tgl_meninggal",
				"tanggal_lahir", "tanggallahir_istri", "tanggallahir_suami", "tanggal_mati",
				"tanggallahir_pasangan", "tgl_lahir_ayah", "tgl_lahir_ibu", "tgl_berakhir_paspor", "tgl_akte_perkawinan", "tgl_perceraian", "tanggallahir","tanggallahir_pelapor", "tgl_lahir", "tanggallahir_ayah", "tanggallahir_ibu", "tgl_lahir_wali", "tgl_nikah");
			foreach ($input as $key => $entry){
				// Isian tanggal diganti dengan format tanggal standar
				if (in_array($key, $isian_tanggal)){
					$buffer=preg_replace("/\[$key\]|\[form_$key\]/",tgl_indo_dari_str($entry),$buffer);
				}
				$buffer=str_replace("[form_$key]",$entry,$buffer);
				// Diletakkan di bagian akhir karena bisa sama dengan kode isian sebelumnya
				// dan kalau masih ada dianggap sebagai kode dari form isian
				$buffer=str_replace("[$key]",$entry,$buffer);
			}
		}
		return $buffer;
	}

	function coba($url='', &$nama_surat){
		$input = $_POST;
		$rtf = $this->surat_rtf($url, $input);
		// Simpan surat di folder arsip dan download
		$path_arsip = LOKASI_ARSIP;
		$berkas_arsip = $path_arsip.$nama_surat;
		$handle = fopen($berkas_arsip,'w+');
		fwrite($handle,$rtf);
		fclose($handle);
		// Untuk konversi rtf ke pdf, libreoffice harus terinstall
		if (strpos(strtoupper(php_uname('s')), 'WIN') !== false) {
			// Windows O/S
			$berkas_arsip_win = str_replace('/', "\\", $berkas_arsip);
			$fcpath = str_replace('/', "\\", FCPATH);
			$outdir = rtrim(str_replace('/',"\\",FCPATH.LOKASI_ARSIP), "/\\");
			$cmd = 'cd '.config_item('libreoffice_path');
			$cmd = $cmd." && soffice --headless --convert-to pdf:writer_pdf_Export --outdir ".$outdir." ".$fcpath.$berkas_arsip_win;
		} else {
			// Linux
			$cmd = "libreoffice --headless --norestore --convert-to pdf --outdir ".FCPATH.LOKASI_ARSIP." ".FCPATH.$berkas_arsip;
		}
		exec($cmd, $output, $return);
		// Kalau berhasil, pakai pdf
		if ($return==0) {
			$nama_surat = pathinfo($nama_surat, PATHINFO_FILENAME).".pdf";
			$berkas_arsip = $path_arsip.$nama_surat;
		}

		$_SESSION['success']=8;
		header("location:".base_url($berkas_arsip));
	}

}
