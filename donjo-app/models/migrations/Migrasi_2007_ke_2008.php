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
		// Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE point MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE point MODIFY COLUMN simbol varchar(50) DEFAULT NULL");
		$this->db->query("ALTER TABLE line MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE line MODIFY COLUMN simbol varchar(50) DEFAULT NULL");
		//Hapus sosmed google-plus
		$this->db->delete('media_sosial', ['id' => '3']);
		// Buat table notifikasi
		$this->add_notifikasi();
	}

	private function add_notifikasi()
	{
		if (!$this->db->table_exists('notifikasi') )
		{
			$query = "
			CREATE TABLE `notifikasi` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`kode` varchar(100) NOT NULL,
				`judul` varchar(100) NOT NULL,
				`jenis` varchar(50) NOT NULL,
				`isi` text NOT NULL,
				`server` varchar(20) NOT NULL,
				`tgl_berikutnya` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
				`updated_by` int(11) NOT NULL,
				`frekuensi` smallint(6) NOT NULL,
				`aksi` varchar(100) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY (kode)
			)";
			$this->db->query($query);
		}

		$list_data = [

			// Persetujuan Pengguna
			[
				'kode' => 'persetujuan_penggunaan',
				'judul' => '<i class="fa fa-file-text-o text-black"></i> &nbsp;Persetujuan Penggunaan OpenSID',
				'jenis' => 'pengumuman',
				'isi' =>
					'<p><b>Persetujuan Penggunaan OpenSID:</b>
					<ol>
						<li>Pengguna telah membaca dan menyetujui <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">Lisensi GPL V3</a>.</li>
						<li>OpenSID gratis dan disediakan "SEBAGAIMANA ADANYA", di mana segala tanggung jawab termasuk keamanan data desa ada pada pengguna.</li>
						<li>Pengguna paham bahwa setiap ubahan OpenSID juga berlisensi GPL V3 yang tidak dapat dimusnahkan, dan aplikasi ubahan itu juga sumber terbuka yang bebas disebarkan oleh pihak yang menerima.</li>
						<li>Pengguna mengetahui, paham dan menyetujui bahwa OpenSID akan mengirim data penggunaan ke server OpenDesa secara berkala untuk tujuan menyempurnakan OpenSID, dengan pengertian bahwa data yang dikirim sama sekali tidak berisi data identitas penduduk atau data sensitif desa lainnya.</li>
					</ol></p>
					<b>Apakah anda dan desa anda setuju dengan ketentuan di atas?</b>',
				'server' => 'client',
				'tgl_berikutnya' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				'updated_by' => 0,
				'frekuensi' => 90,
				'aksi' => 'notif/update_pengumuman,siteman'
			],

			// Tracking Off
			[
				'kode' => 'tracking_off',
				'judul' => '<i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Peringatan Tracking Off',
				'jenis' => 'peringatan',
				'isi' =>
					'<p>Kami mendeteksi bahwa anda telah mematikan tracking website. Hal ini akan mempengaruhi website anda dalam menerima informasi yang kami kirimkan melalui server OpenSID.</p>
					<br><b>Hidupkan kembali tracking untuk mendapatkan informasi dari server OpenSID?</b>',
				'server' => 'client',
				'tgl_berikutnya' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				'updated_by' => 0,
				'frekuensi' => 90,
				'aksi' => 'setting/tracking,setting/notif_tracking'
			]
		];

		foreach ($list_data as $data)
		{
			$sql = $this->db->insert_string('notifikasi', $data);
			$sql .= " ON DUPLICATE KEY UPDATE judul = VALUES(judul), jenis = VALUES(jenis), isi = VALUES(isi), server = VALUES(server), frekuensi = VALUES(frekuensi), aksi = VALUES(aksi)";
			$this->db->query($sql);
		}
	}

}
