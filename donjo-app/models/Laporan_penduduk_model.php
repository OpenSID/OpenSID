<?php class Laporan_penduduk_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model('program_bantuan_model');
	}


	function autocomplete(){
		$sql   = "SELECT dusun_nama FROM tweb_wil_dusun";
		$query = $this->db->query($sql);
		$data  = $query->result_array();

		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['dusun_nama']. "'";
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}

	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.nama LIKE '$kw'";
			return $search_sql;
			}
		}

	function link_statistik_penduduk(){
		$statistik = array(
			"statistik/3" => "Agama",
			"statistik/17"=> "Akte Kelahiran",
			"statistik/16"=> "Akseptor KB",
			"statistik/9" => "Cacat",
			"statistik/7" => "Golongan Darah",
			"statistik/4" => "Jenis Kelamin",
			"statistik/0" => "Pendidikan Dalam KK",
			"statistik/14"=> "Pendidikan Sedang Ditempuh",
			"statistik/1" => "Pekerjaan",
			"statistik/6" => "Status Penduduk",
			"statistik/2" => "Status Perkawinan",
			"statistik/13"=> "Umur",
			"statistik/18"=> "Kepemilikan Wajib KTP",
			"statistik/5" => "Warga Negara"
		);
		return $statistik;
	}

	function link_statistik_keluarga(){
		$statistik = array(
			"statistik/kelas_sosial" => "Kelas Sosial"
		);
		return $statistik;
	}

	function link_statistik_lainnya(){
		$statistik = array(
			"wilayah" => "Wilayah Administratif"
		);
		return $statistik;
	}

	function judul_statistik($lap){
		// Program bantuan berbentuk '50<program_id>'
		if ($lap > 50) {
			$program_id = preg_replace("/^50/", "", $lap);
			$this->db->select("nama");
			$this->db->where('id', $program_id);
			$q = $this->db->get('program');
			$program = $q->row_array();
			return $program['nama'];
		}

		switch("$lap"){
			case "kelas_sosial": return "Klasifikasi Sosial"; break;
			case "0": return "Pendidikan Dalam KK"; break;
			case "1": return "Pekerjaan"; break;
			case "2": return "Status Perkawinan"; break;
			case "3": return "Agama"; break;
			case "4": return "Jenis Kelamin"; break;
			case "5": return "Warga Negara"; break;
			case "6": return "Status"; break;
			case "7": return "Golongan Darah"; break;
			case "9": return "Cacat"; break;
			case "10": return "Sakit Menahun"; break;
			case "13": return "Umur"; break;
			case "14": return "Pendidikan Sedang Ditempuh"; break;
			case "15": return "Umur"; break;
			case "16": return "Akseptor KB"; break;
			case "17": return "Akte Kelahiran"; break;
			case "18": return "Kepemilikan Wajib KTP"; break;
			case "21": return "Klasifikasi Sosial"; break;
			case "24": return "Penerima BOS"; break;
			default: return "Pendidikan";
		}
	}

	function jenis_laporan($lap) {
		$jenis_laporan = "penduduk";
		if ($lap>50) {
			// Untuk program bantuan, $lap berbentuk '50<program_id>'
			$program_id = preg_replace('/^50/', '', $lap);
			$program = $this->program_bantuan_model->get_sasaran($program_id);
			// Hanya sasaran=1 yang sasarannya penduduk, yang lain keluarga atau kelompok
			if ($program['sasaran'] != 1) $jenis_laporan = "keluarga_kelompok";
		} elseif ($lap>20) {
			$jenis_laporan = "keluarga_kelompok";
		}
		return $jenis_laporan;
	}

	// $lap berbentuk '50<program_id>'
	function statistik_program_bantuan($lap=0){
		$program_id = preg_replace('/^50/', '', $lap);
		$program = $this->program_bantuan_model->get_sasaran($program_id);
		switch ($program['sasaran']) {
			case 1:
				# Data penduduk
				$sql = "SELECT
					(SELECT COUNT(p.id) FROM program_peserta p
						LEFT JOIN tweb_penduduk o ON p.peserta = o.nik
						WHERE p.program_id = $program_id AND o.status_dasar = 1) AS jumlah,
					(SELECT COUNT(p.id) FROM program_peserta p
						LEFT JOIN tweb_penduduk o ON p.peserta = o.nik
						WHERE p.program_id = $program_id AND o.sex = 1 AND o.status_dasar = 1) AS laki,
					(SELECT COUNT(p.id) FROM program_peserta p
						LEFT JOIN tweb_penduduk o ON p.peserta = o.nik
						WHERE p.program_id = $program_id AND o.sex = 2 AND o.status_dasar = 1) AS perempuan";
				//Total Sasaran
				$sql_sasaran = "SELECT
					(SELECT COUNT(s.id) FROM tweb_penduduk s WHERE s.status_dasar=1) AS jumlah,
					(SELECT COUNT(s.id) FROM tweb_penduduk s WHERE s.sex = 1 and s.status_dasar=1) AS laki,
					(SELECT COUNT(s.id) FROM tweb_penduduk s WHERE s.sex = 2 and s.status_dasar=1) AS perempuan";
				break;
			case 2:
				# Data KK
				# Kolom laki dan perempuan tidak dipakai
				$sql = "SELECT
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS jumlah,
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS laki,
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS perempuan
					";
				//Total Sasaran
				$sql_sasaran = "SELECT
					(SELECT COUNT(s.id) FROM tweb_keluarga s) AS jumlah,
					(SELECT COUNT(s.id) FROM tweb_keluarga s) AS laki,
					(SELECT COUNT(s.id) FROM tweb_keluarga s) AS perempuan";
				break;
			case 3:
				# Data Rumah Tangga
				# Kolom laki dan perempuan tidak dipakai
				$sql = "SELECT
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS jumlah,
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS laki,
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS perempuan
					";
				//Total Sasaran
				$sql_sasaran = "SELECT
					(SELECT COUNT(s.id) FROM tweb_rtm s) AS jumlah,
					(SELECT COUNT(s.id) FROM tweb_rtm s) AS laki,
					(SELECT COUNT(s.id) FROM tweb_rtm s) AS perempuan";
				break;
			case 4:
				# Data Kelompok
				# Kolom laki dan perempuan tidak dipakai
				$sql = "SELECT
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS jumlah,
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS laki,
					(SELECT COUNT(p.id) FROM program_peserta p WHERE p.program_id = $program_id) AS perempuan
					";
				//Total Sasaran
				$sql_sasaran = "SELECT
					(SELECT COUNT(s.id) FROM kelompok s) AS jumlah,
					(SELECT COUNT(s.id) FROM kelompok s) AS laki,
					(SELECT COUNT(s.id) FROM kelompok s) AS perempuan";
				break;

			default:
				# Tidak lakukan apa-apa
				break;
		}

		// Peserta
		$query = $this->db->query($sql);
		$data=$query->result_array();
		$data[0]['no']=1;
		$data[0]['nama']="PESERTA";

		// Total sasaran
		$query_sasaran = $this->db->query($sql_sasaran);
		$bel = $query_sasaran->row_array();

		// Yang tidak terdaftar
		$data[1]['no']=2;
		$data[1]['id']="";
		$data[1]['nama']="BUKAN PESERTA";
		$data[1]['jumlah']=$bel['jumlah']-$data[0]['jumlah'];
		$data[1]['perempuan']=$bel['perempuan']-$data[0]['perempuan'];
		$data[1]['laki']=$bel['laki']-$data[0]['laki'];

		$total['jumlah']=0;
		$total['laki']=0;
		$total['perempuan']=0;
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;
			$total['jumlah']+=$data[$i]['jumlah'];
			$total['laki']+=$data[$i]['laki'];
			$total['perempuan']+=$data[$i]['perempuan'];
			$i++;
		}

		$data[$i]['no']="";
		$data[$i]['id']=JUMLAH;
		$data[$i]['nama']="JUMLAH";
		$data[$i]['jumlah']=$total['jumlah'];
		$data[$i]['perempuan']=$total['perempuan'];
		$data[$i]['laki']=$total['laki'];

		$i++;
		$data[$i]['no']="";
		$data[$i]['id']=BELUM_MENGISI;
		$data[$i]['nama']="BELUM MENGISI";
		$data[$i]['jumlah']=$bel['jumlah']-$total['jumlah'];
		$data[$i]['perempuan']=$bel['perempuan']-$total['perempuan'];
		$data[$i]['laki']=$bel['laki']-$total['laki'];

		$i=0;
		while($i<count($data)){
			$data[$i]['persen']=$data[$i]['jumlah']/$bel['jumlah']*100;
			$data[$i]['persen']=number_format((float)$data[$i]['persen'], 2, '.', '');
			$data[$i]['persen']=$data[$i]['persen']."%";

			$data[$i]['persen1']=$data[$i]['laki']/$bel['jumlah']*100;
			$data[$i]['persen1']=number_format((float)$data[$i]['persen1'], 2, '.', '');
			$data[$i]['persen1']=$data[$i]['persen1']."%";

			$data[$i]['persen2']=$data[$i]['perempuan']/$bel['jumlah']*100;
			$data[$i]['persen2']=number_format((float)$data[$i]['persen2'], 2, '.', '');
			$data[$i]['persen2']=$data[$i]['persen2']."%";


			$i++;
		}

		$bel['no']="";
		$bel['id']="";
		$bel['nama']="TOTAL";
		$bel['persen']="100%";

		$bel['persen1']=$bel['laki']/$bel['jumlah']*100;
		$bel['persen1']=number_format((float)$bel['persen1'], 2, '.', '');
		$bel['persen1']=$bel['persen1']."%";

		$bel['persen2']=$bel['perempuan']/$bel['jumlah']*100;
		$bel['persen2']=number_format((float)$bel['persen2'], 2, '.', '');
		$bel['persen2']=$bel['persen2']."%";

		$data['total']=$bel;

		return $data;

	}

	function list_data($lap=0,$o=0){
		// Laporan program bantuan
		if ($lap > 50) {
			return $this->statistik_program_bantuan($lap, $o);
		}

		//Ordering SQL
		switch($o){
			case 1: $order_sql = ' ORDER BY u.id'; break;
			case 2: $order_sql = ' ORDER BY u.id DESC'; break;
			case 3: $order_sql = ' ORDER BY laki'; break;
			case 4: $order_sql = ' ORDER BY laki DESC'; break;
			case 5: $order_sql = ' ORDER BY jumlah'; break;
			case 6: $order_sql = ' ORDER BY jumlah DESC'; break;
			case 7: $order_sql = ' ORDER BY perempuan'; break;
			case 8: $order_sql = ' ORDER BY perempuan DESC'; break;
			default:$order_sql = '';
		}
		switch("$lap"){
			//Bagian Keluarga
			case 'kelas_sosial': $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE kelas_sosial = u.id) AS jumlah,(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE kelas_sosial = u.id AND p.sex = 1) AS laki,(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE kelas_sosial = u.id AND p.sex = 2) AS perempuan FROM tweb_keluarga_sejahtera u"; break;
			case "21": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE kelas_sosial = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM klasifikasi_analisis_keluarga u WHERE jenis='1'"; break;
			case "24": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE id_bos = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_bos u WHERE 1 "; break;

			// Bagian Penduduk
			case "0": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_kk_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_kk_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_kk_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_pendidikan_kk u WHERE 1"; break;

			case "1": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE pekerjaan_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE pekerjaan_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE pekerjaan_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_pekerjaan u WHERE 1 "; break;

			case "2": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_kawin = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_kawin = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE status_kawin = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_kawin u WHERE 1"; break;

			case "3": $sql   = "SELECT u.*,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE agama_id = u.id AND status_dasar = 1) AS jumlah,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE agama_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE agama_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_agama u
				WHERE 1"; break;

			case "4": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk  WHERE sex = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE sex = u.id AND sex=1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE sex = 2  AND sex=u.id AND status_dasar = 1) AS perempuan FROM tweb_penduduk_sex u WHERE 1"; break;

			case "5": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE warganegara_id = u.id AND status_dasar = 1 ) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE warganegara_id = u.id AND sex=1 AND status_dasar=1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE warganegara_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_warganegara u WHERE 1"; break;

			case "6": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE status = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE status = u.id AND sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE status = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM  tweb_penduduk_status u WHERE 1"; break;

			case "7": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE golongan_darah_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE golongan_darah_id = u.id AND sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE golongan_darah_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_golongan_darah u WHERE 1"; break;

			case "9": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE cacat_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE cacat_id = u.id AND  sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE cacat_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_cacat u WHERE 1"; break;

			case "10": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE sakit_menahun_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE sakit_menahun_id = u.id AND  sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE sakit_menahun_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_sakit_menahun u WHERE 1"; break;

			case "13": $sql   = "SELECT u.*, (SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND status_dasar = 1) AS jumlah, (SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND sex = 1 AND status_dasar = 1) AS laki, (SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_umur u WHERE status=1"; break;

			case "14": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_sedang_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_sedang_id = u.id AND sex = 1 AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE pendidikan_sedang_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_penduduk_pendidikan u WHERE left(nama,5)<> 'TAMAT'"; break;

			case "15": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= u.dari AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 <= u.sampai) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= u.dari AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 <= u.sampai AND sex=1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 >= u.dari AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 <= u.sampai AND sex=2) AS perempuan  FROM tweb_penduduk_umur u WHERE status is NULL "; break;

			case "16": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_penduduk WHERE cara_kb_id = u.id AND status_dasar = 1) AS jumlah,(SELECT COUNT(id) FROM tweb_penduduk WHERE cara_kb_id = u.id AND  sex=1  AND status_dasar = 1) AS laki,(SELECT COUNT(id) FROM tweb_penduduk WHERE cara_kb_id = u.id AND sex = 2 AND status_dasar = 1) AS perempuan FROM tweb_cara_kb u WHERE 1"; break;

			case "17": $sql   = "SELECT u.*, concat( dari, ' - ', sampai) as nama,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND akta_lahir <> '' AND status_dasar = 1) AS jumlah,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND sex = 1 AND akta_lahir <> '' AND status_dasar = 1) AS laki,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=u.dari AND (DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=u.sampai  AND sex = 2 AND akta_lahir <> '' AND status_dasar = 1) AS perempuan
				FROM tweb_penduduk_umur u
				WHERE status=1 ";
				break;

			case "18": $sql   = "SELECT u.*,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam AND status_dasar = 1) AS jumlah,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam AND sex = 1 AND status_dasar = 1) AS laki,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam AND sex = 2 AND status_dasar = 1) AS perempuan
				FROM tweb_status_ktp u
				WHERE 1 ";
				break;

			default:$sql   = "SELECT u.* FROM tweb_penduduk_pendidikan u WHERE 1 ";
		}

		$sql .= $order_sql;
		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		if ($lap==18){
			$sql3 = "SELECT (SELECT COUNT(p.id) FROM tweb_penduduk p WHERE ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND p.status_dasar=1) AS jumlah,
			(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND p.sex = 1 and status_dasar=1) AS laki,
			(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE ((DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND p.sex = 2 and status_dasar=1) AS perempuan";
		}
		elseif($lap<=20 AND "$lap" <> 'kelas_sosial'){
			$sql3 = "SELECT (SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.status_dasar=1) AS jumlah,
			(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.sex = 1 and status_dasar=1) AS laki,
			(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.sex = 2 and status_dasar=1) AS perempuan";
		}else{
			$sql3 = "SELECT (SELECT COUNT(k.id) FROM tweb_keluarga k WHERE 1) AS jumlah,
			(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.sex = 1) AS laki,
			(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.sex = 2) AS perempuan";
		}

		$query3 = $this->db->query($sql3);
		$bel = $query3->row_array();

		$total['jumlah']=0;
		$bel['no']="";
		$bel['id']="";
		$bel['nama']="TOTAL";
		$total['laki']=0;
		$total['perempuan']=0;
		$i=0;
		while($i<count($data)){
			$data[$i]['no']=$i+1;

			//if($data[$i]['jumlah']<1)
			//	$data[$i]['jumlah']="-";
			//else
				$total['jumlah']+=$data[$i]['jumlah'];

			//if($data[$i]['laki']<1)
			//	$data[$i]['laki']="-";
			//else
				$total['laki']+=$data[$i]['laki'];

			//if($data[$i]['perempuan']<1)
			//	$data[$i]['perempuan']="-";
			//else
				$total['perempuan']+=$data[$i]['perempuan'];

			$i++;
		}

		$data[$i]['no']="";
		$data[$i]['id']=JUMLAH;
		$data[$i]['nama']="JUMLAH";
		$data[$i]['jumlah']=$total['jumlah'];
		$data[$i]['perempuan']=$total['perempuan'];
		$data[$i]['laki']=$total['laki'];

		$i++;
		$data[$i]['no']="";
		$data[$i]['id']=BELUM_MENGISI;
		$data[$i]['nama']="BELUM MENGISI";
		$data[$i]['jumlah']=$bel['jumlah']-$total['jumlah'];
		$data[$i]['perempuan']=$bel['perempuan']-$total['perempuan'];
		$data[$i]['laki']=$bel['laki']-$total['laki'];

		$i=0;
		while($i<count($data)){
			$data[$i]['persen']=$data[$i]['jumlah']/$bel['jumlah']*100;
			$data[$i]['persen']=number_format((float)$data[$i]['persen'], 2, '.', '');
			$data[$i]['persen']=$data[$i]['persen']."%";

			$data[$i]['persen1']=$data[$i]['laki']/$bel['jumlah']*100;
			$data[$i]['persen1']=number_format((float)$data[$i]['persen1'], 2, '.', '');
			$data[$i]['persen1']=$data[$i]['persen1']."%";

			$data[$i]['persen2']=$data[$i]['perempuan']/$bel['jumlah']*100;
			$data[$i]['persen2']=number_format((float)$data[$i]['persen2'], 2, '.', '');
			$data[$i]['persen2']=$data[$i]['persen2']."%";


			$i++;
		}

			$bel['persen']="100%";

			$bel['persen1']=$bel['laki']/$bel['jumlah']*100;
			$bel['persen1']=number_format((float)$bel['persen1'], 2, '.', '');
			$bel['persen1']=$bel['persen1']."%";

			$bel['persen2']=$bel['perempuan']/$bel['jumlah']*100;
			$bel['persen2']=number_format((float)$bel['persen2'], 2, '.', '');
			$bel['persen2']=$bel['persen2']."%";

		$data['total']=$bel;
		return $data;
	}

	function get_config(){
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	function list_data_rentang(){
		$query = $this->db->where('status',1)->order_by('dari')->get('tweb_penduduk_umur');
		$data = $query->result_array();
		return $data;
	}

	function get_rentang($id=0){
		$sql   = "SELECT * FROM tweb_penduduk_umur WHERE id= $id ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	function get_rentang_terakhir(){
		$sql   = "SELECT (case when max(sampai) is null then '0' else (max(sampai)+1) end) as dari FROM tweb_penduduk_umur WHERE status=1 ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	function insert_rentang(){
		$data = $_POST;
		$data['status']=1;
		if ($data['sampai'] != '99999')
			$data['nama'] = $data['dari'].' s/d '.$data['sampai'].' Tahun';
		else
			$data['nama'] = 'Di atas '.$data['dari'].' Tahun';
		$outp = $this->db->insert('tweb_penduduk_umur',$data);

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function update_rentang($id=0){
		$data = $_POST;
		if ($data['sampai'] != '99999')
			$data['nama'] = $data['dari'].' s/d '.$data['sampai'].' Tahun';
		else
			$data['nama'] = 'Di atas '.$data['dari'].' Tahun';
		$outp = $this->db->where('id',$id)->update('tweb_penduduk_umur', $data);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_rentang($id=0){
		$sql   = "DELETE FROM tweb_penduduk_umur WHERE id='$id' ";
		$outp=$this->db->query($sql);
		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

	function delete_all_rentang(){
		$id_cb = $_POST['id_cb'];

		if(count($id_cb)){
			foreach($id_cb as $id){
				$sql  = "DELETE FROM tweb_penduduk_umur WHERE id=?";
				$outp = $this->db->query($sql,array($id));
			}
		}
		else $outp = false;

		if($outp) $_SESSION['success']=1;
			else $_SESSION['success']=-1;
	}

}

?>
