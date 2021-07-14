<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul Menu
 *
 * donjo-app/models/Web_menu_model.php
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Web_menu_model extends MY_Model {

	private $urut_model;

	public function __construct()
	{
		parent::__construct();
		require_once APPPATH.'/models/Urut_model.php';
		$this->urut_model = new Urut_Model('menu');
	}

	public function autocomplete($cari = '')
	{
		return $this->autocomplete_str('nama', 'menu', $cari);
	}

	private function search_sql()
	{
		if ($cari = $this->session->cari)
		{
			$this->db->like('nama', $cari);
		}
	}

	private function filter_sql()
	{
		if ($filter = $this->session->filter)
		{
			$this->db->where('enabled', $filter);
		}
	}

	public function paging($p = 1)
	{
		$this->list_data_sql();
		$jml_data = $this->db->select('id')->get()->num_rows();

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->session->per_page;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$this->db->from('menu')
			->where('tipe', 1);

		$this->filter_sql();
		$this->search_sql();
	}

	public function list_data($o = 0, $offset = 0, $limit = 0)
	{
		switch($o)
		{
			case 1:
				$this->db->order_by('nama');
				break;
			case 2:
				$this->db->order_by('nama', 'desc');
				break;
			case 3:
				$this->db->order_by('enabled');
			case 4:
				$this->db->order_by('enabled', 'desc');
			default:
				$this->db->order_by('urut');
		}
		
		$this->list_data_sql();
		if ($limit > 0 ) $this->db->limit($limit, $offset);
		$data = $this->db
			->get()
			->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if ($data[$i]['link_tipe'] != 99) $data[$i]['link'] = $this->menu_slug($data[$i]['link']);

			$j++;
		}

		return $data;
	}

	public function insert()
	{
		$post = $this->input->post();
		$data['tipe'] = 1;
		$data['parrent'] = 0;
		$data['urut'] = $this->urut_model->urut_max(array('tipe' => 1)) + 1;
		$data['nama'] = htmlentities($post['nama']);
		$data['link'] = $post['link'];
		$data['link_tipe'] = $post['link_tipe'];

		$outp = $this->db->insert('menu', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id=0)
	{
		$post = $this->input->post();
		$data['nama'] = htmlentities($post['nama']);
		$data['link'] = $post['link'];
		if ($data['link']=="")
			UNSET($data['link']);

		$data['link_tipe'] = $post['link_tipe'];

		$this->db->where('id', $id);
		$outp = $this->db->update('menu', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->or_where('parrent', $id)->delete('menu');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
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

	public function list_sub_menu($menu=1)
	{
		$data = $this->db->select('*')
			->from('menu')
			->where('parrent', $menu)
			->where('tipe', 3)
			->order_by('urut')
			->get()->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			if ($data[$i]['link_tipe'] != 99) $data[$i]['link'] = $this->menu_slug($data[$i]['link']);
		}

		return $data;
	}

	public function insert_sub_menu($menu=0)
	{
		$post = $this->input->post();
		$data = [];
		$data['parrent'] = $menu;
		$data['tipe'] = 3;
		$data['urut'] = $this->urut_model->urut_max(array('tipe' => 3, 'parrent' => $menu)) + 1;
		$data['nama'] = htmlentities($post['nama']);
		$data['link'] = $post['link'];
		$data['link_tipe'] = $post['link_tipe'];
		$outp = $this->db->insert('menu', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_sub_menu($id=0)
	{
		$post = $this->input->post();
		$data = [];
		$data['nama'] = htmlentities($post['nama']);
		$data['link'] = $post['link'];
		$data['link_tipe'] = $post['link_tipe'];
		if ($data['link'] == "")
		{
			UNSET($data['link']);
		}

		$this->db->where('id', $id);
		$outp = $this->db->update('menu', $data);
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete_sub_menu($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('menu');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
	}

	public function delete_all_sub_menu()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete_sub_menu($id, $semua=true);
		}
	}

	public function menu_lock($id='',$val=0)
	{
		$sql = "UPDATE menu SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_menu($id=0)
	{
		$sql = "SELECT * FROM menu WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		return $data;
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	public function urut($id, $arah, $menu='')
	{
		$subset = !empty($menu) ? array("tipe" => 3, "parrent" => $menu) : array("tipe" => 1);
		$this->urut_model->urut($id, $arah, $subset);
	}

	public function menu_aktif($link)
	{
		$ada_menu = $this->db->where('link', $link)
			->where('enabled', 1)
			->get('menu')
			->num_rows();

		return $ada_menu;
	}

}
?>
