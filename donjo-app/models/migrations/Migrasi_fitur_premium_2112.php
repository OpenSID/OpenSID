<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2112.php
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

class Migrasi_fitur_premium_2112 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

    $hasil = $hasil && $this->migrasi_2021111251($hasil);
    $hasil = $hasil && $this->migrasi_2021111451($hasil);
    $hasil = $hasil && $this->migrasi_2021111551($hasil);
    $hasil = $hasil && $this->migrasi_2021111552($hasil);

		status_sukses($hasil);
		return $hasil;
	}

  protected function migrasi_2021111251($hasil)
  {
    // Ubah default kk_level menjadi null; tadinya 0
    $fields = [
      'kk_level' => [
        'type' => 'TINYINT',
        'constraint' => 2,
        'null' => TRUE,
        'default' => NULL
      ],
    ];
    $hasil = $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);

    $hasil = $hasil && $this->db
      ->set('kk_level', NULL)
      ->where('kk_level', 0)
      ->update('tweb_penduduk');

    // Ubah rentang umur kategori TUA untuk kasus salah pengisian tanggal lahir
    $hasil = $hasil && $this->db
      ->set('sampai', '99999')
      ->where('id', 4)
      ->update('tweb_penduduk_umur');


    // Ubah cara_kb_id yg nilainya tidak valid
    $hasil = $hasil && $this->db
      ->set('cara_kb_id', NULL)
      ->where_not_in('cara_kb_id', [1, 2, 3, 4, 5, 6, 7, 99])
      ->update('tweb_penduduk');

    return $hasil;
  }

  protected function migrasi_2021111451($hasil)
  {
    // Ubah judul status hubungan dalam keluarga
    return $hasil && $this->db->where('id', 9)->update('tweb_penduduk_hubungan', ['nama' => 'FAMILI LAIN']);
  }

  protected function migrasi_2021111551($hasil)
  {
    // Hapus data analisis_parameter dengan responden 0 untuk tipe pertanyaan 3 dan 4
    $this->load->model('analisis_statistik_jawaban_model');
    return $hasil && $this->analisis_statistik_jawaban_model->hapus_data_kosong();
  }

  protected function migrasi_2021111552($hasil)
  {
    // Tambah lampiran untuk Surat Keterangan Kelahiran
		return $hasil && $this->db->where('url_surat', 'surat_ket_kelahiran')->update('tweb_surat_format', ['lampiran' => 'f-2.01.php']);
  }
}