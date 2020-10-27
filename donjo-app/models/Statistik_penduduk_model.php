<?php class Statistik_penduduk_model extends Laporan_penduduk_model {

/* Gunakan model ini untuk mulai refactor statistik penduduk
 * Mungkin bisa gunakan anonymous classes yg disediakan di PHP 7.x
 * Usahakan supaya di Laporan_penduduk_model juga menggunakan query builder Codeigniter
*/

	public function __construct()
	{
		parent::__construct();
		$this->load->model('program_bantuan_model');
	}

	public function statistik($lap)
	{
		switch (true)
		{
			case ($lap == 'bantuan_penduduk'):
				$statistik = new Penduduk_penerima_bantuan();
				break;
			case ($lap == 'bantuan_keluarga'):
				$statistik = new Keluarga_penerima_bantuan();
				break;
			case ($lap > 50):
				$program_id = preg_replace('/^50/', '', $lap);
				$program = $this->program_bantuan_model->get_sasaran($program_id);
				switch ($program['sasaran'])
				{
					case 1:
						$statistik = new Bantuan_penduduk($program_id);
						break;
					case 2:
						$statistik = new Bantuan_keluarga($program_id);
						break;
					case 3:
						$statistik = new Bantuan_rumah_tangga($program_id);
						break;
					case 4:
						$statistik = new Bantuan_kelompok($program_id);
						break;
				}
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
			->join('tweb_penduduk p', 'pp.peserta = p.nik', 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->where('u.sasaran', '1')
			->where('u.status', '1')
			->order_by('u.nama')
			->group_by('u.id');
		if ($dusun = $this->session->userdata("dusun")) $this->db->where('a.dusun', $dusun);
		if ($rw = $this->session->userdata("rw")) $this->db->where('a.rw', $rw);
		if ($rt = $this->session->userdata("rt")) $this->db->where('a.rt', $rt);
		return true;
	}

	public function get_data_jml()
	{
		return $this->data_jml_semua_penduduk();
	}

	// hitung jumlah unik penerima bantuan (terkadang satu peserta menerima lebih dari 1 bantuan)
	// hitung jumlah unik penerima yg bukan penduduk hidup
	public function hitung_total()
	{
		$data = $this->db->select('COUNT(DISTINCT(pp.peserta))as jumlah')
			->select('COUNT(DISTINCT(CASE WHEN p.sex = 1 THEN p.id END)) AS laki')
			->select('COUNT(DISTINCT(CASE WHEN p.sex = 2 THEN p.id END)) AS perempuan')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 THEN p.id END))as jumlah_nonaktif')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 1 THEN p.id END)) AS jumlah_nonaktif_laki')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 2 THEN p.id END)) AS jumlah_nonaktif_perempuan')
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
			->select('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
			->select('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
			->from('program u')
			->join('program_peserta pp', 'pp.program_id = u.id', 'left')
			->join('tweb_keluarga k', 'pp.peserta = k.no_kk', 'left')
			->join('tweb_penduduk p', 'k.nik_kepala = p.id', 'left')
			->where('u.sasaran', '2')
			->where('u.status', '1')
			->group_by('u.id');
		return true;
	}

	public function get_data_jml()
	{
		return $this->data_jml_semua_keluarga();
	}

	// hitung jumlah keluarga unik penerima bantuan (terkadang satu keluarga menerima lebih dari 1 bantuan)
	public function hitung_total()
	{
		$data = $this->db->select('COUNT(DISTINCT(pp.peserta))as jumlah')
			->select('COUNT(DISTINCT(CASE WHEN p.sex = 1 THEN p.id END)) AS laki')
			->select('COUNT(DISTINCT(CASE WHEN p.sex = 2 THEN p.id END)) AS perempuan')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 THEN p.id END))as jumlah_nonaktif')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 1 THEN p.id END)) AS jumlah_nonaktif_laki')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 2 THEN p.id END)) AS jumlah_nonaktif_perempuan')
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

class Bantuan_penduduk extends Statistik_penduduk_model {

	public $judul_jumlah = 'PESERTA';
	public $judul_belum = 'BUKAN PESERTA';
	private $program_id;

  function __construct($program_id)
  {
    parent::__construct();
    $this->program_id = $program_id;
  }

	public function select_per_kategori()
	{
		// Tidak ada kategori
		return false;
	}

