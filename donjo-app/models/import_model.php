<?php

define("KOLOM_IMPOR_KELUARGA", serialize(array(
  strtolower("alamat") => "1",
  strtolower("dusun") => "2",
  strtolower("rw")  => "3",
  strtolower("rt") => "4",
  strtolower("nama") => "5",
  strtolower("no_kk") => "6",
  strtolower("nik")  => "7",
  strtolower("sex") => "8",
  strtolower("tempatlahir") => "9",
  strtolower("tanggallahir")  => "10",
  strtolower("agama_id") => "11",
  strtolower("pendidikan_kk_id") => "12",
  strtolower("pendidikan_sedang_id") => "13",
  strtolower("pekerjaan_id") => "14",
  strtolower("status_kawin")  => "15",
  strtolower("kk_level") => "16",
  strtolower("warganegara_id") => "17",
  strtolower("nama_ayah")  => "18",
  strtolower("nama_ibu") => "19",
  strtolower("golongan_darah_id") => "20")));

class import_model extends CI_Model{

	function __construct(){
		parent::__construct();
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
	}

/* 	========================================================
		IMPORT EXCEL
		========================================================
*/
	function file_import_valid() {
		// error 1 = UPLOAD_ERR_INI_SIZE; lihat Upload.php
		// TODO: pakai cara upload yg disediakan Codeigniter
		if ($_FILES['userfile']['error'] == 1) {
			$max_upload = (int)(ini_get('upload_max_filesize'));
			$max_post = (int)(ini_get('post_max_size'));
			$memory_limit = (int)(ini_get('memory_limit'));
			$upload_mb = min($max_upload, $max_post, $memory_limit);
			$_SESSION['error_msg'].= " -> Ukuran file melebihi batas " . $upload_mb . " MB";
			$_SESSION['success']=-1;
			return false;
		}

		$mime_type_excel = array("application/vnd.ms-excel", "application/octet-stream");
		if(!in_array($_FILES['userfile']['type'], $mime_type_excel)){
			$_SESSION['error_msg'].= " -> Jenis file salah: " . $_FILES['userfile']['type'];
			$_SESSION['success']=-1;
			return false;
		}

		return true;
	}

	function data_import_valid($isi_baris) {
		// Kolom yang harus diisi
		if ($isi_baris['nama']=="" OR $isi_baris['nik']=="" OR $isi_baris['dusun']=="" OR $isi_baris['rt']== "" OR $isi_baris['rw']=="")
			return false;
		// Validasi data setiap kolom ber-kode
		if ($isi_baris['sex']!="" AND !($isi_baris['sex'] >= 1 && $isi_baris['sex'] <= 2)) return false;
		if ($isi_baris['agama_id']!="" AND !($isi_baris['agama_id'] >= 1 && $isi_baris['agama_id'] <= 7)) return false;
		if ($isi_baris['pendidikan_kk_id']!="" AND !($isi_baris['pendidikan_kk_id'] >= 1 && $isi_baris['pendidikan_kk_id'] <= 10)) return false;
		if ($isi_baris['pendidikan_sedang_id']!="" AND !($isi_baris['pendidikan_sedang_id'] >= 1 && $isi_baris['pendidikan_sedang_id'] <= 18)) return false;
		if ($isi_baris['pekerjaan_id']!="" AND !($isi_baris['pekerjaan_id'] >= 1 && $isi_baris['pekerjaan_id'] <= 89)) return false;
		if ($isi_baris['status_kawin']!="" AND !($isi_baris['status_kawin'] >= 1 && $isi_baris['status_kawin'] <= 4)) return false;
		if ($isi_baris['kk_level']!="" AND !($isi_baris['kk_level'] >= 1 && $isi_baris['kk_level'] <= 11)) return false;
		if ($isi_baris['warganegara_id']!="" AND !($isi_baris['warganegara_id'] >= 1 && $isi_baris['warganegara_id'] <= 3)) return false;
		if ($isi_baris['golongan_darah_id']!="" AND !($isi_baris['golongan_darah_id'] >= 1 && $isi_baris['golongan_darah_id'] <= 13)) return false;
		// Validasi data lain
		if (!ctype_digit($isi_baris['nik']) OR (strlen($isi_baris['nik']) != 16 AND $isi_baris['nik'] != '0')) return false;

		return true;
	}

	function format_tanggallahir($tanggallahir) {
		if(strlen($tanggallahir)==0){
			return $tanggallahir;
		}

		// Ganti separator tanggal supaya tanggal diproses sebagai dd-mm-YYYY.
		// Kalau pakai '/', strtotime memrosesnya sebagai mm/dd/YYYY.
		// Lihat panduan strtotime: http://php.net/manual/en/function.strtotime.php
		$tanggallahir = str_replace('/', '-', $tanggallahir);
		$tanggallahir = date("Y-m-d",strtotime($tanggallahir));
		return $tanggallahir;
	}

	function get_isi_baris($data, $i) {
		$kolom_impor_keluarga = unserialize(KOLOM_IMPOR_KELUARGA);
		$isi_baris['alamat'] = trim($data->val($i,$kolom_impor_keluarga['alamat']));
		$dusun = ltrim(trim($data->val($i, $kolom_impor_keluarga['dusun'])),"'");
		$dusun = str_replace('_',' ', $dusun);
		$dusun = strtoupper($dusun);
		$dusun = str_replace('DUSUN ','', $dusun);
		$isi_baris['dusun'] = $dusun;

		$isi_baris['rw'] = ltrim(trim($data->val($i, $kolom_impor_keluarga['rw'])),"'");
		$isi_baris['rt'] = ltrim(trim($data->val($i, $kolom_impor_keluarga['rt'])),"'");

		$nama = trim($data->val($i, $kolom_impor_keluarga['nama']));
		$nama = preg_replace('/[^a-zA-Z0-9,\.]/', ' ', $nama);
		$isi_baris['nama'] = $nama;

		// Data Disdukcapil adakalanya berisi karakter tambahan pada no_kk dan nik
		// yang tidak tampak (non-printable characters),
		// jadi perlu dibuang
		$no_kk= trim($data->val($i, $kolom_impor_keluarga['no_kk']));
		$no_kk = preg_replace('/[^0-9]/', '', $no_kk);
		$isi_baris['no_kk'] = $no_kk;

		$nik = trim($data->val($i, $kolom_impor_keluarga['nik']));
		$nik = preg_replace('/[^0-9]/', '', $nik);
		$isi_baris['nik'] = $nik;

		$isi_baris['sex'] = trim($data->val($i, $kolom_impor_keluarga['sex']));
		$isi_baris['tempatlahir']= trim($data->val($i, $kolom_impor_keluarga['tempatlahir']));

		$tanggallahir= ltrim(trim($data->val($i, $kolom_impor_keluarga['tanggallahir'])),"'");
		$isi_baris['tanggallahir'] = $this->format_tanggallahir($tanggallahir);

		$isi_baris['agama_id']= trim($data->val($i, $kolom_impor_keluarga['agama_id']));
		$isi_baris['pendidikan_kk_id']= trim($data->val($i, $kolom_impor_keluarga['pendidikan_kk_id']));

		$pendidikan_sedang_id= trim($data->val($i, $kolom_impor_keluarga['pendidikan_sedang_id']));
		if($pendidikan_sedang_id=="")
			$pendidikan_sedang_id=18;
		$isi_baris['pendidikan_sedang_id'] = $pendidikan_sedang_id;

		$isi_baris['pekerjaan_id']= trim($data->val($i, $kolom_impor_keluarga['pekerjaan_id']));
		$isi_baris['status_kawin']= trim($data->val($i, $kolom_impor_keluarga['status_kawin']));
		$isi_baris['kk_level']= trim($data->val($i, $kolom_impor_keluarga['kk_level']));
		$isi_baris['warganegara_id']= trim($data->val($i, $kolom_impor_keluarga['warganegara_id']));

		$nama_ayah= trim($data->val($i,$kolom_impor_keluarga['nama_ayah']));
		if($nama_ayah==""){
			$nama_ayah = "-";
		}
		$isi_baris['nama_ayah'] = $nama_ayah;

		$nama_ibu= trim($data->val($i,$kolom_impor_keluarga['nama_ibu']));
		if($nama_ibu==""){
			$nama_ibu = "-";
		}
		$isi_baris['nama_ibu'] = $nama_ibu;

		$isi_baris['golongan_darah_id']= trim($data->val($i, $kolom_impor_keluarga['golongan_darah_id']));

		return $isi_baris;
	}

	function tulis_tweb_wil_clusterdesa(&$isi_baris) {
		// Masukkan wilayah administratif ke tabel tweb_wil_clusterdesa apabila
		// wilayah administratif ini belum ada

		// --- Masukkan dusun apabila belum ada
		$query = "SELECT id FROM tweb_wil_clusterdesa WHERE dusun=?";
		$hasil = $this->db->query($query, $isi_baris['dusun']);
		$res = $hasil->row_array();
		if (empty($res)) {
			$query = "INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES (0,0,'".$isi_baris['dusun']."')";
			$hasil = $this->db->query($query);
			$query = "INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES (0,'-','".$isi_baris['dusun']."')";
			$hasil = $this->db->query($query);
			$query = "INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES ('-','-','".$isi_baris['dusun']."')";
			$hasil = $this->db->query($query);
		}

		// --- Masukkan rw apabila belum ada
		$query = "SELECT id FROM tweb_wil_clusterdesa WHERE dusun=? AND rw=?";
		$hasil = $this->db->query($query, array($isi_baris['dusun'], $isi_baris['rw']));
		$res = $hasil->row_array();
		if (empty($res)) {
			$query = "INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES (0,'".$isi_baris['rw']."','".$isi_baris['dusun']."')";
			$hasil = $this->db->query($query);
			$query = "INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES ('-','".$isi_baris['rw']."','".$isi_baris['dusun']."')";
			$hasil = $this->db->query($query);
			$isi_baris['id_cluster'] = $this->db->insert_id();
		}

		// --- Masukkan rt apabila belum ada
		$query = "SELECT id FROM tweb_wil_clusterdesa WHERE
							dusun='".$isi_baris['dusun']."' AND rw='".$isi_baris['rw']."' AND rt='".$isi_baris['rt']."'";
		$hasil = $this->db->query($query);
		$res = $hasil->row_array();
		if ( ! empty($res)) {
			$isi_baris['id_cluster'] = $res['id'];
		} else {
			$query = "INSERT INTO tweb_wil_clusterdesa(rt,rw,dusun) VALUES ('".$isi_baris['rt']."','".$isi_baris['rw']."','".$isi_baris['dusun']."')";
			$hasil = $this->db->query($query);
			$isi_baris['id_cluster'] = $this->db->insert_id();
		}
	}

	function tulis_tweb_keluarga(&$isi_baris) {
		// Penduduk dengan no_kk adalah penduduk lepas
		if ($isi_baris['no_kk'] == '') {
			return;
		}
		// Masukkan keluarga ke tabel tweb_keluarga apabila
		// keluarga ini belum ada
		$query = "SELECT id from tweb_keluarga WHERE no_kk=?";
		$hasil = $this->db->query($query, $isi_baris['no_kk']);
		$res = $hasil->row_array();
		if ( ! empty($res)) {
			// Update keluarga apabila sudah ada
			$isi_baris['id_kk'] = $res['id'];
			$id = $res['id'];
			$this->db->where('id',$id);
			// Hanya update apabila alamat kosong
			// karena alamat keluarga akan diupdate menggunakan data kepala keluarga di tulis_tweb_pendududk
			$this->db->where('alamat', NULL);
			$data['alamat'] = $isi_baris['alamat'];
			$hasil = $this->db->update('tweb_keluarga',$data);
		} else {
			$data['no_kk'] = $isi_baris['no_kk'];
			$data['alamat'] = $isi_baris['alamat'];
			$hasil = $this->db->insert('tweb_keluarga', $data);
			$isi_baris['id_kk'] = $this->db->insert_id();
		}
	}

