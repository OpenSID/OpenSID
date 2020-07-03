<?php

class Notif_model extends CI_Model {

	public function permohonan_surat_baru()
	{
		$num_rows = $this->db->where('status', 0)
			->get('permohonan_surat')->num_rows();
		return $num_rows;
	}

	public function komentar_baru()
	{
		$num_rows = $this->db->where('id_artikel !=', LAPORAN_MANDIRI)
			->where('status', 2)
			->get('komentar')->num_rows();
		return $num_rows;
	}

	public function inbox_baru()
	{
		$num_rows = $this->db->where("id_artikel", LAPORAN_MANDIRI)
			->where('status', 2)
			->where('tipe', 1)
			->where('is_archived', 0)
			->get('komentar')->num_rows();
		return $num_rows;
	}

	public function get_notif($id)
	{
		$notif = $this->db->where('id', $id)->get('notifikasi')->row_array();
		return $notif;
	}

	public function get_notif_by_judul($judul)
	{
		$notif = $this->db->where('judul', $judul)->get('notifikasi')->row_array();
		return $notif;
	}

	public function update_by_judul($judul, $tgl_berikutnya, $updated_at, $updated_by)
	{
		$this->db->set('tgl_berikutnya', $tgl_berikutnya);
		$this->db->set('updated_at', $updated_at);
		$this->db->set('updated_by', $updated_by);
		$this->db->where('judul', $judul);
		$this->db->update('notifikasi');
	}

}

?>