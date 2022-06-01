<?php

class First_penduduk_m extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function wilayah()
	{
		$sql = "SELECT u.*, a.nama AS nama_kadus, a.nik AS nik_kadus,
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE dusun = u.dusun AND rw <> '-' AND rt = '-') AS jumlah_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE dusun = u.dusun AND v.rt <> '0' AND v.rt <> '-') AS jumlah_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) and status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 1 and status_dasar = 1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 2 and status_dasar = 1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala = p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.kk_level = 1 and status_dasar = 1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw = '0'  ";

		$query = $this->db->query($sql);
		$data = $query->result_array();

		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function master_indikator()
	{
		$data = $this->db
			->select('m.id, m.nama AS master, m.subjek_tipe, s.subjek, p.nama AS periode, p.tahun_pelaksanaan AS tahun,  p.id AS id_periode')
			->distinct()
			->from('analisis_indikator u')
			->join('analisis_master m', 'u.id_master = m.id', 'left')
			->join('analisis_ref_subjek s', 'm.subjek_tipe = s.id', 'left')
			->join('analisis_periode p', 'p.id_master = m.id', 'left')
			->where('u.is_publik', 1)
			->where('p.aktif', 1)
			->order_by('m.nama')
			->get()->result_array();
		return $data;
	}

	public function list_indikator($master = null)
	{
		if (empty($master)) $master = $this->master_indikator()[0]['id'];

		$data = $this->db
			->select('u.id, u.nomor, u.id_master, u.pertanyaan AS indikator, s.subjek, p.nama AS periode, p.tahun_pelaksanaan AS tahun, m.nama AS master, m.subjek_tipe, p.id AS id_periode')
			->from('analisis_indikator u')
			->join('analisis_master m', 'u.id_master = m.id', 'left')
			->join('analisis_ref_subjek s', 'm.subjek_tipe = s.id', 'left')
			->join('analisis_periode p', 'p.id_master = m.id', 'left')
			->where('u.is_publik', 1)
			->where('p.aktif', 1)
			->where('u.id_master', $master)
			->order_by('LPAD(u.nomor, 10, " ")')
			->get()->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

	public function get_indikator($id=0)
	{
		$indikator = $this->db
			->select('id_master, pertanyaan')
			->from('analisis_indikator')
			->where('id', $id)
			->get()->row_array();

		return $indikator;
	}

	public function list_jawab($id=0, $sb=0, $per=0)
	{
		$data = $this->db->select('*')
			->from('analisis_parameter')
			->where('id_indikator', $id)
			->order_by('kode_jawaban ASC')
			->get()->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			switch ($sb)
			{
				case 1: $this->db->join('tweb_penduduk p', 'r.id_subjek = p.id', 'left')
					->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
					break;
				case 2: $this->db->join('tweb_keluarga v', 'r.id_subjek = v.id', 'left')
					->join('tweb_penduduk p', 'v.nik_kepala = p.id', 'left')
					->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
					break;
				case 3: $this->db->join('tweb_rtm v', 'r.id_subjek = v.id', 'left')
					->join('tweb_penduduk p', 'v.nik_kepala = p.id', 'left')
					->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
					break;
				case 4: $this->db->join('kelompok v', 'r.id_subjek = v.id', 'left')
					->join('tweb_penduduk p', 'v.id_ketua = p.id', 'left')
					->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
					break;
			}

			$jml = $this->db
				->select('COUNT(r.id_subjek) AS jml')
				->from('analisis_respon r')
				->where('r.id_parameter', $data[$i]['id'])
				->where('r.id_periode', $per)
				->get()->row()->jml;
			$data[$i]['nilai'] = $jml;
			$data[$i]['no'] = $i + 1;
		}
		return $data;
	}

}
