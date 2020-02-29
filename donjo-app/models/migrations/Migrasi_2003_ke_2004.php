<?php
class Migrasi_2003_ke_2004 extends CI_model {

	public function up()
	{
		// Update tabel menu yg sudah terisi agar tidak error
		// Untuk Jenis Link : Statistik Penduduk
		$this->db->where('link', 'statistik/0')->update('menu', array('link' => 'statistik/pendidikan-dalam-kk'));
		$this->db->where('link', 'statistik/1')->update('menu', array('link' => 'statistik/pekerjaan'));
		$this->db->where('link', 'statistik/2')->update('menu', array('link' => 'statistik/status-perkawinan'));
		$this->db->where('link', 'statistik/3')->update('menu', array('link' => 'statistik/agama'));
		$this->db->where('link', 'statistik/4')->update('menu', array('link' => 'statistik/jenis-kelamin'));
		$this->db->where('link', 'statistik/5')->update('menu', array('link' => 'statistik/warga-negara'));
		$this->db->where('link', 'statistik/6')->update('menu', array('link' => 'statistik/status'));
		$this->db->where('link', 'statistik/7')->update('menu', array('link' => 'statistik/golongan-darah'));
		$this->db->where('link', 'statistik/9')->update('menu', array('link' => 'statistik/cacat'));
		$this->db->where('link', 'statistik/10')->update('menu', array('link' => 'statistik/sakit-menahun'));
		$this->db->where('link', 'statistik/13')->update('menu', array('link' => 'statistik/umur'));
		$this->db->where('link', 'statistik/14')->update('menu', array('link' => 'statistik/pendidikan-sedang-ditempuh'));
		$this->db->where('link', 'statistik/15')->update('menu', array('link' => 'statistik/umur'));
		$this->db->where('link', 'statistik/16')->update('menu', array('link' => 'statistik/akseptor-kb'));
		$this->db->where('link', 'statistik/17')->update('menu', array('link' => 'statistik/akte-kelahiran'));
		$this->db->where('link', 'statistik/18')->update('menu', array('link' => 'statistik/kepemilikan-wajib-ktp'));
		$this->db->where('link', 'statistik/19')->update('menu', array('link' => 'statistik/jenis-asuransi'));
		$this->db->where('link', 'statistik/21')->update('menu', array('link' => 'statistik/klasifikasi-sosial'));
		$this->db->where('link', 'statistik/24')->update('menu', array('link' => 'statistik/penerima-bos'));
		
		// Untuk Jenis Link : artikel
		//ganti link dengan url artikel pada tabel menu menjadi artikel/slug (slug diambil dari tabel artikel)
		$list_menu = $this->db->where("link like '%artikel/%'")->get('menu')->result_array();
		foreach ($list_menu as $menu)
		{
			$id = str_replace("artikel/", "", $menu['link']);
			$artikel = $this->db->select('*, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri')->where('id', $id)->get('artikel')->row_array();
			$this->db->where('link', 'artikel/'.$artikel['id'])->update('menu', array('link' => 'artikel/'.buat_slug($artikel)));			
		}

		//ganti link pada tabel menu
		$this->db->where('link', 'dpt')->update('menu', array('link' => 'calon-pemilih'));
		$this->db->where('link', 'peraturan_desa')->update('menu', array('link' => 'dokumen/produk-hukum'));
		$this->db->where('link', 'informasi_publik')->update('menu', array('link' => 'dokumen/informasi-publik'));

		//program bantuan
		$list_program = $this->db->where("link like '%statistik/50%'")->get('menu')->result_array();
		foreach ($list_program as $program)
		{
			$id = str_replace("statistik/50", "", $program['link']);
			
			$nama_program = $this->db->where('id', $id)->get('program')->row_array();
			$this->db->where('link', 'statistik/50'.$id)->update('menu', array('link' => 'statistik/'.lowershyphen($nama_program['nama'])));			
		}
	}
}
