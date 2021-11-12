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

		$hasil = $hasil && $this->migrasi_2021110571($hasil);
    $hasil = $hasil && $this->migrasi_2021111251($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	// Tambah modul kader pemberdayaan masyarakat
	protected function migrasi_2021110571($hasil)
	{
		$hasil = $hasil && $this->tambah_modul_kader_pemberdayaan_masyarakat($hasil);
		$hasil = $hasil && $this->tabel_referensi($hasil);

		return $hasil;
	}

	protected function tambah_modul_kader_pemberdayaan_masyarakat($hasil)
	{
		$hasil = $hasil && $this->tambah_modul([
			'id'     => 331,
			'modul'  => 'Kader Pemberdayaan Masyarakat',
			'url'    => 'bumindes_kader',
			'aktif'  => 1,
			'hidden' => 2,
			'parent' => 301
		]);

		// Tambah hak ases group operator
		$query = "
			INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES
			-- Operator --
			(2,331,3) -- Bumindes Kader Pemberdayaan Masyarakat --
			";

		$hasil = $hasil && $this->db->query($query);

		return $hasil;
	}

	public function tabel_referensi($hasil)
	{
		// Tambah tabel kader_pemberdayaan_masyarakat
		if (! $this->db->table_exists('kader_pemberdayaan_masyarakat'))
		{
			$fields = [
				'id' => [
					'type' => 'INT',
					'constraint' => 12,
					'unsigned' => true,
					'auto_increment' => true
				],
				'penduduk_id' => [
					'type' => 'INT',
					'constraint' => 12,
				],
				'kursus' => [
					'type' => 'TEXT',
					'null' => true,
					'default' => null,
				],
				'bidang' => [
					'type' => 'TEXT',
					'null' => true,
					'default' => null,
				],
				'keterangan' => [
					'type' => 'TEXT',
					'null' => true,
					'default' => null,
				],
			];
			$hasil = $hasil && $this->dbforge->add_field($fields);
			$hasil = $hasil && $this->dbforge->add_key('id', TRUE);
			$hasil = $hasil && $this->dbforge->create_table("kader_pemberdayaan_masyarakat", TRUE);
		}

		if (! $this->db->table_exists('ref_penduduk_bidang'))
		{
			$fields = [
				'id' => [
					'type' => 'INT',
					'constraint' => 12,
					'unsigned' => true,
					'auto_increment' => true
				],
				'nama' => [
					'type' => 'VARCHAR',
					'constraint' => 100,
				],
			];
			$hasil = $hasil && $this->dbforge->add_field($fields);
			$hasil = $hasil && $this->dbforge->add_key('id', TRUE);
			$hasil = $hasil && $this->dbforge->create_table("ref_penduduk_bidang", TRUE);

			// Tambahkan data awal
			$this->db->truncate('ref_penduduk_bidang');
			$insert_batch = [
				['nama' => 'Service Komputer'],
				['nama' => 'Operator Buldoser'],
				['nama' => 'Operator Komputer'],
				['nama' => 'Operator Genset'],
				['nama' => 'Service HP'],
				['nama' => 'Rias Pengantin'],
				['nama' => 'Design Grafis'],
				['nama' => 'Menjahit'],
				['nama' => 'Menulis'],
				['nama' => 'Reporter'],
				['nama' => 'Sosial Media Manajer'],
				['nama' => 'Manajemen Trainee'],
				['nama' => 'Kasir'],
				['nama' => 'HRD'],
				['nama' => 'Guru'],
				['nama' => 'Digital Marketing'],
				['nama' => 'Customer Services'],
				['nama' => 'Welder'],
				['nama' => 'Mekanik Alat Berat'],
				['nama' => 'Teknisi Listrik'],
				['nama' => 'Internet Marketing'],
			];

			$hasil = $hasil && $this->db->insert_batch('ref_penduduk_bidang', $insert_batch);
		}

		if (! $this->db->table_exists('ref_penduduk_kursus'))
		{
			$fields = [
				'id' => [
					'type' => 'INT',
					'constraint' => 12,
					'unsigned' => true,
					'auto_increment' => true
				],
				'nama' => [
					'type' => 'VARCHAR',
					'constraint' => 100,
				],
			];
			$hasil = $hasil && $this->dbforge->add_field($fields);
			$hasil = $hasil && $this->dbforge->add_key('id', TRUE);
			$hasil = $hasil && $this->dbforge->create_table("ref_penduduk_kursus", TRUE);

			// tambahkan data awal
			$this->db->truncate('ref_penduduk_kursus');
			$insert_batch = [
				['nama' => 'Kursus Komputer'],
				['nama' => 'Kursus Menjahit'],
				['nama' => 'Pelatihan Kelistrikan'],
				['nama' => 'Kursus Mekanik Motor'],
				['nama' => 'Pelatihan Security'],
				['nama' => 'Kursus Otomotif'],
				['nama' => 'Kursus Bahasa Inggris'],
				['nama' => 'Kursus Tata Kecantikan Kulit'],
				['nama' => 'Kursus Megemudi'],
				['nama' => 'Kursus Tata Boga'],
				['nama' => 'Kursus Meubeler'],
				['nama' => 'Kursus Las'],
				['nama' => 'Kursus Sablon'],
				['nama' => 'Kursus Penerbangan'],
				['nama' => 'Kursus Desain Interior'],
				['nama' => 'Kursus Teknisi HP'],
				['nama' => 'Kursus Garment'],
				['nama' => 'Kursus Akupuntur'],
				['nama' => 'Kursus Senam'],
				['nama' => 'Kursus Pendidik PAUD'],
				['nama' => 'Kursus Baby Sitter'],
				['nama' => 'Kursus Desain Grafis'],
				['nama' => 'Kursus Bahasa Indonesia'],
				['nama' => 'Kursus Photografi'],
				['nama' => 'Kursus Expor Impor'],
				['nama' => 'Kursus Jurnalistik'],
				['nama' => 'Kursus Bahasa Arab'],
				['nama' => 'Kursus Bahasa Jepang'],
				['nama' => 'Kursus Anak Buah Kapal'],
				['nama' => 'Kursus Refleksi'],
				['nama' => 'Kursus Akupuntur'],
				['nama' => 'Kursus Perhotelan'],
				['nama' => 'Kursus Tata Rias'],
				['nama' => 'Kursus Administrasi Perkantoran'],
				['nama' => 'Kursus Broadcasting'],
				['nama' => 'Kursus Kerajinan Tangan'],
				['nama' => 'Kursus Sosial Media Marketing'],
				['nama' => 'Kursus Internet Marketing'],
				['nama' => 'Kursus Sekretaris'],
				['nama' => 'Kursus Perpajakan'],
				['nama' => 'Kursus Publik Speaking'],
				['nama' => 'Kursus Publik Relation'],
				['nama' => 'Kursus Batik'],
				['nama' => 'Kursus Pengobatan Tradisional'],
			];

			$hasil = $hasil && $this->db->insert_batch('ref_penduduk_kursus', $insert_batch);
		}

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
}