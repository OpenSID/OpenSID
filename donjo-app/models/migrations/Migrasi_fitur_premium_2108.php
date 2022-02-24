<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2108.php
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

class Migrasi_fitur_premium_2108 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		$hasil = $hasil && $this->migrasi_2021070271($hasil);
		$hasil = $hasil && $this->migrasi_2021071251($hasil);
		$hasil = $hasil && $this->migrasi_2021071551($hasil);
		$hasil = $hasil && $this->migrasi_2021072672($hasil);
		$hasil = $hasil && $this->migrasi_2021072971($hasil);
		$hasil = $hasil && $this->migrasi_2021072972($hasil);
		$hasil = $hasil && $this->migrasi_2021072951($hasil);
		$hasil = $hasil && $this->migrasi_2021081851($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	protected function migrasi_2021070271($hasil)
	{
		if (! $this->db->field_exists('bpjs_ketenagakerjaan', 'tweb_penduduk'))
		{
			$hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', ['bpjs_ketenagakerjaan' => ['type' => 'CHAR', 'constraint' => '100', 'null' => true]]);
		}

		// Update view supaya kolom baru ikut masuk
		$hasil = $hasil && $this->db->query("CREATE OR REPLACE VIEW penduduk_hidup AS SELECT * FROM tweb_penduduk WHERE status_dasar = 1");

		return $hasil;
	}

	protected function migrasi_2021071251($hasil)
	{
		$hasil = $hasil && $this->db
			->set('status_rekam', null)
			->where('status_rekam', 1)
			->update('tweb_penduduk');

		return $hasil;
	}

	// Hapus mutasi kepemilikan awal persil yg salah
	protected function migrasi_2021071551($hasil)
	{
		$hasil = $hasil && $this->db
			->where('jenis_mutasi', 9)
			->where('id_cdesa_masuk is null')
			->delete('mutasi_cdesa');

		return $hasil;
	}

	protected function migrasi_2021072672($hasil)
	{
		if (! $this->db->field_exists('bdt', 'tweb_rtm')) {
			$hasil = $hasil && $this->dbforge->add_column('tweb_rtm', ['bdt' => ['type' => 'VARCHAR', 'constraint' => '16', 'null' => true]]);
		}

		return $hasil;
	}
	
	protected function migrasi_2021072971($hasil)
	{
		$hasil = $hasil && $this->tambah_table_laporan_apbdes($hasil);
		$hasil = $hasil && $this->tambah_modul_laporan_apbdes($hasil);
		$hasil = $hasil && $this->tambah_modul_sinkronisasi($hasil);
		$hasil = $hasil && $this->ubah_pengaturan_aplikasi($hasil);

		return $hasil;
	}

	// Tabel Laporan APBDes
	protected function tambah_table_laporan_apbdes($hasil)
	{
		$fields = [
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => true
			],

			'judul' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			],

			'tahun' => [
				'type' => 'INT',
				'constraint' => 11
			],

			'semester' => [
				'type' => 'INT',
				'constraint' => 11
			],

			'nama_file' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			],
			
			'kirim' => [
				'type' => 'DATETIME',
				'null' => true
			],

			'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
			'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
		];

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_field($fields);
		$hasil = $hasil && $this->dbforge->create_table('laporan_apbdes', true);

		return $hasil;
	}

	// Menu Laporan APBDes
	protected function tambah_modul_laporan_apbdes($hasil)
	{
		$fields = [
			'id' => 325,
			'modul' => 'Laporan APBDes',
			'url' => 'laporan_apbdes',
			'aktif' => 1,
			'ikon' => 'fa-file-text-o',
			'urut' => 5,
			'level' => 2,
			'hidden' => 0,
			'ikon_kecil' => 'fa-file-text-o',
			'parent' => 201
		];

		$hasil = $hasil && $this->tambah_modul($fields);

		// Hapus cache menu navigasi
		$this->load->driver('cache');
		$this->cache->hapus_cache_untuk_semua('_cache_modul');

		return $hasil;
	}

	// Menu Sinkronisasi
	protected function tambah_modul_sinkronisasi($hasil)
	{
		$fields = [
			'id' => 326,
			'modul' => 'Sinkronisasi',
			'url' => 'sinkronisasi',
			'aktif' => 1,
			'ikon' => ' fa-random',
			'urut' => 7,
			'level' => 2,
			'hidden' => 0,
			'ikon_kecil' => 'fa-random',
			'parent' => 11
		];

		$hasil = $hasil && $this->tambah_modul($fields);

		// Hapus cache menu navigasi
		$this->load->driver('cache');
		$this->cache->hapus_cache_untuk_semua('_cache_modul');

		return $hasil;
	}

	private function ubah_pengaturan_aplikasi($hasil)
	{
		$hasil = $hasil && $this->db
			->where_in('key', ['api_opendk_server', 'api_opendk_key', 'api_opendk_user', 'api_opendk_password'])
			->update('setting_aplikasi', ['kategori' => 'opendk']);

		return $hasil;
	}
	
	protected function migrasi_2021072972($hasil)
	{
		// Hapus key layanan_opendesa_server, layanan_opendesa_dev_server dan dev_tracker
		$hasil = $hasil && $this->db
			->where_in('key', ['layanan_opendesa_server', 'layanan_opendesa_dev_server', 'dev_tracker'])
			->delete('setting_aplikasi');

		return $hasil;
	}

	// Sesuaikan tabel menu
	protected function migrasi_2021072951($hasil)
	{
		if ($this->db->field_exists('tipe', 'menu'))
		{
			$hasil = $hasil && $this->db
				->where('parrent', 1)
				->where('tipe', 1)
				->update('menu', ['parrent' => 0]);

			$hasil = $hasil && $this->dbforge->drop_column('menu', 'tipe');

			$fields = [
				'id' => [
					'type' => 'INT',
					'constraint' => 11,
					'auto_increment' => true
				],

				'parrent' => [
					'type' => 'INT',
					'constraint' => 11,
					'default' => 0
				],

				'enabled' => [
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1
				],
			];

			$hasil = $hasil && $this->dbforge->modify_column('menu', $fields);

			// Hapus menu yg tdk memiliki parrent
			$list_menu = $this->db->select('id')->get_where('menu', ['parrent' => 0])->result_array();
			$hapus = sql_in_list(array_column($list_menu, 'id'));
			if ($hapus) $hasil = $hasil && $this->db->where("parrent NOT IN ($hapus) AND parrent != 0")->delete('menu');
		}

		return $hasil;
	}

	// Migrasi tambahan dari rev02
	protected function migrasi_2021081851($hasil)
	{
		// Cek log surat, hapus semua file view verifikasi berdasrkan surat yg sudah di cetak
		$list_data = $this->db->select('nama_surat')->get('log_surat')->result();

		foreach ($list_data as $data)
		{
			// Hapus file
			$file = LOKASI_ARSIP . '/' . str_replace('.rtf', '.php', $data->nama_surat);
			if (file_exists($file))
			{
				$hasil = $hasil && unlink($file);
			}
		}

		return $hasil;
	}
}
