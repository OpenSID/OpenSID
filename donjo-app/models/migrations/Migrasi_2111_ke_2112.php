<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2111_ke_2112.php
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */
class Migrasi_2111_ke_2112 extends MY_model
{
	public function up()
	{
		$hasil=true;
		$this->fix_pamong();
		log_message("info","fix pamong done");		
		$this->create_table_hadir();
	}
	
		
	protected function create_table_hadir()
	{
		$this->dbforge->add_field([
			'id'                 => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'tanggal'          => ['type' => 'date',],
			'pamong_id'        	 => ['type' => 'INT', 'default'=>1],
			'created_at'         => ['type' => 'datetime', 'null' => true],
			'updated_at'         => ['type' => 'datetime', 'null' => true],
			'waktu_masuk'		=> ['type'=> 'datetime', 'null'=> true],
			'waktu_keluar'		=> ['type'=> 'datetime', 'null'=> true],
			'pamong_info'		=> ['type'=> 'text', 'null'=> true],
			'hadir_logs'		=> ['type'=> 'text', 'null'=> true],
			'lapor_logs'		=> ['type'=> 'text', 'null'=> true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('status');
		$hasil =& $this->dbforge->create_table('hadir_pamong_hari', true);
		
		if ($hasil)
		{
			$sql = "ALTER TABLE `hadir_pamong_hari` 
			CHANGE `pamong_info` `pamong_info` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, 
			CHANGE `hadir_logs` `hadir_logs` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `lapor_logs` `lapor_logs` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;";
			$this->db->query($sql);
			$sql = "ALTER TABLE `hadir_pamong_hari` ADD FULLTEXT  (`pamong_info`),
			ADD FULLTEXT  (`lapor_logs`); ";
			$this->db->query($sql); 
			$sql = "ALTER TABLE `tmp_opensid`.`hadir_pamong_hari` ADD UNIQUE `pamong_tanggal` (`pamong_id`, `tanggal`)  ";
			$this->db->query($sql); 
		}
		
	}
	
	protected function fix_pamong()
	{	
		$sql = "insert ignore into tweb_penduduk_mandiri(pin,tanggal_buat,id_pend) select '11948479d5a1007cc6fdb1f652a86abb' pin, now(), id from tweb_penduduk;";

		$sql = "ALTER TABLE `tweb_desa_pamong` ADD INDEX  (`pamong_nik`),ADD INDEX  (`id_pend`); ";
		//$this->db->query($sql); 
		//not approve
		$sql = "ALTER TABLE `tweb_desa_pamong` ADD INDEX  (`pamong_nama`)  ; ";
		//$this->db->query($sql); 
		//not approve
		$sql = "ALTER TABLE `tweb_desa_pamong`
		ADD `pamong_pin` char(32)  CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL AFTER `pamong_nama`,
		ADD `tag_id_card` char(16)  CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL AFTER `pamong_nama`, ADD INDEX (`tag_id_card`); ";
		$this->db->query($sql); 
		//=========Penduduk
		$sql = "ALTER TABLE `tweb_penduduk` ADD INDEX  nik_nama(`nik`,`nama`)  ; ";
		//$this->db->query($sql); 
		//not approve
		$sql = "ALTER TABLE `tweb_penduduk_mandiri` CHANGE `id_pend` `id_pend` INT(11) NOT NULL; ";
		//$this->db->query($sql);
		//not approve
		return ;
	}
	
	
}