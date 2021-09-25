<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2109_ke_2110.php
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
class Migrasi_2110_ke_2111 extends MY_model
{
	public function up()
	{
		$hasil=true;
		$this->create_table_harimerah();
		log_message("info","create table done");
		$this->add_def_harimerah();
		log_message("info","add data table done");
		$this->add_menu_harimerah();
		log_message("info","add menu done");
		$this->fix_pamong();
		log_message("info","add menu done");
		$this->create_table_hadir();
		return $hasil;
	}
	
	protected function create_table_hadir()
	{
		
		if($hasil)
		{
			$sql="ALTER TABLE `hadir_pamong_hari` 
			CHANGE `pamong_info` `pamong_info` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, 
			CHANGE `hadir_logs` `hadir_logs` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, CHANGE `lapor_logs` `lapor_logs` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;";
			$this->db->query($sql);
			$sql="ALTER TABLE `hadir_pamong_hari` ADD FULLTEXT  (`pamong_info`),
			ADD FULLTEXT  (`lapor_logs`); ";
			$this->db->query($sql); 
			$sql="ALTER TABLE `tmp_opensid`.`hadir_pamong_hari` ADD UNIQUE `pamong_tanggal` (`pamong_id`, `tanggal`)  ";
			$this->db->query($sql); 
		}
	}
	
	protected function fix_pamong()
	{	
		$sql="insert ignore into tweb_penduduk_mandiri(pin,tanggal_buat,id_pend) select '11948479d5a1007cc6fdb1f652a86abb' pin, now(), id from tweb_penduduk;";
		//$this->db->query($sql);
		$sql="ALTER TABLE `tweb_desa_pamong` ADD INDEX  (`pamong_nik`),ADD INDEX  (`id_pend`); ";
		$this->db->query($sql); 
		//not approve
		$sql="ALTER TABLE `tweb_desa_pamong` ADD INDEX  (`pamong_nama`)  ; ";
		$this->db->query($sql); 
		//not approve 
		$sql="ALTER TABLE `tweb_desa_pamong`
		ADD `pamong_pin` char(32)  CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL AFTER `pamong_nama`,
		ADD `tag_id_card` char(16)  CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL AFTER `pamong_nama`, ADD INDEX (`tag_id_card`); ";
		$this->db->query($sql); 
		//=========Penduduk
		$sql="ALTER TABLE `tweb_penduduk` ADD INDEX  nik_nama(`nik`,`nama`)  ; ";
		$this->db->query($sql); 
		//not approve
		$sql="ALTER TABLE `tweb_penduduk_mandiri` CHANGE `id_pend` `id_pend` INT(11) NOT NULL; ";
		//$this->db->query($sql);
		//not approve
		return ;
	}
	
