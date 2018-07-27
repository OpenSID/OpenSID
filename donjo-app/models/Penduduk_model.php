<?php class Penduduk_model extends CI_Model{

	function __construct(){
		parent::__construct();

		$this->load->model('keluarga_model');
		$this->ktp_el = array_flip(unserialize(KTP_EL));
		$this->status_rekam = array_flip(unserialize(STATUS_REKAM));
		$this->tempat_dilahirkan = array_flip(unserialize(TEMPAT_DILAHIRKAN));
		$this->jenis_kelahiran = array_flip(unserialize(JENIS_KELAHIRAN));
		$this->penolong_kelahiran = array_flip(unserialize(PENOLONG_KELAHIRAN));
	}

	function autocomplete()
	{
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


	function search_sql()
	{
		if(isset($_SESSION['cari'])){
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR u.nik LIKE '$kw')";
			return $search_sql;
		}
	}

	function keluarga_sql()
	{
		if($_SESSION['layer_keluarga'] == 1){
			$sql = " AND u.kk_level = 1";
			return $sql;
		}
	}

	function sex_sql()
	{
		if(isset($_SESSION['sex'])){
			$kf = $_SESSION['sex'];
			$sex_sql= " AND u.sex = $kf";
		return $sex_sql;
		}
	}

	function dusun_sql()
	{
		if(isset($_SESSION['dusun'])){
			$kf = $_SESSION['dusun'];
			$dusun_sql= " AND a.dusun = '$kf'";
		return $dusun_sql;
		}
	}

	function rw_sql()
	{
		if(isset($_SESSION['rw'])){
			$kf = $_SESSION['rw'];
			$rw_sql= " AND a.rw = '$kf'";
		return $rw_sql;
		}
	}

	function rt_sql()
	{
		if(isset($_SESSION['rt'])){
			$kf = $_SESSION['rt'];
			$rt_sql= " AND a.rt = '$kf'";
		return $rt_sql;
		}
	}

	function pendidikan_kk_sql()
	{
		if(isset($_SESSION['pendidikan_kk_id'])){
			$kf = $_SESSION['pendidikan_kk_id'];
			$pendidikan_sql= " AND u.pendidikan_kk_id = $kf";
		return $pendidikan_sql;
		}
	}

	function get_sql_kolom_kode($kode_session,$kode_kolom)
	{
		if(isset($_SESSION[$kode_session])){
			$kf = $_SESSION[$kode_session];
			if ($kf == BELUM_MENGISI)
				$sql = " AND (u.".$kode_kolom." IS NULL OR u.".$kode_kolom." = '')";
			else
				$sql= " AND u.".$kode_kolom." = $kf";
		return $sql;
		}
	}

	function akta_kelahiran_sql()
	{
		if(isset($_SESSION['akta_kelahiran'])){
			$kf = $_SESSION['akta_kelahiran'];
			if ($kf == BELUM_MENGISI)
				$sql = " AND (u.akta_lahir IS NULL OR u.akta_lahir = '')";
			else
				$sql= " AND u.akta_lahir<>''";
		return $sql;
		}
	}

	function cacatx_sql()
	{
		if(isset($_SESSION['cacatx'])){
			$kf = $_SESSION['cacatx'];
			$cacatx_sql= " AND u.cacat_id <> $kf AND u.cacat_id is not null and u.cacat_id<>''";
		return $cacatx_sql;
		}
	}

	function menahunx_sql()
	{
		if(isset($_SESSION['menahunx'])){
			$kf = $_SESSION['menahunx'];
			$menahunx_sql= " AND u.sakit_menahun_id <> $kf and u.sakit_menahun_id is not null and u.sakit_menahun_id<>'0' ";
		return $menahunx_sql;
		}
	}

	function hamil_sql()
	{
		if(isset($_SESSION['hamil'])){
			$kf = $_SESSION['hamil'];
			$hamil_sql= " AND u.hamil = $kf";
		return $hamil_sql;
		}
	}

	function umur_max_sql()
	{
		if(isset($_SESSION['umur_max'])){
			$kf = $_SESSION['umur_max'];
			$umur_max_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= $kf ";
		return $umur_max_sql;
		}
	}

	function umur_min_sql()
	{
		if(isset($_SESSION['umur_min'])){
			$kf = $_SESSION['umur_min'];
			$umur_min_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= $kf ";
		return $umur_min_sql;
		}
	}

	function umur_sql()
	{
		if(isset($_SESSION['umurx'])){
			$kf = $_SESSION['umurx'];
			if ($kf != BELUM_MENGISI)
				$umur_sql= " AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) >= (SELECT dari FROM tweb_penduduk_umur WHERE id=$kf ) AND (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) <= (SELECT sampai FROM tweb_penduduk_umur WHERE id=$kf ) ";
			else $umur_sql = " AND u.tanggallahir IS NULL OR u.tanggallahir = '' ";
		return $umur_sql;
		}
	}

	function filter_sql()
	{
		if(isset($_SESSION['filter'])){
			$kf = $_SESSION['filter'];
			$filter_sql= " AND u.status = $kf";
		return $filter_sql;
		}
	}

	function status_dasar_sql()
	{
		if(isset($_SESSION['status_dasar'])){
			$kf = $_SESSION['status_dasar'];
				$status_dasar= " AND u.status_dasar = $kf";
		return $status_dasar;
		}
	}

	function status_ktp_sql()
	{
		// Filter berdasarkan data eKTP
		$wajib_ktp_sql = " AND ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) ";
		if(isset($_SESSION['status_ktp'])){
			$kf = $_SESSION['status_ktp'];
			if ($kf == BELUM_MENGISI)
				$sql = $wajib_ktp_sql." AND (u.status_rekam IS NULL OR u.status_rekam = '')";
			else {
				if($kf <> 0){
					$status_ktp = $this->db->where('id',$kf)->get('tweb_status_ktp')->row_array();
					$status_rekam = $status_ktp['status_rekam'];
					$sql = $wajib_ktp_sql." AND u.status_rekam = $status_rekam";
				} else {
					// TOTAL hanya yang wajib KTP
					$sql = $wajib_ktp_sql;
				}
			}
		return $sql;
		}
	}

	function get_alamat_wilayah($id)
	{
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

		$alamat_wilayah= trim("$data[alamat] RT $data[rt] / RW $data[rw] ".ikut_case($data['dusun'],$this->setting->sebutan_dusun)." $data[dusun]");
		return $alamat_wilayah;
	}

	function paging($p=1,$o=0)
	{

		$list_data_sql = $this->list_data_sql();
		$sql = "SELECT COUNT(u.id) AS id ".$list_data_sql;
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

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_data_sql()
	{
		$sql = "
		FROM tweb_penduduk u
		LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
		LEFT JOIN tweb_rtm b ON u.id_rtm = b.id
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
		$sql .= $this->status_dasar_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();

		$kolom_kode = array(
			array('cacat','cacat_id'),
			array('cara_kb_id','cara_kb_id'),
			array('menahun','sakit_menahun_id'),
			array('status','status_kawin'),
			array('pendidikan_kk_id','pendidikan_kk_id'),
			array('pendidikan_sedang_id','pendidikan_sedang_id'),
			array('status_penduduk','status'),
			array('pekerjaan_id','pekerjaan_id'),
			array('agama','agama_id'),
			array('warganegara','warganegara_id'),
			array('golongan_darah','golongan_darah_id')
		);
		foreach ($kolom_kode as $kolom){
			$sql .= $this->get_sql_kolom_kode($kolom[0],$kolom[1]);
		}

		$sql .= $this->cacatx_sql();
		$sql .= $this->akta_kelahiran_sql();
		$sql .= $this->status_ktp_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->hamil_sql();
		return $sql;
	}

	function list_data($o=0,$offset=0,$limit=500)
	{

		$select_sql = "SELECT DISTINCT u.id,u.nik,u.tanggallahir,u.tempatlahir,u.status,u.status_dasar,u.id_kk,u.nama,u.nama_ayah,u.nama_ibu,a.dusun,a.rw,a.rt,d.alamat,d.no_kk AS no_kk,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(log.tgl_peristiwa)-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur_pada_peristiwa,
			x.nama AS sex,sd.nama AS pendidikan_sedang,n.nama AS pendidikan,p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama,m.nama AS gol_darah,hub.nama AS hubungan,b.no_kk AS no_rtm,b.id AS id_rtm
				";
		//Main Query
		$list_data_sql = $this->list_data_sql();
		$sql = $select_sql." ".$list_data_sql;

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

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){

			// Untuk penduduk mati atau hilang, gunakan umur pada tgl peristiwa
			if (in_array($data[$i]['status_dasar'], array('2','4')))
				$data[$i]['umur'] = $data[$i]['umur_pada_peristiwa'];
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

	function list_data_map()
	{
		//Main Query
		$sql = "SELECT u.id, u.nik, u.nama, map.lat, map.lng, a.dusun, a.rw, a.rt, u.foto, d.no_kk AS no_kk,
					(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0
					FROM tweb_penduduk
					WHERE id = u.id) AS umur,
				x.nama AS sex, sd.nama AS pendidikan_sedang, n.nama AS pendidikan, p.nama AS pekerjaan, k.nama AS kawin, g.nama AS agama, m.nama AS gol_darah, hub.nama AS hubungan,
				@alamat:=trim(concat_ws(' ',
					case
						when a.rt != '-' then concat('RT-', a.rt)
						else ''
					end,
					case
						when a.rw != '-' then concat('RW-', a.rw)
						else ''
					end,
					case
						when a.dusun != '-' then concat('Dusun ', a.dusun)
						else ''
					end
				)),
				case
					when length(@alamat) > 0 then @alamat
					else 'Alamat penduduk belum valid'
				end as alamat
				FROM tweb_penduduk u
				LEFT JOIN tweb_wil_clusterdesa a ON u.id_cluster = a.id
				LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
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
				LEFT JOIN tweb_penduduk_map map ON u.id = map.id WHERE 1 ";

		$sql .= $this->keluarga_sql();
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= $this->pendidikan_kk_sql();

		$kolom_kode = array(
			array('cacat','cacat_id'),
			array('cara_kb_id','cara_kb_id'),
			array('menahun','sakit_menahun_id'),
			array('status','status_kawin'),
			array('pendidikan_kk_id','pendidikan_kk_id'),
			array('pendidikan_sedang_id','pendidikan_sedang_id'),
			array('status_penduduk','status'),
			array('pekerjaan_id','pekerjaan_id'),
			array('agama','agama_id'),
			array('warganegara','warganegara_id'),
			array('golongan_darah','golongan_darah_id')
		);
		foreach ($kolom_kode as $kolom){
			$sql .= $this->get_sql_kolom_kode($kolom[0],$kolom[1]);
		}

		$sql .= $this->cacatx_sql();
		$sql .= $this->akta_kelahiran_sql();
		$sql .= $this->menahunx_sql();
		$sql .= $this->umur_min_sql();
		$sql .= $this->umur_max_sql();
		$sql .= $this->umur_sql();
		$sql .= $this->hamil_sql();



		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function validasi_data_penduduk(&$data)
	{
		if ($data['tanggallahir'] == '') $data['tanggallahir'] = NULL;
		if ($data['tanggallahir']) $data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		if ($data['tanggal_akhir_paspor'] == '') $data['tanggal_akhir_paspor'] = NULL;
		if ($data['tanggal_akhir_paspor']) $data['tanggal_akhir_paspor'] = tgl_indo_in($data['tanggal_akhir_paspor']);
		if ($data['tanggalperkawinan'] == '') $data['tanggalperkawinan'] = NULL;
		if ($data['tanggalperkawinan']) $data['tanggalperkawinan'] = tgl_indo_in($data['tanggalperkawinan']);
		if ($data['tanggalperceraian'] == '') $data['tanggalperceraian'] = NULL;
		if ($data['tanggalperceraian']) $data['tanggalperceraian'] = tgl_indo_in($data['tanggalperceraian']);
		// Hanya status 'kawin' yang boleh jadi akseptor kb
		if ($data['status_kawin'] != 2) $data['cara_kb_id'] = NULL;
		// Status hamil tidak berlaku bagi laki-laki
		if ($data['sex'] == 1) $data['hamil'] = 0;

		$valid = array();
		if (preg_match("/[^a-zA-Z '\.,]/", $data['nama'])) {
			array_push($valid, "Nama hanya boleh berisi karakter alpha, spasi, titik, koma dan tanda petik");
		}
		if (isset($data['nik'])) {
			if (!ctype_digit($data['nik']))
				array_push($valid, "NIK hanya berisi angka");
			if (strlen($data['nik']) != 16 AND $data['nik'] != '0')
				array_push($valid, "NIK panjangnya harus 16 atau 0");
			if ($this->db->select('nik')->from('tweb_penduduk')->where(array('nik'=>$data['nik']))->limit(1)->get()->row()->nik)
				array_push($valid, "NIK {$data['nik']} sudah digunakan");
		}
		if (!empty($valid))
			$_SESSION['validation_error'] = true;
		return $valid;
	}

	function insert()
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		$_SESSION['error_msg'] = '';

		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = $data['old_foto'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/jpg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file,$old_foto,$tipe_file);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);
		unset($data['nik_lama']);

		$data['id_cluster'] = $data['rt'];
		UNSET($data['dusun']);
		UNSET($data['rw']);
		UNSET($data['rt']);

		$error_validasi = $this->validasi_data_penduduk($data);
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			// Tampilkan tanda kutip dalam nama
			$_POST['nama'] =  str_replace ( "\"", "&quot;", $_POST['nama'] ) ;
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		if($data['tanggallahir'] == '') unset($data['tanggallahir']);
		if($data['tanggalperkawinan'] == '') unset($data['tanggalperkawinan']);
		if($data['tanggalperceraian'] == '') unset($data['tanggalperceraian']);
		$outp = $this->db->insert('tweb_penduduk',$data);
		$idku = $this->db->insert_id();

		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if($_POST['status']=='3'){
			// Pendatang
			$log['id_detail']="8";
		}else{
			if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
				// Lahir
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
		$this->tulis_log_penduduk_data($log);

		$log1['id_pend'] = $idku;
		$log1['id_cluster'] = 1;
		$log1['tanggal'] = date("m-d-y");

		$outp = $this->db->insert('log_perubahan_penduduk',$log1);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;

		return $idku;
	}

	function update($id=0)
	{
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
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = $data['old_foto'];
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file,$old_foto,$tipe_file);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);

		// Jangan update nik apabila tidak berubah
		if ($data['nik_lama'] == $data['nik']) {
			unset($data['nik']);
		}
		unset($data['nik_lama']);

		$error_validasi = $this->validasi_data_penduduk($data);
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			// Tampilkan tanda kutip dalam nama
			$_POST['nama'] =  str_replace ( "\"", "&quot;", $_POST['nama'] ) ;
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$data);


		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_position($id=0)
	{
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

	function get_penduduk_map($id=0)
	{
		$sql   = "SELECT m.*,p.nama FROM tweb_penduduk_map m LEFT JOIN tweb_penduduk p ON m.id = p.id WHERE m.id = ? ";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}


	function get_log_status_dasar($id)
	{
		// selalu ngambil yang terakhir
		$sql = "SELECT date_format(tgl_peristiwa, '%d-%m-%Y') as tgl_peristiwa, id_detail, catatan from log_penduduk where id_pend = ? order by id desc limit 1";
		$query = $this->db->query($sql,$id);
		return $query->row_array();
	}

	function update_status_dasar($id=0)
	{
		$data['status_dasar'] = $_POST['status_dasar'];
		$this->db->where('id',$id);
		$this->db->update('tweb_penduduk',$data);
		$penduduk = $this->get_penduduk($id);

		// Tulis log_keluarga jika penduduk adalah kepala keluarga
		if ($penduduk['kk_level'] == 1) {
			$id_peristiwa = $penduduk['status_dasar_id'] + 2; // lihat kode di keluarga_model
			$this->keluarga_model->log_keluarga($penduduk['id_kk'], $penduduk['id'], $id_peristiwa);
		}

		// Tulis log_penduduk
		$log['id_pend'] = $id;
		$log['no_kk'] = $penduduk['no_kk'];
		$log['nama_kk'] = $penduduk['kepala_kk'];
		$log['tgl_peristiwa'] = rev_tgl($_POST['tgl_peristiwa']);
		$log['id_detail'] = $data['status_dasar'];
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$log['catatan'] = $_POST['catatan'];

		$this->tulis_log_penduduk_data($log);
	}

	function delete($id='')
	{
		$sql  = "DELETE FROM tweb_penduduk WHERE id=?";
		$outp = $this->db->query($sql,array($id));

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all()
	{
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

	function adv_search_proses()
	{
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

	function get_id_kk($id=0)
	{
		$sql = "SELECT u.id_kk
				FROM tweb_penduduk u
				WHERE id = ? limit 1";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data['id_kk'];
	}

	function get_penduduk($id=0)
	{
		$sql   = "SELECT u.sex as id_sex,u.*,a.dusun,a.rw,a.rt,t.nama AS status,o.nama AS pendidikan_sedang, m.nama as golongan_darah, h.nama as hubungan,
			b.nama AS pendidikan_kk,d.no_kk AS no_kk,d.alamat,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0  FROM tweb_penduduk WHERE id = u.id)
			 AS umur,x.nama AS sex,w.nama AS warganegara,
			 p.nama AS pekerjaan,k.nama AS kawin,g.nama AS agama, c.nama as cacat,
			 kb.nama as cara_kb, sm.nama as sakit_menahun,
			 sd.nama as status_dasar, u.status_dasar as status_dasar_id,
			(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = d.nik_kepala)) AS kepala_kk,
			log.no_kk as log_no_kk
		 FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
			LEFT JOIN tweb_wil_clusterdesa a ON d.id_cluster = a.id
			LEFT JOIN tweb_penduduk_pendidikan o ON u.pendidikan_sedang_id = o.id
			LEFT JOIN tweb_penduduk_pendidikan_kk b ON u.pendidikan_kk_id = b.id
			LEFT JOIN tweb_penduduk_warganegara w ON u.warganegara_id = w.id
			LEFT JOIN tweb_penduduk_status t ON u.status = t.id
			LEFT JOIN tweb_penduduk_pekerjaan p ON u.pekerjaan_id = p.id
			LEFT JOIN tweb_penduduk_kawin k ON u.status_kawin = k.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
			LEFT JOIN tweb_golongan_darah m ON u.golongan_darah_id = m.id
			LEFT JOIN tweb_penduduk_hubungan h on u.kk_level = h.id
			LEFT JOIN tweb_cacat c ON u.cacat_id = c.id
			LEFT JOIN tweb_sakit_menahun sm ON u.sakit_menahun_id = sm.id
			LEFT JOIN tweb_cara_kb kb ON u.cara_kb_id = kb.id
			LEFT JOIN tweb_status_dasar sd ON u.status_dasar = sd.id
			LEFT JOIN log_penduduk log ON u.id = log.id_pend
			WHERE u.id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		$data['tanggallahir'] = tgl_indo_out($data['tanggallahir']);
		$data['tanggal_akhir_paspor'] = tgl_indo_out($data['tanggal_akhir_paspor']);
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
		// Data ektp: cari tulisan untuk kode
		$wajib_ktp = $this->is_wajib_ktp($data);
		if ($wajib_ktp !== null)
			$data['wajib_ktp'] = $wajib_ktp ? 'WAJIB' : 'BELUM';
		$data['ktp_el'] = strtoupper($this->ktp_el[$data['ktp_el']]);
		$data['status_rekam'] = strtoupper($this->status_rekam[$data['status_rekam']]);
		$data['tempat_dilahirkan_nama'] = strtoupper($this->tempat_dilahirkan[$data['tempat_dilahirkan']]);
		$data['jenis_kelahiran_nama'] = strtoupper($this->jenis_kelahiran[$data['jenis_kelahiran']]);
		$data['penolong_kelahiran_nama'] = strtoupper($this->penolong_kelahiran[$data['penolong_kelahiran']]);
		// Tampilkan tanda kutip dalam nama
		$data['nama'] =  str_replace ( "\"", "&quot;", $data['nama'] ) ;

		return $data;
	}

	function get_penduduk_by_nik($nik=0)
	{
		$sql   = "SELECT u.id AS id, u.nama AS nama,x.nama AS sex,u.id_kk AS id_kk,
		u.tempatlahir AS tempatlahir,u.tanggallahir AS tanggallahir, u.kk_level,
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


	function list_dusun()
	{
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_wil()
	{
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE zoom > '0'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_rw($dusun='')
	{
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND dusun = ? AND rw <> '0'";
		$query = $this->db->query($sql,$dusun);
		$data=$query->result_array();
		return $data;
	}

	function list_rw_all()
	{
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw <> '0'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_rt($dusun='',$rw='')
	{
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rw = ? AND dusun = ? AND rt <> '0'";
		$query = $this->db->query($sql,array($rw,$dusun));
		$data=$query->result_array();
		return $data;
	}

	function list_rt_all()
	{
		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE rt <> '0' AND rw <> '-'";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_agama()
	{
		$sql   = "SELECT * FROM tweb_penduduk_agama WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	/**
		$status_kawin_kk adalah status kawin dari kepala keluarga.
		Digunakan pada saat menambah anggota keluarga, supaya yang ditampilkan hanya
		hubungan yang berlaku
	**/
	function list_hubungan($status_kawin_kk=NULL)
	{
		if (empty($status_kawin_kk)) {
			$where = "1";
		} else {
			/***
				Untuk Kepala Keluarga yang belum kawin, hubungan berikut tidak berlaku:
					menantu, cucu, mertua, suami, istri
				Untuk semua Kepala Keluarga, hubungan 'kepala keluarga' tidak berlaku
			***/

			$where = ($status_kawin_kk == 1) ? "id NOT IN ('1','2','3','4','5','6','8') " : "id <> 1";
		}
		$sql   = "SELECT * FROM tweb_penduduk_hubungan WHERE $where";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan()
	{
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan_telah()
	{
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,6)<> 'SEDANG' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan_sedang()
	{
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,5)<> 'TAMAT' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_pendidikan_kk()
	{
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	// Untuk pekerjaan, ubah bentuk seperti 'Belum/tidak Bekerja' menjadi 'Belum/Tidak Bekerja'
	private function ubah_ke_huruf_besar($matches)
	{
		$matches[0][1] = strtoupper($matches[0][1]);
		return $matches[0];
	}

	public function normalkanPekerjaan($nama)
	{
		$nama_pekerjaan = array(
			"(pns)" => "(PNS)",
			"(tni)" => "(TNI)",
			"(polri)" => "(POLRI)",
			" Ri " => " RI ",
			"Dpr-ri" => "DPR-RI",
			"Dpd" => "DPD",
			"Bpk" => "BPK",
			"Dprd" => "DPRD"
		);
		$nama = ucwords(strtolower($nama));
		foreach ($nama_pekerjaan as $key => $value) {
			$nama = str_replace($key, $value, $nama);
		}
		if (strpos($nama,'/')) {
			$nama = $nama;
			$nama = preg_replace_callback('/\/\S{1}/', "Penduduk_Model::ubah_ke_huruf_besar", $nama);
		}
		return $nama;
	}

	function list_pekerjaan($case='')
	{
		$sql   = "SELECT * FROM tweb_penduduk_pekerjaan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		if ($case == 'ucwords') {
			for ($i=0; $i<count($data); $i++) {
				$data[$i]['nama'] = $this->normalkanPekerjaan($data[$i]['nama']);
			}
		}
		return $data;
	}

	function list_warganegara()
	{
		$sql   = "SELECT * FROM tweb_penduduk_warganegara WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_status_kawin()
	{
		$sql   = "SELECT * FROM tweb_penduduk_kawin WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_golongan_darah()
	{
		$sql   = "SELECT * FROM tweb_golongan_darah WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_sex()
	{
		$data = $this->db->select('*')->get("tweb_penduduk_sex")->result_array();
		return $data;
	}

	function list_cacat()
	{
		$sql   = "SELECT * FROM tweb_cacat WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_cara_kb($sex='')
	{
		if ($sex != 1 AND $sex != 2) {
			$sql   = "SELECT * FROM tweb_cara_kb WHERE 1";
		} else {
			$sql   = "SELECT * FROM tweb_cara_kb WHERE sex = ? OR sex = 3";
		}
		$query = $this->db->query($sql, $sex);
		$data=$query->result_array();
		return $data;
	}

	function get_desa()
	{
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function is_anggota_keluarga($id)
	{
		$this->db->select('id_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		if ($penduduk['id_kk'] > 0) return true;
		else return false;
	}

	// Pindah untuk penduduk lepas (yang bukan anggota keluarga)
	function pindah_proses($id=0,$id_cluster='',$alamat)
	{
		$this->db->where('id',$id);
		if (!empty($alamat)) $data['alamat_sekarang'] = $alamat;
		if ($id_cluster AND $id_cluster != '') $data['id_cluster'] = $id_cluster;
		if (!empty($data))
		{
			$outp = $this->db->update('tweb_penduduk', $data);
			$this->tulis_log_penduduk($id, '6', date('m'), date('Y'));
		}
		else
			$outp = true;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function tulis_log_penduduk_data($log)
	{
    $update_str = '';
    foreach($log as $key=>$item) {
        $update_str .= $key.'=VALUES('.$key.'),';
    }
    $update_str = rtrim($update_str, ',');

		$sql = $this->db->insert_string('log_penduduk',$log) . ' ON DUPLICATE KEY UPDATE ' . $update_str;
		$this->db->query($sql);
	}

	function tulis_log_penduduk($id_pend, $id_detail, $bulan, $tahun)
	{
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


	function get_judul_statistik($tipe=0,$nomor=1,$sex=0)
	{
		if ($nomor == BELUM_MENGISI)
			$judul = array("nama" => "BELUM MENGISI");
		else {
			switch($tipe){
				case 0: $sql   = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE id=?";break;
				case 1: $sql   = "SELECT * FROM tweb_penduduk_pekerjaan WHERE id=?";break;
				case 2: $sql   = "SELECT * FROM tweb_penduduk_kawin WHERE id=?";break;
				case 3: $sql   = "SELECT * FROM tweb_penduduk_agama WHERE id=?";break;
				case 4: $sql   = "SELECT * FROM tweb_penduduk_sex WHERE id=?";break;
				case 5: $sql   = "SELECT * FROM tweb_penduduk_warganegara WHERE id=?";break;
				case 6: $sql   = "SELECT * FROM tweb_penduduk_status WHERE id=?";break;
				case 7: $sql   = "SELECT * FROM tweb_golongan_darah WHERE id=?";break;
				case 9: $sql   = "SELECT * FROM tweb_cacat WHERE id=?";break;
				case 10: $sql   = "SELECT * FROM tweb_sakit_menahun WHERE id=?";break;
				case 13: $sql   = "SELECT * FROM tweb_penduduk_umur WHERE id=?";break;
				case 14: $sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE id=?";break;
				case 16: $sql   = "SELECT * FROM tweb_cara_kb WHERE id=?";break;
				case 17: $sql   = "SELECT 'ADA AKTA KELAHIRAN' AS nama"; break;
				case 18: $sql   = "SELECT * FROM tweb_status_ktp WHERE id=?"; break;
			}
			$query = $this->db->query($sql,$nomor);
			$judul = $query->row_array();
		}
		if ($sex == 1) $judul['nama'] .= " - LAKI-LAKI";
		elseif ($sex == 2) $judul['nama'] .= " - PEREMPUAN";
		return $judul;
	}

	// Untuk form surat
	function list_penduduk_status_dasar($status_dasar=1)
	{
        $sql = "SELECT u.id,nik,nama, CONCAT('Alamat : RT-', w.rt, ', RW-', w.rw, ' ',w.dusun) AS alamat,w.rt, w.rw, w.dusun,u.sex FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id WHERE u.status_dasar = ?";
        $data = $this->db->query($sql, array($status_dasar))->result_array();
		return $data;
	}

	function get_cluster($id_cluster=0)
	{

		$sql   = "SELECT * FROM tweb_wil_clusterdesa WHERE id=$id_cluster ";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function randomap()
	{

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

	function generateRandomString($length = 5)
	{
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function generateRandomString2($length = 1)
	{
		$characters = '5678';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	function list_dokumen($id="")
	{
		$sql = "SELECT * FROM dokumen WHERE id_pend = ? ";
		$query = $this->db->query($sql,$id);
		$data=null;
		if($query)
			$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function list_kelompok($id="")
	{
		$sql = "SELECT k.nama,m.kelompok AS kategori FROM kelompok_anggota a LEFT JOIN kelompok k ON a.id_kelompok = k.id LEFT JOIN kelompok_master m ON k.id_master = m.id WHERE a.id_penduduk = ? ";
		$query = $this->db->query($sql,$id);
		$data=null;
		if($query)
			$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$i++;
		}
		return $data;
	}

	function get_dokumen($id=0)
	{
		$sql = "SELECT * FROM dokumen WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}

	function is_wajib_ktp($data)
	{
		// Wajib KTP = sudah umur 17 atau pernah kawin
		$umur = umur($data['tanggallahir']);
		if ($umur === null) return null;
		$wajib_ktp = (($umur > 16) OR (!empty($data['status_kawin']) AND $data['status_kawin'] != 1));
		return $wajib_ktp;
	}

}
