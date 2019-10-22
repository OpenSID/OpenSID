<?php class Laporan_penduduk_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('program_bantuan_model');
	}

	public function autocomplete()
	{
		$str = autocomplete_str('dusun_nama', 'dusun_nama');
		return $str;
	}

	public function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.nama LIKE '$kw'";
			return $search_sql;
		}
	}

	public function list_dusun()
	{
		$sql = $this->db
			->select()
			->from('tweb_wil_clusterdesa')
			->where('rt', '0')
			->where('rw', '0')
			->get();
		return $sql->result_array();
	}

	public function list_rw($dusun = '')
	{
		$sql = $this->db
			->select()
			->from('tweb_wil_clusterdesa')
			->where('rt', '0')
			->where('dusun', $dusun)
			->where('rw <>', '0')
			->get();
		return $sql->result_array();
	}

	public function list_rt($dusun = '', $rw = '')
	{
		$sql = $this->db
			->select()
			->from('tweb_wil_clusterdesa')
			->where('dusun', $dusun)
			->where('rw', $rw)
			->where('rt <>', '0')
			->get();
		return $sql->result_array();
	}

	private function dusun_sql()
	{
		if ($dusun = $this->session->userdata("dusun")) {
			$sql = "AND a.dusun = '$dusun' ";
			return $sql;
		}
	}

	private function rw_sql()
	{
		if ($rw = $this->session->userdata("rw")) {
			$sql = "AND a.rw = '$rw' ";
			return $sql;
		}
	}

	private function rt_sql()
	{
		if ($rt = $this->session->userdata("rt")) {
			$sql = "AND a.rt = '$rt' ";
			return $sql;
		}
	}

	private function get_jumlah_sql($fk = false, $delimiter = false, $where = 0)
	{
		$sql = "(SELECT COUNT(b.id) FROM penduduk_hidup b
						LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
						WHERE 1 ";
		$sql .= $fk ? "AND $fk = u.id " : "";
		$sql .= $where ? : '';
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= ") AS jumlah";
		$sql .= $delimiter ? ',' : '';

		return $sql;
	}

	private function get_laki_sql($fk = false, $delimiter = false, $where = 0)
	{
		$sql = "(SELECT COUNT(b.id) FROM penduduk_hidup b
						LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
						WHERE sex = 1 ";
		$sql .= $fk ? "AND $fk = u.id " : "";
		$sql .= $where ? : '';
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= ") AS laki";
		$sql .= $delimiter ? ',' : '';

		return $sql;
	}

	private function get_perempuan_sql($fk = false, $delimiter = false, $where = 0)
	{
		$sql = "(SELECT COUNT(b.id) FROM penduduk_hidup b
						LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
						WHERE sex = 2 ";
		$sql .= $fk ? "AND $fk = u.id " : "";
		$sql .= $where ? : '';
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();
		$sql .= ") AS perempuan";
		$sql .= $delimiter ? ',' : '';

		return $sql;
	}

	private function statistik_penduduk_sql($lap = 0, $fk = false, $tabel_referensi)
	{
		switch($lap){
			case 13:
				// rentang umur
				$where = "AND (DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai ";

				$sql = "SELECT u.*,";
				$sql .= $this->get_jumlah_sql($fk, true, $where);
				$sql .= $this->get_laki_sql($fk, true, $where);
				$sql .= $this->get_perempuan_sql($fk, false, $where);
				$sql .= " FROM $tabel_referensi u";
				$sql .= " WHERE status = 1";
				break;

			case 15:
				$where = "AND (DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai ";

				$sql = "SELECT u.*,";
				$sql .= $this->get_jumlah_sql($fk, true, $where);
				$sql .= $this->get_laki_sql($fk, true, $where);
				$sql .= $this->get_perempuan_sql($fk, false, $where);
				$sql .= " FROM $tabel_referensi u";
				$sql .= " WHERE status = NULL";
				break;

			case 17:
				// akta kelahiran
				$where = "AND (DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai AND akta_lahir <> '' ";

				$sql = "SELECT u.*, concat( dari, ' - ', sampai) as nama,";
				$sql .= $this->get_jumlah_sql($fk, true, $where);
				$sql .= $this->get_laki_sql($fk, true, $where);
				$sql .= $this->get_perempuan_sql($fk, false, $where);
				$sql .= " FROM $tabel_referensi u";
				$sql .= " WHERE status = 1";
				break;

			case 18:
				// kepemilikan ktp
				$where = " AND ((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam ";

				$sql = "SELECT u.*,";
				$sql .= $this->get_jumlah_sql($fk, true, $where);
				$sql .= $this->get_laki_sql($fk, true, $where);
				$sql .= $this->get_perempuan_sql($fk, false, $where);
				$sql .= " FROM $tabel_referensi u";
				break;

			default:
				$sql = "SELECT u.*,";
				$sql .= $this->get_jumlah_sql($fk, true);
				$sql .= $this->get_laki_sql($fk, true);
				$sql .= $this->get_perempuan_sql($fk);
				$sql .= " FROM $tabel_referensi u";
				break;
		}

		return $sql;
	}

	public function link_statistik_penduduk()
	{
		$statistik = array(
			"statistik/3"  => "Agama",
			"statistik/17" => "Akte Kelahiran",
			"statistik/16" => "Akseptor KB",
			"dpt" 				 => "Calon Pemilih",
			"statistik/9"  => "Cacat",
			"statistik/7"  => "Golongan Darah",
			"statistik/4"  => "Jenis Kelamin",
			"statistik/0"  => "Pendidikan Dalam KK",
			"statistik/14" => "Pendidikan Sedang Ditempuh",
			"statistik/10" => "Penyakit Menahun",
			"statistik/1"  => "Pekerjaan",
			"statistik/6"  => "Status Penduduk",
			"statistik/2"  => "Status Perkawinan",
			"statistik/13" => "Umur",
			"statistik/18" => "Kepemilikan Wajib KTP",
			"statistik/5"  => "Warga Negara"
		);
		return $statistik;
	}

	public function link_statistik_keluarga()
	{
		$statistik = array(
			"statistik/kelas_sosial" => "Kelas Sosial"
		);
		return $statistik;
	}

	public function link_statis_lainnya()
	{
		$statistik = array(
			"wilayah" => "Wilayah Administratif",
      'peraturan_desa' => 'Produk Hukum',
      'informasi_publik' => 'Informasi Publik'
		);
		return $statistik;
	}

	public function judul_statistik($lap)
	{
		// Program bantuan berbentuk '50<program_id>'
		if ($lap > 50)
		{
			$program_id = preg_replace("/^50/", "", $lap);
			$this->db->select("nama");
			$this->db->where('id', $program_id);
			$q = $this->db->get('program');
			$program = $q->row_array();
			return $program['nama'];
		}

		switch ("$lap")
		{
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
			case "19": return "Jenis Asuransi"; break;
			case "21": return "Klasifikasi Sosial"; break;
			case "24": return "Penerima BOS"; break;
			default: return "Pendidikan";
		}
	}

	public function jenis_laporan($lap)
	{
		$jenis_laporan = "penduduk";
		if ($lap>50)
		{
			// Untuk program bantuan, $lap berbentuk '50<program_id>'
			$program_id = preg_replace('/^50/', '', $lap);
			$program = $this->program_bantuan_model->get_sasaran($program_id);
			// Hanya sasaran=1 yang sasarannya penduduk, yang lain keluarga atau kelompok
			if ($program['sasaran'] != 1) $jenis_laporan = "keluarga_kelompok";
		}
		elseif ($lap>20)
		{
			$jenis_laporan = "keluarga_kelompok";
		}
		return $jenis_laporan;
	}

	// $lap berbentuk '50<program_id>'
	public function statistik_program_bantuan($lap=0)
	{
		$program_id = preg_replace('/^50/', '', $lap);
		$program = $this->program_bantuan_model->get_sasaran($program_id);
		switch ($program['sasaran'])
		{
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
					(SELECT COUNT(s.id) FROM keluarga_aktif s) AS jumlah,
					(SELECT COUNT(s.id) FROM keluarga_aktif s) AS laki,
					(SELECT COUNT(s.id) FROM keluarga_aktif s) AS perempuan";
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
		$data = $query->result_array();
		$data[0]['no'] = 1;
		$data[0]['nama'] ="PESERTA";

		// Total sasaran
		$query_sasaran = $this->db->query($sql_sasaran);
		$bel = $query_sasaran->row_array();

		// Yang tidak terdaftar
		$data[1]['no'] = 2;
		$data[1]['id'] = "";
		$data[1]['nama'] = "BUKAN PESERTA";
		$data[1]['jumlah'] = $bel['jumlah'] - $data[0]['jumlah'];
		$data[1]['perempuan'] = $bel['perempuan'] - $data[0]['perempuan'];
		$data[1]['laki'] = $bel['laki'] - $data[0]['laki'];

		$total['jumlah'] = 0;
		$total['laki'] = 0;
		$total['perempuan'] = 0;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$total['jumlah'] += $data[$i]['jumlah'];
			$total['laki'] += $data[$i]['laki'];
			$total['perempuan'] += $data[$i]['perempuan'];
		}

		$data[$i]['no'] = "";
		$data[$i]['id'] = JUMLAH;
		$data[$i]['nama'] = "JUMLAH";
		$data[$i]['jumlah'] = $total['jumlah'];
		$data[$i]['perempuan'] = $total['perempuan'];
		$data[$i]['laki'] = $total['laki'];

		$i++;
		$data[$i]['no'] = "";
		$data[$i]['id'] = BELUM_MENGISI;
		$data[$i]['nama'] = "BELUM MENGISI";
		$data[$i]['jumlah'] = $bel['jumlah'] - $total['jumlah'];
		$data[$i]['perempuan'] = $bel['perempuan'] - $total['perempuan'];
		$data[$i]['laki'] = $bel['laki'] - $total['laki'];

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['persen'] = $data[$i]['jumlah']/$bel['jumlah']*100;
			$data[$i]['persen'] = number_format((float)$data[$i]['persen'], 2, '.', '');
			$data[$i]['persen'] = $data[$i]['persen']."%";

			$data[$i]['persen1'] = $data[$i]['laki']/$bel['jumlah']*100;
			$data[$i]['persen1'] = number_format((float)$data[$i]['persen1'], 2, '.', '');
			$data[$i]['persen1'] = $data[$i]['persen1']."%";

			$data[$i]['persen2'] = $data[$i]['perempuan']/$bel['jumlah']*100;
			$data[$i]['persen2'] = number_format((float)$data[$i]['persen2'], 2, '.', '');
			$data[$i]['persen2'] = $data[$i]['persen2']."%";
		}

		$bel['no'] = "";
		$bel['id'] = "";
		$bel['nama'] = "TOTAL";
		$bel['persen'] = "100%";

		$bel['persen1'] = $bel['laki']/$bel['jumlah']*100;
		$bel['persen1'] = number_format((float)$bel['persen1'], 2, '.', '');
		$bel['persen1'] = $bel['persen1']."%";

		$bel['persen2'] = $bel['perempuan']/$bel['jumlah']*100;
		$bel['persen2'] = number_format((float)$bel['persen2'], 2, '.', '');
		$bel['persen2'] = $bel['persen2']."%";

		$data['total'] = $bel;

		return $data;

	}

	public function list_data($lap=0, $o=0)
	{
		// Laporan program bantuan
		if ($lap > 50)
		{
			return $this->statistik_program_bantuan($lap, $o);
		}

		//Ordering SQL
		switch ($o)
		{
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
		switch ("$lap")
		{
			//Bagian Keluarga
			case 'kelas_sosial': $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE kelas_sosial = u.id) AS jumlah,(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE kelas_sosial = u.id AND p.sex = 1) AS laki,(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE kelas_sosial = u.id AND p.sex = 2) AS perempuan FROM tweb_keluarga_sejahtera u"; break;
			case "21": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE kelas_sosial = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM klasifikasi_analisis_keluarga u WHERE jenis='1'"; break;
			case "24": $sql   = "SELECT u.*,(SELECT COUNT(id) FROM tweb_keluarga WHERE id_bos = u.id) AS jumlah,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS laki,(SELECT COUNT(id) FROM tweb_keluarga WHERE 0) AS perempuan FROM ref_bos u WHERE 1 "; break;

			// Bagian Penduduk
			case "0":
				$sql = $this->statistik_penduduk_sql($lap, "pendidikan_kk_id", "tweb_penduduk_pendidikan_kk");
				break;

			case "1":
				$sql = $this->statistik_penduduk_sql($lap, "pekerjaan_id", "tweb_penduduk_pekerjaan");
				break;

			case "2":
				$sql = $this->statistik_penduduk_sql($lap, "status_kawin", "tweb_penduduk_kawin");
				break;

			case "3":
				$sql = $this->statistik_penduduk_sql($lap, "agama_id", "tweb_penduduk_agama");
				break;

			case "4":
				$sql = $this->statistik_penduduk_sql($lap, "sex", "tweb_penduduk_sex");
				break;

			case "5":
				$sql = $this->statistik_penduduk_sql($lap, "warganegara_id", "tweb_penduduk_warganegara");
				break;

			case "6":
				$sql = $this->statistik_penduduk_sql($lap, "status", "tweb_penduduk_status");
				break;

			case "7":
				$sql = $this->statistik_penduduk_sql($lap, "golongan_darah_id", "tweb_golongan_darah");
				break;

			case "9":
				$sql = $this->statistik_penduduk_sql($lap, "cacat_id", "tweb_cacat");
				break;

			case "10":
				$sql = $this->statistik_penduduk_sql($lap, "sakit_menahun_id", "tweb_sakit_menahun");
				break;

			case "13":
				$sql = $this->statistik_penduduk_sql($lap, false, "tweb_penduduk_umur");
				break;

			case "14":
				$sql = $this->statistik_penduduk_sql($lap, "pendidikan_sedang_id", "tweb_penduduk_pendidikan");
				$sql .= " WHERE left(nama,5)<> 'TAMAT'";
				break;

			case "15":
				$sql = $this->statistik_penduduk_sql($lap, false, "tweb_penduduk_umur");
				break;

			case "16":
				$sql = $this->statistik_penduduk_sql($lap, "cara_kb_id", "tweb_cara_kb");
				break;

			case "17":
				$sql = $this->statistik_penduduk_sql($lap, false, "tweb_penduduk_umur");
				break;

			case "18":
				$sql = $this->statistik_penduduk_sql($lap, false, "tweb_status_ktp");
				break;

			case "19":
				$sql = $this->statistik_penduduk_sql($lap, "id_asuransi", "tweb_penduduk_asuransi");
				break;

			default:
				$sql = "SELECT u.* FROM tweb_penduduk_pendidikan u WHERE 1 ";
		}

		$sql .= $order_sql;
		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		if ($lap == 18)
		{
			$where = " AND ((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) ";

			$sql3 = "SELECT ";
			$sql3 .= $this->get_jumlah_sql(false, true, $where);
			$sql3 .= $this->get_laki_sql(false, true, $where);
			$sql3 .= $this->get_perempuan_sql(false, false, $where);
		}
		elseif ($lap<=20 AND "$lap" <> 'kelas_sosial')
		{
			$sql3 = "SELECT ";
			$sql3 .= $this->get_jumlah_sql(false, true);
			$sql3 .= $this->get_laki_sql(false, true);
			$sql3 .= $this->get_perempuan_sql(false);
		}
		else
		{
			$sql3 = "SELECT (SELECT COUNT(k.id) FROM tweb_keluarga k WHERE 1) AS jumlah,
			(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.sex = 1) AS laki,
			(SELECT COUNT(k.id) FROM tweb_keluarga k INNER JOIN tweb_penduduk p ON k.nik_kepala=p.id  WHERE p.sex = 2) AS perempuan";
		}

		$query3 = $this->db->query($sql3);
		$bel = $query3->row_array();

		$total['jumlah'] = 0;
		$bel['no'] = "";
		$bel['id'] = "";
		$bel['nama'] = "TOTAL";
		$total['laki'] = 0;
		$total['perempuan'] = 0;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$total['jumlah'] += $data[$i]['jumlah'];
			$total['laki'] += $data[$i]['laki'];
			$total['perempuan'] += $data[$i]['perempuan'];
		}

		$data[$i]['no'] = "";
		$data[$i]['id'] = JUMLAH;
		$data[$i]['nama'] = "JUMLAH";
		$data[$i]['jumlah'] = $total['jumlah'];
		$data[$i]['perempuan'] = $total['perempuan'];
		$data[$i]['laki'] = $total['laki'];

		$i++;
		$data[$i]['no'] = "";
		$data[$i]['id'] = BELUM_MENGISI;
		$data[$i]['nama'] = "BELUM MENGISI";
		$data[$i]['jumlah'] = $bel['jumlah'] - $total['jumlah'];
		$data[$i]['perempuan'] = $bel['perempuan'] - $total['perempuan'];
		$data[$i]['laki'] = $bel['laki'] - $total['laki'];

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['persen'] = $data[$i]['jumlah']/$bel['jumlah']*100;
			$data[$i]['persen'] = number_format((float)$data[$i]['persen'], 2, '.', '');
			$data[$i]['persen'] = $data[$i]['persen']."%";

			$data[$i]['persen1'] = $data[$i]['laki']/$bel['jumlah']*100;
			$data[$i]['persen1'] = number_format((float)$data[$i]['persen1'], 2, '.', '');
			$data[$i]['persen1'] = $data[$i]['persen1']."%";

			$data[$i]['persen2'] = $data[$i]['perempuan']/$bel['jumlah']*100;
			$data[$i]['persen2'] = number_format((float)$data[$i]['persen2'], 2, '.', '');
			$data[$i]['persen2'] = $data[$i]['persen2']."%";
		}

		$bel['persen'] = "100%";

		$bel['persen1'] = $bel['laki']/$bel['jumlah']*100;
		$bel['persen1'] = number_format((float)$bel['persen1'], 2, '.', '');
		$bel['persen1'] = $bel['persen1']."%";

 		$bel['persen2'] = $bel['perempuan']/$bel['jumlah']*100;
		$bel['persen2'] = number_format((float)$bel['persen2'], 2, '.', '');
		$bel['persen2'] = $bel['persen2']."%";

		$data['total'] = $bel;
		return $data;
	}

	public function get_config()
	{
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	public function list_data_rentang()
	{
		$query = $this->db->where('status', 1)->order_by('dari')->get('tweb_penduduk_umur');
		$data = $query->result_array();
		return $data;
	}

	public function get_rentang($id=0)
	{
		$sql = "SELECT * FROM tweb_penduduk_umur WHERE id = $id ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	public function get_rentang_terakhir()
	{
		$sql = "SELECT (case when max(sampai) is null then '0' else (max(sampai)+1) end) as dari FROM tweb_penduduk_umur WHERE status = 1 ";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	public function insert_rentang()
	{
		$data = $_POST;
		$data['status'] = 1;
		if ($data['sampai'] != '99999')
			$data['nama'] = $data['dari'].' s/d '.$data['sampai'].' Tahun';
		else
			$data['nama'] = 'Di atas '.$data['dari'].' Tahun';
		$outp = $this->db->insert('tweb_penduduk_umur', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function update_rentang($id=0)
	{
		$data = $_POST;
		if ($data['sampai'] != '99999')
			$data['nama'] = $data['dari'].' s/d '.$data['sampai'].' Tahun';
		else
			$data['nama'] = 'Di atas '.$data['dari'].' Tahun';
		$outp = $this->db->where('id',$id)->update('tweb_penduduk_umur', $data);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_rentang($id=0)
	{
		$sql = "DELETE FROM tweb_penduduk_umur WHERE id = '$id' ";
		$outp = $this->db->query($sql);
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_all_rentang()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM tweb_penduduk_umur WHERE id = ?";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

}

?>
