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

class Analisis_respon extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->session->unset_userdata(['delik']);
        $this->load->model(['analisis_respon_model', 'wilayah_model', 'analisis_master_model']);
        $this->session->submenu  = 'Input Data';
        $this->session->asubmenu = "{$this->controller}";
        $this->modul_ini         = 5;
        $this->sub_modul_ini     = 110;
    }

    public function clear()
    {
        $this->session->unset_userdata(['cari', 'dusun', 'rw', 'rt', 'isi']);
        $this->session->per_page = 50;

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0)
    {
        if (empty($this->analisis_master_model->get_aktif_periode())) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Tidak ada periode aktif. Entri data respon harus ada periode aktif.';

            redirect('analisis_periode');
        }
        unset($_SESSION['cari2']);
        $data['p'] = $p;
        $data['o'] = $o;

        if (isset($_SESSION['cari'])) {
            $data['cari'] = $_SESSION['cari'];
        } else {
            $data['cari'] = '';
        }

        if (isset($_SESSION['isi'])) {
            $data['isi'] = $_SESSION['isi'];
        } else {
            $data['isi'] = '';
        }

        if (isset($_SESSION['dusun'])) {
            $data['dusun']   = $_SESSION['dusun'];
            $data['list_rw'] = $this->wilayah_model->list_rw($data['dusun']);

            if (isset($_SESSION['rw'])) {
                $data['rw']      = $_SESSION['rw'];
                $data['list_rt'] = $this->wilayah_model->list_rt($data['dusun'], $data['rw']);
                if (isset($_SESSION['rt'])) {
                    $data['rt'] = $_SESSION['rt'];
                } else {
                    $data['rt'] = '';
                }
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

    private function judul_subjek($subjek_tipe)
    {
        $asubjek = $this->referensi_model->list_by_id('analisis_ref_subjek')[$subjek_tipe]['subjek'];

        switch ($subjek_tipe) {
            case 1:
                $judul = [
                    'nama'    => 'Nama',
                    'nomor'   => 'NIK',
                    'asubjek' => $asubjek,
                ];
                break;

            case 2: $judul = [
                'nama'    => 'Kepala Keluarga',
                'nomor'   => 'Nomor KK',
                'asubjek' => $asubjek,
            ];
                break;

            case 3: $judul = [
                'nama'    => 'Kepala Rumah Tangga',
                'nomor'   => 'Nomor Rumah Tangga',
                'asubjek' => $asubjek,
            ];
                break;

            case 4: $judul = [
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

            default: $judul = null;
        }

        return $judul;
    }

    public function kuisioner($p = 1, $o = 0, $id = 0, $fs = 0)
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
        $data['subjek']          = $this->analisis_respon_model->get_subjek($id);
        $data['list_jawab']      = $this->analisis_respon_model->list_indikator($id);
        $data['list_bukti']      = $this->analisis_respon_model->list_bukti($id);
        $data['list_anggota']    = $this->analisis_respon_model->list_anggota($id);
        $data['form_action']     = site_url("{$this->controller}/update_kuisioner/{$p}/{$o}/{$id}");
        $data['perbaharui']      = site_url("{$this->controller}/perbaharui/{$p}/{$o}/{$id}");

        $this->render('analisis_respon/form', $data);
    }

    public function perbaharui($p = 1, $o = 0, $id_subjek = 0)
    {
        $this->redirect_hak_akses('u');
        $data = $this->analisis_respon_model->perbaharui($id_subjek);

        redirect("{$this->controller}/kuisioner/{$p}/{$o}/{$id_subjek}");
    }

    public function update_kuisioner($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->analisis_respon_model->update_kuisioner($id);

        redirect("{$this->controller}/kuisioner/{$p}/{$o}/{$id}");
    }

    //CHILD--------------------
    public function kuisioner_child($p = 1, $o = 0, $id = 0, $idc = '')
    {
        $data['p'] = $p;
        $data['o'] = $o;

        $data['list_jawab']  = $this->analisis_respon_model->list_indikator_child($idc);
        $data['form_action'] = site_url("{$this->controller}/update_kuisioner_child/{$p}/{$o}/{$id}/{$idc}");

        $this->load->view('analisis_respon/form_ajax', $data);
    }

    public function update_kuisioner_child($p = 1, $o = 0, $id = 0, $idc = '')
    {
        $this->redirect_hak_akses('u');
        $per = $this->analisis_respon_model->get_periode_child();
        $this->analisis_respon_model->update_kuisioner($idc, $per);
        redirect("{$this->controller}/kuisioner/{$p}/{$o}/{$id}");
    }

    public function aturan_unduh()
    {
        $data['main'] = $this->analisis_respon_model->aturan_unduh();
        $this->load->view('analisis_respon/import/aturan_unduh', $data);
    }

    public function data_ajax()
    {
        $this->load->view('analisis_respon/import/data_ajax');
    }

    public function data_unduh($p = 0, $o = 0)
    {
        $data['subjek_tipe'] = $this->session->subjek_tipe;
        $data['main']        = $this->analisis_respon_model->data_unduh($p, $o);
        $data['periode']     = $this->analisis_master_model->get_aktif_periode();
        $data['indikator']   = $this->analisis_respon_model->indikator_unduh($p, $o);

        $key         = ($data['periode'] + 3) * ($this->session->analisis_master + 7) * ($this->session->subjek_tipe * 3);
        $data['key'] = 'AN' . $key;

        switch ($this->session->subjek_tipe) {
            case 5:
                $data['span_kolom'] = 3;
                break;

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

    public function import($op = 0)
    {
        $this->redirect_hak_akses('u');
        $data['form_action'] = site_url("{$this->controller}/import_proses/{$op}");

        $this->load->view('analisis_respon/import/import', $data);
    }

    public function import_proses($op = 0)
    {
        $this->redirect_hak_akses('u');
        $this->analisis_respon_model->import_respon($op);

        redirect($this->controller);
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }

        redirect($this->controller);
    }

    public function isi()
    {
        $isi = $this->input->post('isi');
        if ($isi != '') {
            $_SESSION['isi'] = $isi;
        } else {
            unset($_SESSION['isi']);
        }

        redirect($this->controller);
    }

    public function dusun()
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

    public function rw()
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

    public function rt()
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $_SESSION['rt'] = $rt;
        } else {
            unset($_SESSION['rt']);
        }

        redirect($this->controller);
    }

    public function form_impor_bdt()
    {
        $this->redirect_hak_akses('u');
        $data['form_action'] = site_url("{$this->controller}/impor_bdt/");

        $this->load->view('analisis_respon/import/impor_bdt', $data);
    }

    public function impor_bdt()
    {
        $this->redirect_hak_akses('u');
        $this->load->model('bdt_model');
        $this->bdt_model->impor();

        redirect($this->controller);
    }

    public function unduh_form_bdt()
    {
        header('location:' . base_url('assets/import/contoh-data-bdt2015.xls'));
    }
}
