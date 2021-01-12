<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2101.php
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

class Migrasi_fitur_premium_2102 extends MY_model {

	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		$hasil =& $this->pengaturan_latar($hasil);

		//tambah kolom urut di tabel tweb_wil_clusterdesa
		if (!$this->db->field_exists('urut', 'tweb_wil_clusterdesa'))
			$hasil = $this->dbforge->add_column('tweb_wil_clusterdesa', array(
				'urut' => array(
				'type' => 'INT',
				'constraint' => 11,
				'null' => TRUE,
				),
			));

		$hasil =& $this->url_suplemen($hasil);
		// Buat folder untuk cache - 'cache\';
		mkdir(config_item('cache_path'), 0775, true);

		$hasil =& $this->create_table_pembangunan($hasil);
		$hasil =& $this->create_table_pembangunan_ref_dokumentasi($hasil);
		$hasil =& $this->add_modul_pembangunan($hasil);
		$hasil =& $this->sebutan_kepala_desa($hasil);
		$hasil =& $this->urut_cetak($hasil);
		$hasil =& $this->penduduk_updates($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	private function pengaturan_latar($hasil)
	{
		$old = "desa/css";
		$new = "desa/pengaturan";

		if (is_dir($old))
		{
			// Ubah nama folder desa/csss jadi desa/pengaturan
			rename($old, $new);
		}
		// Buat folder untuk latar
		mkdir($new . "/siteman/images", 0775, true);
		mkdir($new . "/klasik/images", 0775, true);
		mkdir($new . "/natra/images", 0775, true);
		return $hasil;
	}

	// Tambahkan clear pada url suplemen
	private function url_suplemen($hasil)
	{
		$hasil =& $this->db->where('id', 25)
			->set('url', 'suplemen/clear')
			->update('setting_modul');
		return $hasil;
	}

	protected function create_table_pembangunan($hasil)
	{
		$this->dbforge->add_field([
			'id'                 => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'id_lokasi'          => ['type' => 'INT', 'constraint' => 11, 'null' => true],
			'sumber_dana'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'judul'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'keterangan'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'lokasi'             => ['type' => 'VARCHAR','constraint' => 225, 'null' => true],
			'lat'                => ['type' => 'VARCHAR','constraint' => 225, 'null' => true],
			'lng'                => ['type' => 'VARCHAR','constraint' => 255, 'null' => true],
			'volume'             => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
			'tahun_anggaran'     => ['type' => 'YEAR', 'null' => true],
			'pelaksana_kegiatan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'status'             => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 1],
			'created_at'         => ['type' => 'datetime', 'null' => true],
			'updated_at'         => ['type' => 'datetime', 'null' => true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('id_lokasi');
		$hasil =& $this->dbforge->create_table('pembangunan', true);
		return $hasil;
	}

	protected function create_table_pembangunan_ref_dokumentasi($hasil)
	{
		$this->dbforge->add_field([
			'id'             => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'id_pembangunan' => ['type' => 'INT', 'constraint' => 11],
			'gambar'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'persentase'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'keterangan'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('id_pembangunan');
		$hasil =& $this->dbforge->create_table('pembangunan_ref_dokumentasi', true);
		return $hasil;
	}

	protected function add_modul_pembangunan($hasil)
	{
		$hasil =& $this->tambah_modul([
			'id'         => 220,
			'modul'      => 'Pembangunan',
			'url'        => 'pembangunan',
			'aktif'      => 1,
			'ikon'       => 'fa-institution',
			'urut'       => 9,
			'level'      => 2,
			'hidden'     => 0,
			'ikon_kecil' => 'fa-institution',
			'parent'     => 0
		]);

		$hasil =& $this->tambah_modul([
			'id'         => 221,
			'modul'      => 'Pembangunan Dokumentasi',
			'url'        => 'pembangunan_dokumentasi',
			'aktif'      => 1,
			'ikon'       => '',
			'urut'       => 0,
			'level'      => 0,
			'hidden'     => 2,
			'ikon_kecil' => '',
			'parent'     => 220
		]);

		// Hapus cache menu navigasi
		$this->load->driver('cache');
		$this->cache->hapus_cache_untuk_semua('_cache_modul');

		//tambah kolom Foto utama di tabel pembangunan
		if (!$this->db->field_exists('foto', 'pembangunan'))
			$hasil = $this->dbforge->add_column('pembangunan', array(
				'foto' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE,
				),
			));

		//tambah kolom Anggaran di tabel pembangunan
		if (!$this->db->field_exists('anggaran', 'pembangunan'))
			$hasil = $this->dbforge->add_column('pembangunan', array(
				'anggaran' => array(
				'type' => 'INT',
				'constraint' => 11,
				'null' => FALSE,
				'default' => '0',
				),
			));

		return $hasil;
	}

	// Tambah Sebutan jabatan kepala desa
	private function sebutan_kepala_desa($hasil)
	{
		$setting = [
					'key' => 'sebutan_kepala_desa',
					'value' => 'Kepala',
					'keterangan' => 'Pengganti sebutan jabatan Kepala Desa'
					];
		$hasil =& $this->tambah_setting($setting);

		return $hasil;
	}

	private function urut_cetak($hasil)
	{
		//tambah kolom urut untuk tabel cetak semua di tabel tweb_wil_clusterdesa
		if ( ! $this->db->field_exists('urut_cetak', 'tweb_wil_clusterdesa'))
			$hasil =& $this->dbforge->add_column('tweb_wil_clusterdesa', array(
				'urut_cetak' => array(
				'type' => 'INT',
				'constraint' => 11,
				'null' => TRUE,
				),
			));

		return $hasil;
	}


	// Updates for issues #2777
	public function penduduk_updates($hasil){
		// Menambahkan Tabel tweb_penduduk_bahasa yang digunakan untuk kolom bahasa
		if (!$this->db->table_exists('tweb_penduduk_bahasa') )
		{
			// Membuat table tweb_penduduk_bahasa, attribut baru untuk kolom bahasa
			$query = "
			CREATE TABLE IF NOT EXISTS `tweb_penduduk_bahasa` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`nama` varchar(50) NOT NULL,
				`inisial` varchar(10) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);

			// Insert data ke table tweb_penduduk_bahasa
			$query = "
			INSERT INTO tweb_penduduk_bahasa (`id`, `nama`, `inisial`) VALUES
				(1, 'Latin', 'L'),
				(2, 'Daerah', 'D'),
				(3, 'Arab', 'A'),
				(4, 'Arab dan Latin', 'AL'),
				(5, 'Arab dan Daerah', 'AD'),
				(6, 'Arab, Latin dan Daerah', 'ALD')
			";
			$hasil =& $this->db->query($query);
		}

		// Menambahkan bahasa_id setelah column warganegara_id pada table tweb_penduduk, digunakan untuk define bahasa penduduk
		$hasil =& $this->db->query("ALTER TABLE tweb_penduduk ADD bahasa_id int(11) NULL AFTER warganegara_id");
		// Menambahkan column ket pada table tweb_penduduk
		$hasil =& $this->db->query("ALTER TABLE tweb_penduduk ADD ket tinytext NULL");

		return $hasil;
	}
}
