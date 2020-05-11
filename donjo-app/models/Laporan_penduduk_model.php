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

	protected function get_jumlah_sql($fk = false, $delimiter = false, $where = 0)
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

	protected function get_laki_sql($fk = false, $delimiter = false, $where = 0)
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

	protected function get_perempuan_sql($fk = false, $delimiter = false, $where = 0)
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
			"statistik/5"  => "Warga Negara",
			"statistik/19" => "Asuransi",
			"statistik/covid" => "Status Covid",
			"statistik/bantuan_penduduk" => "Penerima Bantuan (Penduduk)"
		);
		return $statistik;
	}

	public function link_statistik_keluarga()
	{
		$statistik = array(
			"statistik/kelas_sosial" => "Kelas Sosial",
			"statistik/bantuan_keluarga" => "Penerima Bantuan (keluarga)"
		);
		return $statistik;
	}

	public function link_statis_lainnya()
	{
		$statistik = array(
			"wilayah" => "Wilayah Administratif",
      'peraturan_desa' => 'Produk Hukum',
      'informasi_publik' => 'Informasi Publik',
      'peta' => 'Peta'
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
			case "covid": return "Status Covid"; break;
			case "21": return "Klasifikasi Sosial"; break;
			case "24": return "Penerima BOS"; break;
			case "bantuan_penduduk": return "Penerima Bantuan (Penduduk)"; break;
			case "bantuan_keluarga": return "Penerima Bantuan (Keluarga)"; break;
			default: return NULL;
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

	// -------------------- Siapkan data untuk statistik kependudukan -------------------

	protected function hitung_total(&$data)
	{
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
		return $total;
	}

	protected function hitung_persentase(&$data, $semua)
	{
		// Hitung semua presentase
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['persen'] = $data[$i]['jumlah']/$semua['jumlah']*100;
			$data[$i]['persen'] = number_format((float)$data[$i]['persen'], 2, '.', '');
			$data[$i]['persen'] = $data[$i]['persen']."%";

			$data[$i]['persen1'] = $data[$i]['laki']/$semua['jumlah']*100;
			$data[$i]['persen1'] = number_format((float)$data[$i]['persen1'], 2, '.', '');
			$data[$i]['persen1'] = $data[$i]['persen1']."%";

			$data[$i]['persen2'] = $data[$i]['perempuan']/$semua['jumlah']*100;
			$data[$i]['persen2'] = number_format((float)$data[$i]['persen2'], 2, '.', '');
			$data[$i]['persen2'] = $data[$i]['persen2']."%";
		}

		$data['total'] = $semua;
	}

	protected function baris_jumlah($total, $nama)
	{
		// Isi Total
		$baris_jumlah = array(
			'no' => "",
			'id' => JUMLAH,
			'nama' => $nama,
			'jumlah' => $total['jumlah'],
			'perempuan' => $total['perempuan'],
			'laki' => $total['laki']
		);
		return $baris_jumlah;
	}

	protected function baris_belum($semua, $total, $nama)
	{
		// Isi data jml belum mengisi
		$baris_belum = array(
			'no' => "",
			'id' => BELUM_MENGISI,
			'nama' => $nama,
			'jumlah' => $semua['jumlah'] - $total['jumlah'],
			'perempuan' => $semua['perempuan'] - $total['perempuan'],
			'laki' => $semua['laki'] - $total['laki']
		);
		return $baris_belum;
	}

	private function select_jml_penduduk_per_kategori($id_referensi, $tabel_referensi)
	{
		$this->db
			->select('u.*, COUNT(p.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
			->from("$tabel_referensi u")
			->join('tweb_penduduk p', "u.id = p.$id_referensi", 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left')
			->group_by('u.id');

		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
	}

	protected function data_jml_semua_penduduk()
	{
		$this->db
			->select('COUNT(b.id) AS jumlah')
		  ->select('COUNT(CASE WHEN b.sex = 1 THEN b.id END) AS laki')
		  ->select('COUNT(CASE WHEN b.sex = 2 THEN b.id END) AS perempuan')
			->from('penduduk_hidup b')
			->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id', 'left');

		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);

		$semua = $this->db->get()->row_array();
		return $semua;
	}

	protected function data_jml_semua_keluarga()
	{
		// Data jumlah
		$semua = $this->db
			->select('COUNT(k.id) as jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
		  ->from('tweb_keluarga k')
		  ->join('tweb_penduduk p', 'p.id=k.nik_kepala', 'left')
		  ->get()->row_array();
		return $semua;
	}

	protected function persentase_semua($semua)
	{
		// Hitung persentase
		$semua['no'] = "";
		$semua['id'] = "";
		$semua['nama'] = "TOTAL";
		$semua['persen'] = "100%";

		$semua['persen1'] = $semua['laki']/$semua['jumlah']*100;
		$semua['persen1'] = number_format((float)$semua['persen1'], 2, '.', '');
		$semua['persen1'] = $semua['persen1']."%";

 		$semua['persen2'] = $semua['perempuan']/$semua['jumlah']*100;
		$semua['persen2'] = number_format((float)$semua['persen2'], 2, '.', '');
		$semua['persen2'] = $semua['persen2']."%";
		return $semua;
	}

	private function order_by($o)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $this->db->order_by('u.id'); break;
			case 2: $this->db->order_by('u.id DESC'); break;
			case 3: $this->db->order_by('laki'); break;
			case 4: $this->db->order_by('laki DESC'); break;
			case 5: $this->db->order_by('jumlah'); break;
			case 6: $this->db->order_by('jumlah DESC'); break;
			case 7: $this->db->order_by('perempuan'); break;
			case 8: $this->db->order_by('perempuan DESC'); break;
		}
	}

	private function str_jml_penduduk($where)
	{
		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
		$str_jml_penduduk = $this->db->select('COUNT(b.id)')
			->from('penduduk_hidup b')
			->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id')
			->where($where)
			->get_compiled_select();
		return $str_jml_penduduk;
	}

	private function str_jml_laki($where)
	{
		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
		$str_jml_laki = $this->db->select('COUNT(b.id)')
			->from('penduduk_hidup b')
			->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id')
			->where($where)
			->where('b.sex', '1')
			->get_compiled_select();
		return $str_jml_laki;
	}

	private function str_jml_perempuan($where)
	{
		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
		$str_jml_perempuan = $this->db->select('COUNT(b.id)')
			->from('penduduk_hidup b')
			->join('tweb_wil_clusterdesa a', 'b.id_cluster = a.id')
			->where($where)
			->where('b.sex', '2')
			->get_compiled_select();
		return $str_jml_perempuan;
	}

	public function list_data($lap=0, $o=0)
	{
		// Penerima program bantuan secara menyeluruh
		if ($lap == 'bantuan_penduduk')
		{
			$this->load->model('statistik_penduduk_model');
			return $this->statistik_penduduk_model->list_data($o);
		}

		if ($lap == 'bantuan_keluarga')
		{
			$this->load->model('Statistik_keluarga_model');
			return $this->Statistik_keluarga_model->list_data($o);
		}

		// Laporan program bantuan
		if ($lap > 50)
		{
			return $this->statistik_program_bantuan($lap, $o);
		}

		// Bagian Penduduk
		$statistik_penduduk = array(
			"0" => array('id_referensi' => "pendidikan_kk_id", 'tabel_referensi' => "tweb_penduduk_pendidikan_kk"),
			"1" => array('id_referensi' => "pekerjaan_id", 'tabel_referensi' => "tweb_penduduk_pekerjaan"),
			"2" => array('id_referensi' => "status_kawin", 'tabel_referensi' => "tweb_penduduk_kawin"),
			"3" => array('id_referensi' => "agama_id", 'tabel_referensi' => "tweb_penduduk_agama"),
			"4" => array('id_referensi' => "sex", 'tabel_referensi' => "tweb_penduduk_sex"),
			"5" => array('id_referensi' => "warganegara_id", 'tabel_referensi' => "tweb_penduduk_warganegara"),
			"6" => array('id_referensi' => "status", 'tabel_referensi' => "tweb_penduduk_status"),
			"7" => array('id_referensi' => "golongan_darah_id", 'tabel_referensi' => "tweb_golongan_darah"),
			"9" => array('id_referensi' => "cacat_id", 'tabel_referensi' => "tweb_cacat"),
			"10" => array('id_referensi' => "sakit_menahun_id", 'tabel_referensi' => "tweb_sakit_menahun"),
			"14" => array('id_referensi' => "pendidikan_sedang_id", 'tabel_referensi' => "tweb_penduduk_pendidikan"),
			"16" => array('id_referensi' => "cara_kb_id", 'tabel_referensi' => "tweb_cara_kb"),
			"19" => array('id_referensi' => "id_asuransi", 'tabel_referensi' => "tweb_penduduk_asuransi")
		);

		switch ("$lap")
		{
			//Bagian Keluarga
			case 'kelas_sosial':
				$this->db
					->select('u.*, COUNT(k.id) as jumlah')
				  ->select('COUNT(CASE WHEN kelas_sosial = u.id AND p.sex = 1 THEN p.id END) AS laki')
				  ->select('COUNT(CASE WHEN kelas_sosial = u.id AND p.sex = 2 THEN p.id END) AS perempuan')
				  ->from('tweb_keluarga_sejahtera u')
				  ->join('tweb_keluarga k', 'k.kelas_sosial = u.id', 'left')
				  ->join('tweb_penduduk p', 'p.id=k.nik_kepala', 'left')
				  ->group_by('u.id');
				break;

			//STATUS_COVID
			case 'covid':
				$this->db
					->select('u.*, COUNT(k.id) as jumlah')
				  ->select('COUNT(CASE WHEN k.status_covid = u.nama AND p.sex = 1 THEN k.id_terdata END) AS laki')
				  ->select('COUNT(CASE WHEN k.status_covid = u.nama AND p.sex = 2 THEN k.id_terdata END) AS perempuan')
				  ->from('ref_status_covid u')
				  ->join('covid19_pemudik k', 'k.status_covid = u.nama', 'left')
				  ->join('tweb_penduduk p', 'p.id=k.id_terdata', 'left')
				  ->group_by('u.id');
				break;

			case "13":
				$where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai";
				$str_jml_penduduk = $this->str_jml_penduduk($where);
				$str_jml_laki = $this->str_jml_laki($where);
				$str_jml_perempuan = $this->str_jml_perempuan($where);
				$this->db->select('u.*')
					->select("($str_jml_penduduk) as jumlah")
					->select("($str_jml_laki) as laki")
					->select("($str_jml_perempuan) as perempuan")
					->from('tweb_penduduk_umur u')
					->where('u.status', "1");
				break;

			case "15":
				$sql = $this->statistik_penduduk_sql($lap, false, "tweb_penduduk_umur");
				break;

			case "17":
				$sql = $this->statistik_penduduk_sql($lap, false, "tweb_penduduk_umur");
				break;

			case "18":
				$sql = $this->statistik_penduduk_sql($lap, false, "tweb_status_ktp");
				break;

			default:
				$this->select_jml_penduduk_per_kategori($statistik_penduduk["0"]['id_referensi'], $statistik_penduduk["0"]['tabel_referensi']);
		}

		$this->order_by($o);
		$data = $this->db->get()->result_array();

		//Siapkan data baris rekap
		if ($lap == 18)
		{
			$this->db->where("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1))");
			$semua = $this->data_jml_semua_penduduk();
		}
		elseif (($lap<=20 OR $lap=='covid') AND ("$lap" <> 'kelas_sosial' OR "$lap" <> 'bantuan_penduduk' OR "$lap" <> 'bantuan_keluarga'))
		{
			$semua = $this->data_jml_semua_penduduk();
		}
		else
		{
			$semua = $this->data_jml_semua_keluarga();
		}
		$semua = $this->persentase_semua($semua);
		$total = $this->hitung_total($data);
		$data[] = $this->baris_jumlah($total, 'JUMLAH');
		$data[] = $this->baris_belum($semua, $total, 'BELUM MENGISI');
		$this->hitung_persentase($data, $semua);

		return $data;
	}

	// -------------------- Akhir siapkan data untuk statistik kependudukan -------------------


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

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_rentang($id=0)
	{
		$data = $_POST;
		if ($data['sampai'] != '99999')
			$data['nama'] = $data['dari'].' s/d '.$data['sampai'].' Tahun';
		else
			$data['nama'] = 'Di atas '.$data['dari'].' Tahun';
		$outp = $this->db->where('id',$id)->update('tweb_penduduk_umur', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_rentang($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('tweb_penduduk_umur');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_all_rentang()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete_rentang($id, $semua=true);
		}
	}

}

?>
