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

class Bumindes_penduduk_induk extends Admin_Controller
{
    private $_set_page;
    private $_list_session;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['pamong_model', 'penduduk_model']);

        $this->modul_ini     = 301;
        $this->sub_modul_ini = 303;

        $this->_set_page = ['10', '20', '50', '100'];

        // Samakan dengan donjo-app/controllers/Penduduk.php, karena memanggil penduduk_model
        $this->_list_session = ['filter_tahun', 'filter_bulan', 'status_hanya_tetap', 'jenis_peristiwa', 'filter', 'status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari', 'umur_min', 'umur_max', 'umurx', 'pekerjaan_id', 'status', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'judul_statistik', 'cacat', 'cara_kb_id', 'akta_kelahiran', 'status_ktp', 'id_asuransi', 'status_covid', 'bantuan_penduduk', 'log', 'warganegara', 'menahun', 'hubungan', 'golongan_darah', 'hamil', 'kumpulan_nik'];
    }

    public function index($page_number = 1, $order_by = 0)
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        // Hanya menampilkan data status_dasar HIDUP, HILANG
        $this->session->status_dasar = [1, 4];

        // Menampilkan hanya status penduduk TETAP
        $this->session->status_penduduk = 1;

        $list_data = $this->penduduk_model->list_data($order_by, $page_number);
        $data      = [
            'main_content' => 'bumindes/penduduk/induk/content_induk',
            'subtitle'     => 'Buku Induk Penduduk',
            'selected_nav' => 'induk',
            'order_by'     => $order_by,
            'cari'         => $this->session->cari ?: '',
            'filter'       => $this->session->filter ?: '',
            'bulan'        => $this->session->filter_bulan,
            'tahun'        => $this->session->filter_tahun,
            'func'         => 'index',
            'set_page'     => $this->_set_page,
            'paging'       => $list_data['paging'],
            'list_tahun'   => $this->penduduk_log_model->list_tahun(),
        ];

        // TODO : Cari cara agar bisa digabungkan ke array $data = [] (tdk terpisah)
        $data['main'] = $list_data['main'];

        $this->render('bumindes/penduduk/main', $data);
    }

    private function clear_session()
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = $this->_set_page[0];
    }

    public function clear()
    {
        $this->clear_session();
        // Set default filter ke tahun dan bulan sekarang
        $this->session->filter_tahun = date('Y');
        $this->session->filter_bulan = date('m');
        redirect('bumindes_penduduk_induk');
    }

    public function ajax_cetak($o = 0, $aksi = '')
    {
        // pengaturan data untuk dialog cetak/unduh
        $data = [
            'o'                   => $o,
            'aksi'                => $aksi,
            'form_action'         => site_url("bumindes_penduduk_induk/cetak/{$o}/{$aksi}"),
            'form_action_privasi' => site_url("bumindes_penduduk_induk/cetak/{$o}/{$aksi}/1"),
            'isi'                 => 'bumindes/penduduk/induk/ajax_dialog_induk',
        ];

        $this->load->view('global/dialog_cetak', $data);
    }

    public function cetak($o = 0, $aksi = '', $privasi_nik = 0)
    {
        $data = [
            'aksi'           => $aksi,
            'config'         => $this->header['desa'],
            'pamong_ketahui' => $this->pamong_model->get_ttd(),
            'pamong_ttd'     => $this->pamong_model->get_ub(),
            'main'           => $this->penduduk_model->list_data($o, 0),
            'bulan'          => $this->session->filter_bulan,
            'tahun'          => $this->session->filter_tahun,
            'tgl_cetak'      => $_POST['tgl_cetak'],
            'privasi_nik'    => $privasi_nik == 1 ? true : false,
            'file'           => 'Buku Induk Kependudukan',
            'isi'            => 'bumindes/penduduk/induk/content_induk_cetak',
            'letak_ttd'      => ['2', '2', '9'],
        ];
        $this->load->view('global/format_cetak', $data);
    }

    public function autocomplete()
    {
        $data = $this->penduduk_model->autocomplete($this->input->post('cari'));
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('bumindes_penduduk_induk');
    }
}
