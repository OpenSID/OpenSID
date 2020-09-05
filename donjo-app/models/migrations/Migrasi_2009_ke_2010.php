<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2009_ke_2010.php
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

class Migrasi_2009_ke_2010 extends MY_model {

	public function up()
	{
		$hasil = true;
		// Sesuaikan panjang judul dokumen menjadi 200
		$this->db->query("ALTER TABLE `dokumen` CHANGE COLUMN `nama` `nama` VARCHAR(200) NOT NULL");
		// Bolehkan C-Desa berbeda berisi nama kepemilikan sama
		$hasil =& $this->hapus_indeks('cdesa', 'nama_kepemilikan');
		// Key di setting_aplikasi seharusnya unik
		$hasil =& $this->tambah_indeks('setting_aplikasi', 'key');
		$hasil =& $this->db->query("INSERT INTO setting_aplikasi (`key`,value,keterangan) VALUES ('sebutan_nip_desa','NIPD','Pengganti sebutan label niap/nipd')
							ON DUPLICATE KEY UPDATE
							value = VALUES(value),
							keterangan = VALUES(keterangan)
							");
		$hasil =& $this->db->query('ALTER TABLE tweb_desa_pamong MODIFY COLUMN pamong_niap varchar(25) default 0');

		$hasil =& $this->add_log_siteman();

		status_sukses($hasil);
	}

	private function add_log_siteman()
	{
		if (!$this->db->table_exists('log_siteman'))
		{
			$query = "
				CREATE TABLE `log_siteman`(
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`ip_address` varchar(45),
					`counter` int(11) DEFAULT 1,
					`created_at` DATETIME,
					`updated_at` DATETIME,
					PRIMARY KEY (id)
				)";
			$this->db->query($query);
		}
	}

}
