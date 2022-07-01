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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Pembangunan_dokumentasi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 220;
        $this->load->library('upload');
        $this->load->model('pembangunan_model');
        $this->load->model('pembangunan_dokumentasi_model', 'model');
    }

    public function show($id = null)
    {
        $pembangunan                = $this->pembangunan_model->find($id);
        $_SESSION['id_pembangunan'] = $id;

        if ($this->input->is_ajax_request()) {
            $start  = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search[value]');
            $order  = $this->model::ORDER_ABLE[$this->input->post('order[0][column]')];
            $dir    = $this->input->post('order[0][dir]');

            $this->json_output([
                'draw'            => $this->input->post('draw'),
                'recordsTotal'    => $this->model->get_data($id)->count_all_results(),
                'recordsFiltered' => $this->model->get_data($id, $search)->count_all_results(),
                'data'            => $this->model->get_data($id, $search)->order_by($order, $dir)->limit($length, $start)->get()->result(),
            ]);
        }

        $this->render(ADMIN . '/pembangunan/dokumentasi/index', [
            'pembangunan' => $pembangunan,
        ]);
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');
        $id_pembangunan = $this->session->id_pembangunan;

        if ($id) {
            $data['main']        = $this->model->find($id);
            $data['form_action'] = site_url("{$this->controller}/update/{$id}/{$id_pembangunan}");
        } else {
            $data['main']        = null;
            $data['form_action'] = site_url("{$this->controller}/insert/{$id_pembangunan}");
        }

        $data['id_pembangunan'] = $id_pembangunan;
        $data['persentase']     = $this->referensi_model->list_ref(STATUS_PEMBANGUNAN);

        $this->render(ADMIN . '/pembangunan/dokumentasi/form', $data);
    }

    public function insert($id_pembangunan = '')
    {
        $this->redirect_hak_akses('u');
        $this->model->insert($id_pembangunan);
        redirect("{$this->controller}/show/{$id_pembangunan}");
    }

    public function update($id = '', $id_pembangunan = '')
    {
        $this->redirect_hak_akses('u');
        $this->model->update($id, $id_pembangunan);
        redirect("{$this->controller}/show/{$id_pembangunan}");
    }

    public function delete($id_pembangunan, $id)
    {
        $this->redirect_hak_akses('h');
        $this->model->delete($id);

        if ($this->db->affected_rows()) {
            $this->session->success = 4;
        } else {
            $this->session->success = -4;
        }

        redirect("{$this->controller}/show/{$id_pembangunan}");
    }
}
