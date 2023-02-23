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

class Analisis_respon_model extends CI_Model
{
    protected $per;
    protected $master;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Spreadsheet_Excel_Reader');
        $this->load->model('analisis_master_model');
        $this->per    = $this->analisis_master_model->get_aktif_periode();
        $this->master = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $this->subjek = $this->session->subjek_tipe;
    }

    public function autocomplete()
    {
        switch ($this->subjek) {
            case 1:
                $this->db
                    ->select('nik, u.nama')
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
                break;

            case 2:
                $this->db
                    ->select('no_kk, p.nama')
                    ->from('keluarga_aktif u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 3:
                $this->db
                    ->select('no_kk, p.nama')
                    ->from('tweb_rtm u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 4:
                $this->db
                    ->select('u.nama AS no_kk, p.nama')
                    ->from('kelompok u')
                    ->join('penduduk_hidup p', 'u.id_ketua = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 5:
                $this->db
                    ->select('u.kode_desa AS no_kk, u.nama_desa as nama')
                    ->from('config u');
                break;

            case 6:
                $this->db
                    ->select('u.dusun')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw', '0');
                break;

            case 7:
                $this->db
                    ->select('u.dusun, u.rw')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw <>', '0');
                break;

            case 8:
                $this->db
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
                    ->or_where("(SELECT COUNT(id) FROM penduduk_hidup WHERE nik LIKE '{$kw}' AND id_rtm = u.id) > 1")
                    ->or_where("(SELECT COUNT(id) FROM penduduk_hidup WHERE nama LIKE '{$kw}' AND id_rtm = u.id) > 1")
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

            default: return null;
        }
    }

    private function dusun_sql()
    {
        if (empty($this->session->dusun) || $this->subjek == 5) {
            return;
        }

        $this->db->where('dusun', $this->session->dusun);
    }

    private function rw_sql()
    {
        if (empty($this->session->rw) || $this->subjek == 5) {
            return;
        }

        $this->db->where('rw', $this->session->rw);
    }

    private function rt_sql()
    {
        if (empty($this->session->rt) || $this->subjek == 5) {
            return;
        }

        $this->db->where('rt', $this->session->rt);
    }

    // Pertanyaan telah diisi atau belum
    // $this->session->isi == 1 untuk pertanyaan yg telah diisi
    private function isi_sql()
    {
        if (empty($isi = $this->session->isi)) {
            return;
        }

        $isi = $isi == 1 ? 1 : 0;
        $this->db
            ->where("(SELECT COUNT(id_subjek) FROM analisis_respon_hasil WHERE id_subjek = u.id AND id_periode = {$this->per}) = {$isi}");
    }

    private function kelompok_sql($kf = 0)
    {
        $this->db->where('id_master', $kf);
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $jml_data = $this->db
            ->select('COUNT(*) AS jml_data')
            ->get()->row()->jml_data;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $id_kelompok = $this->master['id_kelompok'];

        switch ($this->subjek) {
            case 1:
                $this->db
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
                break;

            case 2:
                $this->db
                    ->from('keluarga_aktif u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 3:
                $this->db
                    ->from('tweb_rtm u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 4:
                $this->db
                    ->from('kelompok u')
                    ->join('penduduk_hidup p', 'u.id_ketua = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 5:
                $this->db
                    ->from('config u');
                break;

            case 6:
                $this->db
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw', '0');
                break;

            case 7:
                $this->db
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw <>', '0')
                    ->where('u.rw <>', '-');
                break;

            case 8:
                $this->db
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt <> 0')
                    ->where('u.rt <> "-"');
                break;

            default: return null;
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

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->db
            ->select("(SELECT id_subjek FROM analisis_respon WHERE id_subjek = u.id AND id_periode = {$this->per} LIMIT 1) as cek")
            ->select("(SELECT pengesahan from analisis_respon_bukti b WHERE b.id_master = {$this->master['id']} AND b.id_periode = {$this->per} AND b.id_subjek = u.id) as bukti_pengesahan");

        switch ($this->subjek) {
            case 1:
                $this->db
                    ->select('u.id, u.nik AS nid, u.nama, u.sex, c.dusun, c.rw, c.rt');
                break;

            case 2:
                $this->db
                    ->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 3:
                $this->db
                    ->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 4:
                $this->db
                    ->select('u.id, u.kode AS nid, u.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 5:
                $this->db
                    ->select('u.id, u.kode_desa as nid, u.nama_desa as nama, "-" as sex, "-" as dusun, "-" as rw, "-" as rt');
                break;

            case 6:
                $this->db->select("u.id, u.dusun AS nid, CONCAT(UPPER('{$this->setting->sebutan_dusun} '), u.dusun) as nama, '-' as sex, u.dusun, '-' as rw, '-' as rt");
                break;

            case 7:
                $this->db->select("u.id, u.rw AS nid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt");
                break;

            case 8:
                $this->db
                    ->select("u.id, u.rt AS nid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt");
                break;

            default: return null;
        }
        $this->list_data_sql();

        switch ($o) {
            case 1: $this->db->order_by('u.id');
                break;

            case 2: $this->db->order_by('u.id DESC');
                break;

            case 3: $this->db->order_by('nama');
                break;

            case 4: $this->db->order_by('nama DESC');
                break;

            default:$this->db->order_by('u.id');
        }

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $data = $this->db->get()->result_array();
        $j    = $offset;

        for ($i = 0; $i < count($data); $i++) {
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

        switch ($this->subjek) {
            case 1:
                $this->db->select('u.id, u.nik AS nid, u.nama, u.sex, c.dusun, c.rw, c.rt');
                break;

            case 2:
                $this->db->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 3:
                $this->db->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw,c.rt');
                break;

            case 4:
                $this->db->select('u.id, u.kode AS nid, u.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 5:
                $this->db->select('u.id, u.kode_desa as nid, u.nama_desa as nama, "-" as sex, "-" as dusun, "-" as rw, "-" as rt');
                break;

            case 6:
                $this->db->select("u.id, u.dusun AS nid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun) as nama, '-' as sex, u.dusun, '-' as rw, '-' as rt");
                break;

            case 7:
                $this->db->select("u.id, u.rw AS nid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt");
                break;

            case 8:
                $this->db->select("u.id, u.rt AS nid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt");
                break;

            default:
                return null;
                break;
        }

        $this->list_data_sql();

        switch ($o) {
            case 1: $this->db->order_by('u.id');
                break;

            case 2: $this->db->order_by('u.id DESC');
                break;

            case 3: $this->db->order_by('nama');
                break;

            case 4: $this->db->order_by('nama DESC');
                break;

            default:$this->db->order_by('u.id');
        }

        $data = $this->db->get()->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $i + 1;
            if ($p == 1) {
                $par = $this->db
                    ->select('kode_jawaban, asign, jawaban, r.id_indikator, r.id_parameter AS korek')
                    ->from('analisis_respon r')
                    ->join('analisis_parameter p', 'p.id = r.id_parameter', 'left')
                    ->where('r.id_periode', $per)
                    ->where('r.id_subjek', $data[$i]['id'])
                    ->order_by('r.id_indikator')
                    ->get()->result_array();
                $data[$i]['par'] = $par;
            } else {
                $data[$i]['par'] = null;
            }

            $data[$i]['jk'] = ($data[$i]['sex'] == 1) ? 'L' : 'P';
        }

        return $data;
    }

    public function update_kuisioner($id = 0, $per = 0)
    {
        $outp                     = true;
        $this->session->error_msg = '';
        if ($per == 0) {
            $per       = $this->analisis_master_model->get_aktif_periode();
            $id_master = $_SESSION['analisis_master'];
        } else {
            $id_master = $this->db->get_where('analisis_periode', ['id' => $per])->row_array();
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
            $sql = 'DELETE FROM analisis_respon WHERE id_subjek = ? AND id_periode=?';
            $this->db->query($sql, [$id, $per]);
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

                        $sql   = 'SELECT * FROM analisis_parameter u WHERE jawaban = ? AND id_indikator = ?';
                        $query = $this->db->query($sql, [$id_p, $indikator]);
                        $dx    = $query->row_array();
                        if (! $dx) {
                            $data['id_indikator'] = $indikator;
                            $data['jawaban']      = $id_p;
                            $outp &= $this->db->insert('analisis_parameter', $data);
                            unset($data);

                            $sql   = 'SELECT * FROM analisis_parameter u WHERE jawaban = ? AND id_indikator = ?';
                            $query = $this->db->query($sql, [$id_p, $indikator]);
                            $dx    = $query->row_array();

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

                        $sql   = 'SELECT * FROM analisis_parameter u WHERE jawaban = ? AND id_indikator = ?';
                        $query = $this->db->query($sql, [$id_p, $indikator]);
                        $dx    = $query->row_array();
                        if (! $dx) {
                            $data['id_indikator'] = $indikator;
                            $data['jawaban']      = $id_p;
                            $outp &= $this->db->insert('analisis_parameter', $data);
                            unset($data);

                            $sql   = 'SELECT * FROM analisis_parameter u WHERE jawaban = ? AND id_indikator = ?';
                            $query = $this->db->query($sql, [$id_p, $indikator]);
                            $dx    = $query->row_array();

                            $data2['id_parameter'] = $dx['id'];
                            $data2['id_indikator'] = $indikator;
                            $data2['id_subjek']    = $id;
                            $data2['id_periode']   = $per;
                            $outp &= $this->db->insert('analisis_respon', $data2);
                        } else {
                            unset($data);
                            $data['id_indikator'] = $indikator;
                            $data['id_parameter'] = $dx['id'];

                            $data['id_subjek']  = $id;
                            $data['id_periode'] = $per;
                            $outp &= $this->db->insert('analisis_respon', $data);
                        }
                    }
                    next($id_it);
                }
            }

            $sql   = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=?';
            $query = $this->db->query($sql, [$id, $per]);
            $dx    = $query->row_array();

            $upx['id_master']  = $id_master;
            $upx['akumulasi']  = 0 + $dx['jml'];
            $upx['id_subjek']  = $id;
            $upx['id_periode'] = $per;

            $sql = 'DELETE FROM analisis_respon_hasil WHERE id_subjek = ? AND id_periode=?';
            $this->db->query($sql, [$id, $per]);
            $outp &= $this->db->insert('analisis_respon_hasil', $upx);
        }
        if (isset($_FILES['pengesahan'])) {
            $lokasi_file = $_FILES['pengesahan']['tmp_name'];
            $tipe_file   = $_FILES['pengesahan']['type'];
            if (! empty($lokasi_file)) {
                if ($tipe_file != 'image/jpeg' && $tipe_file != 'image/pjpeg') {
                    $_SESSION['sukses'] = -1;
                } else {
                    $nama_file = $_SESSION['analisis_master'] . '_' . $per . '_' . $id . '_' . mt_rand(10000, 99999) . '.jpg';
                    UploadPengesahan($nama_file);
                    $bukti['pengesahan'] = $nama_file;
                    $bukti['id_master']  = $id_master;
                    $bukti['id_subjek']  = $id;
                    $bukti['id_periode'] = $per;

                    $ada_bukti = $this->db->where(['id_master' => $id_master, 'id_subjek' => $id, 'id_periode' => $per])->get('analisis_respon_bukti')->num_rows();
                    if ($ada_bukti > 0) {
                        $outp = $this->db->where(['id_master' => $id_master, 'id_subjek' => $id, 'id_periode' => $per])->update('analisis_respon_bukti', $bukti);
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
            $sql   = 'SELECT s.id as id_parameter,s.jawaban,s.kode_jawaban FROM analisis_parameter s WHERE id_indikator = ? ORDER BY s.kode_jawaban ASC ';
            $query = $this->db->query($sql, $in);
        } else {
            $sql   = 'SELECT s.id as id_parameter,s.jawaban,s.kode_jawaban,(SELECT count(id_subjek) FROM analisis_respon WHERE id_parameter = s.id AND id_subjek = ? AND id_periode=?) as cek FROM analisis_parameter s WHERE id_indikator = ? ORDER BY s.kode_jawaban ASC ';
            $query = $this->db->query($sql, [$id, $per, $in]);
        }

        $data = $query->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $i + 1;
            if (isset($this->session->delik)) {
                $data[$i]['cek'] = 0;
            }
        }

        return $data;
    }

    private function list_jawab3($id = 0, $in = 0, $per = 0)
    {
        $sql   = 'SELECT s.id as id_parameter,s.jawaban FROM analisis_respon r LEFT JOIN analisis_parameter s ON r.id_parameter = s.id WHERE r.id_indikator = ? AND r.id_subjek = ? AND r.id_periode=?';
        $query = $this->db->query($sql, [$in, $id, $per]);

        return $query->row_array();
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

        for ($i = 0; $i < count($data); $i++) {
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
            $sql   = 'SELECT s.id as id_parameter,s.jawaban,s.kode_jawaban FROM analisis_parameter s WHERE id_indikator = ? ';
            $query = $this->db->query($sql, $in);
        } else {
            $sql   = 'SELECT s.id as id_parameter,s.jawaban,s.kode_jawaban,(SELECT count(id_subjek) FROM analisis_respon WHERE id_parameter = s.id AND id_subjek = ? AND id_periode=?) as cek FROM analisis_parameter s WHERE id_indikator = ? ';
            $query = $this->db->query($sql, [$id, $per, $in]);
        }
        $data = $query->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $i + 1;
            if (isset($this->session->delik)) {
                $data[$i]['cek'] = 0;
            }
        }

        return $data;
    }

    private function list_jawab5($id = 0, $in = 0, $per = 0)
    {
        $sql   = 'SELECT s.id as id_parameter,s.jawaban FROM analisis_respon r LEFT JOIN analisis_parameter s ON r.id_parameter = s.id WHERE r.id_indikator = ? AND r.id_subjek = ? AND r.id_periode = ?';
        $query = $this->db->query($sql, [$in, $id, $per]);

        return $query->row_array();
    }

    public function list_indikator_child($id = 0)
    {
        $sql      = 'SELECT id_child FROM analisis_master WHERE id = ? ';
        $query    = $this->db->query($sql, $_SESSION['analisis_master']);
        $id_child = $query->row_array();
        $id_child = $id_child['id_child'];

        $sql   = 'SELECT id FROM analisis_periode WHERE id_master = ? AND aktif = 1';
        $query = $this->db->query($sql, $id_child);
        $per   = $query->row_array();
        $per   = $per['id'];

        $sql = 'SELECT * FROM analisis_indikator u WHERE id_master = ? ';
        $sql .= ' ORDER BY nomor';
        $query = $this->db->query($sql, $id_child);
        $data  = $query->result_array();

        for ($i = 0; $i < count($data); $i++) {
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
        $sql      = 'SELECT id_child FROM analisis_master WHERE id = ? ';
        $query    = $this->db->query($sql, $_SESSION['analisis_master']);
        $id_child = $query->row_array();
        $id_child = $id_child['id_child'];

        $sql   = 'SELECT id FROM analisis_periode WHERE id_master = ? AND aktif = 1';
        $query = $this->db->query($sql, $id_child);
        $per   = $query->row_array();
        $per   = $per['id'];

        return $per;
    }
    //---------------------------

    public function list_bukti($id = 0)
    {
        $per = $this->analisis_master_model->get_aktif_periode();
        $sql = 'SELECT pengesahan FROM analisis_respon_bukti WHERE id_subjek = ? AND id_master = ? AND id_periode = ? ';
        $sql .= ' ORDER BY tgl_update DESC';
        $query = $this->db->query($sql, [$id, $_SESSION['analisis_master'], $per]);
        $data  = $query->result_array();

        return $data;
    }

    public function get_subjek($id = 0)
    {
        $sebutan_dusun = ucwords($this->setting->sebutan_dusun);

        switch ($this->subjek) {
            case 1:
                $this->db
                    ->select('u.*, u.nik AS nid, c.dusun, c.rw, c.rt')
                    ->select("CONCAT('{$sebutan_dusun} ', c.dusun, ', RT ', c.rt, ' / RW ', c.rw) as wilayah")
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
                break;

            case 2:
                $this->db
                    ->select('u.*, u.no_kk AS nid, p.nik AS nik_kepala, p.nama, p.sex, c.dusun, c.rw, c.rt')
                    ->select("CONCAT('{$sebutan_dusun} ', c.dusun, ', RT ', c.rt, ' / RW ', c.rw) as wilayah")
                    ->from('keluarga_aktif u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');

                break;

            case 3:
                $this->db
                    ->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt')
                    ->from('tweb_rtm u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 4: $
                {$this}->db
                    ->select('u.id, u.kode AS nid, u.nama, p.sex, c.dusun, c.rw, c.rt')
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
                    ->join('tweb_penduduk_sex x2', 'c.pamong_sex = x2.id', 'LEFT');

                break;

            case 6:
                $this->db
                    ->select("u.id, u.dusun AS nid, UPPER('{$sebutan_dusun}') as nama, '-' as sex, u.dusun, '-' as rw, '-' as rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw', '0');
                break;

            case 7:
                $this->db
                    ->select("u.id, u.rw AS nid, CONCAT( UPPER('{$sebutan_dusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw <>', '0');
                break;

            case 8:
                $this->db
                    ->select("u.id, u.rt AS nid, CONCAT( UPPER('{$sebutan_dusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt <> 0')
                    ->where('u.rt <> "-"');
                break;

            default: return null;
        }
        $data = $this->db
            ->where('u.id', $id)
            ->limit(1)
            ->get()
            ->row_array();

        // Data tambahan subjek desa
        if ($this->subjek == 5) {
            $tambahan = [
                'jumlah_total_penduduk'            => $this->db->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_laki_laki'        => $this->db->where('sex', 1)->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_perempuan'        => $this->db->where('sex', 2)->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_pedatang'         => $this->db->where('status', 2)->count_all_results('penduduk_hidup'),
                'jumlah_penduduk_yang_pergi'       => $this->db->where('kode_peristiwa', 3)->count_all_results('log_penduduk'),
                'jumlah_total_kepala_keluarga'     => $this->db->join('penduduk_hidup t', 'u.nik_kepala = t.id', 'left')->count_all_results('keluarga_aktif u'),
                'jumlah_kepala_keluarga_laki_laki' => $this->db->join('penduduk_hidup t', 'u.nik_kepala = t.id', 'left')->where('sex', 1)->count_all_results('keluarga_aktif u'),
                'jumlah_kepala_keluarga_perempuan' => $this->db->join('penduduk_hidup t', 'u.nik_kepala = t.id', 'left')->where('sex', 2)->count_all_results('keluarga_aktif u'),
                'jumlah_peserta_bpjs'              => $this->db->where('bpjs_ketenagakerjaan != ', null)->count_all_results('penduduk_hidup'),
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
                case 2: $sql = 'SELECT u.* FROM penduduk_hidup u WHERE u.id_kk = ? ORDER BY kk_level';
                    break;

                case 3: $sql = 'SELECT u.* FROM penduduk_hidup u WHERE u.id_rtm = ? ORDER BY rtm_level';
                    break;

                default: return null;
            }
            $query = $this->db->query($sql, $id);

            return $query->result_array();
        }

        return null;
    }

    public function aturan_unduh()
    {
        $subjek    = $this->subjek;
        $order_sql = ' ORDER BY u.nomor ASC';
        $sql       = 'SELECT u.*,t.tipe AS tipe_indikator,k.kategori AS kategori FROM analisis_indikator u LEFT JOIN analisis_tipe_indikator t ON u.id_tipe = t.id LEFT JOIN analisis_kategori_indikator k ON u.id_kategori = k.id WHERE u.id_master = ? ';
        $sql .= $order_sql;

        $query = $this->db->query($sql, $_SESSION['analisis_master']);
        $data  = $query->result_array();

        $per = $this->analisis_master_model->get_aktif_periode();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $i + 1;

            if ($data[$i]['id_tipe'] == 1 || $data[$i]['id_tipe'] == 2) {
                $sql2            = 'SELECT i.id,i.kode_jawaban,i.jawaban FROM analisis_parameter i WHERE i.id_indikator = ? ORDER BY i.kode_jawaban ASC ';
                $query2          = $this->db->query($sql2, $data[$i]['id']);
                $respon2         = $query2->result_array();
                $data[$i]['par'] = $respon2;
            } else {
                $data[$i]['par'] = null;
            }
            if ($data[$i]['act_analisis'] == 1) {
                $data[$i]['act_analisis'] = 'Ya';
            } else {
                $data[$i]['act_analisis'] = 'Tidak';
            }
        }

        return $data;
    }

    public function indikator_data_unduh()
    {
        $order_sql = ' ORDER BY u.nomor';

        $sql = 'SELECT u.* FROM analisis_indikator u WHERE u.id_master = ? ';
        $sql .= $order_sql;
        $query = $this->db->query($sql, $_SESSION['analisis_master']);
        $data  = $query->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no']  = $i + 1;
            $data[$i]['par'] = null;

            $sql2            = 'SELECT id_parameter FROM analisis_respon WHERE id_indikator = ? AND asign = 1 ';
            $query2          = $this->db->query($sql2, $data[$i]['id']);
            $par             = $query2->result_array();
            $data[$i]['par'] = $par;
        }

        return $data;
    }

    public function indikator_unduh($p = 0)
    {
        $data = $this->db
            ->select('u.*')
            ->where('u.id_master', $this->session->analisis_master)
            ->order_by('LPAD(u.nomor, 10, " ")')
            ->get('analisis_indikator u')
            ->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no']  = $i + 1;
            $data[$i]['par'] = null;

            if ($p == 2) {
                $par = $this->db
                    ->select('*')
                    ->where('id_indikator', $data[$i]['id'])
                    ->where('asign', 1)
                    ->get('analisis_parameter')
                    ->result_array();
                $data[$i]['par'] = $par;
            }
        }

        return $data;
    }

    public function pre_update($pr = 0)
    {
        if ($pr == 0) {
            $per = $this->analisis_master_model->get_aktif_periode();
        } else {
            $per = $pr;
        }

        $sql   = 'SELECT DISTINCT(id_subjek) AS id FROM analisis_respon WHERE id_periode = ? ';
        $query = $this->db->query($sql, $per);
        $data  = $query->result_array();

        $sql = 'DELETE FROM analisis_respon_hasil WHERE id_subjek = 0';
        $this->db->query($sql);

        $sql = 'DELETE FROM analisis_respon WHERE id_subjek = 0';
        $this->db->query($sql);

        $sql = 'DELETE FROM analisis_respon_hasil WHERE id_periode = ?';
        $this->db->query($sql, $per);

        for ($i = 0; $i < count($data); $i++) {
            $sql   = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=?';
            $query = $this->db->query($sql, [$data[$i]['id'], $per]);
            $dx    = $query->row_array();

            $upx[$i]['id_master']  = $_SESSION['analisis_master'];
            $upx[$i]['akumulasi']  = 0 + $dx['jml'];
            $upx[$i]['id_subjek']  = $data[$i]['id'];
            $upx[$i]['id_periode'] = $per;
        }
        if (@$upx) {
            $this->db->insert_batch('analisis_respon_hasil', $upx);
        }
    }

    public function update_hasil($id = 0)
    {
        $per = $this->analisis_master_model->get_aktif_periode();

        $sql   = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis = 1 AND r.id_periode = ?';
        $query = $this->db->query($sql, [$id, $per]);
        $dx    = $query->row_array();

        $upx['id_master']  = $_SESSION['analisis_master'];
        $upx['akumulasi']  = 0 + $dx['jml'];
        $upx['id_subjek']  = $id;
        $upx['id_periode'] = $per;

        $sql = 'DELETE FROM analisis_respon_hasil WHERE id_subjek = ? AND id_periode=?';
        $this->db->query($sql, [$id, $per]);
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

        $sql       = 'SELECT * FROM analisis_indikator WHERE id_master=? ORDER BY id ASC';
        $query     = $this->db->query($sql, $_SESSION['analisis_master']);
        $indikator = $query->result_array();
        $jml       = count($indikator);

        $data  = new Spreadsheet_Excel_Reader($_FILES['respon']['tmp_name']);
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
                    $sqls      = 'SELECT id FROM penduduk_hidup WHERE nik = ?;';
                    $querys    = $this->db->query($sqls, [$id_subjek]);
                    $isbj      = $querys->row_array();
                    $id_subjek = $isbj['id'];
                } elseif ($subject == 3) {
                    // sasaran rumah tangga, simpan id, bukan nomor rumah tangga
                    $id_subjek = $this->db->select('id')
                        ->where('id_rtm', $id_subjek)
                        ->get('tweb_rtm')
                        ->row()->id;
                }

                $j   = $kl + $op;
                $all = '';

                foreach ($indikator as $indi) {
                    $isi = $data->val($i, $j, $s);
                    if ($isi != '') {
                        if ($indi['id_tipe'] == 1) {
                            $sql   = 'SELECT id FROM analisis_parameter WHERE id_indikator = ? AND kode_jawaban = ?;';
                            $query = $this->db->query($sql, [$indi['id'], $isi]);
                            $param = $query->row_array();

                            if ($param) {
                                $in_param = $param['id'];
                            } else {
                                if ($isi == '') {
                                    $in_param = 0;
                                } else {
                                    $in_param = -1;
                                }
                            }

                            $respon[] = [
                                'id_parameter' => $in_param,
                                'id_indikator' => $indi['id'],
                                'id_subjek'    => $id_subjek,
                                'id_periode'   => $per, ];
                        } elseif ($indi['id_tipe'] == 2) {
                            $this->respon_checkbox($indi, $isi, $id_subjek, $per, $respon);
                        } else {
                            $sql   = 'SELECT id FROM analisis_parameter WHERE id_indikator = ? AND jawaban = ?;';
                            $query = $this->db->query($sql, [$indi['id'], $isi]);
                            $param = $query->row_array();

                            // apakah sdh ada jawaban yg sama
                            if ($param) {
                                $in_param = $param['id'];
                            } else {
                                $parameter['jawaban']      = $isi;
                                $parameter['id_indikator'] = $indi['id'];
                                $parameter['asign']        = 0;

                                $this->db->insert('analisis_parameter', $parameter);

                                $sql      = 'SELECT id FROM analisis_parameter WHERE id_indikator = ? AND jawaban = ?;';
                                $query    = $this->db->query($sql, [$indi['id'], $isi]);
                                $param    = $query->row_array();
                                $in_param = $param['id'];
                            }

                            $respon[] = [
                                'id_parameter' => $in_param,
                                'id_indikator' => $indi['id'],
                                'id_subjek'    => $id_subjek,
                                'id_periode'   => $per, ];
                        }
                    }

                    $j++;
                }
            }

            if (count($respon) > 0) {
                $outp = $this->db->insert_batch('analisis_respon', $respon);
            } else {
                $outp                  = false;
                $_SESSION['error_msg'] = 'Tidak ada data';
            }
        }

        $this->pre_update();

        status_sukses($outp); //Tampilkan Pesan
    }

    private function respon_checkbox($indi, $isi, $id_subjek, $per, &$respon)
    {
        $list_isi = explode(',', $isi);

        foreach ($list_isi as $isi_ini) {
            if ($indi['is_teks'] == 1) {
                // Isian sebagai teks pilihan bukan kode
                $teks  = strtolower($isi_ini);
                $param = $this->db->where('id_indikator', $indi['id'])->where("LOWER(jawaban) = '{$teks}'")
                    ->get('analisis_parameter')->row_array();
            } else {
                $param = $this->db->where('id_indikator', $indi['id'])->where('kode_jawaban', $isi_ini)
                    ->get('analisis_parameter')->row_array();
            }
            if ($param['id'] != '') {
                $in_param = $param['id'];
                $respon[] = [
                    'id_parameter' => $in_param,
                    'id_indikator' => $indi['id'],
                    'id_subjek'    => $id_subjek,
                    'id_periode'   => $per,
                ];
            }
        }
    }

    public function get_respon_by_id_periode($id_periode = 0, $subjek = 1)
    {
        $result = [];
        if ($subjek == 1) { // Untuk Subjek Penduduk
            $list_penduduk = $this->db->select('r.*, p.nik')
                ->from('analisis_respon r')
                ->join('tweb_penduduk p', 'r.id_subjek = p.id')
                ->where('r.id_periode', $id_periode)
                ->get()
                ->result_array();

            foreach ($list_penduduk as $penduduk) {
                $result[$penduduk['nik']][$penduduk['id_indikator']] = $penduduk;
            }
        } else { // Untuk Subjek Keluarga
            $list_keluarga = $this->db->select('r.*, k.no_kk')
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

    public function perbaharui($id_subjek = 0)
    {
        // Daftar indikator yg menggunakan referensi
        $id_indikator = $this->db
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
