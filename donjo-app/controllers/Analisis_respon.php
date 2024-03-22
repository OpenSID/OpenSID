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

use App\Enums\AnalisisRefSubjekEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_respon extends Admin_Controller
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

        $this->session->unset_userdata(['delik']);
        $this->load->model(['analisis_respon_model', 'wilayah_model', 'analisis_master_model']);
        $this->session->submenu  = 'Input Data';
        $this->session->asubmenu = "{$this->controller}";
    }

    public function clear(): void
    {
        $this->session->unset_userdata(['cari', 'dusun', 'rw', 'rt', 'isi']);
        $this->session->per_page = 50;

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0): void
    {
        if (empty($this->analisis_master_model->get_aktif_periode())) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Tidak ada periode aktif. Entri data respon harus ada periode aktif.';

            redirect('analisis_periode');
        }
        unset($_SESSION['cari2']);
        $data['p'] = $p;
        $data['o'] = $o;

        $data['cari'] = $_SESSION['cari'] ?? '';

        $data['isi'] = $_SESSION['isi'] ?? '';

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

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];

        $data['list_dusun']       = $this->wilayah_model->list_dusun();
        $data['paging']           = $this->analisis_respon_model->paging($p, $o);
        $data['main']             = $this->analisis_respon_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']          = $this->analisis_respon_model->autocomplete();
        $data['analisis_master']  = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['analisis_periode'] = $this->analisis_master_model->periode;
        $data                     = array_merge($data, $this->judul_subjek($data['analisis_master']['subjek_tipe']));

        $this->render('analisis_respon/table', $data);
    }

    private function judul_subjek($subjek_tipe): ?array
    {
        $asubjek = AnalisisRefSubjekEnum::all()[$subjek_tipe];

        switch ($subjek_tipe) {
            case 1:
                $judul = [
                    'nama'    => 'Nama',
                    'nomor'   => 'NIK',
                    'asubjek' => $asubjek,
                ];
                break;

            case 2:
                $judul = [
                    'nama'    => 'Kepala Keluarga',
                    'nomor'   => 'Nomor KK',
                    'asubjek' => $asubjek,
                ];
                break;

            case 3:
                $judul = [
                    'nama'    => 'Kepala Rumah Tangga',
                    'nomor'   => 'Nomor Rumah Tangga',
                    'asubjek' => $asubjek,
                ];
                break;

            case 4:
                $judul = [
                    'nama'    => 'Nama Kelompok',
                    'nomor'   => 'ID Kelompok',
                    'asubjek' => $asubjek,
                ];
                break;

            case 5:
                $desa  = ucwords($this->setting->sebutan_desa);
                $judul = [
                    'nama'    => "Nama {$desa}",
                    'nomor'   => "Kode {$desa}",
                    'asubjek' => ucwords($this->setting->sebutan_desa),
                ];
                break;

            case 6:
                $dusun = ucwords($this->setting->sebutan_dusun);
                $judul = [
                    'nama'    => "Nama {$dusun}",
                    'nomor'   => $dusun,
                    'asubjek' => $asubjek,
                ];
                break;

            case 7:
                $judul = [
                    'nama'    => "Nama {$this->setting->sebutan_dusun}/RW",
                    'nomor'   => 'RW',
                    'asubjek' => $asubjek,
                ];
                break;

            case 8:
                $judul = [
                    'nama'    => "Nama {$this->setting->sebutan_dusun}/RW/RT",
                    'nomor'   => 'RT',
                    'asubjek' => $asubjek,
                ];
                break;

            default:
                $judul = null;
        }

        return $judul;
    }

    public function kuisioner($p = 1, $o = 0, $id = 0, $fs = 0): void
    {
        if ($fs == 1) {
            $this->session->fullscreen = 1;
        }
        if ($fs == 2) {
            $this->session->unset_userdata('fullscreen');
        }
        if ($fs != 0) {
            redirect("analisis_respon/kuisioner/{$p}/{$o}/{$id}");
        }

        $data['p']  = $p;
        $data['o']  = $o;
        $data['id'] = $id;

        $data['analisis_master'] = $this->analisis_master_model->get_analisis_master($this->session->analisis_master);
        $data['subjek']          = $this->analisis_respon_model->get_subjek($id) ?? show_404();
        $data['list_jawab']      = $this->analisis_respon_model->list_indikator($id);
        $data['list_bukti']      = $this->analisis_respon_model->list_bukti($id);
        $data['list_anggota']    = $this->analisis_respon_model->list_anggota($id);
        $data['form_action']     = site_url("{$this->controller}/update_kuisioner/{$p}/{$o}/{$id}");
        $data['perbaharui']      = site_url("{$this->controller}/perbaharui/{$p}/{$o}/{$id}");

        $this->render('analisis_respon/form', $data);
    }

    public function perbaharui($p = 1, $o = 0, $id_subjek = 0): void
    {
        isCan('u');
        $this->analisis_respon_model->perbaharui($id_subjek);

        redirect("{$this->controller}/kuisioner/{$p}/{$o}/{$id_subjek}");
    }

    public function update_kuisioner($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $this->analisis_respon_model->update_kuisioner($id);

        redirect("{$this->controller}/kuisioner/{$p}/{$o}/{$id}");
    }

    //CHILD--------------------
    public function kuisioner_child($p = 1, $o = 0, $id = 0, $idc = ''): void
    {
        $data['p'] = $p;
        $data['o'] = $o;

        $data['list_jawab']  = $this->analisis_respon_model->list_indikator_child($idc);
        $data['form_action'] = site_url("{$this->controller}/update_kuisioner_child/{$p}/{$o}/{$id}/{$idc}");

        $this->load->view('analisis_respon/form_ajax', $data);
    }

    public function update_kuisioner_child($p = 1, $o = 0, $id = 0, $idc = ''): void
    {
        isCan('u');
        $per = $this->analisis_respon_model->get_periode_child();
        $this->analisis_respon_model->update_kuisioner($idc, $per);
        redirect("{$this->controller}/kuisioner/{$p}/{$o}/{$id}");
    }

    public function aturan_unduh(): void
    {
        $data['main'] = $this->analisis_respon_model->aturan_unduh();
        $this->load->view('analisis_respon/import/aturan_unduh', $data);
    }

    public function data_ajax(): void
    {
        $this->load->view('analisis_respon/import/data_ajax');
    }

    /**
     * Unduh data analisis respon
     *
     * @param int $tipe | 1. Dengan isian data, 2. Dengan kode isian
     */
    public function data_unduh($tipe = 1): void
    {
        $data['subjek_tipe'] = $this->session->subjek_tipe;
        $data['main']        = $this->analisis_respon_model->data_unduh(1, 0);
        $data['periode']     = $this->analisis_master_model->get_aktif_periode();
        $data['indikator']   = $this->analisis_respon_model->indikator_unduh(1);
        $data['tipe']        = $tipe;
        $key                 = ($data['periode'] + 3) * ($this->session->analisis_master + 7) * ($this->session->subjek_tipe * 3);
        $data['key']         = 'AN' . $key;

        switch ($this->session->subjek_tipe) {
            case 5:

            case 6:
                $data['span_kolom'] = 3;
                break;

            case 7:
                $data['span_kolom'] = 5;
                break;

            case 8:
                $data['span_kolom'] = 6;
                break;

            default:
                $data['span_kolom'] = 7;
                break;
        }
        $data['judul'] = $this->judul_subjek($this->session->subjek_tipe);

        $this->load->view('analisis_respon/import/data_unduh', $data);
    }

    public function import($op = 0): void
    {
        isCan('u');
        $data['form_action'] = site_url("{$this->controller}/import_proses/{$op}");

        $this->load->view('analisis_respon/import/import', $data);
    }

    public function import_proses($op = 0): void
    {
        isCan('u');
        $this->analisis_respon_model->import_respon($op);

        redirect($this->controller);
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

    public function isi(): void
    {
        $isi = $this->input->post('isi');
        if ($isi != '') {
            $_SESSION['isi'] = $isi;
        } else {
            unset($_SESSION['isi']);
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

    public function form_impor_bdt(): void
    {
        isCan('u');
        $data['form_action'] = site_url("{$this->controller}/impor_bdt/");

        $this->load->view('analisis_respon/import/impor_bdt', $data);
    }

    public function impor_bdt(): void
    {
        isCan('u');
        $this->load->model('bdt_model');
        $this->bdt_model->impor();

        redirect($this->controller);
    }

    public function unduh_form_bdt(): void
    {
        header('location:' . base_url('assets/import/contoh-data-bdt2015.xls'));
    }
}
