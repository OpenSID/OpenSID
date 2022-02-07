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

class Grup extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('grup_model');
        $this->modul_ini     = 11;
        $this->sub_modul_ini = 44;
        $this->set_page      = ['20', '50', '100'];
        $this->list_session  = ['jenis', 'cari'];
    }

    public function clear()
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = $this->set_page[0];
        redirect($this->controller);
    }

    public function index($p = 1, $o = 0)
    {
        $this->tab_ini = 11;
        $data['p']     = $p;
        $data['o']     = $o;

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
        $data['paging']          = $this->grup_model->paging($p, $o);
        $data['main']            = $this->grup_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['list_jenis_grup'] = $this->grup_model->list_jenis_grup();
        $data['keyword']         = $this->grup_model->autocomplete();

        $this->render('grup/table', $data);
    }

    public function filter($filter)
    {
        $this->session->{$filter} = $this->input->post($filter) ?: null;
        redirect($this->controller);
    }

    public function form($p = 1, $o = 0, $id = '', $view = false)
    {
        if (! $view && in_array($id, $this->grup_model::KECUALI)) {
            session_error('Grup Pengguna Tidak Dapat Diubah');
            redirect($this->controller);
        }

        if (! $view) {
            $this->redirect_hak_akses('u');
        }
        $data['p']                   = $p;
        $data['o']                   = $o;
        $data['view']                = $view;
        $data['list_akses_modul']    = $this->grup_model->grup_akses((int) $id);
        $data['list_akses_submodul'] = $this->grup_model->akses_submodul((int) $id);
        // Centang modul jika ada akses submodul
        foreach ($data['list_akses_modul'] as $key => $akses_modul) {
            foreach ($data['list_akses_submodul'][$akses_modul['id']] as $akses_submodul) {
                if ($akses_submodul['ada_akses'] == 1) {
                    $data['list_akses_modul'][$key]['ada_akses'] = 1;
                }
            }
        }
        if ($id) {
            $data['grup']        = $this->grup_model->get_grup($id);
            $data['form_action'] = site_url("{$this->controller}/update/{$p}/{$o}/{$id}");
        } else {
            $data['grup']        = null;
            $data['form_action'] = site_url("{$this->controller}/insert");
        }

        $this->render('grup/form', $data);
    }

    public function search()
    {
        $this->session->cari = $this->input->post('cari') ?: null;
        redirect($this->controller);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->set_form_validation();
        if ($this->form_validation->run() !== true) {
            $this->session->success   = -1;
            $this->session->error_msg = trim(validation_errors());
            redirect("{$this->controller}/form");
        } else {
            $this->grup_model->insert();
            redirect($this->controller);
        }
    }

    private function set_form_validation()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nama', 'Nama Grup', 'required|callback_syarat_nama');
        $this->form_validation->set_message('nama', 'Hanya boleh berisi karakter alfanumerik, spasi dan strip');
        $this->form_validation->set_rules('modul[]', 'Akses Modul', 'required');
    }

    public function syarat_nama($str)
    {
        return ! preg_match('/[^a-zA-Z0-9 \\-]/', $str);
    }

    public function update($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->set_form_validation();

        if ($this->form_validation->run() !== true) {
            $this->session->success   = -1;
            $this->session->error_msg = trim(validation_errors());
            redirect("grup/form/{$p}/{$o}/{$id}");
        } else {
            $this->grup_model->update($id);
            redirect("grup/index/{$p}/{$o}");
        }
    }

    public function delete($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h');
        $this->grup_model->delete($id);
        redirect("grup/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h');
        $this->grup_model->delete_all();
        redirect("grup/index/{$p}/{$o}");
    }
}
