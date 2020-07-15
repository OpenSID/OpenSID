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

	public function update_by_kode($kode, $tgl_berikutnya, $updated_at, $updated_by)
	{
		$data = [
			'tgl_berikutnya' => $tgl_berikutnya,
			'updated_at' => $updated_at,
			'updated_by' => $updated_by
		];

		$this->db->where('kode', $kode);
		$this->db->update('notifikasi', $data);
	}

	public function notifikasi($kode)
	{
		// pengumuman tampil saat sistem pertama digunakan atau ketika tgl_berikutnya tlh tercapai
		// data pengumuman di input ke database jauh hari sebelumnya
		// nilai default tgl_berikutnya pasti lebih kecil dr tgl saat pertama sistem digunakan
		$pengumuman = null;
		$notif = $this->get_notif_by_kode($kode);
		$tgl_sekarang = date("Y-m-d H:i:s");
		$tgl_berikutnya = $notif['tgl_berikutnya'];
		if ($tgl_berikutnya <= $tgl_sekarang)
		{
			// simpan view pengumuman dalam variabel
			$data['isi_pengumuman'] = $notif['isi'];
			$data['kode'] = $notif['kode'];
			$data['judul'] = $notif['judul'];
			$data['aksi'] = $notif['aksi'];
			$aksi = explode(',', $notif['aksi']);
			$data['aksi_ya'] = $aksi[0];
			$data['aksi_tidak'] = $aksi[1];
			$pengumuman = $this->load->view('notif/pengumuman', $data, TRUE); // TRUE utk ambil content view sebagai output
		}
		return $pengumuman;
	}

	public function update_notifikasi($kode)
	{
		// update tabel notifikasi
		$notif = $this->notif_model->get_notif_by_kode($kode);
		$tgl_sekarang = date("Y-m-d H:i:s");

		// jika notifikasi berupa pemberitahuan tracking, dan checkbox jangan tampilkan lagi tidak dicentang
		// maka notifikasi akan ditampilkan lagi
		if (empty($this->input->post('cek_lagi')) && $kode == 'tracking_off' ) 
		{
			$tgl_berikutnya = $tgl_sekarang;
		}
		else
		{
			$frekuensi = $notif['frekuensi'];
			$string_frekuensi = "+". $frekuensi . " Days";
			$tambah_hari = strtotime($string_frekuensi); // tgl hari ini ditambah frekuensi
			$tgl_berikutnya =  date('Y-m-d H:i:s', $tambah_hari);			
		}
		$user = $this->session->user;
		$this->notif_model->update_by_kode($kode, $tgl_berikutnya, $tgl_sekarang, $user);
	}

	// query semua notifikasi yang siap untuk tampil
	// order by 'id' dengan asumsi id=1 adalah Persetujuan Penggunaan 
	public function get_semua_notif()
	{
		$hari_ini = new DateTime();
		$compare = $hari_ini->format('Y-m-d H:i:s');
		$semua_notif = $this->db->where('tgl_berikutnya <=', $compare)
								->order_by('id', 'ASC')
								->get('notifikasi')->result_array();
		return $semua_notif;
	}

}

?>
