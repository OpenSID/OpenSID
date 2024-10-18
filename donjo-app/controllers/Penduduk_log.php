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

class Penduduk_log extends Admin_Controller
{
    public $modul_ini           = 'kependudukan';
    public $sub_modul_ini       = 'peristiwa';
    public $kategori_pengaturan = 'log_penduduk';
    private array $set_page     = ['20', '50', '100'];
    private array $list_session = ['filter_tahun', 'filter_bulan', 'kode_peristiwa', 'status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari', 'judul_statistik', 'akta_kematian', 'umurx'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['penduduk_model', 'penduduk_log_model', 'wilayah_model']);
    }

    private function clear_session(): void
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->filter_bulan = date('n');
        $this->session->filter_tahun = date('Y');
        $this->session->per_page     = 20;
    }

    public function clear(): void
    {
        $this->clear_session();

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0): void
    {
        $data['p'] = $p;
        $data['o'] = $o;

        foreach ($this->list_session as $list) {
            if (in_array($list, ['dusun', 'rw', 'rt'])) {
                ${$list} = $this->session->{$list};
            } else {
                $data[$list] = $this->session->{$list} ?: '';
            }
        }

        if (isset($dusun)) {
            $data['dusun']   = $dusun;
            $data['list_rw'] = $this->wilayah_model->list_rw($dusun);

            if (isset($rw)) {
                $data['rw']      = $rw;
                $data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

                $data['rt'] = '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = $data['rw'] = $data['rt'] = '';
        }
        $data['tahun'] = $this->session->filter_tahun;
        $data['bulan'] = $this->session->filter_bulan;

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']                 = 'index';
        $data['per_page']             = $this->session->per_page;
        $data['set_page']             = $this->set_page;
        $data['paging']               = $this->penduduk_log_model->paging($p, $o);
        $data['main']                 = $this->penduduk_log_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']              = $this->penduduk_model->autocomplete();
        $data['tahun_log_pertama']    = $this->penduduk_log_model->tahun_log_pertama();
        $data['list_jenis_peristiwa'] = $this->referensi_model->list_data('ref_peristiwa');
        $data['list_sex']             = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['list_agama']           = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['list_dusun']           = $this->wilayah_model->list_dusun();

        $this->render('penduduk_log/penduduk_log', $data);
    }

    public function dokumen($id): void
    {
        $data['main'] = $this->penduduk_log_model->get_log($id);

        // download file
        $this->load->helper('download');
        $file = $data['main']['file_akta_mati'];
        if ($file != '') {
            $path = LOKASI_DOKUMEN . $file;
            force_download($path, null);
        } else {
            show_404();
        }
    }

    public function filter($filter): void
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        redirect($this->controller);
    }

    public function dusun(): void
    {
        $this->session->unset_userdata(['rw', 'rt']);
        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $this->session->dusun = $dusun;
        } else {
            $this->session->unset_userdata('dusun');
        }

        redirect($this->controller);
    }

    public function rw(): void
    {
        $this->session->unset_userdata('rt');
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $this->session->rw = $rw;
        } else {
            $this->session->unset_userdata('rw');
        }

        redirect($this->controller);
    }

    public function rt(): void
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $this->session->rt = $rt;
        } else {
            $this->session->unset_userdata('rt');
        }

        redirect($this->controller);
    }

    public function tahun_bulan(): void
    {
        if ($bln = $this->input->post('bulan')) {
            $this->session->filter_bulan = $bln;
        } else {
            $this->session->unset_userdata('filter_bulan');
        }
        if ($thn = $this->input->post('tahun')) {
            $this->session->filter_tahun = $thn;
        } else {
            // Kalau tidak tentukan tahun, tampilkan semua
            $this->session->unset_userdata('filter_tahun');
            $this->session->unset_userdata('filter_bulan');
        }

        redirect($this->controller);
    }

    public function edit($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $data['log_status_dasar'] = $this->penduduk_log_model->get_log($id) ?? show_404();
        $data['list_ref_pindah']  = $this->referensi_model->list_data('ref_pindah');
        $data['sebab']            = $this->referensi_model->list_ref(SEBAB);
        $data['penolong_mati']    = $this->referensi_model->list_ref(PENOLONG_MATI);
        $data['form_action']      = site_url("{$this->controller}/update/{$p}/{$o}/{$id}");

        $this->load->view('penduduk_log/ajax_edit', $data);
    }

    public function update($p = 1, $o = 0, $id = ''): void
    {
        isCan('u');
        $this->penduduk_log_model->update($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function kembalikan_status($id_log): void
    {
        isCan('u');

        if (! data_lengkap()) {
            show_404();
        }

        unset($_SESSION['success']);
        $this->penduduk_log_model->kembalikan_status($id_log);

        redirect($this->controller);
    }

    public function ajax_kembalikan_status_pergi($id = 0): void
    {
        isCan('u');
        $data['nik']         = $this->penduduk_model->get_penduduk($id);
        $data['form_action'] = site_url("{$this->controller}/kembalikan_status_pergi/{$id}");

        $this->load->view('sid/kependudukan/ajax_edit_status_dasar_pergi', $data);
    }

    public function kembalikan_status_pergi($id_log = 0): void
    {
        isCan('u');

        if (! data_lengkap()) {
            show_404();
        }

        unset($_SESSION['success']);
        $this->penduduk_log_model->kembalikan_status_pergi($id_log);

        redirect($this->controller);
    }

    public function kembalikan_status_all(): void
    {
        isCan('u');

        if (! data_lengkap()) {
            show_404();
        }

        $this->penduduk_log_model->kembalikan_status_all();

        redirect($this->controller);
    }

    public function cetak($o = 0, $aksi = '', $privasi_nik = 0): void
    {
        $data['main'] = $this->penduduk_log_model->list_data($o, 0);
        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }

        $this->load->view("penduduk_log/penduduk_log_{$aksi}", $data);
    }

    public function ajax_cetak($o = 0, $aksi = ''): void
    {
        $data['o']                   = $o;
        $data['aksi']                = $aksi;
        $data['form_action']         = site_url("{$this->controller}/cetak/{$o}/{$aksi}");
        $data['form_action_privasi'] = site_url("{$this->controller}/cetak/{$o}/{$aksi}/1");

        $this->load->view('sid/kependudukan/ajax_cetak_bersama', $data);
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        $this->clear_session();
        $this->session->sex = ($sex == 0) ? null : $sex;

        if ((string) $tipe === 'akta-kematian') {
            $session                       = 'akta_kematian';
            $kategori                      = 'AKTA KEMATIAN : ';
            $this->session->status_dasar   = 2;
            $this->session->kode_peristiwa = 2;
            $this->session->unset_userdata(['filter_tahun', 'filter_bulan', 'agama']);
        }

        $this->session->{$session} = rawurldecode($nomor);

        $judul = $this->penduduk_model->get_judul_statistik($tipe, $nomor, $sex);
        if ($judul['nama']) {
            $this->session->judul_statistik = $kategori . $judul['nama'];
        } else {
            $this->session->unset_userdata('judul_statistik');
        }

        redirect($this->controller);
    }
}
