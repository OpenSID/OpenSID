<?php
class Migrasi_2005_ke_2006 extends CI_model {

	public function up()
	{
		$this->grup_akses_covid19();

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
	}

	private function grup_akses_covid19()
	{
		// Menambahkan menu 'Group / Hak Akses' covid19 table 'user_grup'
		$data[] = array(
			'id'=>'5',
			'nama' => 'Satgas Covid-19',
		);

		foreach ($data as $modul)
		{
			$sql = $this->db->insert_string('user_grup', $modul);
			$sql .= " ON DUPLICATE KEY UPDATE
			id = VALUES(id),
			nama = VALUES(nama)";
			$this->db->query($sql);
		}
	}
	
}
