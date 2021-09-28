<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2010.php
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

class Migrasi_fitur_premium_2010 extends MY_model {

	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;
		// Ubah judul setting ukuran lebar bagan
		$hasil =& $this->db->where('key', 'ukuran_lebar_bagan')
			->set('keterangan', 'Ukuran Lebar Bagan (800 / 1200 / 1400)')
			->set('kategori', 'conf_bagan')
			->update('setting_aplikasi');

		// Tambah kolom jabatan dan no_sk_jabatan di tabel kelompok_anggota
		if (!$this->db->field_exists('jabatan', 'kelompok_anggota'))
		{
			$fields = array(
				'jabatan' => array(
					'type' => 'VARCHAR',
					'constraint' => 50,
					'default' => 90
				),
				'no_sk_jabatan' => array(
					'type' => 'VARCHAR',
					'constraint' => 50,
					'null' => TRUE
				),
				'foto' => array(
					'type' => 'VARCHAR',
					'constraint' => 100,
					'null' => TRUE
				)
			);

			$this->dbforge->add_column('kelompok_anggota', $fields);
			// Sesuaikan jabatan ketua yg sudah ada
			$list_kelompok = $this->db->get('kelompok')->result_array();
			foreach ($list_kelompok as $kelompok)
			{
				$hasil =& $this->db
					->set('jabatan', 1)
					->where('id_kelompok', $kelompok['id'])
					->where('id_penduduk', $kelompok['id_ketua'])
					->update('kelompok_anggota');
			}
		}

		// Sesuaikan panjang keterangan kelompok menjadi 200
		$field = [
			'keterangan' => [
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null' => TRUE,
				'default' => NULL
			]
		];
		$hasil =& $this->dbforge->modify_column('kelompok', $field);

		// Tambah menu IDM
		$modul = array(
			'id' => '101',
			'modul' => 'Status ' . ucwords($this->setting->sebutan_desa),
			'url' => 'status_desa',
			'aktif' => '1',
			'ikon' => 'fa-dot-circle-o',
			'urut' => '4',
			'level' => '0',
			'parent' => '200',
			'hidden' => '0',
			'ikon_kecil' => ''
		);
		$hasil =& $this->tambah_modul($modul);

		// Tambah modul Lembaran Desa
		$modul = array(
			'id' => '311',
			'modul' => 'Buku Lembaran Dan Berita Desa',
			'url' => 'lembaran_desa/clear',
			'aktif' => '1',
			'ikon' => 'fa-files-o',
			'urut' => '0',
			'level' => '0',
			'parent' => '302',
			'hidden' => '0',
			'ikon_kecil' => ''
		);
		$hasil =& $this->tambah_modul($modul);

		return $hasil;
	}
}
