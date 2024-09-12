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

class Analisis_laporan extends Admin_Controller
{
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'master-analisis';
    private $_set_page;
    private $_list_session;

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        if (! $this->session->has_userdata('analisis_master')) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Pilih master analisis terlebih dahulu';

            redirect('analisis_master');
        }

        $this->load->model(['pamong_model', 'wilayah_model', 'analisis_laporan_model', 'analisis_respon_model', 'analisis_master_model']);
        $this->session->submenu  = 'Laporan Analisis';
        $this->session->asubmenu = 'analisis_laporan';
        $this->_set_page         = ['50', '100', '200'];
        $this->_list_session     = ['cari', 'klasifikasi', 'dusun', 'rw', 'rt', 'jawab'];
    }

    public function clear(): void
    {
        $this->session->unset_userdata($this->_list_session);
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
        $this->session->unset_userdata(['cari2']); // cari2 gunanya apa???
        $data['p'] = $p;
        $data['o'] = $o;

        foreach ($this->_list_session as $list) {
            if (in_array($list, ['dusun', 'rw', 'rt'])) {
                ${$list} = $this->session->{$list};
            } else {
                $data[$list] = $this->session->{$list} ?: '';
            }
        }

        if (isset($dusun)) {
            $data['dusun']   = $dusun;
            $data['list_rw'] = $this->wilayah_model->list_rw($dusun);

            if (isset($rw)) {
                $data['rw']      = $rw;
                $data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

                $data['rt'] = $rt ?? '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = $data['rw'] = $data['rt'] = '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']             = 'index';
        $data['set_page']         = $this->_set_page;
        $data['judul']            = $this->analisis_laporan_model->get_judul();
        $data['list_dusun']       = $this->wilayah_model->list_dusun();
        $data['list_klasifikasi'] = $this->analisis_laporan_model->list_klasifikasi();
        $data['paging']           = $this->analisis_laporan_model->paging($p, $o);
        $data['main']             = $this->analisis_laporan_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']          = $this->analisis_laporan_model->autocomplete();
        $data['analisis_master']  = $this->analisis_master_model->analisis_master;
        $data['analisis_periode'] = $this->analisis_master_model->periode;

        $this->render('analisis_laporan/table', $data);
    }

    public function kuisioner($p = 1, $o = 0, $id = 0): void
    {
        $data['p']  = $p;
        $data['o']  = $o;
        $data['id'] = $id;

        $data['analisis_master'] = $this->analisis_master_model->analisis_master;
        $data['subjek']          = $this->analisis_laporan_model->get_subjek($id);
        $data['total']           = $this->analisis_laporan_model->get_total($id);

        $this->load->model('analisis_respon_model');
        $data['list_bukti']   = $this->analisis_respon_model->list_bukti($id);
        $data['list_anggota'] = $this->analisis_respon_model->list_anggota($id);
        $data['list_jawab']   = $this->analisis_laporan_model->list_indikator($id);
        $data['form_action']  = site_url("analisis_laporan/update_kuisioner/{$p}/{$o}/{$id}");
        if ($master['subjek_tipe'] == 5) {
            $data['asubjek'] = ucwords($this->setting->sebutan_desa);
        } else {
            $data['asubjek'] = AnalisisRefSubjekEnum::all()[$master['subjek_tipe']];
        }

        $this->render('analisis_laporan/form', $data);
    }

    // $aksi = cetak/unduh
    public function dialog_kuisioner($p = 1, $o = 0, $id = 0, $aksi = ''): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = site_url("analisis_laporan/daftar/{$p}/{$o}/{$id}/{$aksi}");

        $this->load->view('global/ttd_pamong', $data);
    }

    private function subjek_tipe()
    {
        $subjek_tipe = $this->session->subjek_tipe;

        switch ($subjek_tipe) {
            case 1: $asubjek = 'Penduduk';
                break;

            case 2: $asubjek = 'Keluarga';
                break;

            case 3: $asubjek = 'Rumahtangga';
                break;

            case 4: $asubjek = 'Kelompok';
                break;

            default: return null;
        }

        return $asubjek;
    }

    public function daftar($p = 1, $o = 0, $id = 0, $aksi = ''): void
    {
        $post      = $this->input->post();
        $data['p'] = $p;
        $data['o'] = $o;

        $data['analisis_master'] = $this->analisis_master_model->analisis_master;
        $data['subjek']          = $this->analisis_laporan_model->get_subjek($id);
        $data['asubjek']         = $this->subjek_tipe();
        $data['total']           = $this->analisis_laporan_model->get_total($id);

        $this->load->model('analisis_respon_model');
        $data['list_bukti']   = $this->analisis_respon_model->list_bukti($id);
        $data['list_anggota'] = $this->analisis_respon_model->list_anggota($id);
        $data['list_jawab']   = $this->analisis_laporan_model->list_indikator($id);

        $data['config']         = $this->header['desa'];
        $data['pamong_ttd']     = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($post['pamong_ketahui']);
        $data['aksi']           = $aksi;

        $this->load->view('analisis_laporan/form_cetak', $data);
    }

    // $aksi = cetak/unduh
    public function dialog($o = 0, $aksi = ''): void
    {
        // Simpan session lama
        $temp_cari = $this->session->cari;
        $this->session->unset_userdata('cari');
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = site_url("analisis_laporan/cetak/{$o}/{$aksi}");
        $this->session->cari = $temp_cari;

        $this->load->view('global/ttd_pamong', $data);
    }

    public function cetak($o = 0, $aksi = ''): void
    {
        $post                    = $this->input->post();
        $data['pamong_ttd']      = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['pamong_ketahui']  = $this->pamong_model->get_data($post['pamong_ketahui']);
        $data['aksi']            = $aksi;
        $data['config']          = $this->header['desa'];
        $data['judul']           = $this->analisis_laporan_model->get_judul();
        $data['file']            = 'Laporan Hasil Analisis ' . $data['judul']['asubjek'];
        $data['isi']             = 'analisis_laporan/table_print';
        $data['analisis_master'] = $this->analisis_master_model->analisis_master;
        $data['main']            = $this->analisis_laporan_model->list_data($o, 0, 10000);
        $data['letak_ttd']       = ['2', '2', '1'];

        $this->load->view('global/format_cetak', $data);
    }

    public function multi_jawab(): void
    {
        $data['form_action'] = site_url('analisis_laporan/multi_exec');
        $data['main']        = $this->analisis_laporan_model->multi_jawab(1, 1);

        $this->load->view('analisis_laporan/ajax_multi', $data);
    }

    public function multi_exec(): void
    {
        $idcb = $_POST['id_cb'];
        print_r($idcb);
        //redirect($this->controller);
    }

    public function ajax_multi_jawab(): void
    {
        $data['jawab']       = $_SESSION['jawab'] ?? '';
        $data['main']        = $this->analisis_laporan_model->multi_jawab(1, 1);
        $data['form_action'] = site_url('analisis_laporan/multi_jawab_proses');

        $this->load->view('analisis_laporan/ajax_multi', $data);
    }

    public function multi_jawab_proses(): void
    {
        if (isset($_POST['id_cb'])) {
            unset($_SESSION['jawab'], $_SESSION['jmkf']);

            $id_cb = $_POST['id_cb'];
            $cb    = '';
            if (count($id_cb) > 0) {
                foreach ($id_cb as $id) {
                    $cb .= $id . ',';
                }
            }
            $_SESSION['jawab'] = $cb . '7777777';

            $jmkf             = $this->analisis_laporan_model->group_parameter();
            $_SESSION['jmkf'] = count($jmkf);
        }

        redirect($this->controller);
    }

    public function filter($filter): void
    {
        if ($filter == 'dusun') {
            $this->session->unset_userdata(['rw', 'rt']);
        }
        if ($filter == 'rw') {
            $this->session->unset_userdata('rt');
        }

        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        redirect($this->controller);
    }
}
