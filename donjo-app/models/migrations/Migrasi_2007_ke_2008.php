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
		// Tambah perubahan database di sini

		// Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE point MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE point MODIFY COLUMN simbol varchar(50) DEFAULT NULL");
		$this->db->query("ALTER TABLE line MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE line MODIFY COLUMN simbol varchar(50) DEFAULT NULL");

		//Hapus sosmed google-plus
		$this->db->delete('media_sosial', ['id' => '3']);

		//Simbol Lokasi - Tambah kolom id dan attribute unique di tabel gis_simbol
		if (!$this->db->field_exists('id', 'gis_simbol'))
		$this->db->query("ALTER TABLE gis_simbol ADD id INT NOT NULL AUTO_INCREMENT KEY FIRST");
		$this->db->query("ALTER TABLE gis_simbol ADD UNIQUE(`simbol`)");

		//Simbol Lokasi - Tambah Beberapa Simbol Lokasi Pelayanan Umum, Instansi Pemerintah, TNI/Polri
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

}
