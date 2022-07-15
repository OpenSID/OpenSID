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

class Dokumen extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('web_dokumen_model');
        $this->load->model('pamong_model');
        $this->load->helper('download');
        $this->modul_ini     = 15;
        $this->sub_modul_ini = 52;
    }

    public function clear()
    {
        unset($_SESSION['cari'], $_SESSION['filter']);

        redirect('dokumen');
    }

    public function index($kat = 1, $p = 1, $o = 0)
    {
        $data['p']   = $p;
        $data['o']   = $o;
        $data['kat'] = $kat;

        if (isset($_SESSION['cari'])) {
            $data['cari'] = $_SESSION['cari'];
        } else {
            $data['cari'] = '';
        }

        if (isset($_SESSION['filter'])) {
            $data['filter'] = $_SESSION['filter'];
        } else {
            $data['filter'] = '';
        }

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];

        $data['kat_nama'] = $this->web_dokumen_model->kat_nama($kat);
        $data['paging']   = $this->web_dokumen_model->paging($kat, $p, $o);
        $data['main']     = $this->web_dokumen_model->list_data($kat, $o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']  = $this->web_dokumen_model->autocomplete();

        $this->render('dokumen/table_dokumen', $data);
    }

    public function form($kat = 1, $p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $data['p']   = $p;
        $data['o']   = $o;
        $data['kat'] = $kat;

        if ($id) {
            $data['dokumen']     = $this->web_dokumen_model->get_dokumen($id);
            $data['form_action'] = site_url("dokumen/update/{$kat}/{$id}/{$p}/{$o}");
        } else {
            $data['dokumen']     = null;
            $data['form_action'] = site_url('dokumen/insert');
        }
        $data['kat_nama']             = $this->web_dokumen_model->kat_nama($kat);
        $data['list_kategori_publik'] = $this->referensi_model->list_ref_flip(KATEGORI_PUBLIK);

        $this->render('dokumen/form', $data);
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        $kat  = $this->input->post('kategori');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        redirect("dokumen/index/{$kat}");
    }

    public function filter()
    {
        $filter = $this->input->post('filter');
        $kat    = $this->input->post('kategori');
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }
        redirect("dokumen/index/{$kat}");
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $_SESSION['success'] = 1;
        $kat                 = $this->input->post('kategori');
        $outp                = $this->web_dokumen_model->insert();
        if (! $outp) {
            $_SESSION['success'] = -1;
        }
        redirect("dokumen/index/{$kat}");
    }

    public function update($kat, $id = '', $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u');
        $_SESSION['success'] = 1;
        $outp                = $this->web_dokumen_model->update($id);
        if (! $outp) {
            $_SESSION['success'] = -1;
        }
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function delete($kat = 1, $p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h', "dokumen/index/{$kat}/{$p}/{$o}");
        $this->web_dokumen_model->delete($id);
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function delete_all($kat = 1, $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h', "dokumen/index/{$kat}/{$p}/{$o}");
        $this->web_dokumen_model->delete_all();
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function dokumen_lock($kat = 1, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_dokumen_model->dokumen_lock($id, 1);
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function dokumen_unlock($kat = 1, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_dokumen_model->dokumen_lock($id, 2);
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function dialog_cetak($kat = 1)
    {
        $data['form_action']     = site_url("dokumen/cetak/{$kat}");
        $data['kat']             = $kat;
        $data['jenis_peraturan'] = $this->referensi_model->list_ref(JENIS_PERATURAN_DESA);
        $data['pamong']          = $this->pamong_model->list_data();
        $data['pamong_ttd']      = $this->pamong_model->get_ub();
        $data['pamong_ketahui']  = $this->pamong_model->get_ttd();
        $data['tahun_laporan']   = $this->web_dokumen_model->list_tahun($kat);
        $this->load->view('dokumen/dialog_cetak', $data);
    }

    public function cetak($kat = 1)
    {
        $data     = $this->data_cetak($kat);
        $template = $data['template'];
        $this->load->view("dokumen/{$template}", $data);
    }

    private function data_cetak($kat)
    {
        $post                   = $this->input->post();
        $data['main']           = $this->web_dokumen_model->data_cetak($kat, $post['tahun'], $post['jenis_peraturan']);
        $data['input']          = $post;
        $data['pamong']         = $this->pamong_model->list_data();
        $data['pamong_ttd']     = $this->pamong_model->get_data($_POST['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
        $data['kat']            = $kat;
        $data['tahun']          = $post['tahun'];
        $data['desa']           = $this->config_model->get_data();
        if ($kat == 1) {
            $data['kategori'] = 'Informasi Publik';
        } else {
            $list_kategori    = $this->web_dokumen_model->list_kategori();
            $data['kategori'] = $list_kategori[$kat];
        }
        if ($kat == 2) {
            $data['template'] = 'sk_kades_print';
        } elseif ($kat == 3) {
            $data['template'] = 'perdes_print';
        } else {
            $data['template'] = 'dokumen_print';
        }

        return $data;
    }

    public function dialog_excel($kat = 1)
    {
        $data['form_action']     = site_url("dokumen/excel/{$kat}");
        $data['kat']             = $kat;
        $data['jenis_peraturan'] = $this->referensi_model->list_ref(JENIS_PERATURAN_DESA);
        $data['pamong']          = $this->pamong_model->list_data();
        $data['pamong_ttd']      = $this->pamong_model->get_ub();
        $data['pamong_ketahui']  = $this->pamong_model->get_ttd();
        $data['tahun_laporan']   = $this->web_dokumen_model->list_tahun($kat);
        $this->load->view('dokumen/dialog_cetak', $data);
    }

    public function excel($kat = 1)
    {
        $data = $this->data_cetak($kat);
        $this->load->view('dokumen/dokumen_excel', $data);
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int        $id_dokumen Id berkas pada koloam dokumen.id
     * @param mixed|null $id_pend
     * @param mixed      $tampil
     *
     * @return void
     */
    public function unduh_berkas($id_dokumen, $id_pend = null, $tampil = false)
    {
        // Ambil nama berkas dari database
        $data = $this->web_dokumen_model->get_dokumen($id_dokumen, $id_pend);
        ambilBerkas($data['satuan'], $this->controller, null, LOKASI_DOKUMEN, $tampil);
    }

    public function tampilkan_berkas($id_dokumen, $id_pend = null)
    {
        $this->unduh_berkas($id_dokumen, $id_pend, $tampil = true);
    }
}
