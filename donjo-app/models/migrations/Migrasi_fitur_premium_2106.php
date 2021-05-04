<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2106.php
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
class Migrasi_fitur_premium_2106 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		status_sukses($hasil);
		return $hasil;
	}

	protected function tambah_jenis_mutasi_inventaris()
	{
		$hasil = true;
		if ( ! $this->db->field_exists('jenis_mutasi', 'mutasi_inventaris_asset'))
		{
			$hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_asset', 'jenis_mutasi varchar(50) NOT NULL');
		}

		if ( ! $this->db->field_exists('jenis_mutasi', 'mutasi_inventaris_gedung'))
		{
			$hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_gedung', 'jenis_mutasi varchar(50) NOT NULL');
		}

		if ( ! $this->db->field_exists('jenis_mutasi', 'mutasi_inventaris_jalan'))
		{
			$hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_jalan', 'jenis_mutasi varchar(50) NOT NULL');
		}

		if ( ! $this->db->field_exists('jenis_mutasi', 'mutasi_inventaris_peralatan'))
		{
			$hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_peralatan', 'jenis_mutasi varchar(50) NOT NULL');
		}

		if ( ! $this->db->field_exists('jenis_mutasi', 'mutasi_inventaris_asset'))
		{
			$hasil = $hasil && $this->dbforge->add_column('mutasi_inventaris_asset', 'jenis_mutasi varchar(50) NOT NULL');
		}

		$this->db->query("DROP VIEW master_inventaris");
		$this->db->query("CREATE VIEW master_inventaris AS 
			SELECT
				'inventaris_asset' AS asset,
				inventaris_asset.id,
				inventaris_asset.nama_barang,
				inventaris_asset.kode_barang,
				'Baik' AS kondisi,
				inventaris_asset.keterangan,
				inventaris_asset.asal
			FROM
				inventaris_asset
			UNION ALL
			SELECT
				'inventaris_gedung' AS asset,
				inventaris_gedung.id,
				inventaris_gedung.nama_barang,
				inventaris_gedung.kode_barang,
				inventaris_gedung.kondisi_bangunan,
				inventaris_gedung.keterangan,
				inventaris_gedung.asal
			FROM
				inventaris_gedung
			UNION ALL
			SELECT
				'inventaris_jalan' AS asset,
				inventaris_jalan.id,
				inventaris_jalan.nama_barang,
				inventaris_jalan.kode_barang,
				inventaris_jalan.kondisi,
				inventaris_jalan.keterangan,
				inventaris_jalan.asal
			FROM
				inventaris_jalan
			UNION ALL
			SELECT
				'inventaris_peralatan' AS asset,
				inventaris_peralatan.id,
				inventaris_peralatan.nama_barang,
				inventaris_peralatan.kode_barang,
				'Baik',
				inventaris_peralatan.keterangan,
				inventaris_peralatan.asal
			FROM
				inventaris_peralatan
			UNION ALL
			SELECT
				'inventaris_tanah' AS asset,
				inventaris_tanah.id,
				inventaris_tanah.nama_barang,
				inventaris_tanah.kode_barang,
				'Baik',
				inventaris_tanah.keterangan,
				inventaris_tanah.asal
			FROM
				inventaris_tanah
			WHERE
				inventaris_tanah.nama_barang NOT IN (
						SELECT
							tweb_aset.nama
						FROM
							tweb_aset
						WHERE
							(`golongan` = '1'	AND `bidang` = '01'	AND kelompok = '01')
						OR (`golongan` = '1' AND `bidang` = '01' AND kelompok = '00')
					)
		");

		$this->db->query("DROP VIEW rekap_mutasi_inventaris");
		$this->db->query("CREATE VIEW master_inventaris AS
			SELECT
				'inventaris_asset' as asset,
				id_inventaris_asset,
				status_mutasi,
				jenis_mutasi,
				tahun_mutasi,
				keterangan
			FROM
				mutasi_inventaris_asset
			UNION ALL
			SELECT
				'inventaris_gedung',
				id_inventaris_gedung,
				status_mutasi,
				jenis_mutasi,
				tahun_mutasi,
				keterangan
			FROM
				mutasi_inventaris_gedung
			UNION ALL
			SELECT
				'inventaris_jalan',
				id_inventaris_jalan,
				status_mutasi,
				jenis_mutasi,
				tahun_mutasi,
				keterangan
			FROM
			mutasi_inventaris_jalan
			UNION ALL
			SELECT
				'inventaris_peralatan',
				id_inventaris_peralatan,
				status_mutasi,
				jenis_mutasi,
				tahun_mutasi,
				keterangan
			FROM
				mutasi_inventaris_peralatan
			UNION ALL
				SELECT
					'inventaris_tanah',
					id_inventaris_tanah,
					status_mutasi,
					jenis_mutasi,
					tahun_mutasi,
					keterangan
				FROM
					mutasi_inventaris_tanah
		");


		return $hasil;
	}
}