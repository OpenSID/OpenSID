<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul Kelompok
 *
 * donjo-app/models/Kelompok_master_model.php
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

class Kelompok_master_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('kelompok', 'kelompok_master');
	}

	private function search_sql()
	{
		$value = $this->session->cari;
		if (isset($value))
		{
			$kw = $this->db->escape_like_str($value);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (u.kelompok LIKE '$kw' OR u.kelompok LIKE '$kw')";
			return $search_sql;
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
		$sql = "FROM kelompok_master u WHERE 1 ";
		$sql .= $this->search_sql();
		return $sql;
	}

	// $limit = 0 mengambil semua
	public function list_data($o = 0, $offset = 0, $limit = 0)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.kelompok'; break;
			case 2: $order_sql = ' ORDER BY u.kelompok DESC'; break;
			default:$order_sql = ' ORDER BY u.kelompok';
		}

		$paging_sql = $limit > 0 ? ' LIMIT ' . $offset . ',' . $limit : '';

		$sql = "SELECT u.* " . $this->list_data_sql();

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		return $data;
	}

	public function insert()
	{
		$data = $this->validasi($this->input->post());
		$outp = $this->db->insert('kelompok_master', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id = 0)
	{
		$data = $this->validasi($this->input->post());
		$this->db->where('id', $id);
		$outp = $this->db->update('kelompok_master', $data);
		status_sukses($outp); //Tampilkan Pesan
	}

	private function validasi($post)
	{
		if ($post['id']) $data['id'] = bilangan($post['id']);
		$data['kelompok'] = nama_terbatas($post['kelompok']);
		$data['deskripsi'] = htmlentities($post['deskripsi']);
		return $data;
	}

	public function delete($id = '', $semua = FALSE)
	{
		if ( ! $semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kelompok_master');

		status_sukses($outp, $gagal_saja = TRUE); //Tampilkan Pesan
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=true);
		}
	}

	public function get_kelompok_master($id = 0)
	{
		$sql = "SELECT * FROM kelompok_master WHERE id = ?";
		$query = $this->db->query($sql,$id);
		$data = $query->row_array();
		return $data;
	}

	public function list_subjek()
	{
		$sql = "SELECT * FROM kelompok_ref_subjek";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

}
