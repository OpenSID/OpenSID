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

	public function get_notif_by_kode($kode)
	{
		$notif = $this->db->where('kode', $kode)->get('notifikasi')->row_array();
		return $notif;
	}

	public function notifikasi($notif)
	{
		$pengumuman = null;
		// Simpan view pengumuman dalam variabel
		$data['isi_pengumuman'] = $notif['isi'];
		$data['kode'] = $notif['kode'];
		$data['judul'] = $notif['judul'];
		$data['jenis'] = $notif['jenis'];
		$data['aksi'] = $notif['aksi'];
		$aksi = explode(',', $notif['aksi']);
		$data['aksi_ya'] = $aksi[0];
		$data['aksi_tidak'] = $aksi[1];
		$pengumuman = $this->load->view('notif/pengumuman', $data, TRUE); // TRUE utk ambil content view sebagai output
		return $pengumuman;
	}

	private function masih_berlaku($notif)
	{
		switch ($notif['kode'])
		{
			case 'tracking_off':
				if ($this->setting->enable_track)
				{
					$this->db->where('kode', 'tracking_off')
						->update('notifikasi', ['aktif' => 0]);
					return false;
				}
				break;
		}
		return true;
	}

	public function update_notifikasi($kode, $non_aktifkan=false)
	{
		// update tabel notifikasi
		$notif = $this->notif_model->get_notif_by_kode($kode);

		$tgl_sekarang = date("Y-m-d H:i:s");
		$frekuensi = $notif['frekuensi'];
		$string_frekuensi = "+". $frekuensi . " Days";
		$tambah_hari = strtotime($string_frekuensi); // tgl hari ini ditambah frekuensi
		$data = [
			'tgl_berikutnya' =>  date('Y-m-d H:i:s', $tambah_hari),
			'updated_by' => $this->session->user,
			'updated_at' => date("Y-m-d H:i:s"),
			'aktif' => 1
		];
		// Non-aktifkan pengumuman kalau dicentang
		if ($notif['jenis'] == 'pengumuman' and $non_aktifkan) $data['aktif'] = 0;

		$this->db->where('kode', $kode)
			->update('notifikasi', $data);
	}

	// Ambil semua notifikasi yang siap untuk tampil
	// Urut persetujuan dulu
	public function get_semua_notif()
	{
		$hari_ini = new DateTime();
		$compare = $hari_ini->format('Y-m-d H:i:s');
		$semua_notif = $this->db->where('tgl_berikutnya <=', $compare)
			->select('*')
			->select("IF (jenis = 'persetujuan', CONCAT('A',id), CONCAT('Z',id)) AS urut")
			->where('aktif', 1)
			->order_by('urut', 'ASC')
			->get('notifikasi')->result_array();
		return $semua_notif;
	}

	public function insert_notif($data)
	{
		$sql = $this->db->insert_string('notifikasi', $data) . duplicate_key_update_str($data);
		$this->db->query($sql);
	}

}

?>
