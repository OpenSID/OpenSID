<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * Migrasi_2007_ke_2008.php
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

class Migrasi_2007_ke_2008 extends CI_model {

	public function up()
	{
		// Buat kolom nama dokumen lebih panjang
		$this->db->query("ALTER TABLE dokumen MODIFY COLUMN nama varchar(100) NOT NULL");

		$this->ubah_data_persil();
		$this->tambah_simbol_lokasi();
		$this->buat_folder_simbol_lokasi();

		// Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE point MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE point MODIFY COLUMN simbol varchar(50) DEFAULT NULL");
		$this->db->query("ALTER TABLE line MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE line MODIFY COLUMN simbol varchar(50) DEFAULT NULL");
		//Hapus sosmed google-plus
		$this->db->delete('media_sosial', ['id' => '3']);
		// Catat user yg menggunggah dokumen
		if (!$this->db->field_exists('created_at', 'dokumen'))
		{
			$this->dbforge->add_column('dokumen', 'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
  		$fields = array();
  		$fields['created_by'] = array(
	        	'type' => 'varchar',
	        	'constraint' => 16,
	        );
  		$fields['updated_by'] = array(
	        	'type' => 'varchar',
	        	'constraint' => 16,
	        );
  		$fields['dok_warga'] = array(
	        	'type' => 'tinyint',
	        	'constraint' => 1,
	        	'default' => 0
	        );
			$this->dbforge->add_column('dokumen', $fields);
  	}
  	// Perbaharui view dokumen_hidup
		$this->db->query("DROP VIEW dokumen_hidup");
		$this->db->query("CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1");
		// Ubah jenis kolom
	  $this->db->query('ALTER TABLE tweb_penduduk MODIFY dokumen_kitas VARCHAR(45) DEFAULT NULL;');

		// Buat table notifikasi
		$this->add_notifikasi();

		// Update Url Menu Identitas [Desa]
		$this->db->where('id', 17)->update('setting_modul', ['url' => 'identitas_desa']);
		$this->db->where('id', 200)->update('setting_modul', ['url' => 'identitas_desa']);
		$this->db->where('isi', 'peta_lokasi_kantor.php')
			->update('widget', ['form_admin' => 'identitas_desa/maps/kantor']);
		$this->db->where('isi', 'peta_wilayah_desa.php')
			->update('widget', ['form_admin' => 'identitas_desa/maps/wilayah']);
		// Tambahkan panjang nomor sk
	  $this->db->query('ALTER TABLE tweb_desa_pamong MODIFY pamong_nosk VARCHAR(30) DEFAULT NULL;');
		// Tambahkan panjang keterangan program bantuan
	  $this->db->query('ALTER TABLE program MODIFY ndesc VARCHAR(500) DEFAULT NULL;');
	}

	private function add_notifikasi()
	{
		if (!$this->db->table_exists('notifikasi') )
		{
			$query = "
			CREATE TABLE `notifikasi` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`kode` varchar(100) NOT NULL,
				`judul` varchar(100) NOT NULL,
				`jenis` varchar(50) NOT NULL,
				`isi` text NOT NULL,
				`server` varchar(20) NOT NULL,
				`tgl_berikutnya` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
				`updated_by` int(11) NOT NULL,
				`frekuensi` smallint(6) NOT NULL,
				`aksi` varchar(100) NOT NULL,
				`aktif` tinyint(2) NOT NULL DEFAULT 1,
				PRIMARY KEY (`id`),
				UNIQUE KEY (kode)
			)";
			$this->db->query($query);
		}

		$list_data = [

			// Persetujuan Pengguna
			[
				'kode' => 'persetujuan_penggunaan',
				'judul' => '<i class="fa fa-file-text-o text-black"></i> &nbsp;Persetujuan Penggunaan OpenSID',
				'jenis' => 'persetujuan',
				'isi' =>
					'<p><b>Untuk menggunakan OpenSID, anda dan desa anda perlu menyetujui ketentuan berikut:</b>
					<ol>
						<li>Pengguna telah membaca dan menyetujui <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">Lisensi GPL V3</a>.</li>
						<li>OpenSID gratis dan disediakan "SEBAGAIMANA ADANYA", di mana segala tanggung jawab termasuk keamanan data desa ada pada pengguna.</li>
						<li>Pengguna paham bahwa setiap ubahan OpenSID juga berlisensi GPL V3 yang tidak dapat dimusnahkan, dan aplikasi ubahan itu juga sumber terbuka yang bebas disebarkan oleh pihak yang menerima.</li>
						<li>Pengguna mengetahui, paham dan menyetujui bahwa OpenSID akan mengirim data penggunaan ke server OpenDesa secara berkala untuk tujuan menyempurnakan OpenSID, dengan pengertian bahwa data yang dikirim sama sekali tidak berisi data identitas penduduk atau data sensitif desa lainnya.</li>
					</ol></p>
					<b>Apakah anda dan desa anda setuju dengan ketentuan di atas?</b>',
				'server' => 'client',
				'tgl_berikutnya' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				'updated_by' => 0,
				'frekuensi' => 90,
				'aksi' => 'notif/update_pengumuman,siteman'
			],

			// Tracking Off
			[
				'kode' => 'tracking_off',
				'judul' => '<i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Peringatan Tracking Off',
				'jenis' => 'peringatan',
				'isi' =>
					'<p>Kami mendeteksi bahwa anda telah mematikan fitur tracking. Bila dimatikan, penggunaan website desa anda tidak akan tercatat di server OpenDesa dan tidak akan menerima informasi penting yang sesekali dikirim OpenDesa.</p>
					<br><b>Hidupkan kembali tracking untuk mendapatkan informasi dari OpenDesa?</b>',
				'server' => 'client',
				'tgl_berikutnya' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				'updated_by' => 0,
				'frekuensi' => 90,
				'aksi' => 'setting/aktifkan_tracking,notif/update_pengumuman'
			]
		];
		foreach ($list_data as $data)
		{
			$sql = $this->db->insert_string('notifikasi', $data);
			$sql .= " ON DUPLICATE KEY UPDATE judul = VALUES(judul), jenis = VALUES(jenis), isi = VALUES(isi), server = VALUES(server), frekuensi = VALUES(frekuensi), aksi = VALUES(aksi)";
			$this->db->query($sql);
		}
	}

