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

class Admin_pembangunan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 220;
        $this->load->library('upload');
        $this->load->model('pembangunan_model', 'model');
        $this->load->model('pembangunan_dokumentasi_model');
        $this->load->model('wilayah_model');
        $this->load->model('pamong_model');
        $this->load->model('plan_lokasi_model');
        $this->load->model('plan_area_model');
        $this->load->model('plan_garis_model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $start  = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search[value]');
            $order  = $this->model::ORDER_ABLE[$this->input->post('order[0][column]')];
            $dir    = $this->input->post('order[0][dir]');
            $tahun  = $this->input->post('tahun');

            $this->model->set_tipe(''); // Ambil semua pembangunan

            $this->json_output([
                'draw'            => $this->input->post('draw'),
                'recordsTotal'    => $this->model->get_data()->count_all_results(),
                'recordsFiltered' => $this->model->get_data($search, $tahun)->count_all_results(),
                'data'            => $this->model->get_data($search, $tahun)->order_by($order, $dir)->limit($length, $start)->get()->result(),
            ]);
        }

        $this->render(ADMIN . '/pembangunan/index', [
            'list_tahun' => $this->model->list_filter_tahun(),
        ]);
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');
        if ($id) {
            $data['main']        = $this->model->find($id);
            $data['form_action'] = site_url("{$this->controller}/update/{$id}");
        } else {
            $data['main'] = null;

            $data['form_action'] = site_url("{$this->controller}/insert");
        }

        $data['list_lokasi'] = $this->wilayah_model->list_semua_wilayah();
        $data['sumber_dana'] = $this->referensi_model->list_ref(SUMBER_DANA);

        $this->render(ADMIN . '/pembangunan/form', $data);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->model->insert();
        redirect($this->controller);
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->model->update($id);
        redirect($this->controller);
    }

    public function delete($id)
    {
        $this->redirect_hak_akses('h');
        $this->model->delete($id);

        if ($this->db->affected_rows()) {
            $this->session->success = 4;
        } else {
            $this->session->success = -4;
        }

        redirect($this->controller);
    }

    public function lokasi_maps($id)
    {
        $data = $this->model->find($id);

        if (null === $data) {
            show_404();
        }

        // Update lokasi maps
        if ($request = $this->input->post()) {
            $this->redirect_hak_akses('u');
            $this->model->update_lokasi_maps($id, $request);

            $this->session->success = 1;

            redirect($this->controller);
        }

        $this->render(ADMIN . '/pembangunan/lokasi_maps', [
            'data'                   => $data,
            'desa'                   => $this->header['desa'],
            'wil_atas'               => $this->header['desa'],
            'dusun_gis'              => $this->wilayah_model->list_dusun(),
            'rw_gis'                 => $this->wilayah_model->list_rw(),
            'rt_gis'                 => $this->wilayah_model->list_rt(),
            'all_lokasi'             => $this->plan_lokasi_model->list_lokasi(),
            'all_garis'              => $this->plan_garis_model->list_garis(),
            'all_area'               => $this->plan_area_model->list_area(),
            'all_lokasi_pembangunan' => $this->model->list_lokasi_pembangunan(),
        ]);
    }

    public function dialog_daftar($id = 0, $aksi = '')
    {
        $this->load->view('global/ttd_pamong', [
            'aksi'           => $aksi,
            'pamong'         => $this->pamong_model->list_data(),
            'pamong_ttd'     => $this->pamong_model->get_ub(),
            'pamong_ketahui' => $this->pamong_model->get_ttd(),
            'form_action'    => site_url("{$this->controller}/daftar/{$id}/{$aksi}"),
        ]);
    }

    public function daftar($id = 0, $aksi = '')
    {
        $request = $this->input->post();

        $pembangunan = $this->model->find($id);
        $dokumentasi = $this->pembangunan_dokumentasi_model->find_dokumentasi($pembangunan->id);

        $data['pembangunan']    = $pembangunan;
        $data['dokumentasi']    = $dokumentasi;
        $data['config']         = $this->header['desa'];
        $data['pamong_ttd']     = $this->pamong_model->get_data($request['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($request['pamong_ketahui']);
        $data['aksi']           = $aksi;
        $data['file']           = 'Laporan Pembangunan';
        $data['isi']            = ADMIN . '/pembangunan/cetak';

        $this->load->view('global/format_cetak', $data);
    }

    public function unlock($id)
    {
        $this->model->unlock($id);

        $this->session->success = 1;

        redirect($this->controller);
    }

    public function lock($id)
    {
        $this->redirect_hak_akses('u');
        $this->model->lock($id);

        $this->session->success = 1;

        redirect($this->controller);
    }
}
