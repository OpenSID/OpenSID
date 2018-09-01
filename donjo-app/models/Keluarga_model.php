<?php class Keluarga_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model('program_bantuan_model');
	}

	function autocomplete(){
		$sql   = "SELECT t.nama FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE 1  ";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ',"'.$data[$i]['nama'].'"';
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}

	function sex_sql(){
		if(isset($_SESSION['sex'])){
			$kf = $_SESSION['sex'];
			$sex_sql= " AND t.sex = '$kf'";
		return $sex_sql;
		}
	}

	/*
		1 - tampilkan keluarga di mana KK mempunyai status dasar 'hidup'
		2 - tampilkan keluarga di mana KK mempunyai status dasar 'hilang/pindah/mati'
	*/
	function status_dasar_sql(){
		if(isset($_SESSION['status_dasar'])){
			$kf = $_SESSION['status_dasar'];
			if ($kf == '1')	$status_dasar_sql= " AND t.status_dasar = 1";
			else $status_dasar_sql= " AND t.status_dasar <> 1";
		return $status_dasar_sql;
		}
	}

	function dusun_sql(){
		if(isset($_SESSION['dusun'])){
			$kf = $_SESSION['dusun'];
			$dusun_sql= " AND c.dusun = '$kf'";
		return $dusun_sql;
		}
	}

	function rw_sql(){
		if(isset($_SESSION['rw'])){
			$kf = $_SESSION['rw'];
			$rw_sql= " AND c.rw = '$kf'";
		return $rw_sql;
		}
	}

	function rt_sql(){
		if(isset($_SESSION['rt'])){
			$kf = $_SESSION['rt'];
			$rt_sql= " AND c.rt = '$kf'";
		return $rt_sql;
		}
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (t.nama LIKE '$kw' OR u.no_kk LIKE '$kw')";
			return $search_sql;
		}
	}

	function jenis_sql(){
		if(isset($_SESSION['jenis'])){
			$kh = $_SESSION['jenis'];
			$jenis_sql= " AND jenis = $kh";
		return $jenis_sql;
		}
	}

	function filter_sql() {
		$sql  = $this->search_sql() .
						$this->status_dasar_sql() .
						$this->dusun_sql() .
					 	$this->rw_sql() .
		 				$this->rt_sql() .
		 				$this->sex_sql();
		return $sql;
	}

	function kelas_sql(){
		if(isset($_SESSION['kelas'])){
			$kh = $_SESSION['kelas'];
			if ($kh == BELUM_MENGISI)
				$sql = " AND (u.kelas_sosial IS NULL OR u.kelas_sosial = '')";
			else
				$sql= " AND kelas_sosial= $kh";
		return $sql;
		}
	}

	function bos_sql(){
		if(isset($_SESSION['id_bos'])){
			$kh = $_SESSION['id_bos'];
			$bos_sql= " AND id_bos= $kh";
		return $bos_sql;
		}
	}

	function paging($p=1,$o=0){
		$sql    = "SELECT COUNT(u.id) AS id ".$this->_list_data_sql();
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

	private function _list_data_sql() {
		$sql = "FROM tweb_keluarga u
			LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id
			LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id
			WHERE 1 ";

		$sql 	.= $this->filter_sql();
		$sql 	.= $this->kelas_sql();
		$sql 	.= $this->bos_sql();
		//$sql     .= $this->rt_sql();
		return $sql;
	}

	function list_data($o=0,$offset=0,$limit=500){

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.no_kk'; break;
			case 2: $order_sql = ' ORDER BY u.no_kk DESC'; break;
			case 3: $order_sql = ' ORDER BY kepala_kk'; break;
			case 4: $order_sql = ' ORDER BY kepala_kk DESC'; break;
			case 5: $order_sql = ' ORDER BY g.nama'; break;
			case 6: $order_sql = ' ORDER BY g.nama DESC'; break;
			default:$order_sql = ' ORDER BY u.tgl_daftar DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql   = "SELECT u.*,t.nama AS kepala_kk,t.nik,t.sex,t.status_dasar,(SELECT COUNT(id) FROM tweb_penduduk WHERE id_kk = u.id AND status_dasar = 1) AS jumlah_anggota,c.dusun,c.rw,c.rt ".$this->_list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			if($data[$i]['jumlah_anggota']==0)
				$data[$i]['jumlah_anggota'] = "-";

			if($data[$i]['sex']==1)
				$data[$i]['sex'] = "LAKI-LAKI";
			else
				$data[$i]['sex'] = "PEREMPUAN";
			$i++;
			$j++;
		}
		return $data;
	}


	// Tambah keluarga baru dari penduduk lepas (status tetap atau pendatang)
	function insert(){
		unset($_SESSION['error_msg']);
		$data = $_POST;

		$error_validasi = $this->_validasi_data_keluarga($data);
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$temp = $data['nik_kepala'];
		$outp = $this->db->insert('tweb_keluarga',penetration($data));

		$sql   = "SELECT id FROM tweb_keluarga WHERE nik_kepala=?";
		$query = $this->db->query($sql,$temp);
		$kk = $query->row_array();

		$default['id_kk'] = $kk['id'];
		$default['kk_level'] = 1;
		$default['status'] = 1; // statusnya menjadi tetap

		$this->db->where('id',$temp);
		$this->db->update('tweb_penduduk',$default);

		$this->load->model('penduduk_model');
		$this->penduduk_model->tulis_log_penduduk($temp, '9', date('m'), date('Y'));

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk',$log);

		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($kk['id'], $data['nik_kepala'], 1);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function validasi_data_penduduk($data){
		$valid = array();
		if (!ctype_digit($data['nik']))
			array_push($valid, "NIK hanya berisi angka");
		if (strlen($data['nik']) != 16 AND $data['nik'] != '0')
			array_push($valid, "NIK panjangnya harus 16 atau 0");
		if ($this->db->select('nik')->from('tweb_penduduk')->where(array('nik'=>$data['nik']))->limit(1)->get()->row()->nik)
			array_push($valid, "NIK {$data['nik']} sudah digunakan");
		if (!empty($valid))
			$_SESSION['validation_error'] = true;
		return $valid;
	}

	private function _validasi_data_keluarga($data){
		$valid = array();
		if (isset($data['no_kk'])) {
			if (!ctype_digit($data['no_kk']))
				array_push($valid, "Nomor KK hanya berisi angka");
			if (strlen($data['no_kk']) != 16 AND $data['no_kk'] != '0')
				array_push($valid, "Nomor KK panjangnya harus 16 atau 0");
			if ($this->db->select('no_kk')->from('tweb_keluarga')->where(array('no_kk'=>$data['no_kk']))->limit(1)->get()->row()->no_kk)
				array_push($valid, "Nomor KK {$data['no_kk']} sudah digunakan");
		}
		if (!empty($valid))
			$_SESSION['validation_error'] = true;
		return $valid;
	}

	function insert_new(){
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		unset($_SESSION['error_msg']);
		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = '';
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
		unset($data['nik_lama']);

		$error_validasi = array_merge($this->validasi_data_penduduk($data), $this->_validasi_data_keluarga($data));
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$data['id_cluster'] = $data['rt'];
		UNSET($data['dusun']);
		UNSET($data['rw']);
		UNSET($data['rt']);
		UNSET($data['no_kk']);
		UNSET($data['new']);

		// Simpan alamat keluarga sebelum menulis penduduk
		$data2['alamat'] = $data['alamat'];
		UNSET($data['alamat']);

		if ($data['tanggallahir'] == '') unset($data['tanggallahir']);
		else $data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);
		if ($data['tanggalperkawinan'] == '') unset($data['tanggalperkawinan']);
		else $data['tanggalperkawinan'] = tgl_indo_in($data['tanggalperkawinan']);
		if ($data['tanggalperceraian'] == '') unset($data['tanggalperceraian']);
		else $data['tanggalperceraian'] = tgl_indo_in($data['tanggalperceraian']);

		$outp = $this->db->insert('tweb_penduduk',penetration($data));
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;

		$sql   = "SELECT id FROM tweb_penduduk WHERE nik=?";
		$query = $this->db->query($sql,$data['nik']);
		$temp2  = $query->row_array();

		$data2['nik_kepala'] = $temp2['id'];
		$data2['no_kk'] = $_POST['no_kk'];
		$data2['id_cluster'] = $data['id_cluster'];

		$temp = $data2['nik_kepala'];
		$outp = $this->db->insert('tweb_keluarga',$data2);

		$sql   = "SELECT id FROM tweb_keluarga WHERE nik_kepala=?";
		$query = $this->db->query($sql,$temp);
		$kk = $query->row_array();

		$default['id_kk'] = $kk['id'];
		$default['kk_level'] = 1;

		$this->db->where('id',$temp);
		$this->db->update('tweb_penduduk',$default);

		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
			$x['id_detail']='1';
		}else{
			$x['id_detail']='5';
		}

		$x['id_pend']=$temp;
		$x['bulan']=$blnskrg;
		$x['tahun']=$thnskrg;
		$this->penduduk_model->tulis_log_penduduk_data($x);

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date("m-d-y");
		$outp = $this->db->insert('log_perubahan_penduduk',$log);

		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($kk['id'], $data2['nik_kepala'], 1);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	/* 	Hapus keluarga:
			(1) Untuk setiap anggota keluarga lakukan rem_anggota (pecah kk).
			(2) Hapus keluarga
			$id adalah id tweb_keluarga
	*/
	function delete($id=''){
		$nik_kepala = $this->db->select('nik_kepala')->where('id',$id)->get('tweb_keluarga')->row()->nik_kepala;
		$list_anggota = $this->db->select('id')->where('id_kk',$id)->get('tweb_penduduk')->result_array();
		foreach ($list_anggota as $anggota) {
			$this->rem_anggota($id,$anggota['id']);
		}
		$this->db->where('id',$id)->delete('tweb_keluarga');
		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($id, $nik_kepala, 2);
	}

	function delete_all(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$this->delete($id);
			}
		}
	}

	/* 	Untuk statistik perkembangan keluarga
	 		id_peristiwa:
	       1 - keluarga baru
	       2 - keluarga dihapus
	       3 - kepala keluarga status dasar kembali 'hidup' (salah mengisi di log_penduduk)
	       4 - kepala keluarga status dasar 'mati'
	       5 - kepala keluarga status dasar 'pindah'
	       6 - kepala keluarga status dasar 'hilang'
	*/
	function log_keluarga($id, $kk, $id_peristiwa) {
		$this->db->select('sex');
		$this->db->where('id', $kk);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		$log_keluarga['id_kk'] = $id;
		$log_keluarga['kk_sex'] = $penduduk['sex'];
		$log_keluarga['id_peristiwa'] = $id_peristiwa;
		$log_keluarga['tgl_peristiwa'] = date('Y-m-d H:i:s');
		$outp = $this->db->insert('log_keluarga',$log_keluarga);
	}

	function add_anggota($id=0){
		$data = $_POST;
		$temp['id_kk'] = $id;
		$temp['kk_level'] = $data['kk_level'];

		$this->db->where('id',$data['nik']);
		$outp = $this->db->update('tweb_penduduk',$temp);

		if($temp['kk_level']=="1"){
			$temp2['nik_kepala'] = $data['nik'];
			$this->db->where('id',$temp['id_kk']);
			$outp = $this->db->update('tweb_keluarga',$temp2);
		}

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_anggota($id=0){
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
			$this->db->update('tweb_keluarga',$nik);

		}

		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function rem_anggota($kk=0,$id=0){
		$pend     = $this->keluarga_model->get_anggota($id);
		$temp['no_kk_sebelumnya'] = $this->db->select('no_kk')->where('id',$kk)->get('tweb_keluarga')->row()->no_kk;
		$temp['id_kk'] = 0;
		$temp['kk_level'] = 0;
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk',$temp);
		if($pend['kk_level']=='1'){
			$temp2['nik_kepala']=0;
			$this->db->where('id',$pend['id_kk']);
			$outp = $this->db->update('tweb_keluarga',$temp2);
		}

		$this->load->model('penduduk_model');
		$this->penduduk_model->tulis_log_penduduk($id, '7', date('m'), date('Y'));
	}

	function rem_all_anggota($kk){
		$id_cb = $_POST['id_cb'];
		if(count($id_cb)){
			foreach($id_cb as $id){
				$this->rem_anggota($kk,$id);
			}
		}
	}

	function get_dusun($id=0){
		$sql   = "SELECT * FROM tweb_keluarga WHERE dusun_id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function get_keluarga($id=0){
		$sql   = "SELECT * FROM tweb_keluarga WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		$data['alamat_plus_dusun'] = $data['alamat'];
		return $data;
	}

	function get_data_cetak_kk($id=0){
		$kk['id_kk'] = $id;

		$kk['main'] = $this->keluarga_model->list_anggota($id);
		$kk['kepala_kk'] = $this->keluarga_model->get_kepala_kk($id);
		$kk['desa'] = $this->keluarga_model->get_desa();
		$data['all_kk'][] = $kk;
		return $data;
	}

	function get_data_cetak_kk_all(){
		$data = array();
		$id_cb = $_POST['id_cb'];
		if(count($id_cb)){
			foreach($id_cb as $id){
				$kk = $this->get_data_cetak_kk($id);
				$data['all_kk'][] = $kk['all_kk'][0]; //Kumpulkan semua kk
			}
		}
		return $data;
	}

	function get_anggota($id=0){
		$sql   = "SELECT * FROM tweb_penduduk WHERE id=?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		return $data;
	}

	function list_penduduk_lepas(){
		$sql   = "SELECT u.id,u.nik,u.nama,u.alamat_sekarang as alamat, w.rt, w.rw, w.dusun
			FROM tweb_penduduk u
			LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id
			WHERE (status = 1 OR status = 3) AND id_kk = 0";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	// $options['dengan_kk'] = false jika hanya perlu tanggungan keluarga tanpa kepala keluarga
	// $options['pilih'] untuk membatasi ke nik tertentu saja
	function list_anggota($id=0,$options=array('dengan_kk'=>true)){
		$sql   = "SELECT u.*,u.sex as sex_id,u.status_kawin as status_kawin_id,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,
				b.dusun,b.rw,b.rt,x.nama as sex,u.kk_level,a.nama as agama, d.nama as pendidikan,j.nama as pekerjaan,w.nama as status_kawin,f.nama as warganegara,g.nama as golongan_darah,h.nama AS hubungan, k.alamat
			FROM tweb_penduduk u
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id
			LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id
			LEFT JOIN tweb_wil_clusterdesa b ON u.id_cluster = b.id
			LEFT JOIN tweb_keluarga k ON u.id_kk = k.id
			WHERE status = 1 AND status_dasar = 1 AND id_kk = ?";
		if($options['dengan_kk'] !== NULL AND !$options['dengan_kk']) $sql .= " AND kk_level <> 1";
		if(!empty($options['pilih'])) $sql .= " AND u.nik IN (".$options['pilih'].")";
		$sql .= " ORDER BY kk_level, tanggallahir";
		$query = $this->db->query($sql,array($id));
		$data=$query->result_array();
		return $data;
	}

	// $id adalah id_kk : id dari tabel tweb_keluarga, kecuali
	// apabila $is_no_kk == true maka $id adalah no_kk
	function get_kepala_kk($id, $is_no_kk = false){
		$kolom_id = ($is_no_kk) ? "no_kk" : "id";
		$sql   = "SELECT nik,u.id,u.nama,u.status_kawin as status_kawin_id,tempatlahir,tanggallahir,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,a.nama as agama,d.nama as pendidikan,j.nama as pekerjaan, x.nama as sex,w.nama as status_kawin,h.nama as hubungan,f.nama as warganegara,warganegara_id,nama_ayah,nama_ibu,g.nama as golongan_darah ,c.rt as rt,c.rw as rw,c.dusun as dusun, (SELECT no_kk FROM tweb_keluarga WHERE $kolom_id = ?) AS no_kk, (SELECT alamat FROM tweb_keluarga WHERE $kolom_id = ?) AS alamat, (SELECT id FROM tweb_keluarga WHERE $kolom_id = ?) AS id_kk
			FROM tweb_penduduk u
			LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id
			LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id
			LEFT JOIN tweb_wil_clusterdesa c ON (SELECT id_cluster from tweb_keluarga where $kolom_id = ?) = c.id
			WHERE u.id = (SELECT nik_kepala FROM tweb_keluarga WHERE $kolom_id = ?) ";
		$query = $this->db->query($sql,array($id,$id,$id,$id,$id));
		$data = $query->row_array();
		if ($data['dusun'] != '') $data['alamat_plus_dusun'] = trim($data['alamat'].' '.ucwords($this->setting->sebutan_dusun).' '.$data['dusun']);
		elseif ($data['alamat']) $data['alamat_plus_dusun'] = $data['alamat'];
		$data['alamat_wilayah'] = $this->get_alamat_wilayah($data['id_kk']);
		return $data;
	}
	function get_kepala_a($id){

		$sql = "SELECT u.*,c.*, k.no_kk, k.alamat
			FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga k ON k.id = ?
			LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id WHERE u.id = (SELECT nik_kepala FROM tweb_keluarga WHERE id = ?) ";
		$query = $this->db->query($sql,array($id,$id));
		return $query->row_array();

	}

        function get_desa(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function list_hubungan(){
		$sql   = "SELECT *,nama as hubungan FROM tweb_penduduk_hubungan WHERE 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	// Tambah anggota keluarga
	function insert_a(){
		unset($_SESSION['validation_error']);
		$_SESSION['success'] = 1;
		unset($_SESSION['error_msg']);

		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		if (!empty($lokasi_file)){
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png"){
				unset($data['foto']);
			} else {
				UploadFoto($nama_file,"",$tipe_file);
				$data['foto'] = $nama_file;
			}
		}else{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);
		unset($data['nik_lama']);

		$satuan=$_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir= substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if(($blnlahir==$blnskrg)and($thnlahir==$thnskrg)){
			$id_detail='1';
		}else{
			$id_detail='5';
		}
		$data['tanggallahir'] = tgl_indo_in($data['tanggallahir']);

		$error_validasi = array_merge($this->validasi_data_penduduk($data), $this->_validasi_data_keluarga($data));
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return;
		}

		$outp = $this->db->insert('tweb_penduduk',$data);
		if (!$outp) $_SESSION = -1;

    $id_pend = $this->db->insert_id();
		$this->load->model('penduduk_model');
		$this->penduduk_model->tulis_log_penduduk($id_pend, $id_detail, $blnskrg, $thnskrg);
	}

	function get_nokk($id){
		$this->db->select('no_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_keluarga');
		$kk = $q->row_array();
		return $kk['no_kk'];
	}

	private function _cek_nokk($data){
		$nokk_lama = $this->get_nokk($data['id']);
		if ($data['no_kk'] == $nokk_lama) return true; // Tidak berubah

		$error_validasi = $this->_validasi_data_keluarga($data);
		if (!empty($error_validasi)){
			foreach ($error_validasi as $error) {
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return false;
		}
		return true;
	}

	function update_nokk($id=0){
		unset($_SESSION['error_msg']);
		$data = $_POST;

		if (!$this->_cek_nokk($data)) return;

		$id_program = $data['id_program'];
		unset($data['id_program']);
		// Update peserta program bantuan untuk kk ini
		$no_kk = $this->get_nokk($id);
		$program = $this->program_bantuan_model->list_program_keluarga($id);
		foreach ($program as $bantuan) {
			if (in_array($bantuan['id'],$id_program)){
				// Tambahkan ke program bantuan
				$this->program_bantuan_model->add_peserta(array('nik'=>$no_kk), $bantuan['id']);
			} else {
				// Hapus dari program bantuan
				$this->program_bantuan_model->hapus_peserta_program($no_kk, $bantuan['id']);
			}
		}
		if ($data['tgl_cetak_kk']) $data['tgl_cetak_kk'] = date("Y-m-d H:i:s",strtotime($data['tgl_cetak_kk']));
		else $data['tgl_cetak_kk'] = NULL;
		$this->db->where("id",$id);
		$outp=$this->db->update("tweb_keluarga",$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function list_sosial(){

		$dus = "";
		$rw = "";
		$rt = "";

		if(isset($_SESSION['dusun']))
			$dus = " AND c.dusun = '$_SESSION[dusun]'";

		if(isset($_SESSION['rw']))
			$rw = " AND c.rw = '$_SESSION[rw]'";

		if(isset($_SESSION['rt']))
			$rt = " AND c.rt = '$_SESSION[rt]'";

		$sql   = "SELECT s.*,(SELECT COUNT(u.id) AS id FROM tweb_keluarga u LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id WHERE  u.kelas_sosial = s.id $dus $rw $rt) as jumlah FROM ref_kelas_sosial s WHERE 1";


		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function pindah_proses($id=0,$id_cluster='',$alamat=''){
		$this->load->model('penduduk_model');
		// Ubah alamat keluarga
		$this->db->where('id',$id);
		if (!empty($alamat)) $data_kel['alamat'] = $alamat;
		if ($id_cluster AND $id_cluster != '') $data_kel['id_cluster'] = $id_cluster;
		if (!empty($data_kel)) $this->db->update('tweb_keluarga', $data_kel);
		// Ubah dusun/rw/rt untuk semua anggota keluarga
		if ($id_cluster AND $id_cluster != '') {
			$this->db->where('id_kk',$id);
			$data['id_cluster'] = $id_cluster;
			$outp = $this->db->update('tweb_penduduk',$data);

			// Tulis log pindah untuk setiap anggota keluarga
			$sql   = "SELECT id FROM tweb_penduduk WHERE id_kk=$id";
			$query = $this->db->query($sql);
			$data2= $query->result_array();
			foreach($data2 as $datanya){
				$this->penduduk_model->tulis_log_penduduk($datanya[id], '6', date('m'), date('Y'));
			}
		}

	}

	function get_alamat_wilayah($id_kk) {
		$sql = "SELECT a.dusun,a.rw,a.rt,k.alamat
				FROM tweb_keluarga k
				LEFT JOIN tweb_wil_clusterdesa a ON k.id_cluster = a.id
				WHERE k.id=?";
		$query = $this->db->query($sql,$id_kk);
		$data  = $query->row_array();
		if (!isset($data['alamat'])) $data['alamat'] = '';
		if (!isset($data['rt'])) $data['rt'] = '';
		if (!isset($data['rw'])) $data['rw'] = '';
		if (!isset($data['dusun'])) $data['dusun'] = '';

		$alamat_wilayah= trim("$data[alamat] RT $data[rt] / RW $data[rw] ".ikut_case($data['dusun'],$this->setting->sebutan_dusun)." $data[dusun]");
		return $alamat_wilayah;
	}


	function get_judul_statistik($tipe=0,$nomor=1,$sex=0){
		if ($nomor == BELUM_MENGISI)
			$judul = array("nama" => "BELUM MENGISI");
		else {
			switch($tipe){
				case 'kelas_sosial': $sql = "SELECT * FROM tweb_keluarga_sejahtera WHERE id=? "; break;
				case 21: $sql   = "SELECT * FROM klasifikasi_analisis_keluarga WHERE id=? and jenis='1'  ";break;
				case 24: $sql   = "SELECT * FROM ref_bos WHERE id=?";break;
			}
			$query = $this->db->query($sql,$nomor);
			$judul = $query->row_array();
		}
		if ($sex == 1) $judul['nama'] .= " - LAKI-LAKI";
		elseif ($sex == 2) $judul['nama'] .= " - PEREMPUAN";
		return $judul;
	}

	function get_data_unduh_kk($id){
		$data = array();
		$data['desa']     = $this->get_desa();
		$data['id_kk']    = $id;
		$data['main']     = $this->list_anggota($id);
		$data['kepala_kk']= $this->get_kepala_kk($id);
		return $data;
	}

	function unduh_kk($id=''){
		$id_cb = $_POST['id_cb'];
		if (empty($id) AND count($id_cb) == 1){
			// Aksi borongan dengan satu KK saja
			$id = $id_cb[0];
		}
		if (empty($id)){
			// Aksi borongan lebih dari satu KK
			$berkas_kk = array();
			if(count($id_cb)){
				foreach($id_cb as $id){
					$data = $this->get_data_unduh_kk($id);
					$berkas_kk[] = $this->buat_berkas_kk($data);
				}
			}
			# Masukkan semua berkas ke dalam zip
			$berkas_kk = $this->masukkan_zip($berkas_kk);
	    # Unduh berkas zip
	    header('Content-disposition: attachment; filename=berkas_kk_'.date("d-m-Y").'.zip');
	    header('Content-type: application/zip');
	    readfile($berkas_kk);
		} else {
			// Satu kk
			$data = $this->get_data_unduh_kk($id);
			$berkas_kk = $this->buat_berkas_kk($data);
			header("location:".base_url($berkas_kk));
		}
	}

	function buat_berkas_kk($data=''){
		$mypath="surat\\kk\\";

		$path = "".str_replace("\\","/",$mypath);
		$path_arsip = LOKASI_ARSIP;

		$file = $path."kk.rtf";
		if(!is_file($file)){
			return;
		}

		$nama ="";

		$handle = fopen($file,'r');
		$buffer = stream_get_contents($handle);
		$i=0;
		foreach($data['main'] AS $ranggota){
			$i++;
			$nama 			.= $ranggota['nama']."\line ";
			$no 			.= $i."\line ";
			$hubungan 		.= $ranggota['hubungan']."\line ";
			$nik 			.= $ranggota['nik']."\line ";
			$sex 			.= $ranggota['sex']."\line ";
			$tempatlahir 	.= $ranggota['tempatlahir']."\line ";
			$tanggallahir 	.= tgl_indo($ranggota['tanggallahir'])."\line ";
			$agama 			.= $ranggota['agama']."\line ";
			$pendidikan 	.= $ranggota['pendidikan']."\line ";
			$pekerjaan 		.= $ranggota['pekerjaan']."\line ";
			$status_kawin 	.= $ranggota['status_kawin']."\line ";
			$warganegara 	.= $ranggota['warganegara']."\line ";
			$dokumen_pasport.= $ranggota['dokumen_pasport']."\line ";
			$dokumen_kitas 	.= $ranggota['dokumen_kitas']."\line ";
			$nama_ayah 		.= $ranggota['nama_ayah']."\line ";
			$nama_ibu 		.= $ranggota['nama_ibu']."\line ";

			if($ranggota['golongan_darah']!="TIDAK TAHU")
				$golongan_darah .= $ranggota['golongan_darah']."\line ";
			else
				$golongan_darah .= "- \line ";
		}

		$buffer=str_replace("[no]","$no",$buffer);
		$buffer=str_replace("[nama]","\caps $nama",$buffer);
		$buffer=str_replace("[hubungan]","$hubungan",$buffer);
		$buffer=str_replace("[nik]","$nik",$buffer);
		$buffer=str_replace("[sex]","$sex",$buffer);
		$buffer=str_replace("[agama]","$agama",$buffer);
		$buffer=str_replace("[pendidikan]","$pendidikan",$buffer);
		$buffer=str_replace("[pekerjaan]","$pekerjaan",$buffer);
		$buffer=str_replace("[tempatlahir]","\caps $tempatlahir",$buffer);
		$buffer=str_replace("[tanggallahir]","\caps $tanggallahir",$buffer);
		$buffer=str_replace("[kawin]","$status_kawin",$buffer);
		$buffer=str_replace("[warganegara]","$warganegara",$buffer);
		$buffer=str_replace("[pasport]","$dokumen_pasport",$buffer);
		$buffer=str_replace("[kitas]","$dokumen_kitas",$buffer);
		$buffer=str_replace("[ayah]","\caps $nama_ayah",$buffer);
		$buffer=str_replace("[ibu]","\caps $nama_ibu",$buffer);
		$buffer=str_replace("[darah]","$golongan_darah",$buffer);

		$h = $data['desa'];
		$k = $data['kepala_kk'];
		$tertanda = tgl_indo(date("Y m d"));
		$tertanda = $h['nama_desa'].", ".$tertanda;
		$buffer=str_replace("desa","\caps $h[nama_desa]",$buffer);
		$buffer=str_replace("alamat_plus_dusun","\caps $k[alamat_plus_dusun]",$buffer);
		$buffer=str_replace("prop","\caps $h[nama_propinsi]",$buffer);
		$buffer=str_replace("kab","\caps $h[nama_kabupaten]",$buffer);
		$buffer=str_replace("kec","\caps $h[nama_kecamatan]",$buffer);
		$buffer=str_replace("*camat","\caps $h[nama_kepala_camat]",$buffer);
		$buffer=str_replace("*kades","\caps $h[nama_kepala_desa]",$buffer);
		$buffer=str_replace("*rt","$k[rt]",$buffer);
		$buffer=str_replace("*rw","$k[rw]",$buffer);
		$buffer=str_replace("*kk","\caps $k[nama]",$buffer);
		$buffer=str_replace("no_kk","$k[no_kk]",$buffer);
		$buffer=str_replace("pos","$h[kode_pos]",$buffer);
		$buffer=str_replace("*tertanda","\caps $tertanda",$buffer);
		$buffer=str_replace("*nip_camat","$h[nip_kepala_camat]",$buffer);

		$berkas_arsip = $path_arsip."kk_$k[no_kk].rtf";
		$handle = fopen($berkas_arsip,'w+');
		fwrite($handle,$buffer);
		fclose($handle);
		return $berkas_arsip;
	}

	function masukkan_zip($files=array()){
    $zip = new ZipArchive();
    # create a temp file & open it
    $tmp_file = tempnam(sys_get_temp_dir(),'');
    $zip->open($tmp_file, ZipArchive::CREATE);

    # masukkan setiap berkas ke dalam zip
    foreach($files as $file){
        $download_file = file_get_contents($file);
        $zip->addFromString(basename($file),$download_file);
    }
    $zip->close();
    return $tmp_file;
	}

	function coba2(){
		ini_set('memory_limit', '2048M');
		$mypath="surat\\undangan\\";

		$path = "".str_replace("\\","/",$mypath);
		$path_arsip = LOKASI_ARSIP;

		$file = $path."apik.rtf";
		if(is_file($file)){
			$buffer2 ="";

			$handle = fopen($file,'r');
			$b = stream_get_contents($handle);

			$c = Parse_Data($b,'\widowctrl','{\*\themedata');
			$c = "\widowctrl".$c;
			$awal = Parse_Data($b,'{','\widowctrl');
			$awal = "{".$awal;
			$akhir = strstr($b,'{\*\themedata');

			$data = $this->list_data();
			$i=1;
			$h = substr_count($c, 'fxnama');
			$h =4;
			$j=count($data);
			$k =1;
			$buffer=$c;
			foreach($data AS $d){
				if($d['sex']=="PEREMPUAN")
					$sex = "IBU";
				else
					$sex = "BAPAK";

				$alamat = $d['dusun'].", RT ".$d['rt']."/RW ".$d['rw'];
				$buffer=str_replace("fxnama$k","\caps $d[kepala_kk]",$buffer);
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

			$buffer2 .= $buffer;

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
