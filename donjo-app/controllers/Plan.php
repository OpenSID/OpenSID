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

class Plan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('wilayah_model');
        $this->load->model('plan_lokasi_model');
        $this->load->model('plan_area_model');
        $this->load->model('plan_garis_model');
        $this->load->model('pembangunan_model');
        $this->load->model('pembangunan_dokumentasi_model');
        $this->modul_ini     = 9;
        $this->sub_modul_ini = 8;
    }

    public function clear()
    {
        unset($_SESSION['cari'], $_SESSION['filter'], $_SESSION['point'], $_SESSION['subpoint']);

        redirect('plan');
    }

    public function index($p = 1, $o = 0)
    {
        $data['p'] = $p;
        $data['o'] = $o;

        if (isset($_SESSION['cari'])) {
            $data['cari'] = $_SESSION['cari'];
        } else {
            $data['cari'] = '';
        }

        if (isset($_SESSION['filter'])) {
            $data['filter'] = $_SESSION['filter'];
        } else {
            $data['filter'] = '';
        }

        if (isset($_SESSION['point'])) {
            $data['point'] = $_SESSION['point'];
        } else {
            $data['point'] = '';
        }

        if (isset($_SESSION['subpoint'])) {
            $data['subpoint'] = $_SESSION['subpoint'];
        } else {
            $data['subpoint'] = '';
        }

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }

        $data['per_page'] = $_SESSION['per_page'];

        $data['paging']        = $this->plan_lokasi_model->paging($p, $o);
        $data['main']          = $this->plan_lokasi_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']       = $this->plan_lokasi_model->autocomplete();
        $data['list_point']    = $this->plan_lokasi_model->list_point();
        $data['list_subpoint'] = $this->plan_lokasi_model->list_subpoint();
        $data['tip']           = 3;

        $this->render('lokasi/table', $data);
    }

    public function form($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['lokasi']      = $this->plan_lokasi_model->get_lokasi($id);
            $data['form_action'] = site_url("plan/update/{$id}/{$p}/{$o}");
        } else {
            $data['lokasi']      = null;
            $data['form_action'] = site_url('plan/insert');
        }

        $data['list_point'] = $this->plan_lokasi_model->list_point();
        $data['tip']        = 3;

        $this->render('lokasi/form', $data);
    }

    public function ajax_lokasi_maps($p = 1, $o = 0, $id = '')
    {
        $data['p'] = $p;
        $data['o'] = $o;
        if ($id) {
            $data['lokasi'] = $this->plan_lokasi_model->get_lokasi($id);
        } else {
            $data['lokasi'] = null;
        }

        $data['desa']                   = $this->config_model->get_data();
        $sebutan_desa                   = ucwords($this->setting->sebutan_desa);
        $data['wil_atas']               = $this->config_model->get_data();
        $data['dusun_gis']              = $this->wilayah_model->list_dusun();
        $data['rw_gis']                 = $this->wilayah_model->list_rw();
        $data['rt_gis']                 = $this->wilayah_model->list_rt();
        $data['all_lokasi']             = $this->plan_lokasi_model->list_data();
        $data['all_garis']              = $this->plan_garis_model->list_data();
        $data['all_area']               = $this->plan_area_model->list_data();
        $data['all_lokasi_pembangunan'] = $this->pembangunan_model->list_lokasi_pembangunan();
        $data['form_action']            = site_url("plan/update_maps/{$p}/{$o}/{$id}");

        $this->render('lokasi/maps', $data);
    }

    public function update_maps($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_lokasi_model->update_position($id);
        redirect("plan/index/{$p}/{$o}");
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        redirect('plan');
    }

    public function filter()
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }
        redirect('plan');
    }

    public function point()
    {
        $point = $this->input->post('point');
        if ($point != 0) {
            $_SESSION['point'] = $point;
        } else {
            unset($_SESSION['point']);
        }
        redirect('plan');
    }

    public function subpoint()
    {
        unset($_SESSION['point']);
        $subpoint = $this->input->post('subpoint');
        if ($subpoint != 0) {
            $_SESSION['subpoint'] = $subpoint;
        } else {
            unset($_SESSION['subpoint']);
        }
        redirect('plan');
    }

    public function insert($tip = 1)
    {
        $this->redirect_hak_akses('u');
        $this->plan_lokasi_model->insert($tip);
        redirect("plan/index/{$tip}");
    }

    public function update($id = '', $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u');
        $this->plan_lokasi_model->update($id);
        redirect("plan/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h', "plan/index/{$p}/{$o}");
        $this->plan_lokasi_model->delete($id);
        redirect("plan/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h', "plan/index/{$p}/{$o}");
        $this->plan_lokasi_model->delete_all();
        redirect("plan/index/{$p}/{$o}");
    }

    public function lokasi_lock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_lokasi_model->lokasi_lock($id, 1);
        redirect("plan/index/{$p}/{$o}");
    }

    public function lokasi_unlock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_lokasi_model->lokasi_lock($id, 2);
        redirect("plan/index/{$p}/{$o}");
    }
}
