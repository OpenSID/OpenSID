<?php class Export_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	/* ==================================================================================
		Export ke format Excel yang bisa diimpor mempergunakan Import Excel
	  Tabel: dari tweb_wil_clusterdesa, c; tweb_keluarga, k; tweb_penduduk:, p
	  Kolom: c.dusun,c.rw,c.rt,p.nama,k.no_kk,p.nik,p.sex,p.tempatlahir,p.tanggallahir,p.agama_id,p.pendidikan_kk_id,p.pendidikan_sedang_id,p.pekerjaan_id,p.status_kawin,p.kk_level,p.warganegara_id,p.nama_ayah,p.nama_ibu,p.golongan_darah_id
	*/

  function bersihkanData(&$str,$key)
  {
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    // Kode yang tersimpan sebagai '0' harus '' untuk dibaca oleh Import Excel
    if($str == "0") $str = "";
  }

  // Export data penduduk ke format Import Excel
	function export_excel(){
		$sql = "SELECT k.alamat, c.dusun,c.rw,c.rt,p.nama,k.no_kk,p.nik,p.sex,p.tempatlahir,p.tanggallahir,p.agama_id,p.pendidikan_kk_id,p.pendidikan_sedang_id,p.pekerjaan_id,p.status_kawin,p.kk_level,p.warganegara_id,p.nama_ayah,p.nama_ibu,p.golongan_darah_id,p.akta_lahir,p.dokumen_pasport,p.tanggal_akhir_paspor,p.dokumen_kitas,p.ayah_nik,p.ibu_nik,p.akta_perkawinan,p.tanggalperkawinan,p.akta_perceraian,p.tanggalperceraian,p.cacat_id,p.cara_kb_id,p.hamil

			FROM tweb_penduduk p
			LEFT JOIN tweb_keluarga k on k.id = p.id_kk
			LEFT JOIN tweb_wil_clusterdesa c on p.id_cluster = c.id
			ORDER BY k.no_kk
		";
		$q = $this->db->query($sql);
		$data = $q->result_array();
		for($i=0; $i<count($data); $i++){
			$baris = $data[$i];
			array_walk($baris, array($this, 'bersihkanData'));
			$data[$i] = $baris;
		}
		return $data;
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

	private function do_backup($prefs){
		// TODO: jika versi CI yang baru sudah mendukung backup untuk mysqli, hapus kondisi untuk mysqli
		if ($this->db->dbdriver == 'mysqli') {
			$backup =& $this->_db_mysqli_backup($prefs);
		} else {
			$this->load->dbutil();
			$backup =& $this->dbutil->backup($prefs);
		}
		return $backup;
	}

	/*
		Backup menggunakan CI dilakukan per table. Tidak memperhatikan relational constraint antara table. Jadi perlu disesuaikan supaya bisa di-impor menggunakan
		Database > Backup/Restore > Restore atau menggunakan phpmyadmin.

		TODO: cari cara backup yang menghasilkan .sql seperti menu export di phpmyadmin.
	*/
	function backup() {
		// Tabel inventaris ditambah di belakang, karena tergantung jenis_barang
		// Juga tabel suplemen_terdata yang tergantung suplemen
		$prefs = array(
				'format'      => 'sql',
				'tables'			=> array('inventaris', 'mutasi_inventaris', 'suplemen_terdata'),
			  );
		$tabel_dgn_foreign_key = $this->do_backup($prefs);
		$prefs = array(
				'format'      => 'sql',
				'tables'			=> array('jenis_barang'),
			  );
		$jenis_barang = $this->do_backup($prefs);
		$prefs = array(
				'format'      => 'sql',
				'tables'			=> array('suplemen'),
			  );
		$suplemen = $this->do_backup($prefs);

		// Tabel data_surat adalah view, di-backup terpisah
		$prefs = array(
				'format'      => 'sql',
				'ignore'			=> array('data_surat', 'jenis_barang', 'inventaris', 'mutasi_inventaris', 'suplemen', 'suplemen_terdata'),
			  );

		$backup = $this->do_backup($prefs);

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

		// Tambahkan data_surat, inventaris dan suplemen_terdata di ujung karena tergantung pada tabel lainnya
		$backup .= "DROP VIEW IF EXISTS data_surat;\n";
		$backup .= $data_surat;
		$backup .= "DROP TABLE IF EXISTS mutasi_inventaris;\n";
		$backup .= "DROP TABLE IF EXISTS inventaris;\n";
		$backup .= $jenis_barang;
		$backup .= "DROP TABLE IF EXISTS suplemen_terdata;\n";
		$backup .= $suplemen;
		$backup .= $tabel_dgn_foreign_key;

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
			  if($sql_line != "" && (strpos($sql_line,"--") === false OR strpos($sql_line, "--") != 0) && $sql_line[0] != '#'){
					$query .= $sql_line;
					if (substr(rtrim($query), -1) == ';'){
					  $result = $this->db->simple_query($query) ;
					  if (!$result) {
					  	$_SESSION['success'] = -1;
					  	$error = $this->db->error();
					  	echo "<br><br>>>>>>>>> Error: ".$query.'<br>';
					  	echo $error['message'].'<br>'; // (mysql_error equivalent)
							echo $error['code'].'<br>'; // (mysql_errno equivalent)
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

	// --------------------------------------------------------------------

	/**
	 * Database Backup
	 *
	 * @access	public
	 * @return	void
	 */
	// From DB_utility.php
	private function _db_mysqli_backup($params = array())
	{
		// If the parameters have not been submitted as an
		// array then we know that it is simply the table
		// name, which is a valid short cut.
		if (is_string($params))
		{
			$params = array('tables' => $params);
		}

		// ------------------------------------------------------

		// Set up our default preferences
		$prefs = array(
							'tables'		=> array(),
							'ignore'		=> array(),
							'filename'		=> '',
							'format'		=> 'gzip', // gzip, zip, txt
							'add_drop'		=> TRUE,
							'add_insert'	=> TRUE,
							'newline'		=> "\n"
						);

		// Did the user submit any preferences? If so set them....
		if (count($params) > 0)
		{
			foreach ($prefs as $key => $val)
			{
				if (isset($params[$key]))
				{
					$prefs[$key] = $params[$key];
				}
			}
		}

		// ------------------------------------------------------

		// Are we backing up a complete database or individual tables?
		// If no table names were submitted we'll fetch the entire table list
		if (count($prefs['tables']) == 0)
		{
			$prefs['tables'] = $this->db->list_tables();
		}

		// ------------------------------------------------------

		// Validate the format
		if ( ! in_array($prefs['format'], array('gzip', 'zip', 'txt'), TRUE))
		{
			$prefs['format'] = 'txt';
		}

		// ------------------------------------------------------

		// Is the encoder supported?  If not, we'll either issue an
		// error or use plain text depending on the debug settings
		if (($prefs['format'] == 'gzip' AND ! @function_exists('gzencode'))
		OR ($prefs['format'] == 'zip'  AND ! @function_exists('gzcompress')))
		{
			if ($this->db->db_debug)
			{
				return $this->db->display_error('db_unsuported_compression');
			}

			$prefs['format'] = 'txt';
		}

		// ------------------------------------------------------

		// Set the filename if not provided - Only needed with Zip files
		if ($prefs['filename'] == '' AND $prefs['format'] == 'zip')
		{
			$prefs['filename'] = (count($prefs['tables']) == 1) ? $prefs['tables'] : $this->db->database;
			$prefs['filename'] .= '_'.date('Y-m-d_H-i', time());
		}

		// ------------------------------------------------------

		// Was a Gzip file requested?
		if ($prefs['format'] == 'gzip')
		{
			return gzencode($this->_mysqli_backup($prefs));
		}

		// ------------------------------------------------------

		// Was a text file requested?
		if ($prefs['format'] == 'txt')
		{
			return $this->_mysqli_backup($prefs);
		}

		// ------------------------------------------------------

		// Was a Zip file requested?
		if ($prefs['format'] == 'zip')
		{
			// If they included the .zip file extension we'll remove it
			if (preg_match("|.+?\.zip$|", $prefs['filename']))
			{
				$prefs['filename'] = str_replace('.zip', '', $prefs['filename']);
			}

			// Tack on the ".sql" file extension if needed
			if ( ! preg_match("|.+?\.sql$|", $prefs['filename']))
			{
				$prefs['filename'] .= '.sql';
			}

			// Load the Zip class and output it

			$CI =& get_instance();
			$CI->load->library('zip');
			$CI->zip->add_data($prefs['filename'], $this->_mysqli_backup($prefs));
			return $CI->zip->get_zip();
		}

	}


	// utility backup untuk driver mysqli
	private function _mysqli_backup($params = array())
	{
		// Ubah menggunakan info dari http://stackoverflow.com/questions/24197844/database-utility-backups-not-working-with-mysqli-codeignitor
		if (count($params) == 0) {
			return FALSE;
		}

		// Extract the prefs for simplicity
		extract($params);

		// Build the output
		$output = '';
		foreach ((array)$tables as $table) {
			// Is the table in the "ignore" list?
			if (in_array($table, (array)$ignore, TRUE)) {
				continue;
			}

			// Get the table schema
			$query = $this->db->query("SHOW CREATE TABLE `".$this->db->database.'`.`'.$table.'`');

			// No result means the table name was invalid
			if ($query === FALSE) {
				continue;
			}

			// Write out the table schema
			$output .= '#'.$newline.'# TABLE STRUCTURE FOR: '.$table.$newline.'#'.$newline.$newline;

			if ($add_drop == TRUE) {
				$output .= 'DROP TABLE IF EXISTS '.$table.';'.$newline.$newline;
			}

			$i = 0;
			$result = $query->result_array();
			foreach ($result[0] as $val) {
				if ($i++ % 2) {
					$output .= $val.';'.$newline.$newline;
				}
			}

			// If inserts are not needed we're done...
			if ($add_insert == FALSE) {
				continue;
			}

			// Grab all the data from the current table
			$query = $this->db->query("SELECT * FROM $table");

			if ($query->num_rows() == 0) {
				continue;
			}

			// Fetch the field names and determine if the field is an
			// integer type.  We use this info to decide whether to
			// surround the data with quotes or not
			$i = 0;
			$field_str = '';
			$is_int = array();
			while ($field = mysqli_fetch_field($query->result_id)) {
				// Most versions of MySQL store timestamp as a string
				$is_int[$i] = (in_array(
										// strtolower(mysql_field_type($query->result_id, $i)),
										strtolower($field->type),
										array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'), //, 'timestamp'),
										TRUE)
										) ? TRUE : FALSE;

				// Create a string of field names
				$field_str .= '`'.$field->name.'`, ';
				$i++;
			}

			// Trim off the end comma
			$field_str = preg_replace( "/, $/" , "" , $field_str);

			// Build the insert string
			foreach ($query->result_array() as $row) {
				$val_str = '';

				$i = 0;
				foreach ($row as $v) {
					// Is the value NULL?
					if ($v === NULL) {
						$val_str .= 'NULL';

					} else {
						// Escape the data if it's not an integer
						if ($is_int[$i] == FALSE) {
							$val_str .= $this->db->escape($v);
						} else {
							$val_str .= $v;
						}
					}

					// Append a comma
					$val_str .= ', ';
					$i++;
				}

				// Remove the comma at the end of the string
				$val_str = preg_replace( "/, $/" , "" , $val_str);

				// Build the INSERT string
				$output .= 'INSERT INTO '.$table.' ('.$field_str.') VALUES ('.$val_str.');'.$newline;
			}

			$output .= $newline.$newline;
		}

		return $output;
	}
}
?>