	function tulis_tweb_penduduk($isi_baris) {
		// Siapkan data penduduk
			$data['nama'] = $isi_baris['nama'];
			$data['nik'] = $isi_baris['nik'];
			$data['id_kk'] = $isi_baris['id_kk'];
			$data['kk_level'] = $isi_baris['kk_level'];
			$data['sex'] = $isi_baris['sex'];
			$data['tempatlahir'] = $isi_baris['tempatlahir'];
			$data['tanggallahir'] = $isi_baris['tanggallahir'];
			$data['agama_id'] = $isi_baris['agama_id'];
			$data['pendidikan_kk_id'] = $isi_baris['pendidikan_kk_id'];
			$data['pendidikan_sedang_id'] = $isi_baris['pendidikan_sedang_id'];
			$data['pekerjaan_id'] = $isi_baris['pekerjaan_id'];
			$data['status_kawin'] = $isi_baris['status_kawin'];
			$data['warganegara_id'] = $isi_baris['warganegara_id'];
			$data['nama_ayah'] = $isi_baris['nama_ayah'];
			$data['nama_ibu'] = $isi_baris['nama_ibu'];
			$data['golongan_darah_id'] = $isi_baris['golongan_darah_id'];
			$data['id_cluster'] = $isi_baris['id_cluster'];
			$data['status'] = '1';  // penduduk impor dianggap aktif
		// Jangan masukkan atau update isian yang kosong
			foreach ($data as $key => $value) {
				if ($value == "") {
					unset($data[$key]);
				}
			}
		// Masukkan penduduk ke tabel tweb_penduduk apabila
		// penduduk ini belum ada
		// Penduduk dianggap baru apabila NIK tidak diketahui (nilai 0)
		if ($isi_baris['nik'] != 0) {
			// Update data penduduk yang sudah ada
			$query = "SELECT id from tweb_penduduk WHERE nik=?";
			$hasil = $this->db->query($query, $isi_baris['nik']);
			$res = $hasil->row_array();
			if (!empty($res)) {
				$id = $res['id'];
				$this->db->where('id',$id);
				$hasil = $this->db->update('tweb_penduduk',$data);
			} else {
				$hasil = $this->db->insert('tweb_penduduk',$data);
				$id = $this->db->insert_id();
			}
		} else {
			$hasil = $this->db->insert('tweb_penduduk',$data);
			$id = $this->db->insert_id();
		}

		// Update nik_kepala dan id_cluster di keluarga apabila baris ini kepala keluarga
		// dan sudah ada NIK
		if ($data['kk_level'] == 1) {
      $this->db->where('id', $data['id_kk']);
      $this->db->update('tweb_keluarga', array('nik_kepala' => $id, 'id_cluster' => $isi_baris['id_cluster'], 'alamat' => $isi_baris['alamat']));
		}
	}

	function hapus_data_penduduk() {
		$a="TRUNCATE tweb_wil_clusterdesa";
		$this->db->query($a);

		$a="TRUNCATE tweb_keluarga";
		$this->db->query($a);

		$a="TRUNCATE tweb_penduduk";
		$this->db->query($a);
	}

	function cari_baris_pertama($data, $baris) {
		if ($baris <=1 )
			return 0;

		$baris_pertama = 1;
		for ($i=2; $i<=$baris; $i++){
			// Baris dengan kolom dusun = '###' menunjukkan telah sampai pada baris data terakhir
			if($data->val($i,1) == '###') {
				$baris_pertama = $i-1;
				break;
			}
			// Baris dengan dusun/rw/rt kosong menandakan baris tanpa data
			if ($data->val($i,1) == '' AND $data->val($i,2) == '' AND $data->val($i,3) == '') {
				continue;
			} else {
				// Ketemu baris data pertama
				$baris_pertama = $i;
				break;
			}
		}
		return $baris_pertama;
	}

	function import_excel($hapus=false) {
		$_SESSION['error_msg'] = '';
		$_SESSION['success'] = 1;
		if ($this->file_import_valid() == false) {
			return;
		}

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);
		if ($this->cari_baris_pertama($data, $baris) <= 1) {
			$_SESSION['error_msg'].= " -> Tidak ada data";
			$_SESSION['success']=-1;
			return;
		}
		$baris_data = $baris;

		$this->db->query("SET character_set_connection = utf8");
		$this->db->query("SET character_set_client = utf8");

		// Pengguna bisa menentukan apakah data penduduk yang ada dihapus dulu
		// atau tidak sebelum melakukan impor
		if ($hapus) { $this->hapus_data_penduduk(); }

		$gagal=0;
		$baris_gagal ="";
		$baris_kosong = 0;
		// Import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++){

			// Baris dengan kolom dusun = '###' menunjukkan telah sampai pada baris data terakhir
			if($data->val($i,1) == '###') {
				$baris_data = $i-1;
				break;
			}

			// Baris dengan dusun/rw/rt kosong menandakan baris tanpa data
			if ($data->val($i,1) == '' AND $data->val($i,2) == '' AND $data->val($i,3) == '') {
				$baris_kosong++;
				continue;
			}

