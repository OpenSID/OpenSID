<?php class Penduduk_Model extends CI_Model{

	function __construct(){
		parent::__construct();

		$this->load->model('keluarga_model');
	}

	function autocomplete(){
		$sql   = "SELECT nama FROM tweb_penduduk";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['nama'].'"';
			$i++;
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		return $outp;
	}


	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = penetration($this->db->escape_like_str($cari));
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR u.nik LIKE '$kw')";
			return $search_sql;
			}
	}

	function sex_sql(){
		if(isset($_SESSION['sex'])){
			$kf = $_SESSION['sex'];
			$sex_sql= " AND u.sex = $kf";
		return $sex_sql;
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

	function agama_sql(){
		if(isset($_SESSION['agama'])){
			$kf = $_SESSION['agama'];
			$agama_sql= " AND u.agama_id = $kf";
		return $agama_sql;
		}
	}

	function warganegara_sql(){
		if(isset($_SESSION['warganegara'])){
			$kf = $_SESSION['warganegara'];
			$warganegara_sql= " AND u.warganegara_id = $kf";
		return $warganegara_sql;
		}
	}

	function golongan_darah_sql(){
		if(isset($_SESSION['golongan_darah'])){
			$kf = $_SESSION['golongan_darah'];
			$golongan_darah_sql= " AND u.golongan_darah_id = $kf";
		return $golongan_darah_sql;
		}
	}

	function pekerjaan_sql(){
		if(isset($_SESSION['pekerjaan_id'])){
			$kf = $_SESSION['pekerjaan_id'];
			$pekerjaan_sql= " AND u.pekerjaan_id = $kf";
		return $pekerjaan_sql;
		}
	}

	function cacat_sql(){
		if(isset($_SESSION['cacat'])){
			$kf = $_SESSION['cacat'];
			$cacat_sql= " AND u.cacat_id = $kf";
		return $cacat_sql;
		}
	}

	function cara_kb_sql(){
		if(isset($_SESSION['cara_kb_id'])){
			$kf = $_SESSION['cara_kb_id'];
			$cara_kb_sql= " AND u.cara_kb_id = $kf";
		return $cara_kb_sql;
		}
	}
	function cacatx_sql(){
		if(isset($_SESSION['cacatx'])){
			$kf = $_SESSION['cacatx'];
			$cacatx_sql= " AND u.cacat_id <> $kf AND u.cacat_id is not null and u.cacat_id<>''";
		return $cacatx_sql;
		}
	}

	function menahun_sql(){
		if(isset($_SESSION['menahun'])){
			$kf = $_SESSION['menahun'];
			$menahun_sql= " AND u.sakit_menahun_id = $kf";
		return $menahun_sql;
		}
	}

	function menahunx_sql(){
		if(isset($_SESSION['menahunx'])){
			$kf = $_SESSION['menahunx'];
			$menahunx_sql= " AND u.sakit_menahun_id <> $kf and u.sakit_menahun_id is not null and u.sakit_menahun_id<>'0' ";
		return $menahunx_sql;
		}
	}
	function statuskawin_sql(){
		if(isset($_SESSION['status'])){
			$kf = $_SESSION['status'];
			$statuskawin_sql= " AND u.status_kawin = $kf";
		return $statuskawin_sql;
		}
	}

	function pendidikan_kk_sql(){
		if(isset($_SESSION['pendidikan_kk_id'])){
			$kf = $_SESSION['pendidikan_kk_id'];
			$pendidikan_kk_sql= " AND u.pendidikan_kk_id = $kf";
		return $pendidikan_kk_sql;
		}
	}
	function hamil_sql(){
		if(isset($_SESSION['hamil'])){
			$kf = $_SESSION['hamil'];
			$hamil_sql= " AND u.hamil = $kf";
		return $hamil_sql;
		}
	}
	function pendidikan_sedang_sql(){
		if(isset($_SESSION['pendidikan_sedang_id'])){
			$kf = $_SESSION['pendidikan_sedang_id'];
			$pendidikan_sedang_sql= " AND u.pendidikan_sedang_id = $kf";
		return $pendidikan_sedang_sql;
		}
	}

	function status_penduduk_sql(){
		if(isset($_SESSION['status_penduduk'])){
			$kf = $_SESSION['status_penduduk'];
			$status_penduduk_sql= " AND u.status = $kf";
		return $status_penduduk_sql;
		}
	}

	function umur_max_sql(){
		if(isset($_SESSION['umur_max'])){
			$kf = $_SESSION['umur_max'];
			$umur_max_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= $kf ";
		return $umur_max_sql;
		}
	}

	function umur_min_sql(){
		if(isset($_SESSION['umur_min'])){
			$kf = $_SESSION['umur_min'];
			$umur_min_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= $kf ";
		return $umur_min_sql;
		}
	}

	function umur_sql(){
		if(isset($_SESSION['umurx'])){
			$kf = $_SESSION['umurx'];
			$umur_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= (SELECT dari FROM tweb_penduduk_umur WHERE id=$kf ) AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= (SELECT sampai FROM tweb_penduduk_umur WHERE id=$kf ) ";
		return $umur_sql;
		}
	}

	function filter_sql(){
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.status = $kf";
		return $filter_sql;
		}
	}

	function log_sql(){
		if(isset($_SESSION['log'])){
			// Hanya tampilkan penduduk yang status dasarnya bukan 'HIDUP'
			$log_sql= " AND u.status_dasar > 1 ";
		}else{
			$log_sql= " AND u.status_dasar = 1 ";
		}
		return $log_sql;
	}

	function get_alamat_wilayah($id) {
		// Alamat anggota keluarga diambil dari tabel keluarga
		$this->db->select('id_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		if ($penduduk['id_kk'] > 0) {
			return $this->keluarga_model->get_alamat_wilayah($penduduk['id_kk']);
		}
		// Alamat penduduk lepas diambil dari kolom alamat_sekarang
		$sql = "SELECT a.dusun,a.rw,a.rt,u.alamat_sekarang as alamat
				FROM tweb_penduduk u
				LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
				WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();

		$alamat_wilayah= trim("$data[alamat] RT $data[rt] / RW $data[rw] $data[dusun]");
		return $alamat_wilayah;
	}

	function paging($p=1,$o=0,$log=0){

		$sql      = "SELECT COUNT(u.id) AS id FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id LEFT JOIN tweb_keluarga d ON u.id_kk = d.id LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id  LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id LEFT JOIN tweb_penduduk_sex x ON u.pendidikan_id = x.id LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id LEFT JOIN tweb_cacat f ON u.cacat_id = f.id LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id WHERe 1  ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->cacat_sql();
		$sql .= $this->cacatx_sql();
		$sql .= $this->cara_kb_sql();
		$sql .= $this->menahun_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->golongan_darah_sql();
		$sql .= $this->warganegara_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->pekerjaan_sql();
		$sql .= $this->statuskawin_sql();
		$sql .= $this->pendidikan_kk_sql();
		$sql .= $this->pendidikan_sedang_sql();
		$sql .= $this->status_penduduk_sql();
		$sql .= $this->hamil_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->log_sql();
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

	function list_data($o=0,$offset=0,$limit=500,$log=0){

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.nik'; break;
			case 2: $order_sql = ' ORDER BY u.nik DESC'; break;
			case 3: $order_sql = ' ORDER BY u.nama'; break;
			case 4: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY d.no_kk'; break;
			case 6: $order_sql = ' ORDER BY d.no_kk DESC'; break;
			case 7: $order_sql = ' ORDER BY umur'; break;
			case 8: $order_sql = ' ORDER BY umur DESC'; break;
			default:$order_sql = '';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		if ($log==1) {
			$select_sql = "SELECT u.id,u.nik,u.tanggallahir,u.tempatlahir,u.status,u.status_dasar,u.id_kk,u.nama,u.nama_ayah,u.nama_ibu,a.dusun,a.rw,a.rt,d.alamat,d.no_kk AS no_kk,log.catatan as catatan,
				(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan,log.tgl_peristiwa
				";
		} else {
			// data log tidak di-select, supaya di tabel Penduduk tidak ada duplikat
			$select_sql = "SELECT DISTINCT u.id,u.nik,u.tanggallahir,u.tempatlahir,u.status,u.status_dasar,u.id_kk,u.nama,u.nama_ayah,u.nama_ibu,a.dusun,a.rw,a.rt,d.alamat,d.no_kk AS no_kk,
				(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan
				";
		}

		//Main Query
		$sql = $select_sql."
		FROM tweb_penduduk u
		LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
		LEFT JOIN tweb_wil_clusterdesa a ON d.id_cluster = a.id
		LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id
		LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id
		LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
		LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
		LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
		LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
		LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id
		LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id
		LEFT JOIN tweb_cacat f ON u.cacat_id = f.id
		LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id
		LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id
		LEFT JOIN log_penduduk log ON u.id = log.id_pend
		WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->cacat_sql();
		$sql .= $this->cacatx_sql();
		$sql .= $this->cara_kb_sql();
		$sql .= $this->menahun_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->warganegara_sql();
		$sql .= $this->golongan_darah_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->pekerjaan_sql();
		$sql .= $this->statuskawin_sql();
		$sql .= $this->pendidikan_sedang_sql();
		$sql .= $this->pendidikan_kk_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->status_penduduk_sql();
		$sql .= $this->log_sql();
		$sql .= $this->hamil_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){

			// Ubah alamat penduduk lepas
			if (!$data[$i]['id_kk'] OR $data[$i]['id_kk'] == 0) {
				// Ambil alamat penduduk
				$sql = "SELECT p.id_cluster, p.alamat_sekarang, c.dusun, c.rw, c.rt
					FROM tweb_penduduk p
					LEFT JOIN tweb_wil_clusterdesa c on p.id_cluster = c.id
					WHERE p.id = ?
					";
				$query = $this->db->query($sql, $data[$i]['id']);
				$penduduk = $query->row_array();
				$data[$i]['alamat'] = $penduduk['alamat_sekarang'];
				$data[$i]['dusun'] = $penduduk['dusun'];
				$data[$i]['rw'] = $penduduk['rw'];
				$data[$i]['rt'] = $penduduk['rt'];
			}

			$data[$i]['no']=$j+1;

			$i++;
			$j++;
		}
		return $data;
	}

	function list_data_map(){

		//Main Query
		$sql   = "SELECT u.id,u.nik,u.nama,map.lat,map.lng,a.dusun,a.rw,a.rt,d.no_kk AS no_kk,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id LEFT JOIN tweb_keluarga d ON u.id_kk = d.id LEFT JOIN tweb_penduduk_pendidikan_kk n ON u.pendidikan_kk_id = n.id LEFT JOIN tweb_penduduk_pendidikan sd ON u.pendidikan_sedang_id = sd.id  LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id LEFT JOIN tweb_penduduk_warganegara v ON u.warganegara_id = v.id LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id LEFT JOIN tweb_cacat f ON u.cacat_id = f.id LEFT JOIN tweb_penduduk_hubungan hub ON u.kk_level = hub.id LEFT JOIN tweb_sakit_menahun j ON u.sakit_menahun_id = j.id LEFT JOIN tweb_penduduk_map map ON u.id = map.id WHERE 1 ";

		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->cacat_sql();
		$sql .= $this->cacatx_sql();
		$sql .= $this->menahun_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->warganegara_sql();
		$sql .= $this->golongan_darah_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->pekerjaan_sql();
		$sql .= $this->statuskawin_sql();
		$sql .= $this->pendidikan_sedang_sql();
		$sql .= $this->pendidikan_kk_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->status_penduduk_sql();
		$sql .= $this->hamil_sql();

		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['alamat']='';

			if($data[$i]['alamat_sekarang'] != "-")
	      $data[$i]['alamat']="KP/JL-".$data[$i]['alamat_sekarang'];

			if($data[$i]['rt'] != "-")
				$data[$i]['alamat']="RT-".$data[$i]['rt'];

			if($data[$i]['rw'] != "-")
				$data[$i]['alamat']=$data[$i]['alamat']." RW-".$data[$i]['rw'];

			if($data[$i]['dusun'] != "-")
				$data[$i]['alamat']=$data[$i]['alamat']." Dusun ".$data[$i]['dusun'];
			else
				$data[$i]['alamat']="Alamat penduduk belum valid";

			$i++;
		}
		return $data;
	}

	function validasi_data_penduduk(&$data){
		if ($data['tanggallahir'] == '') $data['tanggallahir'] = NULL;
		if ($data['tanggallahir']) $data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		if ($data['tanggalperkawinan'] == '') $data['tanggalperkawinan'] = NULL;
		if ($data['tanggalperkawinan']) $data['tanggalperkawinan'] = tgl_indo_in($data['tanggalperkawinan']);
		if ($data['tanggalperceraian'] == '') $data['tanggalperceraian'] = NULL;
		if ($data['tanggalperceraian']) $data['tanggalperceraian'] = tgl_indo_in($data['tanggalperceraian']);
		// Hanya status 'kawin' yang boleh jadi akseptor kb
		if ($data['status_kawin'] != 2) $data['cara_kb_id'] = NULL;

		$valid = array();
		if (!ctype_digit($data['nik']))
			array_push($valid, "NIK hanya berisi angka");
		if (strlen($data['nik']) != 16 AND $data['nik'] != '0')
			array_push($valid, "NIK panjangnya harus 16 atau 0");
		if (!empty($valid))
			$_SESSION['validation_error'] = true;
		return $valid;
	}

	function insert(){
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		$_SESSION['error_msg'] = '';

		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$old_foto    = $data['old_foto'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/jpg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file,$old_foto);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);

		$data['id_cluster'] = $data['rt'];
		UNSET($data['dusun']);
		UNSET($data['rw']);
		UNSET($data['rt']);

		$data['nama'] = penetration($data['nama']);
		$data['nama_ayah'] = penetration($data['nama_ayah']);
		$data['nama_ibu'] = penetration($data['nama_ibu']);

		$error_validasi = $this->validasi_data_penduduk($data);
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$outp = $this->db->insert('tweb_penduduk',$data);
		$idku = $this->db->insert_id();

		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if($_POST['status']=='3'){
			$log['id_detail']="8";
			}else{
			if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
				$log['id_detail']='1';
			}else{
				$log['id_detail']='5';
			}
		}
		$log['id_pend'] = $idku;
		//$log['id_detail'] = "8";
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$log['tgl_peristiwa'] = date("d-m-Y");
		$outp = $this->db->insert('log_penduduk',$log);

		$log1['id_pend'] = $idku;
		$log1['id_cluster'] = 1;
		$log1['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk',$log1);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;

		return $idku;
	}

	function update($id=0){
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		unset($_SESSION['error_msg']);
		$data = $_POST;

		$sql   = "SELECT id_kk FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$pend = $query->row_array();

		if($data['kk_level']==1){
			$lvl['kk_level'] = 11;
			$this->db->where('id_kk',$pend['id_kk']);
			$this->db->where('kk_level',1);
			$this->db->update('tweb_penduduk',$lvl);

			$nik['nik_kepala'] = $id;
			$this->db->where('id',$pend['id_kk']);
			$outp = $this->db->update('tweb_keluarga',$nik);
		}

		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$old_foto    = $data['old_foto'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file,$old_foto);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);

		$error_validasi = $this->validasi_data_penduduk($data);
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$data);


		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_position($id=0){
		$sql  = "SELECT id FROM tweb_penduduk_map WHERE id=?";
		$query = $this->db->query($sql,$id);
		$cek = $query->row_array();

		$data = $_POST;
		unset($data['zoom']);
		unset($data['map_tipe']);
		if($cek['id']==$id){
			if($data['lat']){
				$this->db->where('id',$id);
				$outp = $this->db->update('tweb_penduduk_map',$data);
			}
		}else{
			if($data['lat']){
				$data['id'] = $id;
				$outp = $this->db->insert('tweb_penduduk_map',$data);
			}
		}
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function get_penduduk_map($id=0){
		$sql   = "SELECT m.*,p.nama FROM tweb_penduduk_map m LEFT JOIN tweb_penduduk p ON m.id = p.id WHERE m.id = ? ";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}

	function update_status_dasar($id=0){
		$data['status_dasar'] = $_POST['status_dasar'];
		$this->db->where('id',$id);
		$this->db->update('tweb_penduduk',$data);

		$log['id_pend'] = $id;
		$log['tgl_peristiwa'] = rev_tgl($_POST['tgl_peristiwa']);
		$log['id_detail'] = $data['status_dasar'];
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$log['catatan'] = $_POST['catatan'];
		$outp = $this->db->insert('log_penduduk',$log);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete($id=''){
		$sql  = "DELETE FROM tweb_penduduk WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_penduduk WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function adv_search_proses(){
		UNSET($_POST['umur1']);
		UNSET($_POST['umur2']);

		UNSET($_POST['dusun']);
		UNSET($_POST['rt']);
		UNSET($_POST['rw']);
		$i=0;
		while($i++ < count($_POST)){
			$col[$i] = key($_POST);
				next($_POST);
		}
		$i=0;
		while($i++ < count($col)){
			if($_POST[$col[$i]]=="")
				UNSET($_POST[$col[$i]]);
		}

		$data=$_POST;
		$this->db->where($data);
		return  $this->db->get('tweb_penduduk');
	}

	function get_id_kk($id=0) {
		$sql = "SELECT u.id_kk
				FROM tweb_penduduk u
				WHERE id = ? limit 1";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data['id_kk'];
	}

	function get_penduduk($id=0){
		$sql   = "SELECT u.sex as id_sex,u.*,a.dusun,a.rw,a.rt,t.nama AS status,o.nama AS pendidikan_sedang,
		b.nama AS pendidikan_kk,d.no_kk AS no_kk,d.alamat,
		(
			SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0  FROM tweb_penduduk WHERE id = u.id
		)
		 AS umur,x.nama AS sex,w.nama AS warganegara,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama, c.nama as cacat, kb.nama as cara_kb
		 FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
			LEFT JOIN tweb_wil_clusterdesa a ON d.id_cluster = a.id
			LEFT JOIN tweb_penduduk_pendidikan n ON u.pendidikan_id = n.id
			LEFT JOIN tweb_penduduk_pendidikan o ON u.pendidikan_sedang_id = o.id
			LEFT JOIN tweb_penduduk_pendidikan_kk b ON u.pendidikan_kk_id = b.id
			LEFT JOIN tweb_penduduk_warganegara w ON u.warganegara_id = w.id
			LEFT JOIN tweb_penduduk_status t ON u.status = t.id
			LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
			LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
			LEFT JOIN tweb_cacat c ON u.cacat_id = c.id
			LEFT JOIN tweb_cara_kb kb ON u.cara_kb_id = kb.id
			WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		$data['tanggallahir'] = tgl_indo_out($data['tanggallahir']);
		$data['tanggalperkawinan'] = tgl_indo_out($data['tanggalperkawinan']);
		$data['tanggalperceraian'] = tgl_indo_out($data['tanggalperceraian']);
		// Penduduk lepas, pakai alamat penduduk
		if ($data['id_kk'] == 0 OR $data['id_kk'] == '') {
			$data['alamat'] = $data['alamat_sekarang'];
			$this->db->where('id', $data['id_cluster']);
			$query = $this->db->get('tweb_wil_clusterdesa');
			$cluster = $query->row_array();
			$data['dusun'] = $cluster['dusun'];
			$data['rw'] = $cluster['rw'];
			$data['rt'] = $cluster['rt'];
		}
		return $data;
	}

	function get_penduduk_by_nik($nik=0){
		$sql   = "SELECT u.id AS id, u.nama AS nama,x.nama AS sex,u.id_kk AS id_kk,
		u.tempatlahir AS tempatlahir,u.tanggallahir AS tanggallahir,
		(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
		w.nama AS status_kawin,f.nama AS warganegara,a.nama AS agama,h.nama as hubungan,d.nama AS pendidikan,j.nama AS pekerjaan,u.nik AS nik,c.rt AS rt,c.rw AS rw,c.dusun AS dusun,k.no_kk AS no_kk,k.alamat,
		(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
		from tweb_penduduk u
		left join tweb_penduduk_sex x on u.sex = x.id
		left join tweb_penduduk_kawin w on u.status_kawin = w.id
		left join tweb_penduduk_agama a on u.agama_id = a.id
		left join tweb_penduduk_hubungan h on u.kk_level = h.id
		left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
		left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
		left join tweb_wil_clusterdesa c on u.id_cluster = c.id
		left join tweb_keluarga k on u.id_kk = k.id
		left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
		WHERE u.nik = ?";
		$query = $this->db->query($sql,$nik);
		$data  = $query->row_array();
		$data['alamat_wilayah']= trim("$data[alamat] RT $data[rt] / RW $data[rw] $data[dusun]");
		return $data;
	}


	function list_dusun(){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_wil(){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE zoom > '0'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_rw($dusun=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND dusun = ? AND rw <> '0'";
		$query = $this->db->query($sql,$dusun);
		$data=$query->result_array();
		return $data;
	}

	function list_rw_all(){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw <> '0'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_rt($dusun='',$rw=''){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rw = ? AND dusun = ? AND rt <> '0'";
		$query = $this->db->query($sql,array($rw,$dusun));
		$data=$query->result_array();
		return $data;
	}

	function list_rt_all(){
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt <> '0' AND rw <> '-'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_agama(){
		$sql   = "SELECT * FROM tweb_penduduk_agama WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_hubungan(){
		$sql   = "SELECT * FROM tweb_penduduk_hubungan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan_telah(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,6)<> 'SEDANG' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan_sedang(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,5)<> 'TAMAT' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan_kk(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pekerjaan(){
		$sql   = "SELECT * FROM tweb_penduduk_pekerjaan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_warganegara(){
		$sql   = "SELECT * FROM tweb_penduduk_warganegara WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_status_kawin(){
		$sql   = "SELECT * FROM tweb_penduduk_kawin WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_golongan_darah(){
		$sql   = "SELECT * FROM tweb_golongan_darah WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_cacat(){
		$sql   = "SELECT * FROM tweb_cacat WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_cara_kb($sex=''){
		if ($sex != 1 AND $sex != 2) {
			$sql   = "SELECT * FROM tweb_cara_kb WHERE 1";
		} else {
			$sql   = "SELECT * FROM tweb_cara_kb WHERE sex = ? OR sex = 3";
		}
		$query = $this->db->query($sql, $sex);
		$data=$query->result_array();
		return $data;
	}

	function get_desa(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function is_anggota_keluarga($id) {
		$this->db->select('id_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		if ($penduduk['id_kk'] > 0) return true;
		else return false;
	}

	// Pindah untuk penduduk lepas (yang bukan anggota keluarga)
	function pindah_proses($id=0,$id_cluster='',$alamat){
		$this->db->where('id',$id);
		$data['alamat_sekarang'] = $alamat;
		if ($id_cluster != '') $data['id_cluster'] = $id_cluster;
		$outp = $this->db->update('tweb_penduduk',$data);

		$this->tulis_log_penduduk($id, '6', date('m'), date('Y'));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function tulis_log_penduduk($id_pend, $id_detail, $bulan, $tahun) {
    $query = "
      INSERT INTO log_penduduk (id_pend, id_detail, bulan, tahun) VALUES
      (?, ?, ?, ?)
      ON DUPLICATE KEY UPDATE
        id_pend = VALUES(id_pend),
        id_detail = VALUES(id_detail),
        bulan = VALUES(bulan),
        tahun = VALUES(tahun);
    ";
    $this->db->query($query,array($id_pend, $id_detail, $bulan, $tahun));
	}


	function get_judul_statistik($tipe=0,$nomor=1){
		switch($tipe){
			case 0: $sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE id=?";break;
			case 1: $sql   = "SELECT * FROM tweb_penduduk_pekerjaan WHERE id=?";break;
			case 2: $sql   = "SELECT * FROM tweb_penduduk_kawin WHERE id=?";break;
			case 3: $sql   = "SELECT * FROM tweb_penduduk_agama WHERE id=?";break;
			case 4: $sql   = "SELECT * FROM tweb_penduduk_sex WHERE id=?";break;
			case 5: $sql   = "SELECT * FROM tweb_penduduk_warganegara WHERE id=?";break;
			case 6: $sql   = "SELECT * FROM tweb_penduduk_status WHERE id=?";break;
			case 7: $sql   = "SELECT * FROM tweb_golongan_darah WHERE id=?";break;
			case 9: $sql   = "SELECT * FROM tweb_cacat WHERE id=?";break;
			case 10: $sql   = "SELECT * FROM tweb_sakit_menahun WHERE id=?";break;
			case 12: $sql   = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE id=?";break;
			case 13: $sql   = "SELECT * FROM tweb_penduduk_umur WHERE id=?";break;
			case 14: $sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE id=?";break;
			case 16: $sql   = "SELECT * FROM tweb_cara_kb WHERE id=?";break;
		}
		$query = $this->db->query($sql,$nomor);
		return $query->row_array();
	}

	function get_cluster($id_cluster=0){

		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE id=$id_cluster ";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function randomap(){

		$sql   = "SELECT u.id,id_cluster,map.lat,map.lng FROM tweb_penduduk u LEFT JOIN tweb_penduduk_map map ON u.id = map.id WHERE 1 ";
		$query = $this->db->query($sql);
		$data=$query->result_array();


		//Formating Output
		$i=0;
		while($i<count($data)){

		$lat = "-7.5";
		$lng = "110.4";
			$id = $data[$i]['id'];

			//$lat .= random_int(549081020,610339140);
			//$lng .= random_int(366521873,445829429);

			$lat .= $this->generateRandomString2(1);
			$lng .= $this->generateRandomString2(1);

			$lat .= $this->generateRandomString(17);
			$lng .= $this->generateRandomString(17);

			$data2['lat'] = $lat;
			$data2['lng'] = $lng;
			$data2['id'] = $id;
			$this->db->insert('tweb_penduduk_map',$data2);

			$i++;
		}
	}

	function generateRandomString($length = 5) {
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function generateRandomString2($length = 1) {
		$characters = '5678';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}


	function coba2(){
		ini_set('memory_limit', '2048M');
		$mypath="surat\\undangan\\";

		$path = "".str_replace("\\","/",$mypath);
		$path_arsip = LOKASI_ARSIP;

		$file = $path."pemuda.rtf";
		if(is_file($file)){
			$buffer2 ="";

			$handle = fopen($file,'r');
			$b = stream_get_contents($handle);

			$c = Parse_Data($b,'\expshrtn','{\*\themedata');
			$c = "\expshrtn".$c;
			$awal = Parse_Data($b,'{','\expshrtn');
			$awal = "{".$awal;
			$akhir = strstr($b,'{\*\themedata');

			$data = $this->list_data();
			$i=1;
			$h = substr_count($c,"fxnama");
			$h = 4;
			$j=count($data);
			$k =1;
			$buffer=$c;
			foreach($data AS $d){
				if($d['sex']=="PEREMPUAN")
					$sex = "Sdri.";
				else
					$sex = "Sdr.";

				$alamat = $d['dusun'].", RT ".$d['rt']."/RW ".$d['rw'];
				$buffer=str_replace("fxnama$k","\caps $d[nama]",$buffer);
				$buffer=str_replace("fxalamat$k","\caps $alamat",$buffer);
				$buffer=str_replace("fxpre$k","\caps $sex",$buffer);

				if($k==$h){
					$k=0;

					if($i>=$j)
						$buffer2 .= $buffer;
					else
						$buffer2 .= $buffer." \page ";

					$buffer=$c;
				}

				$k++;
				$i++;
			}

			$buffer2 .= " \page ".$buffer;

			$buffers = $awal.$buffer2.$akhir;

			$berkas_arsip = $path_arsip."undangan.rtf";
			$handle = fopen($berkas_arsip,'w+');
			fwrite($handle,$buffers);
			fclose($handle);
			$_SESSION['success']=8;
			header("location:".base_url($berkas_arsip));
		}

	}
}