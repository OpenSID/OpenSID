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

class Pengaduan_admin extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 334;
        $this->load->model('pengaduan_model');
    }

    public function index()
    {
        $data['allstatus'] = $this->pengaduan_model->get_data()->count_all_results();
        $data['status1']   = $this->pengaduan_model->get_data('1')->count_all_results();
        $data['status2']   = $this->pengaduan_model->get_data('2')->count_all_results();
        $data['status3']   = $this->pengaduan_model->get_data('3')->count_all_results();

        $data['m_allstatus'] = $this->pengaduan_model->get_data_month()->count_all_results();
        $data['m_status1']   = $this->pengaduan_model->get_data_month('1')->count_all_results();
        $data['m_status2']   = $this->pengaduan_model->get_data_month('2')->count_all_results();
        $data['m_status3']   = $this->pengaduan_model->get_data_month('3')->count_all_results();

        if ($this->input->is_ajax_request()) {
            $start  = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search[value]');
            $order  = $this->pengaduan_model::ORDER_ABLE_PENGADUAN[$this->input->post('order[0][column]')];
            $dir    = $this->input->post('order[0][dir]');
            $status = $this->input->post('status');

            return json([
                'draw'            => $this->input->post('draw'),
                'recordsTotal'    => $this->pengaduan_model->get_pengaduan_a('', $status)->count_all_results(),
                'recordsFiltered' => $this->pengaduan_model->get_pengaduan_a($search, $status)->count_all_results(),
                'data'            => $this->pengaduan_model->get_pengaduan_a($search, $status)->order_by($order, $dir)->limit($length, $start)->get()->result(),
            ]);
        }

        $this->render('pengaduan_admin/index', $data);
    }

    public function kirim($id)
    {
        $this->redirect_hak_akses('u');
        $this->pengaduan_model->m_insert($id);

        redirect($this->controller);
    }

    public function pengaduan_form($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $data['main']        = $this->pengaduan_model->pengaduan_detail($id) ?? show_404();
            $data['form_action'] = site_url("{$this->controller}/kirim/{$id}");
        }

        $this->load->view('pengaduan_admin/modal_form', $data);
    }

    public function pengaduan_form_detail($id = '')
    {
        if ($id) {
            $data['pengaduana'] = $this->pengaduan_model->pengaduan_detailna($id);
        }

        $this->load->view('pengaduan_admin/modal_detail', $data);
    }

    public function pengaduan_insert()
    {
        $this->redirect_hak_akses('u');
        $this->pengaduan_model->pengaduan_insert();

        redirect($this->controller);
    }

    public function pengaduan_update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->pengaduan_model->pengaduan_update($id);
        redirect($this->controller);
    }

    public function pengaduan_delete($id)
    {
        $this->redirect_hak_akses('h');
        $this->pengaduan_model->pengaduan_delete($id);

        redirect($this->controller);
    }

    public function pengaduan_delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->pengaduan_model->pengaduan_delete_all();

        redirect($this->controller);
    }

    public function pengaduan_status($id = 0, $status = 0)
    {
        $this->redirect_hak_akses('u');
        $this->pengaduan_model->status('produk_pengaduan', $id, $status);

        redirect($this->controller);
    }
}
