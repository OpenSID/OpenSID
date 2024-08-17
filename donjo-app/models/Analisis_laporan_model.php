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

use App\Enums\AnalisisRefSubjekEnum;

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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_laporan_model extends My_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('analisis_master_model');
        $this->subjek = $this->session->subjek_tipe;
    }

    public function autocomplete()
    {
        $sql = 'SELECT no_kk FROM tweb_keluarga WHERE config_id = ' . identitas('id') .
            ' UNION SELECT t.nama
                FROM tweb_keluarga u
                LEFT JOIN penduduk_hidup t ON u.nik_kepala = t.id
                LEFT JOIN tweb_wil_clusterdesa c ON t.id_cluster = c.id
                WHERE u.config_id = ' . identitas('id');
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        $outp    = '';
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $outp .= ',"' . $data[$i]['no_kk'] . '"';
        }
        $outp = strtolower(substr($outp, 1));

        return '[' . $outp . ']';
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

                // no break
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

            default:
                return null;
        }
    }

    private function master_sql()
    {
        if (isset($this->session->analisis_master)) {
            $kf = $this->session->analisis_master;

            return " AND u.id_master = {$kf}";
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

    private function klasifikasi_sql(): void
    {
        if (empty($this->session->klasifikasi)) {
            return;
        }

        $this->db->where('k.id', $this->session->klasifikasi);
    }

    private function jawab_sql(): void
    {
        if (empty($kf = $this->session->jawab)) {
            return;
        }

        $per  = $this->analisis_master_model->periode->id;
        $jmkf = $this->session->jmkf;
        $this->db
            ->where_in('x.id_parameter', $kf)
            ->where("((SELECT COUNT(id_parameter) FROM analisis_respon ar WHERE id_subjek = u.id AND id_periode = {$per} AND id_parameter IN ({$kf})) = {$jmkf})");
    }

    public function get_judul()
    {
        $asubjek = AnalisisRefSubjekEnum::all()[$this->subjek];

        switch ($this->subjek) {
            case 1:
                $data['nama']     = 'Nama';
                $data['nomor']    = 'NIK Penduduk';
                $data['nomor_kk'] = 'No. KK';
                $data['asubjek']  = $asubjek;
                break;

            case 2:
                $data['nama']     = 'Kepala Keluarga';
                $data['nomor']    = 'Nomor KK';
                $data['nomor_kk'] = 'NIK KK';
                $data['asubjek']  = $asubjek;
                break;

            case 3:
                $data['nama']     = 'Kepala Rumah Tangga';
                $data['nomor']    = 'Nomor Rumah Tangga';
                $data['nomor_kk'] = 'NIK KK';
                $data['asubjek']  = $asubjek;
                break;

            case 4:
                $data['nama']    = 'Nama Kelompok';
                $data['nomor']   = 'ID Kelompok';
                $data['asubjek'] = $asubjek;
                break;

            case 5:
                $desa            = ucwords($this->setting->sebutan_desa);
                $data['nama']    = "Nama {$desa}";
                $data['nomor']   = "Kode {$desa}";
                $data['asubjek'] = $desa;
                break;

            case 6:
                $dusun = ucwords($this->setting->sebutan_dusun);
                $data  = [
                    'nama'    => "Nama {$dusun}",
                    'nomor'   => $dusun,
                    'asubjek' => $dusun,
                ];
                break;

            case 7:
                $data = [
                    'nama'    => "Nama {$this->setting->sebutan_dusun}/RW",
                    'nomor'   => 'RW',
                    'asubjek' => $asubjek,
                ];
                break;

            case 8:
                $data = [
                    'nama'    => "Nama {$this->setting->sebutan_dusun}/RW/RT",
                    'nomor'   => 'RT',
                    'asubjek' => $asubjek,
                ];
                break;

            default:
                // code...
                break;
        }

        return $data;
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_query();
        $jml_data = $this->db
            ->select('COUNT(DISTINCT u.id) AS jml_data')
            ->get()->row()->jml_data;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function list_data_query(): void
    {
        $per     = $this->analisis_master_model->periode->id;
        $master  = $this->analisis_master_model->analisis_master;
        $pembagi = (int) $master['pembagi'];

        switch ($this->subjek) {
            case 1:
                $this->config_id('u')
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left')
                    ->join('tweb_keluarga kk', 'kk.id = u.id_kk', 'left');
                break;

            case 2:
                $this->config_id('u')
                    ->from('tweb_keluarga u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 3:
                $this->config_id('u')
                    ->from('tweb_rtm u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id')
                    ->join('tweb_keluarga kk', 'kk.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 4:
                $this->config_id('u')
                    ->from('kelompok u')
                    ->join('penduduk_hidup p', 'u.id_ketua = p.id', 'left')
                    ->join('tweb_keluarga kk', 'kk.nik_kepala = p.id', 'left')
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
                    ->where('u.rw <>', '0');
                break;

            case 8:
                $this->config_id('u')
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt <> 0')
                    ->where('u.rt <> "-"');
                break;
        }

        if ($this->session->jawab) {
            $this->db->join('analisis_respon x', 'ON u.id = x.id_subjek', 'left')
                ->where(' x.id_periode', $per);
        }

        $this->db
            ->join('analisis_respon_hasil h', 'u.id = h.id_subjek', 'left')
            ->join('analisis_klasifikasi k', "(h.akumulasi/{$pembagi}) >= k.minval AND (h.akumulasi/{$pembagi}) <= k.maxval", 'left')
            ->where('h.id_periode', $per)
            ->where('k.id_master', $this->session->analisis_master);

        $this->search_sql();
        $this->klasifikasi_sql();
        $this->dusun_sql();
        $this->rw_sql();
        $this->rt_sql();
        $this->jawab_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $master  = $this->analisis_master_model->analisis_master;
        $pembagi = (int) $master['pembagi'];

        switch ($this->subjek) {
            case 1:
                $this->db->select('u.id, u.nik AS uid, kk.no_kk AS kk, u.nama, kk.alamat, c.dusun, c.rw, c.rt, u.sex');
                break;

            case 2:
                $this->db->select('u.id, u.no_kk AS uid, p.nik AS kk, p.nama, u.alamat, c.dusun, c.rw, c.rt, p.sex');
                break;

            case 3:
                $this->db->select('u.id, u.no_kk AS uid, p.nik AS kk, p.nama, kk.alamat, c.dusun, c.rw, c.rt, p.sex');
                break;

            case 4:
                $this->db->select('u.id, u.kode AS uid, u.nama, p.sex, c.dusun, c.rw, c.rt');
                break;

            case 5:
                $this->db->select("u.id, u.kode_desa AS uid, u.nama_desa as nama, '-' as sex, '-' as dusun, '-' as rw, '-' as rt");
                break;

            case 6:
                $this->db->select("u.id, u.dusun AS uid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun) as nama, '-' as sex, '-' as dusun, '-' as rw, '-' as rt");
                break;

            case 7:
                $this->db->select("u.id, u.rw AS uid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt");
                break;

            case 8:
                $this->db->select("u.id, u.rt AS uid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt");
                break;

            default:
                return null;
        }
        $this->db->select("CAST((h.akumulasi/{$pembagi}) AS decimal(8,3)) AS cek, k.nama AS klasifikasi");

        $this->list_data_query();

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

            case 5:
                $this->db->order_by('cek');
                break;

            case 6:
                $this->db->order_by('cek DESC');
                break;

            case 7:
                $this->db->order_by('kk');
                break;

            case 8:
                $this->db->order_by('kk DESC');
                break;

            default:
        }
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $data = $this->db->get()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;

            if ($data[$i]['cek']) {
                $data[$i]['nilai'] = $data[$i]['cek'];
                $data[$i]['set']   = '<img src="' . base_url('assets/images/icon/tick.png') . '">';
            } else {
                $data[$i]['nilai']       = '-';
                $data[$i]['set']         = '<img src="' . base_url('assets/images/icon/cross.png') . '">';
                $data[$i]['klasifikasi'] = '-';
            }
            $data[$i]['jk'] = '-';
            $data[$i]['jk'] = $data[$i]['sex'] == 1 ? 'LAKI-LAKI' : 'PEREMPUAN';

            $j++;
        }

        return $data;
    }

    private function list_jawab2($id = 0, $in = 0)
    {
        $per  = $this->analisis_master_model->periode->id;
        $data = $this->config_id('r')
            ->select('s.id as id_parameter,s.jawaban as jawaban,s.nilai')
            ->join('analisis_parameter s', 'r.id_parameter = s.id')
            ->where('r.id_subjek', $id)
            ->where('r.id_periode', $per)
            ->where('r.id_indikator', $in)
            ->get()
            ->row_array();

        if (empty($data['jawaban'])) {
            $data['jawaban'] = '-';
            $data['nilai']   = '0';
        }

        return $data;
    }

    public function list_indikator($id = 0)
    {
        $jmkf = $this->group_parameter();
        $cb   = '';
        if (count($jmkf) > 0) {
            foreach ($jmkf as $jm) {
                $cb .= $jm['id_jmkf'] . ',';
            }
        }
        $cb .= '7777777';

        $sql = "SELECT u.*,
            (SELECT COUNT(id)
                FROM analisis_indikator
                WHERE id = u.id AND id IN({$cb}) AND config_id = " . identitas('id') . ') AS cek
            FROM analisis_indikator u
            WHERE u.config_id = ' . identitas('id');
        $sql .= $this->master_sql();
        $sql .= ' ORDER BY u.nomor ASC';
        $query   = $this->db->query($sql, $id);
        $data    = $query->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']      = $i + 1;
            $ret                 = $this->list_jawab2($id, $data[$i]['id']);
            $data[$i]['jawaban'] = $ret['jawaban'];
            $data[$i]['nilai']   = $ret['nilai'];
            $data[$i]['poin']    = $data[$i]['bobot'] * $ret['nilai'];
        }

        return $data;
    }

    public function get_total($id = 0)
    {
        $per  = $this->analisis_master_model->periode->id;
        $data = $this->config_id('u')
            ->select('akumulasi')
            ->from('analisis_respon_hasil u')
            ->where('id_subjek', $id)
            ->where('id_periode', $per)
            ->get()
            ->row_array();

        return $data['akumulasi'];
    }

    public function get_subjek($id = 0)
    {
        switch ($this->subjek) {
            case 1:
                $this->config_id('u')
                    ->select('u.id, u.nik AS nid, u.nama, u.sex, c.dusun, c.rw, c.rt')
                    ->from('penduduk_hidup u')
                    ->join('tweb_wil_clusterdesa c', 'u.id_cluster = c.id', 'left');
                break;

            case 2:
                $this->config_id('u')
                    ->select('u.id, u.no_kk AS nid, p.nama, p.sex, c.dusun, c.rw, c.rt')
                    ->from('tweb_keluarga u')
                    ->join('penduduk_hidup p', 'u.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
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
                    ->select('u.id, u.kode AS nid, u.nama, p.sex, c.dusun, c.rw, c.rt')
                    ->from('kelompok u')
                    ->join('penduduk_hidup p', '.id_ketua = p.id', 'left')
                    ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left');
                break;

            case 5:
                $this->db
                    ->select("u.id, u.kode_desa AS nid, u.nama_desa as nama, '-' as sex, '-' as dusun, '-' as rw, '-' as rt")
                    ->from('config u')
                    ->where('u.app_key', get_app_key());
                break;

            case 6:
                $this->config_id('u')
                    ->select("u.id, u.dusun AS nid, UPPER('{$this->setting->sebutan_dusun}') as nama, '-' as sex, u.dusun, '-' as rw, '-' as rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw', '0');
                break;

            case 7:
                $this->config_id('u')
                    ->select("u.id, u.rw AS nid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw) as nama, '-' as sex, u.dusun, u.rw, '-' as rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt', '0')
                    ->where('u.rw <>', '0');
                break;

            case 8:
                $this->config_id('u')
                    ->select("u.id, u.rt AS nid, CONCAT( UPPER('{$this->setting->sebutan_dusun} '), u.dusun, ' RW ', u.rw, ' RT ', u.rt) as nama, '-' as sex, u.dusun, u.rw, u.rt")
                    ->from('tweb_wil_clusterdesa u')
                    ->where('u.rt <> 0')
                    ->where('u.rt <> "-"');
                break;

            default:
                return null;
        }

        return $this->db
            ->where('u.id', $id)
            ->get()
            ->row_array();
    }

    public function multi_jawab($p = 0, $o = 0)
    {
        $master = $this->analisis_master_model->analisis_master;
        $kf     = $this->session->jawab ?? '7777777';

        switch ($o) {
            case 1:
            case 3:
                $order_sql = ' ORDER BY u.id';
                break;

            case 2:
            case 4:
                $order_sql = ' ORDER BY u.id DESC';
                break;

            default:
        }

        $asign_sql = ' AND i.asign = 1';
        $order_sql = ' ORDER BY u.nomor,i.kode_jawaban ASC';

        $sql = "SELECT u.pertanyaan,u.nomor,i.jawaban,i.id AS id_jawaban,i.kode_jawaban,
            (SELECT count(id) FROM analisis_parameter WHERE id IN ({$kf}) AND id = i.id AND u.config_id = " . identitas('id') . ") AS cek
            FROM analisis_indikator u
            LEFT JOIN analisis_parameter i ON u.id = i.id_indikator
            WHERE u.id_master = {$master['id']} AND u.config_id = " . identitas('id');
        $sql .= $asign_sql;
        $sql .= $order_sql;
        $query   = $this->db->query($sql, $master);
        $data    = $query->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    public function group_parameter()
    {
        if (isset($this->session->jawab)) {
            return $this->config_id('ap')
                ->select('DISTINCT(id_indikator) AS id_jmkf')
                ->from('analisis_parameter ap')
                ->where_in('ap.id', $this->session->jawab)
                ->get()
                ->result_array();
        }

        return null;
    }

    public function list_klasifikasi()
    {
        return $this->config_id('u')
            ->select('u.id, u.nama')
            ->from('analisis_klasifikasi u')
            ->where('u.id_master', $this->session->analisis_master)
            ->get()
            ->result_array();
    }
}
