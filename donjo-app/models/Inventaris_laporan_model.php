<?php

/*
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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_laporan_model extends CI_Model
{
    protected $table_pamong = 'tweb_desa_pamong';

    public function laporan_inventaris()
    {
        $laporan_inventaris = [
            ['inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri'],
            ['inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah'],
            ['inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi'],
            ['inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten'],
            ['inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan'],

            ['inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri'],
            ['inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah'],
            ['inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi'],
            ['inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten'],
            ['inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan'],

            ['inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri'],
            ['inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah'],
            ['inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi'],
            ['inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten'],
            ['inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan'],

            ['inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri'],
            ['inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah'],
            ['inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi'],
            ['inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten'],
            ['inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan'],

            ['inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri'],
            ['inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah'],
            ['inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi'],
            ['inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten'],
            ['inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan'],

            ['inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri'],
            ['inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah'],
            ['inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi'],
            ['inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten'],
            ['inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan'],
        ];
        $result = [];

        foreach ($laporan_inventaris as $inventaris) {
            $this->db->select("count({$inventaris[1]}.asal) as total");
            $this->db->where("{$inventaris[1]}.visible", 1);
            $this->db->where("{$inventaris[1]}.status", 0);
            $this->db->where("{$inventaris[1]}.asal", $inventaris[2]);
            $hasil                  = $this->db->get($inventaris[1])->row();
            $result[$inventaris[0]] = ! empty($hasil) ? $hasil : 0;
        }

        return $result;
    }

    public function mutasi_laporan_inventaris()
    {
        $laporan_inventaris = [
            ['inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri'],
            ['inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah'],
            ['inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi'],
            ['inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten'],
            ['inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan'],

            ['inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri'],
            ['inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah'],
            ['inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi'],
            ['inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten'],
            ['inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan'],

            ['inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri'],
            ['inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah'],
            ['inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi'],
            ['inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten'],
            ['inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan'],

            ['inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri'],
            ['inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah'],
            ['inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi'],
            ['inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten'],
            ['inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan'],

            ['inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri'],
            ['inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah'],
            ['inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi'],
            ['inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten'],
            ['inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan'],

            ['inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri'],
            ['inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah'],
            ['inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi'],
            ['inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten'],
            ['inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan'],
        ];
        $result = [];

        foreach ($laporan_inventaris as $inventaris) {
            $this->db->select("count({$inventaris[1]}.asal) as total");
            $this->db->where("{$inventaris[1]}.status", 1);
            $this->db->where("{$inventaris[1]}.visible", 1);
            $this->db->where("{$inventaris[1]}.asal", $inventaris[2]);
            $hasil                  = $this->db->get($inventaris[1])->row();
            $result[$inventaris[0]] = ! empty($hasil) ? $hasil : 0;
        }

        return $result;
    }

    public function cetak_inventaris($tahun)
    {
        $cetak_inventaris = [
            ['cetak_inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan', 'tahun_pengadaan'],

            ['cetak_inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan', 'tahun_pengadaan'],

            ['cetak_inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan', 'tanggal_dokument'],

            ['cetak_inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan', 'tanggal_dokument'],

            ['cetak_inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan', 'tahun_pengadaan'],

            ['cetak_inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan', 'tanggal_dokument'],
        ];
        $result = [];

        foreach ($cetak_inventaris as $inventaris) {
            $this->db->select("count({$inventaris[1]}.asal) as total");
            $this->db->where("{$inventaris[1]}.visible", 1);
            $this->db->where("{$inventaris[1]}.status", 0);
            if ($tahun != 1) {
                if ($inventaris[3] == 'tahun_pengadaan') {
                    $this->db->where("{$inventaris[1]}.tahun_pengadaan", $tahun);
                } else {
                    $this->db->where('year(tanggal_dokument)', $tahun);
                }
            }
            $this->db->where("{$inventaris[1]}.asal", $inventaris[2]);
            $hasil                  = $this->db->get($inventaris[1])->row();
            $result[$inventaris[0]] = ! empty($hasil) ? $hasil : 0;
        }

        return $result;
    }

    public function mutasi_cetak_inventaris($tahun)
    {
        $cetak_inventaris = [
            ['cetak_inventaris_tanah_pribadi', 'inventaris_tanah', 'Pembelian Sendiri', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_pemerintah', 'inventaris_tanah', 'Bantuan Pemerintah', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_provinsi', 'inventaris_tanah', 'Bantuan Provinsi', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_kabupaten', 'inventaris_tanah', 'Bantuan Kabupaten', 'tahun_pengadaan'],
            ['cetak_inventaris_tanah_sumbangan', 'inventaris_tanah', 'Sumbangan', 'tahun_pengadaan'],

            ['cetak_inventaris_peralatan_pribadi', 'inventaris_peralatan', 'Pembelian Sendiri', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_pemerintah', 'inventaris_peralatan', 'Bantuan Pemerintah', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_provinsi', 'inventaris_peralatan', 'Bantuan Provinsi', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_kabupaten', 'inventaris_peralatan', 'Bantuan Kabupaten', 'tahun_pengadaan'],
            ['cetak_inventaris_peralatan_sumbangan', 'inventaris_peralatan', 'Sumbangan', 'tahun_pengadaan'],

            ['cetak_inventaris_gedung_pribadi', 'inventaris_gedung', 'Pembelian Sendiri', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_pemerintah', 'inventaris_gedung', 'Bantuan Pemerintah', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_provinsi', 'inventaris_gedung', 'Bantuan Provinsi', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_kabupaten', 'inventaris_gedung', 'Bantuan Kabupaten', 'tanggal_dokument'],
            ['cetak_inventaris_gedung_sumbangan', 'inventaris_gedung', 'Sumbangan', 'tanggal_dokument'],

            ['cetak_inventaris_jalan_pribadi', 'inventaris_jalan', 'Pembelian Sendiri', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_pemerintah', 'inventaris_jalan', 'Bantuan Pemerintah', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_provinsi', 'inventaris_jalan', 'Bantuan Provinsi', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_kabupaten', 'inventaris_jalan', 'Bantuan Kabupaten', 'tanggal_dokument'],
            ['cetak_inventaris_jalan_sumbangan', 'inventaris_jalan', 'Sumbangan', 'tanggal_dokument'],

            ['cetak_inventaris_asset_pribadi', 'inventaris_asset', 'Pembelian Sendiri', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_pemerintah', 'inventaris_asset', 'Bantuan Pemerintah', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_provinsi', 'inventaris_asset', 'Bantuan Provinsi', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_kabupaten', 'inventaris_asset', 'Bantuan Kabupaten', 'tahun_pengadaan'],
            ['cetak_inventaris_asset_sumbangan', 'inventaris_asset', 'Sumbangan', 'tahun_pengadaan'],

            ['cetak_inventaris_kontruksi_pribadi', 'inventaris_kontruksi', 'Pembelian Sendiri', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_pemerintah', 'inventaris_kontruksi', 'Bantuan Pemerintah', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_provinsi', 'inventaris_kontruksi', 'Bantuan Provinsi', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_kabupaten', 'inventaris_kontruksi', 'Bantuan Kabupaten', 'tanggal_dokument'],
            ['cetak_inventaris_kontruksi_sumbangan', 'inventaris_kontruksi', 'Sumbangan', 'tanggal_dokument'],
        ];
        $result = [];

        foreach ($cetak_inventaris as $inventaris) {
            $this->db->select("count({$inventaris[1]}.asal) as total");
            $this->db->where("{$inventaris[1]}.status", 1);
            $this->db->where("{$inventaris[1]}.visible", 1);
            if ($tahun != 1) {
                if ($inventaris[3] == 'tahun_pengadaan') {
                    $this->db->where("{$inventaris[1]}.tahun_pengadaan", $tahun);
                } else {
                    $this->db->where('year(tanggal_dokument)', $tahun);
                }
            }
            $this->db->where("{$inventaris[1]}.asal", $inventaris[2]);
            $hasil                  = $this->db->get($inventaris[1])->row();
            $result[$inventaris[0]] = ! empty($hasil) ? $hasil : 0;
        }

        return $result;
    }

    public function permen_47($tahun, $jns_asset)
    {
        $kondisi = [
            'Baik'                      => 1,
            'Perbaiki'                  => 2,
            'Rusak'                     => 2,
            'Barang Rusak Dijual'       => 2,
            'Masih Baik Dijual'         => 1,
            'Masih Baik Disumbangkan'   => 1,
            'Barang Rusak Disumbangkan' => 2,
            'null'                      => 1,
        ];

        // sub query untuk mencari asset < tahun ke n
        // barang rusak pada tahun n-1 tidak akan masuk
        if ($jns_asset !== null) {
            $this->db->where('asset', $jns_asset);
        } // cek filter

        $sub_q = $this->db
            ->select('concat(b.asset,b.id_inventaris_asset)')
            ->where('b.status_mutasi', 'Hapus')
            ->where('year(tahun_mutasi) <', $tahun)
            ->from('rekap_mutasi_inventaris as b')->get_compiled_select();

        $tgl_thn_n = $this->db
            ->select('MAX(c.tahun_mutasi)')
            ->where('year(c.tahun_mutasi)', $tahun)
            ->where('a.asset = c.asset')
            ->where('a.id_inventaris_asset = c.id_inventaris_asset')
            ->from('rekap_mutasi_inventaris as c')->get_compiled_select();

        $tgl_thn_min_n = $this->db
            ->select('MAX(c.tahun_mutasi)')
            ->where('year(c.tahun_mutasi) <', $tahun)
            ->where('a.asset = c.asset')
            ->where('a.id_inventaris_asset = c.id_inventaris_asset')
            ->from('rekap_mutasi_inventaris as c')->get_compiled_select();

        // mutasi asset yang tidak rusak saat tahun n-1 data dianggap sebagai data akhir tahun n dan awal tahun
        $this->db
            ->where("concat(a.asset,a.id_inventaris_asset) NOT IN ({$sub_q})")
            ->where("tahun_mutasi = ({$tgl_thn_min_n})");

        if ($jns_asset !== null) {
            $this->db->where('asset', $jns_asset);
        } // cek filter

        foreach ($this->db->get('rekap_mutasi_inventaris as a') as $asset) {
            $akhir_tahun[$asset->asset][$asset->id_inventaris_asset] = $asset;
            $awal_tahun[$asset->asset][$asset->id_inventaris_asset]  = $asset;
        }

        // jika ada input pada tahun ke n. data akhir tahun akan digantikan dengan data ini
        if ($jns_asset !== null) {
            $this->db->where('asset', $jns_asset);
        } // cek filter

        $this->db->where("tahun_mutasi = ({$tgl_thn_n})");

        foreach ($this->db->get('rekap_mutasi_inventaris As a')->result() as $asset) {
            if ($asset->status_mutasi == null) {
                $asset->kondisi = 2;
            } elseif ($asset->status_mutasi == 'Hapus') {
                $asset->kondisi = $kondisi[$asset->jenis_mutasi];
            } else {
                $asset->kondisi = $kondisi[$asset->status_mutasi];
            }
            if ($asset->status_mutasi == null) {
                $asset->status_mutasi = 'Hapus';
            }
            $akhir_tahun[$asset->asset][$asset->id_inventaris_asset] = $asset; // memperbarui data akhir tahun
        }

        // ambil master data iventaris
        $inventaris = [];
        if ($jns_asset !== null) {
            $this->db->where('asset', $jns_asset);
        } // cek filter
        $master_data = $this->db
            ->where("concat(a.asset,a.id) NOT IN ({$sub_q})")
            ->where('a.tahun_pengadaan <=', $tahun)
            ->get('master_inventaris AS a');

        foreach ($master_data->result() as $asset) {
            // akhir tahun
            if (isset($akhir_tahun[$asset->asset][$asset->id])) {
                $asset->akhir_tahun   = $akhir_tahun[$asset->asset][$asset->id]->kondisi;
                $asset->tahun_mutasi  = $akhir_tahun[$asset->asset][$asset->id]->tahun_mutasi;
                $asset->status_mutasi = $akhir_tahun[$asset->asset][$asset->id]->status_mutasi;
                $asset->jenis_mutasi  = $akhir_tahun[$asset->asset][$asset->id]->jenis_mutasi;
            } else {
                $asset->akhir_tahun = $kondisi[$asset->kondisi];
            }

            // awal tahun
            if (isset($awal_tahun[$asset->asset][$asset->id])) {
                $asset->awal_tahun = $akhir_tahun[$asset->asset][$asset->id]->kondisi;
            } else {
                $asset->awal_tahun = $kondisi[$asset->kondisi];
            }
            $inventaris[] = $asset;
        }

        // rekapitulasi
        $rekap = [];

        foreach ($inventaris as $value) {
            if (! isset($rekap[$value->nama_barang])) {
                $rekap[$value->nama_barang] = [
                    'Bantuan Kabupaten'  => [],
                    'Bantuan Pemerintah' => [],
                    'Bantuan Provinsi'   => [],
                    'Pembelian Sendiri'  => [],
                    'Sumbangan'          => [],
                    'awal_baik'          => [],
                    'awal_rusak'         => [],
                    'hapus_rusak'        => [],
                    'hapus_jual'         => [],
                    'hapus_sumbang'      => [],
                    'akhir_baik'         => [],
                    'akhir_rusak'        => [],
                    'keterangan'         => [],
                ];
            }

            $rekap[$value->nama_barang][$value->asal][] = 1;
            if (isset($value->tahun_mutasi)) {
                $rekap[$value->nama_barang]['tahun_mutasi'] = $value->tahun_mutasi;
            } //tahun mutasi
            // rekap awal tahun
            if ($value->awal_tahun == 1) {
                $rekap[$value->nama_barang]['awal_baik'][] = 1;
            }

            if ($value->awal_tahun == 2) {
                $rekap[$value->nama_barang]['awal_rusak'][] = 1;
            }

            // Penghapusan
            if ($value->status_mutasi == 'Hapus') {
                if ($value->jenis_mutasi == 'Rusak') {
                    $rekap[$value->nama_barang]['hapus_rusak'][] = 1;
                }

                if ($value->jenis_mutasi == 'Masih Baik Disumbangkan') {
                    $rekap[$value->nama_barang]['hapus_sumbang'][] = 1;
                }

                if ($value->jenis_mutasi == 'Barang Rusak Disumbangkan') {
                    $rekap[$value->nama_barang]['hapus_sumbang'][] = 1;
                }

                if ($value->jenis_mutasi == 'Barang Rusak Dijual') {
                    $rekap[$value->nama_barang]['hapus_jual'][] = 1;
                }

                if ($value->jenis_mutasi == 'Masih Baik Dijual') {
                    $rekap[$value->nama_barang]['hapus_jual'][] = 1;
                }

                $rekap[$value->nama_barang]['tgl_hapus'] = $value->tahun_mutasi;
            } else {
                // rekap akhir tahun
                if ($value->akhir_tahun == 1) {
                    $rekap[$value->nama_barang]['akhir_baik'][] = 1;
                }

                if ($value->akhir_tahun == 2) {
                    $rekap[$value->nama_barang]['akhir_rusak'][] = 1;
                }
            }

            if ($value->keterangan != '') {
                $rekap[$value->nama_barang]['keterangan'][] = $value->keterangan;
            }
        }

        return $rekap;
    }

    public function min_tahun()
    {
        return $this->db
            ->select('min(m.tahun_pengadaan) as tahun')
            ->from('master_inventaris m')
            ->get()->row()->tahun;
    }
}
