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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_kategori extends Admin_Controller
{
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'master-analisis';

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        if (! $this->session->has_userdata('analisis_master')) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Pilih master analisis terlebih dahulu';

            redirect('analisis_master');
        }

        $this->load->model(['analisis_kategori_model', 'analisis_master_model']);
        $this->session->submenu  = 'Data Kategori';
        $this->session->asubmenu = 'analisis_kategori';
        $this->set_page          = ['20', '50', '100'];
        $this->list_session      = ['cari', 'filter', 'state'];
    }

    public function clear(): void
    {
        $this->session->unset_userdata(['cari']);

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0): void
    {
        unset($_SESSION['cari2']);
        $data['p'] = $p;
        $data['o'] = $o;

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }
        $data['func']     = 'index';
        $data['set_page'] = $this->set_page;
        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->analisis_kategori_model->paging($p, $o);
        $data['main']     = $this->analisis_kategori_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);

        $data['keyword']         = $this->analisis_kategori_model->autocomplete();
        $data['analisis_master'] = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);

        $this->render('analisis_kategori/table', $data);
    }

    public function form($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['analisis_kategori'] = $this->analisis_kategori_model->get_analisis_kategori($id) ?? show_404();
            $data['form_action']       = site_url("{$this->controller}/update/{$p}/{$o}/{$id}");
        } else {
            $data['analisis_kategori'] = null;
            $data['form_action']       = site_url("{$this->controller}/insert");
        }

        $this->load->view('analisis_kategori/ajax_form', $data);
    }

    public function search(): void
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }

        redirect($this->controller);
    }

    public function insert(): void
    {
        isCan('u');
        $this->analisis_kategori_model->insert();

        redirect($this->controller);
    }

    public function update($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $this->analisis_kategori_model->update($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = 0): void
    {
        isCan('h');
        $this->analisis_kategori_model->delete($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0): void
    {
        isCan('h');
        $this->analisis_kategori_model->delete_all();

        redirect("{$this->controller}/index/{$p}/{$o}");
    }
}
