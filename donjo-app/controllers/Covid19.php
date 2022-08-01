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

class Covid19 extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('covid19_model');
        $this->load->model('wilayah_model');
        $this->load->model('penduduk_model');
        $this->modul_ini = 206;
    }

    public function index()
    {
        $this->data_pemudik(1);
    }

    public function data_pemudik($page = 1)
    {
        $this->sub_modul_ini = 207;

        if (isset($_POST['per_page'])) {
            $this->session->set_userdata('per_page', $_POST['per_page']);
        } else {
            $this->session->set_userdata('per_page', 10);
        }

        $data             = $this->covid19_model->get_list_pemudik($page);
        $data['per_page'] = $this->session->userdata('per_page');

        $this->render('covid19/data_pemudik', $data);
    }

    public function form_pemudik()
    {
        $this->redirect_hak_akses('u');
        $this->sub_modul_ini = 207;

        $d                      = new DateTime('NOW');
        $data['tanggal_datang'] = $d->format('Y-m-d H:i:s');

        $data['list_penduduk'] = $this->covid19_model->get_penduduk_not_in_pemudik();

        if (isset($_POST['terdata'])) {
            $data['individu'] = $this->covid19_model->get_penduduk_by_id($_POST['terdata']);
        } else {
            $data['individu'] = null;
        }

        $data['select_tujuan_mudik'] = $this->covid19_model->list_tujuan_mudik();

        $data['dusun']               = $this->wilayah_model->list_dusun();
        $data['rw']                  = $this->wilayah_model->list_rw($data['penduduk']['dusun']);
        $data['rt']                  = $this->wilayah_model->list_rt($data['penduduk']['dusun'], $data['penduduk']['rw']);
        $data['agama']               = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['golongan_darah']      = $this->referensi_model->list_data('tweb_golongan_darah');
        $data['jenis_kelamin']       = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['status_penduduk']     = $this->referensi_model->list_data('tweb_penduduk_status');
        $data['select_status_covid'] = $this->referensi_model->list_data('ref_status_covid');

        $nav['act'] = 206;

        $data['form_action']          = site_url('covid19/add_pemudik');
        $data['form_action_penduduk'] = site_url('covid19/insert_penduduk');

        $this->render('covid19/form_pemudik', $data);
    }

    public function insert_penduduk()
    {
        $this->redirect_hak_akses('u');
        $callback_url = $_POST['callback_url'];
        unset($_POST['callback_url']);

        $this->session->jenis_peristiwa = 5; // pindah masuk
        $id                             = $this->penduduk_model->insert();
        if ($_SESSION['success'] == -1) {
            $_SESSION['dari_internal'] = true;
        }

        redirect("{$this->controller}/form_pemudik");
    }

    public function add_pemudik()
    {
        $this->redirect_hak_akses('u');
        $this->covid19_model->add_pemudik($_POST);

        redirect($this->controller);
    }

    public function hapus_pemudik($id_pemudik)
    {
        $this->redirect_hak_akses('h');
        $this->covid19_model->delete_pemudik_by_id($id_pemudik);

        redirect($this->controller);
    }

    public function edit_pemudik_form($id = 0)
    {
        $this->redirect_hak_akses('u');
        $data                        = $this->covid19_model->get_pemudik_by_id($id);
        $data['select_tujuan_mudik'] = $this->covid19_model->list_tujuan_mudik();
        $data['select_status_covid'] = $this->referensi_model->list_data('ref_status_covid');
        $data['form_action']         = site_url("{$this->controller}/edit_pemudik/{$id}");

        $this->load->view('covid19/edit_pemudik', $data);
    }

    public function edit_pemudik($id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->covid19_model->update_pemudik_by_id($this->input->post(), $id);

        redirect($this->controller);
    }

    public function detil_pemudik($id = 0)
    {
        $nav['act'] = 206;

        $data['terdata']  = $this->covid19_model->get_pemudik_by_id($id);
        $data['individu'] = $this->covid19_model->get_penduduk_by_id($data['terdata']['id_terdata']);

        $data['terdata']['judul_terdata_nama'] = 'NIK';
        $data['terdata']['judul_terdata_info'] = 'Nama Terdata';
        $data['terdata']['terdata_nama']       = $data['individu']['nik'];
        $data['terdata']['terdata_info']       = $data['individu']['nama'];

        $data['penduduk'] = $this->penduduk_model->get_penduduk($data['terdata']['id_terdata']);
        $this->session->set_userdata('nik_lama', $data['penduduk']['nik']);

        $data['dusun']                = $this->wilayah_model->list_dusun();
        $data['rw']                   = $this->wilayah_model->list_rw($data['penduduk']['dusun']);
        $data['rt']                   = $this->wilayah_model->list_rt($data['penduduk']['dusun'], $data['penduduk']['rw']);
        $data['agama']                = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['golongan_darah']       = $this->referensi_model->list_data('tweb_golongan_darah');
        $data['jenis_kelamin']        = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['status_penduduk']      = $this->referensi_model->list_data('tweb_penduduk_status');
        $data['form_action_penduduk'] = site_url('covid19/update_penduduk/' . $data['terdata']['id_terdata'] . '/' . $id);

        $this->render('covid19/detil_pemudik', $data);
    }

    public function update_penduduk($id_pend, $id_pemudik)
    {
        $this->redirect_hak_akses('u');
        $this->penduduk_model->update($id_pend);
        if ($_SESSION['success'] == -1) {
            $_SESSION['dari_internal'] = true;
        }

        redirect("{$this->controller}/detil_pemudik/{$id_pemudik}");
    }

    public function pantau($page = 1, $filter_tgl = null, $filter_nik = null)
    {
        $this->sub_modul_ini = 208;

        if (isset($_POST['per_page'])) {
            $this->session->set_userdata('per_page', $_POST['per_page']);
        } else {
            $this->session->set_userdata('per_page', 10);
        }
        $data['per_page'] = $this->session->userdata('per_page');
        $data['page']     = $page;

        // get list pemudik
        $data['pemudik_array'] = $this->covid19_model->get_list_pemudik_wajib_pantau(true);
        // get list pemudik end

        // get list pemantauan
        $pantau_pemudik      = $this->covid19_model->get_list_pantau_pemudik($page, $filter_tgl, $filter_nik);
        $data['unique_nik']  = $this->covid19_model->get_unique_nik_pantau_pemudik();
        $data['unique_date'] = $this->covid19_model->get_unique_date_pantau_pemudik();
        $data['filter_tgl']  = $filter_tgl ?? '0';
        $data['filter_nik']  = $filter_nik ?? '0';

        $data['paging']               = $pantau_pemudik['paging'];
        $data['pantau_pemudik_array'] = $pantau_pemudik['query_array'];
        // get list pemantauan end

        // datetime now
        $d                    = new DateTime('NOW');
        $data['datetime_now'] = $d->format('Y-m-d H:i:s');

        $data['this_url']    = site_url('covid19/pantau');
        $data['form_action'] = site_url('covid19/add_pantau');

        $url_delete_front         = 'covid19/hapus_pantau';
        $url_delete_rare          = "{$page}";
        $data['url_delete_front'] = $url_delete_front;
        $data['url_delete_rare']  = $url_delete_rare;

        $this->render('covid19/pantau_pemudik', $data);
    }

    public function add_pantau()
    {
        $this->redirect_hak_akses('u', '', 'covid19/pantau');
        $this->covid19_model->add_pantau_pemudik($_POST);
        $url = 'covid19/pantau/' . $_POST['page'] . '/' . $_POST['data_h_plus'];

        redirect($url);
    }

    public function hapus_pantau($id_pantau_pemudik, $page = null, $h_plus = null)
    {
        $this->redirect_hak_akses('h', '', 'covid19/pantau');
        $this->covid19_model->delete_pantau_pemudik_by_id($id_pantau_pemudik);

        $url = 'covid19/pantau';
        $url .= (isset($page) ? "/{$page}" : '');
        $url .= (isset($h_plus) ? "/{$h_plus}" : '');

        redirect($url);
    }

    // $aksi = cetak/unduh
    public function daftar($aksi = '', $filter_tgl = null, $filter_nik = null)
    {
        $this->session->set_userdata('per_page', 0); // Unduh semua data

        if (isset($filter_tgl) || isset($filter_nik)) {
            $data  = $this->covid19_model->get_list_pantau_pemudik(1, $filter_tgl, $filter_nik);
            $judul = 'pantauan';
        } else {
            $data  = $this->covid19_model->get_list_pemudik(1);
            $judul = 'pendataan';
        }

        if ($aksi === 'cetak') {
            $aksi = $aksi . '_' . $judul;
        }

        $data['config'] = $this->config_model->get_data();
        $data['aksi']   = $aksi;
        $data['judul']  = $judul;
        $this->session->set_userdata('per_page', 10); // Kembalikan ke paginasi default

        $this->load->view('covid19/' . $data['aksi'], $data);
    }
}
