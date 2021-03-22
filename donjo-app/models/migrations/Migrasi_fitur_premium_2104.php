<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2104.php
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

class Migrasi_fitur_premium_2104 extends MY_model {

	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		// Hapus id_peristiwa = 1 lama di log_keluarga karena pengertiannya sudah tidak konsisten dengan penggunaan yg baru. Sekarang hanya terbatas pada keluarga baru yg dibentuk dari penduduk yg sudah ada.

		$hasil =& $this->db
			->where('id_peristiwa', 1)
			->where("date(tgl_peristiwa) < '2021-03-04'")
			->delete('log_keluarga');

		// Buat tabel url shortener
		$hasil =& $this->buat_tabel_url_shortener($hasil);
		// Buat tabel url statistik
		$hasil =& $this->buat_tabel_url_statistik($hasil);
		// Tambah field qr_code pada tabel tweb_surat_format
		$hasil =& $this->field_qr_code($hasil);
		// Ubah field tag_id_card menjadi index dan unique
		$hasil =& $this->ubah_tag_id_card_unique_index($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	// Buat tabel url shortener
	protected function buat_tabel_url_shortener($hasil)
	{
		$this->dbforge->add_field([
			'id'			=> ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'url'			=> ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
			'alias'		=> ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
			'created'	=> ['type' => 'datetime', 'null' => false],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('alias');
		$hasil =& $this->dbforge->create_table('urls', true);
		return $hasil;
	}

	// Buat tabel url statistik
	protected function buat_tabel_url_statistik($hasil)
	{
		$this->dbforge->add_field([
			'id'			=> ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'url_id'	=> ['type' => 'INT', 'null' => false],
			'created'	=> ['type' => 'datetime', 'null' => false],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('url_id');
		$hasil =& $this->dbforge->create_table('statistics', true);
		return $hasil;
	}

	// Tambah field qr_code pada tabel tweb_surat_format
	protected function field_qr_code($hasil)
	{
		if ( ! $this->db->field_exists('qr_code', 'tweb_surat_format'))
		{
			$fields = [
				'qr_code' => [
					'type' => 'TINYINT',
					'constraint' => 1,
					'null' => FALSE,
					'default' => 0,
				],
			];

			$hasil =& $this->dbforge->add_column('tweb_surat_format', $fields);
		}
		return $hasil;
	}

	// Indeksasi field tag_id_card
	protected function ubah_tag_id_card_unique_index($hasil)
	{
		$hasil =& $this->db
			->set('tag_id_card', NULL)
			->where('tag_id_card', '')
			->update('tweb_penduduk');

		$hasil =& $this->tambah_indeks('tweb_penduduk', 'tag_id_card');
		return $hasil;
	}

}
