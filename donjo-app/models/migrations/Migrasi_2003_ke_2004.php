<?php
class Migrasi_2003_ke_2004 extends CI_model {

	public function up()
	{
		// Ubah panjang jalan dari KM menjadi M.
		// Untuk mencegah diubah berkali-kali buat asumsi panjang jalan sebelum konversi maksimal 100 KM dan sesudah menggunakan M, minimal 100 M.
		$this->db->where('panjang < 100')
			->set('panjang', 'panjang * 1000', false)
			->update('inventaris_jalan');
  	// Urut tabel gambar_gallery
  	if (!$this->db->field_exists('urut', 'gambar_gallery'))
  	{
			// Tambah kolom
			$fields = array();
			$fields['urut'] = array(
					'type' => 'int',
					'constraint' => 5
			);
			$this->dbforge->add_column('gambar_gallery', $fields);
  	}
    	//ketika update akan ada folder surat dan template-surat
		$folder = "surat";
		//perubahan untuk sistem, tdk menggunakan fungsi rename krn hanya berlaku jika tdk ada penambahan folder baru saat update(template-surat)
		$this->load->helper("file");
		//1. Hapus Folder Surat dan Isinya
		delete_files($folder, true , false, 1);
		//2. Hapus Folder pada desa-contoh/surat
		delete_files('desa-contoh/'.$folder, true , false, 1);
		//perubahan untuk desa
		rename('desa/'.$folder, 'desa/template-surat');	
	}
}