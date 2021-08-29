<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2109.php
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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */

class Migrasi_fitur_premium_2109 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		$hasil = $hasil && $this->migrasi_2021081851($hasil);
		$hasil = $hasil && $this->migrasi_2021082051($hasil);
    $hasil = $hasil && $this->migrasi_2021082052($hasil);
    $hasil = $hasil && $this->migrasi_2021082151($hasil);
		$hasil = $hasil && $this->migrasi_2021082951($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	protected function migrasi_2021081851($hasil)
	{
		// Cek log surat, hapus semua file view verifikasi berdasrkan surat yg sudah di cetak
		$list_data = $this->db->select('nama_surat')->get('log_surat')->result();

		foreach ($list_data as $data)
		{
			// Hapus file
			$file = LOKASI_ARSIP . '/' . str_replace('.rtf', '.php', $data->nama_surat);
			if (file_exists($file))
			{
				$hasil = $hasil && unlink($file);
			}
		}
		
		return $hasil;
	}

	protected function migrasi_2021082051($hasil)
	{
		// Hapus file .htaccess
		$file = LOKASI_ARSIP . '.htaccess';
		if (file_exists($file))
		{
			$hasil = $hasil && unlink($file);
		}

		return $hasil;
	}

  protected function migrasi_2021082052($hasil)
	{
		// Hapus file .htaccess
		$file = LOKASI_DOKUMEN . '.htaccess';
    if (file_exists($file))
		{
			$hasil = $hasil && unlink($file);
		}

		return $hasil;
	}

  protected function migrasi_2021082151($hasil)
	{
		// Sesuaikan struktur kolom nik di table tweb_penduduk
		$fields = [
			'nik' => [
				'type' => 'VARCHAR',
				'constraint' => 16,
			]
		];

		$hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);

		// Ubah NIK 0 jadi 0[kode-desa-10-digit];
		$list_data = $this->db->select('id, nik')->get_where('tweb_penduduk', ['nik' => '0'])->result();
		foreach ($list_data as $data)
		{
			$nik_sementara = $this->penduduk_model->nik_sementara();
			$hasil = $hasil && $this->db->where('id', $data->id)->update('tweb_penduduk', ['nik' => $nik_sementara]);
		}

		$hasil = $hasil && $this->tambah_indeks('tweb_penduduk', 'nik');

    return $hasil;
	}

	protected function migrasi_2021082951($hasil)
	{
		$gol6 = $this->db->where('golongan', 6)->where('bidang', '00')->where('nama', 'KONSTRUKSI DALAM PENGERJAAN')->get('tweb_aset')->row();
		if ($gol6)
		{
			$hasil = $hasil && $this->db->where('golongan', 6)->update('tweb_aset', ['golongan' => 7]);
		}

		$gol5 = $this->db->where('golongan', 5)->where('bidang', '00')->where('nama', 'ASET TETAP LAINNYA')->get('tweb_aset')->row();
		if ($gol5)
		{
			$hasil = $hasil && $this->db->where('golongan', 5)->update('tweb_aset', ['golongan' => 6]);
			$hasil = $hasil && $this->db->query("UPDATE inventaris_asset SET register = CONCAT('6', SUBSTRING(register, 2));");
		}

		$gol4 = $this->db->where('golongan', 4)->where('bidang', '00')->where('nama', 'JALAN')->get('tweb_aset')->row();
		if ($gol4) {
			$hasil = $hasil && $this->db->where('golongan', 4)->update('tweb_aset', ['golongan' => 5]);
			$hasil = $hasil && $this->db->query("UPDATE inventaris_jalan SET register = CONCAT('5', SUBSTRING(register, 2));");
		}

		$gol3 = $this->db->where('golongan', 3)->where('bidang', '00')->where('nama', 'GEDUNG DAN BANGUNAN')->get('tweb_aset')->row();
		if ($gol3) {
			$hasil = $hasil && $this->db->where('golongan', 3)->update('tweb_aset', ['golongan' => 4]);
			$hasil = $hasil && $this->db->query("UPDATE inventaris_gedung SET register = CONCAT('4', SUBSTRING(register, 2));");
		}

		$gol2 = $this->db->where('golongan', 2)->where('bidang', '00')->where('nama', 'PERALATAN DAN MESIN')->get('tweb_aset')->row();
		if ($gol2) {
			$hasil = $hasil && $this->db->where('golongan', 2)->update('tweb_aset', ['golongan' => 3]);
			$hasil = $hasil && $this->db->query("UPDATE inventaris_peralatan SET register = CONCAT('3', SUBSTRING(register, 2));");
		}

		$gol1 = $this->db->where('golongan', 1)->where('bidang', '00')->where('nama', 'TANAH')->get('tweb_aset')->row();
		if ($gol1) {
			$hasil = $hasil && $this->db->where('golongan', 1)->update('tweb_aset', ['golongan' => 2]);
			$hasil = $hasil && $this->db->query("UPDATE inventaris_tanah SET register = CONCAT('2', SUBSTRING(register, 2));");
		}

		return $hasil;
	}
}
