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
		$hasil = $hasil && $this->migrasi_2021061951($hasil);
		$hasil = $hasil && $this->migrasi_2021062051($hasil);
		$hasil = $hasil && $this->migrasi_2021062052($hasil);
		$hasil = $hasil && $this->migrasi_2021062053($hasil);
		$hasil = $hasil && $this->migrasi_2021062152($hasil);
		$hasil = $hasil && $this->migrasi_2021062154($hasil);
		$hasil = $hasil && $this->migrasi_2021062371($hasil);
		$hasil = $hasil && $this->migrasi_2021062373($hasil);
		
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
			INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('pesan_singkat_wa', 'Saya ingin membeli [nama_produk] yang anda tawarkan di Lapak Desa [link_web]', 'Pesan Singkat WhatsApp', 'textarea', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

		$hasil = $hasil && $this->db->query("
			INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES ('banyak_foto_tiap_produk', 3, 'Banyaknya foto tiap produk yang bisa di unggah', 'int', 'lapak') ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

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

	private function migrasi_2021061951($hasil)
	{
		// Tambah hak ases group operator dan redaksi
		$query = "
			INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES
			-- Operator --
			(2,43,3), -- Aplikasi --
			(2,44,3), -- Pengguna --
			(2,45,3), -- Database --
			(2,46,3), -- Info Sistem --
			(2,214,3), -- C-Desa --
			(2,320,3), -- Buku Tanah di Desa --
			(2,321,3), -- Pendapat --
			(2,322,3), -- Buku Inventaris dan Kekayaan Desa --
			(2,323,3), -- Buku Rencana Kerja Pembangunan --
			(2,324,3), -- Lapak --

			-- Redaksi --
			(3,65,7), -- Kategori --
			(3,324,7) -- Lapak --
		";

		$hasil = $hasil && $this->db->query($query);

		return $hasil;
	}

	protected function migrasi_2021062051($hasil)
	{
		$count = $this->db->like('path', '[[[[', 'AFTER')
			->like('path', ']]]]', 'BEFORE')
			->get('config')->num_rows();

		if ($count == 0)
		{
			//update data path menjadi [[[[x,y]]],[[[x,y]]]]
			$hasil = $this->db->set('path', 'concat("[",path,"]")', false)
				->update('config');
		}

		//update data path pada dusun
		$hasil = $hasil && $this->db
			->where('rt', '0')
			->where('rw', '0')
			->like('path', '[[[', 'AFTER')
			->not_like('path', '[[[[', 'AFTER')
			->set('path', 'concat("[",path,"]")', false)
			->update('tweb_wil_clusterdesa');

		return $hasil;
	}

	protected function migrasi_2021062052($hasil)
	{
		// Tambahkan id_cluster pada tweb_keluarga yg null
		$query = "
			update tweb_keluarga as k,
				(select t.* from
				   (select id, id_kk, id_cluster from tweb_penduduk where id_kk in
					 (select id from tweb_keluarga where id_cluster is null)
				   ) t
				) as p
				set k.id_cluster = p.id_cluster
				where k.id = p.id_kk
		";

		$hasil = $hasil && $this->db->query($query);

		// Perbaiki struktur table tweb_keluarga field id_cluster tdk boleh null
		$fields = [
			'id_cluster' => [
				'name' => 'id_cluster',
				'type' => 'INT',
				'constraint' => 11,
				'null' => FALSE,
			],
		];

		$hasil = $hasil && $this->dbforge->modify_column('tweb_keluarga', $fields);

		return $hasil;
	}

	protected function migrasi_2021062053($hasil)
	{
		// Tambahkan id_cluster pada tweb_keluarga yg null
		$query = "
			update tweb_keluarga as k,
				(select t.* from
				   (select id, id_kk, id_cluster from tweb_penduduk where id_kk in
				     (select id from tweb_keluarga where id_cluster is null)
				   ) t
				) as p
				set k.id_cluster = p.id_cluster
				where k.id = p.id_kk
		";

		$hasil = $hasil && $this->db->query($query);

		// Perbaiki struktur table tweb_keluarga field id_cluster tdk boleh null
		$fields = [
			'id_cluster' => [
				'name' => 'id_cluster',
				'type' => 'INT',
				'constraint' => 11,
				'null' => FALSE,
			],
		];

		$hasil = $hasil && $this->dbforge->modify_column('tweb_keluarga', $fields);

		return $hasil;
	}

	protected function migrasi_2021062152($hasil)
	{
		// Ubah struktur field potongan table produk
		$fields = [
			'potongan' => [
				'name' => 'potongan',
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			],
		];

		$hasil = $hasil && $this->dbforge->modify_column('produk', $fields);

		if ( ! $this->db->field_exists('tipe_potongan', 'produk'))
		{
			// Tambah field tipe_potongan pada table produk
			// Tipe 1 = persen, 2 = nominal
			$fields = [
				'tipe_potongan' => [
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 1
				],
			];

			$hasil = $hasil && $this->dbforge->add_column('produk', $fields, 'satuan');
		}

		return $hasil;
	}

	protected function migrasi_2021062154($hasil)
	{
		if ( ! $this->db->field_exists('status', 'produk_kategori'))
		{
			// Tambah field status pada table produk_kategori
			$fields = [
				'status' => [
					'type' => 'TINYINT',
					'constraint' => 1,
					'null' => FALSE,
					'default' => 1
				]
			];

			$hasil = $hasil && $this->dbforge->add_column('produk_kategori', $fields);
		}

		return $hasil;
	}

	protected function migrasi_2021062371($hasil)
	{
		// Hapus key tampilkan_di_halaman_utama_web jika terlanjur migrasi (untuk tester)
		$hasil = $hasil && $this->db->where('key', 'tampilkan_di_halaman_utama_web')->delete('setting_aplikasi');
		
		return $hasil;
	}

	protected function migrasi_2021062373($hasil)
	{
    // insert kolom suku
		if ( ! $this->db->field_exists('suku', 'tweb_penduduk'))
			$hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', ['suku' => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => TRUE]]);
		
		// create table master suku
		if ( ! $this->db->table_exists('ref_penduduk_suku'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 65,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'suku'=> array(
					'type' => 'VARCHAR',
					'constraint' => 100
				),
				'deskripsi' => array(
					'type' => 'TEXT'
				),
			);
			$hasil = $hasil && $this->dbforge->add_field($fields);
			$hasil = $hasil && $this->dbforge->add_key('id', TRUE);
			$hasil = $hasil && $this->dbforge->create_table("ref_penduduk_suku", TRUE);

			// tambahkan data awal
			$insert_batch = array(
				array('suku' => 'Aceh', 'deskripsi' => 'Aceh'),
				array('suku' => 'Alas', 'deskripsi' => 'Aceh'),
				array('suku' => 'Alor', 'deskripsi' => 'NTT'),
				array('suku' => 'Ambon', 'deskripsi' => 'Ambon'),
				array('suku' => 'Ampana', 'deskripsi' => 'Sulawesi Tengah'),
				array('suku' => 'Anak Dalam', 'deskripsi' => 'Jambi'),
				array('suku' => 'Aneuk Jamee', 'deskripsi' => 'Aceh'),
				array('suku' => 'Arab: Orang Hadhrami', 'deskripsi' => 'Arab: Orang Hadhrami'),
				array('suku' => 'Aru', 'deskripsi' => 'Maluku'),
				array('suku' => 'Asmat', 'deskripsi' => 'Papua'),
				array('suku' => 'Bare’e', 'deskripsi' => 'Bare’e di Kabupaten Tojo Una-Una Tojo dan Tojo Barat'),
				array('suku' => 'Banten', 'deskripsi' => 'Banten di Banten'),
				array('suku' => 'Besemah', 'deskripsi' => 'Besemah di Sumatera Selatan'),
				array('suku' => 'Bali', 'deskripsi' => 'Bali di Bali terdiri dari: Suku Bali Majapahit di sebagian besar Pulau Bali; Suku Bali Aga di Karangasem dan Kintamani'),
				array('suku' => 'Balantak', 'deskripsi' => 'Balantak di Sulawesi Tengah'),
				array('suku' => 'Banggai', 'deskripsi' => 'Banggai di Sulawesi Tengah (Kabupaten Banggai Kepulauan)'),
				array('suku' => 'Baduy', 'deskripsi' => 'Baduy di Banten'),
				array('suku' => 'Bajau', 'deskripsi' => 'Bajau di Kalimantan Timur'),
				array('suku' => 'Banjar', 'deskripsi' => 'Banjar di Kalimantan Selatan'),
				array('suku' => 'Batak', 'deskripsi' => 'Sumatera Utara'),
				array('suku' => 'Batak Karo', 'deskripsi' => 'Sumatera Utara'),
				array('suku' => 'Mandailing', 'deskripsi' => 'Sumatera Utara'),
				array('suku' => 'Angkola', 'deskripsi' => 'Sumatera Utara'),
				array('suku' => 'Toba', 'deskripsi' => 'Sumatera Utara'),
				array('suku' => 'Pakpak', 'deskripsi' => 'Sumatera Utara'),
				array('suku' => 'Simalungun', 'deskripsi' => 'Sumatera Utara'),
				array('suku' => 'Batin', 'deskripsi' => 'Batin di Jambi'),
				array('suku' => 'Bawean', 'deskripsi' => 'Bawean di Jawa Timur (Gresik)'),
				array('suku' => 'Bentong', 'deskripsi' => 'Bentong di Sulawesi Selatan'),
				array('suku' => 'Berau', 'deskripsi' => 'Berau di Kalimantan Timur (kabupaten Berau)'),
				array('suku' => 'Betawi', 'deskripsi' => 'Betawi di Jakarta'),
				array('suku' => 'Bima', 'deskripsi' => 'Bima NTB (kota Bima)'),
				array('suku' => 'Boti', 'deskripsi' => 'Boti di kabupaten Timor Tengah Selatan'),
				array('suku' => 'Bolang Mongondow', 'deskripsi' => 'Bolang Mongondow di Sulawesi Utara (Kabupaten Bolaang Mongondow)'),
				array('suku' => 'Bugis', 'deskripsi' => 'Bugis di Sulawesi Selatan: Orang Bugis Pagatan di Kalimantan Selatan, Kusan Hilir, Tanah Bumbu'),
				array('suku' => 'Bungku', 'deskripsi' => 'Bungku di Sulawesi Tengah (Kabupaten Morowali)'),
				array('suku' => 'Buru', 'deskripsi' => 'Buru di Maluku (Kabupaten Buru)'),
				array('suku' => 'Buol', 'deskripsi' => 'Buol di Sulawesi Tengah (Kabupaten Buol)'),
				array('suku' => 'Bulungan ', 'deskripsi' => 'Bulungan di Kalimantan Timur (Kabupaten Bulungan)'),
				array('suku' => 'Buton', 'deskripsi' => 'Buton di Sulawesi Tenggara (Kabupaten Buton dan Kota Bau-Bau)'),
				array('suku' => 'Bonai', 'deskripsi' => 'Bonai di Riau (Kabupaten Rokan Hilir)'),
				array('suku' => 'Cham ', 'deskripsi' => 'Cham di Aceh'),
				array('suku' => 'Cirebon ', 'deskripsi' => 'Cirebon di Jawa Barat (Kota Cirebon)'),
				array('suku' => 'Damal', 'deskripsi' => 'Damal di Mimika'),
				array('suku' => 'Dampeles', 'deskripsi' => 'Dampeles di Sulawesi Tengah'),
				array('suku' => 'Dani ', 'deskripsi' => 'Dani di Papua (Lembah Baliem)'),
				array('suku' => 'Dairi', 'deskripsi' => 'Dairi di Sumatera Utara'),
				array('suku' => 'Daya ', 'deskripsi' => 'Daya di Sumatera Selatan'),
				array('suku' => 'Dayak', 'deskripsi' => 'Dayak terdiri dari: Suku Dayak Ahe di Kalimantan Barat; Suku Dayak Bajare di Kalimantan Barat; Suku Dayak Damea di Kalimantan Barat; Suku Dayak Banyadu di Kalimantan Barat; Suku Bakati di Kalimantan Barat; Suku Punan di Kalimantan Tengah; Suku Kanayatn di Kalimantan Barat; Suku Dayak Krio di Kalimantan Barat (Ketapang); Suku Dayak Sungai Laur di Kalimantan Barat (Ketapang); Suku Dayak Simpangh di Kalimantan Barat (Ketapang); Suku Iban di Kalimantan Barat; Suku Mualang di Kalimantan Barat (Sekada'),
				array('suku' => 'Dompu', 'deskripsi' => 'Dompu NTB (Kabupaten Dompu)'),
				array('suku' => 'Donggo', 'deskripsi' => 'Donggo, Bima'),
				array('suku' => 'Dongga', 'deskripsi' => 'Donggala di Sulawesi Tengah'),
				array('suku' => 'Dondo ', 'deskripsi' => 'Dondo di Sulawesi Tengah (Kabupaten Toli-Toli)'),
				array('suku' => 'Duri', 'deskripsi' => 'Duri Terletak di bagian utara Kabupaten Enrekang berbatasan dengan Kabupaten Tana Toraja, meliputi tiga kecamatan induk Anggeraja, Baraka, dan Alla di Sulawesi Selatan'),
				array('suku' => 'Eropa ', 'deskripsi' => 'Eropa (orang Indo, peranakan Eropa-Indonesia, atau etnik Mestizo)'),
				array('suku' => 'Flores', 'deskripsi' => 'Flores di NTT (Flores Timur)'),
				array('suku' => 'Lamaholot', 'deskripsi' => 'Lamaholot, Flores Timur, terdiri dari: Suku Wandan, di Solor Timur, Flores Timur; Suku Kaliha, di Solor Timur, Flores Timur; Suku Serang Gorang, di Solor Timur, Flores Timur; Suku Lamarobak, di Solor Timur, Flores Timur; Suku Atanuhan, di Solor Timur, Flores Timur; Suku Wotan, di Solor Timur, Flores Timur; Suku Kapitan Belen, di Solor Timur, Flores Timur'),
				array('suku' => 'Gayo', 'deskripsi' => 'Gayo di Aceh (Gayo Lues Aceh Tengah Bener Meriah Aceh Tenggara Aceh Timur Aceh Tamiang)'),
				array('suku' => 'Gorontalo', 'deskripsi' => 'Gorontalo di Gorontalo (Kota Gorontalo)'),
				array('suku' => 'Gumai ', 'deskripsi' => 'Gumai di Sumatera Selatan (Lahat)'),
				array('suku' => 'India', 'deskripsi' => 'India, terdiri dari: Suku Tamil di Aceh, Sumatera Utara, Sumatera Barat, dan DKI Jakarta; Suku Punjab di Sumatera Utara, DKI Jakarta, dan Jawa Timur; Suku Bengali di DKI Jakarta; Suku Gujarati di DKI Jakarta dan Jawa Tengah; Orang Sindhi di DKI Jakarta dan Jawa Timur; Orang Sikh di Sumatera Utara, DKI Jakarta, dan Jawa Timur'),
				array('suku' => 'Jawa', 'deskripsi' => 'Jawa di Jawa Tengah, Jawa Timur, DI Yogyakarta'),
				array('suku' => 'Tengger', 'deskripsi' => 'Tengger di Jawa Timur (Probolinggo, Pasuruan, dan Malang)'),
				array('suku' => 'Osing ', 'deskripsi' => 'Osing di Jawa Timur (Banyuwangi)'),
				array('suku' => 'Samin ', 'deskripsi' => 'Samin di Jawa Tengah (Purwodadi)'),
				array('suku' => 'Bawean', 'deskripsi' => 'Bawean di Jawa Timur (Pulau Bawean)'),
				array('suku' => 'Jambi ', 'deskripsi' => 'Jambi di Jambi (Kota Jambi)'),
				array('suku' => 'Jepang', 'deskripsi' => 'Jepang di DKI Jakarta, Jawa Timur, dan Bali'),
				array('suku' => 'Kei', 'deskripsi' => 'Kei di Maluku Tenggara (Kabupaten Maluku Tenggara dan Kota Tual)'),
				array('suku' => 'Kaili ', 'deskripsi' => 'Kaili di Sulawesi Tengah (Kota Palu)'),
				array('suku' => 'Kampar', 'deskripsi' => 'Kampar'),
				array('suku' => 'Kaur ', 'deskripsi' => 'Kaur di Bengkulu (Kabupaten Kaur)'),
				array('suku' => 'Kayu Agung', 'deskripsi' => 'Kayu Agung di Sumatera Selatan'),
				array('suku' => 'Kerinci', 'deskripsi' => 'Kerinci di Jambi (Kabupaten Kerinci)'),
				array('suku' => 'Komering ', 'deskripsi' => 'Komering di Sumatera Selatan (Kabupaten Ogan Komering Ilir, Baturaja)'),
				array('suku' => 'Konjo Pegunungan', 'deskripsi' => 'Konjo Pegunungan, Kabupaten Gowa, Sulawesi Selatan'),
				array('suku' => 'Konjo Pesisir', 'deskripsi' => 'Konjo Pesisir, Kabupaten Bulukumba, Sulawesi Selatan'),
				array('suku' => 'Koto', 'deskripsi' => 'Koto di Sumatera Barat'),
				array('suku' => 'Kubu', 'deskripsi' => 'Kubu di Jambi dan Sumatera Selatan'),
				array('suku' => 'Kulawi', 'deskripsi' => 'Kulawi di Sulawesi Tengah'),
				array('suku' => 'Kutai ', 'deskripsi' => 'Kutai di Kalimantan Timur (Kutai Kartanegara)'),
				array('suku' => 'Kluet ', 'deskripsi' => 'Kluet di Aceh (Aceh Selatan)'),
				array('suku' => 'Korea ', 'deskripsi' => 'Korea di DKI Jakarta'),
				array('suku' => 'Krui', 'deskripsi' => 'Krui di Lampung'),
				array('suku' => 'Laut,', 'deskripsi' => 'Laut, Kepulauan Riau'),
				array('suku' => 'Lampung', 'deskripsi' => 'Lampung, terdiri dari: Suku Sungkai di Lampung; Suku Abung di Lampung; Suku Way Kanan di Lampung, Sumatera Selatan dan Bengkulu; Suku Pubian di Lampung; Suku Tulang Bawang di Lampung; Suku Melinting di Lampung; Suku Peminggir Teluk di Lampung; Suku Ranau di Lampung, Sumatera Selatan dan Sumatera Utara; Suku Komering di Sumatera Selatan; Suku Cikoneng di Banten; Suku Merpas di Bengkulu; Suku Belalau di Lampung; Suku Smoung di Lampung; Suku Semaka di Lampung'),
				array('suku' => 'Lematang ', 'deskripsi' => 'Lematang di Sumatera Selatan'),
				array('suku' => 'Lembak', 'deskripsi' => 'Lembak, Kabupaten Rejang Lebong, Bengkulu'),
				array('suku' => 'Lintang', 'deskripsi' => 'Lintang, Sumatera Selatan'),
				array('suku' => 'Lom', 'deskripsi' => 'Lom, Bangka Belitung'),
				array('suku' => 'Lore', 'deskripsi' => 'Lore, Sulawesi Tengah'),
				array('suku' => 'Lubu', 'deskripsi' => 'Lubu, daerah perbatasan antara Provinsi Sumatera Utara dan Provinsi Sumatera Barat'),
				array('suku' => 'Moronene', 'deskripsi' => 'Moronene di Sulawesi Tenggara.'),
				array('suku' => 'Madura', 'deskripsi' => 'Madura di Jawa Timur (Pulau Madura, Kangean, wilayah Tapal Kuda)'),
				array('suku' => 'Makassar', 'deskripsi' => 'Makassar di Sulawesi Selatan: Kabupaten Gowa, Kabupaten Takalar, Kabupaten Jeneponto, Kabupaten Bantaeng, Kabupaten Bulukumba (sebagian), Kabupaten Sinjai (bagian perbatasan Kab Gowa), Kabupaten Maros (sebagian), Kabupaten Pangkep (sebagian), Kota Makassar'),
				array('suku' => 'Mamasa', 'deskripsi' => 'Mamasa (Toraja Barat) di Sulawesi Barat: Kabupaten Mamasa'),
				array('suku' => 'Manda', 'deskripsi' => 'Mandar Sulawesi Barat: Polewali Mandar'),
				array('suku' => 'Melayu', 'deskripsi' => 'Melayu, terdiri dari Suku Melayu Tamiang di Aceh (Aceh Tamiang); Suku Melayu Riau di Riau dan Kepulauan Riau; Suku Melayu Deli di Sumatera Utara; Suku Melayu Jambi di Jambi; Suku Melayu Bangka di Pulau Bangka; Suku Melayu Belitung di Pulau Belitung; Suku Melayu Sambas di Kalimantan Barat'),
				array('suku' => 'Mentawai', 'deskripsi' => 'Mentawai di Sumatera Barat (Kabupaten Kepulauan Mentawai)'),
				array('suku' => 'Minahasa', 'deskripsi' => 'Minahasa di Sulawesi Utara (Kabupaten Minahasa), terdiri 9 subetnik : Suku Babontehu; Suku Bantik; Suku Pasan Ratahan'),
				array('suku' => 'Ponosakan', 'deskripsi' => 'Ponosakan; Suku Tonsea; Suku Tontemboan; Suku Toulour; Suku Tonsawang; Suku Tombulu'),
				array('suku' => 'Minangkabau', 'deskripsi' => 'Minangkabau, Sumatera Barat'),
				array('suku' => 'Mongondow', 'deskripsi' => 'Mongondow, Sulawesi Utara'),
				array('suku' => 'Mori', 'deskripsi' => 'Mori, Kabupaten Morowali, Sulawesi Tengah'),
				array('suku' => 'Muko-Muko', 'deskripsi' => 'Muko-Muko di Bengkulu (Kabupaten Mukomuko)'),
				array('suku' => 'Muna', 'deskripsi' => 'Muna di Sulawesi Tenggara (Kabupaten Muna)'),
				array('suku' => 'Muyu', 'deskripsi' => 'Muyu di Kabupaten Boven Digoel, Papua'),
				array('suku' => 'Mekongga', 'deskripsi' => 'Mekongga di Sulawesi Tenggara (Kabupaten Kolaka dan Kabupaten Kolaka Utara)'),
				array('suku' => 'Moro', 'deskripsi' => 'Moro di Kalimantan Barat dan Kalimantan Utara'),
				array('suku' => 'Nias', 'deskripsi' => 'Nias di Sumatera Utara (Kabupaten Nias, Nias Selatan dan Nias Utara dari dua keturunan Jepang dan Vietnam)'),
				array('suku' => 'Ngada ', 'deskripsi' => 'Ngada di NTT: Kabupaten Ngada'),
				array('suku' => 'Osing', 'deskripsi' => 'Osing di Banyuwangi Jawa Timur'),
				array('suku' => 'Ogan', 'deskripsi' => 'Ogan di Sumatera Selatan'),
				array('suku' => 'Ocu', 'deskripsi' => 'Ocu di Kabupaten Kampar, Riau'),
				array('suku' => 'Padoe', 'deskripsi' => 'Padoe di Sulawesi Tengah dan Sulawesi Selatan'),
				array('suku' => 'Papua', 'deskripsi' => 'Papua / Irian, terdiri dari: Suku Asmat di Kabupaten Asmat; Suku Biak di Kabupaten Biak Numfor; Suku Dani, Lembah Baliem, Papua; Suku Ekagi, daerah Paniai, Abepura, Papua; Suku Amungme di Mimika; Suku Bauzi, Mamberamo hilir, Papua utara; Suku Arfak di Manokwari; Suku Kamoro di Mimika'),
				array('suku' => 'Palembang', 'deskripsi' => 'Palembang di Sumatera Selatan (Kota Palembang)'),
				array('suku' => 'Pamona', 'deskripsi' => 'Pamona di Sulawesi Tengah (Kabupaten Poso) dan di Sulawesi Selatan'),
				array('suku' => 'Pesisi', 'deskripsi' => 'Pesisi di Sumatera Utara (Tapanuli Tengah)'),
				array('suku' => 'Pasir', 'deskripsi' => 'Pasir di Kalimantan Timur (Kabupaten Pasir)'),
				array('suku' => 'Pubian', 'deskripsi' => 'Pubian di Lampung'),
				array('suku' => 'Pattae', 'deskripsi' => 'Pattae di Polewali Mandar'),
				array('suku' => 'Pakistani', 'deskripsi' => 'Pakistani di Sumatera Utara, DKI Jakarta, dan Jawa Tengah'),
				array('suku' => 'Peranakan', 'deskripsi' => 'Peranakan (Tionghoa-Peranakan atau Baba Nyonya)'),
				array('suku' => 'Rawa', 'deskripsi' => 'Rawa, Rokan Hilir, Riau'),
				array('suku' => 'Rejang', 'deskripsi' => 'Rejang di Bengkulu (Kabupaten Bengkulu Tengah, Kabupaten Bengkulu Utara, Kabupaten Kepahiang, Kabupaten Lebong, dan Kabupaten Rejang Lebong)'),
				array('suku' => 'Rote', 'deskripsi' => 'Rote di NTT (Kabupaten Rote Ndao)'),
				array('suku' => 'Rongga', 'deskripsi' => 'Rongga di NTT Kabupaten Manggarai Timur'),
				array('suku' => 'Rohingya', 'deskripsi' => 'Rohingya'),
				array('suku' => 'Sabu', 'deskripsi' => 'Sabu di Pulau Sabu, NTT'),
				array('suku' => 'Saluan', 'deskripsi' => 'Saluan di Sulawesi Tengah'),
				array('suku' => 'Sambas', 'deskripsi' => 'Sambas (Melayu Sambas) di Kalimantan Barat: Kabupaten Sambas'),
				array('suku' => 'Samin', 'deskripsi' => 'Samin di Jawa Tengah (Blora) dan Jawa Timur (Bojonegoro)'),
				array('suku' => 'Sangi', 'deskripsi' => 'Sangir di Sulawesi Utara (Kepulauan Sangihe)'),
				array('suku' => 'Sasak', 'deskripsi' => 'Sasak di NTB, Lombok'),
				array('suku' => 'Sekak Bangka', 'deskripsi' => 'Sekak Bangka'),
				array('suku' => 'Sekayu', 'deskripsi' => 'Sekayu di Sumatera Selatan'),
				array('suku' => 'Semendo ', 'deskripsi' => 'Semendo di Bengkulu, Sumatera Selatan (Muara Enim)'),
				array('suku' => 'Serawai ', 'deskripsi' => 'Serawai di Bengkulu (Kabupaten Bengkulu Selatan dan Kabupaten Seluma)'),
				array('suku' => 'Simeulue', 'deskripsi' => 'Simeulue di Aceh (Kabupaten Simeulue)'),
				array('suku' => 'Sigulai ', 'deskripsi' => 'Sigulai di Aceh (Kabupaten Simeulue bagian utara'),
				array('suku' => 'Suluk', 'deskripsi' => 'Suluk di Kalimantan Utara)'),
				array('suku' => 'Sumbawa ', 'deskripsi' => 'Sumbawa Di NTB (Kabupaten Sumbawa)'),
				array('suku' => 'Sumba', 'deskripsi' => 'Sumba di NTT (Sumba Barat, Sumba Timur)'),
				array('suku' => 'Sunda', 'deskripsi' => 'Sunda di Jawa Barat, Banten, DKI Jakarta, Lampung, Sumatra Selatan dan Jawa Tengah'),
				array('suku' => 'Sungkai ', 'deskripsi' => 'Sungkai di Lampung Lampung Utara'),
				array('suku' => 'Talau', 'deskripsi' => 'Talaud di Sulawesi Utara (Kepulauan Talaud)'),
				array('suku' => 'Talang Mamak', 'deskripsi' => 'Talang Mamak di Riau (Indragiri Hulu)'),
				array('suku' => 'Tamiang ', 'deskripsi' => 'Tamiang di Aceh (Kabupaten Aceh Tamiang)'),
				array('suku' => 'Tengger ', 'deskripsi' => 'Tengger di Jawa Timur (Kabupaten Pasuruan) dan Probolinggo (lereng G. Bromo)'),
				array('suku' => 'Ternate ', 'deskripsi' => 'Ternate di Maluku Utara (Kota Ternate)'),
				array('suku' => 'Tidore', 'deskripsi' => 'Tidore di Maluku Utara (Kota Tidore)'),
				array('suku' => 'Tidung', 'deskripsi' => 'Tidung di Kalimantan Timur (Kabupaten Tanah Tidung)'),
				array('suku' => 'Timor', 'deskripsi' => 'Timor di NTT, Kota Kupang'),
				array('suku' => 'Tionghoa', 'deskripsi' => 'Tionghoa, terdiri dari: Orang Cina Parit di Pelaihari, Tanah Laut, Kalsel; Orang Cina Benteng di Tangerang, Provinsi Banten; Orang Tionghoa Hokkien di Jawa dan Sumatera Utara; Orang Tionghoa Hakka di Belitung dan Kalimantan Barat; Orang Tionghoa Hubei; Orang Tionghoa Hainan; Orang Tionghoa Kanton; Orang Tionghoa Hokchia; Orang Tionghoa Tiochiu'),
				array('suku' => 'Tojo', 'deskripsi' => 'Tojo di Sulawesi Tengah (Kabupaten Tojo Una-Una)'),
				array('suku' => 'Toraja', 'deskripsi' => 'Toraja di Sulawesi Selatan (Tana Toraja)'),
				array('suku' => 'Tolaki', 'deskripsi' => 'Tolaki di Sulawesi Tenggara (Kendari)'),
				array('suku' => 'Toli Toli', 'deskripsi' => 'Toli Toli di Sulawesi Tengah (Kabupaten Toli-Toli)'),
				array('suku' => 'Tomini', 'deskripsi' => 'Tomini di Sulawesi Tengah (Kabupaten Parigi Mouton'),
				array('suku' => 'Una-una ', 'deskripsi' => 'Una-una di Sulawesi Tengah (Kabupaten Tojo Una-Una)'),
				array('suku' => 'Ulu', 'deskripsi' => 'Ulu di Sumatera Utara (Mandailing natal)'),
				array('suku' => 'Wolio', 'deskripsi' => 'Wolio di Sulawesi Tenggara (Buton)'),
			);

			$hasil = $hasil && $this->db->insert_batch('ref_penduduk_suku', $insert_batch);
		}

		// Update view supaya kolom baru ikut masuk
		$hasil = $hasil && $this->db->query("CREATE OR REPLACE VIEW penduduk_hidup AS SELECT * FROM tweb_penduduk WHERE status_dasar = 1");

		return $hasil;
	}
}