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
        $this->list_session = ['data_filter_tahun', 'data_filter_jenis', 'data_filter_cari'];
        $this->_set_page = ['50', '100', '200'];
    }

    public function index()
    {
        $total_dokumen_desa = $this->arsip_fisik_model->ambil_total_data('dokumen_desa');
        $total_surat_desa = $this->arsip_fisik_model->ambil_total_data('surat_desa');
        $total_kependudukan = $this->arsip_fisik_model->ambil_total_data('kependudukan');
        $total_layanan_surat = $this->arsip_fisik_model->ambil_total_data('layanan_surat');
        
        $data = [
            'dokumen_desa' => [
                'title'=>'Dokumen Desa',
                'total' => $total_dokumen_desa,
                'uri' => 'bumindes_arsip/clear/dokumen_desa'
            ],
            'surat_desa' => [
                'title'=>'Surat Desa',
                'total' => $total_surat_desa,
                'uri' => 'bumindes_arsip/clear/surat_desa'
            ],
            'kependudukan' => [
                'title'=>'Kependudukan',
                'total' => $total_kependudukan,
                'uri' => 'bumindes_arsip/clear/kependudukan'
            ],
            'layanan_surat' => [
                'title'=>'Layanan Surat',
                'total' => $total_layanan_surat,
                'uri' => 'bumindes_arsip/clear/layanan_surat'
            ]
        ];

        
        $this->render('bumindes/arsip/dashboard', $data);
    }

    public function tampil($kategori, $p=1)
    {
        $this->session->kategori_arsip = $kategori;
        if($filter_jenis = $this->input->post('jenis')){
            $this->session->unset_userdata($this->list_session[2]);
            $this->session->{$this->list_session[1]} = $filter_jenis;
        } 
        if($filter_tahun = $this->input->post('tahun')){
            $this->session->unset_userdata($this->list_session[2]);
            $this->session->{$this->list_session[0]} = $filter_tahun;
        }
        if($filter_cari = $this->input->post('cari')){
            $this->session->unset_userdata($this->list_session[0]);
            $this->session->unset_userdata($this->list_session[1]);
            $this->session->{$this->list_session[2]} = $filter_cari;
        }

        $this->session->data_perpage = $this->session->data_perpage ?? $this->_set_page[0];

        $data['func']              = "set_perpage/$kategori";
        $data['set_page']          = $this->_set_page;
        $data['per_page']          = $this->session->data_perpage;
        $data['paging']            = $this->arsip_fisik_model->paging($p, $kategori);
        $data['main']              = $this->arsip_fisik_model->ambil_dokumen_perpage($kategori, true, $data['per_page'], $p);
        $data['page']              = $p;
        $data['kategori']          = $kategori;

        $filter = $this->arsip_fisik_model->ambil_semua_filter($kategori);
        
        $data['list_tahun'] = $filter['tahun'];
        $data['list_jenis'] = $filter['jenis'];

        $data['main_content'] = 'bumindes/arsip/content_arsip';
        
        $this->render('bumindes/arsip/main', $data);
    }

    public function set_perpage($kategori, $page = 1)
    {
        if($sess_perpage = $this->input->post('per_page')){
            $this->session->data_perpage = $sess_perpage;
            redirect($this->controller."/$kategori/$page");
        }

        redirect($this->controller."/{$kategori}/{$page}");
    }

    public function tindakan_lihat($kategori, $id, $tindakan)
    {
        $tabel = $this->get_table($kategori);
        $berkas = $this->arsip_fisik_model->get_nama_berkas($tabel, $id);
        switch($tindakan){
            case 'lihat':
                $this->tampilkan_berkas($tabel, $berkas);
                break;
            case 'unduh':
                $this->unduh_berkas($tabel, $berkas);
                break;
        }
    }

    public function tindakan_ubah($kategori, $id, $tindakan, $p)
    {
        $tabel = $this->get_table($kategori);
        switch($tindakan){
            case 'ubah':
                $this->modal_ubah_arsip($tabel, $id, $p);
                break;
            case 'hapus':
                $this->modal_hapus_dokumen($tabel, $id, $p);
                break;
        }
    }

    public function tampilkan_berkas($tabel, $berkas, $tampil = true)
    {
        $lokasi = '';
        if($tabel=='dokumen_hidup')
            $lokasi = LOKASI_DOKUMEN;
        else if($tabel=='surat_masuk' || $tabel=='surat_keluar')
            $lokasi = LOKASI_ARSIP;
        ambilBerkas($berkas, $this->controller."/{$this->session->kategori_arsip}", null, $lokasi, $tampil ?? false);
    }

    public function test($berkas){
        ambilBerkas($berkas, $this->controller);
    }

    public function unduh_berkas($tabel, $berkas)
    {
        $this->tampilkan_berkas($tabel, $berkas, false);
    }

    public function modal_ubah_arsip($tabel, $id, $p)
    {
        $data['page'] = $p;
        $data['tabel'] = $tabel;
        $data['id_doc'] = $id;
        $this->load->view("bumindes/arsip/modal_tindakan_ubah", $data);
    }

    public function modal_hapus_dokumen($tabel, $id, $p)
    {
        $data['page'] = $p;
        $data['tabel'] = $tabel;
        $data['id_doc'] = $id;
        $this->load->view("bumindes/arsip/modal_tindakan_hapus", $data);
    }

    public function ubah_dokumen($tabel, $id, $p)
    {
        $lokasi_baru = $this->input->post('lokasi_arsip');
        $this->arsip_fisik_model->update_lokasi($tabel, $id, $lokasi_baru);

        redirect($this->controller."/{$this->session->kategori_arsip}/{$p}");
    }

    public function hapus_dokumen($tabel, $id, $p)
    {
        $this->arsip_fisik_model->hapus_dokumen($tabel, $id);

        redirect($this->controller."/{$this->session->kategori_arsip}/{$p}");
    }


    public function clear($kategori)
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->unset_userdata('data_perpage');
        $this->session->unset_userdata('tabel_arsip');
        $this->session->unset_userdata('id_arsip');
        $this->session->unset_userdata('id');

        redirect($this->controller."/$kategori");
    }

    private function get_table($kategori){
        if($kategori == 'dokumen_desa' || $kategori == 'kependudukan'){
            return 'dokumen_hidup';
        }else if($kategori == 'layanan_surat'){
            return 'log_surat';
        }else if($kategori == 'surat_masuk' || $kategori == 'surat_keluar'){
            return $kategori;
        }else
            return;
    }
}