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

class Ekspedisi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('download');
        $this->load->model('surat_keluar_model');
        $this->load->model('ekspedisi_model');
        $this->load->model('klasifikasi_model');
        $this->load->model('pamong_model');
        $this->modul_ini     = 301;
        $this->sub_modul_ini = 302;
    }

    public function clear()
    {
        $this->session->per_page = 20;
        $this->session->cari     = null;
        $this->session->filter   = null;
        redirect('ekspedisi');
    }

    public function index($p = 1, $o = 2)
    {
        $data['p'] = $p;
        $data['o'] = $o;

        $data['cari']            = $this->session->cari ?: '';
        $data['filter']          = $this->session->filter ?: '';
        $this->session->per_page = $this->input->post('per_page') ?: null;

        $data['per_page']     = $this->session->per_page;
        $data['paging']       = $this->ekspedisi_model->paging($p, $o);
        $data['main']         = $this->ekspedisi_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['tahun_surat']  = $this->ekspedisi_model->list_tahun_surat();
        $data['keyword']      = $this->ekspedisi_model->autocomplete();
        $data['main_content'] = 'ekspedisi/table';
        $data['subtitle']     = 'Buku Ekspedisi';
        $data['selected_nav'] = 'ekspedisi';

        $this->render('bumindes/umum/main', $data);
    }

    public function form($p, $o, $id)
    {
        $this->redirect_hak_akses('u');
        $data['klasifikasi'] = $this->klasifikasi_model->list_kode();
        $data['p']           = $p;
        $data['o']           = $o;

        if ($id) {
            $data['surat_keluar'] = $this->surat_keluar_model->get_surat_keluar($id);
            $data['form_action']  = site_url("ekspedisi/update/{$p}/{$o}/{$id}");
        }

        // Buang unique id pada link nama file
        $berkas                               = explode('__sid__', $data['surat_keluar']['tanda_terima']);
        $namaFile                             = $berkas[0];
        $ekstensiFile                         = explode('.', end($berkas));
        $ekstensiFile                         = end($ekstensiFile);
        $data['surat_keluar']['tanda_terima'] = $namaFile . '.' . $ekstensiFile;
        $this->render('ekspedisi/form', $data);
    }

    public function search()
    {
        $this->session->cari = $this->input->post('cari') ?: null;
        redirect('ekspedisi');
    }

    public function filter()
    {
        $this->session->filter = $this->input->post('filter') ?: null;
        redirect('ekspedisi');
    }

    public function update($p, $o, $id)
    {
        $this->redirect_hak_akses('u');
        $this->ekspedisi_model->update($id);
        redirect("ekspedisi/index/{$p}/{$o}");
    }

    public function dialog($aksi = 'cetak', $o = 0)
    {
        $data['aksi']           = $aksi;
        $data['pamong']         = $this->pamong_model->list_data();
        $data['pamong_ttd']     = $this->pamong_model->get_ub();
        $data['pamong_ketahui'] = $this->pamong_model->get_ttd();
        $data['tahun_surat']    = $this->ekspedisi_model->list_tahun_surat();
        $data['form_action']    = site_url("ekspedisi/daftar/{$aksi}/{$o}");

        $this->load->view('ekspedisi/ajax_cetak', $data);
    }

    public function daftar($aksi = 'cetak', $o = 1)
    {
        $data['input']          = $_POST;
        $_SESSION['filter']     = $data['input']['tahun'];
        $data['pamong_ttd']     = $this->pamong_model->get_data($_POST['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
        $data['desa']           = $this->config_model->get_data();
        $data['main']           = $this->ekspedisi_model->list_data($o, 0, 10000);

        $this->load->view("ekspedisi/ekspedisi_{$aksi}", $data);
    }

    /**
     * Unduh berkas tanda terima berdasarkan kolom surat_keluar.id
     *
     * @param int $id ID surat_keluar
     *
     * @return void
     */
    public function unduh_tanda_terima($id)
    {
        // Ambil nama berkas dari database
        $berkas = $this->ekspedisi_model->get_tanda_terima($id);
        ambilBerkas($berkas, 'surat_keluar', '__sid__');
    }

    public function bukan_ekspedisi($p, $o, $id)
    {
        $this->surat_keluar_model->untuk_ekspedisi($id, $masuk = 0);
        redirect("ekspedisi/index/{$p}/{$o}");
    }
}
