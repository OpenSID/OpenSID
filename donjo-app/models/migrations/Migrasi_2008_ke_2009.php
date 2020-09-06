<?php

/**
 * File ini:
 *
 * Model untuk migrasi database
 *
 * donjo-app/models/migrations/Migrasi_2008_ke_2009.php
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

class Migrasi_2008_ke_2009 extends CI_model {

	public function up()
	{
		// Hapus url parrent menu layanan mandiri
		$this->db->where('id', 14)
			->set('url', '')
			->update('setting_modul');

		// Catatan : u/ field dgn table referensi jika tdk ada maka nilainya NULL, jgn isi 0 agar sesuai saat difilter pd statistik
		$this->db->where('sakit_menahun_id', 0)
			->set('sakit_menahun_id', NULL)
			->update('tweb_penduduk');
		$this->db->where('cacat_id', 0)
			->set('cacat_id', NULL)
			->update('tweb_penduduk');

		// Update isi field_admin pd widget agenda
		$this->db->where('isi', 'agenda.php')
			->set('form_admin', 'web/tab/1000')
			->update('widget');

		// Hapus view lama yg tdk digunakan lagi
		$this->db->query("DROP VIEW IF EXISTS data_surat");

		// Tambah kolom kartu_id_pend di tabel program_peserta
		if (!$this->db->field_exists('kartu_id_pend', 'program_peserta'))
		{
			$fields['kartu_id_pend'] = [
				'type' => 'INT',
				'constraint' => 11,
			];

			$this->dbforge->add_column('program_peserta', $fields);
		}

		// Isi field kartu_id_pend berdasarkan data peserta program
		$list_peserta = $this->db->select('id, kartu_nik, kartu_id_pend')->get('program_peserta')->result_array();
		foreach ($list_peserta as $peserta)
		{
			// Cari penduduk berdasaran kartu_nik
			$penduduk = $this->db->select('id')->get_where('tweb_penduduk', ['nik' => $peserta['kartu_nik']])->row_array();
			if (($peserta['kartu_id_pend'] == NULL) && $penduduk) $this->db->where('id', $peserta['id'])->update('program_peserta', ['kartu_id_pend' => $penduduk['id']]);
		}

		// Hapus anggota kelompok yg tdk memiliki kelompok / tdk terhapus saat menghapus kelompok
		$kelompok = $this->db->select('id')->get('kelompok')->result_array();
		$list_id_kelompok = sql_in_list(array_column($kelompok, 'id'));
		if ($list_id_kelompok) $this->db->where("id_kelompok NOT IN ($list_id_kelompok)")->delete('kelompok_anggota');
		// Buat FOREIGN KEY field id_kelompok jika tdk ada
		$query = $this->db
			->from('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
			->where('CONSTRAINT_NAME', 'kelompok_anggota_fk')
			->where('TABLE_NAME', 'kelompok_anggota')
			->get();

		if ($query->num_rows() == 0)
		{
			$this->dbforge->add_column('kelompok_anggota', [
				'CONSTRAINT `kelompok_anggota_fk` FOREIGN KEY (`id_kelompok`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
			]);
		}

		// Migrasi fitur premium
		$migrasi = 'migrasi_fitur_premium_2009';
  	$this->load->model('migrations/'.$migrasi);
  	$this->$migrasi->up();
	}

}
