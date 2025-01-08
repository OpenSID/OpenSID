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

use App\Libraries\Paging;
use App\Libraries\SpreadsheetExcelReader;

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_respon_model extends MY_Model
{
    protected $per;
    protected $master;
    protected $subjek;

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('Spreadsheet_Excel_Reader');
        $this->load->model('analisis_master_model');
        $this->per    = $this->analisis_master_model->get_aktif_periode();
        $this->master = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $this->subjek = $this->session->subjek_tipe;
    }

    public function autocomplete()
    {
        switch ($this->subjek) {
            case 1:
                $this->config_id('u')
                    ->select('nik, u.nama')
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
                break;

            case 2:
                $this->config_id('u')
                    ->select('no_kk, p.nama')
                    ->from('keluarga_aktif u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 3:
                $this->config_id('u')
                    ->select('no_kk, p.nama')
                    ->from('tweb_rtm u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 4:
                $this->config_id('u')
                    ->select('u.nama AS no_kk, p.nama')
                    ->from('kelompok u')
                    ->join('penduduk_hidup p', 'u.id_ketua = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 5:
                $this->db
                    ->select('u.kode_desa AS no_kk, u.nama_desa as nama')
                    ->from('config u')
                    ->where('u.app_key', get_app_key());
                break;

            case 6:
                $this->config_id('u')
                    ->select('u.dusun')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw', '0');
                break;

            case 7:
                $this->config_id('u')
                    ->select('u.dusun, u.rw')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw <>', '0');
                break;

            case 8:
                $this->config_id('u')
                    ->select('u.dusun, u.rw, u.rt')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt <> 0')
                    ->where('u.rt <> "-"');
                break;
        }
        $this->dusun_sql();
        $this->rw_sql();
        $this->rt_sql();
        $data = $this->db->get()->result_array();

        return autocomplete_data_ke_str($data);
    }

    private function search_sql()
    {
        if (empty($cari = $this->session->cari)) {
            return;
        }

        switch ($this->subjek) {
            case 1:
                $this->db
                    ->group_start()
                    ->like('u.nik', $cari)
                    ->or_like('u.nama', $cari)
                    ->group_end();
                break;

            case 2:
                $this->db
                    ->group_start()
                    ->like('u.no_kk', $cari)
                    ->or_like('p.nama', $cari)
                    ->group_end();
                break;

            case 3:
                $kw = $this->db->escape_like_str($cari);
                $kw = '%' . $kw . '%';
                $this->db
                    ->group_start()
                    ->group_start()
                    ->like('u.no_kk', $cari)
                    ->or_like('p.nama', $cari)
                    ->group_end()
                    ->or_where("(SELECT COUNT(id) FROM penduduk_hidup WHERE nik LIKE '{$kw}' AND id_rtm = u.id AND config_id = '{$this->config_id}') > 1")
                    ->or_where("(SELECT COUNT(id) FROM penduduk_hidup WHERE nama LIKE '{$kw}' AND id_rtm = u.id AND config_id = '{$this->config_id}') > 1")
                    ->group_end();
                break;

            case 4:
                $this->db
                    ->group_start()
                    ->like('u.nama', $cari)
                    ->or_like('p.nama', $cari)
                    ->group_end();
                break;

            case 6:
                $this->db
                    ->like('u.dusun', $cari);
                break;

            case 7:
                $this->db
                    ->group_start()
                    ->like('u.dusun', $cari)
                    ->or_like('u.rw', $cari)
                    ->group_end();
                break;

            case 8:
                $this->db
                    ->group_start()
                    ->like('u.dusun', $cari)
                    ->or_like('u.rw', $cari)
                    ->or_like('u.rt', $cari)
                    ->group_end();
                break;

            default:
                return null;
        }
    }

    private function dusun_sql(): void
    {
        if (empty($this->session->dusun) || $this->subjek == 5) {
            return;
        }

        $this->db->where('dusun', $this->session->dusun);
    }

    private function rw_sql(): void
    {
        if (empty($this->session->rw) || $this->subjek == 5) {
            return;
        }

        $this->db->where('rw', $this->session->rw);
    }

    private function rt_sql(): void
    {
        if (empty($this->session->rt) || $this->subjek == 5) {
            return;
        }

        $this->db->where('rt', $this->session->rt);
    }

    // Pertanyaan telah diisi atau belum
    // $this->session->isi == 1 untuk pertanyaan yg telah diisi
    private function isi_sql(): void
    {
        if (empty($isi = $this->session->isi)) {
            return;
        }

        $isi = $isi == 1 ? 1 : 0;
        $this->db
            ->where("(SELECT COUNT(id_subjek) FROM analisis_respon_hasil WHERE id_subjek = u.id AND id_periode = {$this->per} AND config_id = '{$this->config_id}') = {$isi}");
    }

    private function kelompok_sql($kf = 0): void
    {
        $this->db->where('id_master', $kf);
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $jml_data = $this->db
            ->select('COUNT(*) AS jml_data')
            ->get()
            ->row()
            ->jml_data;

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    private function list_data_sql()
    {
        $id_kelompok = $this->master['id_kelompok'];

        switch ($this->subjek) {
            case 1:
                $this->config_id('u')
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
                break;

            case 2:
                $this->config_id('u')
                    ->from('keluarga_aktif u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 3:
                $this->config_id('u')
                    ->from('tweb_rtm u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 4:
                $this->config_id('u')
                    ->from('kelompok u')
                    ->join('penduduk_hidup p', 'u.id_ketua = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 5:
                $this->db
                    ->from('config u')
                    ->where('u.app_key', get_app_key());
                break;

            case 6:
                $this->config_id('u')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw', '0');
                break;

            case 7:
                $this->config_id('u')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw <>', '0')
                    ->where('u.rw <>', '-');
                break;

            case 8:
                $this->config_id('u')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt <> 0')
                    ->where('u.rt <> "-"');
                break;

            default:
                return null;
        }
        if ($id_kelompok != 0) {
            $this->kelompok_sql($id_kelompok);
        }

        $this->search_sql();
        $this->dusun_sql();
        $this->rw_sql();
        $this->rt_sql();
        $this->isi_sql();
    }

    public function list_data($order = 0, $offset = 0, $limit = 500)
    {
        $settingDusun = setting('sebutan_dusun'); // Fetch setting value outside the query

        $this->db->select("(SELECT a.id_subjek FROM analisis_respon a WHERE a.id_subjek = u.id AND a.id_periode = {$this->per} LIMIT 1) as cek")
            ->select("(SELECT b.pengesahan FROM analisis_respon_bukti b WHERE b.id_master = {$this->master['id']} AND b.id_periode = {$this->per} AND b.id_subjek = u.id AND b.config_id = {$this->config_id}) as bukti_pengesahan");

        switch ($this->subjek) {
            case 1:
                $this->db->select('u.id, u.nik AS nid, u.nama, u.sex, c.dusun, c.rw, c.rt');
                break;

            case 2:
            case 3:
                $this->db->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 4:
                $this->db->select('u.id, u.kode AS nid, u.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 5:
                $this->db->select('u.id, u.kode_desa as nid, u.nama_desa as nama, "-" as sex, "-" as dusun, "-" as rw, "-" as rt');
                break;

            case 6:
                $this->db->select("u.id, u.dusun AS nid, CONCAT(UPPER('{$settingDusun} '), u.dusun) as nama, '-' as sex, u.dusun, '-' as rw, '-' as rt");
                break;

            case 7:
                $this->db->select("u.id, u.rw AS nid, CONCAT(UPPER('{$settingDusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt");
                break;

            case 8:
                $this->db->select("u.id, u.rt AS nid, CONCAT(UPPER('{$settingDusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt");
                break;

            default:
                return null;
        }

        $this->list_data_sql();

        switch ($order) {
            case 1:
                $this->db->order_by('u.id');
                break;

            case 2:
                $this->db->order_by('u.id DESC');
                break;

            case 3:
                $this->db->order_by('nama');
                break;

            case 4:
                $this->db->order_by('nama DESC');
                break;

            default:
                $this->db->order_by('u.id');
        }

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $data    = $this->db->get()->result_array();
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']     = $j + 1;
            $data[$i]['set']    = '<img src="' . base_url('assets/images/icon/') . ($data[$i]['cek'] ? 'ok' : 'nok') . '.png">';
            $data[$i]['jk']     = ($data[$i]['sex'] == 1) ? 'L' : 'P';
            $data[$i]['alamat'] = $data[$i]['dusun'] . ' RW-' . $data[$i]['rw'] . ' RT-' . $data[$i]['rt'];
            $j++;
        }

        return $data;
    }

    public function data_unduh($p, $o)
    {
        $per = $this->analisis_master_model->get_aktif_periode();

        // Fetch setting for 'sebutan_dusun' once
        $settingDusun = setting('sebutan_dusun');

        // Define base selection fields for different cases
        $baseSelectFields = [
            1 => 'u.id, u.nik AS nid, u.nama, u.sex, c.dusun, c.rw, c.rt',
            2 => 'u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt',
            3 => 'u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt',
            4 => 'u.id, u.kode AS nid, u.nama, p.sex, c.dusun, c.rw, c.rt',
            5 => 'u.id, u.kode_desa as nid, u.nama_desa as nama, "-" as sex, "-" as dusun, "-" as rw, "-" as rt',
        ];

        switch ($this->subjek) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                // Common case for subjek 1-5
                $this->db->select($baseSelectFields[$this->subjek]);
                break;

            case 6:
                $this->db->select("u.id, u.dusun AS nid, CONCAT(UPPER('{$settingDusun} '), u.dusun) as nama, '-' as sex, u.dusun, '-' as rw, '-' as rt");
                break;

            case 7:
                $this->db->select("u.id, u.rw AS nid, CONCAT(UPPER('{$settingDusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt");
                break;

            case 8:
                $this->db->select("u.id, u.rt AS nid, CONCAT(UPPER('{$settingDusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt");
                break;

            default:
                return null;
        }

        $this->list_data_sql();

        switch ($o) {
            case 1:
                $this->db->order_by('u.id');
                break;

            case 2:
                $this->db->order_by('u.id DESC');
                break;

            case 3:
                $this->db->order_by('nama');
                break;

            case 4:
                $this->db->order_by('nama DESC');
                break;

            default:
                $this->db->order_by('u.id');
        }

        $data    = $this->db->get()->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
            if ($p == 1) {
                $par = $this->db
                    ->select('kode_jawaban, asign, jawaban, r.id_indikator, r.id_parameter AS korek')
                    ->from('analisis_respon r')
                    ->join('analisis_parameter p', 'p.id = r.id_parameter', 'left')
                    ->where('r.id_periode', $per)
                    ->where('r.id_subjek', $data[$i]['id'])
                    ->order_by('r.id_indikator')
                    ->get()
                    ->result_array();
                $data[$i]['par'] = $par;
            } else {
                $data[$i]['par'] = null;
            }

            $data[$i]['jk'] = ($data[$i]['sex'] == 1) ? 'L' : 'P';
        }

        return $data;
    }

    public function update_kuisioner($id = 0, $per = 0): void
    {
        $outp                     = true;
        $this->session->error_msg = '';
        if ($per == 0) {
            $per       = $this->analisis_master_model->get_aktif_periode();
            $id_master = $_SESSION['analisis_master'];
        } else {
            $id_master = $this->config_id()->get_where('analisis_periode', ['id' => $per])->row_array();
            $id_master = $id_master['id_master'];
        }
        $ia = 0;
        $it = 0;
        $ir = 0;
        $ic = 0;

        if (isset($_POST['rb'])) {
            $id_rbx = $_POST['rb'];

            foreach ($id_rbx as $id_px) {
                if ($id_px != '') {
                    $ir = 1;
                }
            }
        }
        if (isset($_POST['cb'])) {
            $id_rby = $_POST['cb'];

            foreach ($id_rby as $id_py) {
                if ($id_py != '') {
                    $ic = 1;
                }
            }
        }
        if (isset($_POST['ia'])) {
            $id_iax = $_POST['ia'];

            foreach ($id_iax as $id_px) {
                if ($id_px != '') {
                    $ia = 1;
                }
            }
        }
        if (isset($_POST['it'])) {
            $id_iay = $_POST['it'];

            foreach ($id_iay as $id_py) {
                if ($id_py != '') {
                    $it = 1;
                }
            }
        }

        //CEK ada input
        if ($ir != 0 || $ic != 0 || $ia != 0 || $it != 0) {
            $this->db->where('id_subjek', $id)->where('id_periode', $per)->delete('analisis_respon');
            if (! empty($_POST['rb'])) {
                $id_rb = $_POST['rb'];

                foreach ($id_rb as $id_p) {
                    if (empty($id_p)) {
                        continue;
                    } // Abaikan isian kosong
                    $p = preg_split('/\\./', $id_p);

                    $data['id_subjek']    = $id;
                    $data['id_periode']   = $per;
                    $data['id_indikator'] = $p[0];
                    $data['id_parameter'] = $p[1];
                    $outp &= $this->db->insert('analisis_respon', $data);
                }
            }
            if (isset($_POST['cb'])) {
                $id_cb = $_POST['cb'];
                if ($id_cb) {
                    foreach ($id_cb as $id_p) {
                        $p = preg_split('/\\./', $id_p);

                        $data['id_subjek']    = $id;
                        $data['id_periode']   = $per;
                        $data['id_indikator'] = $p[0];
                        $data['id_parameter'] = $p[1];
                        $outp &= $this->db->insert('analisis_respon', $data);
                    }
                }
            }

            if (isset($_POST['ia'])) {
                $id_ia = $_POST['ia'];

                foreach ($id_ia as $id_p) {
                    if ($id_p != '') {
                        unset($data);
                        $indikator = key($id_ia);
                        $dx        = $this->config_id()
                            ->get_where('analisis_parameter', ['jawaban' => $id_p, 'id_indikator' => $indikator])
                            ->row_array();
                        if (! $dx) {
                            $data['id_indikator'] = $indikator;
                            $data['jawaban']      = $id_p;
                            $data['config_id']    = $this->config_id;
                            $outp &= $this->db->insert('analisis_parameter', $data);
                            unset($data);
                            $dx = $this->config_id()
                                ->get_where('analisis_parameter', ['jawaban' => $id_p, 'id_indikator' => $indikator])
                                ->row_array();
                            $data['id_parameter'] = $dx['id'];
                            $data['id_indikator'] = $indikator;
                            $data['id_subjek']    = $id;
                            $data['id_periode']   = $per;
                            $outp &= $this->db->insert('analisis_respon', $data);
                        } else {
                            unset($data);
                            $data['id_indikator'] = $indikator;
                            $data['id_parameter'] = $dx['id'];
                            $data['id_subjek']    = $id;
                            $data['id_periode']   = $per;
                            $outp &= $this->db->insert('analisis_respon', $data);
                        }
                    }
                    next($id_ia);
                }
            }
            if (isset($_POST['it'])) {
                $id_it = $_POST['it'];

                foreach ($id_it as $id_p) {
                    if ($id_p != '') {
                        unset($data);
                        $indikator = key($id_it);
                        $dx        = $this->config_id()
                            ->get_where('analisis_parameter', ['jawaban' => $id_p, 'id_indikator' => $indikator])
                            ->row_array();
                        if (! $dx) {
                            $data['id_indikator'] = $indikator;
                            $data['jawaban']      = $id_p;
                            $data['config_id']    = $this->config_id;
                            $outp &= $this->db->insert('analisis_parameter', $data);
                            unset($data);
                            $dx = $this->config_id()
                                ->get_where('analisis_parameter', ['jawaban' => $id_p, 'id_indikator' => $indikator])
                                ->row_array();
                            $data2['id_parameter'] = $dx['id'];
                            $data2['id_indikator'] = $indikator;
                            $data2['id_subjek']    = $id;
                            $data2['id_periode']   = $per;
                            $outp &= $this->db->insert('analisis_respon', $data2);
                        } else {
                            unset($data);
                            $data['id_indikator'] = $indikator;
                            $data['id_parameter'] = $dx['id'];
                            $data['id_subjek']    = $id;
                            $data['id_periode']   = $per;
                            $outp &= $this->db->insert('analisis_respon', $data);
                        }
                    }
                    next($id_it);
                }
            }

            $sql   = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=? ';
            $query = $this->db->query($sql, [$id, $per]);
            $dx    = $query->row_array();

            $upx['id_master']  = $id_master;
            $upx['akumulasi']  = 0 + $dx['jml'];
            $upx['id_subjek']  = $id;
            $upx['id_periode'] = $per;
            $upx['config_id']  = $this->config_id;
            $this->config_id()->where('id_subjek', $id)->where('id_periode', $per)->delete('analisis_respon_hasil');
            $outp &= $this->db->insert('analisis_respon_hasil', $upx);
        }
        if (isset($_FILES['pengesahan'])) {
            $lokasi_file = $_FILES['pengesahan']['tmp_name'];
            $tipe_file   = $_FILES['pengesahan']['type'];
            if (! empty($lokasi_file)) {
                if ($tipe_file != 'image/jpeg' && $tipe_file != 'image/pjpeg') {
                    $_SESSION['sukses'] = -1;
                } else {
                    $nama_file = $_SESSION['analisis_master'] . '_' . $per . '_' . $id . '_' . random_int(10000, 99999) . '.jpg';
                    UploadPengesahan($nama_file);
                    $bukti['pengesahan'] = $nama_file;
                    $bukti['id_master']  = $id_master;
                    $bukti['id_subjek']  = $id;
                    $bukti['id_periode'] = $per;
                    $bukti['config_id']  = identitas('id');

                    $ada_bukti = $this->config_id()->where(['id_master' => $id_master, 'id_subjek' => $id, 'id_periode' => $per])->get('analisis_respon_bukti')->num_rows();
                    if ($ada_bukti > 0) {
                        $outp = $this->config_id()->where(['id_master' => $id_master, 'id_subjek' => $id, 'id_periode' => $per])->update('analisis_respon_bukti', $bukti);
                    } else {
                        $outp &= $this->db->insert('analisis_respon_bukti', $bukti);
                    }
                }
            }
        }
        status_sukses($outp);
    }

    private function list_jawab2($id = 0, $in = 0, $per = 0)
    {
        if (isset($this->session->delik)) {
            $query = $this->config_id('s')
                ->select('s.id as id_parameter,s.jawaban,s.kode_jawaban')
                ->where('id_indikator', $in)
                ->order_by('s.kode_jawaban', 'ASC')
                ->get('analisis_parameter s');
        } else {
            $query = $this->config_id('s')
                ->select('s.id as id_parameter,s.jawaban,s.kode_jawaban')
                ->select('(SELECT count(id_subjek) FROM analisis_respon WHERE id_parameter = s.id AND id_subjek =' . $id . ' AND id_periode=' . $per . ') as cek')
                ->where('id_indikator', $in)
                ->order_by('s.kode_jawaban', 'ASC')
                ->get('analisis_parameter s');
        }

        $data    = $query->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
            if (isset($this->session->delik)) {
                $data[$i]['cek'] = 0;
            }
        }

        return $data;
    }

    private function list_jawab3($id = 0, $in = 0, $per = 0)
    {
        return $this->db
            ->select('s.id as id_parameter,s.jawaban')
            ->from('analisis_respon r')
            ->join('analisis_parameter s', 'r.id_parameter = s.id', 'left')
            ->where('r.id_indikator', $in)
            ->where('r.id_subjek', $id)
            ->where('r.id_periode', $per)
            ->get()
            ->row_array();
    }

    public function list_indikator($id = 0)
    {
        $per = $this->analisis_master_model->get_aktif_periode();

        $data = $this->db
            ->select('u.id, u.id_kategori, u.nomor, u.id_tipe, u.pertanyaan, u.referensi, k.kategori')
            ->from('analisis_indikator u')
            ->join('analisis_kategori_indikator k', 'u.id_kategori = k.id', 'left')
            ->where('u.id_master', $this->session->analisis_master)
            ->order_by("LPAD(u.nomor, 10, ' ') ASC")
            ->get()
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;

            if ($data[$i]['id_tipe'] == 1 || $data[$i]['id_tipe'] == 2) {
                $data[$i]['parameter_respon'] = $this->list_jawab2($id, $data[$i]['id'], $per);
            } else {
                $data[$i]['parameter_respon'] = (isset($this->session->delik)) ? '' : $this->list_jawab3($id, $data[$i]['id'], $per);
            }
        }

        return $data;
    }

    //CHILD-----------------------

    private function list_jawab4($id = 0, $in = 0, $per = 0)
    {
        if (isset($this->session->delik)) {
            $query = $this->config_id('s')
                ->select('s.id as id_parameter,s.jawaban,s.kode_jawaban')
                ->where('id_indikator', $in)
                ->get('analisis_parameter s');
        } else {
            $query = $this->config_id('s')
                ->select('s.id as id_parameter,s.jawaban,s.kode_jawaban')
                ->select('(SELECT count(id_subjek) FROM analisis_respon WHERE id_parameter = s.id AND id_subjek =' . $id . ' AND id_periode=' . $per . ') as cek')
                ->where('id_indikator', $in)
                ->get('analisis_parameter s');
        }
        $data    = $query->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
            if (isset($this->session->delik)) {
                $data[$i]['cek'] = 0;
            }
        }

        return $data;
    }

    private function list_jawab5($id = 0, $in = 0, $per = 0)
    {
        return $this->db
            ->select('s.id as id_parameter,s.jawaban')
            ->from('analisis_respon r')
            ->join('analisis_parameter s', 'r.id_parameter = s.id', 'left')
            ->where('r.id_indikator', $in)
            ->where('r.id_subjek', $id)
            ->where('r.id_periode', $per)
            ->get()
            ->row_array();
    }

    public function list_indikator_child($id = 0)
    {
        $id_child = $this->config_id()
            ->select('id_child')
            ->where('id', $_SESSION['analisis_master'])
            ->get('analisis_master')
            ->row_array();
        $id_child = $id_child['id_child'];

        $per = $this->config_id()
            ->select('id')
            ->where('id_master', $id_child)
            ->where('aktif', 1)
            ->get('analisis_periode')
            ->row_array();
        $per = $per['id'];

        $data = $this->config_id('u')
            ->where('u.id_master', $id_child)
            ->order_by('u.nomor')
            ->get('analisis_indikator u')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;

            if ($data[$i]['id_tipe'] == 1 || $data[$i]['id_tipe'] == 2) {
                $data[$i]['parameter_respon'] = $this->list_jawab4($id, $data[$i]['id'], $per);
            } else {
                $data[$i]['parameter_respon'] = (isset($this->session->delik)) ? '' : $this->list_jawab5($id, $data[$i]['id'], $per);
            }
        }

        return $data;
    }

    public function get_periode_child()
    {
        $id_child = $this->config_id()
            ->select('id_child')
            ->where('id', $_SESSION['analisis_master'])
            ->get('analisis_master')
            ->row_array();
        $id_child = $id_child['id_child'];

        $per = $this->config_id()
            ->select('id')
            ->where('id_master', $id_child)
            ->where('aktif', 1)
            ->get('analisis_periode')
            ->row_array();

        return $per['id'];
    }
    //---------------------------

    public function list_bukti($id = 0)
    {
        $per = $this->analisis_master_model->get_aktif_periode();

        return $this->config_id()
            ->select('pengesahan')
            ->where('id_subjek', $id)
            ->where('id_master', $_SESSION['analisis_master'])
            ->where('id_periode', $per)
            ->order_by('tgl_update', 'DESC')
            ->get('analisis_respon_bukti')
            ->result_array();
    }

    public function get_subjek($id = 0)
    {
        $sebutan_dusun = ucwords(setting('sebutan_dusun'));

        switch ($this->subjek) {
            case 1:
                $this->config_id('u')
                    ->select('u.*, u.nik AS nid, c.dusun, c.rw, c.rt')
                    ->select("CONCAT('{$sebutan_dusun} ', c.dusun, ', RT ', c.rt, ' / RW ', c.rw) as wilayah")
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
                break;

            case 2:
                $this->config_id('u')
                    ->select('u.*, u.no_kk AS nid, p.nik AS nik_kepala, p.nama, p.sex, c.dusun, c.rw, c.rt')
                    ->select("CONCAT('{$sebutan_dusun} ', c.dusun, ', RT ', c.rt, ' / RW ', c.rw) as wilayah")
                    ->from('keluarga_aktif u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');

                break;

            case 3:
                $this->config_id('u')
                    ->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt')
                    ->from('tweb_rtm u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 4:
                $this->config_id('u')
                    ->select('u.nama AS no_kk, p.nama')
                    ->from('kelompok u')
                    ->join('penduduk_hidup p', 'u.id_ketua = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 5:
                $this->db
                    ->select("u.id, u.kode_desa AS nid, u.nama_desa as nama, '-' as sex, '-' as dusun, '-' as rw, '-' as rt")
                    ->select("
                        u.nama_desa, u.kode_desa, u.kode_pos, u.alamat_kantor, u.telepon as no_telepon_kantor_desa, u.email_desa, CONCAT('Lintang : ', u.lat, ', ', 'Bujur : ', u.lng) as titik_koordinat_desa")
                    ->select('
                        c.pamong_nip AS nip_kepala_desa,
                        (case when p.sex is not null then p.sex else c.pamong_sex end) as jk_kepala_desa,
                        (case when p.pendidikan_kk_id is not null then b.nama else c.pamong_pendidikan end) as pendidikan_kepala_desa,
                        (case when p.nama is not null then p.nama else c.pamong_nama end) AS nama_kepala_desa,
                        p.telepon as no_telepon_kepala_desa
                    ')
                    ->from('config u')
                    ->join('tweb_desa_pamong c', 'u.pamong_id = c.pamong_id', 'left')
                    ->join('tweb_penduduk p', 'c.id_pend = p.id', 'left')
                    ->join('tweb_penduduk_pendidikan_kk b', 'p.pendidikan_kk_id = b.id', 'LEFT')
                    ->join('tweb_penduduk_sex x', 'p.sex = x.id', 'LEFT')
                    ->join('tweb_penduduk_pendidikan_kk b2', 'c.pamong_pendidikan = b2.id', 'LEFT')
                    ->join('tweb_penduduk_sex x2', 'c.pamong_sex = x2.id', 'LEFT')
                    ->where('u.app_key', get_app_key());

                break;

            case 6:
                $this->config_id('u')
                    ->select("u.id, u.dusun AS nid, UPPER('{$sebutan_dusun}') as nama, '-' as sex, u.dusun, '-' as rw, '-' as rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw', '0');
                break;

            case 7:
                $this->config_id('u')
                    ->select("u.id, u.rw AS nid, CONCAT( UPPER('{$sebutan_dusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw <>', '0');
                break;

            case 8:
                $this->config_id('u')
                    ->select("u.id, u.rt AS nid, CONCAT( UPPER('{$sebutan_dusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt <> 0')
                    ->where('u.rt <> "-"');
                break;

            default:
                return null;
        }
        $data = $this->db
            ->where('u.id', $id)
            ->limit(1)
            ->get()
            ->row_array();

        // Data tambahan subjek desa
        if ($this->subjek == 5) {
            $tambahan = [
                'jumlah_total_penduduk'            => $this->config_id()->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_laki_laki'        => $this->config_id()->where('sex', 1)->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_perempuan'        => $this->config_id()->where('sex', 2)->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_pedatang'         => $this->config_id()->where('status', 2)->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_yang_pergi'       => $this->config_id()->where('kode_peristiwa', 3)->count_all_results('log_penduduk'),
                'jumlah_total_kepala_keluarga'     => $this->config_id('u')->join('penduduk_hidup t', 'u.nik_kepala = t.id', 'left')->count_all_results('keluarga_aktif u'),
                'jumlah_kepala_keluarga_laki_laki' => $this->config_id('u')->join('penduduk_hidup t', 'u.nik_kepala = t.id', 'left')->where('sex', 1)->count_all_results('keluarga_aktif u'),
                'jumlah_kepala_keluarga_perempuan' => $this->config_id('u')->join('penduduk_hidup t', 'u.nik_kepala = t.id', 'left')->where('sex', 2)->count_all_results('keluarga_aktif u'),
                'jumlah_peserta_bpjs'              => $this->config_id()->where('bpjs_ketenagakerjaan != ', null)->count_all_results('penduduk_hidup'),
            ];

            $data = array_merge($data, $tambahan);
        }

        return $data;
    }

    public function list_anggota($id = 0)
    {
        $subjek = $this->subjek;
        if ($subjek == 2 || $subjek == 3) {
            switch ($subjek) {
                case 2:
                    return $this->config_id('u')
                        ->where('u.id_kk', $id)
                        ->order_by('kk_level')
                        ->get('penduduk_hidup u')
                        ->result_array();

                case 3:
                    return $this->config_id('u')
                        ->where('u.id_rtm', $id)
                        ->order_by('rtm_level')
                        ->get('penduduk_hidup u')
                        ->result_array();

                default:
                    return null;
            }
        }

        return null;
    }

    public function aturan_unduh()
    {
        $data = $this->config_id('u')
            ->select('u.*, t.tipe AS tipe_indikator, k.kategori AS kategori')
            ->from('analisis_indikator u')
            ->join('analisis_tipe_indikator t', 'u.id_tipe = t.id', 'left')
            ->join('analisis_kategori_indikator k', 'u.id_kategori = k.id', 'left')
            ->where('u.id_master', $_SESSION['analisis_master'])
            ->order_by('u.nomor')
            ->get()
            ->result_array();

        $this->analisis_master_model->get_aktif_periode();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;

            if ($data[$i]['id_tipe'] == 1 || $data[$i]['id_tipe'] == 2) {
                $data[$i]['par'] = $this->config_id('i')
                    ->select('i.id, i.kode_jawaban, i.jawaban')
                    ->from('analisis_parameter i')
                    ->where('i.id_indikator', $data[$i]['id'])
                    ->order_by('i.kode_jawaban')
                    ->get()
                    ->result_array();
            } else {
                $data[$i]['par'] = null;
            }
            $data[$i]['act_analisis'] = $data[$i]['act_analisis'] == 1 ? 'Ya' : 'Tidak';
        }

        return $data;
    }

    public function indikator_data_unduh()
    {
        $data = $this->config_id('u')
            ->where('u.id_master', $_SESSION['analisis_master'])
            ->order_by('u.nomor')
            ->get('analisis_indikator u')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']  = $i + 1;
            $data[$i]['par'] = null;
            $data[$i]['par'] = $this->config_id()
                ->select('id_parameter')
                ->where('id_indikator', $data[$i]['id'])
                ->where('asign', 1)
                ->get('analisis_parameter')
                ->result_array();
        }

        return $data;
    }

    public function indikator_unduh($p = 0)
    {
        $data = $this->config_id('u')
            ->where('u.id_master', $this->session->analisis_master)
            ->order_by('LPAD(u.nomor, 10, " ")')
            ->get('analisis_indikator u')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']  = $i + 1;
            $data[$i]['par'] = null;

            if ($p == 2) {
                $par = $this->config_id()
                    ->where('id_indikator', $data[$i]['id'])
                    ->where('asign', 1)
                    ->get('analisis_parameter')
                    ->result_array();
                $data[$i]['par'] = $par;
            }
        }

        return $data;
    }

    public function pre_update($pr = 0): void
    {
        $per = $pr == 0 ? $this->analisis_master_model->get_aktif_periode() : $pr;

        $sql   = 'SELECT DISTINCT(id_subjek) AS id FROM analisis_respon WHERE id_periode = ? ';
        $query = $this->db->query($sql, $per);
        $data  = $query->result_array();

        $sql = 'DELETE FROM analisis_respon_hasil WHERE id_subjek = 0  AND config_id=' . identitas('id');
        $this->db->query($sql);

        $sql = 'DELETE FROM analisis_respon WHERE id_subjek = 0';
        $this->db->query($sql);

        $sql = 'DELETE FROM analisis_respon_hasil WHERE id_periode = ? AND config_id=' . identitas('id');
        $this->db->query($sql, $per);
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $sql   = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=?';
            $query = $this->db->query($sql, [$data[$i]['id'], $per]);
            $dx    = $query->row_array();

            $upx[$i]['id_master']  = $_SESSION['analisis_master'];
            $upx[$i]['akumulasi']  = 0 + $dx['jml'];
            $upx[$i]['id_subjek']  = $data[$i]['id'];
            $upx[$i]['id_periode'] = $per;
            $upx[$i]['config_id']  = $this->config_id;
        }
        if (@$upx) {
            $this->db->insert_batch('analisis_respon_hasil', $upx);
        }
    }

    public function update_hasil($id = 0): void
    {
        $per = $this->analisis_master_model->get_aktif_periode();

        $sql   = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis = 1 AND r.id_periode = ? ';
        $query = $this->db->query($sql, [$id, $per]);
        $dx    = $query->row_array();

        $upx['id_master']  = $_SESSION['analisis_master'];
        $upx['akumulasi']  = 0 + $dx['jml'];
        $upx['id_subjek']  = $id;
        $upx['id_periode'] = $per;
        $upx['config_id']  = $this->config_id;

        $this->config_id()
            ->where('id_subjek', $id)
            ->where('id_periode', $per)
            ->delete('analisis_respon_hasil');
        $this->db->insert('analisis_respon_hasil', $upx);
    }

    public function import_respon($op = 0)
    {
        $per = $this->analisis_master_model->get_aktif_periode();

        $subjek = $this->subjek;
        $mas    = $_SESSION['analisis_master'];
        $key    = ($per + 3) * ($mas + 7) * ($subjek * 3);
        $key    = 'AN' . $key;
        $respon = [];

        $indikator = $this->config_id()
            ->where('id_master', $_SESSION['analisis_master'])
            ->order_by('id')
            ->get('analisis_indikator')
            ->result_array();

        try {
            if ($_FILES['respon']['type'] != 'application/vnd.ms-excel') {
                return [
                    'success' => false,
                    'message' => 'File yang diunggah harus berformat .xls',
                ];
            }
            $data  = new SpreadsheetExcelReader($_FILES['respon']['tmp_name']);
            $s     = 0;
            $baris = $data->rowcount($s);
            $kolom = $data->colcount($s);

            $ketemu = 0;

            for ($b = 1; $b <= $baris; $b++) {
                for ($k = 1; $k <= $kolom; $k++) {
                    $isi = $data->val($b, $k, $s);
                    // ketemu njuk stop
                    if ($isi == $key) {
                        $br = $b + 1;
                        $kl = $k + 1;

                        $b      = $baris + 1;
                        $k      = $kolom + 1;
                        $ketemu = 1;
                    }
                }
            }
            if ($ketemu == 1) {
                $dels = '';
                $true = 0;

                for ($i = $br; $i <= $baris; $i++) {
                    $id_subjek = $data->val($i, $kl - 1, $s);

                    $j = $kl;

                    foreach ($indikator as $indi) {
                        $isi = $data->val($i, $j, $s);
                        if ($isi != '') {
                            $true = 1;
                        }

                        $j++;
                    }
                    if ($true == 1) {
                        $dels .= $id_subjek . ',';
                        $true = 0;
                    }
                }

                $dels .= '9999999';
                //cek ada row
                $this->db->where("id_subjek in({$dels})")->where('id_periode', $per)->delete('analisis_respon');
                $dels = '';

                for ($i = $br; $i <= $baris; $i++) {
                    $id_subjek = $data->val($i, $kl - 1, $s);
                    if (strlen($id_subjek) > 14 && $subjek == 1) {
                        $isbj = $this->config_id()
                            ->where('nik', $id_subjek)
                            ->get('penduduk_hidup')
                            ->row_array();
                        $id_subjek = $isbj['id'];
                    } elseif ($subject == 3) {
                        // sasaran rumah tangga, simpan id, bukan nomor rumah tangga
                        $id_subjek = $this->db->select('id')
                            ->where('id_rtm', $id_subjek)
                            ->get('tweb_rtm')
                            ->row()
                            ->id;
                    }

                    $j   = $kl + $op;
                    $all = '';

                    foreach ($indikator as $indi) {
                        $isi = $data->val($i, $j, $s);
                        if ($isi != '') {
                            if ($indi['id_tipe'] == 1) {
                                $param = $this->config_id()
                                    ->where('id_indikator', $indi['id'])
                                    ->group_start()
                                    ->where('kode_jawaban', $isi)
                                    ->or_where('jawaban', $isi)
                                    ->group_end()
                                    ->get('analisis_parameter')
                                    ->row_array();

                                if ($param) {
                                    $in_param = $param['id'];
                                } elseif ($isi == '') {
                                    $in_param = 0;
                                } else {
                                    $in_param = -1;
                                }

                                $respon[] = [
                                    'id_parameter' => $in_param,
                                    'id_indikator' => $indi['id'],
                                    'id_subjek'    => $id_subjek,
                                    'id_periode'   => $per,
                                ];
                            } elseif ($indi['id_tipe'] == 2) {
                                $this->respon_checkbox($indi, $isi, $id_subjek, $per, $respon);
                            } else {
                                $param = $this->config_id()
                                    ->where('id_indikator', $indi['id'])
                                    ->where('jawaban', $isi)
                                    ->get('analisis_parameter')
                                    ->row_array();

                                // apakah sdh ada jawaban yg sama
                                if ($param) {
                                    $in_param = $param['id'];
                                } else {
                                    $parameter['jawaban']      = $isi;
                                    $parameter['id_indikator'] = $indi['id'];
                                    $parameter['asign']        = 0;
                                    $parameter['config_id']    = $this->config_id;

                                    $this->db->insert('analisis_parameter', $parameter);

                                    $param = $this->config_id()
                                        ->where('id_indikator', $indi['id'])
                                        ->where('jawaban', $isi)
                                        ->get('analisis_parameter')
                                        ->row_array();
                                    $in_param = $param['id'];
                                }

                                $respon[] = [
                                    'id_parameter' => $in_param,
                                    'id_indikator' => $indi['id'],
                                    'id_subjek'    => $id_subjek,
                                    'id_periode'   => $per,
                                ];
                            }
                        }

                        $j++;
                    }
                }

                if (count($respon) > 0) {
                    $outp = $this->db->insert_batch('analisis_respon', $respon);
                } else {
                    return [
                        'success' => false,
                        'message' => 'Tidak ada data yang diimpor',
                    ];
                }
            }

            $this->pre_update();
        } catch (Exception $e) {
            return [
                'success' => false,
                'pesan'   => $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => 'Data berhasil diimpor',
        ];
    }

    private function respon_checkbox($indi, $isi, $id_subjek, $per, &$respon): void
    {
        $list_isi = explode(',', $isi);

        foreach ($list_isi as $isi_ini) {
            if ($indi['is_teks'] == 1) {
                // Isian sebagai teks pilihan bukan kode
                $teks  = strtolower($isi_ini);
                $param = $this->config_id()
                    ->where('id_indikator', $indi['id'])
                    ->where("LOWER(jawaban) = '{$teks}'")
                    ->get('analisis_parameter')
                    ->row_array();
            } else {
                $param = $this->config_id()
                    ->where('id_indikator', $indi['id'])
                    ->where('kode_jawaban', $isi_ini)
                    ->get('analisis_parameter')
                    ->row_array();
            }
            if ($param['id'] != '') {
                $in_param = $param['id'];
                $respon[] = [
                    'id_parameter' => $in_param,
                    'id_indikator' => $indi['id'],
                    'id_subjek'    => $id_subjek,
                    'id_periode'   => $per,
                    'config_id'    => $this->config_id,
                ];
            }
        }
    }

    public function get_respon_by_id_periode($id_periode = 0, $subjek = 1)
    {
        $result = [];
        if ($subjek == 1) { // Untuk Subjek Penduduk
            $list_penduduk = $this->db
                ->select('r.*, p.nik')
                ->from('analisis_respon r')
                ->join('tweb_penduduk p', 'r.id_subjek = p.id')
                ->where('r.id_periode', $id_periode)
                ->get()
                ->result_array();

            foreach ($list_penduduk as $penduduk) {
                $result[$penduduk['nik']][$penduduk['id_indikator']] = $penduduk;
            }
        } else { // Untuk Subjek Keluarga
            $list_keluarga = $this->db
                ->select('r.*, k.no_kk')
                ->from('analisis_respon r')
                ->join('tweb_keluarga k', 'r.id_subjek = k.id')
                ->where('r.id_periode', $id_periode)
                ->get()
                ->result_array();

            foreach ($list_keluarga as $keluarga) {
                $result[$keluarga['no_kk']][$keluarga['id_indikator']] = $keluarga;
            }
        }

        return $result;
    }

    public function perbaharui($id_subjek = 0): void
    {
        // Daftar indikator yg menggunakan referensi
        $id_indikator = $this->config_id()
            ->select('id')
            ->get_where('analisis_indikator', ['id_master' => $this->session->analisis_master])
            ->result_array();

        if ($id_indikator) {
            $id_indikator = array_column($id_indikator, 'id');

            $outp = $this->db
                ->where('id_subjek', $id_subjek)
                ->where_in('id_indikator', $id_indikator)
                ->delete('analisis_respon');
        }

        status_sukses($outp);
    }
}
