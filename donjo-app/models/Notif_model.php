<?php

class Notif_model extends CI_Model {

	public function status_langganan()
	{
		$this->load->library('data_publik');
		$tracker_host = (ENVIRONMENT == 'development') ? $this->setting->dev_tracker : $this->setting->tracker;
		if ( ! $this->data_publik->has_internet_connection()) return;

		$this->data_publik->set_api_url($tracker_host . '/index.php/api/pelanggan/customer?token=' . $this->setting->api_key_opensid, 'status_pelanggan')
				->set_interval(1)
				->set_cache_folder(FCPATH.'desa');
		$status = $this->data_publik->get_url_content();
		if (empty($status->body->PELANGGAN_PREMIUM)) return; // Tidak ada info pelanggan

		$tgl_akhir = $status->body->PELANGGAN_PREMIUM[0]->tgl_akhir;
		$tgl_akhir = strtotime($tgl_akhir);
		$masa_berlaku = round(($tgl_akhir - time()) / (60 * 60 * 24));
		switch (true)
		{
			case ($masa_berlaku > 30):
				$status = ['status' => 1, 'warna' => 'lightgreen', 'ikon' => 'fa-battery-full'];
				break;
			case ($masa_berlaku > 10):
				$status = ['status' => 2, 'warna' => 'orange', 'ikon' => 'fa-battery-half'];
				break;
			default:
				$status = ['status' => 3, 'warna' => 'pink', 'ikon' => 'fa-battery-empty'];
		}
		$status['masa'] = $masa_berlaku;
		return $status;
	}

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

	/**
	 * Tipe 1: Inbox untuk admin, Outbox untuk pengguna layanan mandiri
	 * Tipe 2: Outbox untuk admin, Inbox untuk pengguna layanan mandiri
	 */
	public function inbox_baru($tipe=1, $nik='')
	{
		if ($nik) $this->db->where('email', $nik);

		$num_rows = $this->db
			->where("id_artikel", LAPORAN_MANDIRI)
			->where('status', 2)
			->where('tipe', $tipe)
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