	public function get_data_jml()
	{
		return $this->data_jml_semua_penduduk();
	}

	public function hitung_total()
	{
		// Ambil data sasaran penduduk
		$data = $this->db
			->select('COUNT(pp.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN pp.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN pp.id END) AS perempuan')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 THEN pp.id END))as jumlah_nonaktif')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 1 THEN pp.id END)) AS jumlah_nonaktif_laki')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 2 THEN pp.id END)) AS jumlah_nonaktif_perempuan')
			->from('program_peserta pp')
			->join('tweb_penduduk p', 'pp.peserta = p.nik', 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->where('pp.program_id', $this->program_id)
			->get()->row_array();
		return $data;
	}
}

class Bantuan_keluarga extends Statistik_penduduk_model {

	public $judul_jumlah = 'PESERTA';
	public $judul_belum = 'BUKAN PESERTA';
	private $program_id;

  function __construct($program_id)
  {
    parent::__construct();
    $this->program_id = $program_id;
  }

	public function select_per_kategori()
	{
		// Tidak ada kategori
		return false;
	}

	public function get_data_jml()
	{
		return $this->data_jml_semua_keluarga();
	}

	public function hitung_total()
	{
		// Ambil data sasaran keluarga
		$data = $this->db
			->select('COUNT(pp.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN pp.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN pp.id END) AS perempuan')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 THEN pp.id END))as jumlah_nonaktif')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 1 THEN pp.id END)) AS jumlah_nonaktif_laki')
			->select('COUNT(DISTINCT(CASE WHEN p.status_dasar <> 1 AND p.sex = 2 THEN pp.id END)) AS jumlah_nonaktif_perempuan')
			->from('program_peserta pp')
			->join('tweb_keluarga k', 'k.no_kk = pp.peserta', 'left')
			->join('tweb_penduduk p', 'k.nik_kepala = p.id', 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->where('pp.program_id', $this->program_id)
			->get()->row_array();
		return $data;
	}
}

class Bantuan_rumah_tangga extends Statistik_penduduk_model {

	public $judul_jumlah = 'PESERTA';
	public $judul_belum = 'BUKAN PESERTA';
	private $program_id;

  function __construct($program_id)
  {
    parent::__construct();
    $this->program_id = $program_id;
  }

	public function select_per_kategori()
	{
		// Tidak ada kategori
		return false;
	}

	public function get_data_jml()
	{
		$data = $this->db
			->select('COUNT(r.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN r.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN r.id END) AS perempuan')
			->from('tweb_rtm r')
			->join('tweb_penduduk p', 'r.nik_kepala = p.id', 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->get()->row_array();
		return $data;
	}

	public function hitung_total()
	{
		// Ambil data sasaran rumah tangga
		$data = $this->db
			->select('COUNT(pp.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN pp.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN pp.id END) AS perempuan')
			->from('program_peserta pp')
			->join('tweb_rtm r', 'r.no_kk = pp.peserta', 'left')
			->join('tweb_penduduk p', 'r.nik_kepala = p.id', 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->where('pp.program_id', $this->program_id)
			->get()->row_array();
		return $data;
	}
}

class Bantuan_kelompok extends Statistik_penduduk_model {

	public $judul_jumlah = 'PESERTA';
	public $judul_belum = 'BUKAN PESERTA';
	private $program_id;

  function __construct($program_id)
  {
    parent::__construct();
    $this->program_id = $program_id;
  }

	public function select_per_kategori()
	{
		// Tidak ada kategori
		return false;
	}

	public function get_data_jml()
	{
		$data = $this->db
			->select('COUNT(k.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN k.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN k.id END) AS perempuan')
			->from('kelompok k')
			->join('tweb_penduduk p', 'k.id_ketua = p.id', 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->get()->row_array();
		return $data;
	}

	public function hitung_total()
	{
		// Ambil data sasaran kelompok
		$data = $this->db
			->select('COUNT(pp.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN pp.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN pp.id END) AS perempuan')
			->from('program_peserta pp')
			->join('kelompok k', 'k.id = pp.peserta', 'left')
			->join('tweb_penduduk p', 'k.id_ketua = p.id', 'left')
			->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id')
			->where('pp.program_id', $this->program_id)
			->get()->row_array();
		return $data;
	}
}

?>
