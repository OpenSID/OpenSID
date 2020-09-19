<?php class Statistik_penduduk_model extends Laporan_penduduk_model {

/* Gunakan model ini untuk mulai refactor statistik penduduk
 * Mungkin bisa gunakan anonymous classes yg disediakan di PHP 7.x
 * Usahakan supaya di Laporan_penduduk_model juga menggunakan query builder Codeigniter
*/

	public function __construct()
	{
		parent::__construct();
	}

	public function statistik($lap)
	{
		switch ($lap)
		{
			case 'bantuan_penduduk':
				$statistik = new Penduduk_penerima_bantuan();
				break;
			case 'bantuan_keluarga':
				$statistik = new Keluarga_penerima_bantuan();
				break;
		}
		return $statistik;
	}
}

/*
 * ==============================================================
 * Semua pengaturan untuk masing2 statistik kependudukan.
 * Dipanggil dari donjo-app/models/Laporan_penduduk_model.php
 * ==============================================================
 */

class Penduduk_penerima_bantuan extends Statistik_penduduk_model {

	public $judul_jumlah = 'PENERIMA';
	public $judul_belum = 'BUKAN PENERIMA';

  function __construct()
  {
    parent::__construct();
  }

	public function select_per_kategori()
	{
		// Ambil data sasaran penduduk
		$this->db->select('u.id, u.nama')
			->select('COUNT(pp.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN pp.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN pp.id END) AS perempuan')
			->from('program u')
			->join('program_peserta pp', 'u.id = pp.program_id', 'left')
			->join('penduduk_hidup p', 'pp.peserta = p.nik')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->where('u.sasaran', '1')
			->where('u.status', '1')
			->order_by('u.nama')
			->group_by('u.id');
		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
	}

	public function get_data_jml()
	{
		return $this->data_jml_semua_penduduk();
	}

	// hitung jumlah unik penerima bantuan (terkadang satu peserta menerima lebih dari 1 bantuan)
	public function hitung_total()
	{
		$data = $this->db->select('COUNT(DISTINCT(pp.peserta))as jumlah')
			->select('COUNT(DISTINCT(CASE WHEN pp.program_id = u.id AND p.sex = 1 THEN p.id END)) AS laki')
			->select('COUNT(DISTINCT(CASE WHEN pp.program_id = u.id AND p.sex = 2 THEN p.id END)) AS perempuan')
			->from('program u')
			->join('program_peserta pp', 'pp.program_id = u.id', 'left')
			->join('tweb_penduduk p', 'pp.peserta = p.nik', 'left')
			->where('u.sasaran', '1')
			->where('u.status', '1')
			->get()->row_array();
		return $data;
	}

}

class Keluarga_penerima_bantuan extends Statistik_penduduk_model {

	public $judul_jumlah = 'PENERIMA';
	public $judul_belum = 'BUKAN PENERIMA';

  function __construct()
  {
    parent::__construct();
  }

	public function select_per_kategori()
	{
		// Ambil data sasaran penduduk
		$this->db->select('u.id, u.nama')
			->select('u.*, COUNT(pp.peserta) as jumlah')
			->select('COUNT(CASE WHEN pp.program_id = u.id AND p.sex = 1 THEN p.id END) AS laki')
			->select('COUNT(CASE WHEN pp.program_id = u.id AND p.sex = 2 THEN p.id END) AS perempuan')
			->from('program u')
			->join('program_peserta pp', 'pp.program_id = u.id', 'left')
			->join('tweb_keluarga k', 'pp.peserta = k.no_kk', 'left')
			->join('tweb_penduduk p', 'k.nik_kepala = p.id', 'left')
			->where('u.sasaran', '2')
			->where('u.status', '1')
			->group_by('u.id');
	}

	public function get_data_jml()
	{
		return $this->data_jml_semua_keluarga();
	}

	// hitung jumlah keluarga unik penerima bantuan (terkadang satu keluarga menerima lebih dari 1 bantuan)
	public function hitung_total()
	{
		$data = $this->db->select('COUNT(DISTINCT(pp.peserta))as jumlah')
			->select('COUNT(DISTINCT(CASE WHEN pp.program_id = u.id AND p.sex = 1 THEN p.id END)) AS laki')
			->select('COUNT(DISTINCT(CASE WHEN pp.program_id = u.id AND p.sex = 2 THEN p.id END)) AS perempuan')
			->from('program u')
			->join('program_peserta pp', 'pp.program_id = u.id', 'left')
			->join('tweb_keluarga k', 'pp.peserta = k.no_kk', 'left')
			->join('tweb_penduduk p', 'k.nik_kepala = p.id', 'left')
			->where('u.sasaran', '2')
			->where('u.status', '1')
			->get()->row_array();
		return $data;
	}

}


?>