			$isi_baris = $this->get_isi_baris($data, $i);
			if ($this->data_import_valid($isi_baris)) {
				$this->tulis_tweb_wil_clusterdesa($isi_baris);
				$this->tulis_tweb_keluarga($isi_baris);
				$this->tulis_tweb_penduduk($isi_baris);
			}else{
				$gagal++;
				$baris_gagal .=$i.",";
			}
		}

		$sukses = $baris_data - $baris_kosong - $gagal - 1;

		if($gagal==0)
			$baris_gagal ="tidak ada data yang gagal di import.";
		else $_SESSION['success']=-1;

		$_SESSION['gagal']=$gagal;
		$_SESSION['sukses']=$sukses;
		$_SESSION['baris']=$baris_gagal;
	}

	/* 	====================
			Selesai IMPORT EXCEL
			====================
	*/

	/* 	===============================
			IMPORT BUKU INDUK PENDUDUK 2012
			===============================
	*/

	function cari_bip_kk($data_sheet, $baris, $dari=1){
		if ($baris <=1 )
			return 0;

		$baris_kk = 0;
		for ($i=$dari; $i<=$baris; $i++){
			// Baris dengan kolom[2] = "NO.KK" menunjukkan mulainya data keluarga dan anggotanya
			if($data_sheet[$i][2] == 'NO.KK') {
				$baris_kk = $i;
				break;
			}
		}
		return $baris_kk;
	}

	function get_bip_keluarga($data_sheet, $i){
		// Contoh alamat: "DUSUN KERANDANGAN, RT:001, RW:001, Kodepos:83355,-"
		// $i = baris judul data keluarga. Data keluarga ada di baris berikutnya
		$baris = $i + 1;
		$alamat = $data_sheet[$baris][7];
		$pos_awal = strpos($alamat, 'DUSUN');
		if ($pos_awal !== false){
			$pos = $pos_awal + 5;
			$data_keluarga['dusun'] = trim(substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos));
			$alamat = substr_replace($alamat, '', $pos_awal, strpos($alamat, ',', $pos) - $pos_awal);
		} else $data_keluarga['dusun'] = 'LAINNYA';
		$pos_awal = strpos($alamat, 'RW:');
		if ($pos_awal !== false){
			$pos = $pos + 3;
			$data_keluarga['rw'] = substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos);
			$alamat = substr_replace($alamat, '', $pos_awal, strpos($alamat, ',', $pos) - $pos_awal);
		} else $data_keluarga['rw'] = '-';
		if ($data_keluarga['rw'] == '') $data_keluarga['rw'] = '-';
		$pos_awal = strpos($alamat, 'RT:');
		if ($pos_awal !== false){
			$pos = $pos_awal + 3;
			$data_keluarga['rt'] = substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos);
			$alamat = substr_replace($alamat, '', $pos_awal, strpos($alamat, ',', $pos) - $pos_awal);
		} else $data_keluarga['rt'] = '-';
		if ($data_keluarga['rt'] == '') $data_keluarga['rt'] = '-';
		$alamat = rtrim(ltrim(preg_replace("/Kodepos:.*,/i", '', $alamat), " ,-")," ,-");
		// $alamat sudah tidak ada dusun, rw, rt atau kodepos -- tinggal jalan, kompleks, gedung dsbnya
		$data_keluarga['alamat'] = $alamat;
		$data_keluarga['no_kk'] = $data_sheet[$baris][2];
		return $data_keluarga;
	}

	function get_bip_anggota_keluarga($data_sheet, $i, $data_keluarga){
		// $i = baris data anggota keluarga
		$data_anggota = $data_keluarga;
		$data_anggota['nik'] = preg_replace('/[^0-9]/', '', trim($data_sheet[$i][3]));
		$data_anggota['nama'] = trim($data_sheet[$i][4]);
		$tmp = unserialize(KODE_SEX);
		$data_anggota['sex'] = $tmp[trim($data_sheet[$i][5])];
		$data_anggota['tempatlahir'] = trim($data_sheet[$i][6]);
		$tanggallahir = trim($data_sheet[$i][7]);
		$data_anggota['tanggallahir'] = $this->format_tanggallahir($tanggallahir);
		$tmp = unserialize(KODE_AGAMA);
		$data_anggota['agama_id'] = $tmp[strtolower(trim($data_sheet[$i][9]))];
		$tmp = unserialize(KODE_STATUS);
		$data_anggota['status_kawin'] = $tmp[strtolower(trim($data_sheet[$i][10]))];
		$tmp = unserialize(KODE_HUBUNGAN);
		$data_anggota['kk_level'] = $tmp[strtolower(trim($data_sheet[$i][11]))];
		$tmp = unserialize(KODE_PENDIDIKAN);
		$data_anggota['pendidikan_kk_id'] = $tmp[strtolower(trim($data_sheet[$i][12]))];
		$tmp = unserialize(KODE_PEKERJAAN);
		$data_anggota['pekerjaan_id'] = $tmp[strtolower(trim($data_sheet[$i][13]))];
		$nama_ibu = trim($data_sheet[$i][14]);
		if($nama_ibu==""){
			$nama_ibu = "-";
		}
		$data_anggota['nama_ibu'] = $nama_ibu;
		$nama_ayah = trim($data_sheet[$i][15]);
		if($nama_ayah==""){
			$nama_ayah = "-";
		}
		$data_anggota['nama_ayah'] = $nama_ayah;
		$data_anggota['akta_lahir'] = trim($data_sheet[$i][16]);

		// Isi kolom default
		$data_anggota['warganegara_id'] = "1";
		$data_anggota['golongan_darah_id'] = "13";
		$data_anggota['pendidikan_sedang_id'] = "";

		return $data_anggota;
	}

	function import_bip_2012($data) {
		$gagal_penduduk = 0;
		$baris_gagal = "";
		$total_keluarga = 0;
		$total_penduduk = 0;

		// BIP bisa terdiri dari beberapa worksheet
		// Proses sheet satu-per-satu
		for ($sheet_index=0; $sheet_index<count($data->boundsheets); $sheet_index++){
			// membaca jumlah baris di sheet ini
			$baris = $data->rowcount($sheet_index);
			$data_sheet = $data->sheets[$sheet_index]['cells'];
			if ($this->cari_bip_kk($data_sheet, $baris, 1) < 1) {
				// Tidak ada data keluarga
				continue;
			}
			// Import data sheet ini mulai baris pertama
			for ($i=1; $i<=$baris; $i++){
				// Cari keluarga berikutnya
				if ($data_sheet[$i][2] != "NO.KK") continue;
				// Proses keluarga
				$data_keluarga = $this->get_bip_keluarga($data_sheet, $i);
				$this->tulis_tweb_wil_clusterdesa($data_keluarga);
				$this->tulis_tweb_keluarga($data_keluarga);
				$total_keluarga++;
				// Pergi ke data anggota keluarga
				$i = $i + 3;
				// Proses setiap anggota keluarga
				while ($data_sheet[$i][2] != "NO.KK" AND $i <= $baris) {
					$data_anggota = $this->get_bip_anggota_keluarga($data_sheet, $i, $data_keluarga);
					if ($this->data_import_valid($data_anggota)) {
						$this->tulis_tweb_penduduk($data_anggota);
						$total_penduduk++;
					}else{
						$gagal_penduduk++;
						$baris_gagal .=$i.",";
					}
					$i++;
				}
				$i = $i - 1;
			}
		}

		if($gagal_penduduk==0)
			$baris_gagal ="tidak ada data yang gagal di import.";
		else $_SESSION['success']=-1;

		$_SESSION['gagal']=$gagal_penduduk;
		$_SESSION['total_keluarga']=$total_keluarga;
		$_SESSION['total_penduduk']=$total_penduduk;
		$_SESSION['baris']=$baris_gagal;
	}

	function import_bip($hapus=false){
		$_SESSION['error_msg'] = '';
		$_SESSION['success'] = 1;
		if ($this->file_import_valid() == false) {
			return;
		}

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

		$this->db->query("SET character_set_connection = utf8");
		$this->db->query("SET character_set_client = utf8");

		// Pengguna bisa menentukan apakah data penduduk yang ada dihapus dulu
		// atau tidak sebelum melakukan impor
		if ($hapus) { $this->hapus_data_penduduk(); }

		// Proses berdasarkan format BIP yang diupload
		$data_sheet = $data->sheets[0]['cells'];
		if ($data_sheet[1][1] == "BUKU INDUK PENDUDUK WNI") {
			$a = 1;
			$this->import_bip_2016($data);
		} else {
			$a = 2;
			$this->import_bip_2012($data);
		}
	}

	/* 	===============================
			IMPORT BUKU INDUK PENDUDUK 2016
			===============================
	*/

	function cari_bip_kk_2016($data_sheet, $baris, $dari=1){
		if ($baris <= 1 )
			return 0;

		$baris_kk = 0;
		for ($i=$dari; $i<=$baris; $i++){
			// Baris dengan kolom[1] yang mulai dengan "No. KK" menunjukkan mulainya data keluarga dan anggotanya
			if (strpos($data_sheet[$i][1], 'No. KK') === 0) {
				$baris_kk = $i;
				break;
			}
		}
		return $baris_kk;
	}

		function get_bip_keluarga_2016($data_sheet, $i){
		// Contoh alamat: "Alamat : MERTAK PAOK, Nama Dusun : MERTAK PAOK, RT/RW : -/-"
		// $i = baris berisi data keluarga.
		$baris = $i;
		$alamat = $data_sheet[$baris][3];
		$pos_awal = strpos($alamat, 'Alamat :');
		if ($pos_awal !== false){
			$pos = $pos_awal + strlen('Alamat :');
			$data_keluarga['alamat'] = trim(substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos));
		} else $data_keluarga['alamat'] = '';
		$pos_awal = strpos($alamat, 'Nama Dusun :');
		if ($pos_awal !== false){
			$pos = $pos_awal + strlen('Nama Dusun :');
			$data_keluarga['dusun'] = trim(substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos));
		} else $data_keluarga['dusun'] = 'LAINNYA';
		$pos_rtrw = strpos($alamat, 'RT/RW :');
		if ($pos_rtrw !== false){
			$pos_rtrw = $pos_rtrw + strlen('RT/RW :');
			$pos_rw = strpos($alamat, '/', $pos_rtrw);
			$pos = $pos_rw + strlen('/');
			$data_keluarga['rw'] = trim(substr($alamat, $pos, strlen($alamat) - $pos));
		} else $data_keluarga['rw'] = '-';
		if ($data_keluarga['rw'] == '') $data_keluarga['rw'] = '-';
		if ($pos_rtrw !== false){
			$data_keluarga['rt'] = trim(substr($alamat, $pos_rtrw, $pos_rw - $pos_rtrw));
		} else $data_keluarga['rt'] = '-';
		if ($data_keluarga['rt'] == '') $data_keluarga['rt'] = '-';
		// Contoh No. KK : 5202030102110012
		$no_kk = $data_sheet[$baris][1];
		$pos_awal = strpos($no_kk, 'No. KK :');
		if ($pos_awal !== false){
			$pos = $pos_awal + strlen('No. KK :');
			$data_keluarga['no_kk'] = preg_replace('/[^0-9]/', '', trim(substr($no_kk, $pos, strlen($no_kk) - $pos)));
		}
		return $data_keluarga;
	}

	function get_bip_anggota_keluarga_2016($data_sheet, $i, $data_keluarga){
		// $i = baris data anggota keluarga
		$data_anggota = $data_keluarga;
		$data_anggota['nama'] = trim($data_sheet[$i][2]);
		$data_anggota['nik'] = preg_replace('/[^0-9]/', '', trim($data_sheet[$i][3]));
		$data_anggota['tempatlahir'] = trim($data_sheet[$i][4]);
		$tanggallahir = trim($data_sheet[$i][5]);
		$data_anggota['tanggallahir'] = $this->format_tanggallahir($tanggallahir);
		$tmp = unserialize(KODE_SEX);
		$data_anggota['sex'] = $tmp[trim($data_sheet[$i][6])];
		$tmp = unserialize(KODE_HUBUNGAN);
		$data_anggota['kk_level'] = $tmp[strtolower(trim($data_sheet[$i][7]))];
		$tmp = unserialize(KODE_AGAMA);
		$data_anggota['agama_id'] = $tmp[strtolower(trim($data_sheet[$i][8]))];
		$tmp = unserialize(KODE_PENDIDIKAN);
		$data_anggota['pendidikan_kk_id'] = $tmp[strtolower(trim($data_sheet[$i][9]))];
		$tmp = unserialize(KODE_PEKERJAAN);
		$data_anggota['pekerjaan_id'] = $tmp[strtolower(trim($data_sheet[$i][10]))];
		$nama_ibu = trim($data_sheet[$i][11]);
		if($nama_ibu==""){
			$nama_ibu = "-";
		}
		$data_anggota['nama_ibu'] = $nama_ibu;

		// Isi kolom default
		$data_anggota['status_kawin'] = "";
		$data_anggota['nama_ayah'] = "-";
		$data_anggota['akta_lahir'] = "";
		$data_anggota['warganegara_id'] = "1";
		$data_anggota['golongan_darah_id'] = "13";
		$data_anggota['pendidikan_sedang_id'] = "";

		return $data_anggota;
	}

	function import_bip_2016($data) {
		$gagal_penduduk = 0;
		$baris_gagal = "";
		$total_keluarga = 0;
		$total_penduduk = 0;

		// BIP bisa terdiri dari beberapa worksheet
		// Proses sheet satu-per-satu
		for ($sheet_index=0; $sheet_index<count($data->boundsheets); $sheet_index++){
			// membaca jumlah baris di sheet ini
			$baris = $data->rowcount($sheet_index);
			$data_sheet = $data->sheets[$sheet_index]['cells'];
			if ($this->cari_bip_kk_2016($data_sheet, $baris, 1) < 1) {
				// Tidak ada data keluarga
				continue;
			}
			// Import data sheet ini mulai baris pertama
			for ($i=1; $i<=$baris; $i++){
				// Baris-baris keterangan ada di akhir berkas BIP 2016. Selesai apabila ketemu.
				if(strpos($data_sheet[$i][1], 'Keterangan:') === 0) break;

				// Cari keluarga berikutnya
				if(strpos($data_sheet[$i][1], 'No. KK') !== 0) continue;
				// Proses keluarga
				$data_keluarga = $this->get_bip_keluarga_2016($data_sheet, $i);
				$this->tulis_tweb_wil_clusterdesa($data_keluarga);
				$this->tulis_tweb_keluarga($data_keluarga);
				$total_keluarga++;
				// Pergi ke data anggota keluarga
				$i = $i + 1;
				// Proses setiap anggota keluarga
				while (strpos($data_sheet[$i][1], 'No. KK') !== 0 AND $i <= $baris) {
					if(!is_numeric($data_sheet[$i][1])) break;
					$data_anggota = $this->get_bip_anggota_keluarga_2016($data_sheet, $i, $data_keluarga);
					if ($this->data_import_valid($data_anggota)) {
						$this->tulis_tweb_penduduk($data_anggota);
						$total_penduduk++;
					}else{
						$gagal_penduduk++;
						$baris_gagal .=$i.",";
					}
					$i++;
				}
				$i = $i - 1;
			}
		}

		if($gagal_penduduk==0)
			$baris_gagal ="tidak ada data yang gagal di import.";
		else $_SESSION['success']=-1;

		$_SESSION['gagal']=$gagal_penduduk;
		$_SESSION['total_keluarga']=$total_keluarga;
		$_SESSION['total_penduduk']=$total_penduduk;
		$_SESSION['baris']=$baris_gagal;
	}


	/* 	==================================
			Selesai IMPORT BUKU INDUK PENDUDUK
			==================================
	*/

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
			//echo $cluster;
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
			//echo $inset.$in;

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
			//echo $cluster;
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
		$a="DELETE FROM `tweb_penduduk` WHERE status=2; ";
		$this->db->query($a);

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

		//master
		$sheet=0;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		//echo "<table>";
		for ($i=2; $i<=$baris; $i++){
			//echo "<tr>";

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
					//echo "<td>".$rt."</td><td>".$rt2."</td><td>".$dusun2."</td>";

				}elseif($j==17){

					$tlahir = $data->val($i,16,$sheet)."-".$data->val($i,17,$sheet)."-1";
					//echo "<td>".$tlahir."</td>";

				}else{

					//echo "<td>".$temp."</td>";

				}

				if($j==1)
					$j+=9;
			}
				$sql   		= "SELECT id FROM tweb_wil_clusterdesa WHERE rt = ? OR rt = ?";
				$query 		= $this->db->query($sql,array($rt,$rt2));
				$cluster  	= $query->row_array();
				if($cluster)
					$id_cluster = $cluster['id'];
				else
					$id_cluster = 0;
				$penduduk = "";
				$penduduk['id_cluster']		= $id_cluster;
				$penduduk['status']			= 2;
				$penduduk['nama']			= $data->val($i,13,$sheet);
				$penduduk['id_rtm']			= $data->val($i,1,$sheet);
				$penduduk['tanggallahir']	= $tlahir;
				$penduduk['rtm_level']		= 2;
				$penduduk['nik']			= $data->val($i,25,$sheet);
				$penduduk['kk_level']		= $data->val($i,14,$sheet);
				$penduduk['sex']			= $data->val($i,15,$sheet);
				$penduduk['pendidikan_id']			= $data->val($i,22,$sheet);
				$penduduk['pendidikan_kk_id']			= $data->val($i,22,$sheet);

				$outp = $this->db->insert('tweb_penduduk',$penduduk);

			//echo "</tr>";
		}
		//echo "</table>";

		$a="TRUNCATE tweb_rtm; ";
		$this->db->query($a);

		$a="INSERT INTO tweb_rtm (no_kk) SELECT distinct(id_rtm) AS no_kk FROM tweb_penduduk WHERE tweb_penduduk.status=2 AND tweb_penduduk.id_rtm <> 0; ";
		$this->db->query($a);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function ppls_rumahtangga(){
		//$a="TRUNCATE tweb_rtm; ";
		//$this->db->query($a);

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

		//master
		$sheet=0;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		//echo "<table>";
		for ($i=2; $i<=$baris; $i++){
			//echo "<tr>";


				$penduduk = "";
				//$penduduk['id_cluster']		= $id_cluster;
				//$penduduk['status']			= 2;
				$penduduk['nama']			= $data->val($i,12,$sheet);
				$penduduk['id_rtm']			= $data->val($i,1,$sheet);
				//$penduduk['tanggallahir']	= $tlahir;
				//$penduduk['nik']			= $data->val($i,25,$sheet);
				//$penduduk['kk_level']		= $data->val($i,14,$sheet);
				//$penduduk['sex']			= $data->val($i,15,$sheet);
				//$penduduk['pendidikan_id']			= $data->val($i,22,$sheet);
				//$penduduk['pendidikan_kk_id']			= $data->val($i,22,$sheet);

				//$outp = $this->db->insert('tweb_penduduk',$penduduk);
				$upd['rtm_level'] = 1;

			$this->db->where('id_rtm',$penduduk['id_rtm']	);
			$this->db->where('nama',$penduduk['nama']	);
			$outp = $this->db->update('tweb_penduduk',$upd);

			//echo "</tr>";
		}
		//echo "</table>";


		//$a="INSERT INTO tweb_rtm (no_kk)SELECT distinct(id_rtm) AS no_kk FROM tweb_pendudukWHERE status=2 AND id_rtm <> 0; ";
		//$this->db->query($a);

		//$a="UPDATE p SET p.id_rtm = r.id FROM tweb_penduduk p JOIN tweb_rtm r ON (p.id_rtm = r.no_kk); ";
		//$this->db->query($a);

		$sql   = "SELECT id,no_kk FROM tweb_rtm WHERE 1 ";

		$query = $this->db->query($sql);
		$rtm=$query->result_array();

		//Formating Output
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


define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);
define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);
define('ROOT_START_BLOCK_POS', 0x30);
define('BIG_BLOCK_SIZE', 0x200);
define('SMALL_BLOCK_SIZE', 0x40);
define('EXTENSION_BLOCK_POS', 0x44);
define('NUM_EXTENSION_BLOCK_POS', 0x48);
define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);
define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);
define('SMALL_BLOCK_THRESHOLD', 0x1000);
// property storage offsets
define('SIZE_OF_NAME_POS', 0x40);
define('TYPE_POS', 0x42);
define('START_BLOCK_POS', 0x74);
define('SIZE_POS', 0x78);
define('IDENTIFIER_OLE', pack("CCCCCCCC",0xd0,0xcf,0x11,0xe0,0xa1,0xb1,0x1a,0xe1));


