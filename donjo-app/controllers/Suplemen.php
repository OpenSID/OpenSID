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

class Suplemen extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['suplemen_model', 'pamong_model', 'penduduk_model', 'keluarga_model', 'wilayah_model']);
        $this->modul_ini     = 2;
        $this->sub_modul_ini = 25;
        $this->_list_session = ['cari', 'sasaran', 'sex', 'dusun', 'rw', 'rt'];
        $this->_set_page     = ['20', '50', '100'];
    }

    public function index($page_number = 1, $order_by = 0)
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $sasaran = $this->input->post('sasaran');
        if (isset($sasaran)) {
            $this->session->sasaran = $sasaran;
        }

        $data = [
            'func'         => 'index',
            'set_page'     => $this->_set_page,
            'paging'       => $this->suplemen_model->paging_suplemen($page_number),
            'list_sasaran' => unserialize(SASARAN),
            'set_sasaran'  => $this->session->sasaran,
        ];

        $data['suplemen'] = $this->suplemen_model->list_data($order_by, $data['paging']->offset, $data['paging']->per_page);

        $this->render('suplemen/suplemen', $data);
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');
        if ($id) {
            $data['suplemen']    = $this->suplemen_model->get_suplemen($id);
            $data['form_action'] = site_url("{$this->controller}/ubah/{$id}");
        } else {
            $data['suplemen']    = null;
            $data['form_action'] = site_url("{$this->controller}/tambah");
        }

        $data['list_sasaran'] = unserialize(SASARAN);

        $this->render('suplemen/form', $data);
    }

    public function tambah()
    {
        $this->redirect_hak_akses('u');
        $this->suplemen_model->create();

        redirect($this->controller);
    }

    public function ubah($id)
    {
        $this->redirect_hak_akses('u');
        $this->suplemen_model->update($id);

        redirect($this->controller);
    }

    public function hapus($id)
    {
        $this->redirect_hak_akses('h');
        $this->suplemen_model->hapus($id);

        redirect($this->controller);
    }

    public function panduan()
    {
        $this->render('suplemen/panduan');
    }

    public function filter($filter)
    {
        if ($filter == 'dusun') {
            $this->session->unset_userdata(['rw', 'rt']);
        }
        if ($filter == 'rw') {
            $this->session->unset_userdata('rt');
        }

        //# untuk filter pada data rincian suplemen
        $value      = $this->input->post($filter);
        $id_rincian = $this->session->id_rincian;
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        redirect("{$this->controller}/rincian/{$id_rincian}");
    }

    public function clear($id = 0)
    {
        $this->session->per_page = $this->_set_page[0];
        //# untuk filter pada data rincian suplemen
        if ($id) {
            $this->session->id_rincian = $id;
            $this->session->unset_userdata(['cari', 'sex', 'dusun', 'rw', 'rt']);

            redirect("{$this->controller}/rincian/{$id}");
        }
        //Untuk index Suplemen
        else {
            $this->session->unset_userdata($this->_list_session);

            redirect($this->controller);
        }
    }

    public function rincian($id = '', $p = 1)
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data                       = $this->suplemen_model->get_rincian($p, $id);
        $data['sasaran']            = unserialize(SASARAN);
        $data['func']               = "rincian/{$id}";
        $data['per_page']           = $this->session->per_page;
        $data['set_page']           = $this->_set_page;
        $data['cari']               = $this->session->cari;
        $data['sex']                = $this->session->sex ?? null;
        $data['list_jenis_kelamin'] = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['list_dusun']         = $this->wilayah_model->list_dusun();

        foreach ($this->_list_session as $list) {
            if (in_array($list, ['dusun', 'rw', 'rt'])) {
                ${$list} = $this->session->{$list};
            }
        }
        if (isset($dusun)) {
            $data['dusun']   = $dusun;
            $data['list_rw'] = $this->wilayah_model->list_rw($dusun);

            if (isset($rw)) {
                $data['rw']      = $rw;
                $data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

                if (isset($rt)) {
                    $data['rt'] = $rt;
                } else {
                    $data['rt'] = '';
                }
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = $data['rw'] = $data['rt'] = '';
        }

        $this->render('suplemen/suplemen_anggota', $data);
    }

    public function form_terdata($id)
    {
        $this->redirect_hak_akses('u');
        $data['sasaran']      = unserialize(SASARAN);
        $data['suplemen']     = $this->suplemen_model->get_suplemen($id);
        $sasaran              = $data['suplemen']['sasaran'];
        $data['list_sasaran'] = $this->suplemen_model->list_sasaran($id, $sasaran);
        if (isset($_POST['terdata'])) {
            $data['individu'] = $this->suplemen_model->get_terdata($_POST['terdata'], $sasaran);
        } else {
            $data['individu'] = null;
        }

        $data['form_action'] = site_url("{$this->controller}/add_terdata");

        $this->render('suplemen/form_terdata', $data);
    }

    public function terdata($sasaran = 0, $id = 0)
    {
        $data = $this->suplemen_model->get_terdata_suplemen($sasaran, $id);

        $this->render('suplemen/terdata', $data);
    }

    public function data_terdata($id = 0)
    {
        $data['terdata']  = $this->suplemen_model->get_suplemen_terdata_by_id($id);
        $data['suplemen'] = $this->suplemen_model->get_suplemen($data['terdata']['id_suplemen']);
        $data['individu'] = $this->suplemen_model->get_terdata($data['terdata']['id_terdata'], $data['suplemen']['sasaran']);

        $this->render('suplemen/data_terdata', $data);
    }

    public function edit_terdata_form($id = 0)
    {
        $this->redirect_hak_akses('u');
        $data                = $this->suplemen_model->get_suplemen_terdata_by_id($id);
        $data['form_action'] = site_url("{$this->controller}/edit_terdata/{$id}");

        $this->load->view('suplemen/edit_terdata', $data);
    }

    public function add_terdata($id)
    {
        $this->redirect_hak_akses('u');
        $this->suplemen_model->add_terdata($_POST, $id);

        redirect("{$this->controller}/rincian/{$id}");
    }

    public function edit_terdata($id)
    {
        $this->redirect_hak_akses('u');
        $this->suplemen_model->edit_terdata($_POST, $id);
        $id_suplemen = $_POST['id_suplemen'];

        redirect("{$this->controller}/rincian/{$id_suplemen}");
    }

    public function hapus_terdata($id_suplemen, $id_terdata)
    {
        $this->redirect_hak_akses('h');
        $this->suplemen_model->hapus_terdata($id_terdata);

        redirect("{$this->controller}/rincian/{$id_suplemen}");
    }

    public function hapus_terdata_all($id_suplemen)
    {
        $this->redirect_hak_akses('h');
        $this->suplemen_model->hapus_terdata_all();

        redirect("{$this->controller}/rincian/{$id_suplemen}");
    }

    // $aksi = cetak/unduh
    public function dialog_daftar($id = 0, $aksi = '')
    {
        $data['aksi']           = $aksi;
        $data['pamong']         = $this->pamong_model->list_data();
        $data['pamong_ttd']     = $this->pamong_model->get_ub();
        $data['pamong_ketahui'] = $this->pamong_model->get_ttd();
        $data['form_action']    = site_url("{$this->controller}/daftar/{$id}/{$aksi}");

        $this->load->view('global/ttd_pamong', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($id = 0, $aksi = '')
    {
        if ($id > 0) {
            $post                    = $this->input->post();
            $temp                    = $this->session->per_page;
            $this->session->per_page = 1000000000; // Angka besar supaya semua data terunduh
            $data                    = $this->suplemen_model->get_rincian(1, $id);
            $data['sasaran']         = unserialize(SASARAN);
            $data['config']          = $this->header['desa'];
            $data['pamong_ttd']      = $this->pamong_model->get_data($post['pamong_ttd']);
            $data['pamong_ketahui']  = $this->pamong_model->get_data($post['pamong_ketahui']);
            $data['aksi']            = $aksi;
            $this->session->per_page = $temp;

            //pengaturan data untuk format cetak/ unduh
            $data['file']      = 'Laporan Suplemen ' . $data['suplemen']['nama'];
            $data['isi']       = 'suplemen/cetak';
            $data['letak_ttd'] = ['2', '2', '3'];

            $this->load->view('global/format_cetak', $data);
        }
    }

    public function impor()
    {
        $this->redirect_hak_akses('u');
        $id = $this->input->post('id_suplemen');
        $this->suplemen_model->impor($id);

        redirect("{$this->controller}/rincian/{$id}");
    }

    public function ekspor($id = 0)
    {
        $temp = $this->session->per_page;

        $this->session->per_page = 0;
        $this->suplemen_model->ekspor($id);
        $this->session->per_page = $temp;
    }
}
