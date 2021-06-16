<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2107.php
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

class Migrasi_fitur_premium_2107 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = TRUE;

		$hasil = $hasil && $this->migrasi_2021060851($hasil);
		$hasil = $hasil && $this->migrasi_2021060901($hasil);
		$hasil = $hasil && $this->migrasi_2021061201($hasil);
		$hasil = $hasil && $this->migrasi_2021061301($hasil);
		$hasil = $hasil && $this->migrasi_2021061651($hasil);
		$hasil = $hasil && $this->migrasi_2021061652($hasil);
    $hasil = $hasil && $this->migrasi_2021061653($hasil);

		status_sukses($hasil);

		return $hasil;
	}

	protected function migrasi_2021060851($hasil)
	{
		if ( ! $this->db->field_exists('id_peta', 'persil'))
		{
			$hasil = $hasil && $this->dbforge->add_column('persil', 'id_peta int(60)'); // tambah id peta untuk menyimpan id area 
		}
    
		if ( ! $this->db->field_exists('id_peta', 'mutasi_cdesa'))
		{
			$hasil = $hasil && $this->dbforge->add_column('mutasi_cdesa', 'id_peta int(60)');// tambah id peta untuk menyimpan id area
		}
 
		return $hasil;
	}

	protected function migrasi_2021060901($hasil)
	{
		$hasil = $hasil && $this->tambah_table_pelapak($hasil);
		$hasil = $hasil && $this->tambah_table_produk_kategori($hasil);
		$hasil = $hasil && $this->tambah_table_produk($hasil);
		$hasil = $hasil && $this->tambah_modul_produk($hasil);
		$hasil = $hasil && $this->tambah_folder_produk($hasil);
		$hasil = $hasil && $this->tambah_pengaturan_aplikasi($hasil);

		return $hasil;
	}

	// Tabel Pelapak
	protected function tambah_table_pelapak($hasil)
	{
		$fields = [
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			],

			'id_pend' => [
				'type' => 'TINYINT',
				'constraint' => 11,
				'null' => TRUE,
				'default' => NULL
			],

			'telepon' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			],

			'lat' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			],

			'lng' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			],

			'zoom' => [
				'type' => 'TINYINT',
				'constraint' => 4,
				'null' => TRUE
			],

			'status' => [
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 1
			]
		];

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_field($fields);
		$hasil = $hasil && $this->dbforge->create_table('pelapak', TRUE);

		return $hasil;
	}

	// Tabel Produk Kategori
	protected function tambah_table_produk_kategori($hasil)
	{
		$fields = [
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			],

			'kategori' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => NULL
			],

			'slug' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => NULL
			]
		];

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_field($fields);
		$hasil = $hasil && $this->dbforge->create_table('produk_kategori', TRUE);

		return $hasil;
	}

	// Tabel Produk
	protected function tambah_table_produk($hasil)
	{
		$fields = [
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			],

			'id_pelapak' => [
				'type' => 'INT',
				'constraint' => 11,
				'null' => TRUE,
				'default' => NULL
			],

			'id_produk_kategori' => [
				'type' => 'INT',
				'constraint' => 11,
				'null' => TRUE,
				'default' => NULL
			],

			'nama' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => NULL
			],

			'harga' => [
				'type' => 'INT',
				'constraint' => 11,
				'default' => NULL
			],

			'satuan' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => NULL
			],

			'potongan' => [
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			],

			'deskripsi' => [
				'type' => 'TEXT',
				'default' => NULL
			],

			'foto' => [
				'type' => 'VARCHAR',
				'constraint' => 225,
				'null' => TRUE
			],

			'status' => [
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 1
			],

			'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
			'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
		];

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_field($fields);
		$hasil = $hasil && $this->dbforge->create_table('produk', TRUE);

		$hasil = $hasil && $this->tambah_foreign_key('lapak_fk', 'produk', 'id_pelapak', 'pelapak', 'id');
		$hasil = $hasil && $this->tambah_foreign_key('produk_kategori_fk', 'produk', 'id_produk_kategori', 'produk_kategori', 'id');

		return $hasil;
	}

	// Menu Produk / Lapak
	protected function tambah_modul_produk($hasil)
	{
		$fields = [
			'id' => 324,
			'modul' => 'Lapak',
			'url' => 'lapak_admin',
			'aktif' => 1,
			'ikon' => 'fa-cart-plus',
			'urut' => 122,
			'level' => 2,
			'hidden' => 0,
			'ikon_kecil' => 'fa-cart-plus',
			'parent' => 0
		];

		$hasil =& $this->tambah_modul($fields);

		// Hapus cache menu navigasi
		$this->load->driver('cache');
		$this->cache->hapus_cache_untuk_semua('_cache_modul');

		return $hasil;
	}

	protected function tambah_folder_produk($hasil)
	{
		$folder = 'upload/produk';
		if ( ! file_exists('/desa/' . $folder))
		{
			mkdir('desa/' . $folder, 0755, TRUE);
			xcopy('desa-contoh/' . $folder, 'desa/' . $folder);
		}
		return $hasil;
	}

	// Menambahkan data ke setting_aplikasi
	protected function tambah_pengaturan_aplikasi($hasil)
	{
		$hasil = $hasil && $this->db->query("
			INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('tampilkan_lapak_web', '1', 'Aktif / Non-aktif Lapak di Halaman Website Url Terpisah', 'boolean', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

		$hasil = $hasil && $this->db->query("
			INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('pesan_singkat_wa', 'Saya ingin membeli [nama_produk] yang anda tawarkan', 'Pesan Singkat WhatsApp', 'textarea', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");
		
		$hasil = $hasil && $this->db->query("
			INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('banyak_foto_tiap_produk', 3, 'Banyaknya foto tiap produk yang bisa di unggah', 'int', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

		$hasil = $hasil && $this->db->query("
			INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('tampilkan_di_halaman_utama_web', '1', 'Aktif / Non-aktif Lapak di Halaman Utama Website', 'boolean', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

		return $hasil;
	}

	protected function migrasi_2021061201($hasil)
	{
		// Ubah nilai default zoom yang sudah ada
		$hasil = $hasil && $this->db->where('zoom', NULL)->update('pelapak', ['zoom' => 10]);
		
		// Ubah default nilai zoom table pelapak
		$fields = [
			'zoom' => [
				'name' => 'zoom',
				'type' => 'TINYINT',
				'constraint' => 4,
				'null' => FALSE,
				'default' => 10
			],
		];

		$hasil = $hasil && $this->dbforge->modify_column('pelapak', $fields);

		return $hasil;
	}

	protected function migrasi_2021061301($hasil)
	{
		// Ubah tipe data id_pend pada tabel pelapak
		$fields = [
			'id_pend' => [
				'type' => 'INT',
				'constraint' => 11,
			],
		];

		$hasil = $hasil && $this->dbforge->modify_column('pelapak', $fields);

		return $hasil;
	}

	// Menambahkan data ke setting_aplikasi
	protected function migrasi_2021061651($hasil)
	{
		$hasil = $hasil && $this->db->query("
			INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('jumlah_produk_perhalaman', '10', 'Jumlah produk yang ditampilkan dalam satu halaman', 'int', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");
    return $hasil;
  }

	protected function migrasi_2021061652($hasil)
	{
		// Ubah nilai default foto pada tabel user
		$fields = [
			'foto' => [
				'name' => 'foto',
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => 'kuser.png',
			],
		];

		$hasil = $hasil && $this->dbforge->modify_column('user', $fields);
		return $hasil;
	}

  protected function migrasi_2021061653($hasil)
  {
    // Hapus table provinsi
    $hasil = $hasil && $this->dbforge->drop_table('provinsi', TRUE);

    return $hasil;
  }

}