function GetInt4d($data, $pos) {
	$value = ord($data[$pos]) | (ord($data[$pos+1])	<< 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
	if ($value>=4294967294) {
		$value=-2;
	}
	return $value;
}

// http://uk.php.net/manual/en/function.getdate.php
function gmgetdate($ts = null){
	$k = array('seconds','minutes','hours','mday','wday','mon','year','yday','weekday','month',0);
	return(array_comb($k,explode(":",gmdate('s:i:G:j:w:n:Y:z:l:F:U',is_null($ts)?time():$ts))));
	}

// Added for PHP4 compatibility
function array_comb($array1, $array2) {
	$out = array();
	foreach ($array1 as $key => $value) {
		$out[$value] = $array2[$key];
	}
	return $out;
}

function v($data,$pos) {
	return ord($data[$pos]) | ord($data[$pos+1])<<8;
}

class OLERead {
	var $data = '';
	function OLERead(){	}

	function read($sFileName){
		// check if file exist and is readable (Darko Miljanovic)
		if(!is_readable($sFileName)) {
			$this->error = 1;
			return false;
		}
		$this->data = @file_get_contents($sFileName);
		if (!$this->data) {
			$this->error = 1;
			return false;
   		}
   		if (substr($this->data, 0, 8) != IDENTIFIER_OLE) {
			$this->error = 1;
			return false;
   		}
		$this->numBigBlockDepotBlocks = GetInt4d($this->data, NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);
		$this->sbdStartBlock = GetInt4d($this->data, SMALL_BLOCK_DEPOT_BLOCK_POS);
		$this->rootStartBlock = GetInt4d($this->data, ROOT_START_BLOCK_POS);
		$this->extensionBlock = GetInt4d($this->data, EXTENSION_BLOCK_POS);
		$this->numExtensionBlocks = GetInt4d($this->data, NUM_EXTENSION_BLOCK_POS);

		$bigBlockDepotBlocks = array();
		$pos = BIG_BLOCK_DEPOT_BLOCKS_POS;
		$bbdBlocks = $this->numBigBlockDepotBlocks;
		if ($this->numExtensionBlocks != 0) {
			$bbdBlocks = (BIG_BLOCK_SIZE - BIG_BLOCK_DEPOT_BLOCKS_POS)/4;
		}

		for ($i = 0; $i < $bbdBlocks; $i++) {
			$bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
			$pos += 4;
		}


		for ($j = 0; $j < $this->numExtensionBlocks; $j++) {
			$pos = ($this->extensionBlock + 1) * BIG_BLOCK_SIZE;
			$blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, BIG_BLOCK_SIZE / 4 - 1);

			for ($i = $bbdBlocks; $i < $bbdBlocks + $blocksToRead; $i++) {
				$bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
				$pos += 4;
			}

			$bbdBlocks += $blocksToRead;
			if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
				$this->extensionBlock = GetInt4d($this->data, $pos);
			}
		}

		// readBigBlockDepot
		$pos = 0;
		$index = 0;
		$this->bigBlockChain = array();

		for ($i = 0; $i < $this->numBigBlockDepotBlocks; $i++) {
			$pos = ($bigBlockDepotBlocks[$i] + 1) * BIG_BLOCK_SIZE;
			//echo "pos = $pos";
			for ($j = 0 ; $j < BIG_BLOCK_SIZE / 4; $j++) {
				$this->bigBlockChain[$index] = GetInt4d($this->data, $pos);
				$pos += 4 ;
				$index++;
			}
		}

		// readSmallBlockDepot();
		$pos = 0;
		$index = 0;
		$sbdBlock = $this->sbdStartBlock;
		$this->smallBlockChain = array();

		while ($sbdBlock != -2) {
		  $pos = ($sbdBlock + 1) * BIG_BLOCK_SIZE;
		  for ($j = 0; $j < BIG_BLOCK_SIZE / 4; $j++) {
			$this->smallBlockChain[$index] = GetInt4d($this->data, $pos);
			$pos += 4;
			$index++;
		  }
		  $sbdBlock = $this->bigBlockChain[$sbdBlock];
		}


		// readData(rootStartBlock)
		$block = $this->rootStartBlock;
		$pos = 0;
		$this->entry = $this->__readData($block);
		$this->__readPropertySets();
	}

	function __readData($bl) {
		$block = $bl;
		$pos = 0;
		$data = '';
		while ($block != -2)  {
			$pos = ($block + 1) * BIG_BLOCK_SIZE;
			$data = $data.substr($this->data, $pos, BIG_BLOCK_SIZE);
			$block = $this->bigBlockChain[$block];
		}
		return $data;
	 }

	function __readPropertySets(){
		$offset = 0;
		while ($offset < strlen($this->entry)) {
			$d = substr($this->entry, $offset, PROPERTY_STORAGE_BLOCK_SIZE);
			$nameSize = ord($d[SIZE_OF_NAME_POS]) | (ord($d[SIZE_OF_NAME_POS+1]) << 8);
			$type = ord($d[TYPE_POS]);
			$startBlock = GetInt4d($d, START_BLOCK_POS);
			$size = GetInt4d($d, SIZE_POS);
			$name = '';
			for ($i = 0; $i < $nameSize ; $i++) {
				$name .= $d[$i];
			}
			$name = str_replace("\x00", "", $name);
			$this->props[] = array (
				'name' => $name,
				'type' => $type,
				'startBlock' => $startBlock,
				'size' => $size);
			if ((strtolower($name) == "workbook") || ( strtolower($name) == "book")) {
				$this->wrkbook = count($this->props) - 1;
			}
			if ($name == "Root Entry") {
				$this->rootentry = count($this->props) - 1;
			}
			$offset += PROPERTY_STORAGE_BLOCK_SIZE;
		}

	}


	function getWorkBook(){
		if ($this->props[$this->wrkbook]['size'] < SMALL_BLOCK_THRESHOLD){
			$rootdata = $this->__readData($this->props[$this->rootentry]['startBlock']);
			$streamData = '';
			$block = $this->props[$this->wrkbook]['startBlock'];
			$pos = 0;
			while ($block != -2) {
	  			  $pos = $block * SMALL_BLOCK_SIZE;
				  $streamData .= substr($rootdata, $pos, SMALL_BLOCK_SIZE);
				  $block = $this->smallBlockChain[$block];
			}
			return $streamData;
		}else{
			$numBlocks = $this->props[$this->wrkbook]['size'] / BIG_BLOCK_SIZE;
			if ($this->props[$this->wrkbook]['size'] % BIG_BLOCK_SIZE != 0) {
				$numBlocks++;
			}

			if ($numBlocks == 0) return '';
			$streamData = '';
			$block = $this->props[$this->wrkbook]['startBlock'];
			$pos = 0;
			while ($block != -2) {
			  $pos = ($block + 1) * BIG_BLOCK_SIZE;
			  $streamData .= substr($this->data, $pos, BIG_BLOCK_SIZE);
			  $block = $this->bigBlockChain[$block];
			}
			return $streamData;
		}
	}

}

define('SPREADSHEET_EXCEL_READER_BIFF8',			 0x600);
define('SPREADSHEET_EXCEL_READER_BIFF7',			 0x500);
define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS',   0x5);
define('SPREADSHEET_EXCEL_READER_WORKSHEET',		 0x10);
define('SPREADSHEET_EXCEL_READER_TYPE_BOF',		  0x809);
define('SPREADSHEET_EXCEL_READER_TYPE_EOF',		  0x0a);
define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET',   0x85);
define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION',	0x200);
define('SPREADSHEET_EXCEL_READER_TYPE_ROW',		  0x208);
define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL',	   0xd7);
define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS',	 0x2f);
define('SPREADSHEET_EXCEL_READER_TYPE_NOTE',		 0x1c);
define('SPREADSHEET_EXCEL_READER_TYPE_TXO',		  0x1b6);
define('SPREADSHEET_EXCEL_READER_TYPE_RK',		   0x7e);
define('SPREADSHEET_EXCEL_READER_TYPE_RK2',		  0x27e);
define('SPREADSHEET_EXCEL_READER_TYPE_MULRK',		0xbd);
define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK',	 0xbe);
define('SPREADSHEET_EXCEL_READER_TYPE_INDEX',		0x20b);
define('SPREADSHEET_EXCEL_READER_TYPE_SST',		  0xfc);
define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST',	   0xff);
define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE',	 0x3c);
define('SPREADSHEET_EXCEL_READER_TYPE_LABEL',		0x204);
define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST',	 0xfd);
define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER',	   0x203);
define('SPREADSHEET_EXCEL_READER_TYPE_NAME',		 0x18);
define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY',		0x221);
define('SPREADSHEET_EXCEL_READER_TYPE_STRING',	   0x207);
define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA',	  0x406);
define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2',	 0x6);
define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT',	   0x41e);
define('SPREADSHEET_EXCEL_READER_TYPE_XF',		   0xe0);
define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR',	  0x205);
define('SPREADSHEET_EXCEL_READER_TYPE_FONT',	  0x0031);
define('SPREADSHEET_EXCEL_READER_TYPE_PALETTE',	  0x0092);
define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN',	  0xffff);
define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);
define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS',  0xE5);
define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS' ,	25569);
define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);
define('SPREADSHEET_EXCEL_READER_MSINADAY',		  86400);
define('SPREADSHEET_EXCEL_READER_TYPE_HYPER',	     0x01b8);
define('SPREADSHEET_EXCEL_READER_TYPE_COLINFO',	     0x7d);
define('SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH',  0x55);
define('SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH', 0x99);
define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT',	"%s");


/*
* Main Class
*/
class Spreadsheet_Excel_Reader {

	// MK: Added to make data retrieval easier
	var $colnames = array();
	var $colindexes = array();
	var $standardColWidth = 0;
	var $defaultColWidth = 0;

	function myHex($d) {
		if ($d < 16) return "0" . dechex($d);
		return dechex($d);
	}

	function dumpHexData($data, $pos, $length) {
		$info = "";
		for ($i = 0; $i <= $length; $i++) {
			$info .= ($i==0?"":" ") . $this->myHex(ord($data[$pos + $i])) . (ord($data[$pos + $i])>31? "[" . $data[$pos + $i] . "]":'');
		}
		return $info;
	}

	function getCol($col) {
		if (is_string($col)) {
			$col = strtolower($col);
			if (array_key_exists($col,$this->colnames)) {
				$col = $this->colnames[$col];
			}
		}
		return $col;
	}

	// PUBLIC API FUNCTIONS
	// --------------------

	function val($row,$col,$sheet=0) {
		$col = $this->getCol($col);
		if (array_key_exists($row,$this->sheets[$sheet]['cells']) && array_key_exists($col,$this->sheets[$sheet]['cells'][$row])) {
			return $this->sheets[$sheet]['cells'][$row][$col];
		}
		return "";
	}
	function value($row,$col,$sheet=0) {
		return $this->val($row,$col,$sheet);
	}
	function info($row,$col,$type='',$sheet=0) {
		$col = $this->getCol($col);
		if (array_key_exists('cellsInfo',$this->sheets[$sheet])
				&& array_key_exists($row,$this->sheets[$sheet]['cellsInfo'])
				&& array_key_exists($col,$this->sheets[$sheet]['cellsInfo'][$row])
				&& array_key_exists($type,$this->sheets[$sheet]['cellsInfo'][$row][$col])) {
			return $this->sheets[$sheet]['cellsInfo'][$row][$col][$type];
		}
		return "";
	}
	function type($row,$col,$sheet=0) {
		return $this->info($row,$col,'type',$sheet);
	}
	function raw($row,$col,$sheet=0) {
		return $this->info($row,$col,'raw',$sheet);
	}
	function rowspan($row,$col,$sheet=0) {
		$val = $this->info($row,$col,'rowspan',$sheet);
		if ($val=="") { return 1; }
		return $val;
	}
	function colspan($row,$col,$sheet=0) {
		$val = $this->info($row,$col,'colspan',$sheet);
		if ($val=="") { return 1; }
		return $val;
	}
	function hyperlink($row,$col,$sheet=0) {
		$link = $this->sheets[$sheet]['cellsInfo'][$row][$col]['hyperlink'];
		if ($link) {
			return $link['link'];
		}
		return '';
	}
	function rowcount($sheet=0) {
		return $this->sheets[$sheet]['numRows'];
	}
	function colcount($sheet=0) {
		return $this->sheets[$sheet]['numCols'];
	}
	function colwidth($col,$sheet=0) {
		// Col width is actually the width of the number 0. So we have to estimate and come close
		return $this->colInfo[$sheet][$col]['width']/9142*200;
	}
	function colhidden($col,$sheet=0) {
		return !!$this->colInfo[$sheet][$col]['hidden'];
	}
	function rowheight($row,$sheet=0) {
		return $this->rowInfo[$sheet][$row]['height'];
	}
	function rowhidden($row,$sheet=0) {
		return !!$this->rowInfo[$sheet][$row]['hidden'];
	}

