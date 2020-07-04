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

	public function get_notif_by_kode($kode)
	{
		$notif = $this->db->where('kode', $kode)->get('notifikasi')->row_array();
		return $notif;
	}

	public function update_by_kode($kode, $tgl_berikutnya, $updated_at, $updated_by)
	{
		$this->db->set('tgl_berikutnya', $tgl_berikutnya);
		$this->db->set('updated_at', $updated_at);
		$this->db->set('updated_by', $updated_by);
		$this->db->where('kode', $kode);
		$this->db->update('notifikasi');
	}

	public function notifikasi($kode)
	{
		$pengumuman = null;
		$notif = $this->get_notif_by_kode($kode);
		$tgl_sekarang = date("Y-m-d H:i:s");
		$tgl_berikutnya = $notif['tgl_berikutnya'];
		if ($tgl_berikutnya <= $tgl_sekarang)
		{
			// simpan view pengumuman dalam variabel
			$data['isi_pengumuman'] = $notif['isi'];
			$data['judul'] = $notif['judul'];
			$pengumuman = $this->load->view('notif/pengumuman', $data, TRUE); // TRUE utk ambil content view sebagai output
		}
		return $pengumuman;
	}

	public function update_notifikasi($kode)
	{
		// update tabel notifikasi
		$notif = $this->notif_model->get_notif_by_kode($kode);
		$tgl_sekarang = date("Y-m-d H:i:s");
		$frekuensi = $notif['frekuensi'];
		$string_frekuensi = "+". $frekuensi . " Days";
		$tambah_hari = strtotime($string_frekuensi);  // tgl hari ini ditambah frekuensi
		$tgl_berikutnya =  date('Y-m-d H:i:s', $tambah_hari);
		$user = $this->session->user;
		$this->notif_model->update_by_kode("persetujuan_penggunaan", $tgl_berikutnya, $tgl_sekarang, $user);
	}

}

?>