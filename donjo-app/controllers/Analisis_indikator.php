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

class Analisis_indikator extends Admin_Controller
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

        $this->load->model(['analisis_indikator_model', 'analisis_parameter_model', 'analisis_master_model']);
        $this->session->submenu  = 'Data Indikator';
        $this->session->asubmenu = 'analisis_indikator';
    }

    public function clear(): void
    {
        $this->session->unset_userdata(['cari', 'filter', 'tipe', 'kategori']);

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0): void
    {
        unset($_SESSION['cari2']);
        $data['p'] = $p;
        $data['o'] = $o;

        $data['cari']     = $_SESSION['cari'] ?? '';
        $data['filter']   = $_SESSION['filter'] ?? '';
        $data['tipe']     = $_SESSION['tipe'] ?? '';
        $data['kategori'] = $_SESSION['kategori'] ?? '';
        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];

        $data['paging']          = $this->analisis_indikator_model->paging($p, $o);
        $data['main']            = $this->analisis_indikator_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']         = $this->analisis_indikator_model->autocomplete();
        $data['analisis_master'] = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['list_tipe']       = $this->analisis_indikator_model->list_tipe();
        $data['list_kategori']   = $this->analisis_indikator_model->list_kategori();

        $this->render('analisis_indikator/table', $data);
    }

    public function form($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['analisis_indikator'] = $this->analisis_indikator_model->get_analisis_indikator($id) ?? show_404();
            $data['form_action']        = site_url("{$this->controller}/update/{$p}/{$o}/{$id}");

            // Cek apakah ada pilihan untuk id_tipe 1 dan 2
            $data['ubah'] = ($this->analisis_indikator_model->list_indikator($id) && in_array($data['analisis_indikator']['id_tipe'], [1, 2])) ? false : true;
        } else {
            $data['analisis_indikator'] = null;
            $data['form_action']        = site_url("{$this->controller}/insert");

            $data['ubah'] = true;
        }

        $data['list_kategori']   = $this->analisis_indikator_model->list_kategori();
        $data['analisis_master'] = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['data_tabel']      = $this->analisis_indikator_model->data_tabel($this->session->subjek_tipe);

        $this->render('analisis_indikator/form', $data);
    }

    public function parameter($id = 0): void
    {
        $ai = $this->analisis_indikator_model->get_analisis_indikator($id) ?? show_404();
        if ($ai['id_tipe'] == 3 || $ai['id_tipe'] == 4) {
            redirect($this->controller);
        }

        $data['analisis_indikator'] = $ai;
        $data['analisis_master']    = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['main']               = $this->analisis_indikator_model->list_indikator($id);

        $this->render('analisis_indikator/parameter/table', $data);
    }

    public function form_parameter($in = '', $id = 0): void
    {
        isCan('u');
        if ($id) {
            $data['analisis_parameter'] = $this->analisis_indikator_model->get_analisis_parameter($id) ?? show_404();
            $data['form_action']        = site_url("{$this->controller}/p_update/{$in}/{$id}");
        } else {
            $data['analisis_parameter'] = null;
            $data['form_action']        = site_url("{$this->controller}/p_insert/{$in}");
        }

        $data['analisis_master']    = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['analisis_indikator'] = $this->analisis_indikator_model->get_analisis_indikator($in);

        $this->load->view('analisis_indikator/parameter/ajax_form', $data);
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

    public function filter(): void
    {
        $filter = $this->input->post('filter');
        if (! empty($filter)) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }

        redirect($this->controller);
    }

    public function tipe(): void
    {
        $filter = $this->input->post('tipe');
        if (! empty($filter)) {
            $_SESSION['tipe'] = $filter;
        } else {
            unset($_SESSION['tipe']);
        }

        redirect($this->controller);
    }

    public function kategori(): void
    {
        $filter = $this->input->post('kategori');
        if (! empty($filter)) {
            $_SESSION['kategori'] = $filter;
        } else {
            unset($_SESSION['kategori']);
        }

        redirect($this->controller);
    }

    public function insert(): void
    {
        isCan('u');
        $this->analisis_indikator_model->insert();

        redirect($this->controller);
    }

    public function update($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $this->analisis_indikator_model->update($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = 0): void
    {
        isCan('h');
        $this->analisis_indikator_model->delete($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0): void
    {
        isCan('h');
        $this->analisis_indikator_model->delete_all();

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function p_insert($in = ''): void
    {
        isCan('u');
        $this->analisis_indikator_model->p_insert($in);

        redirect("{$this->controller}/parameter/{$in}");
    }

    public function p_update($in = '', $id = 0): void
    {
        isCan('u');
        $this->analisis_indikator_model->p_update($id, $in);

        redirect("{$this->controller}/parameter/{$in}");
    }

    public function p_delete($in = '', $id = 0): void
    {
        isCan('h');
        $this->analisis_indikator_model->p_delete($id);

        redirect("{$this->controller}/parameter/{$in}");
    }

    public function p_delete_all($in = ''): void
    {
        isCan('h');
        $this->analisis_indikator_model->p_delete_all();

        redirect("{$this->controller}/parameter/{$in}");
    }
}
