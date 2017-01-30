<?php class Export_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	/* ==================================================================================
		Export ke format Excel yang bisa diimpor mempergunakan Import Excel
	  Tabel: dari tweb_wil_clusterdesa, c; tweb_keluarga, k; tweb_penduduk:, p
	  Kolom: c.dusun,c.rw,c.rt,p.nama,k.no_kk,p.nik,p.sex,p.tempatlahir,p.tanggallahir,p.agama_id,p.pendidikan_kk_id,p.pendidikan_sedang_id,p.pekerjaan_id,p.status_kawin,p.kk_level,p.warganegara_id,p.nama_ayah,p.nama_ibu,p.golongan_darah_id
	*/

  function bersihkanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    // Kode yang tersimpan sebagai '0' harus '' untuk dibaca oleh Import Excel
    if($str == "0") $str = "";
    // Paksa bilangan seperti nik dan no_kk agar dibaca oleh Excel sebagai string
    // Juga bilangan yang mulai dengan '0' reperti RT/RW '002'
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str)) {
      $str = "'$str";
    }
  }

  // Export data penduduk ke format Import Excel
	function export_by_keluarga(){
		$sql = "SELECT k.alamat, c.dusun,c.rw,c.rt,p.nama,k.no_kk,p.nik,p.sex,p.tempatlahir,p.tanggallahir,p.agama_id,p.pendidikan_kk_id,p.pendidikan_sedang_id,p.pekerjaan_id,p.status_kawin,p.kk_level,p.warganegara_id,p.nama_ayah,p.nama_ibu,p.golongan_darah_id
			FROM tweb_penduduk p
			LEFT JOIN tweb_keluarga k on k.id = p.id_kk
			LEFT JOIN tweb_wil_clusterdesa c on p.id_cluster = c.id
			ORDER BY k.no_kk
		";
		$q = $this->db->query($sql);
		$data = $q->result_array();
	  // Nama file untuk diunduh
	  $nama_file = "export_penduduk_" . date("d-m-Y") . ".xls";

	  $return = '';
		if($q->num_rows()>0){
      // judul kolom pada baris pertama
			$i=0;
			$baris = $data[$i];
      $return .= implode("\t", array_keys($baris)) . "\r\n";
			while($i<count($data)){
				$baris = $data[$i];
				$baris['tanggallahir'] = "'".date_format(date_create($baris['tanggallahir']),"d-m-Y");
				array_walk($baris, array($this, 'bersihkanData'));
	      $return .= implode("\t", array_values($baris)) . "\r\n";
				$i++;
			}
		}

	  header("Content-Disposition: attachment; filename=\"$nama_file\"");
	  header("Content-Type: application/vnd.ms-excel");
		echo $return;
	}

	// ====================== End export_by_keluarga ========================

	function export_dasar()
	{
		$return = "";
		$return.=$this->_build_schema('tweb_penduduk', 'penduduk');
		$return.=$this->_build_schema('tweb_keluarga', 'keluarga');
		$return.=$this->_build_schema('tweb_wil_clusterdesa', 'cluster');

		Header('Content-type: application/octet-stream');
		Header('Content-Disposition: attachment; filename=data_dasar('.date("d-m-Y").').sid');
		echo $return;
	}


	function export_akp()
	{
		$return=$this->_build_schema('analisis_keluarga', 'akpkeluarga');

		Header('Content-type: application/octet-stream');
		Header('Content-Disposition: attachment; filename=data_akp('.date("d-m-Y").').sid');
		echo $return;
	}

	function analisis()
	{

		$sql   = "DELETE FROM analisis_respon_hasil WHERE id_periode=1";
		$this->db->query($sql);

		$sql   = "DELETE FROM analisis_respon WHERE id_periode=1";
		$this->db->query($sql);

		$sql   = "SELECT id FROM tweb_keluarga WHERE 1 ORDER BY no_kk";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){
			$sql2   = "SELECT u.*,(SELECT COUNT(id) FROM analisis_parameter WHERE id_indikator = u.id) AS jml FROM analisis_indikator u WHERE id_master = 1 ORDER BY bobot";
			$query2 = $this->db->query($sql2);
			$res=$query2->result_array();
			$j=0;
			while($j<count($res)){
				$updx['id_subjek'] = $data[$i]['id'];
				$updx['id_indikator'] = $res[$j]['id'];
				$jm = $res[$j]['jml'];
				$jm=$jm-1;

				$sqlx   = "SELECT id FROM analisis_parameter WHERE id_indikator = ?";
				$queryx = $this->db->query($sqlx,$res[$j]['id']);
				$jaw=$queryx->result_array();
				//print_r($jaw);
				$numbers=rand($jaw[0]['id'],$jaw[$jm]['id']);

				$updx['id_parameter'] = $numbers;
				$updx['id_periode'] = 1;
				$outp = $this->db->insert('analisis_respon',$updx);

				$j++;
			}

			$sql   = "SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=?";
			$query = $this->db->query($sql,array($data[$i]['id'],1));
			$dx = $query->row_array();


			$upx['id_master'] =1;
			$upx['akumulasi'] = $dx['jml'];
			$upx['id_subjek'] = $data[$i]['id'];
			$upx['id_periode'] = 1;
			$outp = $this->db->insert('analisis_respon_hasil',$upx);
			$i++;
		}

		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;

	}

	function lombok(){
		$sql   = "SELECT * FROM sheet1 WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		$i=0;
		while($i<count($data)){

				$upx['id_master'] =4;
				$upx['id_kategori'] = $data[$i]['id_kat'];
				$upx['pertanyaan'] = $data[$i]['indi'];
				$upx['bobot'] = 1;
				$upx['act_analisis'] = 1;
				$upx['id_tipe'] = 1;
				$outp = $this->db->insert('analisis_indikator',$upx);

				$sql2   = "SELECT id FROM analisis_indikator ORDER BY id DESC LIMIT 1";
				$query2 = $this->db->query($sql2);
				$res=$query2->row_array();


				$updx['id_indikator'] = $res['id'];

				$updx['nilai'] = 1;
				$updx['jawaban'] = $data[$i]['C'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 2;
				$updx['jawaban'] = $data[$i]['D'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 3;
				$updx['jawaban'] = $data[$i]['E'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 4;
				$updx['jawaban'] = $data[$i]['F'];
				$outp = $this->db->insert('analisis_parameter',$updx);
				$updx['nilai'] = 5;
				$updx['jawaban'] = $data[$i]['G'];
				$outp = $this->db->insert('analisis_parameter',$updx);



			$i++;
		}


		if($outp) $_SESSION['success']=1;
		else $_SESSION['success']=-1;

	}

	function backup(){
		$this->load->dbutil();

		// Tabel data_surat adalah view, di-backup terpisah
    $prefs = array(
            'format'      => 'sql',
            'ignore'			=> array('data_surat'),
          );
    $backup =& $this->dbutil->backup($prefs);

		// Tabel data_surat adalah view, di-backup terpisah
    $prefs = array(
            'format'      => 'sql',
            'tables'			=> array('data_surat'),
          );
    $data_surat =& $this->dbutil->backup($prefs);
    // Hilangkan ketentuan user untuk view data_surat dan baris-baris lain yang
    // dihasilkan oleh dbutil->backup karena bermasalah
    // pada waktu import dgn restore ataupun phpmyadmin
    $data_surat = preg_replace("/DEFINER=`root`@`localhost`/", "", $data_surat);
    $data_surat = preg_replace("/utf8_general_ci;/", "", $data_surat);
    $baris_baris = explode("\n", $data_surat);
    foreach ($baris_baris as $baris) {
	    if (strpos($baris, 'INSERT INTO data_surat') !== FALSE) {
	         continue;
	    }
	    $simpan[] = $baris;
		}
		$data_surat = implode("\n", $simpan);

    // Tambahkan data_surat di ujung karena tergantung pada tabel lainnya
    $backup = $backup . "DROP VIEW IF EXISTS data_surat;\n";
    $backup = $backup . $data_surat;

    $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.sql';
    $save = base_url().$db_name;

    $this->load->helper('file');
    write_file($save, $backup);
    $this->load->helper('download');
    force_download($db_name, $backup);

		if($backup) $_SESSION['success']=1;
		else $_SESSION['success']=-1;
	}

	function restore(){

		$db = $this->db->database;
		$sql   = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='$db'";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		foreach($data AS $dat){
			$tbl = $dat["TABLE_NAME"];
			$this->db->simple_query("DROP TABLE $tbl");
		}
		$_SESSION['success'] = 1;
		$filename = $_FILES['userfile']['tmp_name'];
		if ($filename!=''){
			$lines = file($filename);
			$query = "";
			foreach($lines as $sql_line){
				// Abaikan baris apabila kosong atau komentar
				$sql_line = trim($sql_line);
			  if($sql_line != "" && (strpos($sql_line,"--") === false OR strpos($sql_line, "--") != 0)){
					$query .= $sql_line;
					if (substr(rtrim($query), -1) == ';'){
					  $result = $this->db->simple_query($query) ;
					  if (!$result) {
					  	$_SESSION['success'] = -1;
					  	echo "Error: ".$query;
					  }
					  $query = "";
					}
			  }
		 	}
		}
	}

	function gawe_surat(){

		$sql   = "SELECT * FROM tweb_surat_format WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		foreach($data AS $dat){

			$string=$dat['url_surat'];
			$mypath="surat\\".$dat['url_surat']."\\";
			$path = "".str_replace("\\","/",$mypath)."/";

			if (!file_exists($mypath)) {
				mkdir($mypath, 0777, true);
			}

			if (!file_exists(path)) {
				mkdir(path);
			}
			//$ccyymmdd = date("Y-m-d");
			$handle = fopen($path.$dat['url_surat'],'w+');
			fwrite($handle);
			fclose($handle);
		}

	}


	private function _build_schema($nama_tabel, $nama_tanda) {
		$return = "";
		$result = $this->db->query("SELECT * FROM $nama_tabel");
		$fields = $this->db->field_data($nama_tabel);
		$num_fields = count($fields);

		$return.= "<$nama_tanda>\r\n";
		foreach($result->result() as $row) {
			$j=0;
			foreach($fields as $col) {
				$name = $col->name;
				if (isset($row->$name)) {
					$return.= $row->$name ;
				} else {
					$return.= '';
				}
				if ($j < ($num_fields-1)) {
					$return.= '+';
				}
				$j++;
			}
			$return.= "\r\n";
		}
		$return.="</$nama_tanda>\r\n";
		return $return;
	}

}
?>
