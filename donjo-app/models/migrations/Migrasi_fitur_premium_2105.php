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

		status_sukses($hasil);
		return $hasil;
	}

	protected function create_table_tanah_desa($hasil)
	{
		$this->dbforge->add_field([
			'id'                => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],			
			'nama_pemilik_asal'	=> ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false],
			'letter_c'          => ['type' => 'TEXT', 'null' => false],
			'persil'         	=> ['type' => 'TEXT', 'null' => false],
			'nomor_sertif'      => ['type' => 'TEXT', 'null' => false],
			'tanggal_sertif'   	=> ['type' => 'DATE', 'null' => false, 'default'=> 'curent_timestamp'],
			'hak_tanah'         => ['type' => 'TEXT', 'null' => false],
			'penggunaan_tanah'	=> ['type' => 'TEXT', 'null' => false],
			'luas'     			=> ['type' => 'INT', 'constraint' => 10, 'null' => false],
			'lain' 				=> ['type' => 'TEXT', 'null' => false],
			'keterangan' 		=> ['type' => 'TEXT', 'null' => false],
			'created_at'        => ['type' => 'TIMESTAMP', 'null' => false, 'default'=> 'curent_timestamp'],
			'created_by'        => ['type' => 'INT', 'constraint' => 10, 'null' => false],
			'updated_at'        => ['type' => 'TIMESTAMP', 'null' => false, 'default'=> 'curent_timestamp'],
			'updated_by'        => ['type' => 'INT', 'constraint' => 10, 'null' => false],
			'visible'           => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 1],
		]);

		$this->dbforge->add_key('id', true);
		$hasil =& $this->dbforge->create_table('tanah_desa', true);
		return $hasil;
	}

	protected function create_table_tanah_kas_desa($hasil)
	{
		$this->dbforge->add_field([
			'id'                => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],			
			'nama_pemilik_asal'	=> ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false],
			'letter_c'          => ['type' => 'TEXT', 'null' => false],
			'persil'         	=> ['type' => 'TEXT', 'null' => false],
			'kelas' 		    => ['type' => 'TEXT', 'null' => false],
			'luas'     			=> ['type' => 'INT', 'constraint' => 10, 'null' => false],
			'perolehan_tkd'     => ['type' => 'TEXT', 'null' => false],
			'jenis_tkd'         => ['type' => 'TEXT', 'null' => false],
			'patok'		        => ['type' => 'TEXT', 'null' => false],
			'papan_nama'		=> ['type' => 'TEXT', 'null' => false],
			'tanggal_perolehan' => ['type' => 'DATE', 'null' => false, 'default'=> 'curent_timestamp'],
			'lokasi'			=> ['type' => 'TEXT', 'null' => false],
			'peruntukan' 		=> ['type' => 'TEXT', 'null' => false],
			'keterangan' 		=> ['type' => 'TEXT', 'null' => false],
			'created_at'        => ['type' => 'TIMESTAMP', 'null' => false, 'default'=> 'curent_timestamp'],
			'created_by'        => ['type' => 'INT', 'constraint' => 10, 'null' => false],
			'updated_at'        => ['type' => 'TIMESTAMP', 'null' => false, 'default'=> 'curent_timestamp'],
			'updated_by'        => ['type' => 'INT', 'constraint' => 10, 'null' => false],
			'status'           	=> ['type' => 'TINYINT', 'constraint' => 2, 'default' => 0],
			'visible'           => ['type' => 'TINYINT', 'constraint' => 2, 'default' => 1],
		]);

		$this->dbforge->add_key('id', true);
		$hasil =& $this->dbforge->create_table('tanah_kas_desa', true);
		return $hasil;
	}

}