	// GET THE CSS FOR FORMATTING
	// ==========================
	function style($row,$col,$sheet=0,$properties='') {
		$css = "";
		$font=$this->font($row,$col,$sheet);
		if ($font!="") {
			$css .= "font-family:$font;";
		}
		$align=$this->align($row,$col,$sheet);
		if ($align!="") {
			$css .= "text-align:$align;";
		}
		$height=$this->height($row,$col,$sheet);
		if ($height!="") {
			$css .= "font-size:$height"."px;";
		}
		$bgcolor=$this->bgColor($row,$col,$sheet);
		if ($bgcolor!="") {
			$bgcolor = $this->colors[$bgcolor];
			$css .= "background-color:$bgcolor;";
		}
		$color=$this->color($row,$col,$sheet);
		if ($color!="") {
			$css .= "color:$color;";
		}
		$bold=$this->bold($row,$col,$sheet);
		if ($bold) {
			$css .= "font-weight:bold;";
		}
		$italic=$this->italic($row,$col,$sheet);
		if ($italic) {
			$css .= "font-style:italic;";
		}
		$underline=$this->underline($row,$col,$sheet);
		if ($underline) {
			$css .= "text-decoration:underline;";
		}
		// Borders
		$bLeft = $this->borderLeft($row,$col,$sheet);
		$bRight = $this->borderRight($row,$col,$sheet);
		$bTop = $this->borderTop($row,$col,$sheet);
		$bBottom = $this->borderBottom($row,$col,$sheet);
		$bLeftCol = $this->borderLeftColor($row,$col,$sheet);
		$bRightCol = $this->borderRightColor($row,$col,$sheet);
		$bTopCol = $this->borderTopColor($row,$col,$sheet);
		$bBottomCol = $this->borderBottomColor($row,$col,$sheet);
		// Try to output the minimal required style
		if ($bLeft!="" && $bLeft==$bRight && $bRight==$bTop && $bTop==$bBottom) {
			$css .= "border:" . $this->lineStylesCss[$bLeft] .";";
		}
		else {
			if ($bLeft!="") { $css .= "border-left:" . $this->lineStylesCss[$bLeft] .";"; }
			if ($bRight!="") { $css .= "border-right:" . $this->lineStylesCss[$bRight] .";"; }
			if ($bTop!="") { $css .= "border-top:" . $this->lineStylesCss[$bTop] .";"; }
			if ($bBottom!="") { $css .= "border-bottom:" . $this->lineStylesCss[$bBottom] .";"; }
		}
		// Only output border colors if there is an actual border specified
		if ($bLeft!="" && $bLeftCol!="") { $css .= "border-left-color:" . $bLeftCol .";"; }
		if ($bRight!="" && $bRightCol!="") { $css .= "border-right-color:" . $bRightCol .";"; }
		if ($bTop!="" && $bTopCol!="") { $css .= "border-top-color:" . $bTopCol . ";"; }
		if ($bBottom!="" && $bBottomCol!="") { $css .= "border-bottom-color:" . $bBottomCol .";"; }

		return $css;
	}

	// FORMAT PROPERTIES
	// =================
	function format($row,$col,$sheet=0) {
		return $this->info($row,$col,'format',$sheet);
	}
	function formatIndex($row,$col,$sheet=0) {
		return $this->info($row,$col,'formatIndex',$sheet);
	}
	function formatColor($row,$col,$sheet=0) {
		return $this->info($row,$col,'formatColor',$sheet);
	}

	// CELL (XF) PROPERTIES
	// ====================
	function xfRecord($row,$col,$sheet=0) {
		$xfIndex = $this->info($row,$col,'xfIndex',$sheet);
		if ($xfIndex!="") {
			return $this->xfRecords[$xfIndex];
		}
		return null;
	}
	function xfProperty($row,$col,$sheet,$prop) {
		$xfRecord = $this->xfRecord($row,$col,$sheet);
		if ($xfRecord!=null) {
			return $xfRecord[$prop];
		}
		return "";
	}
	function align($row,$col,$sheet=0) {
		return $this->xfProperty($row,$col,$sheet,'align');
	}
	function bgColor($row,$col,$sheet=0) {
		return $this->xfProperty($row,$col,$sheet,'bgColor');
	}
	function borderLeft($row,$col,$sheet=0) {
		return $this->xfProperty($row,$col,$sheet,'borderLeft');
	}
	function borderRight($row,$col,$sheet=0) {
		return $this->xfProperty($row,$col,$sheet,'borderRight');
	}
	function borderTop($row,$col,$sheet=0) {
		return $this->xfProperty($row,$col,$sheet,'borderTop');
	}
	function borderBottom($row,$col,$sheet=0) {
		return $this->xfProperty($row,$col,$sheet,'borderBottom');
	}
	function borderLeftColor($row,$col,$sheet=0) {
		return $this->colors[$this->xfProperty($row,$col,$sheet,'borderLeftColor')];
	}
	function borderRightColor($row,$col,$sheet=0) {
		return $this->colors[$this->xfProperty($row,$col,$sheet,'borderRightColor')];
	}
	function borderTopColor($row,$col,$sheet=0) {
		return $this->colors[$this->xfProperty($row,$col,$sheet,'borderTopColor')];
	}
	function borderBottomColor($row,$col,$sheet=0) {
		return $this->colors[$this->xfProperty($row,$col,$sheet,'borderBottomColor')];
	}

	// FONT PROPERTIES
	// ===============
	function fontRecord($row,$col,$sheet=0) {
	    $xfRecord = $this->xfRecord($row,$col,$sheet);
		if ($xfRecord!=null) {
			$font = $xfRecord['fontIndex'];
			if ($font!=null) {
				return $this->fontRecords[$font];
			}
		}
		return null;
	}
	function fontProperty($row,$col,$sheet=0,$prop) {
		$font = $this->fontRecord($row,$col,$sheet);
		if ($font!=null) {
			return $font[$prop];
		}
		return false;
	}
	function fontIndex($row,$col,$sheet=0) {
		return $this->xfProperty($row,$col,$sheet,'fontIndex');
	}
	function color($row,$col,$sheet=0) {
		$formatColor = $this->formatColor($row,$col,$sheet);
		if ($formatColor!="") {
			return $formatColor;
		}
		$ci = $this->fontProperty($row,$col,$sheet,'color');
                return $this->rawColor($ci);
        }
        function rawColor($ci) {
		if (($ci <> 0x7FFF) && ($ci <> '')) {
			return $this->colors[$ci];
		}
		return "";
	}
	function bold($row,$col,$sheet=0) {
		return $this->fontProperty($row,$col,$sheet,'bold');
	}
	function italic($row,$col,$sheet=0) {
		return $this->fontProperty($row,$col,$sheet,'italic');
	}
	function underline($row,$col,$sheet=0) {
		return $this->fontProperty($row,$col,$sheet,'under');
	}
	function height($row,$col,$sheet=0) {
		return $this->fontProperty($row,$col,$sheet,'height');
	}
	function font($row,$col,$sheet=0) {
		return $this->fontProperty($row,$col,$sheet,'font');
	}

	// DUMP AN HTML TABLE OF THE ENTIRE XLS DATA
	// =========================================
	function dump($row_numbers=false,$col_letters=false,$sheet=0,$table_class='excel') {
		$out = "<table class=\"$table_class\" cellspacing=0>";
		if ($col_letters) {
			$out .= "<thead>\n\t<tr>";
			if ($row_numbers) {
				$out .= "\n\t\t<th>&nbsp</th>";
			}
			for($i=1;$i<=$this->colcount($sheet);$i++) {
				$style = "width:" . ($this->colwidth($i,$sheet)*1) . "px;";
				if ($this->colhidden($i,$sheet)) {
					$style .= "display:none;";
				}
				$out .= "\n\t\t<th style=\"$style\">" . strtoupper($this->colindexes[$i]) . "</th>";
			}
			$out .= "</tr></thead>\n";
		}

		$out .= "<tbody>\n";
		for($row=1;$row<=$this->rowcount($sheet);$row++) {
			$rowheight = $this->rowheight($row,$sheet);
			$style = "height:" . ($rowheight*(4/3)) . "px;";
			if ($this->rowhidden($row,$sheet)) {
				$style .= "display:none;";
			}
			$out .= "\n\t<tr style=\"$style\">";
			if ($row_numbers) {
				$out .= "\n\t\t<th>$row</th>";
			}
			for($col=1;$col<=$this->colcount($sheet);$col++) {
				// Account for Rowspans/Colspans
				$rowspan = $this->rowspan($row,$col,$sheet);
				$colspan = $this->colspan($row,$col,$sheet);
				for($i=0;$i<$rowspan;$i++) {
					for($j=0;$j<$colspan;$j++) {
						if ($i>0 || $j>0) {
							$this->sheets[$sheet]['cellsInfo'][$row+$i][$col+$j]['dontprint']=1;
						}
					}
				}
				if(!$this->sheets[$sheet]['cellsInfo'][$row][$col]['dontprint']) {
					$style = $this->style($row,$col,$sheet);
					if ($this->colhidden($col,$sheet)) {
						$style .= "display:none;";
					}
					$out .= "\n\t\t<td style=\"$style\"" . ($colspan > 1?" colspan=$colspan":"") . ($rowspan > 1?" rowspan=$rowspan":"") . ">";
					$val = $this->val($row,$col,$sheet);
					if ($val=='') { $val="&nbsp;"; }
					else {
						$val = htmlentities($val);
						$link = $this->hyperlink($row,$col,$sheet);
						if ($link!='') {
							$val = "<a href=\"$link\">$val</a>";
						}
					}
					$out .= "<nobr>".nl2br($val)."</nobr>";
					$out .= "</td>";
				}
			}
			$out .= "</tr>\n";
		}
		$out .= "</tbody></table>";
		return $out;
	}

	// --------------
	// END PUBLIC API


	var $boundsheets = array();
	var $formatRecords = array();
	var $fontRecords = array();
	var $xfRecords = array();
	var $colInfo = array();
   	var $rowInfo = array();

	var $sst = array();
	var $sheets = array();

	var $data;
	var $_ole;
	var $_defaultEncoding = "UTF-8";
	var $_defaultFormat = SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT;
	var $_columnsFormat = array();
	var $_rowoffset = 1;
	var $_coloffset = 1;

	/**
	 * List of default date formats used by Excel
	 */
	var $dateFormats = array (
		0xe => "m/d/Y",
		0xf => "M-d-Y",
		0x10 => "d-M",
		0x11 => "M-Y",
		0x12 => "h:i a",
		0x13 => "h:i:s a",
		0x14 => "H:i",
		0x15 => "H:i:s",
		0x16 => "d/m/Y H:i",
		0x2d => "i:s",
		0x2e => "H:i:s",
		0x2f => "i:s.S"
	);

	/**
	 * Default number formats used by Excel
	 */
	var $numberFormats = array(
		0x1 => "0",
		0x2 => "0.00",
		0x3 => "#,##0",
		0x4 => "#,##0.00",
		0x5 => "\$#,##0;(\$#,##0)",
		0x6 => "\$#,##0;[Red](\$#,##0)",
		0x7 => "\$#,##0.00;(\$#,##0.00)",
		0x8 => "\$#,##0.00;[Red](\$#,##0.00)",
		0x9 => "0%",
		0xa => "0.00%",
		0xb => "0.00E+00",
		0x25 => "#,##0;(#,##0)",
		0x26 => "#,##0;[Red](#,##0)",
		0x27 => "#,##0.00;(#,##0.00)",
		0x28 => "#,##0.00;[Red](#,##0.00)",
		0x29 => "#,##0;(#,##0)",  // Not exactly
		0x2a => "\$#,##0;(\$#,##0)",  // Not exactly
		0x2b => "#,##0.00;(#,##0.00)",  // Not exactly
		0x2c => "\$#,##0.00;(\$#,##0.00)",  // Not exactly
		0x30 => "##0.0E+0"
	);

