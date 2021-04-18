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
		$hasil = $hasil && $this->create_table_tanah_desa($hasil);
		$hasil = $hasil && $this->create_table_tanah_kas_desa($hasil);
		$hasil = $hasil && $this->bumindes_updates($hasil);
		$hasil = $hasil && $this->hapus_kolom_tanah_di_desa();
		$hasil = $hasil && $this->hapus_kolom_tanah_kas_desa();
		$hasil = $hasil && $this->ubah_kolom_tanah_di_desa();
		$hasil = $hasil && $this->tambah_kolom_tanah_di_desa();
		$hasil = $hasil && $this->tambah_kolom_tanah_kas_desa();

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
			$hasil = $hasil && $this->dbforge->add_column('log_keluarga', [
				'id_pend' => ['type' => 'INT', 'constraint' => 11, 'null' => TRUE],
				'updated_by' => ['type' => 'INT', 'constraint' => 11, 'null' => FALSE]
			]);
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

	// Hapus kolom tanah di desa
	protected function hapus_kolom_tanah_di_desa()
	{
		$hasil = true;
		if ($this->db->field_exists('hak_tanah', 'tanah_desa'))
		{
			$hasil = $hasil && $this->dbforge->drop_column('tanah_desa', 'hak_tanah');
			$hasil = $hasil && $this->dbforge->drop_column('tanah_desa', 'penggunaan_tanah');
		}
		return $hasil;
	}

	// Hapus kolom tanah kas desa
	protected function hapus_kolom_tanah_kas_desa()
	{
		$hasil = true;

		if ($this->db->field_exists('perolehan_tkd', 'tanah_kas_desa'))
		{
			$hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'perolehan_tkd');
			$hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'jenis_tkd');
			$hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'patok');
			$hasil = $hasil && $this->dbforge->drop_column('tanah_kas_desa', 'papan_nama');
		}

		return $hasil;
	}

	// Ubah kolom tanah desa
	protected function ubah_kolom_tanah_di_desa()
	{
		$hasil = true;
		$fields = [
			'lain' => ['type' => 'int', 'constraint' => 11],			
		];
		$hasil = $hasil && $this->dbforge->modify_column('tanah_desa', $fields);
		return $hasil;
	}

	// Tambah kolom tanah di desa
	protected function tambah_kolom_tanah_di_desa()
	{
		$hasil = true;
		if ( ! $this->db->field_exists('hak_milik', 'tanah_desa'))
		{
			$hasil = $hasil && $this->dbforge->add_column('tanah_desa', [
				'jenis_pemilik' => ['type' => 'TEXT','after' => 'id_penduduk'],
				'hak_milik' => ['type' => 'INT','constraint' => 11, 'after' => 'luas'],
				'hak_guna_bangunan' => ['type' => 'INT','constraint' => 11,'after' => 'hak_milik'],
				'hak_pakai' => ['type' => 'INT','constraint' => 11,'after' => 'hak_guna_bangunan'],
				'hak_guna_usaha' => ['type' => 'INT','constraint' => 11,'after' => 'hak_pakai'],
				'hak_pengelolaan' => ['type' => 'INT','constraint' => 11,'after' => 'hak_guna_usaha'],
				'hak_milik_adat' => ['type' => 'INT','constraint' => 11,'after' => 'hak_pengelolaan'],
				'hak_verponding' => ['type' => 'INT','constraint' => 11,'after' => 'hak_milik_adat'],
				'tanah_negara' => ['type' => 'INT','constraint' => 11,'after' => 'hak_verponding'],
				'perumahan' => ['type' => 'INT','constraint' => 11,'after' => 'tanah_negara'],
				'perdagangan_jasa' => ['type' => 'INT','constraint' => 11,'after' => 'perumahan'],
				'perkantoran' => ['type' => 'INT','constraint' => 11,'after' => 'perdagangan_jasa'],
				'industri' => ['type' => 'INT','constraint' => 11,'after' => 'perkantoran'],
				'fasilitas_umum' => ['type' => 'INT','constraint' => 11,'after' => 'industri'],
				'sawah' => ['type' => 'INT','constraint' => 11,'after' => 'fasilitas_umum'],
				'tegalan' => ['type' => 'INT','constraint' => 11,'after' => 'sawah'],
				'perkebunan' => ['type' => 'INT','constraint' => 11,'after' => 'tegalan'],
				'peternakan_perikanan' => ['type' => 'INT','constraint' => 11,'after' => 'perkebunan'],
				'hutan_belukar' => ['type' => 'INT','constraint' => 11,'after' => 'peternakan_perikanan'],
				'hutan_lebat_lindung' => ['type' => 'INT','constraint' => 11,'after' => 'hutan_belukar'],
				'tanah_kosong' => ['type' => 'INT','constraint' => 11,'after' => 'hutan_lebat_lindung'],
			]);			
		}

		return $hasil;
	}

	// Tambah kolom tanah kas desa
	protected function tambah_kolom_tanah_kas_desa()
	{
		$hasil = true;
		if ( ! $this->db->field_exists('asli_milik_desa', 'tanah_kas_desa'))
		{
			$hasil = $hasil && $this->dbforge->add_column('tanah_kas_desa', [				
				'asli_milik_desa' => ['type' => 'INT','constraint' => 11, 'after' => 'luas'],
				'pemerintah' => ['type' => 'INT','constraint' => 11,'after' => 'asli_milik_desa'],
				'provinsi' => ['type' => 'INT','constraint' => 11,'after' => 'pemerintah'],
				'kabupaten_kota' => ['type' => 'INT','constraint' => 11,'after' => 'provinsi'],
				'lain_lain' => ['type' => 'INT','constraint' => 11,'after' => 'kabupaten_kota'],
				'sawah' => ['type' => 'INT','constraint' => 11,'after' => 'lain_lain'],
				'tegal' => ['type' => 'INT','constraint' => 11,'after' => 'sawah'],
				'kebun' => ['type' => 'INT','constraint' => 11,'after' => 'tegal'],
				'tambak_kolam' => ['type' => 'INT','constraint' => 11,'after' => 'kebun'],
				'tanah_kering_darat' => ['type' => 'INT','constraint' => 11,'after' => 'tambak_kolam'],
				'ada_patok' => ['type' => 'INT','constraint' => 11,'after' => 'tanah_kering_darat'],
				'tidak_ada_patok' => ['type' => 'INT','constraint' => 11,'after' => 'ada_patok'],
				'ada_papan_nama' => ['type' => 'INT','constraint' => 11,'after' => 'tidak_ada_patok'],
				'tidak_ada_papan_nama' => ['type' => 'INT','constraint' => 11,'after' => 'ada_papan_nama'],				
			]);
		}

		return $hasil;
	}

}