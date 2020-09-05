<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk halaman siteman
 *
 * donjo-app/models/Log_siteman_model.php,
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
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
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

 class Log_siteman_model extends CI_Model 
 {
	 public static $tabel = 'log_siteman';

	 public function __construct() {
		 parent::__construct();
	 }

	 public function ambil_log_ip($ip_address)
	 {
		 $query = $this->db->where('ip_address', $ip_address)->get(self::$tabel);
		 if ($query->num_rows() === 1) 
		 {
			 return $query->row();
		 }
		 return NULL;
	 }

	 public function cek_blokir($ip_address)
	 {
		 $max_percobaan = MAX_PERCOBAAN_SITEMAN;
		 $durasi_blokir = DURASI_BLOKIR_SITEMAN;
		 $log_ip = $this->ambil_log_ip($ip_address);
		 if (is_object($log_ip) && $log_ip->counter >= $max_percobaan) 
		 {
				$percobaan_terakhir = strtotime($log_ip->updated_at);
				$masa_tenggang = time() - $percobaan_terakhir;

				if ($masa_tenggang >= $durasi_blokir) 
				{
					$this->reset_log_ip($ip_address);
					return false;
				}
				return true;
		 }
		 return false;
	 }

	 public function update_log_ip($ip_address) 
	 {
		 $log_ip = $this->ambil_log_ip($ip_address);
		 if ($log_ip) 
		 {
			 $update_entri = array(
				 'counter' => $log_ip->counter + 1,
				 'updated_at' => date('Y-m-d H:i:s')
			 );
			 $this->db->where('ip_address', $ip_address)->update(self::$tabel, $update_entri);
		 }
		 else 
		 {
			 $entri_baru = array(
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'ip_address' => $ip_address,
				'counter' => 1
			 );
			 $this->db->insert(self::$tabel, $entri_baru);
		 }
	 }

	 public function reset_log_ip($ip_address)
	 {
		 return $this->db->where('ip_address', $ip_address)->delete(self::$tabel);
	 }
 }
 