	protected function add_menu_harimerah()
	{
		$data = array(
				'id' => 320,
				'modul' => 'Kehadiran',
				'url' => 'kehadiran/clear',
				'aktif' => 1,
				'ikon' => 'fa-gear',
				'urut' => 15,
				'level' => 1,
				'hidden' => 0,
				'ikon_kecil' => 'fa-gear',
				'parent' => 0
				);
		$sql = $this->db->insert_string('setting_modul', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				modul = VALUES(modul),
				aktif = VALUES(aktif),
				ikon = VALUES(ikon),
				urut = VALUES(urut),
				level = VALUES(level),
				hidden = VALUES(hidden),
				ikon_kecil = VALUES(ikon_kecil),
				parent = VALUES(parent)
				";
		$this->db->query($sql);
		$data = array(
				'id' => 321,
				'modul' => 'Tanggal Merah',
				'url' => 'set_hari',
				'aktif' => 1,
				'ikon' => 'fa-gear',
				'urut' => 1,
				'level' => 1,
				'hidden' => 0,
				'ikon_kecil' => 'fa-gear',
				'parent' => 320
				);
		$sql = $this->db->insert_string('setting_modul', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				modul = VALUES(modul),
				aktif = VALUES(aktif),
				ikon = VALUES(ikon),
				urut = VALUES(urut),
				level = VALUES(level),
				hidden = VALUES(hidden),
				ikon_kecil = VALUES(ikon_kecil),
				parent = VALUES(parent)
				";
		$this->db->query($sql);
		
		$data = array(
				'id' => 322,
				'modul' => 'Rekap Kehadiran',
				'url' => 'kehadiran_rekap',
				'aktif' => 1,
				'ikon' => 'fa-gear',
				'urut' => 2,
				'level' => 1,
				'hidden' => 0,
				'ikon_kecil' => 'fa-gear',
				'parent' => 320
				);
		$sql = $this->db->insert_string('setting_modul', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				modul = VALUES(modul),
				aktif = VALUES(aktif),
				ikon = VALUES(ikon),
				urut = VALUES(urut),
				level = VALUES(level),
				hidden = VALUES(hidden),
				ikon_kecil = VALUES(ikon_kecil),
				parent = VALUES(parent)
				";
		$this->db->query($sql);
		
		$data = array(
				'id' => 323,
				'modul' => 'Rekap Laporan',
				'url' => 'kehadiran_lapor',
				'aktif' => 1,
				'ikon' => 'fa-gear',
				'urut' => 2,
				'level' => 1,
				'hidden' => 0,
				'ikon_kecil' => 'fa-gear',
				'parent' => 320
				);
		$sql = $this->db->insert_string('setting_modul', $data);
		$sql .= " ON DUPLICATE KEY UPDATE
				modul = VALUES(modul),
				aktif = VALUES(aktif),
				ikon = VALUES(ikon),
				urut = VALUES(urut),
				level = VALUES(level),
				hidden = VALUES(hidden),
				ikon_kecil = VALUES(ikon_kecil),
				parent = VALUES(parent)
				";
		$this->db->query($sql);
		
	}
	
	protected function create_table_harimerah($hasil=1)
	{
		$this->dbforge->add_field([
			'id'                 => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'tgl_merah'          => ['type' => 'date','unique' => TRUE],
			'status'        	 => ['type' => 'INT', 'default'=>1],
			'detail'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], 
			'created_at'         => ['type' => 'datetime', 'null' => true],
			'updated_at'         => ['type' => 'datetime', 'null' => true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('status');
		$hasil =& $this->dbforge->create_table('setting_harimerah', true);
		if($hasil)
		{
			$sql="ALTER TABLE `setting_harimerah` 
			CHANGE `updated_at` `updated_at` TIMESTAMP 
			on update CURRENT_TIMESTAMP NULL DEFAULT NULL; ";
			$this->db->query($sql);
			$sql="ALTER TABLE `setting_harimerah` 
			CHANGE `created_at` `created_at` TIMESTAMP 
			NULL DEFAULT CURRENT_TIMESTAMP; ";
			$this->db->query($sql);
				
		}
		
		return $hasil;
	}
	
	protected function add_def_harimerah()
	{
		log_message("info","start add tanggal:".date("Ymd H:i:s"));
		$tahun0=strtotime("2000-01-01");
		$tahun1=strtotime("+10 year");
		$hari=3600*24;
		for($i=$tahun0;$i<=$tahun1;$i+=$hari)
		{
			if(date('N',$i)==5) //hari jum'at
			{
				$params=['tgl_merah'=>date("Y-m-d",$i), 'status'=>0];
				//$this->db->insert('setting_harimerah', $param);
				$insert_query = $this->db->insert_string('setting_harimerah', $params);
				$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
				$this->db->query($insert_query);
			}
			
			if(date('N',$i)==6||date('N',$i)==7)
			{
				$params=['tgl_merah'=>date("Y-m-d",$i), 'status'=>1];
				//$this->db->insert('setting_harimerah', $param);
				$insert_query = $this->db->insert_string('setting_harimerah', $params);
				$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
				$this->db->query($insert_query);
			}
			
			if((date("md",$i)=="0101")) //tahun baru
			{
				$params=['tgl_merah'=>date("Y-m-d",$i), 'status'=>9,'detail'=>'Tahun Baru'];
				//$this->db->insert('setting_harimerah', $param);
				$insert_query = $this->db->insert_string('setting_harimerah', $params);
				$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO', $insert_query);
				$insert_query.="on duplicate key update status=9, detail='Tahun Baru'";
				$this->db->query($insert_query);
			}
			
		}
		$fixsql="optimize setting_harimerah";
		$this->db->query($fixsql);
		log_message("info","end add tanggal:".date("Ymd H:i:s"));
	}
	
}