    var $colors = Array(
        0x00 => "#000000",
        0x01 => "#FFFFFF",
        0x02 => "#FF0000",
        0x03 => "#00FF00",
        0x04 => "#0000FF",
        0x05 => "#FFFF00",
        0x06 => "#FF00FF",
        0x07 => "#00FFFF",
        0x08 => "#000000",
        0x09 => "#FFFFFF",
        0x0A => "#FF0000",
        0x0B => "#00FF00",
        0x0C => "#0000FF",
        0x0D => "#FFFF00",
        0x0E => "#FF00FF",
        0x0F => "#00FFFF",
        0x10 => "#800000",
        0x11 => "#008000",
        0x12 => "#000080",
        0x13 => "#808000",
        0x14 => "#800080",
        0x15 => "#008080",
        0x16 => "#C0C0C0",
        0x17 => "#808080",
        0x18 => "#9999FF",
        0x19 => "#993366",
        0x1A => "#FFFFCC",
        0x1B => "#CCFFFF",
        0x1C => "#660066",
        0x1D => "#FF8080",
        0x1E => "#0066CC",
        0x1F => "#CCCCFF",
        0x20 => "#000080",
        0x21 => "#FF00FF",
        0x22 => "#FFFF00",
        0x23 => "#00FFFF",
        0x24 => "#800080",
        0x25 => "#800000",
        0x26 => "#008080",
        0x27 => "#0000FF",
        0x28 => "#00CCFF",
        0x29 => "#CCFFFF",
        0x2A => "#CCFFCC",
        0x2B => "#FFFF99",
        0x2C => "#99CCFF",
        0x2D => "#FF99CC",
        0x2E => "#CC99FF",
        0x2F => "#FFCC99",
        0x30 => "#3366FF",
        0x31 => "#33CCCC",
        0x32 => "#99CC00",
        0x33 => "#FFCC00",
        0x34 => "#FF9900",
        0x35 => "#FF6600",
        0x36 => "#666699",
        0x37 => "#969696",
        0x38 => "#003366",
        0x39 => "#339966",
        0x3A => "#003300",
        0x3B => "#333300",
        0x3C => "#993300",
        0x3D => "#993366",
        0x3E => "#333399",
        0x3F => "#333333",
        0x40 => "#000000",
        0x41 => "#FFFFFF",

        0x43 => "#000000",
        0x4D => "#000000",
        0x4E => "#FFFFFF",
        0x4F => "#000000",
        0x50 => "#FFFFFF",
        0x51 => "#000000",

        0x7FFF => "#000000"
    );

	var $lineStyles = array(
		0x00 => "",
		0x01 => "Thin",
		0x02 => "Medium",
		0x03 => "Dashed",
		0x04 => "Dotted",
		0x05 => "Thick",
		0x06 => "Double",
		0x07 => "Hair",
		0x08 => "Medium dashed",
		0x09 => "Thin dash-dotted",
		0x0A => "Medium dash-dotted",
		0x0B => "Thin dash-dot-dotted",
		0x0C => "Medium dash-dot-dotted",
		0x0D => "Slanted medium dash-dotted"
	);

	var $lineStylesCss = array(
		"Thin" => "1px solid",
		"Medium" => "2px solid",
		"Dashed" => "1px dashed",
		"Dotted" => "1px dotted",
		"Thick" => "3px solid",
		"Double" => "double",
		"Hair" => "1px solid",
		"Medium dashed" => "2px dashed",
		"Thin dash-dotted" => "1px dashed",
		"Medium dash-dotted" => "2px dashed",
		"Thin dash-dot-dotted" => "1px dashed",
		"Medium dash-dot-dotted" => "2px dashed",
		"Slanted medium dash-dotte" => "2px dashed"
	);

	function read16bitstring($data, $start) {
		$len = 0;
		while (ord($data[$start + $len]) + ord($data[$start + $len + 1]) > 0) $len++;
		return substr($data, $start, $len);
	}

	// ADDED by Matt Kruse for better formatting
	function _format_value($format,$num,$f) {
		// 49==TEXT format
		// http://code.google.com/p/php-excel-reader/issues/detail?id=7
		if ( (!$f && $format=="%s") || ($f==49) || ($format=="GENERAL") ) {
			return array('string'=>$num, 'formatColor'=>null);
		}

		// Custom pattern can be POSITIVE;NEGATIVE;ZERO
		// The "text" option as 4th parameter is not handled
		$parts = split(";",$format);
		$pattern = $parts[0];
		// Negative pattern
		if (count($parts)>2 && $num==0) {
			$pattern = $parts[2];
		}
		// Zero pattern
		if (count($parts)>1 && $num<0) {
			$pattern = $parts[1];
			$num = abs($num);
		}

		$color = "";
		$matches = array();
		$color_regex = "/^\[(BLACK|BLUE|CYAN|GREEN|MAGENTA|RED|WHITE|YELLOW)\]/i";
		if (preg_match($color_regex,$pattern,$matches)) {
			$color = strtolower($matches[1]);
			$pattern = preg_replace($color_regex,"",$pattern);
		}

		// In Excel formats, "_" is used to add spacing, which we can't do in HTML
		$pattern = preg_replace("/_./","",$pattern);

		// Some non-number characters are escaped with \, which we don't need
		$pattern = preg_replace("/\\\/","",$pattern);

		// Some non-number strings are quoted, so we'll get rid of the quotes
		$pattern = preg_replace("/\"/","",$pattern);

		// TEMPORARY - Convert # to 0
		$pattern = preg_replace("/\#/","0",$pattern);

		// Find out if we need comma formatting
		$has_commas = preg_match("/,/",$pattern);
		if ($has_commas) {
			$pattern = preg_replace("/,/","",$pattern);
		}

		// Handle Percentages
		if (preg_match("/\d(\%)([^\%]|$)/",$pattern,$matches)) {
			$num = $num * 100;
			$pattern = preg_replace("/(\d)(\%)([^\%]|$)/","$1%$3",$pattern);
		}

		// Handle the number itself
		$number_regex = "/(\d+)(\.?)(\d*)/";
		if (preg_match($number_regex,$pattern,$matches)) {
			$left = $matches[1];
			$dec = $matches[2];
			$right = $matches[3];
			if ($has_commas) {
				$formatted = number_format($num,strlen($right));
			}
			else {
				$sprintf_pattern = "%1.".strlen($right)."f";
				$formatted = sprintf($sprintf_pattern, $num);
			}
			$pattern = preg_replace($number_regex, $formatted, $pattern);
		}

		return array(
			'string'=>$pattern,
			'formatColor'=>$color
		);
	}

	/**
	 * Constructor
	 *
	 * Some basic initialisation
	 */
	function Spreadsheet_Excel_Reader($file='',$store_extended_info=true,$outputEncoding='') {
		$this->_ole = new OLERead();
		$this->setUTFEncoder('iconv');
		if ($outputEncoding != '') {
			$this->setOutputEncoding($outputEncoding);
		}
		for ($i=1; $i<245; $i++) {
			$name = strtolower(( (($i-1)/26>=1)?chr(($i-1)/26+64):'') . chr(($i-1)%26+65));
			$this->colnames[$name] = $i;
			$this->colindexes[$i] = $name;
		}
		$this->store_extended_info = $store_extended_info;
		if ($file!="") {
			$this->read($file);
		}
	}

	/**
	 * Set the encoding method
	 */
	function setOutputEncoding($encoding) {
		$this->_defaultEncoding = $encoding;
	}

	/**
	 *  $encoder = 'iconv' or 'mb'
	 *  set iconv if you would like use 'iconv' for encode UTF-16LE to your encoding
	 *  set mb if you would like use 'mb_convert_encoding' for encode UTF-16LE to your encoding
	 */
	function setUTFEncoder($encoder = 'iconv') {
		$this->_encoderFunction = '';
		if ($encoder == 'iconv') {
			$this->_encoderFunction = function_exists('iconv') ? 'iconv' : '';
		} elseif ($encoder == 'mb') {
			$this->_encoderFunction = function_exists('mb_convert_encoding') ? 'mb_convert_encoding' : '';
		}
	}

	function setRowColOffset($iOffset) {
		$this->_rowoffset = $iOffset;
		$this->_coloffset = $iOffset;
	}

	/**
	 * Set the default number format
	 */
	function setDefaultFormat($sFormat) {
		$this->_defaultFormat = $sFormat;
	}

	/**
	 * Force a column to use a certain format
	 */
	function setColumnFormat($column, $sFormat) {
		$this->_columnsFormat[$column] = $sFormat;
	}

	/**
	 * Read the spreadsheet file using OLE, then parse
	 */
	function read($sFileName) {
		$res = $this->_ole->read($sFileName);

		// oops, something goes wrong (Darko Miljanovic)
		if($res === false) {
			// check error code
			if($this->_ole->error == 1) {
				// bad file
				die('The filename ' . $sFileName . ' is not readable');
			}
			// check other error codes here (eg bad fileformat, etc...)
		}
		$this->data = $this->_ole->getWorkBook();
		$this->_parse();
	}

