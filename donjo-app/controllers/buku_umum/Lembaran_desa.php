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

defined('BASEPATH') || exit('No direct script access allowed');

class Lembaran_desa extends Admin_Controller
{
    public $modul_ini            = 'buku-administrasi-desa';
    public $sub_modul_ini        = 'administrasi-umum';
    private array $_set_page     = ['20', '50', '100'];
    private array $_list_session = ['filter', 'cari', 'jenis_peraturan'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        $this->load->model(['web_dokumen_model', 'pamong_model']);
    }

    // Buku Lembaran Desa dan Berita Desa
    public function index($p = 1, $o = 0): void
    {
        $data['p'] = $p ?: 1;
        $data['o'] = $o ?: 0;
        $kat       = 3;

        $data['cari'] = $this->session->cari ?: '';

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];

        $data['paging']            = $this->web_dokumen_model->paging($kat, $p, $o);
        $data['main']              = $this->web_dokumen_model->list_data($kat, $o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']           = $this->web_dokumen_model->autocomplete();
        $data['submenu']           = $this->referensi_model->list_data('ref_dokumen');
        $data['jenis_peraturan']   = $this->referensi_model->jenis_peraturan_desa();
        $data['sub_kategori']      = $_SESSION['sub_kategori'];
        $_SESSION['menu_kategori'] = true;

        foreach ($data['submenu'] as $s) {
            if ($kat == $s['id']) {
                $_SESSION['submenu']       = $s['id'];
                $_SESSION['sub_kategori']  = $s['kategori'];
                $_SESSION['kode_kategori'] = $s['id'];
            }
        }

        $sebutan_desa         = ucwords(setting('sebutan_desa'));
        $data['main_content'] = 'dokumen/table_lembaran_desa';
        $data['subtitle']     = "Buku Lembaran {$sebutan_desa} Dan Berita {$sebutan_desa}";
        $data['selected_nav'] = 'lembaran';

        $this->render('bumindes/umum/main', $data);
    }

    public function clear(): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = $this->_set_page[0];
        redirect('lembaran_desa');
    }

    public function form($p = 1, $o = 0, $id = ''): void
    {
        isCan('u');

        if ($id) {
            $data['dokumen']     = $this->web_dokumen_model->get_dokumen($id) ?? show_404();
            $data['form_action'] = site_url("lembaran_desa/update/{$id}/{$p}/{$o}");
        }

        $data['kat']             = 3;
        $data['kat_nama']        = 'Lembaran Desa';
        $data['kembali_ke']      = site_url("lembaran_desa/index/{$p}/{$o}");
        $data['list_kategori']   = $this->web_dokumen_model->list_kategori();
        $data['jenis_peraturan'] = $this->referensi_model->jenis_peraturan_desa();

        $this->render('dokumen/form', $data);
    }

    public function search(): void
    {
        $cari                = $this->input->post('cari');
        $this->session->cari = $cari ?: null;
        redirect('lembaran_desa/index');
    }

    public function filter($filter = 'filter'): void
    {
        $this->session->{$filter} = $this->input->post($filter);
        redirect('lembaran_desa/index');
    }

    public function update($id = '', $p = 1, $o = 0): void
    {
        isCan('u');
        $this->session->success = 1;
        $outp                   = $this->web_dokumen_model->update($id);
        status_sukses($outp);
        redirect("lembaran_desa/index/{$p}/{$o}");
    }

    public function lock($id, $val = 1): void
    {
        isCan('u');
        $this->web_dokumen_model->dokumen_lock($id, $val);
        redirect('lembaran_desa');
    }

    public function dialog_daftar($aksi = 'cetak', $o = 0): void
    {
        $data['aksi']            = $aksi;
        $data['form_action']     = site_url("lembaran_desa/daftar/{$aksi}/{$o}");
        $data['jenis_peraturan'] = $this->referensi_model->jenis_peraturan_desa();
        $data['tahun_laporan']   = $this->web_dokumen_model->list_tahun($kat = 3);

        $this->load->view('dokumen/dialog_cetak', $data);
    }

    public function daftar($aksi = 'cetak', $o = 1): void
    {
        $data     = $this->data_cetak($aksi);
        $template = $data['template'];
        $this->load->view("dokumen/{$template}", $data);
    }

    private function data_cetak($aksi)
    {
        // TODO :: gunakan view global penandatangan
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);
        $post                   = $this->input->post();
        $data['main']           = $this->web_dokumen_model->data_cetak($kat = 3, $post['tahun'], $post['jenis_peraturan']);
        $data['input']          = $post;
        $data['tahun']          = $post['tahun'];
        $data['desa']           = $this->header['desa'];
        $data['aksi']           = $aksi;
        $data['template']       = 'lembaran_desa_print';

        return $data;
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int $id_dokumen Id berkas pada koloam dokumen.id
     */
    public function unduh_berkas($id_dokumen = 0): void
    {
        // Ambil nama berkas dari database
        $data = $this->web_dokumen_model->get_dokumen($id_dokumen);
        ambilBerkas($data['satuan'], $this->controller, null, LOKASI_DOKUMEN);
    }
}
