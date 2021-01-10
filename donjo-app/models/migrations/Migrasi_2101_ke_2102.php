<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2101_ke_2102.php
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

class Migrasi_2101_ke_2102 extends MY_model {

	public function up()
	{
		$hasil = true;

		$this->penduduk_updates();

		// Migrasi fitur premium
		$daftar_migrasi_premium = ['2010', '2011', '2012', '2101', '2102'];
		foreach ($daftar_migrasi_premium as $migrasi)
		{
			$migrasi_premium = 'migrasi_fitur_premium_'.$migrasi;
			$file_migrasi = 'migrations/'.$migrasi_premium;
				$this->load->model($file_migrasi);
				$hasil =& $this->$migrasi_premium->up();
		}

		status_sukses($hasil);
		return $hasil;
	}

	// Updates for issues #2777
	public function penduduk_updates(){
		// Menambahkan Tabel tweb_penduduk_bahasa yang digunakan untuk autofield pada pemilihan aset
		if (!$this->db->table_exists('tweb_penduduk_bahasa') )
		{
			// Membuat table tweb_penduduk_bahasa, attribut baru untuk kolom bahasa
			$query = "
			CREATE TABLE IF NOT EXISTS `tweb_penduduk_bahasa` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`nama` varchar(50) NOT NULL,
				`initial` varchar(10) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);

			// Insert data ke table tweb_penduduk_bahasa
			$query = "
			INSERT INTO tweb_penduduk_bahasa (`id`, `nama`, `initial`) VALUES
				(1, 'Latin', 'L'),
				(2, 'Daerah', 'D'),
				(3, 'Arab', 'A'),
				(4, 'Arab dan Latin', 'AL'),
				(5, 'Arab dan Daerah', 'AD'),
				(6, 'Arab, Latin dan Daerah', 'ALD')
			";
			$this->db->query($query);
		}

		// Menambahkan bahasa_id setelah column warganegara_id pada table tweb_penduduk, digunakan untuk define bahasa penduduk
		$this->db->query("ALTER TABLE tweb_penduduk ADD bahasa_id int(11) NULL AFTER warganegara_id");
	}
}
