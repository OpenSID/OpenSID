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

class Bumindes_arsip extends Admin_controller
{
    private $list_session;
    private $_set_page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('arsip_fisik_model');
        $this->list_session  = ['data_filter_tahun', 'data_filter_jenis', 'data_filter_cari', 'data_filter_kategori'];
        $this->_set_page     = ['50', '100', '200'];
        $this->modul_ini     = 301;
        $this->sub_modul_ini = 336;
    }

    public function index($p = 1, $o = 4)
    {
        $total_dokumen_desa  = $this->arsip_fisik_model->ambil_total_data('dokumen_desa');
        $total_surat_masuk   = $this->arsip_fisik_model->ambil_total_data('surat_masuk');
        $total_surat_keluar  = $this->arsip_fisik_model->ambil_total_data('surat_keluar');
        $total_kependudukan  = $this->arsip_fisik_model->ambil_total_data('kependudukan');
        $total_layanan_surat = $this->arsip_fisik_model->ambil_total_data('layanan_surat');

        $data = [
            'dokumen_desa' => [
                'title' => 'Dokumen Desa',
                'total' => $total_dokumen_desa,
                'uri'   => 'dokumen_desa',
            ],
            'surat_masuk' => [
                'title' => 'Surat Masuk',
                'total' => $total_surat_masuk,
                'uri'   => 'surat_masuk',
            ],
            'surat_keluar' => [
                'title' => 'Surat Keluar',
                'total' => $total_surat_keluar,
                'uri'   => 'surat_keluar',
            ],
            'kependudukan' => [
                'title' => 'Kependudukan',
                'total' => $total_kependudukan,
                'uri'   => 'kependudukan',
            ],
            'layanan_surat' => [
                'title' => 'Layanan Surat',
                'total' => $total_layanan_surat,
                'uri'   => 'layanan_surat',
            ],
        ];

        if ($filter_jenis = $this->input->post('jenis')) {
            $this->session->unset_userdata($this->list_session[3]);
            $this->session->{$this->list_session[1]} = $filter_jenis;
        }

        if ($filter_tahun = $this->input->post('tahun')) {
            $this->session->{$this->list_session[0]} = $filter_tahun;
        }

        if ($filter_cari = $this->input->post('cari')) {
            $this->session->unset_userdata($this->list_session[0]);
            $this->session->unset_userdata($this->list_session[1]);
            $this->session->{$this->list_session[2]} = $filter_cari;
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $this->session->per_page = $this->session->per_page ?? $this->_set_page[0];

        $data['func']     = 'index';
        $data['set_page'] = $this->_set_page;
        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->arsip_fisik_model->paging($p);
        $data['main']     = $this->arsip_fisik_model->ambil_dokumen_per_page(true, $data['per_page'], $p, $o);
        $data['page']     = $p;
        $data['o']        = $o;

        $filter = $this->arsip_fisik_model->ambil_semua_filter();

        $data['list_tahun']   = $filter['tahun'];
        $data['list_jenis']   = $filter['jenis'];
        $data['main_content'] = 'bumindes/arsip/content_arsip';

        $this->render('bumindes/arsip/index', $data);
    }

    public function tindakan_lihat($kategori, $id, $tindakan)
    {
        $tabel  = $this->get_table($kategori);
        $berkas = $this->arsip_fisik_model->get_nama_berkas($tabel, $id);

        switch ($tindakan) {
            case 'lihat':
                $this->tampilkan_berkas($tabel, $berkas);
                break;

            case 'unduh':
                $this->unduh_berkas($tabel, $berkas);
                break;
        }
    }

    public function tindakan_ubah($kategori, $id, $p, $o)
    {
        $tabel = $this->get_table($kategori);
        $this->modal_ubah_arsip($tabel, $id, $p, $o);
    }

    public function tampilkan_berkas($tabel, $berkas, $tampil = true)
    {
        $lokasi = '';
        if ($tabel == 'dokumen_hidup') {
            $lokasi = LOKASI_DOKUMEN;
        } elseif ($tabel == 'surat_masuk' || $tabel == 'surat_keluar') {
            $lokasi = LOKASI_ARSIP;
        }
        ambilBerkas($berkas, $this->controller, null, $lokasi, $tampil ?? false);
    }

    public function unduh_berkas($tabel, $berkas)
    {
        $this->tampilkan_berkas($tabel, $berkas, false);
    }

    public function modal_ubah_arsip($tabel, $id, $p, $o)
    {
        $data = [
            'value'       => $this->arsip_fisik_model->get_lokasi_arsip($id, $tabel),
            'form_action' => site_url("{$this->controller}/ubah_dokumen/{$tabel}/{$id}/{$p}/{$o}"),
        ];

        $this->load->view('bumindes/arsip/form', $data);
    }

    public function ubah_dokumen($tabel, $id, $p, $o)
    {
        $lokasi_baru = nama_terbatas($this->input->post('lokasi_arsip'));
        $this->arsip_fisik_model->update_lokasi($tabel, $id, $lokasi_baru);

        redirect("{$this->controller}/{$p}/{$o}");
    }

    public function clear($kategori = '')
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->unset_userdata('per_page');

        if ($kategori) {
            $this->kategori($kategori);
        }

        redirect($this->controller);
    }

    private function get_table($kategori)
    {
        if ($kategori == 'dokumen_desa' || $kategori == 'kependudukan') {
            return 'dokumen_hidup';
        }
        if ($kategori == 'layanan_surat') {
            return 'log_surat';
        }
        if ($kategori == 'surat_masuk' || $kategori == 'surat_keluar') {
            return $kategori;
        }
    }

    private function kategori($kat)
    {
        switch ($kat) {
            case 'dokumen_desa':
                $this->session->{$this->list_session[3]} = 'dokumen_desa';
                break;

            case 'surat_masuk':
                $this->session->{$this->list_session[3]} = 'surat_masuk';
                break;

            case 'surat_keluar':
                $this->session->{$this->list_session[3]} = 'surat_keluar';
                break;

            case 'kependudukan':
                $this->session->{$this->list_session[3]} = 'kependudukan';
                break;

            case 'layanan_surat':
                $this->session->{$this->list_session[3]} = 'layanan_surat';
                break;
        }
    }
}
