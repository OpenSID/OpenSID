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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Pamong;
use App\Models\UserGrup;

class Man_user extends Admin_Controller
{
    private $_set_page;
    private $_list_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->modul_ini     = 'pengaturan';
        $this->sub_modul_ini = 'pengguna';
        $this->_set_page     = ['10', '50', '100', '200'];
        $this->_list_session = ['cari', 'filter', 'group'];
    }

    public function clear()
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = $this->_set_page[0];
        $this->session->filter   = 'active';

        redirect('man_user');
    }

    public function index($p = 1, $o = 0)
    {
        $this->tab_ini = 10;
        $data['p']     = $p;
        $data['o']     = $o;

        foreach ($this->_list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']     = 'index';
        $data['set_page'] = $this->_set_page;
        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->user_model->paging($p, $o);
        $data['main']     = $this->user_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']  = $this->user_model->autocomplete();
        $data['status']   = [
            ['id' => 'active', 'nama' => 'Aktif'],
            ['id' => 'inactive', 'nama' => 'Tidak Aktif'],
        ];
        $data['user_group'] = $this->referensi_model->list_data('user_grup');

        $this->render('man_user/manajemen_user_table', $data);
    }

    public function form($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['user']        = $this->user_model->get_user($id) ?? show_404();
            $data['form_action'] = site_url("man_user/update/{$p}/{$o}/{$id}");
        } else {
            $data['user']        = null;
            $data['form_action'] = site_url('man_user/insert');
        }

        $data['user_group'] = UserGrup::get(['id', 'nama']);
        $data['akses']      = UserGrup::getGrupSistem();
        $data['pamong']     = Pamong::selectData()->daftar()->get();

        $this->render('man_user/manajemen_user_form', $data);
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        redirect('man_user');
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);

        if ($value !== '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('man_user');
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->set_form_validation();
        $this->form_validation->set_rules('username', 'Username', 'is_unique[user.username]');
        $this->form_validation->set_rules('email', 'Email', 'is_unique[user.email]');
        $this->form_validation->set_rules([
            [
                'field'  => 'pamong_id',
                'label'  => 'Pamong',
                'rules'  => 'is_unique[user.pamong_id]',
                'errors' => [
                    'is_unique' => 'pengguna tersebut sudah ada',
                ],
            ],
        ]);

        if ($this->form_validation->run() !== true) {
            session_error(trim(validation_errors()));
            redirect('man_user/form');
        } else {
            $this->user_model->insert();

            redirect('man_user');
        }
    }

    private function set_form_validation()
    {
        $this->form_validation->set_rules('password', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
        $this->form_validation->set_message('syarat_sandi', 'Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');
    }

    // Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
    public function syarat_sandi($str)
    {
        // radiisi berarti tidak sandi tidak diubah
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', $str) || $str == 'radiisi');
    }

    public function update($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        if ($this->input->post('password') != '') {
            $this->set_form_validation();
        }
        $this->form_validation->set_rules('username', 'Username', "is_unique[user.username,id,{$id}]");
        $this->form_validation->set_rules('email', 'Email', "is_unique[user.email,id,{$id}]");
        $this->form_validation->set_rules([
            [
                'field'  => 'pamong_id',
                'label'  => 'Pamong',
                'rules'  => "is_unique[user.pamong_id,id,{$id}]",
                'errors' => [
                    'is_unique' => 'pengguna tersebut sudah ada',
                ],
            ],
        ]);

        if ($this->form_validation->run() !== true) {
            session_error(trim(validation_errors()));

            redirect("man_user/form/{$p}/{$o}/{$id}");
        } else {
            $this->user_model->update($id);
            redirect("man_user/index/{$p}/{$o}");
        }
    }

    public function delete($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h');
        $this->user_model->delete($id);
        redirect("man_user/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h');
        $this->user_model->delete_all();
        redirect("man_user/index/{$p}/{$o}");
    }

    public function user_lock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->user_model->user_lock($id, 0);
        redirect('man_user');
    }

    public function user_unlock($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->user_model->user_lock($id, 1);
        redirect('man_user');
    }
}