	private function ubah_data_persil()
	{
		// Buat tabel baru
		$this->buat_ref_persil_kelas();
		$this->buat_ref_persil_mutasi();
		$this->buat_cdesa();
		$this->buat_cdesa_penduduk();
		$this->buat_persil();
		$this->buat_mutasi_cdesa();
		// Tambah controller
		$this->tambah_modul();
		// Pindahkan data lama ke tabel baru
		$this->pindah_data_lama();
		$this->hapus_data_lama();
	}

	private function tambah_modul()
	{
		$this->db->where('id', 7)
			->update('setting_modul', array('url' => 'cdesa/clear'));
		// Tambah Modul Cdesa
		$submodul_cdesa = array('213'=>'data_persil');
		foreach ($submodul_cdesa as $key => $submodul)
		{
			$modul_nonmenu = array(
				'id' => $key,
				'modul' => $submodul,
				'url' => $submodul,
				'aktif' => '1',
				'ikon' => '',
				'urut' => 0,
				'level' => 2,
				'parent' => '7',
				'hidden' => '2',
				'ikon_kecil' => ''
			);
			$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
			$this->db->query($sql);
		}
	}

	private function buat_ref_persil_kelas()
	{
		// Buat tabel jenis Kelas Persil
		if (!$this->db->table_exists('ref_persil_kelas'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'tipe' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				),
				'kode' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				),
				'ndesc' => array(
					'type' => 'text',
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('ref_persil_kelas');
		}
		else
		{
			$this->db->truncate('ref_persil_kelas');
		}

		$data = [
			['kode' => 'S-I', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Dekat dengan Pemukiman'],
			['kode' => 'S-II', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Agak Dekat dengan Pemukiman'],
			['kode' => 'S-III', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Jauh dengan Pemukiman'],
			['kode' => 'S-IV', 'tipe' => 'BASAH', 'ndesc' => 'Persawahan Sangat Jauh dengan Pemukiman'],
			['kode' => 'D-I', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Dekat dengan Pemukiman'],
			['kode' => 'D-II', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Agak Dekat dengan Pemukiman'],
			['kode' => 'D-III', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Jauh dengan Pemukiman'],
			['kode' => 'D-IV', 'tipe' => 'KERING', 'ndesc' => 'Lahan Kering Sanga Jauh dengan Pemukiman'],
			];
		$this->db->insert_batch('ref_persil_kelas', $data);
	}

	private function buat_ref_persil_mutasi()
	{
		// Buat tabel ref Mutasi Persil
		if (!$this->db->table_exists('ref_persil_mutasi'))
		{
			$fields = array(
				'id' => array(
					'type' => 'TINYINT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				),
				'ndesc' => array(
					'type' => 'text',
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('ref_persil_mutasi');
		}
		else
		{
			$this->db->truncate('ref_persil_mutasi');
		}

		$data = [
			['nama' => 'Jual Beli', 'ndesc' => 'Didapat dari proses Jual Beli'],
			['nama' => 'Hibah', 'ndesc' => 'Didapat dari proses Hibah'],
			['nama' => 'Waris', 'ndesc' => 'Didapat dari proses Waris'],
			];
		$this->db->insert_batch('ref_persil_mutasi', $data);
	}

	private function buat_cdesa()
	{
		// Buat tabel C-DESA
		if (!$this->db->table_exists('cdesa') )
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nomor' => array(
					'type' => 'VARCHAR',
					'constraint' => 20,
					'unique' => TRUE
				),
				'nama_kepemilikan' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'unique' => TRUE
				),
				'jenis_pemilik' => array(
					'type' => 'TINYINT',
					'constraint' => 1,
					'default' => 0
				),
				'nama_pemilik_luar' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null' => true,
					'default' => null
				),
				'alamat_pemilik_luar' => array(
					'type' => 'VARCHAR',
					'constraint' => 200,
					'null' => true,
					'default' => null
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_field("created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
			$this->dbforge->add_field("created_by int(11) NOT NULL");
			$this->dbforge->add_field("updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
			$this->dbforge->add_field("updated_by int(11) NOT NULL");
			$this->dbforge->create_table('cdesa');
		}
	}

	private function buat_cdesa_penduduk()
	{
		// Buat tabel C-DESA
		if (!$this->db->table_exists('cdesa_penduduk') )
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_cdesa' => array(
					'type' => 'INT',
					'unsigned' => TRUE,
					'constraint' => 5,
				),
				'id_pend' => array(
					'type' => 'INT',
					'constraint' => 11
				),
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('cdesa_penduduk');
			$this->db->query("ALTER TABLE `cdesa_penduduk` ADD INDEX `id_cdesa` (`id_cdesa`)");
			$this->dbforge->add_column('cdesa_penduduk', array(
	    	'CONSTRAINT `cdesa_penduduk_fk` FOREIGN KEY (`id_cdesa`) REFERENCES `cdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
			));
		}
	}

	private function buat_persil()
	{
		//tambahkan kolom untuk beberapa data persil
		if (!$this->db->table_exists('persil'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nomor' => array(
					'type' => 'VARCHAR',
					'constraint' => 20
				),
				'nomor_urut_bidang' => array(
					'type' => 'TINYINT',
					'constraint' => 3,
					'default' => 1
				),
				'kelas' => array(
					'type' => 'INT',
					'constraint' => 5
				),
				'luas_persil' => array(
					'type' => 'decimal',
					'constraint' => 7,
					'null' => TRUE
				),
				'id_wilayah' => array(
					'type' => 'INT',
					'constraint' => 11,
					'null' =>TRUE
				),
				'lokasi' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
				'path' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
				'cdesa_awal' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_key(['nomor', 'nomor_urut_bidang']);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('persil');
		}
	}

	private function buat_mutasi_cdesa()
	{
		// Buat tabel mutasi Persil
		if (!$this->db->table_exists('mutasi_cdesa'))
		{
			$fields = array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_cdesa_masuk' => array(
					'type' => 'INT',
					'unsigned' => TRUE,
					'constraint' => 5,
					'null' => TRUE
				),
				'cdesa_keluar' => array(
					'type' => 'INT',
					'unsigned' => TRUE,
					'constraint' => 5,
					'null' => TRUE
				),
				'jenis_mutasi' => array(
					'type' => 'TINYINT',
					'constraint' => 2,
					'null' => TRUe
				),
				'tanggal_mutasi' => array(
					'type' => 'DATE',
					'null' => TRUE
				),
				'keterangan' => array(
					'type' => 'TEXT',
					'null' => TRUE
				),
				'id_persil' => array(
					'type' => 'INT',
					'constraint' => 11
				),
				'no_bidang_persil' => array(
					'type' => 'TINYINT',
					'constraint' => 3,
					'null' => TRUE
				),
				'luas' => array(
					'type' => 'decimal',
					'constraint' => 7,
					'null' => TRUE
				),
				'no_objek_pajak' => array(
					'type' => 'VARCHAR',
					'constraint' => 30,
					'null' => TRUE
				),
				'path' => array(
					'type' => 'TEXT',
					'null' => TRUE
				)
			);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('mutasi_cdesa');
			$this->dbforge->add_column('mutasi_cdesa', array(
	    	'CONSTRAINT `cdesa_mutasi_fk` FOREIGN KEY (`id_cdesa_masuk`) REFERENCES `cdesa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
			));
		}
	}

	private function pindah_data_lama()
	{
		if (!$this->db->table_exists('data_persil')) return;

		$data = $this->db->get('data_persil')->result_array();
		foreach ($data as $persil)
		{
			// 1. Buat persil
			$baru = [
				'nomor' => $persil['nama'],
				'kelas' => 0, // $persil['kelas'] tidak dapat digunakan karena bukan kode
				'luas_persil' => $persil['luas'],
				'id_wilayah' => $persil['id_clusterdesa'],
				'path' => $persil['peta']
			];
			$this->db->insert('persil', $baru);
			$id_persil = $this->db->insert_id();
			// 2. Buat cdesa sebagai pemilik awal setiap persil
			if ($persil['jenis_pemilik'] == 1)
			{
				$nama_pemilik = $this->db->select('nama')
					->from('tweb_penduduk')
					->where('id', $persil['id_pend'])
					->get()->row()
					->nama;
			}
			else
			{
				$nama_pemilik = $persil['pemilik_luar'];
			}
			$cdesa = [
				'nomor' => $persil['nama'], // samakan dengan nonmor persil
				'nama_kepemilikan' => $nama_pemilik,
				'jenis_pemilik' => $persil['jenis_pemilik'],
				'nama_pemilik_luar' => $persil['pemilik_luar'],
				'alamat_pemilik_luar' => $persil['alamat_luar'],
				'created_at' => $persil['rdate'],
				'created_by' => $persil['userID']
			];
			$this->db->insert('cdesa', $cdesa);
			$id_cdesa = $this->db->insert_id();
			$this->db->where('id', $id_persil)
				->update('persil', ['cdesa_awal' => $id_cdesa]);
			$mutasi = [
 				'id_cdesa_masuk' => $id_cdesa,
 				'jenis_mutasi' => '9',
 				'tanggal_mutasi' => $persil['rdate'],
 				'id_persil' => $id_persil,
 				'luas' => $persil['luas'],
 				'no_objek_pajak' => $persil['no_sppt_pbb'],
 				'keterangan' => 'Pemilik awal persil ini',
			];
	 		$this->db->insert('mutasi_cdesa', $mutasi);
			// 3. Kalau pemilik adalah warga desa, buat cdesa_penduduk
			if ($persil['jenis_pemilik'] == 1)
			{
				$this->db->insert('cdesa_penduduk', ['id_cdesa' => $id_cdesa, 'id_pend' => $persil['id_pend']]);
			}
		}
	}

	private function hapus_data_lama()
	{
		if ($this->db->table_exists('data_persil')) $this->dbforge->drop_table('data_persil');
		if ($this->db->table_exists('data_persil_jenis')) $this->dbforge->drop_table('data_persil_jenis');
		if ($this->db->table_exists('data_persil_peruntukan')) $this->dbforge->drop_table('data_persil_peruntukan');
	}

	private function tambah_simbol_lokasi()
	{
		//Tambah kolom id dan attribute unique di tabel gis_simbol
		if (!$this->db->field_exists('id', 'gis_simbol'))
		$this->db->query("ALTER TABLE gis_simbol ADD id INT NOT NULL AUTO_INCREMENT KEY FIRST");
		$this->db->query("ALTER TABLE gis_simbol ADD UNIQUE(`simbol`)");

		//Tambah Beberapa Simbol Lokasi Pelayanan Umum, Instansi Pemerintah, TNI/Polri
		$query = "
			INSERT INTO `gis_simbol` (`id`, `simbol`) VALUES
			(611, 'aa_bni.png'),
			(612, 'aa_bri.png'),
			(613, 'aa_btn.png'),
			(614, 'aa_btp.png'),
			(615, 'aa_pajak.png'),
			(616, 'aa_pdam.png'),
			(617, 'aa_pgadai.png'),
			(618, 'aa_pln.png'),
			(619, 'aa_pmi.png'),
			(620, 'aa_polisi.png'),
			(621, 'aa_prtmn.png'),
			(622, 'aa_pskms.png'),
			(623, 'aa_ptrns.png'),
			(624, 'aa_pwbdh.png'),
			(625, 'aa_pwhnd.png'),
			(626, 'aa_pwisl.png'),
			(627, 'aa_pwkhc.png'),
			(628, 'aa_pwkrs.png'),
			(629, 'aa_sk.png'),
			(630, 'aa_skagm.png'),
			(631, 'aa_skint.png'),
			(632, 'aa_sksd.png'),
			(633, 'aa_sksma.png'),
			(634, 'aa_sksmp.png'),
			(635, 'aa_sktk.png'),
			(636, 'aa_tniad.png'),
			(637, 'aa_tnial.png'),
			(638, 'aa_tniau.png')
			ON DUPLICATE KEY UPDATE simbol = VALUES(simbol)";
		$this->db->query($query);
	}

	private function buat_folder_simbol_lokasi()
	{
		// Tambah folder desa untuk menyimpan simbol lokasi
		$new_dir = LOKASI_SIMBOL_LOKASI;
		if (!file_exists($new_dir))
		{
			$outp = true;
			mkdir($new_dir, 0755);
			$dir = LOKASI_SIMBOL_LOKASI_DEF;
			$files = scandir($dir);
			foreach ($files as $file)
			{
				if (!empty($file) && $file != '.' && $file != '..')
				{
					$source = $dir.'/'.$file;
					$destination = $new_dir.'/'.$file;
					$outp = $outp and copy($source, $destination);
				}
			}
			status_sukses($outp);
		}
	}
}
