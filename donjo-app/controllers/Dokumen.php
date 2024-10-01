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

use App\Models\DokumenHidup;

defined('BASEPATH') || exit('No direct script access allowed');

class Dokumen extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'informasi-publik';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('web_dokumen_model');
        $this->load->model('pamong_model');
        $this->load->helper('download');
    }

    public function clear(): void
    {
        unset($_SESSION['cari'], $_SESSION['filter']);

        redirect('dokumen');
    }

    public function index($kat = 1, $p = 1, $o = 0): void
    {
        $data['p']   = $p ?? 1;
        $data['o']   = $o ?? 0;
        $data['kat'] = $kat;

        $data['cari'] = $_SESSION['cari'] ?? '';

        $data['filter'] = $_SESSION['filter'] ?? '';

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

    public function form($kat = 1, $p = 1, $o = 0, $id = ''): void
    {
        isCan('u');
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
        $data['jenis_peraturan']      = $this->referensi_model->jenis_peraturan_desa();

        $this->render('dokumen/form', $data);
    }

    public function search(): void
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

    public function filter(): void
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

    public function insert(): void
    {
        isCan('u');
        $_SESSION['success'] = 1;
        $kat                 = $this->input->post('kategori');
        $outp                = $this->web_dokumen_model->insert();
        if (! $outp) {
            $_SESSION['success'] = -1;
        }
        redirect("dokumen/index/{$kat}");
    }

    public function update($kat, $id = '', $p = 1, $o = 0): void
    {
        isCan('u');
        $_SESSION['success'] = 1;
        $data                = $this->web_dokumen_model->validasi($this->request);
        $dokumen             = DokumenHidup::find($id) ?? show_404();
        if ($this->request['satuan']) {
            $data['satuan'] = $this->upload_dokumen();
        }
        if ($dokumen->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function delete($kat = 1, $p = 1, $o = 0, $id = ''): void
    {
        isCan('h');
        $dokumen = DokumenHidup::find($id) ?? show_404();
        if ($dokumen->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function delete_all($kat = 1, $p = 1, $o = 0): void
    {
        isCan('h');
        if (DokumenHidup::whereIn('id', $_POST['id_cb'])->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function dokumen_lock($kat = 1, $id = ''): void
    {
        isCan('u');
        $this->web_dokumen_model->dokumen_lock($id, 1);
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function dokumen_unlock($kat = 1, $id = ''): void
    {
        isCan('u');
        $this->web_dokumen_model->dokumen_lock($id, 2);
        redirect("dokumen/index/{$kat}/{$p}/{$o}");
    }

    public function dialog_cetak($kat = 1): void
    {
        $data['form_action']     = site_url("dokumen/cetak/{$kat}");
        $data['kat']             = $kat;
        $data['jenis_peraturan'] = $this->referensi_model->jenis_peraturan_desa();
        $data['tahun_laporan']   = $this->web_dokumen_model->list_tahun($kat);
        $this->load->view('dokumen/dialog_cetak', $data);
    }

    public function cetak($kat = 1): void
    {
        $data     = $this->data_cetak($kat);
        $template = $data['template'];
        $this->load->view("dokumen/{$template}", $data);
    }

    private function data_cetak($kat)
    {
        // Agar tidak terlalu banyak mengubah kode, karena menggunakan view global
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);

        $post          = $this->input->post();
        $data['main']  = $this->web_dokumen_model->data_cetak($kat, $post['tahun'], $post['jenis_peraturan']);
        $data['input'] = $post;
        $data['kat']   = $kat;
        $data['tahun'] = $post['tahun'];
        $data['desa']  = $this->header['desa'];
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

    public function dialog_excel($kat = 1): void
    {
        $data['form_action']     = site_url("dokumen/excel/{$kat}");
        $data['kat']             = $kat;
        $data['jenis_peraturan'] = $this->referensi_model->jenis_peraturan_desa();
        $data['tahun_laporan']   = $this->web_dokumen_model->list_tahun($kat);
        $this->load->view('dokumen/dialog_cetak', $data);
    }

    public function excel($kat = 1): void
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
     */
    public function unduh_berkas($id_dokumen, $id_pend = null, $tampil = false): void
    {
        // Ambil nama berkas dari database
        $data = $this->web_dokumen_model->get_dokumen($id_dokumen, $id_pend);
        ambilBerkas($data['satuan'], $this->controller, null, LOKASI_DOKUMEN, $tampil);
    }

    public function tampilkan_berkas($id_dokumen, $id_pend = null): void
    {
        $this->unduh_berkas($id_dokumen, $id_pend, $tampil = true);
    }

    private function upload_dokumen()
    {
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['file_name']     = namafile($this->input->post('nama', true));

        $this->load->library('MY_Upload', null, 'upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('satuan')) {
            session_error($this->upload->display_errors(null, null));

            return false;
        }

        return $this->upload->data()['file_name'];
    }
}
