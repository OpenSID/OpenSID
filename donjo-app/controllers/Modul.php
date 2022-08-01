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

class Modul extends Admin_Controller
{
    private $list_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['modul_model']);
        $this->modul_ini     = 11;
        $this->sub_modul_ini = 42;
        $this->list_session  = ['status', 'cari', 'module'];
    }

    public function clear()
    {
        $this->session->unset_userdata($this->list_session);
        redirect('modul');
    }

    public function index()
    {
        $id = $this->session->module;

        if (! $id) {
            foreach ($this->list_session as $list) {
                $data[$list] = $this->session->{$list} ?: '';
            }

            $data['sub_modul'] = null;
            $data['main']      = $this->modul_model->list_data();
            $data['keyword']   = $this->modul_model->autocomplete();
        } else {
            $data['sub_modul'] = $this->modul_model->get_data($id);
            $data['main']      = $this->modul_model->list_sub_modul($id);
        }

        $this->render('setting/modul/table', $data);
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');
        $data['list_icon'] = $this->modul_model->list_icon();
        if ($id) {
            $data['modul']       = $this->modul_model->get_data($id);
            $data['form_action'] = site_url("modul/update/{$id}");
        } else {
            $data['modul']       = null;
            $data['form_action'] = site_url('modul/insert');
        }

        $this->render('setting/modul/form', $data);
    }

    public function sub_modul($id = '')
    {
        $this->session->module = $id;

        redirect('modul');
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('modul');
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->modul_model->update($id);
        $parent = $this->input->post('parent');
        if ($parent == 0) {
            redirect('modul');
        } else {
            redirect("modul/sub_modul/{$parent}");
        }
    }

    public function lock($id = 0, $val = 1)
    {
        $this->redirect_hak_akses('u');
        $this->modul_model->lock($id, $val);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function ubah_server()
    {
        $this->redirect_hak_akses('u');
        $this->setting_model->update_penggunaan_server();
        redirect('modul');
    }

    public function default_server()
    {
        $this->redirect_hak_akses('u');
        $this->modul_model->default_server();
        $this->clear();
    }
}
