<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2010_ke_2011.php
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

class Migrasi_2011_ke_2012 extends MY_model {

	public function up()
	{
		$hasil = true;

		// Tambah kolom masa_berlaku & satuan_masa_berlaku di tweb_surat_format
		if ( ! $this->db->field_exists('masa_berlaku', 'tweb_surat_format'))
		{
			$fields = [
				'masa_berlaku' => [
					'type' => 'INT',
					'constraint' => 3,
					'default' => '1'
				],
				'satuan_masa_berlaku' => [
					'type' => 'VARCHAR',
					'constraint' => 15,
					'default' => 'M'
				]
			];

			$hasil = $this->dbforge->add_column('tweb_surat_format', $fields);
		}

		// Pengaturan Token TrackSID
		if ( ! $this->db->field_exists('token_opensid', 'setting_aplikasi'))
		{
			$query = "
				INSERT INTO `setting_aplikasi` (`id`, `key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES
				(43, 'token_opensid', '', 'Token OpenSID', '', 'sistem')
				ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)";
			$hasil =& $this->db->query($query);
  	}

		// Migrasi fitur premium
		// Jalankan juga migrasi versi-versi sebelumnya, karena migrasi dari rllis umum belum menjalankan
  	$daftar_migrasi_premium = ['2009', '2010', '2011', '2012'];
  	foreach ($daftar_migrasi_premium as $migrasi)
  	{
  		$migrasi_premium = 'migrasi_fitur_premium_'.$migrasi;
  		$file_migrasi = 'migrations/'.$migrasi_premium;
			$this->load->model($file_migrasi);
			$this->$migrasi_premium->up();
  	}
		status_sukses($hasil);
		return $hasil;
	}
}
