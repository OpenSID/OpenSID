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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_laporan_model extends MY_Model
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
            $this->config_id();
            $hasil = $this->db->get($inventaris[1])->row();

            $result[$inventaris[0]] = empty($hasil) ? 0 : $hasil;
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
            $this->config_id();
            $hasil = $this->db->get($inventaris[1])->row();

            $result[$inventaris[0]] = empty($hasil) ? 0 : $hasil;
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
                if ($inventaris[3] === 'tahun_pengadaan') {
                    $this->db->where("{$inventaris[1]}.tahun_pengadaan", $tahun);
                } else {
                    $this->db->where('year(tanggal_dokument)', $tahun);
                }
            }
            $this->db->where("{$inventaris[1]}.asal", $inventaris[2]);
            $this->config_id();
            $hasil = $this->db->get($inventaris[1])->row();

            $result[$inventaris[0]] = empty($hasil) ? 0 : $hasil;
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
                if ($inventaris[3] === 'tahun_pengadaan') {
                    $this->db->where("{$inventaris[1]}.tahun_pengadaan", $tahun);
                } else {
                    $this->db->where('year(tanggal_dokument)', $tahun);
                }
            }
            $this->db->where("{$inventaris[1]}.asal", $inventaris[2]);
            $this->config_id();
            $hasil = $this->db->get($inventaris[1])->row();

            $result[$inventaris[0]] = empty($hasil) ? 0 : $hasil;
        }

        return $result;
    }

    public function min_tahun()
    {
        return $this->db
            ->select('min(m.tahun_pengadaan) as tahun')
            ->from('master_inventaris m')
            ->where('m.config_id', identitas('id'))
            ->get()->row()->tahun;
    }
}
