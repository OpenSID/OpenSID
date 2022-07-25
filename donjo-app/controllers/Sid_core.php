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

class Sid_core extends Admin_Controller
{
    private $_set_page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['wilayah_model', 'pamong_model']);
        $this->load->library('form_validation');
        $this->modul_ini     = 200;
        $this->sub_modul_ini = 20;
        $this->_set_page     = ['20', '50', '100'];
    }

    public function clear()
    {
        $this->session->unset_userdata('cari');
        $this->session->per_page = $this->_set_page[0];
        redirect("{$this->controller}");
    }

    public function index($p = 1, $o = 0)
    {
        $data['p'] = $p;
        $data['o'] = $o;

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['cari']     = $this->session->cari ?: '';
        $data['func']     = 'index';
        $data['set_page'] = $this->_set_page;
        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->wilayah_model->paging($p, $o);
        $data['main']     = $this->wilayah_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']  = $this->wilayah_model->autocomplete();
        $data['total']    = $this->wilayah_model->total();

        $this->render('sid/wilayah/wilayah', $data);
    }

    // $aksi = cetak/unduh
    public function dialog($aksi = 'cetak')
    {
        $data['aksi']           = $aksi;
        $data['pamong']         = $this->pamong_model->list_data();
        $data['pamong_ttd']     = $this->pamong_model->get_ub();
        $data['pamong_ketahui'] = $this->pamong_model->get_ttd();
        $data['form_action']    = site_url("{$this->controller}/daftar/{$aksi}");
        $this->load->view('global/ttd_pamong', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($aksi = 'cetak')
    {
        $data['pamong_ttd']     = $this->pamong_model->get_data($this->input->post('pamong_ttd'));
        $data['pamong_ketahui'] = $this->pamong_model->get_data($this->input->post('pamong_ketahui'));
        $data['desa']           = $this->header['desa'];
        $data['main']           = $this->wilayah_model->list_semua_wilayah();
        $data['total']          = $this->wilayah_model->total();

        $this->load->view("sid/wilayah/wilayah_{$aksi}", $data);
    }

    public function form($id_dusun = '')
    {
        $this->redirect_hak_akses('u');
        $data['penduduk'] = $this->wilayah_model->list_penduduk();

        if ($id_dusun) {
            $data_dusun          = $this->wilayah_model->cluster_by_id($id_dusun);
            $data['dusun']       = $data_dusun['dusun'];
            $data['individu']    = $this->wilayah_model->get_penduduk($data_dusun['id_kepala']);
            $data['form_action'] = site_url("{$this->controller}/update/{$id_dusun}");
        } else {
            $data['dusun']       = null;
            $data['form_action'] = site_url("{$this->controller}/insert");
        }

        $this->render('sid/wilayah/wilayah_form', $data);
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }
        redirect("{$this->controller}");
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->insert();
        redirect("{$this->controller}");
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update($id);
        redirect("{$this->controller}");
    }

    //Delete dusun/rw/rt tergantung tipe
    public function delete($tipe = '', $id = '')
    {
        $kembali = $_SERVER['HTTP_REFERER'];
        $this->redirect_hak_akses('h');
        $this->wilayah_model->delete($tipe, $id);
        redirect($kembali);
    }

    public function sub_rw($id_dusun = '', $p = 1, $o = 0)
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $dusun            = $this->wilayah_model->cluster_by_id($id_dusun);
        $nama_dusun       = $dusun['dusun'];
        $data['dusun']    = $dusun['dusun'];
        $data['id_dusun'] = $id_dusun;
        $data['func']     = "sub_rw/{$id_dusun}";
        $data['set_page'] = $this->_set_page;

        $data['paging'] = $this->wilayah_model->paging_rw($p, $o, $nama_dusun);
        $data['main']   = $this->wilayah_model->list_data_rw($id_dusun, $data['paging']->offset, $data['paging']->per_page);
        $data['total']  = $this->wilayah_model->total_rw($nama_dusun);

        $this->render('sid/wilayah/wilayah_rw', $data);
    }

    public function cetak_rw($id_dusun = '')
    {
        $dusun            = $this->wilayah_model->cluster_by_id($id_dusun);
        $nama_dusun       = $dusun['dusun'];
        $data['dusun']    = $dusun['dusun'];
        $data['id_dusun'] = $id_dusun;
        $data['main']     = $this->wilayah_model->list_data_rw($id_dusun);
        $data['total']    = $this->wilayah_model->total_rw($nama_dusun);

        $this->load->view('sid/wilayah/wilayah_rw_print', $data);
    }

    public function excel_rw($id_dusun = '')
    {
        $dusun            = $this->wilayah_model->cluster_by_id($id_dusun);
        $nama_dusun       = $dusun['dusun'];
        $data['dusun']    = $dusun['dusun'];
        $data['id_dusun'] = $id_dusun;
        $data['main']     = $this->wilayah_model->list_data_rw($id_dusun);
        $data['total']    = $this->wilayah_model->total_rw($nama_dusun);

        $this->load->view('sid/wilayah/wilayah_rw_excel', $data);
    }

    public function form_rw($id_dusun = '', $id_rw = '')
    {
        $this->redirect_hak_akses('u');
        $data_dusun       = $this->wilayah_model->cluster_by_id($id_dusun);
        $data['dusun']    = $data_dusun['dusun'];
        $data['id_dusun'] = $id_dusun;
        $data['penduduk'] = $this->wilayah_model->list_penduduk();

        if ($id_rw) {
            $data_rw             = $this->wilayah_model->cluster_by_id($id_rw);
            $data['id_rw']       = $id_rw;
            $data['rw']          = $data_rw['rw'];
            $data['individu']    = $this->wilayah_model->get_penduduk($data_rw['id_kepala']);
            $data['form_action'] = site_url("{$this->controller}/update_rw/{$id_dusun}/{$id_rw}");
        } else {
            $data['id_rw']       = null;
            $data['rw']          = null;
            $data['form_action'] = site_url("{$this->controller}/insert_rw/{$id_dusun}");
        }

        $this->render('sid/wilayah/wilayah_form_rw', $data);
    }

    public function insert_rw($id_dusun = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->insert_rw($id_dusun);
        redirect("{$this->controller}/sub_rw/{$id_dusun}");
    }

    public function update_rw($id_dusun = '', $id_rw = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_rw($id_rw);
        redirect("{$this->controller}/sub_rw/{$id_dusun}");
    }

    public function sub_rt($id_dusun = '', $id_rw = '', $p = 1, $o = 0)
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data_rw          = $this->wilayah_model->cluster_by_id($id_rw);
        $data['dusun']    = $data_rw['dusun'];
        $data['id_dusun'] = $id_dusun;
        $data['rw']       = $data_rw['rw'];
        $data['id_rw']    = $id_rw;

        $data['func']     = "sub_rt/{$id_dusun}/{$id_rw}";
        $data['set_page'] = $this->_set_page;

        $data['paging'] = $this->wilayah_model->paging_rt($p, $o, $data['dusun'], $data['rw']);
        $data['main']   = $this->wilayah_model->list_data_rt($data['dusun'], $data['rw'], $data['paging']->offset, $data['paging']->per_page);
        $data['total']  = $this->wilayah_model->total_rt($data['dusun'], $data['rw']);

        $this->render('sid/wilayah/wilayah_rt', $data);
    }

    public function cetak_rt($id_dusun = '', $id_rw = '')
    {
        $temp             = $this->wilayah_model->cluster_by_id($id_dusun);
        $dusun            = $temp['dusun'];
        $data['dusun']    = $temp['dusun'];
        $data['id_dusun'] = $id_dusun;

        $temp          = $this->wilayah_model->cluster_by_id($id_rw);
        $rw            = $temp['rw'];
        $data['rw']    = $rw;
        $data['main']  = $this->wilayah_model->list_data_rt($dusun, $rw);
        $data['total'] = $this->wilayah_model->total_rt($dusun, $rw);

        $this->load->view('sid/wilayah/wilayah_rt_print', $data);
    }

    public function excel_rt($id_dusun = '', $id_rw = '')
    {
        $temp             = $this->wilayah_model->cluster_by_id($id_dusun);
        $dusun            = $temp['dusun'];
        $data['dusun']    = $temp['dusun'];
        $data['id_dusun'] = $id_dusun;

        $temp          = $this->wilayah_model->cluster_by_id($id_rw);
        $rw            = $temp['rw'];
        $data['rw']    = $rw;
        $data['main']  = $this->wilayah_model->list_data_rt($dusun, $rw);
        $data['total'] = $this->wilayah_model->total_rt($dusun, $rw);

        $this->load->view('sid/wilayah/wilayah_rt_excel', $data);
    }

    public function form_rt($id_dusun = '', $id_rw = '', $id_rt = '')
    {
        $this->redirect_hak_akses('u');
        $data_rw          = $this->wilayah_model->cluster_by_id($id_rw);
        $data['dusun']    = $data_rw['dusun'];
        $data['id_dusun'] = $id_dusun;
        $data['rw']       = $data_rw['rw'];
        $data['id_rw']    = $id_rw;
        $data['penduduk'] = $this->wilayah_model->list_penduduk();

        if ($id_rt) {
            $data_rt             = $this->wilayah_model->cluster_by_id($id_rt);
            $data['rt']          = $data_rt['rt'];
            $data['individu']    = $this->wilayah_model->get_penduduk($data_rt['id_kepala']);
            $data['form_action'] = site_url("{$this->controller}/update_rt/{$id_dusun}/{$id_rw}/{$id_rt}");
        } else {
            $data['rt']          = null;
            $data['form_action'] = site_url("{$this->controller}/insert_rt/{$id_dusun}/{$id_rw}");
        }

        $this->render('sid/wilayah/wilayah_form_rt', $data);
    }

    public function insert_rt($id_dusun = '', $id_rw = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->insert_rt($id_dusun, $id_rw);
        redirect("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}");
    }

    public function update_rt($id_dusun = '', $id_rw = '', $id_rt = 0)
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_rt($id_rt);
        redirect("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}");
    }

    public function warga($id = '')
    {
        $temp     = $this->wilayah_model->cluster_by_id($id);
        $id_dusun = $temp['id'];
        $dusun    = $temp['dusun'];

        $_SESSION['per_page'] = 100;
        $_SESSION['dusun']    = $dusun;
        redirect('penduduk/index/1/0');
    }

    public function warga_kk($id = '')
    {
        $temp                 = $this->wilayah_model->cluster_by_id($id);
        $id_dusun             = $temp['id'];
        $dusun                = $temp['dusun'];
        $_SESSION['per_page'] = 50;
        $_SESSION['dusun']    = $dusun;
        redirect('keluarga/index/1/0');
    }

    public function warga_l($id = '')
    {
        $temp     = $this->wilayah_model->cluster_by_id($id);
        $id_dusun = $temp['id'];
        $dusun    = $temp['dusun'];

        $_SESSION['per_page'] = 100;
        $_SESSION['dusun']    = $dusun;
        $_SESSION['sex']      = 1;
        redirect('penduduk/index/1/0');
    }

    public function warga_p($id = '')
    {
        $temp     = $this->wilayah_model->cluster_by_id($id);
        $id_dusun = $temp['id'];
        $dusun    = $temp['dusun'];

        $_SESSION['per_page'] = 100;
        $_SESSION['dusun']    = $dusun;
        $_SESSION['sex']      = 2;
        redirect('penduduk/index/1/0');
    }

    public function ajax_kantor_dusun_maps($id = '')
    {
        $sebutan_desa         = ucwords($this->setting->sebutan_desa);
        $data['poly']         = 'multi';
        $data['wil_ini']      = $this->wilayah_model->cluster_by_id($id);
        $data['wil_atas']     = $this->header['desa'];
        $data['dusun_gis']    = $this->wilayah_model->list_dusun();
        $data['rw_gis']       = $this->wilayah_model->list_rw();
        $data['rt_gis']       = $this->wilayah_model->list_rt();
        $data['nama_wilayah'] = ucwords($this->setting->sebutan_dusun . ' ' . $data['wil_ini']['dusun'] . ' ' . $sebutan_desa . ' ' . $data['wil_atas']['nama_desa']);
        $data['wilayah']      = ucwords($this->setting->sebutan_dusun);
        $data['breadcrumb']   = [
            ['link' => site_url('sid_core'), 'judul' => 'Daftar ' . $data['wilayah']],
        ];
        $data['form_action'] = site_url("{$this->controller}/update_kantor_dusun_map/{$id}");
        $namadesa            = $data['wil_atas']['nama_desa'];
        $data['logo']        = $this->header['desa'];

        if (! empty($data['wil_atas']['lat'] && ! empty($data['wil_atas']['lng'] && ! empty($data['wil_atas']['path'])))) {
            $this->render('sid/wilayah/maps_kantor', $data);
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = "Lokasi Kantor {$sebutan_desa} {$namadesa} Belum Dilengkapi";
            redirect("{$this->controller}");
        }
    }

    public function ajax_wilayah_dusun_maps($id = '')
    {
        $sebutan_desa         = ucwords($this->setting->sebutan_desa);
        $data['poly']         = 'multi';
        $data['wil_atas']     = $this->header['desa'];
        $data['wil_ini']      = $this->wilayah_model->cluster_by_id($id);
        $data['dusun_gis']    = $this->wilayah_model->list_dusun();
        $data['rw_gis']       = $this->wilayah_model->list_rw();
        $data['rt_gis']       = $this->wilayah_model->list_rt();
        $data['nama_wilayah'] = ucwords($this->setting->sebutan_dusun . ' ' . $data['wil_ini']['dusun'] . ' ' . $sebutan_desa . ' ' . $data['wil_atas']['nama_desa']);
        $data['wilayah']      = ucwords($this->setting->sebutan_dusun);
        $data['breadcrumb']   = [
            ['link' => site_url('sid_core'), 'judul' => 'Daftar ' . $data['wilayah']],
        ];
        $data['form_action'] = site_url("{$this->controller}/update_wilayah_dusun_map/{$id}");
        $namadesa            = $data['wil_atas']['nama_desa'];
        $data['logo']        = $this->header['desa'];
        if (! empty($data['wil_atas']['lat'] && ! empty($data['wil_atas']['lng'] && ! empty($data['wil_atas']['path'])))) {
            $this->render('sid/wilayah/maps_wilayah', $data);
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = "Peta Lokasi/Wilayah {$sebutan_desa} {$namadesa} Belum Dilengkapi";
            redirect("{$this->controller}");
        }
    }

    public function update_kantor_dusun_map($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_kantor_dusun_map($id);
        redirect("{$this->controller}");
    }

    public function update_wilayah_dusun_map($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_wilayah_dusun_map($id);
        redirect("{$this->controller}");
    }

    public function ajax_kantor_rw_maps($id_dusun = '', $id_rw = '')
    {
        $sebutan_dusun        = ucwords($this->setting->sebutan_dusun);
        $data['wil_atas']     = $this->wilayah_model->cluster_by_id($id_dusun);
        $data['wil_ini']      = $this->wilayah_model->cluster_by_id($id_rw);
        $data['dusun_gis']    = $this->wilayah_model->list_dusun();
        $data['rw_gis']       = $this->wilayah_model->list_rw();
        $data['rt_gis']       = $this->wilayah_model->list_rt();
        $data['nama_wilayah'] = 'RW ' . $data['wil_ini']['rw'] . ' ' . ucwords($sebutan_dusun . ' ' . $data['wil_ini']['dusun']);
        $data['breadcrumb']   = [
            ['link' => site_url('sid_core'), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => site_url("{$this->controller}/sub_rw/{$id_dusun}"), 'judul' => 'Daftar RW'],
        ];
        $data['wilayah']     = 'RW';
        $data['form_action'] = site_url("{$this->controller}/update_kantor_rw_map/{$id_dusun}/{$id_rw}");
        $data['logo']        = $this->header['desa'];

        if (! empty($data['wil_atas']['path'] && ! empty($data['wil_atas']['lat'] && ! empty($data['wil_atas']['lng'])))) {
            $this->render('sid/wilayah/maps_kantor', $data);
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = "Lokasi Kantor {$sebutan_dusun} {$dusun} Belum Dilengkapi";
            redirect("{$this->controller}/sub_rw/{$id_dusun}");
        }
    }

    public function ajax_wilayah_rw_maps($id_dusun = '', $id_rw = '')
    {
        $sebutan_dusun        = ucwords($this->setting->sebutan_dusun);
        $data['wil_atas']     = $this->wilayah_model->cluster_by_id($id_dusun);
        $data['wil_ini']      = $this->wilayah_model->cluster_by_id($id_rw);
        $data['dusun_gis']    = $this->wilayah_model->list_dusun();
        $data['rw_gis']       = $this->wilayah_model->list_rw();
        $data['rt_gis']       = $this->wilayah_model->list_rt();
        $data['nama_wilayah'] = 'RW ' . $data['wil_ini']['rw'] . ' ' . ucwords($sebutan_dusun . ' ' . $data['wil_ini']['dusun']);
        $data['breadcrumb']   = [
            ['link' => site_url('sid_core'), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => site_url("{$this->controller}/sub_rw/{$id_dusun}"), 'judul' => 'Daftar RW'],
        ];
        $data['wilayah']     = 'RW';
        $data['form_action'] = site_url("{$this->controller}/update_wilayah_rw_map/{$id_dusun}/{$id_rw}");
        $data['logo']        = $this->header['desa'];

        if (! empty($data['wil_atas']['path'] && ! empty($data['wil_atas']['lat'] && ! empty($data['wil_atas']['lng'])))) {
            $this->render('sid/wilayah/maps_wilayah', $data);
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = "Peta Lokasi/Wilayah {$sebutan_dusun} {$dusun} Belum Dilengkapi";
            redirect("{$this->controller}/sub_rw/{$id_dusun}");
        }
    }

    public function update_kantor_rw_map($id_dusun = '', $id_rw = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_kantor_rw_map($id_rw);
        redirect("{$this->controller}/sub_rw/{$id_dusun}");
    }

    public function update_wilayah_rw_map($id_dusun = '', $rw = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_wilayah_rw_map($id_rw);
        redirect("{$this->controller}/sub_rw/{$id_dusun}");
    }

    public function ajax_kantor_rt_maps($id_dusun = '', $id_rw = '', $id = '')
    {
        $sebutan_dusun        = ucwords($this->setting->sebutan_dusun);
        $data['wil_atas']     = $this->wilayah_model->cluster_by_id($id_dusun);
        $data['wil_ini']      = $this->wilayah_model->cluster_by_id($id);
        $data['dusun_gis']    = $this->wilayah_model->list_dusun();
        $data['rw_gis']       = $this->wilayah_model->list_rw();
        $data['rt_gis']       = $this->wilayah_model->list_rt();
        $data['nama_wilayah'] = 'RT ' . $data['wil_ini']['rt'] . ' RW ' . $data['wil_ini']['rw'] . ' ' . ucwords($sebutan_dusun . ' ' . $data['wil_ini']['dusun']);
        $data['breadcrumb']   = [
            ['link' => site_url('sid_core'), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => site_url("{$this->controller}/sub_rw/{$id_dusun}"), 'judul' => 'Daftar RW'],
            ['link' => site_url("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}"), 'judul' => 'Daftar RT'],
        ];
        $data['wilayah']     = 'RT';
        $data['form_action'] = site_url("{$this->controller}/update_wilayah_rt_map/{$id_dusun}/{$id_rw}/{$id}");
        $data['logo']        = $this->header['desa'];

        if (! empty($data['wil_atas']['path'] && ! empty($data['wil_atas']['lat'] && ! empty($data['wil_atas']['lng'])))) {
            $this->render('sid/wilayah/maps_kantor', $data);
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = "Lokasi Kantor {$sebutan_dusun} {$dusun} Belum Dilengkapi";
            redirect("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}");
        }
    }

    public function ajax_wilayah_rt_maps($id_dusun = '', $id_rw = '', $id = '')
    {
        $sebutan_dusun        = ucwords($this->setting->sebutan_dusun);
        $data['wil_atas']     = $this->wilayah_model->cluster_by_id($id_dusun);
        $data['wil_ini']      = $this->wilayah_model->cluster_by_id($id);
        $data['dusun_gis']    = $this->wilayah_model->list_dusun();
        $data['rw_gis']       = $this->wilayah_model->list_rw();
        $data['rt_gis']       = $this->wilayah_model->list_rt();
        $data['nama_wilayah'] = 'RT ' . $data['wil_ini']['rt'] . ' RW ' . $data['wil_ini']['rw'] . ' ' . ucwords($sebutan_dusun . ' ' . $data['wil_ini']['dusun']);
        $data['breadcrumb']   = [
            ['link' => site_url('sid_core'), 'judul' => 'Daftar ' . $sebutan_dusun],
            ['link' => site_url("{$this->controller}/sub_rw/{$id_dusun}"), 'judul' => 'Daftar RW'],
            ['link' => site_url("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}"), 'judul' => 'Daftar RT'],
        ];
        $data['wilayah']     = 'RT';
        $data['form_action'] = site_url("{$this->controller}/update_wilayah_rt_map/{$id_dusun}/{$id_rw}/{$id}");
        $data['logo']        = $this->header['desa'];

        if (! empty($data['wil_atas']['path'] && ! empty($data['wil_atas']['lat'] && ! empty($data['wil_atas']['lng'])))) {
            $this->render('sid/wilayah/maps_wilayah', $data);
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = "Peta Lokasi/Wilayah {$sebutan_dusun} {$dusun} Belum Dilengkapi";
            redirect("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}");
        }
    }

    public function update_kantor_rt_map($id_dusun = '', $id_rw = '', $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_kantor_rt_map($id);
        redirect("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}");
    }

    public function update_wilayah_rt_map($id_dusun = '', $id_rw = '', $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->update_wilayah_rt_map($id);
        redirect("{$this->controller}/sub_rt/{$id_dusun}/{$id_rw}");
    }

    public function kosongkan($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->wilayah_model->kosongkan_path($id);

        redirect($this->controller);
    }

    public function urut($tipe = '', $p = 1, $id = 0, $arah = 0, $id_dusun = 0, $id_rw = 0)
    {
        switch ($tipe) {
            case 'dusun': $url = "index/{$p}";
                break;

            case 'rw': $url = "sub_rw/{$id_dusun}/{$p}";
                break;

            case 'rt': $url = "sub_rt/{$id_dusun}/{$id_rw}/{$p}";
                break;

            default:
                // code...
                break;
        }

        $this->wilayah_model->urut($tipe, $id, $arah, $id_dusun, $id_rw);
        redirect("{$this->controller}/{$url}");
    }
}
