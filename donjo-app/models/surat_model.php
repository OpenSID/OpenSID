<?php class Surat_Model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model('surat_master_model');
		$this->load->model('penduduk_model');
	}

	function list_surat(){
		$sql   = "SELECT * FROM tweb_surat_format WHERE kunci = 0";
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
		$sql   = "SELECT * FROM tweb_surat_format WHERE kunci = 0";
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
		$sql   = "SELECT u.id,nik,nama,w.dusun,w.rw,w.rt,u.sex FROM tweb_penduduk u LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id";
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

	function get_alamat_wilayah($data) {
		$alamat_wilayah= "$data[alamat] RT $data[rt] / RW $data[rw] ".ucwords(strtolower($this->setting->sebutan_dusun))." ".ucwords(strtolower($data['dusun']));
		return trim($alamat_wilayah);
	}

	function get_penduduk($id=0){
		$sql   = "SELECT u.id AS id,u.nama AS nama,u.sex as sex_id,x.nama AS sex,u.id_kk AS id_kk,
		u.tempatlahir AS tempatlahir,u.tanggallahir AS tanggallahir,u.no_kk_sebelumnya,s.nama as status,
		(select (date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(tweb_penduduk.tanggallahir))),'%Y') + 0)`
		from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
		w.nama AS status_kawin,f.nama AS warganegara,a.nama AS agama,d.nama AS pendidikan,j.nama AS pekerjaan,u.nik AS nik,c.rt AS rt,c.rw AS rw,c.dusun AS dusun,k.no_kk AS no_kk,k.alamat,
		(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
		from tweb_penduduk u
		left join tweb_penduduk_sex x on u.sex = x.id
		left join tweb_penduduk_kawin w on u.status_kawin = w.id
		left join tweb_penduduk_agama a on u.agama_id = a.id
		left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
		left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
		left join tweb_wil_clusterdesa c on u.id_cluster = c.id
		left join tweb_keluarga k on u.id_kk = k.id
		left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
		left join tweb_penduduk_status s on u.status = s.id
		WHERE u.id = ?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		$data['alamat_wilayah']= $this->get_alamat_wilayah($data);
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

		$sql   = "SELECT u.id AS id,u.nama AS nama,x.nama AS sex,u.tempatlahir AS tempatlahir,u.tanggallahir AS tanggallahir,
			(select (date_format(from_days((to_days(now()) - to_days(`tweb_penduduk`.`tanggallahir`))),'%Y') + 0) AS `(date_format(from_days((to_days(now()) - to_days(``tweb_penduduk``.``tanggallahir``))),'%Y') + 0)` from tweb_penduduk where (tweb_penduduk.id = u.id)) AS umur,
			w.nama AS status_kawin,f.nama AS warganegara,a.nama AS agama,d.nama AS pendidikan,h.nama AS hubungan,j.nama AS pekerjaan,u.nik AS nik,c.rt AS rt,c.rw AS rw,c.dusun AS dusun,k.no_kk AS no_kk,(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
			FROM tweb_penduduk u
			LEFT JOIN tweb_penduduk_sex x on u.sex = x.id
			LEFT JOIN tweb_penduduk_kawin w on u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_hubungan h on u.kk_level = h.id
			LEFT JOIN tweb_penduduk_agama a on u.agama_id = a.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
			LEFT JOIN tweb_wil_clusterdesa c on u.id_cluster = c.id
			LEFT JOIN tweb_keluarga k on u.id_kk = k.id
			LEFT JOIN tweb_penduduk_warganegara f on u.warganegara_id = f.id
			WHERE u.nik IN($outp)";
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
			w.nama AS status_kawin,f.nama AS warganegara,a.nama AS agama,d.nama AS pendidikan,h.nama AS hubungan,j.nama AS pekerjaan,c.rt AS rt,c.rw AS rw,c.dusun AS dusun,k.no_kk AS no_kk,k.alamat,m.nama as cacat,
			(select tweb_penduduk.nik from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS nik_kk,
			(select tweb_penduduk.telepon from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS telepon_kk,
			(select tweb_penduduk.nama AS nama from tweb_penduduk where (tweb_penduduk.id = k.nik_kepala)) AS kepala_kk
			from tweb_penduduk u
			left join tweb_penduduk_sex x on u.sex = x.id
			left join tweb_penduduk_kawin w on u.status_kawin = w.id
			left join tweb_penduduk_hubungan h on u.kk_level = h.id
			left join tweb_penduduk_agama a on u.agama_id = a.id
			left join tweb_penduduk_pendidikan_kk d on u.pendidikan_kk_id = d.id
			left join tweb_penduduk_pekerjaan j on u.pekerjaan_id = j.id
			left join tweb_cacat m on u.cacat_id = m.id
			left join tweb_wil_clusterdesa c on u.id_cluster = c.id
			left join tweb_keluarga k on u.id_kk = k.id
			left join tweb_penduduk_warganegara f on u.warganegara_id = f.id
			left join tweb_golongan_darah g on u.golongan_darah_id = g.id
			WHERE u.id = ?";
		$query = $this->db->query($sql,$id);
		$data  = $query->row_array();
		$data['alamat_wilayah']= $this->get_alamat_wilayah($data);
		$this->format_data_surat($data);
		return $data;
	}

	function format_data_surat(&$data){
		$kolomUpper = array("tanggallahir","tempatlahir","dusun","pekerjaan","gol_darah","agama","sex","status_kawin","pendidikan","hubungan","nama_ayah","nama_ibu");
		foreach ($kolomUpper as $kolom) {
			$data[$kolom] = ucwords(strtolower($data[$kolom]));
		}
		$namaPendidikan = array("Tk"=>"TK","Sd"=>"SD","Sltp"=>"SLTP","Slta"=>"SLTA","Slb"=>"SLB");
		foreach ($namaPendidikan as $key => $value) {
			$data["pendidikan"] = str_replace($key, $value, $data["pendidikan"]);
		}
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
		$sql   = "SELECT u.*,h.nama as hubungan, p.nama as kepala_kk,g.nama as gol_darah,d.nama as pend,r.nama as pek,m.nama as men, w.nama as wn, n.nama as agama,c.rw,c.rt,c.dusun,(DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( u.tanggallahir ) ) , '%Y' ) +0) as umur, sex.nama as sex, k.alamat
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
		$data['alamat_wilayah']= $this->get_alamat_wilayah($data);
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
		if($istri_id){
			$istri = $this->get_data_pribadi($istri_id);
			return $istri;
		}
	}

	function get_data_suami($id=0){
		$sql = "SELECT u.id
			FROM tweb_penduduk u
			WHERE u.id=(SELECT id FROM tweb_penduduk WHERE id_kk=(SELECT id_kk FROM tweb_penduduk WHERE id=$id AND kk_level=3) AND kk_level=1 limit 1 )";
		$query = $this->db->query($sql);
		$data  = $query->row_array();

		$suami_id = $data['id'];
		if($suami_id){
			$suami = $this->get_data_pribadi($suami_id);
			return $suami;
		}
	}

	function get_data_suami_atau_istri($individu=array()) {
		if (strtolower($individu['sex']) == "laki-laki") {
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
		if (!isset($data['id']) AND $penduduk['kk_level'] == 4 ) {
			$sql = "SELECT u.id
				FROM tweb_penduduk u
				WHERE (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=1 AND u.sex=1) limit 1";
			$query = $this->db->query($sql);
			$data  = $query->row_array();
		}
		if(isset($data['id'])){
			$ayah_id = $data['id'];
			$ayah = $this->get_data_pribadi($ayah_id);
			return $ayah;
		}
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
		if (!isset($data['id']) AND $penduduk['kk_level'] == 4 ) {
			$sql = "SELECT u.id
				FROM tweb_penduduk u
				WHERE (u.id_kk=(SELECT id_kk FROM tweb_penduduk where id=$id) AND u.kk_level=3) limit 1";
			$query = $this->db->query($sql, $id);
			$data  = $query->row_array();
		}
		if(isset($data['id'])){
			$ibu_id = $data['id'];
			$ibu = $this->get_data_pribadi($ibu_id);
			return $ibu;
		}
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
		$data = $query->row_array();
		// Isi lokasi template surat
		// Pakai surat ubahan desa apabila ada
		$file = SuratExportDesa($url);
		if($file == ""){
			$data['lokasi_rtf'] = "surat/$url/";
		} else {
			$data['lokasi_rtf'] = dirname($file)."/";
		}
		$this->surat = $data;
		return $data;
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
	        $regex = "/(\}.?#)|rtlch.?#|fcs.?#+|afs.?\d#+|f\d*?\d#|fs\d*?\d#|af\d*?\d#+|ltrch#+|insrsid\d*?\d#+|alang\d+#+|lang\d+|langfe\d+|langnp\d+|langfenp\d+|b#+|ul#+|hich#+|dbch#+|loch#+|charrsid\d*?\d#+|#+/";
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

	function get_data_form($surat){
		$data_form = LOKASI_SURAT_DESA.$surat."/data_form_".$surat.".php";
		if (is_file($data_form)) return $data_form;
		else {
			$data_form = "surat/$surat/data_form_$surat.php";
			if(is_file($data_form)) return $data_form;
		}
	}

	function get_daftar_kode_surat($surat) {
		$kode = array();
		switch ($surat) {
			case 'surat_ket_nikah':
				$kode['status_kawin_pria'] = array(
				  "Jejaka",
				  "Duda",
				  "Beristri"
				);
				$kode['status_kawin_wanita'] = array(
				  "Perawan",
				  "Janda"
				);
				break;
			case 'surat_permohonan_kartu_keluarga':
				$kode['alasan_permohonan'] = array(
				  1 => "Karena Membentuk Rumah Tangga Baru",
				  2 => "Karena Kartu Keluarga Hilang/Rusak",
				  3 => "Lainnya"
				);
			case 'surat_ket_pindah_penduduk':
				$kode["alasan_pindah"] = array(
					1 => "Pekerjaan",
					2 => "Pendidikan",
					3 => "Keamanan",
					4 => "Kesehatan",
					5 => "Perumahan",
					6 => "Keluarga",
					7 => "Lainnya"
				);
				$kode["klasifikasi_pindah"] = array(
					1 => "Dalam satu Desa/Kelurahan",
					2 => "Antar Desa/Kelurahan",
					3 => "Antar Kecamatan",
					4 => "Antar Kab/Kota",
					5 => "Antar Provinsi"
				);
				$kode["jenis_kepindahan"] = array(
					1 => "Kep. Keluarga",
					2 => "Kep. Keluarga dan Seluruh Angg. Keluarga",
					3 => "Kep. Keluarga dan Sbg. Angg. Keluarga",
					4 => "Angg. Keluarga"
				);
				$kode["status_kk_pindah"] = array(
					1 => "Numpang KK",
					2 => "Membuat KK Baru",
					3 => "Nomor KK Tetap"
				);
				$kode["status_kk_tidak_pindah_f108"] = array(
					1 => "Numpang KK",
					2 => "Membuat KK Baru",
					3 => "Tidak Ada Angg. Keluarga Yang Ditinggal",
					4 => "Nomor KK Tetap"
				);
				$kode["status_kk_tidak_pindah"] = array(
					1 => "Numpang KK",
					2 => "Membuat KK Baru",
					3 => "Nomor KK Tetap"
				);
				break;

			default:
				# code...
				break;
		}
		return $kode;
	}

	// Untuk surat sistem, cek apakah komponen surat sudah disesuaikan oleh desa
	private function lokasi_komponen($nama_surat, $komponen) {
	  $lokasi = LOKASI_SURAT_DESA . $nama_surat . "/" . $komponen;
		if ($this->surat['jenis'] == 1 AND !is_file($lokasi))
			  $lokasi = "surat/$nama_surat/$komponen";
		return $lokasi;
	}

	function surat_rtf_khusus($url, $input, &$buffer, $config, $individu, $ayah, $ibu) {
		$alamat_desa = ucwords($this->setting->sebutan_desa)." ".$config['nama_desa'].", Kecamatan ".$config['nama_kecamatan'].", ".ucwords($this->setting->sebutan_kabupaten)." ".$config['nama_kabupaten'];
		// Proses surat yang membutuhkan pengambilan data khusus
		switch ($url) {
			case 'surat_ket_beda_identitas_kis':
				$lokasi_komponen = $this->lokasi_komponen($url, 'get_data_export.php');
		    include(FCPATH.$lokasi_komponen);
				break;
			case 'surat_ket_kurang_mampu':
				$anggota = $this->keluarga_model->list_anggota($individu['id_kk'],array('dengan_kk'=>false));
				for ($i = 0; $i < MAX_ANGGOTA; $i++) {
					$nomor = $i+1;
					if ($i < count($anggota)) {
						$nik = trim($anggota[$i],"'");
						$buffer=str_replace("[anggota_no_$nomor]",$nomor,$buffer);
						$buffer=str_replace("[anggota_nik_$nomor]",$anggota[$i]['nik'],$buffer);
						$buffer=str_replace("[anggota_nama_$nomor]",strtoupper($anggota[$i]['nama']),$buffer);
						$buffer=str_replace("[anggota_sex_$nomor]",$anggota[$i]['sex'][0],$buffer);
						$buffer=str_replace("[anggota_tempatlahir_$nomor]",strtoupper($anggota[$i]['tempatlahir']),$buffer);
						$buffer=str_replace("[anggota_tanggallahir_$nomor]",tgl_indo_out($anggota[$i]['tanggallahir']),$buffer);
						$buffer=str_replace("[anggota_shdk_$nomor]",strtoupper($anggota[$i]['hubungan']),$buffer);
					} else {
						$buffer=str_replace("[anggota_no_$nomor]","",$buffer);
						$buffer=str_replace("[anggota_nik_$nomor]","",$buffer);
						$buffer=str_replace("[anggota_nama_$nomor]","",$buffer);
						$buffer=str_replace("[anggota_sex_$nomor]","",$buffer);
						$buffer=str_replace("[anggota_tempatlahir_$nomor]","",$buffer);
						$buffer=str_replace("[anggota_tanggallahir_$nomor]","",$buffer);
						$buffer=str_replace("[anggota_shdk_$nomor]","",$buffer);
					}
				}
				break;
			case 'surat_ket_pindah_penduduk':
				$buffer=str_replace("[jumlah_pengikut]",count($input['id_cb']),$buffer);
				for ($i = 0; $i < MAX_PINDAH; $i++) {
					$nomor = $i+1;
					if ($i < count($input['id_cb'])) {
						$nik = trim($input['id_cb'][$i],"'");
						$penduduk = $this->penduduk_model->get_penduduk_by_nik($nik);
						$buffer=str_replace("[pindah_no_$nomor]",$nomor,$buffer);
						$buffer=str_replace("[pindah_nik_$nomor]",$penduduk['nik'],$buffer);
						$buffer=str_replace("[pindah_nama_$nomor]",ucwords(strtolower($penduduk['nama'])),$buffer);
						$buffer=str_replace("[ktp_berlaku$nomor]",$input['ktp_berlaku'][$i],$buffer);
						$buffer=str_replace("[pindah_shdk_$nomor]",ucwords(strtolower($penduduk['hubungan'])),$buffer);
					} else {
						$buffer=str_replace("[pindah_no_$nomor]","",$buffer);
						$buffer=str_replace("[pindah_nik_$nomor]","",$buffer);
						$buffer=str_replace("[pindah_nama_$nomor]","",$buffer);
						$buffer=str_replace("[ktp_berlaku$nomor]","",$buffer);
						$buffer=str_replace("[pindah_shdk_$nomor]","",$buffer);
					}
				}
				$kode = $this->get_daftar_kode_surat($url);
				$alasan_pindah_id = trim($input['alasan_pindah_id'],"'");
				if ($alasan_pindah_id == "7") {
					$str = $kode['alasan_pindah'][$alasan_pindah_id]." (".$input['sebut_alasan'].")";
					$buffer=str_replace("[alasan_pindah]",$str,$buffer);
				} else {
					$buffer=str_replace("[alasan_pindah]",$kode['alasan_pindah'][$alasan_pindah_id],$buffer);
				}
				$buffer=str_replace("[jenis_kepindahan]",$kode['jenis_kepindahan'][$input['jenis_kepindahan_id']],$buffer);
				if ($kode['status_kk_tidak_pindah'][$input['status_kk_tidak_pindah_id']]) {
					$buffer=str_replace("[status_kk_tidak_pindah]",$kode['status_kk_tidak_pindah'][$input['status_kk_tidak_pindah_id']],$buffer);
				}
				else {
					$buffer=str_replace("[status_kk_tidak_pindah]","-",$buffer);
				}
				$buffer=str_replace("[status_kk_pindah]",$kode['status_kk_pindah'][$input['status_kk_pindah_id']],$buffer);
				break;

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

			case 'surat_ket_nikah':
				// Data pasangan pria =====================
				if($input['id_pria']) {
					$pria = $this->get_data_surat($input['id_pria']);
					$ibu_pria = $this->get_data_ibu($input['id_pria']);
					$ayah_pria = $this->get_data_ayah($input['id_pria']);
					$buffer=str_replace("[agama_pria]","$pria[agama]",$buffer);
					$buffer=str_replace("[alamat_pria]","$pria[alamat_wilayah]",$buffer);
					$buffer=str_replace("[nama_pria]","$pria[nama]",$buffer);
					$buffer=str_replace("[no_ktp_pria]","$pria[nik]",$buffer);
					$buffer=str_replace("[no_kk_pria]","$pria[no_kk]",$buffer);
					$buffer=str_replace("[pekerjaan_pria]","$pria[pekerjaan]",$buffer);
					$buffer=str_replace("[sex_pria]","$pria[sex]",$buffer);
					$buffer=str_replace("[status_pria]","$pria[status_kawin]",$buffer);
					$buffer=str_replace("[tempatlahir_pria]",$pria[tempatlahir],$buffer);
					$buffer=str_replace("[tanggallahir_pria]",tgl_indo_dari_str($pria[tanggallahir]),$buffer);
					$buffer=str_replace("[usia_pria]","$pria[umur] Tahun",$buffer);
					$buffer=str_replace("[wn_pria]","$pria[warganegara]",$buffer);
				}

				# Data orang tua apabila warga desa
				if ($ayah_pria) {
					$buffer=str_replace("[form_nama_ayah_pria]",$ayah_pria['nama'],$buffer);
					$buffer=str_replace("[form_tempatlahir_ayah_pria]",ucwords(strtolower($ayah_pria['tempatlahir'])),$buffer);
					$buffer=str_replace("[form_tanggallahir_ayah_pria]",tgl_indo_dari_str($ayah_pria['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ayah_pria]",$ayah_pria['wn'],$buffer);
					$buffer=str_replace("[form_agama_ayah_pria]",ucwords(strtolower($ayah_pria['agama'])),$buffer);
					$buffer=str_replace("[form_pekerjaan_ayah_pria]",ucwords(strtolower($ayah_pria['pek'])),$buffer);
					$buffer=str_replace("[form_alamat_ayah_pria]","RT ".$ayah_pria[rt]." / RW ".$ayah_pria[rw]." ".ucwords(strtolower($ayah_pria[dusun]))." $alamat_desa",$buffer);
				}
				if ($ibu_pria) {
					$buffer=str_replace("[form_nama_ibu_pria]",$ibu_pria['nama'],$buffer);
					$buffer=str_replace("[form_tempatlahir_ibu_pria]",ucwords(strtolower($ibu_pria['tempatlahir'])),$buffer);
					$buffer=str_replace("[form_tanggallahir_ibu_pria]",tgl_indo_dari_str($ibu_pria['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ibu_pria]",$ibu_pria['wn'],$buffer);
					$buffer=str_replace("[form_agama_ibu_pria]",ucwords(strtolower($ibu_pria['agama'])),$buffer);
					$buffer=str_replace("[form_pekerjaan_ibu_pria]",ucwords(strtolower($ibu_pria['pek'])),$buffer);
					$buffer=str_replace("[form_alamat_ibu_pria]","RT $ibu_pria[rt] / RW $ibu_pria[rw] ".ucwords(strtolower($ibu_pria[dusun]))." $alamat_desa",$buffer);
				}
				// Kode isian yang mungkin tidak terisi
				$buffer=str_replace("[form_istri_dulu]",$input['istri_dulu'],$buffer);

				// Data pasangan wanita =====================
				if($input['id_wanita']) {
					$wanita = $this->get_data_surat($input['id_wanita']);
					$ibu_wanita = $this->get_data_ibu($input['id_wanita']);
					$ayah_wanita = $this->get_data_ayah($input['id_wanita']);
					$buffer=str_replace("[form_agama_wanita]",$wanita[agama],$buffer);
					$buffer=str_replace("[form_alamat_wanita]",$wanita[alamat_wilayah],$buffer);
					$buffer=str_replace("[form_nama_wanita]",$wanita[nama],$buffer);
					$buffer=str_replace("[form_pekerjaan_wanita]",$wanita[pekerjaan],$buffer);
					$buffer=str_replace("[form_tempatlahir_wanita]",$wanita[tempatlahir],$buffer);
					$buffer=str_replace("[form_tanggallahir_wanita]",tgl_indo_dari_str($wanita[tanggallahir]),$buffer);
					$buffer=str_replace("[form_wn_wanita]",$wanita[warganegara],$buffer);
				}
				# Data orang tua apabila warga desa
				if ($ayah_wanita) {
					$buffer=str_replace("[form_nama_ayah_wanita]",$ayah_wanita['nama'],$buffer);
					$buffer=str_replace("[form_tempatlahir_ayah_wanita]",ucwords(strtolower($ayah_wanita['tempatlahir'])),$buffer);
					$buffer=str_replace("[form_tanggallahir_ayah_wanita]",tgl_indo_dari_str($ayah_wanita['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ayah_wanita]",$ayah_wanita['wn'],$buffer);
					$buffer=str_replace("[form_agama_ayah_wanita]",ucwords(strtolower($ayah_wanita['agama'])),$buffer);
					$buffer=str_replace("[form_pekerjaan_ayah_wanita]",ucwords(strtolower($ayah_wanita['pek'])),$buffer);
					$buffer=str_replace("[form_alamat_ayah_wanita]","RT $ayah_wanita[rt] / RW $ayah_wanita[rw] ".ucwords(strtolower($ayah_pria[dusun]))." $alamat_desa",$buffer);
				}
				if ($ibu_wanita) {
					$buffer=str_replace("[form_nama_ibu_wanita]",$ibu_wanita['nama'],$buffer);
					$buffer=str_replace("[form_tempatlahir_ibu_wanita]",ucwords(strtolower($ibu_wanita['tempatlahir'])),$buffer);
					$buffer=str_replace("[form_tanggallahir_ibu_wanita]",tgl_indo_dari_str($ibu_wanita['tanggallahir']),$buffer);
					$buffer=str_replace("[form_wn_ibu_wanita]",$ibu_wanita['wn'],$buffer);
					$buffer=str_replace("[form_agama_ibu_wanita]",ucwords(strtolower($ibu_wanita['agama'])),$buffer);
					$buffer=str_replace("[form_pekerjaan_ibu_wanita]",ucwords(strtolower($ibu_wanita['pek'])),$buffer);
					$buffer=str_replace("[form_alamat_ibu_wanita]","RT $ibu_wanita[rt] / RW $ibu_wanita[rw] ".ucwords(strtolower($ibu_wanita[dusun]))." $alamat_desa",$buffer);
				}
				// Kode isian yang mungkin tidak terisi
				$buffer=str_replace("[form_suami_dulu]",$input['suami_dulu'],$buffer);

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
				if (strtolower($individu['sex']) == "laki-laki") {
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

	/* Dipanggil untuk setiap kode isian ditemukan,
	   dan diganti dengan kata pengganti yang huruf besar/kecil mengikuti huruf kode isian.
		 Berdasarkan contoh di http://stackoverflow.com/questions/19317493/php-preg-replace-case-insensitive-match-with-case-sensitive-replacement

		 Huruf pertama dan kedua huruf besar --> ganti dengan huruf besar semua:
		 		[SEbutan_desa] ==> KAMPUNG
		 Huruf pertama besar dan kedua kecil --> ganti dengan huruf besar pertama saja:
		 		[Sebutan_desa] ==> Kampung
		 Huruf pertama kecil --> ganti dengan huruf kecil semua:
		 		[sebutan_desa] ==> kampung
	*/
	function case_replace($dari,$ke,$str){
		$replacer = function($matches) use($ke){
			$matches = array_map(function($match){
				return preg_replace("/[\[\]]/", "", $match);
			}, $matches);
			if(ctype_upper($matches[0][0]) AND ctype_upper($matches[0][1]))
				return strtoupper($ke);
			elseif(ctype_upper($matches[0][0]))
				return ucwords($ke);
			else return strtolower($ke);
		};
		$dari = str_replace("[", "\[", $dari);
		$str = preg_replace_callback("/(".$dari.")/i", $replacer, $str);
		return $str;
	}

	function surat_rtf($data){
		// Ambil data
		$input = $data['input'];
		$individu = $data['individu'];
		$ayah = $data['ayah'];
		$ibu = $data['ibu'];
		$config = $data['config'];
		$surat = $data['surat'];
		$id = $input['nik'];
		$url = $surat['url_surat'];
		$tgl = tgl_indo(date("Y m d"));
		$thn = date("Y");

		$tgllhr = ucwords(tgl_indo($individu['tanggallahir']));
		$individu['nama'] = strtoupper($individu['nama']);

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
			$buffer=$this->case_replace("[sebutan_kabupaten]",$this->setting->sebutan_kabupaten,$buffer);
			$buffer=$this->case_replace("[sebutan_kecamatan]",$this->setting->sebutan_kecamatan,$buffer);
			$buffer=$this->case_replace("[sebutan_desa]",$this->setting->sebutan_desa,$buffer);
			$buffer=$this->case_replace("[sebutan_dusun]",$this->setting->sebutan_dusun,$buffer);
			$buffer=$this->case_replace("[sebutan_camat]",$this->setting->sebutan_camat,$buffer);
			if (!empty($config[email_desa]))
				$alamat_desa = "$config[alamat_kantor] Email: $config[email_desa] Kode Pos: $config[kode_pos]";
			else
				$alamat_desa = "$config[alamat_kantor] Kode Pos: $config[kode_pos]";
			$buffer=str_replace("[alamat_des]",$alamat_desa,$buffer);
			$buffer=str_replace("[alamat_desa]",$alamat_desa,$buffer);
			$buffer=str_replace("[email_desa]","$config[email_desa]",$buffer);
			$buffer=str_replace("[kode_desa]","$config[kode_desa]",$buffer);
			$buffer=str_replace("[kode_kecamatan]","$config[kode_kecamatan]",$buffer);
			$buffer=str_replace("[kode_kabupaten]","$config[kode_kabupaten]",$buffer);
			$buffer=str_replace("[kode_pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[kode_provinsi]","$config[kode_propinsi]",$buffer);
			$buffer=str_replace("[nama_des]","$config[nama_desa]",$buffer);
			$buffer=str_replace("[nama_kab]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kabupaten]","$config[nama_kabupaten]",$buffer);
			$buffer=str_replace("[nama_kec]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_kecamatan]","$config[nama_kecamatan]",$buffer);
			$buffer=str_replace("[nama_provinsi]","$config[nama_propinsi]",$buffer);
			$buffer=str_replace("[nama_kepala_camat]","$config[nama_kepala_camat]",$buffer);
			$buffer=str_replace("[nama_kepala_desa]","$config[nama_kepala_desa]",$buffer);
			$buffer=str_replace("[nip_kepala_camat]","$config[nip_kepala_camat]",$buffer);
			$buffer=str_replace("[nip_kepala_desa]","$config[nip_kepala_desa]",$buffer);
			$buffer=str_replace("[pos]","$config[kode_pos]",$buffer);
			$buffer=str_replace("[telepon_desa]","$config[telepon]",$buffer);
			$buffer=str_replace("[website_desa]","$config[website]",$buffer);

			//DATA DARI TABEL PENDUDUK
			//jika data kurang lengkap bisa di tambahkan dari fungsi "get_data_surat" pada file ini
			$buffer=str_replace("[agama]","$individu[agama]",$buffer);
			$buffer=str_replace("[akta_lahir]","$individu[akta_lahir]",$buffer);
			$buffer=str_replace("[akta_perceraian]","$individu[akta_perceraian]",$buffer);
			$buffer=str_replace("[akta_perkawinan]","$individu[akta_perkawinan]",$buffer);
			$buffer=str_replace("[alamat]","$individu[alamat_wilayah]",$buffer);
			$buffer=str_replace("[alamat_jalan]","$individu[alamat]",$buffer);
			$buffer=str_replace("[alamat_sebelumnya]",ucwords(strtolower($individu[alamat_sebelumnya])),$buffer);
			$buffer=str_replace("[ayah_nik]","$individu[ayah_nik]",$buffer);
			$buffer=str_replace("[cacat]",ucwords(strtolower($individu[cacat])),$buffer);
			$buffer=str_replace("[dokumen_pasport]","$individu[dokumen_pasport]",$buffer);
			$buffer=str_replace("[dusun]","$individu[dusun]",$buffer);
			$buffer=str_replace("[gol_darah]","$individu[gol_darah]",$buffer);
			$buffer=str_replace("[hubungan]","$individu[hubungan]",$buffer);
			$buffer=str_replace("[ibu_nik]","$individu[ibu_nik]",$buffer);
			$buffer=str_replace("[kepala_kk]","$individu[kepala_kk]",$buffer);
			$buffer=str_replace("[nama]","$individu[nama]",$buffer);
			$buffer=str_replace("[nama_ayah]","$individu[nama_ayah]",$buffer);
			$buffer=str_replace("[nama_ibu]","$individu[nama_ibu]",$buffer);
			$buffer=str_replace("[no_kk]","$individu[no_kk]",$buffer);
			$buffer=str_replace("[no_ktp]","$individu[nik]",$buffer);
			$buffer=str_replace("[pendidikan]","$individu[pendidikan]",$buffer);
			$buffer=str_replace("[pekerjaan]","$individu[pekerjaan]",$buffer);
			$buffer=str_replace("[rw]","$individu[rw]",$buffer);
			$buffer=str_replace("[rt]","$individu[rt]",$buffer);
			$buffer=str_replace("[sex]","$individu[sex]",$buffer);
			$buffer=str_replace("[status]","$individu[status_kawin]",$buffer);
			$buffer=str_replace("[tanggallahir]","$tgllhr",$buffer);
			$buffer=str_replace("[tanggalperceraian]",ucwords(tgl_indo($individu[tanggalperceraian])),$buffer);
			$buffer=str_replace("[tanggalperkawinan]",ucwords(tgl_indo($individu[tanggalperkawinan])),$buffer);
			$buffer=str_replace("[tanggal_akhir_paspor]",ucwords(tgl_indo($individu[tanggal_akhir_paspor])),$buffer);
			$buffer=str_replace("[tempatlahir]","$individu[tempatlahir]",$buffer);
			$buffer=str_replace("[tempat_tgl_lahir]","$individu[tempatlahir]/$tgllhr",$buffer);
			$buffer=str_replace("[ttl]","$individu[tempatlahir]/$tgllhr",$buffer);
			$buffer=str_replace("[usia]","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("*usia","$individu[umur] Tahun",$buffer);
			$buffer=str_replace("[warga_negara]","$individu[warganegara]",$buffer);

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
			// Kode isian yang disediakan pada SID CRI
			$buffer=str_replace("[nomor_surat]","$input[nomor]",$buffer);
			$buffer=str_replace("[nomor_sorat]","$input[nomor]",$buffer);
			if(isset($input['berlaku_dari'])) $buffer=str_replace("[mulai_berlaku]",tgl_indo(date('Y m d',strtotime($input['berlaku_dari']))),$buffer);
			if(isset($input['berlaku_sampai'])) $buffer=str_replace("[tgl_akhir]",tgl_indo(date('Y m d',strtotime($input['berlaku_sampai']))),$buffer);
			$buffer=str_replace("[jabatan]","$input[jabatan]",$buffer);
			$buffer=str_replace("[nama_pamong]","$input[pamong]",$buffer);
			$buffer=str_replace("[keterangan]","$input[keterangan]",$buffer);
			if(isset($input['keperluan'])) $buffer=str_replace("[keperluan]","$input[keperluan]",$buffer);
			// $input adalah isian form surat. Kode isian dari form bisa berbentuk [form_isian]
			// sesuai dengan panduan, atau boleh juga langsung [isian] saja
			$isian_tanggal = array("berlaku_dari", "berlaku_sampai", "tanggal", "tgl_meninggal",
				"tanggal_lahir", "tanggallahir_istri", "tanggallahir_suami", "tanggal_mati",
				"tanggallahir_pasangan", "tgl_lahir_ayah", "tgl_lahir_ibu", "tgl_berakhir_paspor",
				"tgl_akte_perkawinan", "tgl_perceraian", "tanggallahir","tanggallahir_pelapor", "tgl_lahir",
				"tanggallahir_ayah", "tanggallahir_ibu", "tgl_lahir_wali", "tgl_nikah",
				"tanggal_pindah", "tanggal_nikah", "tanggallahir_wali", "tanggallahir_suami_dulu", "tanggallahir_istri_dulu", "tanggallahir_ayah_pria", "tanggallahir_ibu_pria"
				);
			foreach ($input as $key => $entry){
				// Isian tanggal diganti dengan format tanggal standar
				if (in_array($key, $isian_tanggal)){
					if (is_array($entry)) {
						for ($i=1; $i<=count($entry); $i++){
							$str = $key.$i;
							$buffer=preg_replace("/\[$str\]|\[form_$str\]/",tgl_indo_dari_str($entry[$i-1]),$buffer);
						}
					} else {
						$buffer=preg_replace("/\[$key\]|\[form_$key\]/",tgl_indo_dari_str($entry),$buffer);
					}
				}
				if (!is_array($entry)) {
					$buffer=str_replace("[form_$key]",$entry,$buffer);
					// Diletakkan di bagian akhir karena bisa sama dengan kode isian sebelumnya
					// dan kalau masih ada dianggap sebagai kode dari form isian
					$buffer=str_replace("[$key]",$entry,$buffer);
				}
			}
		}
		return $buffer;
	}

	/**
		* Kembalikan nama file lampiran yang akan digunakan, di mana
		* seperti Surat Keterangan Pindah Penduduk ada beberapa pilihan format dan
		* pengguna bisa memilih format mana yang akan digunakan.
	*/
	function lampiran_khusus($url, $lampiran_surat, &$input){
		// $lampiran_surat dalam bentuk seperti "f-1.08.php,f-1.25.php"
		$daftar_lampiran = explode(",", $lampiran_surat);
		switch ($url) {
			case 'surat_ket_pindah_penduduk':
				if ($input['kode_format'] == 'F-1.23'){
					$input['judul_format'] = "Dalam Satu Desa/Kelurahan";
				} elseif ($input['kode_format'] == 'F-1.25'){
					$input['judul_format'] = "Antar Desa/Kelurahan Dalam Satu Kecamatan";
				} elseif ($input['kode_format'] =='F-1.29'){
					$input['judul_format'] = "Antar Kecamatan Dalam Satu Kabupaten/Kota";
				} elseif ($input['kode_format'] == 'F-1.34'){
					$input['judul_format'] = "Antar Kabupaten/Kota atau Antar Provinsi";
				}
				if ($input['kode_format'] == "f108")
					return array($daftar_lampiran[0]);
				else
					return array($daftar_lampiran[1]);
				break;

			default:
				return $daftar_lampiran;
		}
	}

	function lampiran($data, $nama_surat, &$lampiran){
		$surat = $data['surat'];
		if (!$surat['lampiran']) return;

		$config = $data['config'];
		$individu = $data['individu'];
		$input = $data['input'];
    include(FCPATH.$surat['lokasi_rtf'].'get_data_lampiran.php');
		$daftar_lampiran = $this->lampiran_khusus($surat['url_surat'],$surat['lampiran'],$input);
		$lampiran = pathinfo($nama_surat, PATHINFO_FILENAME)."_lampiran.pdf";

    // get the HTML using output buffer
    ob_start();
    foreach($daftar_lampiran as $format_lampiran){
	    include(FCPATH.$surat['lokasi_rtf'].$format_lampiran);
    }
    $content = ob_get_clean();

    // convert in PDF
    require_once(FCPATH.'vendor/html2pdf/html2pdf.class.php');
    try
    {
      $html2pdf = new HTML2PDF();
//      $html2pdf->setModeDebug();
      $html2pdf->setDefaultFont('Arial');
      $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			ob_end_clean();
      $html2pdf->Output(LOKASI_ARSIP.$lampiran, 'F');
    }
    catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
    }
	}

	function get_data_untuk_surat($url) {
		$data['input'] = $_POST;
		// Ambil data
		$data['config'] = $this->get_data_desa();
		$data['surat'] = $this->get_surat($url);
		switch ($url) {
			default:
				$id = $data['input']['nik'];
				$data['individu'] = $this->get_data_surat($id);
				$data['ayah'] = $this->get_data_ayah($id);
				$data['ibu'] = $this->get_data_ibu($id);
				break;
		}
		return $data;
	}

	function buat_surat($url='', &$nama_surat, &$lampiran){
		$data = $this->get_data_untuk_surat($url);
		$this->lampiran($data, $nama_surat, $lampiran);
		$this->surat_utama($data, $nama_surat);
	}

	function surat_utama($data, &$nama_surat){
		$rtf = $this->surat_rtf($data);
		// Simpan surat di folder arsip dan download
		$path_arsip = LOKASI_ARSIP;
		$berkas_arsip = $path_arsip.$nama_surat;
		$handle = fopen($berkas_arsip,'w+');
		fwrite($handle,$rtf);
		fclose($handle);
		if (!empty($this->setting->libreoffice_path)) {
			// Untuk konversi rtf ke pdf, libreoffice harus terinstall
			if (strpos(strtoupper(php_uname('s')), 'WIN') !== false) {
				// Windows O/S
				$berkas_arsip_win = str_replace('/', "\\", $berkas_arsip);
				$fcpath = str_replace('/', "\\", FCPATH);
				$outdir = rtrim(str_replace('/',"\\",FCPATH.LOKASI_ARSIP), "/\\");
				$cmd = 'cd '.$this->setting->libreoffice_path;
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
		}

		$_SESSION['success']=8;
		header("location:".base_url($berkas_arsip));
	}

	function get_last_nosurat_log($url){

		// abaikan jenis surat
		if ($this->setting->nomor_terakhir_semua_surat){
			$sql   = "SELECT no_surat,tanggal FROM log_surat ORDER BY tanggal DESC LIMIT 1";
			$query = $this->db->query($sql);
		} else {
			$sql   = "SELECT id FROM tweb_surat_format WHERE url_surat = ?";
			$query = $this->db->query($sql, $url);

			$id_format_surat = $query->row()->id;

			$sql   = "SELECT no_surat,tanggal FROM log_surat WHERE id_format_surat = ? ORDER BY tanggal DESC LIMIT 1";
			$query = $this->db->query($sql, $id_format_surat);
		}

		return $query->row_array();

	}

}
