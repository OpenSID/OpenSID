<?php

class Migrasi_2007_ke_2008 extends CI_model {

	public function up()
	{
		if (!$this->db->table_exists('notifikasi') )
		{
			$query = "
			CREATE TABLE `notifikasi` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`judul` varchar(100) NOT NULL,
				`jenis` varchar(50) NOT NULL,
				`isi` text NOT NULL,
				`server` varchar(20) NOT NULL,
				`tgl_berikutnya` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
				`updated_by` int(11) NOT NULL,
				`frekuensi` smallint(6) NOT NULL,
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);

			$insert = array(
				'judul' => 'persetujuan penggunaan',
				'jenis' => 'pengumuman',
				'isi' => 
						'<ol>
							<li>Pengguna telah membaca dan menyetujui <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">Lisensi GPL V3</a></li>
							<li>Penggunaan OpenSID adalah berdasarkan&nbsp;apa adanya&nbsp;dan adalah tanggung jawab pengguna untuk menjamin keamanan data desa</li>
							<li>Pengguna untuk tidak sekali-kali menggunakan password default, dan untuk rajin mengubah semua password pengguna, termasuk PIN pengguna Layanan Mandiri</li>
							<li>Pengguna untuk rutin melakukan update data desa, termasuk folder&nbsp;desa.</li>
							<li>Pengguna mengetahui dan menyetujui adanya tracker</li>
						</ol>',
				'server' => 'client',
				'tgl_berikutnya' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				'updated_by' => 0,
				'frekuensi' => 90
				
			);
			$this->db->insert('notifikasi', $insert);
		}
	}

}
