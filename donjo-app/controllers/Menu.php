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

class Menu extends Admin_Controller
{
    protected $list_session = ['cari', 'filter', 'parrent'];
    protected $set_page     = ['10', '20', '50', '100'];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('web_menu_model');
        $this->load->model('web_artikel_model');
        $this->load->model('web_kategori_model');
        $this->load->model('kelompok_model');
        $this->load->model('suplemen_model');
        $this->load->model('program_bantuan_model');
        $this->load->model('keuangan_model');
        $this->load->model('kelompok_model');
        $this->modul_ini     = 13;
        $this->sub_modul_ini = 49;
    }

    public function clear($parrent = 0)
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = $this->set_page[0];
        $this->session->parrent  = $parrent;

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0)
    {
        $data['p']   = $p;
        $data['o']   = $o;
        $data['tip'] = 1; // Hanya untuk Navigasi

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $per_page = $this->input->post('per_page');

        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['menu_utama'] = ($data['parrent'] != 0) ? $this->web_menu_model->get_menu($data['parrent']) : null; // Untuk dapatkan nama menu utama
        $data['func']       = 'index';
        $data['set_page']   = $this->set_page;
        $data['per_page']   = $this->session->per_page;
        $data['paging']     = $this->web_menu_model->paging($p);
        $data['main']       = $this->web_menu_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']    = $this->web_menu_model->autocomplete($data['cari']);

        $this->render('menu/table', $data);
    }

    public function ajax_menu($id = '')
    {
        $this->redirect_hak_akses('u');

        $parrent = $this->session->parrent;

        $data['link_tipe']                  = $this->referensi_model->list_ref(LINK_TIPE);
        $data['artikel_statis']             = $this->web_artikel_model->list_artikel_statis();
        $data['kategori_artikel']           = $this->web_kategori_model->list_kategori();
        $data['statistik_penduduk']         = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['statistik_keluarga']         = $this->referensi_model->list_ref(STAT_KELUARGA);
        $data['statistik_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
        $data['statistik_program_bantuan']  = $this->program_bantuan_model->list_program(0);
        $data['kelompok']                   = $this->kelompok_model->set_tipe('kelompok')->list_data();
        $data['lembaga']                    = $this->kelompok_model->set_tipe('lembaga')->list_data();
        $data['suplemen']                   = $this->suplemen_model->list_data();
        $data['statis_lainnya']             = $this->referensi_model->list_ref(STAT_LAINNYA);
        $data['artikel_keuangan']           = $this->keuangan_model->artikel_statis_keuangan();
        $data['menu_utama']                 = ($parrent != 0) ? $this->web_menu_model->get_menu($parrent) : null; // Untuk dapatkan nama menu utama

        if ($id) {
            $data['menu']        = $this->web_menu_model->get_menu($id);
            $data['form_action'] = site_url("{$this->controller}/update/{$id}");
        } else {
            $data['menu']        = null;
            $data['form_action'] = site_url("{$this->controller}/insert");
        }

        $this->load->view('menu/ajax_menu_form', $data);
    }

    public function search()
    {
        if ($cari = $this->input->post('cari')) {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        redirect($this->controller);
    }

    public function filter()
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }
        redirect($this->controller);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->web_menu_model->insert();
        redirect($this->controller);
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_menu_model->update($id);
        redirect($this->controller);
    }

    public function delete($id = '')
    {
        $this->redirect_hak_akses('h');
        $this->web_menu_model->delete($id);
        redirect($this->controller);
    }

    public function delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->web_menu_model->delete_all();
        redirect($this->controller);
    }

    public function menu_lock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_menu_model->menu_lock($id, 0);
        redirect($this->controller);
    }

    public function menu_unlock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_menu_model->menu_lock($id, 1);
        redirect($this->controller);
    }

    public function urut($id = 0, $arah = 0)
    {
        $this->redirect_hak_akses('u');
        $this->web_menu_model->urut($id, $arah);
        redirect($this->controller);
    }
}
