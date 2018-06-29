<?php class Penduduk_log_model extends CI_Model{

	function __construct(){
		parent::__construct();

		$this->load->model('penduduk_model');
	}

	/**
	 * Ambil data log penduduk
	 *
	 * @param $id_log 					id log penduduk
	 * @return array(data log)
	 */
	function get_log($id_log)
	{
		$log = $this->db
					->select("s.nama as status, date_format(tgl_peristiwa, '%d-%m-%Y') as tgl_peristiwa, id_detail, catatan")
					->where('l.id', $id_log)
					->join('tweb_penduduk p','l.id_pend = p.id')
					->join('tweb_status_dasar s','s.id = p.status_dasar')
					->get('log_penduduk l')->row_array();
		if (empty($log['tgl_peristiwa'])) $log['tgl_peristiwa'] = date("d-m-Y");
		return $log;
	}

	/**
	 * Update log penduduk
	 *
	 * @param $id_log 					id log penduduk
	 * @return void
	 */
	function update($id_log)
	{
		unset($_SESSION['success']);
		$data = $this->input->post();
		$data['tgl_peristiwa'] = rev_tgl($data['tgl_peristiwa']);
		if (!$this->db->where('id', $id_log)->update('log_penduduk', $data))
			$_SESSION['success'] = -1;
	}

	/**
	 * Kembalikan status dasar penduduk ke hidup
	 *
	 * @param $id_log 			id log penduduk
	 * @return void
	 */
	public function kembalikan_status($id_log)
	{
		$log = $this->db->where('id', $id_log)->get('log_penduduk')->row();
		$data['status_dasar'] = 1; // status dasar hidup
		if (!$this->db->where('id',$log->id_pend)->update('tweb_penduduk', $data))
			$_SESSION['success'] = - 1;
		// Hapus log penduduk
		if (!$this->db->where('id', $id_log)->delete('log_penduduk'))
			$_SESSION['success'] = - 1;
	}

	/**
	 * Kembalikan status dasar sekumpulan penduduk ke hidup
	 *
	 * @param
	 * @return void
	 */
	public function kembalikan_status_all()
	{
		unset($_SESSION['success']);
		$id_cb = $_POST['id_cb'];
		foreach($id_cb as $id)
		{
			$this->kembalikan_status($id);
		}
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

	function status_dasar_sql()
	{
		if(isset($_SESSION['status_dasar'])){
			$kf = $_SESSION['status_dasar'];
				$sql= " AND u.status_dasar = $kf";
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

	function agama_sql()
	{
		if(isset($_SESSION['agama'])){
			$kf = $_SESSION['agama'];
			$sql= " AND u.agama_id = $kf";
		return $sql;
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

	function paging($p=1,$o=0)
	{

		$list_data_sql = $this->list_data_sql($log);
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
	//
	// Batasi pada rekaman ubah status dasar saja, untuk ditampilkan di Log Penduduk.
	// Yaitu, batasi pada id_detail berikut:
	//   2 = status menjadi mati
	//   3 = status menjadi pindah
	//   4 = status menjadi hilang

	private function list_data_sql()
	{
		$sql = "
		FROM tweb_penduduk u
		LEFT JOIN tweb_keluarga d ON u.id_kk = d.id
		LEFT JOIN tweb_wil_clusterdesa a ON d.id_cluster = a.id
		LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
		LEFT JOIN tweb_penduduk_agama g ON u.agama_id = g.id
		LEFT JOIN tweb_status_dasar sd ON u.status_dasar = sd.id
		LEFT JOIN log_penduduk log ON u.id = log.id_pend
		WHERE u.status_dasar > 1
		AND log.id_detail IN (2,3,4)
		";

		$sql .= $this->search_sql();
		$sql .= $this->status_dasar_sql();
		$sql .= $this->sex_sql();
		$sql .= $this->agama_sql();
		$sql .= $this->dusun_sql();
		$sql .= $this->rw_sql();
		$sql .= $this->rt_sql();

		return $sql;
	}

	function list_data($o=0,$offset=0,$limit=500)
	{

		$select_sql = "SELECT u.id,u.nik,u.tanggallahir,sd.nama as status_dasar,u.id_kk,u.nama,a.dusun,a.rw,a.rt,d.alamat,log.id as id_log,log.no_kk AS no_kk,log.catatan as catatan,log.nama_kk as nama_kk,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(log.tgl_peristiwa)-TO_DAYS(u.tanggallahir)), '%Y')+0) AS umur_pada_peristiwa,
			x.nama AS sex,g.nama AS agama,log.tanggal,log.tgl_peristiwa,log.id_detail
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
			case 7: $order_sql = ' ORDER BY umur_pada_peristiwa'; break;
			case 8: $order_sql = ' ORDER BY umur_pada_peristiwa DESC'; break;
			// Untuk Log Penduduk
			case 9: $order_sql = ' ORDER BY log.tgl_peristiwa'; break;
			case 10: $order_sql = ' ORDER BY log.tgl_peristiwa DESC'; break;
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

}
