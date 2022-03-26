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

class Lapak_admin extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 324;
        $this->load->model(['lapak_model', 'penduduk_model']);
    }

    public function index()
    {
        redirect("{$this->controller}/produk");
    }

    // NAVIGASI
    public function navigasi()
    {
        return [
            'jml_produk' => [
                'aktif' => $this->lapak_model->get_produk('', 1)->count_all_results(),
                'total' => $this->lapak_model->get_produk()->count_all_results(),
            ],

            'jml_pelapak' => [
                'aktif' => $this->lapak_model->get_pelapak('', 1)->count_all_results(),
                'total' => $this->lapak_model->get_pelapak()->count_all_results(),
            ],

            'jml_kategori' => [
                'aktif' => $this->lapak_model->get_kategori('', 1)->count_all_results(),
                'total' => $this->lapak_model->get_kategori()->count_all_results(),
            ],
        ];
    }

    // PRODUK
    public function produk()
    {
        $data['navigasi'] = $this->navigasi();

        if ($data['navigasi']['jml_pelapak']['aktif'] <= 0) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Pelapak tidak tersedia, silakan tambah pelapak terlebih dahulu';
            redirect("{$this->controller}/pelapak");
        }

        if ($data['navigasi']['jml_kategori']['aktif'] <= 0) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Kategori tidak tersedia, silakan tambah kategori terlebih dahulu';
            redirect("{$this->controller}/kategori");
        }

        if ($this->input->is_ajax_request()) {
            $start              = $this->input->post('start');
            $length             = $this->input->post('length');
            $search             = $this->input->post('search[value]');
            $order              = $this->lapak_model::ORDER_ABLE_PRODUK[$this->input->post('order[0][column]')];
            $dir                = $this->input->post('order[0][dir]');
            $status             = $this->input->post('status');
            $id_pend            = $this->input->post('id_pend');
            $id_produk_kategori = $this->input->post('id_produk_kategori');

            return json([
                'draw'            => $this->input->post('draw'),
                'recordsTotal'    => $this->lapak_model->get_produk('', $status)->count_all_results(),
                'recordsFiltered' => $this->lapak_model->get_produk($search, $status, $id_pend, $id_produk_kategori)->count_all_results(),
                'data'            => $this->lapak_model->get_produk($search, $status, $id_pend, $id_produk_kategori)->order_by($order, $dir)->limit($length, $start)->get()->result(),
            ]);
        }

        $data['pelapak']  = $this->lapak_model->get_pelapak('', 1)->get()->result();
        $data['kategori'] = $this->lapak_model->get_kategori('', 1)->get()->result();

        $this->render("{$this->controller}/produk/index", $data);
    }

    public function produk_form($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $data['main']        = $this->lapak_model->produk_detail($id) ?? show_404();
            $data['aksi']        = 'Ubah';
            $data['form_action'] = site_url("{$this->controller}/produk_update/{$id}");
        } else {
            $data['main']->tipe_potongan = 1;
            $data['aksi']                = 'Tambah';
            $data['form_action']         = site_url("{$this->controller}/produk_insert");
        }

        $data['pelapak']  = $this->lapak_model->get_pelapak('', 1)->get()->result();
        $data['kategori'] = $this->lapak_model->get_kategori('', 1)->get()->result();
        $data['satuan']   = $this->lapak_model->get_satuan();

        $this->render("{$this->controller}/produk/form", $data);
    }

    public function produk_insert()
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->produk_insert();
        redirect("{$this->controller}/produk");
    }

    public function produk_update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->produk_update($id);
        redirect("{$this->controller}/produk");
    }

    public function produk_delete($id)
    {
        $this->redirect_hak_akses('h');
        $this->lapak_model->produk_delete($id);
        redirect("{$this->controller}/produk");
    }

    public function produk_delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->lapak_model->produk_delete_all();
        redirect("{$this->controller}/produk");
    }

    public function produk_detail($id = 0)
    {
        $data['main'] = $this->lapak_model->produk_detail($id);

        $this->load->view("{$this->controller}/produk/detail", $data);
    }

    public function produk_status($id = 0, $status = 0)
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->status('produk', $id, $status);
        redirect("{$this->controller}/produk");
    }

    // PELAPAK
    public function pelapak()
    {
        $data['navigasi'] = $this->navigasi();

        if ($this->input->is_ajax_request()) {
            $start  = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search[value]');
            $order  = $this->lapak_model::ORDER_ABLE_PELAPAK[$this->input->post('order[0][column]')];
            $dir    = $this->input->post('order[0][dir]');
            $status = $this->input->post('status');

            return json([
                'draw'            => $this->input->post('draw'),
                'recordsTotal'    => $this->lapak_model->get_pelapak('', $status)->count_all_results(),
                'recordsFiltered' => $this->lapak_model->get_pelapak($search, $status)->count_all_results(),
                'data'            => $this->lapak_model->get_pelapak($search, $status)->order_by($order, $dir)->limit($length, $start)->get()->result(),
            ]);
        }

        $this->render("{$this->controller}/pelapak/index", $data);
    }

    public function pelapak_form($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $data['main']        = $this->lapak_model->pelapak_detail($id) ?? show_404();
            $data['form_action'] = site_url("{$this->controller}/pelapak_update/{$id}");
        } else {
            $data['main']        = null;
            $data['form_action'] = site_url("{$this->controller}/pelapak_insert");
        }

        $data['list_penduduk'] = $this->lapak_model->list_penduduk($data['main']->id_pend ?? 0);

        $this->load->view("{$this->controller}/pelapak/modal_form", $data);
    }

    public function pelapak_maps($id = '')
    {
        $desa    = $this->header['desa'];
        $pelapak = $this->lapak_model->pelapak_detail($id) ?? show_404();
        if ($pelapak) {
            $penduduk = $this->penduduk_model->get_penduduk_map($pelapak->id_pend);
        }

        switch (true) {
            case $pelapak->lat || $pelapak->lng:
                $lat  = $pelapak->lat;
                $lng  = $pelapak->lng;
                $zoom = $pelapak->zoom ?? 10;
                break;

            case $penduduk['lat'] || $penduduk['lng']:
                $lat  = $penduduk['lat'];
                $lng  = $penduduk['lng'];
                $zoom = $penduduk['zoom'] ?? 10;
                break;

            case $desa['lat'] || $desa['lng']:
                $lat  = $desa['lat'];
                $lng  = $desa['lng'];
                $zoom = $desa['zoom'] ?? 10;
                break;

            default:
                $lat  = -1.0546279422758742;
                $lng  = 116.71875000000001;
                $zoom = 10;
                break;
        }

        $data['pelapak'] = $pelapak;
        $data['lokasi']  = [
            'ini'  => $ini,
            'lat'  => $lat,
            'lng'  => $lng,
            'zoom' => $zoom,
        ];
        $data['desa']        = $desa;
        $data['wil_atas']    = $desa;
        $data['dusun_gis']   = $this->wilayah_model->list_dusun();
        $data['rw_gis']      = $this->wilayah_model->list_rw();
        $data['rt_gis']      = $this->wilayah_model->list_rt();
        $data['form_action'] = site_url("{$this->controller}/pelapak_update_maps/{$id}");

        $this->render("{$this->controller}/pelapak/maps", $data);
    }

    public function pelapak_insert()
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->pelapak_insert();
        redirect("{$this->controller}/pelapak");
    }

    public function pelapak_update_maps($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->pelapak_update_maps($id);
        redirect("{$this->controller}/pelapak");
    }

    public function pelapak_update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->pelapak_update($id);
        redirect("{$this->controller}/pelapak");
    }

    public function pelapak_delete($id)
    {
        $this->redirect_hak_akses('h');
        // Cek apakah produk pelapak ada ???
        if ($this->lapak_model->get_produk()->where('id_pelapak', $id)->count_all_results() > 0) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Pelapak tersebut memiliki produk, silahkan hapus terlebih dahulu';
        } else {
            $this->lapak_model->pelapak_delete($id);
        }

        redirect("{$this->controller}/pelapak");
    }

    public function pelapak_delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->lapak_model->pelapak_delete_all();
        redirect("{$this->controller}/pelapak");
    }

    public function pelapak_status($id = 0, $status = 0)
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->status('pelapak', $id, $status);
        redirect("{$this->controller}/pelapak");
    }

    // KATEGORI
    public function kategori()
    {
        $data['navigasi'] = $this->navigasi();

        if ($this->input->is_ajax_request()) {
            $start  = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search[value]');
            $order  = $this->lapak_model::ORDER_ABLE_KATEGORI[$this->input->post('order[0][column]')];
            $dir    = $this->input->post('order[0][dir]');
            $status = $this->input->post('status');

            return json([
                'draw'            => $this->input->post('draw'),
                'recordsTotal'    => $this->lapak_model->get_kategori('', $status)->count_all_results(),
                'recordsFiltered' => $this->lapak_model->get_kategori($search, $status)->count_all_results(),
                'data'            => $this->lapak_model->get_kategori($search, $status)->order_by($order, $dir)->limit($length, $start)->get()->result(),
            ]);
        }

        $this->render("{$this->controller}/kategori/index", $data);
    }

    public function kategori_form($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $data['main']        = $this->lapak_model->kategori_detail($id) ?? show_404();
            $data['form_action'] = site_url("{$this->controller}/kategori_update/{$id}");
        } else {
            $data['main']        = null;
            $data['form_action'] = site_url("{$this->controller}/kategori_insert");
        }

        $this->load->view("{$this->controller}/kategori/modal_form", $data);
    }

    public function kategori_insert()
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->kategori_insert();
        redirect("{$this->controller}/kategori");
    }

    public function kategori_update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->kategori_update($id);
        redirect("{$this->controller}/kategori");
    }

    public function kategori_delete($id)
    {
        $this->redirect_hak_akses('h');
        // Cek apakah produk kategori ada ???
        if ($this->lapak_model->get_produk()->where('id_produk_kategori', $id)->count_all_results() > 0) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Kategori tersebut memiliki produk, silakan hapus terlebih dahulu';
        } else {
            $this->lapak_model->kategori_delete($id);
        }

        redirect("{$this->controller}/kategori");
    }

    public function kategori_delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->lapak_model->kategori_delete_all();
        redirect("{$this->controller}/kategori");
    }

    public function kategori_status($id = 0, $status = 0)
    {
        $this->redirect_hak_akses('u');
        $this->lapak_model->status('produk_kategori', $id, $status);
        redirect("{$this->controller}/kategori");
    }

    // PENGATURAN
    public function pengaturan()
    {
        $data = ['kategori' => ['lapak']];

        $this->load->view('global/modal_setting', $data);
    }
}
