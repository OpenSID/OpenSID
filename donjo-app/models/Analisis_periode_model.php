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

class Analisis_periode_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'analisis_periode');
    }

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari       = $_SESSION['cari'];
            $kw         = $this->db->escape_like_str($cari);
            $kw         = '%' . $kw . '%';
            $search_sql = " AND (u.nama LIKE '{$kw}' OR u.nama LIKE '{$kw}')";

            return $search_sql;
        }
    }

    private function master_sql()
    {
        if (isset($_SESSION['analisis_master'])) {
            $kf         = $_SESSION['analisis_master'];
            $filter_sql = " AND u.id_master = {$kf}";

            return $filter_sql;
        }
    }

    private function state_sql()
    {
        if (isset($_SESSION['state'])) {
            $kf         = $_SESSION['state'];
            $filter_sql = " AND u.id_state = {$kf}";

            return $filter_sql;
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $sql = 'SELECT COUNT(id) AS id FROM analisis_periode u WHERE 1';
        $sql .= $this->search_sql();
        $sql .= $this->master_sql();
        $sql .= $this->state_sql();
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
        switch ($o) {
            case 1: $order_sql = ' ORDER BY u.id';
                break;

            case 2: $order_sql = ' ORDER BY u.id DESC';
                break;

            case 3: $order_sql = ' ORDER BY u.id';
                break;

            case 4: $order_sql = ' ORDER BY u.id DESC';
                break;

            case 5: $order_sql = ' ORDER BY g.id';
                break;

            case 6: $order_sql = ' ORDER BY g.id DESC';
                break;

            default:$order_sql = ' ORDER BY u.id';
        }

        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        $sql = 'SELECT u.*,s.nama AS status FROM analisis_periode u LEFT JOIN analisis_ref_state s ON u.id_state = s.id WHERE 1 ';

        $sql .= $this->search_sql();
        $sql .= $this->master_sql();
        $sql .= $this->state_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $j + 1;
            if ($data[$i]['aktif'] == 1) {
                $data[$i]['aktif'] = "<img src='" . base_url() . "assets/images/icon/tick.png'>";
            } else {
                $data[$i]['aktif'] = '';
            }
            $j++;
        }

        return $data;
    }

    private function validasi_data($post)
    {
        $data                      = [];
        $data['nama']              = nomor_surat_keputusan($post['nama']);
        $data['id_state']          = $post['id_state'] ?: null;
        $data['tahun_pelaksanaan'] = bilangan($post['tahun_pelaksanaan']);
        $data['keterangan']        = htmlentities($post['keterangan']);
        $data['aktif']             = $post['aktif'] ?: null;

        return $data;
    }

    public function insert()
    {
        $data              = $this->validasi_data($this->input->post());
        $data['duplikasi'] = $this->input->post('duplikasi');
        $dp                = $data['duplikasi'];
        unset($data['duplikasi']);

        if ($dp == 1) {
            $dpd  = $this->db->select('id')->where('id_master', $this->session->analisis_master)->order_by('id', 'desc')->get('analisis_periode')->row_array();
            $sblm = $dpd['id'];
        }

        $akt               = [];
        $data['id_master'] = $this->session->analisis_master;
        if ($data['aktif'] == 1) {
            $akt['aktif'] = 2;
            $this->db->where('id_master', $this->session->analisis_master);
            $this->db->update('analisis_periode', $akt);
        }
        $outp = $this->db->insert('analisis_periode', $data);

        if ($dp == 1) {
            $dpd  = $this->db->select('id')->where('id_master', $this->session->analisis_master)->order_by('id', 'desc')->get('analisis_periode')->row_array();
            $skrg = $dpd['id'];

            $data = $this->db->select(['id_subjek', 'id_indikator', 'id_parameter'])->where('id_periode', $sblm)->get('analisis_respon')->result_array();

            if ($data) {
                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['id_periode'] = $skrg;
                }
                $outp = $this->db->insert_batch('analisis_respon', $data);
                $this->load->model('analisis_respon_model');
                $this->analisis_respon_model->pre_update($skrg);
            }
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0)
    {
        $data = $this->validasi_data($this->input->post());
        $akt  = [];

        $data['id_master'] = $this->session->analisis_master;
        if ($data['aktif'] == 1) {
            $akt['aktif'] = 2;
            $this->db->where('id_master', $this->session->analisis_master);
            $this->db->update('analisis_periode', $akt);
        }
        $data['id_master'] = $this->session->analisis_master;
        $this->db->where('id', $id);
        $outp = $this->db->update('analisis_periode', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->delete('analisis_periode');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function get_analisis_periode($id = 0)
    {
        $sql   = 'SELECT * FROM analisis_periode WHERE id = ?';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
    }

    public function list_state()
    {
        $sql   = 'SELECT * FROM analisis_ref_state';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function get_id_periode_aktif($id = 0)
    {
        $data = $this->db->where([
            'aktif'     => 1,
            'id_master' => $id,
        ])
            ->get('analisis_periode')
            ->row_array();

        return $data['id'];
    }
}
