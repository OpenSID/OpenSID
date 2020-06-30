<?php

class Migrasi_2007_ke_2008 extends CI_model {

	public function up()
	{
		if (!$this->db->table_exists('notifikasi') )
		{
			$query = "
			CREATE TABLE `notifikasi` (
				`id` int(11) NOT NULL,
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
		}
	}

}
