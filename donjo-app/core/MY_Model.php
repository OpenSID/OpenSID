<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}

	// Konversi url menu menjadi slug tanpa mengubah data
	public function menu_slug($url)
	{
		$this->load->model('first_artikel_m');

		$cut = explode('/', $url);

		switch ($cut[0])
		{
			case 'artikel':

				$data = $this->first_artikel_m->get_artikel($cut[1]);
				$url = ($data) ? ($cut[0] . '/' . buat_slug($data)) : ($url);
				break;

			case 'kategori':
				$data = $this->first_artikel_m->get_kategori($cut[1]);
				$url = ($data) ? ('artikel/' . $cut[0] . '/' . $data['slug']) : ($url);
				break;
				
			case 'arsip':
			case 'peraturan_desa':
			case 'data_analisis':
			case 'ambil_data_covid':
			case 'informasi_publik':
			case 'load_aparatur_desa':
			case 'load_apbdes':
			case 'load_aparatur_wilayah':
			case 'peta':
			case 'data-suplemen':
			case 'data-kelompok':
			case 'status-idm':
			case 'status-sdgs':
			case 'lapak':
				break;

			default:
				$url = 'first/' . $url;
				break;
		}

		return site_url($url);
	}

	public function autocomplete_str($kolom, $tabel, $cari='')
	{
		if ($cari)
		{
			$this->db->like($kolom, $cari);
		}
		$data = $this->db->distinct()->
			select($kolom)->
			order_by($kolom)->
			get($tabel)->result_array();

		return autocomplete_data_ke_str($data);
	}

	/*
	 * 0 = kolom untuk select/order, 1 = tabel, 2 = where, 3 = $cari
	 */
	public function union($list_kode = '')
	{
		$sql = array();

		foreach ($list_kode as $kode)
		{
			list($kolom, $tabel, $where, $cari) = $kode;
			$sql[] = '('.$this->db
				->select($kolom)
				->from($tabel)
				->where($where)
				->like($kolom, $cari)
				->order_by($kolom, DESC)
				->get_compiled_select()
				.')';
		}

		$sql = implode('UNION', $sql);

		return $this->db->query($sql)->result_array();
	}

	public function hapus_indeks($tabel, $indeks)
	{
		$db = $this->db->database;
		$ada = $this->db
			->select("COUNT(index_name) as ada")
			->from('INFORMATION_SCHEMA.STATISTICS')
			->where('table_schema', $db)
			->where('table_name', $tabel)
			->where('index_name', $indeks)
			->get()->row()->ada;
		if ($ada)
			return $this->db->query("DROP INDEX $indeks ON $tabel");
		else return true;
	}

	public function tambah_indeks($tabel, $kolom, $index = "UNIQUE")
	{
		if ($index == "UNIQUE")
		{
			$duplikat = $this->db
				->select($kolom)
				->from($tabel)
				->group_by($kolom)
				->having("COUNT(`$kolom`) > 1")
				->get()->num_rows();
			if ($duplikat > 0)
			{
				$this->session->error_msg = "Data $kolom ada yg duplikat";
				return false;
			}
		}

		$db = $this->db->database;
		$ada = $this->db
			->select("COUNT(index_name) as ada")
			->from('INFORMATION_SCHEMA.STATISTICS')
			->where('table_schema', $db)
			->where('table_name', $tabel)
			->where('index_name', $kolom)
			->get()->row()->ada;
		if ( ! $ada)
			return $this->db->query("ALTER TABLE $tabel ADD $index $kolom (`$kolom`)");
		else return true;
	}

	public function tambah_modul($modul)
	{
		$sql = $this->db->insert_string('setting_modul', $modul) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), ikon = VALUES(ikon), parent = VALUES(parent)";
		return $this->db->query($sql);
	}

	/**
	 * Ubah modul setting menu.
	 *
	 * @param int $id
	 * @param array $modul
	 * @return bool
	 */
	public function ubah_modul(int $id, array $modul)
	{
		return $this->db->where('id', $id)
			->set($modul)
			->update('setting_modul');
	}

	public function tambah_setting($setting)
	{
		$sql = $this->db->insert_string('setting_aplikasi', $setting) . " ON DUPLICATE KEY UPDATE keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)";
		return $this->db->query($sql);
	}

	// fungsi untuk format paginasi
	public function paginasi($page = 1, $jml_data = 0)
	{
		$this->load->library('paging');
		$cfg['page'] = $page;
		$cfg['per_page'] = $this->session->per_page;
		$cfg['num_links'] = 10;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	// Buat FOREIGN KEY $nama_constraint $di_tbl untuk $fk menunjuk $ke_tbl di $ke_kolom
	public function tambah_foreign_key($nama_constraint, $di_tbl, $fk, $ke_tbl, $ke_kolom)
	{
		$query = $this->db
			->from('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
			->where('CONSTRAINT_NAME', $nama_constraint)
			->where('TABLE_NAME', $di_tbl)
			->get();
		$hasil = true;
		if ($query->num_rows() == 0)
		{
			$hasil = $hasil && $this->dbforge->add_column($di_tbl, [
				"CONSTRAINT `{$nama_constraint}` FOREIGN KEY (`{$fk}`) REFERENCES `{$ke_tbl}` (`{$ke_kolom}`) ON DELETE CASCADE ON UPDATE CASCADE"
			]);
		}
		return $hasil;
	}
}
