<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul Kelompok
 *
 * donjo-app/models/Kelompok_model.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html GPL V3
 * @link https://github.com/OpenSID/OpenSID
 */

class Kelompok_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('wilayah_model');
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'kelompok');
	}

	private function search_sql()
	{
		$value = $this->session->cari;
		if (isset($value))
		{
			$kw = $this->db->escape_like_str($value);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (u.nama LIKE '$kw' OR u.nama LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		$value = $this->session->filter;
		if (isset($value))
		{
			$filter_sql = " AND u.id_master = $value";
			return $filter_sql;
		}
	}

	public function paging($p = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) AS jml ";
		$sql .= $this->list_data_sql();

		$query = $this->db->query($sql);
		$row = $query->row_array();

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->session->per_page;
		$cfg['num_rows'] = $row['jml'];
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = "FROM kelompok u
			LEFT JOIN kelompok_master s ON u.id_master = s.id
			LEFT JOIN tweb_penduduk c ON u.id_ketua = c.id
			WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	// $limit = 0 mengambil semua
	public function list_data($o = 0, $offset = 0, $limit = 0)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.nama'; break;
			case 2: $order_sql = ' ORDER BY u.nama DESC'; break;
			case 3: $order_sql = ' ORDER BY c.nama'; break;
			case 4: $order_sql = ' ORDER BY c.nama DESC'; break;
			case 5: $order_sql = ' ORDER BY master'; break;
			case 6: $order_sql = ' ORDER BY master DESC'; break;
			default:$order_sql = ' ORDER BY u.nama';
		}

		$paging_sql = $limit > 0 ? ' LIMIT ' . $offset . ',' . $limit : '';

		$select_sql = "SELECT u.*, s.kelompok AS master, c.nama AS ketua, (SELECT COUNT(id) FROM kelompok_anggota WHERE id_kelompok = u.id) AS jml_anggota ";

		$sql = $select_sql . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		return $data;
	}

	private function validasi($post)
	{
		$data['id_master'] = bilangan($post['id_master']);
		if ($post['id_ketua']) $data['id_ketua'] = bilangan($post['id_ketua']);
		$data['nama'] = nama_terbatas($post['nama']);
		$data['keterangan'] = htmlentities($post['keterangan']);
		$data['kode'] = nomor_surat_keputusan($post['kode']);
		return $data;
	}

	public function insert()
	{
		$data = $this->validasi($this->input->post());
		$datax = [];

		$outpa = $this->db->insert('kelompok', $data);
		$insert_id = $this->db->insert_id();

		$outpb = $this->db
			->set('id_kelompok', $insert_id)
			->set('id_penduduk', $data['id_ketua'])
			->set('no_anggota', 1)
			->set('jabatan', 1)
			->set('keterangan', 'Ketua Kelompok') // keterangan default untuk Ketua Kelompok
			->insert('kelompok_anggota');

		status_sukses($outpa && $outpb);
	}

	private function validasi_anggota($post)
	{
		if ($post['id_penduduk']) $data['id_penduduk'] = bilangan($post['id_penduduk']);
		$data['no_anggota'] = bilangan($post['no_anggota']);
		$data['jabatan'] = bilangan($post['jabatan']);
		$data['no_sk_jabatan'] = nomor_surat_keputusan($post['no_sk_jabatan']);
		$data['keterangan'] = htmlentities($post['keterangan']);
		$data['jabatan'] = bilangan($post['jabatan']);
		$data['no_sk_jabatan'] = nomor_surat_keputusan($post['no_sk_jabatan']);
		return $data;
	}

	public function insert_a($id = 0)
	{
		$_SESSION['success'] = 1;
		$nama_file = '';
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];

		if (!empty($nama_file))
		{
		  $nama_file = urlencode(generator(6)."_".$_FILES['foto']['name']);
			if (!empty($lokasi_file) AND in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR)))
			{
				UploadFoto($nama_file, $old_foto='', $tipe_file);
			}
			else
			{
				$nama_file = '';
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = " -> Jenis file salah: " . $tipe_file;
			}
		}

		$data = $this->validasi_anggota($this->input->post());
		$data['id_kelompok'] = $id;
		$data['foto'] = $nama_file;
		$this->ubah_jabatan($data['id_kelompok'], $data['id_penduduk'], $data['jabatan'], NULL);

		$sdh_ada = $this->db
			->select('id')
			->from('kelompok_anggota')
			->where('id_kelompok', $id)
			->where('id_penduduk', $data['id_penduduk'])
			->get()->row_array();
		if ( ! $sdh_ada)
		{
			$outp = $this->db->insert('kelompok_anggota', $data);
		}

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id = 0)
	{
		$data = $this->validasi($this->input->post());

		$this->db->where('id', $id);
		$outp = $this->db->update('kelompok', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_a($id = 0, $id_a = 0)
	{
		$data = $this->validasi_anggota($this->input->post());

		$_SESSION['success'] = 1;;
		unset($_SESSION['error_msg']);
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$nama_file = str_replace(" ", "_", $nama_file);
		$old_foto = $this->input->post('old_foto');
		if (!empty($nama_file))
		{
			if (!empty($lokasi_file) AND in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR)))
			{
			  $data['foto'] = urlencode(generator(6)."_".$nama_file);
				UploadFoto($data['foto'], $old_foto, $tipe_file);
			}
			else
			{
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = " -> Jenis file salah: " . $tipe_file;
			}
		}

		$this->ubah_jabatan($id, $id_a, $data['jabatan'], $this->input->post('jabatan_lama'));
		$outp = $this->db
			->where('id_kelompok', $id)
			->where('id_penduduk', $id_a)
			->update('kelompok_anggota', $data);
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id = '', $semua = FALSE)
	{
		if ( ! $semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kelompok');

		status_sukses($outp, $gagal_saja = TRUE); //Tampilkan Pesan
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=TRUE);
		}
	}

	public function delete_anggota($id = '', $semua = FALSE)
	{
		if ( ! $semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kelompok_anggota');

		status_sukses($outp, $gagal_saja=TRUE); //Tampilkan Pesan
	}

	public function delete_anggota_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete_anggota($id, $semua=TRUE);
		}
	}

	public function get_kelompok($id = 0)
	{
		$data = $this->db
			->select('k.*, km.kelompok AS kategori, tp.nama AS nama_ketua')
			->from('kelompok k')
			->join('kelompok_master km', 'k.id_master = km.id', 'left')
			->join('tweb_penduduk tp', 'k.id_ketua = tp.id', 'left')
			->where('k.id', $id)
			->get()
			->row_array();

		return $data;
	}

	public function get_ketua_kelompok($id)
	{
		$this->load->model('penduduk_model');
		$sql = "SELECT u.id, u.nik, u.nama, k.id as id_kelompok, k.nama as nama_kelompok, u.tempatlahir, u.tanggallahir, s.nama as sex,
				(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,
				d.nama as pendidikan, f.nama as warganegara, a.nama as agama,
				wil.rt, wil.rw, wil.dusun
			FROM kelompok k
			LEFT JOIN tweb_penduduk u ON u.id = k.id_ketua
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_sex s ON s.id = u.sex
			LEFT JOIN tweb_wil_clusterdesa wil ON wil.id = u.id_cluster
			WHERE k.id = $id LIMIT 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		$data['alamat_wilayah'] = $this->penduduk_model->get_alamat_wilayah($data['id']);

		return $data;
	}

	public function get_anggota($id = 0, $id_a = 0)
	{
		$sql = "SELECT * FROM kelompok_anggota WHERE id_kelompok = ? AND id_penduduk = ?";
		$query = $this->db->query($sql,array($id, $id_a));
		$data = $query->row_array();
		return $data;
	}

	public function list_master()
	{
		$sql = "SELECT * FROM kelompok_master";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	private function in_list_anggota($kelompok)
	{
		$anggota = $this->db
			->select('p.id')
			->from('kelompok_anggota k')
			->join('penduduk_hidup p', 'k.id_penduduk = p.id', 'left')
			->where('k.id_kelompok', $kelompok)
			->get()->result_array();
		return sql_in_list(array_column($anggota, 'id'));
	}

	public function list_penduduk($ex_kelompok = '')
	{
		if ($ex_kelompok)
		{
			$anggota = $this->in_list_anggota($ex_kelompok);
			if ($anggota) $this->db->where("p.id not in ($anggota)");
		}
		$sebutan_dusun = ucwords($this->setting->sebutan_dusun);
		$this->db
			->select('p.id, nik, nama')
			->select("(
				case when (p.id_kk IS NULL or p.id_kk = 0)
					then
						case when (cp.dusun = '-' or cp.dusun = '')
							then CONCAT(COALESCE(p.alamat_sekarang, ''), ' RT ', cp.rt, ' / RW ', cp.rw)
							else CONCAT(COALESCE(p.alamat_sekarang, ''), ' {$sebutan_dusun} ', cp.dusun, ' RT ', cp.rt, ' / RW ', cp.rw)
						end
					else
						case when (ck.dusun = '-' or ck.dusun = '')
							then CONCAT(COALESCE(k.alamat, ''), ' RT ', ck.rt, ' / RW ', ck.rw)
							else CONCAT(COALESCE(k.alamat, ''), ' {$sebutan_dusun} ', ck.dusun, ' RT ', ck.rt, ' / RW ', ck.rw)
						end
				end) AS alamat")
			->from('penduduk_hidup p')
			->join('tweb_wil_clusterdesa cp', 'p.id_cluster = cp.id', 'left')
			->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
			->join('tweb_wil_clusterdesa ck', 'k.id_cluster = ck.id', 'left');
		$data = $this->db->get()->result_array();
		return $data;
	}

	public function list_pengurus($id_kelompok)
	{
		$this->db->where('jabatan <>', 90);
		$data = $this->list_anggota($id_kelompok);
		return $data;
	}

	public function list_anggota($id_kelompok = 0, $sub = '')
	{
		$dusun = ucwords($this->setting->sebutan_dusun);
		if ($sub == 'anggota') $this->db->where('jabatan', 90); // Hanya anggota saja, tidak termasuk pengurus
		$data = $this->db
			->select('ka.*, tp.nik, tp.nama, tp.tempatlahir, tp.tanggallahir, tpx.nama AS sex')
			->select("(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = tp.id) AS umur")
			->select('a.dusun,a.rw,a.rt')
			->select("CONCAT('{$dusun} ', a.dusun, ' RW ', a.rw, ' RT ', a.rt) as alamat")
			->from('kelompok_anggota ka')
			->join('tweb_penduduk tp', 'ka.id_penduduk = tp.id', 'left')
			->join('tweb_penduduk_sex tpx', 'tp.sex = tpx.id', 'left')
			->join('tweb_wil_clusterdesa a', 'tp.id_cluster = a.id', 'left')
			->where('ka.id_kelompok', $id_kelompok)
			->order_by('CAST(jabatan AS UNSIGNED), CAST(no_anggota AS UNSIGNED)')
			->get()
			->result_array();
		return $data;
	}

	public function ubah_jabatan($id_kelompok, $id_penduduk, $jabatan, $jabatan_lama)
	{
		// jika ada orang lain yang sudah jabat KETUA ubah jabatan menjadi anggota
		// update id_ketua kelompok di tabel kelompok
		if ($jabatan == '1') // Ketua
		{
			$this->db
				->set('jabatan', '90') // Anggota
				->set('no_sk_jabatan', '')
				->where('id_kelompok', $id_kelompok)
				->where('jabatan', '1')
				->update('kelompok_anggota');

			$this->db
				->set('id_ketua', $id_penduduk)
				->where('id', $id_kelompok)
				->update('kelompok');
		}
		elseif ($jabatan_lama == '1') // Ketua
		{
			// jika yang diubah adalah jabatan KETUA maka kosongkan id_ketua kelompok di tabel kelompok
			$this->db
				->set('id_ketua', -9999) // kolom id_ketua di tabel kelompok tidak bisa NULL
				->where('id', $id_kelompok)
				->update('kelompok');
		}
	}

}
