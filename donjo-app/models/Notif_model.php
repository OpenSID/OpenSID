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
		$num_rows = $this->db->where('baca', 2)
			->where('tipe', 1)
			->where('status', 0)
			->get('kotak_pesan')->num_rows();
		return $num_rows;
	}
}
?>