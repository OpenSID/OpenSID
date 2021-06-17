<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Model untuk modul Layanan Mandiri
 *
 * donjo-app/models/Mandiri_model.php
 *
 */
/*
 *  File ini bagian dari:
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

class Mandiri_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('anjungan_model');
		$this->cek_anjungan = $this->anjungan_model->cek_anjungan();
	}

	public function autocomplete()
	{
		$data = $this->db
			->select('p.nama')
			->from('tweb_penduduk_mandiri pm')
			->join('penduduk_hidup p','p.id = pm.id_pend', 'left')
			->get()
			->result_array();

		return autocomplete_data_ke_str($data);
	}

	private function search_sql()
	{
		$cari = $this->session->cari;
		if ($cari)
		{
			$this->db
				->group_start()
					->like('p.nik', $cari)
					->or_like('p.nama', $cari)
				->group_end();
		}
	}

	public function paging($p)
	{
		$this->db->select('COUNT(pm.id_pend) AS jml');
		$this->list_data_sql();

		$row = $this->db->get()->row_array();

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->session->per_page;
		$cfg['num_rows'] = $row['jml'];
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$this->db
			->from('tweb_penduduk_mandiri pm')
			->join('penduduk_hidup p', 'pm.id_pend = p.id', 'LEFT');

		$this->search_sql();
	}

	public function list_data($o = 0, $offset = 0, $limit = 500)
	{
		$this->db->select('pm.*, p.nama, p.nik, p.telepon');

		$this->list_data_sql();

		switch ($o)
		{
			case 1: $this->db->order_by('p.nik'); break;
			case 2: $this->db->order_by('p.nik', DESC); break;
			case 3: $this->db->order_by('p.nama'); break;
			case 4: $this->db->order_by('p.nama', DESC); break;
			case 5: $this->db->order_by('pm.tanggal_buat'); break;
			case 6: $this->db->order_by('pm.tanggal_buat', DESC); break;
			case 7: $this->db->order_by('pm.last_login'); break;
			case 8: $this->db->order_by('pm.last_login', DESC); break;
			default: '';
		}

		$this->db->limit($limit, $offset);

		$data = $this->db->get()->result_array();

		return $data;
	}

	private function generate_pin($pin = '')
	{
		$pin = rand(100000, 999999);
		$pin = strrev($pin);

		return $pin;
	}

	public function insert()
	{
		$post = $this->input->post();
		$pin = bilangan($post['pin'] ?: $this->generate_pin($post['pin']));

		$data['pin'] = hash_pin($pin); // Hash PIN
		$data['tanggal_buat'] = date("Y-m-d H:i:s");
		$data['id_pend'] = $this->input->post('id_pend');
		$outp = $this->db->insert('tweb_penduduk_mandiri', $data);

		status_sukses($data); //Tampilkan Pesan

		// Ambil data sementara untuk ditampilkan
		$flash = $this->get_mandiri($data['id_pend']);
		$flash['pin'] = $pin; // Normal PIN
		$this->session->set_flashdata('info', $flash);
	}

	public function update($id_pend = NULL)
	{
		$post = $this->input->post();
		$pin = bilangan($post['pin'] ?: $this->generate_pin($post['pin']));

		$data['pin'] = hash_pin($pin); // Hash PIN
		$data['ganti_pin'] = 1;
		$outp = $this->db->where('id_pend', $id_pend)->update('tweb_penduduk_mandiri', $data);

		status_sukses($data); //Tampilkan Pesan

		// Ambil data sementara untuk ditampilkan
		$flash = $this->get_mandiri($id_pend);
		$flash['pin'] = $pin; // Normal PIN
		$this->session->set_flashdata('info', $flash);
	}

	public function delete($id_pend = '', $semua = FALSE)
	{
		if ( ! $semua) $this->session->success = 1;

		$outp = $this->db->where('id_pend', $id_pend)->delete('tweb_penduduk_mandiri');

		status_sukses($outp, $gagal_saja = TRUE); //Tampilkan Pesan
	}

	// TODO : Belum digunakan
	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua = TRUE);
		}
	}

	// TODO : Digunakan dimana ?
	private function list_data_ajax_sql($cari = '')
	{
		$this->db
			->from('tweb_penduduk_mandiri u')
			->join('penduduk_hidup n', 'u.id_pend = n.id', 'left')
			->join('tweb_wil_clusterdesa w', 'n.id_cluster = w.id', 'left');

		if ($cari)
		{
			$this->db->where("(nik like '%{$cari}%' or nama like '%{$cari}%')");
		}
	}

	// TODO : Digunakan dimana ?
	public function list_data_ajax($cari, $page)
	{
		$this->list_data_ajax_sql($cari);
		$jml = $this->db
			->select('count(u.id_pend) as jml')
			->get()
			->row()
			->jml;

		$result_count = 25;
		$offset = ($page - 1) * $result_count;

		$this->list_data_ajax_sql($cari);
		$this->db
			->distinct()
			->select('u.id_pend, nik, nama, w.dusun, w.rw, w.rt')
			->limit($result_count, $offset);
		$data = $this->db->get()->result_array();

		foreach ($data as $row )
		{
			$nama = addslashes($row['nama']);
			$alamat = addslashes("Alamat: RT-{$row['rt']}, RW-{$row['rw']} {$row['dusun']}");
			$outp = "{$row['nik']} - {$nama} \n {$alamat}";
			$pendaftar_mandiri[] = array(
				'id' => $row['nik'],
				'text' => $outp
			);
		}

		$end_count = $offset + $result_count;
		$more_pages = $end_count < $jml;

		$result = array(
			'results' => $pendaftar_mandiri,
			"pagination" => array(
				"more" => $more_pages
			)
		);

		return $result;
	}

	public function get_pendaftar_mandiri($nik)
	{
		return $this->db
			->select('id, nik, nama')
			->from('tweb_penduduk')
			->where('status', 1)
			->where('nik', $nik)
			->get()
			->row_array();
	}

	public function list_penduduk()
	{
		$data = $this->db
			->select('id, nik, nama')
			->where('nik <>', '')
			->where('nik <>', 0)
			->where('id NOT IN (SELECT id_pend FROM tweb_penduduk_mandiri)')
			->get('penduduk_hidup')
			->result_array();

		return $data;
	}

	public function get_penduduk($id_pend, $id_nik = FALSE)
	{
		($id_nik === TRUE) ? $this->db->where('nik', $id_pend) : $this->db->where('id', $id_pend);

		$data = $this->db
			->select('id, nik, nama, telepon')
			->get('penduduk_hidup')
			->row_array();

		return $data;
	}

	public function get_mandiri($id_pend, $id_nik = FALSE)
	{
		($id_nik === TRUE) ? $this->db->where('p.nik', $id_pend) : $this->db->where('pm.id_pend', $id_pend);

		$data = $this->db
			->select('pm.*, p.nama, p.nik, p.email, p.telepon')
			->from('tweb_penduduk_mandiri pm')
			->join('penduduk_hidup p', 'pm.id_pend = p.id', 'LEFT')
			->get()
			->row_array();

		return $data;
	}

	#Login Layanan Mandiri
	public function siteman()
	{
		$masuk = $this->input->post();
		$nik = bilangan(bilangan($masuk['nik']));
		$pin = hash_pin(bilangan($masuk['pin']));

		$data = $this->db
						->select('pm.*, p.nama, p.nik, p.tag_id_card, p.foto, p.kk_level, p.id_kk, k.no_kk')
						->from('tweb_penduduk_mandiri pm')
						->join('tweb_penduduk p', 'pm.id_pend = p.id', 'left')
						->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
						->where('p.nik',$nik)
						->get()
						->row();

		switch (true)
		{
			case ($data && $pin == $data->pin):
				$session = [
					'mandiri' => 1,
					'is_login' => $data,
					'login_ektp' => FALSE
				];
				$this->session->set_userdata($session);
				break;

			case ($this->session->mandiri_try > 2):
				$this->session->mandiri_try = $this->session->mandiri_try - 1;
				$this->session->login_ektp = FALSE;
				break;

			default:
				$this->session->mandiri_wait = 1;
				$this->session->login_ektp = FALSE;
				break;
		}
	}

	#Login Layanan Mandiri E-KTP
	public function siteman_ektp()
	{
		$masuk = $this->input->post();
		$pin = hash_pin(bilangan($masuk['pin']));
		$tag = bilangan(bilangan($masuk['tag']));

		$data = $this->db
						->select('pm.*, p.nama, p.nik, p.tag_id_card, p.foto, p.kk_level, p.id_kk, k.no_kk')
						->from('tweb_penduduk_mandiri pm')
						->join('tweb_penduduk p', 'pm.id_pend = p.id', 'left')
						->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
						->where('p.tag_id_card', $tag)
						->get()
						->row();

		switch (true)
		{
			case ($data && $this->cek_anjungan && $tag == $data->tag_id_card):
				$session = [
					'mandiri' => 1,
					'is_login' => $data,
					'login_ektp' => TRUE
				];
				$this->session->set_userdata($session);
				break;

			case ($data && ! $this->cek_anjungan && $tag == $data->tag_id_card && $pin == $data->pin):
				$session = [
					'mandiri' => 1,
					'is_login' => $data,
					'login_ektp' => TRUE
				];
				$this->session->set_userdata($session);
				break;

			case ($this->session->mandiri_try > 2):
				$this->session->mandiri_try = $this->session->mandiri_try - 1;
				$this->session->login_ektp = TRUE;
				break;

			default:
				$this->session->mandiri_wait = 1;
				$this->session->login_ektp = TRUE;
				break;
		}
	}

	public function logout()
	{
		$data = [
			'id_pend' => $this->is_login->id_pend,
			'last_login' => date('Y-m-d H:i:s', NOW())
		];

		if (isset($data['id_pend'])) $this->update_login($data);

		$this->session->unset_userdata(['mandiri', 'is_login', 'data_permohonan']);
	}

	public function update_login(array $data = [])
	{
		$this->db->where('id_pend', $data['id_pend'])->update('tweb_penduduk_mandiri', $data);
	}

	public function ganti_pin()
	{
		$id_pend = $this->is_login->id_pend;
		$ganti = $this->input->post();
		$pin_lama = hash_pin(bilangan($ganti['pin_lama']));
		$pin_baru1 = hash_pin(bilangan($ganti['pin_baru1']));
		$pin_baru2 = hash_pin(bilangan($ganti['pin_baru2']));

		// Ganti password
		$pin = $this->db
			->select('pin')
			->where('id_pend', $id_pend)
			->get('tweb_penduduk_mandiri')
			->row()
			->pin;

		switch (true)
		{
			case ($pin_lama != $pin):
				$respon = [
					'status' => -1, // Notif gagal
					'pesan' => 'PIN gagal diganti, <b>PIN Lama</b> yang anda masukkan tidak sesuai'
				];
				break;

			case ($pin_baru2 == $pin):
				$respon = [
					'status' => -1, // Notif gagal
					'pesan' => '<b>PIN</b> gagal diganti, Silahkan ganti <b>PIN Lama</b> anda dengan <b>PIN Baru</b> '
				];
				break;

			default:
				$data = [
					'id_pend' => $id_pend,
					'pin' => $pin_baru2,
					'last_login' => date('Y-m-d H:i:s', NOW()),
					'ganti_pin' => 0
				];
				$this->update_login($data);
				$respon = [
					'status' => 1, // Notif berhasil
					'aksi' => site_url('layanan-mandiri/keluar'),
					'pesan' => 'PIN berhasil diganti, silahkan masuk kembali'
				];
				break;
		}
		$this->session->set_flashdata('notif', $respon);
	}

	public function jml_mandiri()
	{
		return $this->db->get('tweb_penduduk_mandiri')->num_rows();
	}
}
