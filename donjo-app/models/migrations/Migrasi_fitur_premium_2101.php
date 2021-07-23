<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2101.php
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

class Migrasi_fitur_premium_2101 extends MY_model {

	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		// Tambahkan key sebutan_nip_desa
		$hasil =& $this->db->query("INSERT INTO setting_aplikasi (`key`, value, keterangan) VALUES ('sebutan_nip_desa', 'NIPD', 'Pengganti sebutan label niap/nipd')
			ON DUPLICATE KEY UPDATE value = VALUES(value), keterangan = VALUES(keterangan)");

		$list_setting =
			[
				[
					'key' => 'api_opendk_server',
					'value' => '',
					'keterangan' => 'Alamat Server OpenDK (contoh: https://demo.opendk.my.id)',
				],
				[
					'key' => 'api_opendk_key',
					'value' => '',
					'keterangan' => 'OpenDK API Key untuk Sinkronisasi Data',
				],
				[
					'key' => 'api_opendk_user',
					'value' => '',
					'keterangan' => 'Email Login Pengguna OpenDK',
				],
				[
					'key' => 'api_opendk_password',
					'value' => '',
					'keterangan' => 'Password Login Pengguna OpenDK',
				],
			];
		foreach ($list_setting as $setting)
		{
			$hasil =& $this->tambah_setting($setting);
		}

		// setting_aplikasi.valud diperpanjang
		$field = [
			'value' => [
				'type' => 'VARCHAR',
				'constraint' => 500,
				'null' => TRUE,
				'default' => NULL
			]
		];
		$hasil =& $this->dbforge->modify_column('setting_aplikasi', $field);

		return $hasil;
	}
}
