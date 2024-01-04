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

use App\Models\LogPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_penduduk_mutasi extends Admin_Controller
{
    public $modul_ini            = 'buku-administrasi-desa';
    public $sub_modul_ini        = 'administrasi-penduduk';
    public $kategori_pengaturan  = 'data_lengkap';
    private array $_set_page     = ['10', '20', '50', '100'];
    private array $_list_session = ['tgl_lengkap', 'filter_tahun', 'filter_bulan', 'filter', 'kode_peristiwa', 'status_dasar', 'cari', 'status', 'status_penduduk'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['pamong_model', 'penduduk_model', 'penduduk_log_model']);
        $this->logpenduduk = new LogPenduduk();
    }

    public function index($page_number = 1, $order_by = 0): void
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        // Menampilkan hanya kode peristiwa
        $this->session->kode_peristiwa = [2, 3, 5];
        // Menampilkan hanya status penduduk TETAP
        $this->session->status_penduduk = 1;
        $tanggal_lengkap                = $this->logpenduduk::min('tgl_lapor');

        $data = [
            'main_content'  => 'bumindes/penduduk/mutasi/content_mutasi',
            'subtitle'      => 'Buku Mutasi Penduduk Desa',
            'selected_nav'  => 'mutasi',
            'p'             => $page_number,
            'o'             => $order_by,
            'cari'          => $this->session->cari ?: '',
            'filter'        => $this->session->filter ?: '',
            'per_page'      => $this->session->per_page,
            'bulan'         => $this->session->filter_bulan ?: null,
            'tahun'         => $this->session->filter_tahun ?: null,
            'func'          => 'index',
            'set_page'      => $this->_set_page,
            'tgl_lengkap'   => $tanggal_lengkap,
            'paging'        => $this->penduduk_log_model->paging($page_number),
            'tahun_lengkap' => (new DateTime($tanggal_lengkap))->format('Y'),
            'data_hapus'    => $this->penduduk_log_model->list_data_hapus(),
        ];

        $data['main'] = $this->penduduk_log_model->list_data($order_by, $data['paging']->offset, $data['paging']->per_page);

        if ($data['tgl_lengkap']) {
            $this->session->tgl_lengkap = $data['tgl_lengkap'];
        }

        $this->render('bumindes/penduduk/main', $data);
    }

    private function clear_session(): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = $this->_set_page[0];
    }

    public function clear(): void
    {
        $this->clear_session();
        // Set default filter ke tahun dan bulan sekarang
        $this->session->filter_tahun = date('Y');
        $this->session->filter_bulan = date('m');
        redirect('bumindes_penduduk_mutasi');
    }

    public function ajax_cetak($o = 0, $aksi = ''): void
    {
        // pengaturan data untuk dialog cetak/unduh
        $data = [
            'o'           => $o,
            'aksi'        => $aksi,
            'form_action' => site_url("bumindes_penduduk_mutasi/cetak/{$o}/{$aksi}"),
            'isi'         => 'bumindes/penduduk/mutasi/ajax_dialog_mutasi',
        ];

        $this->load->view('global/dialog_cetak', $data);
    }

    public function cetak($o = 0, $aksi = '', $privasi_nik = 0): void
    {
        $data              = $this->modal_penandatangan();
        $data['aksi']      = $aksi;
        $data['bulan']     = $this->session->filter_bulan ?: date('m');
        $data['tahun']     = $this->session->filter_tahun ?: date('Y');
        $data['main']      = $this->penduduk_log_model->list_data($o);
        $data['config']    = $this->header['desa'];
        $data['tgl_cetak'] = $this->input->post('tgl_cetak');
        $data['file']      = 'Buku Mutasi Penduduk';
        $data['isi']       = 'bumindes/penduduk/mutasi/content_mutasi_cetak';
        $data['letak_ttd'] = ['1', '2', '8'];

        $this->load->view('global/format_cetak', $data);
    }

    public function autocomplete(): void
    {
        $data = $this->penduduk_model->autocomplete($this->input->post('cari'));
        $this->output->set_content_type('application/json')->set_output(json_encode($data, JSON_THROW_ON_ERROR));
    }

    public function filter($filter): void
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('bumindes_penduduk_mutasi');
    }
}
