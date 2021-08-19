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
		
		$hasil = $hasil && $this->migrasi_2021080771($hasil);
		$hasil = $hasil && $this->migrasi_2021081851($hasil);
		$hasil = $hasil && $this->migrasi_2021082051($hasil);
		$hasil = $hasil && $this->migrasi_2021082871($hasil);
		$hasil = $hasil && $this->migrasi_2021082971($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	protected function migrasi_2021080771($hasil)
	{
		if ( ! $this->db->field_exists('mac_address', 'anjungan'))
		{
			$hasil = $hasil && $this->dbforge->add_column('anjungan', ['mac_address' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true]]);
		}

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
		$file = LOKASI_ARSIP . '/.htaccess';
		if (file_exists($file))
		{
			$hasil = $hasil && unlink($file);
		}

		return $hasil;
	}

	protected function migrasi_2021082871($hasil)
	{
		$hasil = $hasil && $this->tambah_modul([
			'id'         => 326,
			'modul'      => 'Lembaga [Desa]',
			'url'        => 'lembaga',
			'aktif'      => 1,
			'ikon'       => 'fa-list',
			'urut'       => 4,
			'level'      => 2,
			'hidden'     => 0,
			'ikon_kecil' => 'fa-list',
			'parent'     => 200
		]);

		$hasil = $hasil && $this->tambah_modul([
			'id'     => 327,
			'modul'  => 'Kategori Lembaga',
			'url'    => 'lembaga_master',
			'aktif'  => 1,
			'hidden' => 2,
			'parent' => 326
		]);

		// Hapus cache menu navigasi
		$this->load->driver('cache');
		$this->cache->hapus_cache_untuk_semua('_cache_modul');
		
		if ( ! $this->db->field_exists('tipe', 'kelompok'))
		{
			$hasil = $hasil && $this->dbforge->add_column('kelompok', ['tipe' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'kelompok']]);
		}

		if ( ! $this->db->field_exists('tipe', 'kelompok_anggota'))
		{
			$hasil = $hasil && $this->dbforge->add_column('kelompok_anggota', [
				'tipe'                 => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'kelompok'],
				'periode'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
				'nmr_sk_pengangkatan'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
				'tgl_sk_pengangkatan'  => ['type' => 'date', 'null' => true],
				'nmr_sk_pemberhentian' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
				'tgl_sk_pemberhentian' => ['type' => 'date', 'null' => true],
			]);
		}

		if ( ! $this->db->field_exists('tipe', 'kelompok_master'))
		{
			$hasil = $hasil && $this->dbforge->add_column('kelompok_master', ['tipe' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'kelompok']]);
		}

		// Tambah hak ases group operator
		$query = "
			INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES
			-- Operator --
			(2,326,3), -- Lembaga --
			(2,327,3) -- Kategori Lembaga --
		";
		
		return $hasil && $this->db->query($query);
	}
	
	protected function migrasi_2021082971($hasil)
	{
		if ( ! $this->db->field_exists('no_antrian', 'permohonan_surat'))
		{
			$hasil = $hasil && $this->dbforge->add_column('permohonan_surat', ['no_antrian' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true]]);
		}

		return $hasil;
	}
}
