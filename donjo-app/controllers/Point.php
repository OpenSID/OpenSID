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

class Point extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('plan_point_model');
        $this->modul_ini     = 9;
        $this->sub_modul_ini = 8;
    }

    public function clear()
    {
        unset($_SESSION['cari'], $_SESSION['filter']);

        redirect('point');
    }

    public function clear_simbol()
    {
        redirect('point/form_simbol');
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

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];

        $data['paging']  = $this->plan_point_model->paging($p, $o);
        $data['main']    = $this->plan_point_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword'] = $this->plan_point_model->autocomplete();

        $data['tip'] = 0;
        $this->render('point/table', $data);
    }

    public function form($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['point']       = $this->plan_point_model->get_point($id);
            $data['form_action'] = site_url("point/update/{$id}/{$p}/{$o}");
        } else {
            $data['point']       = null;
            $data['form_action'] = site_url('point/insert');
        }

        $data['simbol'] = $this->plan_point_model->list_simbol();
        $data['tip']    = 0;

        $this->render('point/form', $data);
    }

    public function sub_point($point = 1)
    {
        $data['subpoint'] = $this->plan_point_model->list_sub_point($point);
        $data['point']    = $this->plan_point_model->get_point($point);
        $data['tip']      = 0;

        $this->render('point/sub_point_table', $data);
    }

    public function ajax_add_sub_point($point = 0, $id = 0)
    {
        if ($id) {
            $data['point']       = $this->plan_point_model->get_point($id);
            $data['form_action'] = site_url("point/update_sub_point/{$point}/{$id}");
        } else {
            $data['point']       = null;
            $data['form_action'] = site_url("point/insert_sub_point/{$point}");
        }

        $data['simbol'] = $this->plan_point_model->list_simbol();
        $this->load->view('point/ajax_add_sub_point_form', $data);
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        redirect('point');
    }

    public function filter()
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }
        redirect('point');
    }

    public function insert($tip = 1)
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->insert($tip);
        redirect("point/index/{$tip}");
    }

    public function update($id = '', $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->update($id);
        redirect("point/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h', "point/index/{$p}/{$o}");
        $this->plan_point_model->delete($id);
        redirect("point/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h', "point/index/{$p}/{$o}");
        $this->plan_point_model->delete_all();
        redirect("point/index/{$p}/{$o}");
    }

    public function point_lock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->point_lock($id, 1);
        redirect("point/index/{$p}/{$o}");
    }

    public function point_unlock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->point_lock($id, 2);
        redirect("point/index/{$p}/{$o}");
    }

    public function insert_sub_point($point = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->insert_sub_point($point);
        redirect("point/sub_point/{$point}");
    }

    public function update_sub_point($point = '', $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->update_sub_point($id);
        redirect("point/sub_point/{$point}");
    }

    public function delete_sub_point($point = '', $id = '')
    {
        $this->redirect_hak_akses('h', "point/sub_point/{$point}");
        $this->plan_point_model->delete_sub_point($id);
        redirect("point/sub_point/{$point}");
    }

    public function delete_all_sub_point($point = '')
    {
        $this->redirect_hak_akses('h', "point/sub_point/{$point}");
        $this->plan_point_model->delete_all_sub_point();
        redirect("point/sub_point/{$point}");
    }

    public function point_lock_sub_point($point = '', $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->point_lock($id, 1);
        redirect("point/sub_point/{$point}");
    }

    public function point_unlock_sub_point($point = '', $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->point_lock($id, 2);
        redirect("point/sub_point/{$point}");
    }

    public function tambah_simbol()
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->tambah_simbol();
        redirect('point/form_simbol');
    }

    public function form_simbol($id = '')
    {
        $this->redirect_hak_akses('u');
        $data['simbol'] = $this->plan_point_model->list_simbol();
        $data['tip']    = 6;

        $this->render('point/form_simbol', $data);
    }

    public function delete_simbol($id = '', $simbol = '')
    {
        $this->redirect_hak_akses('h');
        $this->plan_point_model->delete_simbol($id);
        $this->plan_point_model->delete_simbol_file($simbol);
        redirect('point/form_simbol');
    }

    public function salin_simbol_default()
    {
        $this->redirect_hak_akses('u');
        $this->plan_point_model->salin_simbol_default();
        redirect('point/form_simbol');
    }
}
