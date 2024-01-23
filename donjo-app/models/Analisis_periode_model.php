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

class Analisis_periode_model extends MY_Model
{
    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'analisis_periode');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->like('u.nama', $cari);
        }
    }

    private function master_sql(): void
    {
        if ($analisis_master = $this->session->analisis_master) {
            $this->db->where('u.id_master', $analisis_master);
        }
    }

    private function state_sql(): void
    {
        if ($state = $this->session->state) {
            $this->db->where('u.id_state', $state);
        }
    }

    private function order_sql($order = ''): void
    {
        switch ($order) {
            case 1:

            case 3: $this->db->order_by('u.id');
                break;

            case 2:

            case 4: $this->db->order_by('u.id', 'desc');
                break;

            case 5: $this->db->order_by('g.id');
                break;

            case 6: $this->db->order_by('g.id', 'desc');
                break;

            default: $this->db->order_by('u.id');
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_query();
        $jml_data = $this->db
            ->get()
            ->num_rows();

        return $this->paginasi($p, $jml_data);
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->db->select('u.*, s.nama as status');
        $this->list_data_query();
        $this->order_sql($o);

        $data = $this->db->get()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']    = $j + 1;
            $data[$i]['aktif'] = $data[$i]['aktif'] == 1 ? '<img src="' . base_url('assets/images/icon/tick.png') . '">' : '';
            $j++;
        }

        return $data;
    }

    private function list_data_query(): void
    {
        $this->config_id('u')
            ->from('analisis_periode u')
            ->join('analisis_ref_state s', 'u.id_state = s.id', 'left');
        $this->search_sql();
        $this->master_sql();
        $this->state_sql();
    }

    private function validasi_data($post)
    {
        return ['nama' => nomor_surat_keputusan($post['nama']), 'id_state' => $post['id_state'] ?: null, 'tahun_pelaksanaan' => bilangan($post['tahun_pelaksanaan']), 'keterangan' => htmlentities($post['keterangan']), 'aktif' => $post['aktif'] ?: null];
    }

    public function insert(): void
    {
        $data              = $this->validasi_data($this->input->post());
        $data['config_id'] = $this->config_id;
        $data['duplikasi'] = $this->input->post('duplikasi');
        $dp                = $data['duplikasi'];
        unset($data['duplikasi']);

        if ($dp == 1) {
            $dpd  = $this->config_id()->select('id')->where('id_master', $this->session->analisis_master)->order_by('id', 'desc')->get('analisis_periode')->row_array();
            $sblm = $dpd['id'];
        }

        $akt               = [];
        $data['id_master'] = $this->session->analisis_master;
        if ($data['aktif'] == 1) {
            $akt['aktif'] = 2;
            $this->config_id()->where('id_master', $this->session->analisis_master)->update('analisis_periode', $akt);
        }
        $outp = $this->db->insert('analisis_periode', $data);

        if ($dp == 1) {
            $dpd  = $this->config_id()->select('id')->where('id_master', $this->session->analisis_master)->order_by('id', 'desc')->get('analisis_periode')->row_array();
            $skrg = $dpd['id'];

            $data = $this->db->select(['id_subjek', 'id_indikator', 'id_parameter'])->where('id_periode', $sblm)->get('analisis_respon')->result_array();

            if ($data) {
                $counter = count($data);

                for ($i = 0; $i < $counter; $i++) {
                    $data[$i]['id_periode'] = $skrg;
                }
                $outp = $this->db->insert_batch('analisis_respon', $data);
                $this->load->model('analisis_respon_model');
                $this->analisis_respon_model->pre_update($skrg);
            }
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0): void
    {
        $data = $this->validasi_data($this->input->post());
        $akt  = [];

        $data['id_master'] = $this->session->analisis_master;
        if ($data['aktif'] == 1) {
            $akt['aktif'] = 2;
            $this->config_id()->where('id_master', $this->session->analisis_master)->update('analisis_periode', $akt);
        }
        $data['id_master'] = $this->session->analisis_master;
        $outp              = $this->config_id()->where('id', $id)->update('analisis_periode', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('analisis_periode');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function get_analisis_periode($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('analisis_periode')
            ->row_array();
    }

    public function list_state()
    {
        return $this->db
            ->get('analisis_ref_state')
            ->result_array();
    }

    public function get_id_periode_aktif($id = 0)
    {
        $data = $this->config_id()
            ->where([
                'aktif'     => 1,
                'id_master' => $id,
            ])
            ->get('analisis_periode')
            ->row_array();

        return $data['id'];
    }
}
