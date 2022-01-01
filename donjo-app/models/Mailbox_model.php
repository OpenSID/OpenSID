<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mailbox_model extends CI_Model {

	/**
	 * Gunakan model ini untuk memindahkan semua method terkait mailbox layanan mandiri.
	 * Dimana layanan mailbox memiliki perlakuan yang sepenuhnya berbeda dengan komentar web
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('referensi_model');

	}

	public function list_menu()
	{
		return $this->referensi_model->list_ref_flip(KATEGORI_MAILBOX);
	}

	public function get_kat_nama($kat)
	{
		$sub_menu = $this->list_menu();
		$data = $sub_menu[$kat];
		return $data;
	}

	public function insert($post)
	{
		$data = array();
		$data['email'] = $post['email'];
		$data['owner'] = $post['owner'];
		$data['tipe'] = $post['tipe'];
		$data['status'] = $post['status'];
		$data['subjek'] = strip_tags($post['subjek']);
		$data['komentar'] = strip_tags($post['komentar']);
		$data['permohonan'] = bilangan($post['permohonan']);
		$data['id_artikel'] = 775;
		$data['tgl_upload'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$outp = $this->db->insert('komentar', $data);
		status_sukses($outp);
	}

	/**
	 * Tipe 1: Inbox untuk admin, Outbox untuk pengguna layanan mandiri
	 * Tipe 2: Outbox untuk admin, Inbox untuk pengguna layanan mandiri
	 */
	public function get_all_pesan($nik = '', $tipe = 1)
	{
		$data = $this->db
			->where('email', $nik)
			->where('tipe', $tipe)
			->where('id_artikel', 775)
			->from('komentar')
			->order_by('id', 'DESC')
			->get()
			->result_array();

		return $data;
	}

	public function get_pesan($nik = '', $id = '')
	{
		$data = $this->db
			->where('email', $nik)
			->where('id', $id)
			->where('id_artikel', 775)
			->from('komentar')
			->get()
			->row_array();

		return $data;
	}

	public function ubah_status_pesan($nik = '', $id = '', $status = 1)
	{
		return $this->db
			->where('email', $nik)
			->where('id', $id)
			->where('tipe', 2)
			->where('id_artikel', 775)
			->update('komentar', ['status' => $status]);
	}
}
?>
