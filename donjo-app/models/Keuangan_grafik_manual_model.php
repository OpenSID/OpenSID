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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Keuangan_grafik_manual_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['keuangan_manual_model', 'keuangan_grafik_model']);
    }

    public function rp_apbd_widget($thn, $opt = false)
    {
        $this->db->select('Akun, Nama_Akun');

        if ($opt) {
            $this->db->where("Akun NOT LIKE '1.%'");
            $this->db->where("Akun NOT LIKE '2.%'");
            $this->db->where("Akun NOT LIKE '3.%'");
            $this->db->where("Akun NOT LIKE '7.%'");
        } else {
            $this->db->where("Akun NOT LIKE '1.%'");
            $this->db->where("Akun NOT LIKE '7.%'");
        }

        $this->db->order_by('Akun', 'asc');
        $this->db->group_by('Akun');
        $this->db->group_by('Nama_Akun');
        $data['jenis_pelaksanaan'] = $this->db->get('keuangan_manual_ref_rek1')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai_Anggaran) AS pagu');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('jenis_pelaksanaan');

        $data['anggaran'] = $this->config_id()->get('keuangan_manual_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai_Realisasi) AS realisasi');
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->where('Tahun', $thn);

        $data['realisasi_pendapatan'] = $this->config_id()->get('keuangan_manual_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 2) AS jenis_pelaksanaan, SUM(Nilai_Realisasi) AS realisasi');
        $this->db->where('keuangan_manual_rinci.Tahun', $thn);
        $this->db->group_by('jenis_pelaksanaan');
        $this->db->like('Kd_Rincian', '5.', 'after');

        $data['realisasi_belanja'] = $this->config_id()->get('keuangan_manual_rinci')->result_array();

        $this->db->select('Akun, Nama_Akun');
        $this->db->where("Akun = '6.'");
        $data['pembiayaan_keluar'] = $this->db->get('keuangan_manual_ref_rek1')->result_array();

        foreach ($data['pembiayaan_keluar'] as $i => $p) {
            $data['pembiayaan'][$i]['sub_pembiayaan_keluar'] = $this->get_subval_pembiayaan_keluar($p['Akun'], $thn);
            $data['pembiayaan'][$i]['sub_pembiayaan']        = $this->get_subval_pembiayaan($p['Akun'], $thn);
        }

        return $data;
    }

    public function r_pd_widget($thn, $opt = false)
    {
        $this->db->select('keuangan_manual_ref_rek3.Jenis, keuangan_manual_ref_rek3.Nama_Jenis');

        if ($opt) {
            $this->db->where("keuangan_manual_ref_rek3.Jenis LIKE '4.%'");
        } else {
            $this->db->where("keuangan_manual_ref_rek3.Jenis NOT LIKE '1.%'");
            $this->db->where("keuangan_manual_ref_rek3.Jenis NOT LIKE '5.%'");
            $this->db->where("keuangan_manual_ref_rek3.Jenis NOT LIKE '6.%'");
            $this->db->where("keuangan_manual_ref_rek3.Jenis NOT LIKE '7.%'");
        }

        $this->db->where("keuangan_manual_ref_rek3.Nama_Jenis NOT LIKE '%Hutang%'");
        $this->db->where("keuangan_manual_ref_rek3.Nama_Jenis NOT LIKE '%Ekuitas SAL%'");

        $this->db->order_by('keuangan_manual_ref_rek3.Jenis', 'asc');
        $data['jenis_pendapatan'] = $this->db->get('keuangan_manual_ref_rek3')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(Nilai_Anggaran) AS pagu');
        $this->db->like('Kd_Rincian', '4.', 'after');
        $this->db->group_by('jenis_pendapatan');
        $this->db->where('Tahun', $thn);

        $data['anggaran'] = $this->config_id()->get('keuangan_manual_rinci')->result_array();

        $this->db->select('LEFT(Kd_Rincian, 6) AS jenis_pendapatan, SUM(Nilai_Realisasi) AS realisasi');
        $this->db->like('Kd_Rincian', '4.', 'after');
        $this->db->group_by('jenis_pendapatan');
        $this->db->where('Tahun', $thn);

        $data['realisasi_pendapatan'] = $this->config_id()->get('keuangan_manual_rinci')->result_array();

        return $data;
    }

    public function r_bd_widget($thn, $opt = false)
    {
        $this->db->select('Kd_Bid, Nama_Bidang');
        if ($opt) {
            $this->db->where("Kd_Bid NOT LIKE '01%'");
            $this->db->where("Kd_Bid NOT LIKE '02%'");
            $this->db->where("Kd_Bid NOT LIKE '03%'");
        } else {
            $this->db->where("Kd_Bid NOT LIKE '01%'");
        }

        $this->db->order_by('Kd_Bid', 'asc');
        $data['jenis_belanja'] = $this->db->get('keuangan_manual_ref_bidang')->result_array();
        // Perlu ditambahkan baris berikut untuk memaksa menampilkan semua bidang di grafik keuangan
        // TODO: lihat apakah bisa diatasi langsung di script penampilan
        if (! $opt) {
            array_unshift($data['jenis_belanja'], ['Kd_Bid' => '03', 'Nama_Bidang' => 'ROW_SPACER']);
            array_unshift($data['jenis_belanja'], ['Kd_Bid' => '02', 'Nama_Bidang' => 'ROW_SPACER']);
        }

        $this->db->select('LEFT(Kd_Keg, 10) AS jenis_belanja, SUM(Nilai_Anggaran) AS pagu');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->group_by('jenis_belanja');
        $this->db->where('Tahun', $thn);

        $data['anggaran'] = $this->config_id()->get('keuangan_manual_rinci')->result_array();

        $this->db->select('LEFT(Kd_Keg, 10) AS jenis_belanja, SUM(Nilai_Realisasi) AS realisasi');
        $this->db->like('Kd_Rincian', '5.', 'after');
        $this->db->where('Tahun', $thn);
        $this->db->group_by('jenis_belanja');

        $data['realisasi_belanja'] = $this->config_id()->get('keuangan_manual_rinci')->result_array();

        return $data;
    }

    private function data_widget_pendapatan($tahun, bool $opt = false)
    {
        if ($opt) {
            $raw_data       = $this->r_pd_widget($tahun, $opt = true);
            $res_pendapatan = [];
            $tmp_pendapatan = [];

            foreach ($raw_data['jenis_pendapatan'] as $r) {
                $tmp_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }
        } else {
            $raw_data       = $this->r_pd_widget($tahun, $opt = false);
            $res_pendapatan = [];
            $tmp_pendapatan = [];

            foreach ($raw_data['jenis_pendapatan'] as $r) {
                $tmp_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pendapatan[$r['jenis_pendapatan']]['realisasi_pendapatan'] = ($r['realisasi'] ?: 0);
            }
        }

        foreach ($tmp_pendapatan as $value) {
            $res_pendapatan[] = $value;
        }

        return $res_pendapatan;
    }

    private function data_widget_belanja($tahun, bool $opt = false)
    {
        if ($opt) {
            $raw_data    = $this->r_bd_widget($tahun, $opt = true);
            $res_belanja = [];
            $tmp_belanja = [];

            foreach ($raw_data['jenis_belanja'] as $r) {
                $tmp_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi'] = ($r['realisasi'] ?: 0);
            }
        } else {
            $raw_data    = $this->r_bd_widget($tahun, $opt = false);
            $res_belanja = [];
            $tmp_belanja = [];

            foreach ($raw_data['jenis_belanja'] as $r) {
                $tmp_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_belanja'] as $r) {
                $tmp_belanja[$r['jenis_belanja']]['realisasi_belanja'] = ($r['realisasi'] ?: 0);
            }
        }

        foreach ($tmp_belanja as $value) {
            $res_belanja[] = $value;
        }

        return $res_belanja;
    }

    private function data_widget_pelaksanaan($tahun, bool $opt = false)
    {
        if ($opt) {
            $raw_data = $this->rp_apbd_widget($tahun, $opt = true);

            $res_pelaksanaan = [];
            $tmp_pelaksanaan = [];

            foreach ($raw_data['jenis_pelaksanaan'] as $r) {
                $tmp_pelaksanaan[$r['Akun']]['nama'] = $r['Nama_Akun'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi'] = ($r['realisasi'] ?: 0);
            }
        } else {
            $raw_data        = $this->rp_apbd_widget($tahun, $opt = false);
            $res_pelaksanaan = [];
            $tmp_pelaksanaan = [];

            foreach ($raw_data['jenis_pelaksanaan'] as $r) {
                $tmp_pelaksanaan[$r['Akun']]['nama'] = $r['Nama_Akun'];
            }

            foreach ($raw_data['anggaran'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['anggaran'] = ($r['pagu'] ?: 0);
            }

            foreach ($raw_data['realisasi_pendapatan'] as $r) {
                $tmp_pelaksanaan[$r['jenis_pelaksanaan']]['realisasi_pendapatan'] = ($r['realisasi'] ?: 0);
            }
        }

        foreach ($tmp_pelaksanaan as $value) {
            if ($value['nama'] == 'PEMBIAYAAN') {
                $value['anggaran']             = $raw_data['pembiayaan'][0]['sub_pembiayaan'][0]['anggaran'][0]['pagu'] - $raw_data['pembiayaan'][0]['sub_pembiayaan_keluar'][0]['anggaran'][0]['pagu'];
                $value['realisasi_pendapatan'] = $raw_data['pembiayaan'][0]['sub_pembiayaan'][0]['realisasi'][0]['realisasi'] - $raw_data['pembiayaan'][0]['sub_pembiayaan_keluar'][0]['realisasi'][0]['realisasi'];
                // $value['realisasi'] = $raw_data['pembiayaan'][0]['sub_pembiayaan'][0]['realisasi'][0]['realisasi'] - $raw_data['pembiayaan'][0]['sub_pembiayaan_keluar'][0]['realisasi'][0]['realisasi'];
            }
            $res_pelaksanaan[] = $value;
        }

        return $res_pelaksanaan;
    }

    public function widget_keuangan()
    {
        $data = $this->keuangan_manual_model->list_tahun_anggaran_manual();

        foreach ($data as $tahun) {
            $res[$tahun]['res_pendapatan']  = $this->data_widget_pendapatan($tahun, $opt = true);
            $res[$tahun]['res_belanja']     = $this->data_widget_belanja($tahun, $opt = true);
            $res[$tahun]['res_pelaksanaan'] = $this->data_widget_pelaksanaan($tahun, $opt = true);
        }

        return [
            //Encode ke JSON
            'data'  => json_encode($res, JSON_THROW_ON_ERROR),
            'tahun' => $this->keuangan_manual_model->list_tahun_anggaran_manual_manual(),
            //Cari tahun anggaran terbaru (terbesar secara value)
            'tahun_terbaru' => $this->keuangan_manual_model->list_tahun_anggaran_manual()[0],
        ];
    }

    private function data_keuangan_tema($tahun)
    {
        $data['res_pelaksanaan']            = $this->data_widget_pelaksanaan($tahun, $opt = false);
        $data['res_pelaksanaan']['laporan'] = 'APBDes ' . $tahun . ' Pelaksanaan';
        $data['res_pendapatan']             = $this->data_widget_pendapatan($tahun, $opt = false);
        $data['res_pendapatan']['laporan']  = 'APBDes ' . $tahun . ' Pendapatan';
        $data['res_belanja']                = $this->data_widget_belanja($tahun, $opt = false);
        $data['res_belanja']['laporan']     = 'APBDes ' . $tahun . ' Pembelanjaan';

        return $data;
    }

    public function grafik_keuangan_tema($tahun = null)
    {
        if (null === $tahun) {
            $tahun = date('Y');
        }
        $thn = $this->keuangan_manual_model->list_tahun_anggaran_manual();
        if (empty($thn)) {
            return null;
        }

        if (! in_array($tahun, $thn)) {
            $tahun = $thn[0];
        }
        $raw_data = $this->data_keuangan_tema($tahun);

        foreach ($raw_data as $keys => $raws) {
            foreach ($raws as $key => $raw) {
                if ($key == 'laporan') {
                    $result['data_widget'][$keys]['laporan'] = $raw;

                    continue;
                }

                $data          = $this->keuangan_grafik_model->raw_perhitungan($raw);
                $data['judul'] = $raw['nama'];

                $result['data_widget'][$keys][] = $data;
            }
        }
        $result['tahun'] = $tahun;

        return $result;
    }

    /*
      lap_rp_apbd merupakan fungsi Akhir (Main) dari semua sub dan sub-sub fungsi :

      Sub fungsi Pendapatan
      1.1 sub-sub fungsi : Pagu Pendapatan
      1.2 sub-sub fungsi : Realisasi Pendapatan

      Sub fungsi Belanja
      2.1 sub-sub fungsi : Pagu Belanja
      2.2 sub-sub fungsi : Realisasi Belanja

      Sub fungsi Pembiayaan Masuk
      3.1 sub-sub fungsi : Pagu Pembiayaan Masuk
      3.1 sub-sub fungsi : Realisasi Pembiayaan Masuk

      Sub fungsi Pembiayaan Keluar
      4.1 sub-sub fungsi : Pagu Pembiayaan Keluar
      4.2 sub-sub fungsi : Realisasi Pembiayaan Keluar
    */

    //Table Laporan Pelaksanaan Realisasi
    public function lap_rp_apbd($thn)
    {
        $this->db->select('Akun, Nama_Akun');
        $this->db->where("Akun = '4.'");

        $data['pendapatan'] = $this->db->get('keuangan_manual_ref_rek1')->result_array();

        foreach ($data['pendapatan'] as $i => $p) {
            $data['pendapatan'][$i]['anggaran']       = $this->pagu_akun($p['Akun'], $thn);
            $data['pendapatan'][$i]['realisasi']      = $this->realisasi_akun($p['Akun'], $thn);
            $data['pendapatan'][$i]['sub_pendapatan'] = $this->get_subval_pendapatan($p['Akun'], $thn);
        }

        $this->db->select('Akun, Nama_Akun');
        $this->db->where("Akun = '5.'");

        $data['belanja'] = $this->db->get('keuangan_manual_ref_rek1')->result_array();

        foreach ($data['belanja'] as $i => $p) {
            $data['belanja'][$i]['anggaran']    = $this->pagu_akun($p['Akun'], $thn);
            $data['belanja'][$i]['realisasi']   = $this->realisasi_akun($p['Akun'], $thn);
            $data['belanja'][$i]['sub_belanja'] = $this->get_subval_belanja($p['Akun'], $thn);
        }

        $this->db->select('Kd_Bid, Nama_Bidang');

        $data['belanja_bidang'] = $this->db->get('keuangan_manual_ref_bidang')->result_array();

        foreach ($data['belanja_bidang'] as $i => $p) {
            $data['belanja_bidang'][$i]['anggaran']  = $this->pagu_akun_bidang($p['Kd_Bid'], $thn);
            $data['belanja_bidang'][$i]['realisasi'] = $this->real_akun_belanja_bidang($p['Kd_Bid'], $thn);
        }

        $this->db->select('Akun, Nama_Akun');
        $this->db->where("Akun = '6.'");

        $data['pembiayaan'] = $this->db->get('keuangan_manual_ref_rek1')->result_array();

        foreach ($data['pembiayaan'] as $i => $p) {
            $data['pembiayaan'][$i]['anggaran']       = $this->pagu_akun($p['Akun'], $thn);
            $data['pembiayaan'][$i]['realisasi']      = $this->realisasi_akun($p['Akun'], $thn);
            $data['pembiayaan'][$i]['sub_pembiayaan'] = $this->get_subval_pembiayaan($p['Akun'], $thn);
        }

        $this->db->select('Akun, Nama_Akun');
        $this->db->where("Akun = '6.'");

        $data['pembiayaan_keluar'] = $this->db->get('keuangan_manual_ref_rek1')->result_array();

        foreach ($data['pembiayaan_keluar'] as $i => $p) {
            $data['pembiayaan_keluar'][$i]['anggaran']              = $this->pagu_akun($p['Akun'], $thn);
            $data['pembiayaan_keluar'][$i]['realisasi']             = $this->realisasi_akun($p['Akun'], $thn);
            $data['pembiayaan_keluar'][$i]['sub_pembiayaan_keluar'] = $this->get_subval_pembiayaan_keluar($p['Akun'], $thn);
        }

        return $data;
    }

    private function pagu_akun($akun, $thn)
    {
        $this->config_id()->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai_Anggaran) AS pagu')
            ->like('Kd_Rincian', $akun, 'after')
            ->where('Tahun', $thn)
            ->group_by('Akun');

        return $this->db->get('keuangan_manual_rinci')->result_array();
    }

    private function pagu_akun_bidang($akun, $thn)
    {
        $this->db->select('LEFT(Kd_Keg, 10) AS Akun, SUM(Nilai_Anggaran) AS pagu')
            ->like('LEFT(Kd_Keg, 10)', $akun, 'after')
            ->where('Tahun', $thn)
            ->group_by('Akun');

        return $this->config_id()->get('keuangan_manual_rinci')->result_array();
    }

    private function realisasi_akun($akun, $thn = false)
    {
        $this->config_id()->select('LEFT(Kd_Rincian, 2) AS Akun, SUM(Nilai_Realisasi) AS realisasi')
            ->like('Kd_Rincian', $akun, 'after')
            ->where('keuangan_manual_rinci.Tahun', $thn)
            ->group_by('Akun');

        return $this->db->get('keuangan_manual_rinci')->result_array();
    }

    private function real_akun_belanja_bidang($akun, $thn = false)
    {
        $this->db->select('LEFT(Kd_Keg, 10) AS Akun, SUM(Nilai_Realisasi) AS realisasi')
            ->like('LEFT(Kd_Keg, 10)', $akun, 'after')
            ->where('keuangan_manual_rinci.Tahun', $thn)
            ->group_by('Akun');

        return $this->config_id()->get('keuangan_manual_rinci')->result_array();
    }

    private function get_subval_pendapatan($akun, $thn = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);

        $data = $this->db->get('keuangan_manual_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']        = $this->jumlah_pagu_subval($d['Kelompok'], $thn);
            $data[$i]['realisasi']       = $this->jumlah_realisasi_subval($d['Kelompok'], $thn);
            $data[$i]['sub_pendapatan2'] = $this->sub_pendapatan2($d['Kelompok'], $thn);
        }

        return $data;
    }

    private function get_subval_belanja($akun, $thn = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);

        $data = $this->db->get('keuangan_manual_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']     = $this->jumlah_pagu_subval($d['Kelompok'], $thn);
            $data[$i]['realisasi']    = $this->jumlah_realisasi_subval($d['Kelompok'], $thn);
            $data[$i]['sub_belanja2'] = $this->sub_belanja2($d['Kelompok'], $thn);
        }

        return $data;
    }

    private function get_subval_pembiayaan($akun, $thn = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);

        $this->db->where('Kelompok', '6.1.');
        $data = $this->db->get('keuangan_manual_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']        = $this->jumlah_pagu_subval($d['Kelompok'], $thn);
            $data[$i]['realisasi']       = $this->jumlah_realisasi_subval($d['Kelompok'], $thn);
            $data[$i]['sub_pembiayaan2'] = $this->sub_pembiayaan2($d['Kelompok'], $thn);
        }

        return $data;
    }

    private function get_subval_pembiayaan_keluar($akun, $thn = false)
    {
        $this->db->select('Kelompok, Nama_Kelompok');
        $this->db->where('Akun', $akun);

        $this->db->where('Kelompok', '6.2.');
        $data = $this->db->get('keuangan_manual_ref_rek2')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']               = $this->jumlah_pagu_subval($d['Kelompok'], $thn);
            $data[$i]['realisasi']              = $this->jumlah_realisasi_subval($d['Kelompok'], $thn);
            $data[$i]['sub_pembiayaan_keluar2'] = $this->sub_pembiayaan_keluar2($d['Kelompok'], $thn);
        }

        return $data;
    }

    private function jumlah_pagu_subval($kelompok, $thn)
    {
        $this->config_id()->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai_Anggaran) AS pagu')
            ->like('Kd_Rincian', $kelompok, 'after')
            ->where('Tahun', $thn)
            ->group_by('Kelompok');

        return $this->db->get('keuangan_manual_rinci')->result_array();
    }

    private function jumlah_realisasi_subval($kelompok, $thn = false)
    {
        $this->config_id()->select('LEFT(Kd_Rincian, 4) AS Kelompok, SUM(Nilai_Realisasi) AS realisasi')
            ->like('Kd_Rincian', $kelompok, 'after')
            ->where('keuangan_manual_rinci.Tahun', $thn)
            ->group_by('Kelompok');

        return $this->db->get('keuangan_manual_rinci')->result_array();
    }

    private function sub_pendapatan2($kelompok, $thn = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', $kelompok);

        $data = $this->db->get('keuangan_manual_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $thn);
            $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $thn);
        }

        return $data;
    }

    private function sub_belanja2($kelompok, $thn = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', $kelompok);

        $data = $this->db->get('keuangan_manual_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $thn);
            $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $thn);
        }

        return $data;
    }

    private function sub_pembiayaan2($kelompok, $thn = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', '6.1.');
        $this->db->where('Kelompok', $kelompok);

        $data = $this->db->get('keuangan_manual_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $thn);
            $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $thn);
        }

        return $data;
    }

    private function sub_pembiayaan_keluar2($kelompok, $thn = false)
    {
        $this->db->select('Kelompok, Jenis, Nama_Jenis');
        $this->db->where('Kelompok', '6.2.');
        $this->db->where('Kelompok', $kelompok);

        $data = $this->db->get('keuangan_manual_ref_rek3')->result_array();

        foreach ($data as $i => $d) {
            $data[$i]['anggaran']  = $this->jumlah_pagu($d['Jenis'], $thn);
            $data[$i]['realisasi'] = $this->jumlah_realisasi($d['Jenis'], $thn);
        }

        return $data;
    }

    private function jumlah_pagu($jenis, $thn)
    {
        $this->config_id()->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai_Anggaran) AS pagu')
            ->like('Kd_Rincian', $jenis, 'after')
            ->where('Tahun', $thn)
            ->group_by('Jenis');

        return $this->db->get('keuangan_manual_rinci')->result_array();
    }

    private function jumlah_realisasi($jenis, $thn = false)
    {
        $this->config_id()->select('LEFT(Kd_Rincian, 6) AS Jenis, SUM(Nilai_Realisasi) AS realisasi')
            ->like('Kd_Rincian', $jenis, 'after')
            ->where('keuangan_manual_rinci.Tahun', $thn)
            ->group_by('Jenis');

        return $this->db->get('keuangan_manual_rinci')->result_array();
    }
}
