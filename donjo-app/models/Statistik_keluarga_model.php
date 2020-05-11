<?php class Statistik_keluarga_model extends Laporan_penduduk_model {

/* Gunakan model ini untuk mulai refactor statistik penduduk
 * Mungkin bisa gunakan anonymous classes yg disediakan di PHP 7.x
 * Usahakan supaya di Laporan_penduduk_model juga menggunakan query builder Codeigniter
*/

	public function __construct()
	{
		parent::__construct();
	}

	public function statistik()
	{
		$keluarga_penerima_bantuan = new Keluarga_penerima_bantuan();
		return $keluarga_penerima_bantuan;
	}
}

/*
 * Semua pengaturan untuk statistik penduduk penerima bantuan.
 * Dipanggil dari donjo-app/models/Laporan_penduduk_model.php
 */
class Keluarga_penerima_bantuan extends Statistik_keluarga_model {

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
			->group_by('u.id');
	}

	public function get_data_jml()
	{
		return $this->data_jml_semua_keluarga();
	}

}

?>
