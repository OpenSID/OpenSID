<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2105.php
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

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

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

class Migrasi_fitur_premium_2105 extends MY_model {

	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		// Ubah kolom supaya ada nilai default
		$fields = [
			'kartu_tempat_lahir' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'default' => ''],
			'kartu_alamat' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false, 'default' => ''],
		];
		$hasil = $hasil && $this->dbforge->modify_column('program_peserta', $fields);
		$hasil = $hasil && $this->server_publik();
		$hasil = $hasil && $this->convert_ip_address($hasil);
		$hasil = $hasil && $this->tambah_kolom_log_keluarga($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	protected function server_publik()
	{
		// Tampilkan menu Sekretariat di pengaturan modul
		$hasil = $this->db
			->where('id', 15)
			->set('hidden', 0)
			->set('parent', 0)
			->update('setting_modul');
		$hasil = $hasil && $this->tambah_kolom_updated_at();
		$hasil = $hasil && $this->buat_tabel_ref_sinkronisasi();
		return $hasil;
	}

	// Tambah kolom untuk memungkinkkan sinkronsisasi
	protected function tambah_kolom_updated_at()
	{
		$hasil = true;
		if ( ! $this->db->field_exists('updated_at', 'tweb_keluarga'))
		{
			$hasil = $hasil && $this->dbforge->add_column('tweb_keluarga', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
			$hasil = $hasil && $this->dbforge->add_column('tweb_keluarga', 'updated_by int(11) NOT NULL');
		}
		return $hasil;
	}

	protected function buat_tabel_ref_sinkronisasi()
	{
		$hasil = true;
		// Buat folder unggah sinkronisasi
		mkdir(LOKASI_SINKRONISASI_ZIP, 0775, true);
  	// Tambah rincian pindah di log_penduduk
		$tabel = 'ref_sinkronisasi';
		if ($this->db->table_exists($tabel)) return $hasil;

		$this->dbforge->add_field([
			'tabel' 				=> ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
			'server' 				=> ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
			'jenis_update'	=> ['type' => 'TINYINT', 'constraint' => 4, 'null' => true, 'default' => null],
			'tabel_hapus' 	=> ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'default' => null],
		]);
		$this->dbforge->add_key('tabel', true);
		$hasil = $hasil && $this->dbforge->create_table($tabel, true);
		$hasil = $hasil && $this->db->insert_batch(
			$tabel,
			[
				['tabel'=>'tweb_penduduk', 'server' => '6', 'jenis_update' => 1, 'tabel_hapus' => 'log_hapus_penduduk'],
				['tabel'=>'tweb_keluarga', 'server' => '6', 'jenis_update' => 1, 'tabel_hapus' => 'log_keluarga'],
			]
		);
		return $hasil;
	}

	/**
	 * Convert ip address.
	 */
	protected function convert_ip_address($hasil)
	{
		$data = $this->db
			->not_like('ipAddress', 'ip_address')
			->get('sys_traffic')
			->result();

		$batch = [];

		foreach ($data as $sys_traffic)
		{
			$remove_character = str_replace('{}', '', $sys_traffic->ipAddress);

			$batch[] = [
				'ipAddress' => json_encode(['ip_address' => [$remove_character]]),
				'Tanggal'   => $sys_traffic->Tanggal,
			];
		}

		$hasil = $hasil && $this->db->update_batch('sys_traffic', $batch, 'Tanggal');

		return $hasil >= 0;
	}

	protected function tambah_kolom_log_keluarga($hasil)
	{
		if (! $this->db->field_exists('id_pend', 'log_keluarga'))
		{
			$hasil = $hasil && $this->dbforge->add_column('log_keluarga', [
				'id_pend' => ['type' => 'INT', 'constraint' => 11, 'null' => TRUE],
				'updated_by' => ['type' => 'INT', 'constraint' => 11, 'null' => FALSE]
			]);
			$hasil = $hasil && $this->isi_ulang_log_keluarga($hasil);
		}
		// Pindahkan log_penduduk lama ke log_keluarga
		// Perhatikan pemindahan ini tidak akan dilakukan jika semua log id_peristiwa = 7
		// terhapus pada Migrasi_fitur_premium_2102.php
		$log_keluar = $this->db
			->select('l.id as id, l.id_pend, k.id as id_kk, p2.sex as kk_sex')
			->where('l.kode_peristiwa', 7)
			->from('log_penduduk l')
			->join('tweb_penduduk p1', 'p1.id = l.id_pend')
			->join('tweb_keluarga k', 'k.no_kk = p1.no_kk_sebelumnya', 'left')
			->join('tweb_penduduk p2', 'p2.id = k.nik_kepala', 'left')
			->get()
			->result_array();
		if (count($log_keluar) == 0) return $hasil;
		$data = [];
		foreach ($log_keluar as $log)
		{
			if ( ! $log['id_kk']) continue; // Abaikan kasus keluar dari keluarga
			$data[] = [
				'id_peristiwa' => 12,
				'tgl_peristiwa' => $log['tgl_peristiwa'],
				'updated_by' => $log['updated_by'] ?: $this->session->user,
				'id_kk' => $log['id_kk'],
				'kk_sex' => $log['kk_sex'],
				'id_pend' => $log['id_pend']
			];
		}
		$hasil = $hasil && $this->db->insert_batch('log_keluarga', $data);
		$hasil = $hasil && $this->db
			->where_in('id', array_column($log_keluar, 'id'))
			->delete('log_penduduk');
		return $hasil;
	}

	// Catat ulang semua keluarga di log_keluarga untuk laporan bulanan
	private function isi_ulang_log_keluarga($hasil)
	{
		// Kosongkan
		$this->db->truncate('log_keluarga');
		// Tambah keluarga yg ada sebagai keluarga baru
		$keluarga = $this->db
			->select('k.id as id_kk, p.sex as kk_sex, "1" as id_peristiwa, tgl_daftar as tgl_peristiwa, "1" as updated_by')
			->from('tweb_keluarga k')
			->join('tweb_penduduk p', 'p.id = k.nik_kepala')
			->get()->result_array();
		$hasil = $hasil && $this->db->insert_batch('log_keluarga', $keluarga);

		// Tambah mutasi keluarga
		$mutasi = $this->db
			->select('k.id as id_kk, p.sex as kk_sex, lp.tgl_lapor as tgl_peristiwa')
			->select('(case when lp.kode_peristiwa in (2, 3, 4) then lp.kode_peristiwa end) as id_peristiwa')
			->select('"1" as updated_by')
			->from('tweb_keluarga k')
			->join('tweb_penduduk p', 'p.id = k.nik_kepala')
			->join('log_penduduk lp', 'lp.id_pend = p.id and lp.kode_peristiwa = p.status_dasar')
			->where('p.status_dasar <>', 1)
			->get()->result_array();
		$hasil = $hasil && $this->db->insert_batch('log_keluarga', $mutasi);

		return $hasil;
	}

}
