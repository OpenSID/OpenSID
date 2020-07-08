<?php

class Migrasi_2007_ke_2008 extends CI_model {

	public function up()
	{
		// Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE point MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE point MODIFY COLUMN simbol varchar(50) DEFAULT NULL");
		$this->db->query("ALTER TABLE line MODIFY COLUMN tipe INT(4) NULL DEFAULT 0");
		$this->db->query("ALTER TABLE line MODIFY COLUMN simbol varchar(50) DEFAULT NULL");
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
				PRIMARY KEY (`id`),
				UNIQUE KEY (kode)
			)";
			$this->db->query($query);
		}

		$insert = [
			'kode' => 'persetujuan_penggunaan',
			'judul' => 'Persetujuan Penggunaan OpenSID',
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
			'frekuensi' => 90
		];

		$sql = $this->db->insert_string('notifikasi', $insert) . " ON DUPLICATE KEY UPDATE judul = VALUES(judul), jenis = VALUES(jenis), isi = VALUES(isi), server = VALUES(server), frekuensi = VALUES(frekuensi)";
		$this->db->query($sql);
	}

}
