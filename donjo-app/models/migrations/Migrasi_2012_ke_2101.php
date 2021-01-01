<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2012_ke_2101.php
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

class Migrasi_2012_ke_2101 extends MY_model {

	public function up()
	{
		$hasil = true;

		// Tambah menu Layanan Mandiri > Pengaturan
		$query = "
			INSERT INTO setting_modul (`id`, `modul`, `url`, `aktif`, `ikon`, `urut`, `level`, `parent`, `hidden`, `ikon_kecil`) VALUES
			('314', 'Pengaturan', 'setting/mandiri', '1', 'fa-gear', '6', '2', '14', '0', 'fa-gear')
			ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), level = VALUES(level), parent = VALUES(parent), hidden = VALUES(hidden);
		";
		$hasil =& $this->db->query($query);

		// Tambahkan key layanan_mandiri
		$hasil =& $this->db->query("INSERT INTO setting_aplikasi (`key`, value, keterangan, jenis, kategori) VALUES ('layanan_mandiri', '1', 'Apakah layanan mandiri ditampilkan atau tidak', 'boolean', 'setting_mandiri')
			ON DUPLICATE KEY UPDATE value = VALUES(value), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)");

		// Ubah isi field pd tabel kelompok jd unik, kode = kode_id
		$hasil =& $this->db->query("UPDATE kelompok SET kode=CONCAT_WS('_', kode, id) WHERE id IS NOT NULL");
		// Field unik pd tabel kelompok
		$hasil =& $this->tambah_indeks('kelompok', 'kode');

		$old = "desa/css";
		$new = "desa/pengaturan";

		if (is_dir($old))
		{
			// Ubah nama folder desa/csss jadi desa/pengaturan
			rename($old, $new);
			// Hapus folder tema hadakewa
			delete_files($new . "/hadakewa", true , false, 1);
			// Ubah file img1.jpg
			rename($new . '/klasik/images/img1.jpg', $new . '/klasik/images/latar_website.jpg');

			// Ubah isi file desa-web.css
			$file = $new . '/klasik/desa-web.css';
			$str = file_get_contents($file);
			$str = str_replace("desa/css/", "desa/pengaturan/", $str);
			$str = str_replace("img1.jpg", "latar_website.jpg", $str);
			file_put_contents($file, $str);

			// Pidahkan file siteman
			mkdir($new . "/siteman", 0775);
			mkdir($new . "/siteman/images", 0775);
			copy($new . "/siteman.css", $new . "/siteman/siteman.css");
			unlink($new . "/siteman.css");
			xcopy($new . "/images", $new . "/siteman/images");
			delete_files($new . "/images", true , false, 1);
			// Ubah isi file siteman.css
			$file = $new . '/siteman/siteman.css';
			$str = file_get_contents($file);
			$str = str_replace("desa/css/", "desa/pengaturan/siteman/", $str);
			$str = str_replace("img1.jpg", "latar_login.jpg", $str);
			file_put_contents($file, $str);

			// Salin pengaturan/natra dari folder contoh/pengaturan/natra
			mkdir($new . "/natra", 0775);
			xcopy("desa-contoh/pengaturan/natra", $new . "/natra");
		}

		// Migrasi fitur premium
		$migrasi = 'migrasi_fitur_premium_2009';
  	$this->load->model('migrations/'.$migrasi);
  	$hasil =& $this->$migrasi->up();

		status_sukses($hasil);
	}

}
