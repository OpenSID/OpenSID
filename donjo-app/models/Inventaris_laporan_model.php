<?php

class Inventaris_laporan_model extends CI_Model
{

	protected $table_pamong = 'tweb_desa_pamong';

	public function __construct()
	{
		parent::__construct();
	}

	public function pamong($pamong)
	{
		$this->db->select('*');
		$this->db->from($this->table_pamong);
		$this->db->where($this->table_pamong.'.pamong_id', $pamong);
		$data = $this->db->get()->row();
		return $data;
	}

	public function laporan_inventaris()
	{
		$laporan_inventaris = array(
			array('inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri'),
			array('inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah'),
			array('inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi'),
			array('inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten'),
			array('inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan'),

			array('inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri'),
			array('inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah'),
			array('inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi'),
			array('inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten'),
			array('inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan'),

			array('inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri'),
			array('inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah'),
			array('inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi'),
			array('inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten'),
			array('inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan'),

			array('inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri'),
			array('inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah'),
			array('inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi'),
			array('inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten'),
			array('inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan'),

			array('inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri'),
			array('inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah'),
			array('inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi'),
			array('inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten'),
			array('inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan'),

			array('inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri'),
			array('inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah'),
			array('inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi'),
			array('inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten'),
			array('inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan')
		);
		$result = array();
		foreach ($laporan_inventaris as $inventaris)
		{
			$this->db->select("count($inventaris[1].asal) as total");
			$this->db->where("$inventaris[1].visible", 1);
			$this->db->where("$inventaris[1].status", 0);
			$this->db->where("$inventaris[1].asal", $inventaris[2]);
			$hasil = $this->db->get($inventaris[1])->row();
			$result[$inventaris[0]] = !empty($hasil) ? $hasil : 0;
		}
		return $result;
	}

	public function mutasi_laporan_inventaris()
	{
		$laporan_inventaris = array(
			array('inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri'),
			array('inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah'),
			array('inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi'),
			array('inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten'),
			array('inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan'),

			array('inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri'),
			array('inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah'),
			array('inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi'),
			array('inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten'),
			array('inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan'),

			array('inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri'),
			array('inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah'),
			array('inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi'),
			array('inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten'),
			array('inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan'),

			array('inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri'),
			array('inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah'),
			array('inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi'),
			array('inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten'),
			array('inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan'),

			array('inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri'),
			array('inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah'),
			array('inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi'),
			array('inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten'),
			array('inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan'),

			array('inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri'),
			array('inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah'),
			array('inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi'),
			array('inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten'),
			array('inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan')
		);
		$result = array();
		foreach ($laporan_inventaris as $inventaris)
		{
			$this->db->select("count($inventaris[1].asal) as total");
			$this->db->where("$inventaris[1].status", 1);
			$this->db->where("$inventaris[1].asal", $inventaris[2]);
			$hasil = $this->db->get($inventaris[1])->row();
			$result[$inventaris[0]] = !empty($hasil) ? $hasil : 0;
		}
		return $result;
	}

	public function cetak_inventaris($tahun)
	{
		$cetak_inventaris = array(
			array('cetak_inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan', 'tahun_pengadaan'),

			array('cetak_inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan', 'tahun_pengadaan'),

			array('cetak_inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan', 'tanggal_dokument'),

			array('cetak_inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan', 'tanggal_dokument'),

			array('cetak_inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan', 'tahun_pengadaan'),

			array('cetak_inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan', 'tanggal_dokument')
		);
		$result = array();
		foreach ($cetak_inventaris as $inventaris)
		{
			$this->db->select("count($inventaris[1].asal) as total");
			$this->db->where("$inventaris[1].visible", 1);
			$this->db->where("$inventaris[1].status", 0);
			if ($tahun != 1)
			{
				if ($inventaris[3] == 'tahun_pengadaan')
				{
					$this->db->where("$inventaris[1].tahun_pengadaan", $tahun);
				}
				else
				{
					$this->db->where('year(tanggal_dokument)', $tahun);
				}
			}
			$this->db->where("$inventaris[1].asal", $inventaris[2]);
			$hasil = $this->db->get($inventaris[1])->row();
			$result[$inventaris[0]] = !empty($hasil) ? $hasil : 0;
		}
		return $result;
	}

	public function mutasi_cetak_inventaris($tahun)
	{
		$cetak_inventaris = array(
			array('cetak_inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten', 'tahun_pengadaan'),
			array('cetak_inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan', 'tahun_pengadaan'),

			array('cetak_inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten', 'tahun_pengadaan'),
			array('cetak_inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan', 'tahun_pengadaan'),

			array('cetak_inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten', 'tanggal_dokument'),
			array('cetak_inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan', 'tanggal_dokument'),

			array('cetak_inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten', 'tanggal_dokument'),
			array('cetak_inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan', 'tanggal_dokument'),

			array('cetak_inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten', 'tahun_pengadaan'),
			array('cetak_inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan', 'tahun_pengadaan'),

			array('cetak_inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten', 'tanggal_dokument'),
			array('cetak_inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan', 'tanggal_dokument')
		);
		$result = array();
		foreach ($cetak_inventaris as $inventaris)
		{
			$this->db->select("count($inventaris[1].asal) as total");
			$this->db->where("$inventaris[1].status", 1);
			if ($tahun != 1)
			{
				if ($inventaris[3] == 'tahun_pengadaan')
				{
					$this->db->where("$inventaris[1].tahun_pengadaan", $tahun);
				}
				else
				{
					$this->db->where('year(tanggal_dokument)', $tahun);
				}
			}
			$this->db->where("$inventaris[1].asal", $inventaris[2]);
			$hasil = $this->db->get($inventaris[1])->row();
			$result[$inventaris[0]] = !empty($hasil) ? $hasil : 0;
		}
		return $result;
	}

}




















