<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2106.php
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
class Migrasi_fitur_premium_2106 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		$hasil = $hasil && $this->migrasi_2021050551($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	protected function migrasi_2021050551($hasil)
	{
		$hasil = $hasil && $this->create_table_ref_asal_tanah_kas($hasil);
		$hasil = $hasil && $this->create_table_ref_peruntukan_tanah_kas($hasil);
		$hasil = $hasil && $this->add_value_ref_asal_tanah_kas($hasil);
		$hasil = $hasil && $this->add_value_ref_peruntukan_tanah_kas($hasil);
		return $hasil;
	}

	protected function create_table_ref_asal_tanah_kas($hasil)
	{
		$this->dbforge->add_field([
			'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'nama' => ['type' => 'TEXT'],
		]);

		$this->dbforge->add_key('id', true);
		$hasil =& $this->dbforge->create_table('ref_asal_tanah_kas', true);
		return $hasil;
	}

	protected function create_table_ref_peruntukan_tanah_kas($hasil)
	{
		$this->dbforge->add_field([
			'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'nama' => ['type' => 'TEXT'],
		]);

		$this->dbforge->add_key('id', true);
		$hasil =& $this->dbforge->create_table('ref_peruntukan_tanah_kas', true);
		return $hasil;
	}

	protected function add_value_ref_asal_tanah_kas($hasil)
	{
		$data = array(
			['id'=> 1, 'nama' => 'Jual Beli'],
			['id'=> 2, 'nama' => 'Hibah / Sumbangan'],
			['id'=> 3, 'nama' => 'Lain - lain'],
		);

		foreach ($data as $modul)
		{
			$sql = $this->db->insert_string('ref_asal_tanah_kas', $modul);
			$sql .= " ON DUPLICATE KEY UPDATE
					id = VALUES(id),
					nama = VALUES(nama)";
			$hasil = $hasil && $this->db->query($sql);
		}

		return $hasil;
	}

	protected function add_value_ref_peruntukan_tanah_kas($hasil)
	{
		$data = array(
			['id'=> 1, 'nama' => 'Sewa'],
			['id'=> 2, 'nama' => 'Pinjam Pakai'],
			['id'=> 3, 'nama' => 'Kerjasama Pemanfaatan'],
			['id'=> 4, 'nama' => 'Bangun Guna Serah atau Bangun Serah Guna'],
		);

		foreach ($data as $modul)
		{
			$sql = $this->db->insert_string('ref_peruntukan_tanah_kas', $modul);
			$sql .= " ON DUPLICATE KEY UPDATE
					id = VALUES(id),
					nama = VALUES(nama)";
			$hasil = $hasil && $this->db->query($sql);
		}

		return $hasil;
	}
}