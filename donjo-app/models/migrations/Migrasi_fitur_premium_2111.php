<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2111.php
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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */

class Migrasi_fitur_premium_2111 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		$hasil = $hasil && $this->migrasi_2021100171($hasil);
		$hasil = $hasil && $this->migrasi_2021101051($hasil);
		$hasil = $hasil && $this->migrasi_2021101572($hasil);
		$hasil = $hasil && $this->migrasi_2021101351($hasil);
		$hasil = $hasil && $this->migrasi_2021101871($hasil);
		$hasil = $hasil && $this->migrasi_2021101872($hasil);
		$hasil = $hasil && $this->migrasi_2021102071($hasil);
    $hasil = $hasil && $this->migrasi_2021102271($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	protected function migrasi_2021100171($hasil)
	{
		$hasil = $hasil && $this->tambah_setting([
			'key' => 'telegram_token',
			'value' => '',
			'keterangan' => 'Telgram token',
			'kategori' => 'sistem',
		]);

		$hasil = $hasil && $this->tambah_setting([
			'key' => 'telegram_user_id',
			'value' => '',
			'keterangan' => 'Telgram user id untuk notifikasi ke pengguna',
			'kategori' => 'sistem',
		]);

		return $hasil;
	}

	protected function migrasi_2021101051($hasil)
	{
		$fields = [
			'kode_pos' => [
				'type' => 'INT',
				'constraint' => 5,
				'null' => TRUE,
				'default' => NULL
			],
		];

		$hasil = $hasil && $this->dbforge->modify_column('config', $fields);

		return $hasil;
	}

	protected function migrasi_2021101351($hasil)
	{
		$hasil = $hasil && $this->hapus_indeks('log_keluarga', 'id_kk');
		if (!$this->cek_indeks('log_keluarga', 'id_kk'))
		$hasil = $hasil && $this->db->query("ALTER TABLE log_keluarga ADD UNIQUE id_kk (id_kk, id_peristiwa, tgl_peristiwa, id_pend)");

		return $hasil;
	}

	protected function migrasi_2021101572($hasil)
	{
		return $hasil && $this->ubah_modul(46, ['url'  => 'info_sistem']);
	}

	protected function migrasi_2021101871($hasil)
	{
		// Sesuaikan tabel covid19_pemudik
		
		$this->db->truncate('ref_status_covid');

		$data = [
			[
				'id' => 1,
				'nama' => 'Kasus Suspek',
			],
			[
				'id' => 2,
				'nama' => 'Kasus Probable',
			],
			[
				'id' => 3,
				'nama' => 'Kasus Konfirmasi',
			],
			[
				'id' => 4,
				'nama' => 'Kontak Erat',
			],
			[
				'id' => 5,
				'nama' => 'Pelaku Perjalanan',
			],
			[
				'id' => 6,
				'nama' => 'Discarded',
			],
			[
				'id' => 7,
				'nama' => 'Selesai Isolasi',
			],
		];

		$hasil = $hasil && $this->db->insert_batch('ref_status_covid', $data);
    
		// Ganti ODP & PDP jadi Suspek
		$hasil = $hasil && $this->db
			->where_in('status_covid', ['ODP', 'PDP'])
			->update('covid19_pemudik', ['status_covid' => 1]);

		$hasil = $hasil && $this->db
			->where_in('status_covid', ['ODP', 'PDP'])
			->update('covid19_pantau', ['status_covid' => 1]);

		// Ganti ODR & OTG jadi Kontak Erat
		$hasil = $hasil && $this->db
			->where_in('status_covid', ['ODR', 'OTG'])
			->update('covid19_pemudik', ['status_covid' => 4]);
		
		$hasil = $hasil && $this->db
			->where_in('status_covid', ['ODR', 'OTG'])
			->update('covid19_pantau', ['status_covid' => 4]);

		// Ganti POSITIF jadi Kasus konfirmasi 
		$hasil = $hasil && $this->db
			->where_in('status_covid', ['POSITIF'])
			->update('covid19_pemudik', ['status_covid' => 3]);

		$hasil = $hasil && $this->db
			->where_in('status_covid', ['POSITIF'])
			->update('covid19_pantau', ['status_covid' => 3]);

		// Karena di table ref_status_covid sebelumny tdk ada DLL namu di form pilihan ada,
		// Maka DLL dinyatakan sebagai Selesai isolasi.
		$hasil = $hasil && $this->db
			->where_in('status_covid', ['DLL'])
			->update('covid19_pemudik', ['status_covid' => 7]);

		$hasil = $hasil && $this->db
			->where_in('status_covid', ['DLL'])
			->update('covid19_pantau', ['status_covid' => 7]);

		return $hasil;
	}

	protected function migrasi_2021101872($hasil)
	{
		return $hasil && $this->ubah_modul(220, ['url'  => 'admin_pembangunan']);
	}

	protected function migrasi_2021102071($hasil)
	{
		return $hasil && $this->db->where('link', 'wilayah')->update('menu', ['link' => 'data-wilayah']);
	}

	protected function migrasi_2021102271($hasil)
  {
		if (is_dir(FCPATH . 'cache'))
		{
			$hasil = $hasil && rename(FCPATH . 'cache', DESAPATH . 'cache');
		}

		return $hasil;
  }
}