	/**
	 * Parse a workbook
	 *
	 * @access private
	 * @return bool
	 */
	function _parse() {
		$pos = 0;
		$data = $this->data;

		$code = v($data,$pos);
		$length = v($data,$pos+2);
		$version = v($data,$pos+4);
		$substreamType = v($data,$pos+6);

		$this->version = $version;

		if (($version != SPREADSHEET_EXCEL_READER_BIFF8) &&
			($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
			return false;
		}

		if ($substreamType != SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS){
			return false;
		}

		$pos += $length + 4;

		$code = v($data,$pos);
		$length = v($data,$pos+2);

		while ($code != SPREADSHEET_EXCEL_READER_TYPE_EOF) {
			switch ($code) {
				case SPREADSHEET_EXCEL_READER_TYPE_SST:
					$spos = $pos + 4;
					$limitpos = $spos + $length;
					$uniqueStrings = $this->_GetInt4d($data, $spos+4);
					$spos += 8;
					for ($i = 0; $i < $uniqueStrings; $i++) {
						// Read in the number of characters
						if ($spos == $limitpos) {
							$opcode = v($data,$spos);
							$conlength = v($data,$spos+2);
							if ($opcode != 0x3c) {
								return -1;
							}
							$spos += 4;
							$limitpos = $spos + $conlength;
						}
						$numChars = ord($data[$spos]) | (ord($data[$spos+1]) << 8);
						$spos += 2;
						$optionFlags = ord($data[$spos]);
						$spos++;
						$asciiEncoding = (($optionFlags & 0x01) == 0) ;
						$extendedString = ( ($optionFlags & 0x04) != 0);

						// See if string contains formatting information
						$richString = ( ($optionFlags & 0x08) != 0);

						if ($richString) {
							// Read in the crun
							$formattingRuns = v($data,$spos);
							$spos += 2;
						}

						if ($extendedString) {
							// Read in cchExtRst
							$extendedRunLength = $this->_GetInt4d($data, $spos);
							$spos += 4;
						}

						$len = ($asciiEncoding)? $numChars : $numChars*2;
						if ($spos + $len < $limitpos) {
							$retstr = substr($data, $spos, $len);
							$spos += $len;
						}
						else{
							// found countinue
							$retstr = substr($data, $spos, $limitpos - $spos);
							$bytesRead = $limitpos - $spos;
							$charsLeft = $numChars - (($asciiEncoding) ? $bytesRead : ($bytesRead / 2));
							$spos = $limitpos;

							while ($charsLeft > 0){
								$opcode = v($data,$spos);
								$conlength = v($data,$spos+2);
								if ($opcode != 0x3c) {
									return -1;
								}
								$spos += 4;
								$limitpos = $spos + $conlength;
								$option = ord($data[$spos]);
								$spos += 1;
								if ($asciiEncoding && ($option == 0)) {
									$len = min($charsLeft, $limitpos - $spos); // min($charsLeft, $conlength);
									$retstr .= substr($data, $spos, $len);
									$charsLeft -= $len;
									$asciiEncoding = true;
								}
								elseif (!$asciiEncoding && ($option != 0)) {
									$len = min($charsLeft * 2, $limitpos - $spos); // min($charsLeft, $conlength);
									$retstr .= substr($data, $spos, $len);
									$charsLeft -= $len/2;
									$asciiEncoding = false;
								}
								elseif (!$asciiEncoding && ($option == 0)) {
									// Bummer - the string starts off as Unicode, but after the
									// continuation it is in straightforward ASCII encoding
									$len = min($charsLeft, $limitpos - $spos); // min($charsLeft, $conlength);
									for ($j = 0; $j < $len; $j++) {
										$retstr .= $data[$spos + $j].chr(0);
									}
									$charsLeft -= $len;
									$asciiEncoding = false;
								}
								else{
									$newstr = '';
									for ($j = 0; $j < strlen($retstr); $j++) {
										$newstr = $retstr[$j].chr(0);
									}
									$retstr = $newstr;
									$len = min($charsLeft * 2, $limitpos - $spos); // min($charsLeft, $conlength);
									$retstr .= substr($data, $spos, $len);
									$charsLeft -= $len/2;
									$asciiEncoding = false;
								}
								$spos += $len;
							}
						}
						$retstr = ($asciiEncoding) ? $retstr : $this->_encodeUTF16($retstr);

						if ($richString){
							$spos += 4 * $formattingRuns;
						}

						// For extended strings, skip over the extended string data
						if ($extendedString) {
							$spos += $extendedRunLength;
						}
						$this->sst[]=$retstr;
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_FILEPASS:
					return false;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_NAME:
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_FORMAT:
					$indexCode = v($data,$pos+4);
					if ($version == SPREADSHEET_EXCEL_READER_BIFF8) {
						$numchars = v($data,$pos+6);
						if (ord($data[$pos+8]) == 0){
							$formatString = substr($data, $pos+9, $numchars);
						} else {
							$formatString = substr($data, $pos+9, $numchars*2);
						}
					} else {
						$numchars = ord($data[$pos+6]);
						$formatString = substr($data, $pos+7, $numchars*2);
					}
					$this->formatRecords[$indexCode] = $formatString;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_FONT:
						$height = v($data,$pos+4);
						$option = v($data,$pos+6);
						$color = v($data,$pos+8);
						$weight = v($data,$pos+10);
						$under  = ord($data[$pos+14]);
						$font = "";
						// Font name
						$numchars = ord($data[$pos+18]);
						if ((ord($data[$pos+19]) & 1) == 0){
						    $font = substr($data, $pos+20, $numchars);
						} else {
						    $font = substr($data, $pos+20, $numchars*2);
						    $font =  $this->_encodeUTF16($font);
						}
						$this->fontRecords[] = array(
								'height' => $height / 20,
								'italic' => !!($option & 2),
								'color' => $color,
								'under' => !($under==0),
								'bold' => ($weight==700),
								'font' => $font,
								'raw' => $this->dumpHexData($data, $pos+3, $length)
								);
					    break;

				case SPREADSHEET_EXCEL_READER_TYPE_PALETTE:
						$colors = ord($data[$pos+4]) | ord($data[$pos+5]) << 8;
						for ($coli = 0; $coli < $colors; $coli++) {
						    $colOff = $pos + 2 + ($coli * 4);
  						    $colr = ord($data[$colOff]);
  						    $colg = ord($data[$colOff+1]);
  						    $colb = ord($data[$colOff+2]);
							$this->colors[0x07 + $coli] = '#' . $this->myhex($colr) . $this->myhex($colg) . $this->myhex($colb);
						}
					    break;

				case SPREADSHEET_EXCEL_READER_TYPE_XF:
						$fontIndexCode = (ord($data[$pos+4]) | ord($data[$pos+5]) << 8) - 1;
						$fontIndexCode = max(0,$fontIndexCode);
						$indexCode = ord($data[$pos+6]) | ord($data[$pos+7]) << 8;
						$alignbit = ord($data[$pos+10]) & 3;
						$bgi = (ord($data[$pos+22]) | ord($data[$pos+23]) << 8) & 0x3FFF;
						$bgcolor = ($bgi & 0x7F);
//						$bgcolor = ($bgi & 0x3f80) >> 7;
						$align = "";
						if ($alignbit==3) { $align="right"; }
						if ($alignbit==2) { $align="center"; }

						$fillPattern = (ord($data[$pos+21]) & 0xFC) >> 2;
						if ($fillPattern == 0) {
							$bgcolor = "";
						}

						$xf = array();
						$xf['formatIndex'] = $indexCode;
						$xf['align'] = $align;
						$xf['fontIndex'] = $fontIndexCode;
						$xf['bgColor'] = $bgcolor;
						$xf['fillPattern'] = $fillPattern;

						$border = ord($data[$pos+14]) | (ord($data[$pos+15]) << 8) | (ord($data[$pos+16]) << 16) | (ord($data[$pos+17]) << 24);
						$xf['borderLeft'] = $this->lineStyles[($border & 0xF)];
						$xf['borderRight'] = $this->lineStyles[($border & 0xF0) >> 4];
						$xf['borderTop'] = $this->lineStyles[($border & 0xF00) >> 8];
						$xf['borderBottom'] = $this->lineStyles[($border & 0xF000) >> 12];

						$xf['borderLeftColor'] = ($border & 0x7F0000) >> 16;
						$xf['borderRightColor'] = ($border & 0x3F800000) >> 23;
						$border = (ord($data[$pos+18]) | ord($data[$pos+19]) << 8);

						$xf['borderTopColor'] = ($border & 0x7F);
						$xf['borderBottomColor'] = ($border & 0x3F80) >> 7;

						if (array_key_exists($indexCode, $this->dateFormats)) {
							$xf['type'] = 'date';
							$xf['format'] = $this->dateFormats[$indexCode];
							if ($align=='') { $xf['align'] = 'right'; }
						}elseif (array_key_exists($indexCode, $this->numberFormats)) {
							$xf['type'] = 'number';
							$xf['format'] = $this->numberFormats[$indexCode];
							if ($align=='') { $xf['align'] = 'right'; }
						}else{
							$isdate = FALSE;
							$formatstr = '';
							if ($indexCode > 0){
								if (isset($this->formatRecords[$indexCode]))
									$formatstr = $this->formatRecords[$indexCode];
								if ($formatstr!="") {
									$tmp = preg_replace("/\;.*/","",$formatstr);
									$tmp = preg_replace("/^\[[^\]]*\]/","",$tmp);
									if (preg_match("/[^hmsday\/\-:\s\\\,AMP]/i", $tmp) == 0) { // found day and time format
										$isdate = TRUE;
										$formatstr = $tmp;
										$formatstr = str_replace(array('AM/PM','mmmm','mmm'), array('a','F','M'), $formatstr);
										// m/mm are used for both minutes and months - oh SNAP!
										// This mess tries to fix for that.
										// 'm' == minutes only if following h/hh or preceding s/ss
										$formatstr = preg_replace("/(h:?)mm?/","$1i", $formatstr);
										$formatstr = preg_replace("/mm?(:?s)/","i$1", $formatstr);
										// A single 'm' = n in PHP
										$formatstr = preg_replace("/(^|[^m])m([^m]|$)/", '$1n$2', $formatstr);
										$formatstr = preg_replace("/(^|[^m])m([^m]|$)/", '$1n$2', $formatstr);
										// else it's months
										$formatstr = str_replace('mm', 'm', $formatstr);
										// Convert single 'd' to 'j'
										$formatstr = preg_replace("/(^|[^d])d([^d]|$)/", '$1j$2', $formatstr);
										$formatstr = str_replace(array('dddd','ddd','dd','yyyy','yy','hh','h'), array('l','D','d','Y','y','H','g'), $formatstr);
										$formatstr = preg_replace("/ss?/", 's', $formatstr);
									}
								}
							}
							if ($isdate){
								$xf['type'] = 'date';
								$xf['format'] = $formatstr;
								if ($align=='') { $xf['align'] = 'right'; }
							}else{
								// If the format string has a 0 or # in it, we'll assume it's a number
								if (preg_match("/[0#]/", $formatstr)) {
									$xf['type'] = 'number';
									if ($align=='') { $xf['align']='right'; }
								}
								else {
								$xf['type'] = 'other';
								}
								$xf['format'] = $formatstr;
								$xf['code'] = $indexCode;
							}
						}
						$this->xfRecords[] = $xf;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR:
					$this->nineteenFour = (ord($data[$pos+4]) == 1);
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET:
						$rec_offset = $this->_GetInt4d($data, $pos+4);
						$rec_typeFlag = ord($data[$pos+8]);
						$rec_visibilityFlag = ord($data[$pos+9]);
						$rec_length = ord($data[$pos+10]);

						if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
							$chartype =  ord($data[$pos+11]);
							if ($chartype == 0){
								$rec_name	= substr($data, $pos+12, $rec_length);
							} else {
								$rec_name	= $this->_encodeUTF16(substr($data, $pos+12, $rec_length*2));
							}
						}elseif ($version == SPREADSHEET_EXCEL_READER_BIFF7){
								$rec_name	= substr($data, $pos+11, $rec_length);
						}
					$this->boundsheets[] = array('name'=>$rec_name,'offset'=>$rec_offset);
					break;

			}

			$pos += $length + 4;
			$code = ord($data[$pos]) | ord($data[$pos+1])<<8;
			$length = ord($data[$pos+2]) | ord($data[$pos+3])<<8;
		}

		foreach ($this->boundsheets as $key=>$val){
			$this->sn = $key;
			$this->_parsesheet($val['offset']);
		}
		return true;
	}

	/**
	 * Parse a worksheet
	 */
	function _parsesheet($spos) {
		$cont = true;
		$data = $this->data;
		// read BOF
		$code = ord($data[$spos]) | ord($data[$spos+1])<<8;
		$length = ord($data[$spos+2]) | ord($data[$spos+3])<<8;

		$version = ord($data[$spos + 4]) | ord($data[$spos + 5])<<8;
		$substreamType = ord($data[$spos + 6]) | ord($data[$spos + 7])<<8;

		if (($version != SPREADSHEET_EXCEL_READER_BIFF8) && ($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
			return -1;
		}

		if ($substreamType != SPREADSHEET_EXCEL_READER_WORKSHEET){
			return -2;
		}
		$spos += $length + 4;
		while($cont) {
			$lowcode = ord($data[$spos]);
			if ($lowcode == SPREADSHEET_EXCEL_READER_TYPE_EOF) break;
			$code = $lowcode | ord($data[$spos+1])<<8;
			$length = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
			$spos += 4;
			$this->sheets[$this->sn]['maxrow'] = $this->_rowoffset - 1;
			$this->sheets[$this->sn]['maxcol'] = $this->_coloffset - 1;
			unset($this->rectype);
			switch ($code) {
				case SPREADSHEET_EXCEL_READER_TYPE_DIMENSION:
					if (!isset($this->numRows)) {
						if (($length == 10) ||  ($version == SPREADSHEET_EXCEL_READER_BIFF7)){
							$this->sheets[$this->sn]['numRows'] = ord($data[$spos+2]) | ord($data[$spos+3]) << 8;
							$this->sheets[$this->sn]['numCols'] = ord($data[$spos+6]) | ord($data[$spos+7]) << 8;
						} else {
							$this->sheets[$this->sn]['numRows'] = ord($data[$spos+4]) | ord($data[$spos+5]) << 8;
							$this->sheets[$this->sn]['numCols'] = ord($data[$spos+10]) | ord($data[$spos+11]) << 8;
						}
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS:
					$cellRanges = ord($data[$spos]) | ord($data[$spos+1])<<8;
					for ($i = 0; $i < $cellRanges; $i++) {
						$fr =  ord($data[$spos + 8*$i + 2]) | ord($data[$spos + 8*$i + 3])<<8;
						$lr =  ord($data[$spos + 8*$i + 4]) | ord($data[$spos + 8*$i + 5])<<8;
						$fc =  ord($data[$spos + 8*$i + 6]) | ord($data[$spos + 8*$i + 7])<<8;
						$lc =  ord($data[$spos + 8*$i + 8]) | ord($data[$spos + 8*$i + 9])<<8;
						if ($lr - $fr > 0) {
							$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['rowspan'] = $lr - $fr + 1;
						}
						if ($lc - $fc > 0) {
							$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['colspan'] = $lc - $fc + 1;
						}
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_RK:
				case SPREADSHEET_EXCEL_READER_TYPE_RK2:
					$row = ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$rknum = $this->_GetInt4d($data, $spos + 6);
					$numValue = $this->_GetIEEE754($rknum);
					$info = $this->_getCellDetails($spos,$numValue,$column);
					$this->addcell($row, $column, $info['string'],$info);
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_LABELSST:
					$row		= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column	 = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$xfindex	= ord($data[$spos+4]) | ord($data[$spos+5])<<8;
					$index  = $this->_GetInt4d($data, $spos + 6);
					$this->addcell($row, $column, $this->sst[$index], array('xfIndex'=>$xfindex) );
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_MULRK:
					$row		= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$colFirst   = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$colLast	= ord($data[$spos + $length - 2]) | ord($data[$spos + $length - 1])<<8;
					$columns	= $colLast - $colFirst + 1;
					$tmppos = $spos+4;
					for ($i = 0; $i < $columns; $i++) {
						$numValue = $this->_GetIEEE754($this->_GetInt4d($data, $tmppos + 2));
						$info = $this->_getCellDetails($tmppos-4,$numValue,$colFirst + $i + 1);
						$tmppos += 6;
						$this->addcell($row, $colFirst + $i, $info['string'], $info);
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_NUMBER:
					$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$tmp = unpack("ddouble", substr($data, $spos + 6, 8)); // It machine machine dependent
					if ($this->isDate($spos)) {
						$numValue = $tmp['double'];
					}
					else {
						$numValue = $this->createNumber($spos);
					}
					$info = $this->_getCellDetails($spos,$numValue,$column);
					$this->addcell($row, $column, $info['string'], $info);
					break;

				case SPREADSHEET_EXCEL_READER_TYPE_FORMULA:
				case SPREADSHEET_EXCEL_READER_TYPE_FORMULA2:
					$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					if ((ord($data[$spos+6])==0) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
						//String formula. Result follows in a STRING record
						// This row/col are stored to be referenced in that record
						// http://code.google.com/p/php-excel-reader/issues/detail?id=4
						$previousRow = $row;
						$previousCol = $column;
					} elseif ((ord($data[$spos+6])==1) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
						//Boolean formula. Result is in +2; 0=false,1=true
						// http://code.google.com/p/php-excel-reader/issues/detail?id=4
                        if (ord($this->data[$spos+8])==1) {
                            $this->addcell($row, $column, "TRUE");
                        } else {
                            $this->addcell($row, $column, "FALSE");
                        }
					} elseif ((ord($data[$spos+6])==2) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
						//Error formula. Error code is in +2;
					} elseif ((ord($data[$spos+6])==3) && (ord($data[$spos+12])==255) && (ord($data[$spos+13])==255)) {
						//Formula result is a null string.
						$this->addcell($row, $column, '');
					} else {
						// result is a number, so first 14 bytes are just like a _NUMBER record
						$tmp = unpack("ddouble", substr($data, $spos + 6, 8)); // It machine machine dependent
							  if ($this->isDate($spos)) {
								$numValue = $tmp['double'];
							  }
							  else {
								$numValue = $this->createNumber($spos);
							  }
						$info = $this->_getCellDetails($spos,$numValue,$column);
						$this->addcell($row, $column, $info['string'], $info);
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_BOOLERR:
					$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$string = ord($data[$spos+6]);
					$this->addcell($row, $column, $string);
					break;
                case SPREADSHEET_EXCEL_READER_TYPE_STRING:
					// http://code.google.com/p/php-excel-reader/issues/detail?id=4
					if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
						// Unicode 16 string, like an SST record
						$xpos = $spos;
						$numChars =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
						$xpos += 2;
						$optionFlags =ord($data[$xpos]);
						$xpos++;
						$asciiEncoding = (($optionFlags &0x01) == 0) ;
						$extendedString = (($optionFlags & 0x04) != 0);
                        // See if string contains formatting information
						$richString = (($optionFlags & 0x08) != 0);
						if ($richString) {
							// Read in the crun
							$formattingRuns =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
							$xpos += 2;
						}
						if ($extendedString) {
							// Read in cchExtRst
							$extendedRunLength =$this->_GetInt4d($this->data, $xpos);
							$xpos += 4;
						}
						$len = ($asciiEncoding)?$numChars : $numChars*2;
						$retstr =substr($data, $xpos, $len);
						$xpos += $len;
						$retstr = ($asciiEncoding)? $retstr : $this->_encodeUTF16($retstr);
					}
					elseif ($version == SPREADSHEET_EXCEL_READER_BIFF7){
						// Simple byte string
						$xpos = $spos;
						$numChars =ord($data[$xpos]) | (ord($data[$xpos+1]) << 8);
						$xpos += 2;
						$retstr =substr($data, $xpos, $numChars);
					}
					$this->addcell($previousRow, $previousCol, $retstr);
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_ROW:
					$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$rowInfo = ord($data[$spos + 6]) | ((ord($data[$spos+7]) << 8) & 0x7FFF);
					if (($rowInfo & 0x8000) > 0) {
						$rowHeight = -1;
					} else {
						$rowHeight = $rowInfo & 0x7FFF;
					}
					$rowHidden = (ord($data[$spos + 12]) & 0x20) >> 5;
					$this->rowInfo[$this->sn][$row+1] = Array('height' => $rowHeight / 20, 'hidden'=>$rowHidden );
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_DBCELL:
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_MULBLANK:
					$row = ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$cols = ($length / 2) - 3;
					for ($c = 0; $c < $cols; $c++) {
						$xfindex = ord($data[$spos + 4 + ($c * 2)]) | ord($data[$spos + 5 + ($c * 2)])<<8;
						$this->addcell($row, $column + $c, "", array('xfIndex'=>$xfindex));
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_LABEL:
					$row	= ord($data[$spos]) | ord($data[$spos+1])<<8;
					$column = ord($data[$spos+2]) | ord($data[$spos+3])<<8;
					$this->addcell($row, $column, substr($data, $spos + 8, ord($data[$spos + 6]) | ord($data[$spos + 7])<<8));
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_EOF:
					$cont = false;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_HYPER:
					//  Only handle hyperlinks to a URL
					$row	= ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
					$row2   = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
					$column = ord($this->data[$spos+4]) | ord($this->data[$spos+5])<<8;
					$column2 = ord($this->data[$spos+6]) | ord($this->data[$spos+7])<<8;
					$linkdata = Array();
					$flags = ord($this->data[$spos + 28]);
					$udesc = "";
					$ulink = "";
					$uloc = 32;
					$linkdata['flags'] = $flags;
					if (($flags & 1) > 0 ) {   // is a type we understand
						//  is there a description ?
						if (($flags & 0x14) == 0x14 ) {   // has a description
							$uloc += 4;
							$descLen = ord($this->data[$spos + 32]) | ord($this->data[$spos + 33]) << 8;
							$udesc = substr($this->data, $spos + $uloc, $descLen * 2);
							$uloc += 2 * $descLen;
						}
						$ulink = $this->read16bitstring($this->data, $spos + $uloc + 20);
						if ($udesc == "") {
							$udesc = $ulink;
						}
					}
					$linkdata['desc'] = $udesc;
					$linkdata['link'] = $this->_encodeUTF16($ulink);
					for ($r=$row; $r<=$row2; $r++) {
						for ($c=$column; $c<=$column2; $c++) {
							$this->sheets[$this->sn]['cellsInfo'][$r+1][$c+1]['hyperlink'] = $linkdata;
						}
					}
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH:
					$this->defaultColWidth  = ord($data[$spos+4]) | ord($data[$spos+5]) << 8;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH:
					$this->standardColWidth  = ord($data[$spos+4]) | ord($data[$spos+5]) << 8;
					break;
				case SPREADSHEET_EXCEL_READER_TYPE_COLINFO:
					$colfrom = ord($data[$spos+0]) | ord($data[$spos+1]) << 8;
					$colto = ord($data[$spos+2]) | ord($data[$spos+3]) << 8;
					$cw = ord($data[$spos+4]) | ord($data[$spos+5]) << 8;
					$cxf = ord($data[$spos+6]) | ord($data[$spos+7]) << 8;
					$co = ord($data[$spos+8]);
					for ($coli = $colfrom; $coli <= $colto; $coli++) {
						$this->colInfo[$this->sn][$coli+1] = Array('width' => $cw, 'xf' => $cxf, 'hidden' => ($co & 0x01), 'collapsed' => ($co & 0x1000) >> 12);
					}
					break;

				default:
					break;
			}
			$spos += $length;
		}

		if (!isset($this->sheets[$this->sn]['numRows']))
			 $this->sheets[$this->sn]['numRows'] = $this->sheets[$this->sn]['maxrow'];
		if (!isset($this->sheets[$this->sn]['numCols']))
			 $this->sheets[$this->sn]['numCols'] = $this->sheets[$this->sn]['maxcol'];
		}

		function isDate($spos) {
			$xfindex = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
			return ($this->xfRecords[$xfindex]['type'] == 'date');
		}

		// Get the details for a particular cell
		function _getCellDetails($spos,$numValue,$column) {
			$xfindex = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
			$xfrecord = $this->xfRecords[$xfindex];
			$type = $xfrecord['type'];

			$format = $xfrecord['format'];
			$formatIndex = $xfrecord['formatIndex'];
			$fontIndex = $xfrecord['fontIndex'];
			$formatColor = "";
			$rectype = '';
			$string = '';
			$raw = '';

			if (isset($this->_columnsFormat[$column + 1])){
				$format = $this->_columnsFormat[$column + 1];
			}

			if ($type == 'date') {
				// See http://groups.google.com/group/php-excel-reader-discuss/browse_frm/thread/9c3f9790d12d8e10/f2045c2369ac79de
				$rectype = 'date';
				// Convert numeric value into a date
				$utcDays = floor($numValue - ($this->nineteenFour ? SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904 : SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS));
				$utcValue = ($utcDays) * SPREADSHEET_EXCEL_READER_MSINADAY;
				$dateinfo = gmgetdate($utcValue);

				$raw = $numValue;
				$fractionalDay = $numValue - floor($numValue) + .0000001; // The .0000001 is to fix for php/excel fractional diffs

				$totalseconds = floor(SPREADSHEET_EXCEL_READER_MSINADAY * $fractionalDay);
				$secs = $totalseconds % 60;
				$totalseconds -= $secs;
				$hours = floor($totalseconds / (60 * 60));
				$mins = floor($totalseconds / 60) % 60;
				$string = date ($format, mktime($hours, $mins, $secs, $dateinfo["mon"], $dateinfo["mday"], $dateinfo["year"]));
			} else if ($type == 'number') {
				$rectype = 'number';
				$formatted = $this->_format_value($format, $numValue, $formatIndex);
				$string = $formatted['string'];
				$formatColor = $formatted['formatColor'];
				$raw = $numValue;
			} else{
				if ($format=="") {
					$format = $this->_defaultFormat;
				}
				$rectype = 'unknown';
				$formatted = $this->_format_value($format, $numValue, $formatIndex);
				$string = $formatted['string'];
				$formatColor = $formatted['formatColor'];
				$raw = $numValue;
			}

			return array(
				'string'=>$string,
				'raw'=>$raw,
				'rectype'=>$rectype,
				'format'=>$format,
				'formatIndex'=>$formatIndex,
				'fontIndex'=>$fontIndex,
				'formatColor'=>$formatColor,
				'xfIndex'=>$xfindex
			);

		}


	function createNumber($spos) {
		$rknumhigh = $this->_GetInt4d($this->data, $spos + 10);
		$rknumlow = $this->_GetInt4d($this->data, $spos + 6);
		$sign = ($rknumhigh & 0x80000000) >> 31;
		$exp =  ($rknumhigh & 0x7ff00000) >> 20;
		$mantissa = (0x100000 | ($rknumhigh & 0x000fffff));
		$mantissalow1 = ($rknumlow & 0x80000000) >> 31;
		$mantissalow2 = ($rknumlow & 0x7fffffff);
		$value = $mantissa / pow( 2 , (20- ($exp - 1023)));
		if ($mantissalow1 != 0) $value += 1 / pow (2 , (21 - ($exp - 1023)));
		$value += $mantissalow2 / pow (2 , (52 - ($exp - 1023)));
		if ($sign) {$value = -1 * $value;}
		return  $value;
	}

	function addcell($row, $col, $string, $info=null) {
		$this->sheets[$this->sn]['maxrow'] = max($this->sheets[$this->sn]['maxrow'], $row + $this->_rowoffset);
		$this->sheets[$this->sn]['maxcol'] = max($this->sheets[$this->sn]['maxcol'], $col + $this->_coloffset);
		$this->sheets[$this->sn]['cells'][$row + $this->_rowoffset][$col + $this->_coloffset] = $string;
		if ($this->store_extended_info && $info) {
			foreach ($info as $key=>$val) {
				$this->sheets[$this->sn]['cellsInfo'][$row + $this->_rowoffset][$col + $this->_coloffset][$key] = $val;
			}
		}
	}


	function _GetIEEE754($rknum) {
		if (($rknum & 0x02) != 0) {
				$value = $rknum >> 2;
		} else {
			//mmp
			// I got my info on IEEE754 encoding from
			// http://research.microsoft.com/~hollasch/cgindex/coding/ieeefloat.html
			// The RK format calls for using only the most significant 30 bits of the
			// 64 bit floating point value. The other 34 bits are assumed to be 0
			// So, we use the upper 30 bits of $rknum as follows...
			$sign = ($rknum & 0x80000000) >> 31;
			$exp = ($rknum & 0x7ff00000) >> 20;
			$mantissa = (0x100000 | ($rknum & 0x000ffffc));
			$value = $mantissa / pow( 2 , (20- ($exp - 1023)));
			if ($sign) {
				$value = -1 * $value;
			}
			//end of changes by mmp
		}
		if (($rknum & 0x01) != 0) {
			$value /= 100;
		}
		return $value;
	}

	function _encodeUTF16($string) {
		$result = $string;
		if ($this->_defaultEncoding){
			switch ($this->_encoderFunction){
				case 'iconv' :	 $result = iconv('UTF-16LE', $this->_defaultEncoding, $string);
								break;
				case 'mb_convert_encoding' :	 $result = mb_convert_encoding($string, $this->_defaultEncoding, 'UTF-16LE' );
								break;
			}
		}
		return $result;
	}

	function _GetInt4d($data, $pos) {
		$value = ord($data[$pos]) | (ord($data[$pos+1]) << 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
		if ($value>=4294967294) {
			$value=-2;
		}
		return $value;
	}

}

?>
