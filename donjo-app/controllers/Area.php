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

class Area extends Admin_Controller
{
    /**
     * @var array
     */
    protected $list_session = ['cari', 'filter', 'polygon', 'subpolygon'];

    /**
     * @var array
     */
    protected $set_page = ['50', '100', '200'];

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['wilayah_model', 'plan_lokasi_model', 'plan_area_model', 'plan_garis_model', 'pembangunan_model', 'pembangunan_dokumentasi_model']);
        $this->modul_ini     = 9;
        $this->sub_modul_ini = 8;
        $this->list_session;
        $this->set_page;
    }

    public function clear()
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = $this->set_page[0];
        redirect('area');
    }

    public function index($p = 1, $o = 0)
    {
        $data['p'] = $p;
        $data['o'] = $o;

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']            = 'index';
        $data['set_page']        = $this->set_page;
        $data['per_page']        = $this->session->per_page;
        $data['paging']          = $this->plan_area_model->paging($p, $o);
        $data['main']            = $this->plan_area_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']         = $this->plan_area_model->autocomplete();
        $data['list_polygon']    = $this->plan_area_model->list_polygon();
        $data['list_subpolygon'] = $this->plan_area_model->list_subpolygon();

        $data['tip'] = 4;
        $this->render('area/table', $data);
    }

    public function form($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['area']        = $this->plan_area_model->get_area($id);
            $data['form_action'] = site_url("area/update/{$id}/{$p}/{$o}");
        } else {
            $data['area']        = null;
            $data['form_action'] = site_url('area/insert');
        }

        $data['list_polygon'] = $this->plan_area_model->list_polygon();
        $data['tip']          = 4;

        $this->render('area/form', $data);
    }

    public function ajax_area_maps($p = 1, $o = 0, $id = '')
    {
        $data['p'] = $p;
        $data['o'] = $o;
        if ($id) {
            $data['area'] = $this->plan_area_model->get_area($id);
        } else {
            $data['area'] = null;
        }

        $data['desa']                   = $this->config_model->get_data();
        $data['wil_atas']               = $this->config_model->get_data();
        $data['dusun_gis']              = $this->wilayah_model->list_dusun();
        $data['rw_gis']                 = $this->wilayah_model->list_rw();
        $data['rt_gis']                 = $this->wilayah_model->list_rt();
        $data['all_lokasi']             = $this->plan_lokasi_model->list_data();
        $data['all_garis']              = $this->plan_garis_model->list_data();
        $data['all_area']               = $this->plan_area_model->list_data();
        $data['all_lokasi_pembangunan'] = $this->pembangunan_model->list_lokasi_pembangunan();
        $data['form_action']            = site_url("area/update_maps/{$p}/{$o}/{$id}");

        $this->render('area/maps', $data);
    }

    public function update_maps($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_area_model->update_position($id);
        redirect("area/index/{$p}/{$o}");
    }

    public function kosongkan($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_area_model->kosongkan_path($id);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }
        redirect('area');
    }

    public function filter()
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $this->session->filter = $filter;
        } else {
            $this->session->unset_userdata('filter');
        }
        redirect('area');
    }

    public function polygon()
    {
        $polygon = $this->input->post('polygon');
        if ($polygon != 0) {
            $this->session->polygon = $polygon;
        } else {
            $this->session->unset_userdata('polygon');
        }
        redirect('area');
    }

    public function subpolygon()
    {
        $this->session->unset_userdata('polygon');
        $subpolygon = $this->input->post('subpolygon');
        if ($subpolygon != 0) {
            $this->session->subpolygon = $subpolygon;
        } else {
            $this->session->unset_userdata('subpolygon');
        }
        redirect('area');
    }

    public function insert($tip = 1)
    {
        $this->redirect_hak_akses('u');
        if ($this->validation()) {
            $this->plan_area_model->insert($tip);
            redirect("area/index/{$tip}");
        }

        session_error(trim(validation_errors()));
        redirect('area/form');
    }

    public function update($id = '', $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u');

        if ($this->validation()) {
            $this->plan_area_model->update($id);
            redirect("area/index/{$p}/{$o}");
        }

        session_error(trim(validation_errors()));
        redirect("area/form/{$id}/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h', "area/index/{$p}/{$o}");
        $this->plan_area_model->delete($id);
        redirect("area/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h', "area/index/{$p}/{$o}");
        $this->plan_area_model->delete_all();
        redirect("area/index/{$p}/{$o}");
    }

    public function area_lock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_area_model->area_lock($id, 1);
        redirect("area/index/{$p}/{$o}");
    }

    public function area_unlock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_area_model->area_lock($id, 2);
        redirect("area/index/{$p}/{$o}");
    }

    private function validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('ref_polygon', 'Kategori', 'required');
        $this->form_validation->set_rules('desk', 'Keterangan', 'required|trim');
        $this->form_validation->set_rules('enabled', 'Status', 'required');

        return $this->form_validation->run();
    }
}
