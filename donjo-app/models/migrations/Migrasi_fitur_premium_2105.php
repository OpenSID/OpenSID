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
		$hasil =& $this->dbforge->modify_column('program_peserta', $fields);
		$hasil =& $this->create_table_tanah_desa($hasil);
		$hasil =& $this->create_table_tanah_kas_desa($hasil);
		$hasil =& $this->bumindes_updates($hasil);
		$hasil =& $this->server_publik();
		$hasil =& $this->convert_ip_address($hasil);
		$hasil =& $this->hapus_kolom_tanah_di_desa();
		$hasil =& $this->hapus_kolom_tanah_kas_desa();
		$hasil =& $this->ubah_kolom_tanah_di_desa();
		$hasil =& $this->tambah_kolom_tanah_di_desa();
		$hasil =& $this->tambah_kolom_tanah_kas_desa();

		status_sukses($hasil);
		return $hasil;
	}

	protected function create_table_tanah_desa($hasil)
	{
		$this->dbforge->add_field([
			'id'                => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],		
			'id_penduduk'       => ['type' => 'INT', 'constraint' => 10],	
			'nama_pemilik_asal'	=> ['type' => 'VARCHAR', 'constraint' => 200],
			'hak_tanah'         => ['type' => 'TEXT'],
			'penggunaan_tanah'	=> ['type' => 'TEXT'],
			'luas'     			=> ['type' => 'INT', 'constraint' => 10],
			'lain' 				=> ['type' => 'TEXT'],
			'mutasi' 			=> ['type' => 'TEXT'],
			'keterangan' 		=> ['type' => 'TEXT'],
			'created_at timestamp default current_timestamp',
			'created_by'        => ['type' => 'INT', 'constraint' => 10],
			'updated_at timestamp default current_timestamp',
			'updated_by'        => ['type' => 'INT', 'constraint' => 10],
			'visible'           => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 1],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('id_penduduk');
		$hasil =& $this->dbforge->create_table('tanah_desa', true);	
		return $hasil;
	}

	protected function create_table_tanah_kas_desa($hasil)
	{
		$this->dbforge->add_field([
			'id'                => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],			
			'nama_pemilik_asal'	=> ['type' => 'VARCHAR', 'constraint' => 200],
			'letter_c'          => ['type' => 'TEXT'],
			'persil'         	=> ['type' => 'TEXT'],
			'kelas' 		    => ['type' => 'TEXT'],
			'luas'     			=> ['type' => 'INT', 'constraint' => 10],
			'perolehan_tkd'     => ['type' => 'TEXT'],
			'jenis_tkd'         => ['type' => 'TEXT'],
			'patok'		        => ['type' => 'TEXT'],
			'papan_nama'		=> ['type' => 'TEXT'],
			'tanggal_perolehan date',
			'lokasi'			=> ['type' => 'TEXT'],
			'peruntukan' 		=> ['type' => 'TEXT'],
			'mutasi' 			=> ['type' => 'TEXT'],
			'keterangan' 		=> ['type' => 'TEXT'],
			'created_at timestamp default current_timestamp',
			'created_by'        => ['type' => 'INT', 'constraint' => 10],
			'updated_at timestamp default current_timestamp',
			'updated_by'        => ['type' => 'INT', 'constraint' => 10],
			'visible'           => ['type' => 'TINYINT', 'constraint' => 2, 'default' => 1],
		]);

		$this->dbforge->add_key('id', true);
		$hasil =& $this->dbforge->create_table('tanah_kas_desa', true);
		return $hasil;
	}

	protected function bumindes_updates($hasil)
	{

		$hasil =& $this->db->where('id', 305)->update('setting_modul', ['url' => 'bumindes_tanah_desa/clear']);

		// Menambahkan data pada setting_modul untuk controller bumindes_penduduk
		$hasil =& $this->tambah_modul([
			'id'         => 319,
			'modul'      => 'Buku Tanah Kas Desa',
			'url'        => 'bumindes_tanah_kas_desa/clear',
			'aktif'      => 1,
			'ikon'       => 'fa-files-o',
			'urut'       => 0,
			'level'      => 0,
			'hidden'     => 0,
			'ikon_kecil' => '',
			'parent'     => 305
		]);
		
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
		$hasil =& $this->tambah_kolom_updated_at();
		$hasil =& $this->buat_tabel_ref_sinkronisasi();
		return $hasil;
	}

	// Tambah kolom untuk memungkinkkan sinkronsisasi
	protected function tambah_kolom_updated_at()
	{
		$hasil = true;
		if ( ! $this->db->field_exists('updated_at', 'tweb_keluarga'))
		{
			$hasil =& $this->dbforge->add_column('tweb_keluarga', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
			$hasil =& $this->dbforge->add_column('tweb_keluarga', 'updated_by int(11) NOT NULL');
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
		$hasil =& $this->dbforge->create_table($tabel, true);
		$hasil =& $this->db->insert_batch(
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

		$hasil =& $this->db->update_batch('sys_traffic', $batch, 'Tanggal');

		return $hasil >= 0;
	}

	// Hapus kolom tanah di desa
	protected function hapus_kolom_tanah_di_desa()
	{
		$hasil = true;
		$hasil =& $this->dbforge->drop_column('tanah_desa', 'hak_tanah');
		$hasil =& $this->dbforge->drop_column('tanah_desa', 'penggunaan_tanah');

		return $hasil;
	}

	// Hapus kolom tanah kas desa
	protected function hapus_kolom_tanah_kas_desa()
	{
		$hasil = true;
		$hasil =& $this->dbforge->drop_column('tanah_kas_desa', 'perolehan_tkd');
		$hasil =& $this->dbforge->drop_column('tanah_kas_desa', 'jenis_tkd');
		$hasil =& $this->dbforge->drop_column('tanah_kas_desa', 'patok');
		$hasil =& $this->dbforge->drop_column('tanah_kas_desa', 'papan_nama');

		return $hasil;
	}

	// Ubah kolom tanah desa
	protected function ubah_kolom_tanah_di_desa()
	{
		$hasil = true;
		$fields = [
			'lain' => ['type' => 'int', 'constraint' => 11],			
		];
		$hasil =& $this->dbforge->modify_column('tanah_desa', $fields);
		return $hasil;
	}

	// Tambah kolom tanah di desa
	protected function tambah_kolom_tanah_di_desa()
	{
		$hasil = true;
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hak_milik int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hak_guna_bangunan int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hak_pakai int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hak_guna_usaha int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hak_pengelolaan int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hak_milik_adat int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hak_verponding int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'tanah_negara int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'perumahan int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'perdagangan_jasa int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'perkantoran int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'industri int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'fasilitas_umum int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'sawah int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'tegalan int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'perkebunan int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'peternakan_perikanan int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hutan_belukar int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'hutan_lebat_lindung int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_desa', 'tanah_kosong int(11) NOT NULL');

		return $hasil;
	}

	// Tambah kolom tanah kas desa
	protected function tambah_kolom_tanah_kas_desa()
	{
		$hasil = true;
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'asli_milik_desa int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'pemerintah int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'provinsi int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'kabupaten_kota int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'lain_lain int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'sawah int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'tegal int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'kebun int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'tambak_kolam int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'tanah_kering_darat int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'ada_patok int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'tidak_ada_patok int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'ada_papan_nama int(11) NOT NULL');
		$hasil =& $this->dbforge->add_column('tanah_kas_desa', 'tidak_ada_papan_nama int(11) NOT NULL');

		return $hasil;
	}
}
