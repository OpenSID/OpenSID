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

class Analisis_statistik_jawaban_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->hapus_data_kosong();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('pertanyaan', 'analisis_indikator');
    }

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari = $_SESSION['cari'];
            $kw   = $this->db->escape_like_str($cari);
            $kw   = '%' . $kw . '%';

            return " AND (u.pertanyaan LIKE '{$kw}' OR u.pertanyaan LIKE '{$kw}')";
        }
    }

    private function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $kf = $_SESSION['filter'];

            return " AND u.act_analisis = {$kf}";
        }
    }

    private function master_sql()
    {
        if (isset($_SESSION['analisis_master'])) {
            $kf = $_SESSION['analisis_master'];

            return " AND u.id_master = {$kf}";
        }
    }

    private function tipe_sql()
    {
        if (isset($_SESSION['tipe'])) {
            $kf = $_SESSION['tipe'];

            return " AND u.id_tipe = {$kf}";
        }
    }

    private function kategori_sql()
    {
        if (isset($_SESSION['kategori'])) {
            $kf = $_SESSION['kategori'];

            return " AND u.id_kategori = {$kf}";
        }
    }

    private function dusun_sql()
    {
        if (isset($_SESSION['dusun'])) {
            $kf = $_SESSION['dusun'];

            return " AND a.dusun = '{$kf}'";
        }
    }

    private function rw_sql()
    {
        if (isset($_SESSION['rw'])) {
            $kf = $_SESSION['rw'];

            return " AND a.rw = '{$kf}'";
        }
    }

    private function rt_sql()
    {
        if (isset($_SESSION['rt'])) {
            $kf = $_SESSION['rt'];

            return " AND a.rt = '{$kf}'";
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $sql = 'SELECT COUNT(id) AS id FROM analisis_indikator u WHERE u.config_id = ' . identitas('id');
        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();
        $sql .= $this->master_sql();
        $sql .= $this->tipe_sql();
        $sql .= $this->kategori_sql();
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $subjek = $_SESSION['subjek_tipe'];

        switch ($subjek) {
            case 1: $sbj = 'LEFT JOIN tweb_penduduk p ON r.id_subjek = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case 2: $sbj = 'LEFT JOIN tweb_keluarga v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;

            case 3: $sbj = 'LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case 4: $sbj = 'LEFT JOIN kelompok v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;
        }

        switch ($o) {
            case 1: $order_sql = ' ORDER BY u.nomor';
                break;

            case 2: $order_sql = ' ORDER BY u.nomor DESC';
                break;

            case 3: $order_sql = ' ORDER BY u.pertanyaan';
                break;

            case 4: $order_sql = ' ORDER BY u.pertanyaan DESC';
                break;

            case 5: $order_sql = ' ORDER BY u.id_kategori';
                break;

            case 6: $order_sql = ' ORDER BY u.id_kategori DESC';
                break;

            default:$order_sql = ' ORDER BY u.nomor';
        }

        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        $sql = 'SELECT u.*, t.tipe AS tipe_indikator, k.kategori AS kategori
            FROM analisis_indikator u
            LEFT JOIN analisis_tipe_indikator t ON u.id_tipe = t.id
            LEFT JOIN analisis_kategori_indikator k ON u.id_kategori = k.id
            WHERE u.config_id = ' . identitas('id');

        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();
        $sql .= $this->master_sql();
        $sql .= $this->tipe_sql();
        $sql .= $this->kategori_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        $per     = $this->analisis_master_model->get_aktif_periode();
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']     = $j + 1;
            $data[$i]['jumlah'] = '-';

            $sql1 = "SELECT COUNT(DISTINCT r.id_subjek) AS jml FROM analisis_respon r {$sbj} WHERE r.id_indikator = ? AND r.id_periode = {$per} AND id_parameter > 0";
            $sql1 .= $this->dusun_sql();
            $sql1 .= $this->rw_sql();
            $sql1 .= $this->rt_sql();
            //$sql1 .= "  GROUP BY r.id_indikator  ";
            $query1            = $this->db->query($sql1, $data[$i]['id']);
            $respon            = $query1->row_array();
            $data[$i]['bobot'] = $respon['jml'];

            $dus = $this->dusun_sql();
            $rw  = $this->rw_sql();
            $rt  = $this->rt_sql();

            $sql2 = "SELECT i.id,i.kode_jawaban,i.jawaban,(SELECT COUNT(r.id_subjek) FROM analisis_respon r {$sbj} WHERE r.id_parameter = i.id AND r.id_periode = {$per} {$dus} {$rw} {$rt}) AS jml_p FROM analisis_parameter i WHERE i.id_indikator = ? ORDER BY i.kode_jawaban  AND i.config_id = " . identitas('id');

            $query2          = $this->db->query($sql2, $data[$i]['id']);
            $respon2         = $query2->result_array();
            $data[$i]['par'] = $respon2;

            $data[$i]['act_analisis'] = $data[$i]['act_analisis'] == 1 ? 'Ya' : 'Tidak';

            if ($data[$i]['id_tipe'] == 3) {
                $data[$i]['jumlah'] = 0;

                foreach ($respon2 as $par) {
                    $data[$i]['jumlah'] += (int) $par['jawaban'] * (int) $par['jml_p'];
                }
            }
            $j++;
        }

        return $data;
    }

    public function list_indikator($id = 0)
    {
        $subjek = $_SESSION['subjek_tipe'];

        switch ($subjek) {
            case 1: $sbj = 'LEFT JOIN tweb_penduduk p ON r.id_subjek = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case 2: $sbj = 'LEFT JOIN tweb_keluarga v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;

            case 3: $sbj = 'LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case 4: $sbj = 'LEFT JOIN kelompok v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;
        }

        $sql     = 'SELECT * FROM analisis_parameter WHERE id_indikator = ? AND config_id = ' . identitas('id') . ' ORDER BY kode_jawaban ASC ';
        $query   = $this->db->query($sql, $id);
        $data    = $query->result_array();
        $per     = $this->analisis_master_model->get_aktif_periode();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;

            $sql = "SELECT COUNT(r.id_subjek) AS jml FROM analisis_respon r {$sbj} WHERE r.id_parameter = ? AND r.id_periode = {$per}";
            $sql .= $this->dusun_sql();
            $sql .= $this->rw_sql();
            $sql .= $this->rt_sql();
            $query  = $this->db->query($sql, $data[$i]['id']);
            $respon = $query->row_array();

            $data[$i]['nilai'] = $respon['jml'];
        }

        return $data;
    }

    public function list_subjek($id = 0)
    {
        $per = $this->analisis_master_model->get_aktif_periode();

        $subjek = $_SESSION['subjek_tipe'];

        switch ($subjek) {
            case 1: $sbj = 'LEFT JOIN tweb_penduduk p ON r.id_subjek = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case 2: $sbj = 'LEFT JOIN tweb_keluarga v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;

            case 3: $sbj = 'LEFT JOIN tweb_rtm v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.nik_kepala = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case 4: $sbj = 'LEFT JOIN kelompok v ON r.id_subjek = v.id LEFT JOIN tweb_penduduk p ON v.id_ketua = p.id LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;
        }

        $sql = "SELECT p.id AS id_pend,r.id_subjek,p.nama,p.nik,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = p.id AND config_id = " . identitas('id') . ") AS umur,p.sex,a.dusun,a.rw,a.rt FROM analisis_respon r {$sbj} WHERE r.id_parameter = ? AND r.id_periode = {$per}";

        $sql .= $this->dusun_sql();
        $sql .= $this->rw_sql();
        $sql .= $this->rt_sql();
        $query   = $this->db->query($sql, $id);
        $data    = $query->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['sex'] = $data[$i]['sex'] == 1 ? 'Laki-laki' : 'Perempuan';

            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    public function get_analisis_indikator($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('analisis_indikator')
            ->row_array();
    }

    public function get_analisis_parameter($id = '')
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('analisis_parameter')
            ->row_array();
    }

    public function list_tipe()
    {
        return $this->db->get('analisis_tipe_indikator')->result_array();
    }

    public function list_kategori()
    {
        return $this->config_id('u')
            ->select('u.*')
            ->from('analisis_kategori_indikator u')
            ->get()
            ->result_array();
    }

    public function hapus_data_kosong()
    {
        // Hapus data analisis_parameter dengan responden 0 untuk tipe pertanyaan 3 dan 4
        $hapus = $this->db
            ->select('ap.id')
            ->from('analisis_respon ar')
            ->join('analisis_parameter ap', 'ar.id_parameter = ap.id', 'right')
            ->join('analisis_indikator ai', 'ai.id = ap.id_indikator', 'left')
            ->where_in('ai.id_tipe', [3, 4])
            ->where('id_subjek', null)
            ->get()
            ->result_array();

        if ($hapus) {
            return $this->config_id()
                ->where_in('id', array_column($hapus, 'id'))
                ->delete('analisis_parameter');
        }

        return true;
    }
}
