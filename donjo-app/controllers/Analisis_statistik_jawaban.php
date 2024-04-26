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

class Analisis_statistik_jawaban extends Admin_Controller
{
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'master-analisis';
    private $_set_page;

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        if (! $this->session->has_userdata('analisis_master')) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Pilih master analisis terlebih dahulu';

            redirect('analisis_master');
        }

        $this->load->model(['analisis_statistik_jawaban_model', 'analisis_respon_model', 'wilayah_model', 'analisis_master_model']);
        $this->session->submenu  = 'Statistik Jawaban';
        $this->session->asubmenu = 'analisis_statistik_jawaban';
        // TODO : Simpan di pengaturan aplikasi agar bisa disesuaikan oleh pengguna
        $this->_set_page = ['20', '50', '100'];
    }

    public function clear(): void
    {
        $this->session->unset_userdata(['cari', 'dusun', 'rw', 'rt', 'filter', 'tipe', 'kategori']);
        $this->session->per_page = $this->_set_page[0];

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0): void
    {
        if (empty($this->analisis_master_model->get_aktif_periode())) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Tidak ada periode aktif. Untuk laporan ini harus ada periode aktif.';
            redirect('analisis_periode');
        }
        unset($_SESSION['cari2']);
        $data['p'] = $p;
        $data['o'] = $o;

        $data['cari'] = $_SESSION['cari'] ?? '';

        $data['filter']   = $_SESSION['filter'] ?? '';
        $data['tipe']     = $_SESSION['tipe'] ?? '';
        $data['kategori'] = $_SESSION['kategori'] ?? '';
        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $this->session->per_page;

        if (isset($_SESSION['dusun'])) {
            $data['dusun']   = $_SESSION['dusun'];
            $data['list_rw'] = $this->wilayah_model->list_rw($data['dusun']);

            if (isset($_SESSION['rw'])) {
                $data['rw']      = $_SESSION['rw'];
                $data['list_rt'] = $this->wilayah_model->list_rt($data['dusun'], $data['rw']);

                $data['rt'] = $_SESSION['rt'] ?? '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = '';
            $data['rw']    = '';
            $data['rt']    = '';
        }

        $data['func']            = 'index';
        $data['set_page']        = $this->_set_page;
        $data['paging']          = $this->analisis_statistik_jawaban_model->paging($p, $o);
        $data['main']            = $this->analisis_statistik_jawaban_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']         = $this->analisis_statistik_jawaban_model->autocomplete();
        $data['analisis_master'] = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['list_tipe']       = $this->analisis_statistik_jawaban_model->list_tipe();
        $data['list_kategori']   = $this->analisis_statistik_jawaban_model->list_kategori();
        $data['list_dusun']      = $this->wilayah_model->list_dusun();

        $this->render('analisis_statistik_jawaban/table', $data);
    }

    public function grafik_parameter($id = ''): void
    {
        if (isset($_SESSION['dusun'])) {
            $data['dusun']   = $_SESSION['dusun'];
            $data['list_rw'] = $this->wilayah_model->list_rw($data['dusun']);

            if (isset($_SESSION['rw'])) {
                $data['rw']      = $_SESSION['rw'];
                $data['list_rt'] = $this->wilayah_model->list_rt($data['dusun'], $data['rw']);
                $data['rt']      = $_SESSION['rt'] ?? '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = '';
            $data['rw']    = '';
            $data['rt']    = '';
        }
        $data['list_dusun'] = $this->wilayah_model->list_dusun();
        $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
        $data['analisis_statistik_jawaban'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
        $data['analisis_master']            = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['main']                       = $this->analisis_statistik_jawaban_model->list_indikator($id);

        $this->render('analisis_statistik_jawaban/parameter/grafik_table', $data);
    }

    public function subjek_parameter($id = '', $par = ''): void
    {
        if (isset($_SESSION['dusun'])) {
            $data['dusun']   = $_SESSION['dusun'];
            $data['list_rw'] = $this->wilayah_model->list_rw($data['dusun']);

            if (isset($_SESSION['rw'])) {
                $data['rw']      = $_SESSION['rw'];
                $data['list_rt'] = $this->wilayah_model->list_rt($data['dusun'], $data['rw']);
                $data['rt']      = $_SESSION['rt'] ?? '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = '';
            $data['rw']    = '';
            $data['rt']    = '';
        }
        $data['list_dusun'] = $this->wilayah_model->list_dusun();
        $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);

        $data['analisis_statistik_pertanyaan'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
        $data['analisis_statistik_jawaban']    = $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
        $data['analisis_master']               = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['main']                          = $this->analisis_statistik_jawaban_model->list_subjek($par);

        $this->render('analisis_statistik_jawaban/parameter/subjek_table', $data);
    }

    public function cetak($o = 0): void
    {
        $data['main'] = $this->analisis_statistik_jawaban_model->list_data($o, 0, 10000);
        $this->load->view('analisis_statistik_jawaban/table_print', $data);
    }

    public function excel($o = 0): void
    {
        $data['main'] = $this->analisis_statistik_jawaban_model->list_data($o, 0, 10000);
        $this->load->view('analisis_statistik_jawaban/table_excel', $data);
    }

    public function cetak2($id = '', $par = ''): void
    {
        $data['analisis_statistik_pertanyaan'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
        $data['analisis_statistik_jawaban']    = $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
        $data['main']                          = $this->analisis_statistik_jawaban_model->list_subjek($par);
        $this->load->view('analisis_statistik_jawaban/parameter/table_print', $data);
    }

    public function excel2($id = '', $par = ''): void
    {
        $data['analisis_statistik_pertanyaan'] = $this->analisis_statistik_jawaban_model->get_analisis_indikator($id);
        $data['analisis_statistik_jawaban']    = $this->analisis_statistik_jawaban_model->get_analisis_parameter($par);
        $data['main']                          = $this->analisis_statistik_jawaban_model->list_subjek($par);
        $this->load->view('analisis_statistik_jawaban/parameter/subjek_excel', $data);
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
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }
        redirect($this->controller);
    }

    public function tipe(): void
    {
        $filter = $this->input->post('tipe');
        if ($filter != 0) {
            $_SESSION['tipe'] = $filter;
        } else {
            unset($_SESSION['tipe']);
        }
        redirect($this->controller);
    }

    public function kategori(): void
    {
        $filter = $this->input->post('kategori');
        if ($filter != 0) {
            $_SESSION['kategori'] = $filter;
        } else {
            unset($_SESSION['kategori']);
        }
        redirect($this->controller);
    }

    public function dusun(): void
    {
        unset($_SESSION['rw'], $_SESSION['rt']);

        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $_SESSION['dusun'] = $dusun;
        } else {
            unset($_SESSION['dusun']);
        }
        redirect($this->controller);
    }

    public function rw(): void
    {
        unset($_SESSION['rt']);
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $_SESSION['rw'] = $rw;
        } else {
            unset($_SESSION['rw']);
        }
        redirect($this->controller);
    }

    public function rt(): void
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $_SESSION['rt'] = $rt;
        } else {
            unset($_SESSION['rt']);
        }
        redirect($this->controller);
    }

    public function dusun2($id = '', $par = ''): void
    {
        unset($_SESSION['rw'], $_SESSION['rt']);

        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $_SESSION['dusun'] = $dusun;
        } else {
            unset($_SESSION['dusun']);
        }
        redirect("analisis_statistik_jawaban/subjek_parameter/{$id}/{$par}");
    }

    public function rw2($id = '', $par = ''): void
    {
        unset($_SESSION['rt']);
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $_SESSION['rw'] = $rw;
        } else {
            unset($_SESSION['rw']);
        }
        redirect("analisis_statistik_jawaban/subjek_parameter/{$id}/{$par}");
    }

    public function rt2($id = '', $par = ''): void
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $_SESSION['rt'] = $rt;
        } else {
            unset($_SESSION['rt']);
        }
        redirect("analisis_statistik_jawaban/subjek_parameter/{$id}/{$par}");
    }

    public function dusun3($id = ''): void
    {
        unset($_SESSION['rw'], $_SESSION['rt']);

        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $_SESSION['dusun'] = $dusun;
        } else {
            unset($_SESSION['dusun']);
        }
        redirect("analisis_statistik_jawaban/grafik_parameter/{$id}");
    }

    public function rw3($id = ''): void
    {
        unset($_SESSION['rt']);
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $_SESSION['rw'] = $rw;
        } else {
            unset($_SESSION['rw']);
        }
        redirect("analisis_statistik_jawaban/grafik_parameter/{$id}");
    }

    public function rt3($id = ''): void
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $_SESSION['rt'] = $rt;
        } else {
            unset($_SESSION['rt']);
        }
        redirect("analisis_statistik_jawaban/grafik_parameter/{$id}");
    }

    public function delete($p = 1, $o = 0, $id = ''): void
    {
        isCan('h');
        $this->analisis_statistik_jawaban_model->delete($id);
        redirect("analisis_statistik_jawaban/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0): void
    {
        isCan('h');
        $this->analisis_statistik_jawaban_model->delete_all();
        redirect("analisis_statistik_jawaban/index/{$p}/{$o}");
    }
}
