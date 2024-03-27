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

use App\Models\DisposisiSuratmasuk;
use App\Models\RefJabatan;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_masuk extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-umum';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        // Untuk bisa menggunakan helper force_download()
        $this->load->helper('download');
        $this->load->model('surat_masuk_model');
        $this->load->model('klasifikasi_model');
        $this->load->model('pamong_model');

        $this->load->model('penomoran_surat_model');
        $this->tab_ini = 2;
    }

    public function clear($id = 0): void
    {
        $_SESSION['per_page'] = 20;
        $_SESSION['surat']    = $id;
        unset($_SESSION['cari'], $_SESSION['filter']);

        redirect('surat_masuk');
    }

    public function index($p = 1, $o = 2): void
    {
        $data['p'] = $p ?? 1;
        $data['o'] = $o ?? 0;

        $data['cari'] = $_SESSION['cari'] ?? '';

        $data['filter'] = $_SESSION['filter'] ?? '';

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }

        $this->surat_masuk_model->remove_character();
        $data['per_page']         = $_SESSION['per_page'];
        $data['paging']           = $this->surat_masuk_model->paging($p, $o);
        $data['main']             = $this->surat_masuk_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['tahun_penerimaan'] = $this->surat_masuk_model->list_tahun_penerimaan();
        $data['keyword']          = $this->surat_masuk_model->autocomplete();
        $data['main_content']     = 'surat_masuk/table';
        $data['subtitle']         = 'Buku Agenda - Surat Masuk';
        $data['selected_nav']     = 'agenda_masuk';

        $this->render('bumindes/umum/main', $data);
    }

    public function form($p = 1, $o = 0, $id = ''): void
    {
        isCan('u');
        $data['pengirim']    = $this->surat_masuk_model->autocomplete();
        $data['klasifikasi'] = $this->klasifikasi_model->list_kode();
        $data['p']           = $p;
        $data['o']           = $o;

        if ($id) {
            $data['surat_masuk']           = $this->surat_masuk_model->get_surat_masuk($id) ?? show_404();
            $data['form_action']           = site_url("surat_masuk/update/{$p}/{$o}/{$id}");
            $data['disposisi_surat_masuk'] = DisposisiSuratmasuk::where('id_surat_masuk', $id)->pluck('disposisi_ke')->toArray();
        } else {
            $last_surat                        = $this->penomoran_surat_model->get_surat_terakhir('surat_masuk');
            $data['surat_masuk']['nomor_urut'] = $last_surat['no_surat'] + 1;
            $data['form_action']               = site_url('surat_masuk/insert');
            $data['disposisi_surat_masuk']     = null;
        }

        $data['ref_disposisi'] = $this->ref_disposisi();

        // Buang unique id pada link nama file
        $berkas                             = explode('__sid__', $data['surat_masuk']['berkas_scan']);
        $namaFile                           = $berkas[0];
        $ekstensiFile                       = explode('.', end($berkas));
        $ekstensiFile                       = end($ekstensiFile);
        $data['surat_masuk']['berkas_scan'] = $namaFile . '.' . $ekstensiFile;

        $this->render('surat_masuk/form', $data);
    }

    private function ref_disposisi()
    {
        $non_aktif = RefJabatan::nonAktif()->pluck('id', 'id');

        return RefJabatan::with('pamongs')->urut()->latest()->pluck('nama', 'id')->except(kades()->id)->except($non_aktif)->toArray();
    }

    public function form_upload($p = 1, $o = 0, $url = ''): void
    {
        isCan('u');
        $data['form_action'] = site_url("surat_masuk/upload/{$p}/{$o}/{$url}");
        $this->load->view('surat_masuk/ajax-upload', $data);
    }

    public function search(): void
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }
        redirect('surat_masuk');
    }

    public function filter(): void
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }
        redirect('surat_masuk');
    }

    public function insert(): void
    {
        isCan('u');
        $this->surat_masuk_model->insert();
        redirect('surat_masuk');
    }

    public function update($p = 1, $o = 0, $id = ''): void
    {
        isCan('u');
        $this->surat_masuk_model->update($id);
        redirect("surat_masuk/index/{$p}/{$o}");
    }

    public function upload($p = 1, $o = 0, $url = ''): void
    {
        isCan('u');
        $this->surat_masuk_model->upload($url);
        redirect("surat_masuk/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = ''): void
    {
        isCan('h');
        $this->surat_masuk_model->delete($id);
        redirect("surat_masuk/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0): void
    {
        isCan('h');
        $this->surat_masuk_model->delete_all();
        redirect("surat_masuk/index/{$p}/{$o}");
    }

    public function dialog_disposisi($o, $id): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = 'Cetak';
        $data['form_action'] = site_url("surat_masuk/disposisi/{$id}");
        $this->load->view('global/ttd_pamong', $data);
    }

    // TODO: Satukan dialog cetak dan unduh
    public function dialog_cetak($o = 0): void
    {
        $data['aksi']        = 'Cetak';
        $data['tahun_surat'] = $this->surat_masuk_model->list_tahun_surat();
        $data['form_action'] = site_url("surat_masuk/dialog/cetak/{$o}");
        $this->load->view('surat_masuk/ajax_cetak', $data);
    }

    // TODO: Satukan dialog cetak dan unduh
    public function dialog_unduh($o = 0): void
    {
        $data['aksi']        = 'Unduh';
        $data['tahun_surat'] = $this->surat_masuk_model->list_tahun_surat();
        $data['form_action'] = site_url("surat_masuk/dialog/unduh/{$o}");
        $this->load->view('surat_masuk/ajax_cetak', $data);
    }

    public function dialog($aksi = 'unduh', $o = 0): void
    {
        // TODO :: gunakan view global penandatangan
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);
        $data['input']          = $_POST;
        $_SESSION['filter']     = $data['input']['tahun'];
        $data['desa']           = $this->header['desa'];
        $data['main']           = $this->surat_masuk_model->list_data($o, 0, 10000);

        if ($aksi == 'unduh') {
            $this->load->view('surat_masuk/surat_masuk_excel', $data);
        } else {
            $this->load->view('surat_masuk/surat_masuk_print', $data);
        }
    }

    public function disposisi($id): void
    {
        $disposisi = [];
        collect($this->ref_disposisi())->each(static function ($item, $key) use (&$disposisi): void {
            $disposisi[] = ['id' => $key, 'nama' => $item];
        })->toArray();
        $data['input']          = $_POST;
        $data['desa']           = $this->header['desa'];
        $data['pamong_ttd']     = $this->pamong_model->get_data($_POST['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);

        $data['ref_disposisi']         = $disposisi;
        $data['disposisi_surat_masuk'] = DisposisiSuratmasuk::where('id_surat_masuk', $id)->pluck('disposisi_ke')->toArray();
        $data['surat']                 = $this->surat_masuk_model->get_surat_masuk($id);
        $this->load->view('surat_masuk/disposisi', $data);
    }

    /**
     * Unduh berkas scan berdasarkan kolom surat_masuk.id
     *
     * @param int $idSuratMasuk Id berkas scan pada koloam surat_masuk.id
     * @param int $tipe
     */
    public function berkas($idSuratMasuk = 0, $tipe = 0): void
    {
        // Ambil nama berkas dari database
        $berkas = $this->surat_masuk_model->getNamaBerkasScan($idSuratMasuk);
        ambilBerkas($berkas, 'surat_masuk', '__sid__', LOKASI_ARSIP, $tipe == 1);
    }

    public function nomor_surat_duplikat(): void
    {
        if ($_POST['nomor_urut'] == $_POST['nomor_urut_lama']) {
            $hasil = false;
        } else {
            $hasil = $this->penomoran_surat_model->nomor_surat_duplikat('surat_masuk', $_POST['nomor_urut']);
        }
        echo $hasil ? 'false' : 'true';
    }
}
