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

class Dokumen_sekretariat extends Admin_Controller
{
    private $list_session = ['filter', 'cari', 'jenis_peraturan', 'tahun'];
    private $_set_page;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('web_dokumen_model');
        $this->_set_page     = ['50', '100', '200'];
        $this->modul_ini     = 301;
        $this->sub_modul_ini = 302;
    }

    public function index($kat = 2, $p = 1, $o = 0)
    {
        if ($this->input->post('per_page') !== null) {
            $this->session->per_page = $this->input->post('per_page');
        }
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}/{$p}/{$o}");
    }

    // Produk Hukum Desa
    public function peraturan_desa($kat = 2, $p = 1, $o = 0)
    {
        $data['p']   = $p;
        $data['o']   = $o;
        $data['kat'] = $kat;

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $data['func']              = "index/{$kat}";
        $data['set_page']          = $this->_set_page;
        $data['per_page']          = $this->session->per_page;
        $data['kat_nama']          = $this->web_dokumen_model->kat_nama($kat);
        $data['paging']            = $this->web_dokumen_model->paging($kat, $p, $o);
        $data['main']              = $this->web_dokumen_model->list_data($kat, $o, $data['paging']->offset, $data['paging']->per_page);
        $data['list_tahun']        = $this->web_dokumen_model->list_tahun($kat);
        $data['keyword']           = $this->web_dokumen_model->autocomplete();
        $data['submenu']           = $this->referensi_model->list_data('ref_dokumen');
        $data['jenis_peraturan']   = $this->referensi_model->list_ref(JENIS_PERATURAN_DESA);
        $data['sub_kategori']      = $_SESSION['sub_kategori'];
        $_SESSION['menu_kategori'] = true;

        foreach ($data['submenu'] as $s) {
            if ($kat == $s['id']) {
                $_SESSION['submenu']       = $s['id'];
                $_SESSION['sub_kategori']  = $s['kategori'];
                $_SESSION['kode_kategori'] = $s['id'];
            }
        }

        $data['main_content'] = 'dokumen/table_buku_umum';
        $data['subtitle']     = ($kat == '3') ? 'Buku Peraturan ' . ucwords($this->setting->sebutan_desa) : 'Buku Keputusan ' . ucwords($this->setting->sebutan_kepala_desa);
        $data['selected_nav'] = ($kat == '3') ? 'peraturan' : 'keputusan';

        $this->render('bumindes/umum/main', $data);
    }

    public function clear($kat = 2)
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = $this->_set_page[0];
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}");
    }

    public function form($kat = 2, $p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $data['p']               = $p;
        $data['o']               = $o;
        $data['kat']             = $kat;
        $data['list_kategori']   = $this->web_dokumen_model->list_kategori();
        $data['jenis_peraturan'] = $this->referensi_model->list_ref(JENIS_PERATURAN_DESA);

        if ($id) {
            $data['dokumen']     = $this->web_dokumen_model->get_dokumen($id);
            $data['form_action'] = site_url("dokumen_sekretariat/update/{$kat}/{$id}/{$p}/{$o}");
        } else {
            $data['dokumen']     = null;
            $data['form_action'] = site_url('dokumen_sekretariat/insert');
        }
        $data['kat_nama'] = $this->web_dokumen_model->kat_nama($kat);

        $this->_set_tab($kat);
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
        redirect("dokumen_sekretariat/index/{$kat}");
    }

    public function filter($filter = 'filter')
    {
        $this->session->{$filter} = $this->input->post($filter);
        $kat                      = $this->input->post('kategori');
        redirect("dokumen_sekretariat/index/{$kat}");
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
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}");
    }

    public function update($kat, $id = '', $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u');
        $_SESSION['success'] = 1;
        $kategori            = $this->input->post('kategori');
        if (! empty($kategori)) {
            $kat = $this->input->post('kategori');
        }
        $outp = $this->web_dokumen_model->update($id);
        if (! $outp) {
            $_SESSION['success'] = -1;
        }
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}/{$p}/{$o}");
    }

    public function delete($kat = 1, $p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h');
        $this->web_dokumen_model->delete($id);
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}/{$p}/{$o}");
    }

    public function delete_all($kat = 1, $p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h');
        $this->web_dokumen_model->delete_all();
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}/{$p}/{$o}");
    }

    public function dokumen_lock($kat = 1, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_dokumen_model->dokumen_lock($id, 1);
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}/");
    }

    public function dokumen_unlock($kat = 1, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_dokumen_model->dokumen_lock($id, 2);
        redirect("dokumen_sekretariat/peraturan_desa/{$kat}/");
    }

    public function dialog_cetak($kat = 1)
    {
        redirect("dokumen/dialog_cetak/{$kat}");
    }

    public function dialog_excel($kat = 1)
    {
        redirect("dokumen/dialog_excel/{$kat}");
    }

    private function _set_tab($kat)
    {
        switch ($kat) {
            case '2':
                $this->tab_ini = 59;
                break;

            case '3':
                $this->tab_ini = 60;
                break;

            default:
                $this->tab_ini = 59;
                break;
        }
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int $id_dokumen Id berkas pada koloam dokumen.id
     * @param int $kat
     * @param int $tipe
     *
     * @return void
     */
    public function berkas($id_dokumen = 0, $kat = 1, $tipe = 0)
    {
        // Ambil nama berkas dari database
        $data = $this->web_dokumen_model->get_dokumen($id_dokumen);
        ambilBerkas($data['satuan'], $this->controller . '/peraturan_desa/' . $kat, null, LOKASI_DOKUMEN, ($tipe == 1) ? true : false);
    }
}
