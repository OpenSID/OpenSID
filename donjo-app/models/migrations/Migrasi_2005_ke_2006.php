<?php
class Migrasi_2005_ke_2006 extends CI_model {

	public function up()
	{
		// Menu baru -FITUR PREMIUM-
		$this->buku_administrasi_desa();

		// Ubah nama kode status penduduk
		$this->db->where('id', 2)
			->update('tweb_penduduk_status', array('nama' => 'TIDAK TETAP'));

		//Ganti nama folder widget menjadi widgets
		rename('desa/widget', 'desa/widgets');
		rename('desa/upload/widget', 'desa/upload/widgets');
		// Arahkan semua widget statis ubahan desa ke folder desa/widgets
		$list_widgets = $this->db->where('jenis_widget', 2)->get('widget')->result_array();
		foreach ($list_widgets as $widgets) 
		{
			$ganti = str_replace('desa/widget', 'desa/widgets', $widgets['isi']); // Untuk versi 20.04-pasca ke atas
			$cek = explode('/', $ganti); // Untuk versi 20.04 ke bawah
			if ($cek[0] !== 'desa' AND $cek[1] === NULL)
			{ // agar migrasi bisa dijalankan berulang kali
				$this->db->where('id', $widgets['id'])->update('widget', array('isi' => 'desa/widgets/'.$widgets['isi']));
			}
		}
  	// Sesuaikan dengan sql_mode STRICT_TRANS_TABLES
		$this->db->query("ALTER TABLE outbox MODIFY COLUMN CreatorID text NULL");
	}

	private function buku_administrasi_desa()
	{
		// Menu parent Buku Administrasi Desa
		$menu[0] = array(
			'id'=>'301',
			'modul' => 'Buku Administrasi Desa',
			'url' => 'buku',
			'aktif' => '1',
			'ikon' => 'fa-paste',
			'urut' => '6',
			'level' => '2',
			'hidden' => '0',
			'ikon_kecil' => 'fa fa-paste',
			'parent' => 0
		);
		$menu[1] = array(
			'id'=>'302',
			'modul' => 'Administrasi Umum',
			'url' => 'buku/umum',
			'aktif' => '1',
			'ikon' => 'fa-bookmark',
			'urut' => '1',
			'level' => '2',
			'hidden' => '0',
			'ikon_kecil' => 'fa fa-bookmark',
			'parent' => 301
		);
		foreach ($menu as $modul) 
		{
			$sql = $this->db->insert_string('setting_modul', $modul);
			$sql .= " ON DUPLICATE KEY UPDATE
			id = VALUES(id),
			modul = VALUES(modul),
			url = VALUES(url),
			aktif = VALUES(aktif),
			ikon = VALUES(ikon),
			urut = VALUES(urut),
			level = VALUES(level),
			hidden = VALUES(hidden),
			ikon_kecil = VALUES(ikon_kecil),
			parent = VALUES(parent)";
			$this->db->query($sql);
		}
		// Menu parent Buku Administrasi Desa. END
		
	}

}
