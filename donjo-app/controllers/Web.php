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

class Web extends Admin_Controller
{
    private $_set_page;

    public function __construct()
    {
        parent::__construct();
        // Jika offline_mode dalam level yang menyembunyikan website,
        // tidak perlu menampilkan halaman website
        if ($this->setting->offline_mode >= 2) {
            redirect('hom_sid');

            exit;
        }

        $this->load->model(['web_artikel_model', 'web_kategori_model']);
        $this->_set_page     = ['20', '50', '100'];
        $this->modul_ini     = 13;
        $this->sub_modul_ini = 47;
    }

    public function clear()
    {
        $this->session->unset_userdata(['cari, status']);
        $this->session->per_page = $this->_set_page[0];
        $this->session->kategori = -1;
        redirect('web');
    }

    public function index($p = 1, $o = 0)
    {
        $cat = $this->session->kategori ?: -1;

        $data['p'] = $p;
        $data['o'] = $o;

        $data['cat']    = $cat;
        $data['cari']   = $this->session->cari ?: '';
        $data['status'] = $this->session->status ?: '';

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']     = 'index';
        $data['per_page'] = $this->session->per_page;
        $data['set_page'] = $this->_set_page;

        $paging                = $this->web_artikel_model->paging($cat, $p, $o);
        $data['main']          = $this->web_artikel_model->list_data($cat, $o, $paging->offset, $paging->per_page);
        $data['keyword']       = $this->web_artikel_model->autocomplete($cat);
        $data['list_kategori'] = $this->web_artikel_model->list_kategori();
        $data['kategori']      = $this->web_artikel_model->get_kategori($cat);
        $data                  = $this->security->xss_clean($data);
        $data['paging']        = $paging;

        $this->render('web/artikel/table', $data);
    }

    public function tab($cat = 0)
    {
        $this->session->kategori = $cat;

        redirect('web');
    }

    public function form($id = 0)
    {
        $this->redirect_hak_akses('u');
        $cat = $this->session->kategori ?: 0;

        if ($id) {
            $cek_data = $this->web_artikel_model->get_artikel($id);
            if (! $cek_data) {
                show_404();
            }

            if (! $this->web_artikel_model->boleh_ubah($id, $this->session->user)) {
                redirect('web');
            }

            $this->session->kategori = $cek_data['id_kategori'];
            $data['artikel']         = $cek_data;
            $data['form_action']     = site_url("web/update/{$id}");
        } else {
            $data['artikel']     = null;
            $data['form_action'] = site_url('web/insert');
        }

        $data['cat']      = $cat;
        $data['kategori'] = $this->web_artikel_model->get_kategori($cat);

        $this->render('web/artikel/form', $data);
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('web');
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $cat = $this->session->kategori ?: 0;

        $this->web_artikel_model->insert($cat);
        redirect('web');
    }

    public function update($id = 0)
    {
        $this->redirect_hak_akses('u');
        $cat = $this->session->kategori ?: 0;

        if (! $this->web_artikel_model->boleh_ubah($id, $this->session->user)) {
            redirect('web');
        }

        $this->web_artikel_model->update($cat, $id);
        if ($this->session->success == -1) {
            redirect("web/form/{$id}");
        } else {
            redirect('web');
        }
    }

    public function delete($id = 0)
    {
        $this->redirect_hak_akses('h');
        $this->web_artikel_model->delete($id);
        redirect('web');
    }

    public function delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->web_artikel_model->delete_all();
        redirect('web');
    }

    // TODO: Pindahkan ke controller kategori
    public function hapus()
    {
        $this->redirect_hak_akses('u');
        $cat = $this->session->kategori ?: 0;

        $this->redirect_hak_akses('h');
        $this->web_artikel_model->hapus($cat);
        $this->session->kategori = 0;
        redirect('web');
    }

    // TODO: Pindahkan ke controller kategoris
    public function ubah_kategori_form($id = 0)
    {
        $this->redirect_hak_akses('u');
        if (! $this->web_artikel_model->boleh_ubah($id, $this->session->user)) {
            redirect('web');
        }

        $data['list_kategori']     = $this->web_kategori_model->list_kategori('kategori');
        $data['form_action']       = site_url("web/update_kategori/{$id}");
        $data['kategori_sekarang'] = $this->web_artikel_model->get_kategori_artikel($id);
        $this->load->view('web/artikel/ajax_ubah_kategori_form', $data);
    }

    public function update_kategori($id = 0)
    {
        $this->redirect_hak_akses('u');
        if (! $this->web_artikel_model->boleh_ubah($id, $this->session->user)) {
            redirect('web');
        }

        $cat = $this->input->post('kategori');
        $this->web_artikel_model->update_kategori($id, $cat);
        $this->session->kategori = $cat;
        redirect('web');
    }

    public function artikel_lock($id = 0, $val = 1)
    {
        // Kontributor tidak boleh mengubah status aktif artikel
        $this->redirect_hak_akses('u');

        $this->web_artikel_model->artikel_lock($id, $val);
        redirect('web');
    }

    public function komentar_lock($id = 0, $val = 1)
    {
        // Kontributor tidak boleh mengubah status komentar artikel
        $this->redirect_hak_akses('u');

        $this->web_artikel_model->komentar_lock($id, $val);
        redirect('web');
    }

    // TODO: Pindahkan ke controller kategori
    public function ajax_add_kategori($cat = 1, $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u');
        $data['form_action'] = site_url("web/insert_kategori/{$cat}/{$p}/{$o}");
        $this->load->view('web/artikel/ajax_add_kategori_form', $data);
    }

    // TODO: Pindahkan ke controller kategori
    public function insert_kategori($cat = 1, $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u', "web/index/{$cat}/{$p}/{$o}", 'kategori');
        $this->web_artikel_model->insert_kategori();
        redirect("web/index/{$cat}/{$p}/{$o}");
    }

    public function headline($id = 0)
    {
        // Kontributor tidak boleh melakukan ini
        $this->redirect_hak_akses('u');

        $this->web_artikel_model->headline($id);
        redirect('web');
    }

    public function slide($id = 0)
    {
        // Kontributor tidak boleh melakukan ini
        $this->redirect_hak_akses('u');

        $this->web_artikel_model->slide($id);
        redirect('web');
    }

    public function slider()
    {
        $this->sub_modul_ini = 54;

        $this->render('slider/admin_slider.php');
    }

    public function update_slider()
    {
        // Kontributor tidak boleh melakukan ini
        $this->redirect_hak_akses('u');

        $this->setting_model->update_slider();
        redirect('web/slider');
    }

    public function teks_berjalan()
    {
        $this->sub_modul_ini = 64;

        $this->render('web/admin_teks_berjalan.php');
    }

    public function update_teks_berjalan()
    {
        // Kontributor tidak boleh melakukan ini
        $this->redirect_hak_akses('u');

        $this->setting_model->update_teks_berjalan();
        redirect('web/teks_berjalan');
    }

    public function reset()
    {
        $this->redirect_hak_akses('u');
        $cat = $this->session->kategori ?: 0;

        if ($cat == 999) {
            $this->web_artikel_model->reset($cat);
        }

        redirect('web');
    }
}
