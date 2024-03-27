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

use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Statistik extends Admin_Controller
{
    public $modul_ini            = 'statistik';
    public $sub_modul_ini        = 'statistik-kependudukan';
    private array $_list_session = ['lap', 'order_by', 'dusun', 'rw', 'rt', 'status', 'tahun', 'filter_global'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['wilayah_model', 'laporan_penduduk_model', 'pamong_model', 'program_bantuan_model']);
    }

    public function index(): void
    {
        $data        = $this->get_cluster_session();
        $data['lap'] = $this->session->lap ?? '0';

        $data['order_by']              = $this->session->order_by;
        $data['main']                  = $this->laporan_penduduk_model->list_data($data['lap'], $data['order_by']);
        $data['tautan_data']           = $this->tautan_data($data['lap']);
        $data['list_dusun']            = $this->wilayah_model->list_dusun();
        $data['heading']               = $this->laporan_penduduk_model->judul_statistik($data['lap']);
        $data['stat_penduduk']         = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['stat_keluarga']         = $this->referensi_model->list_ref(STAT_KELUARGA);
        $data['stat_rtm']              = $this->referensi_model->list_ref(STAT_RTM);
        $data['stat_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
        $data['stat_bantuan']          = $this->program_bantuan_model->list_program(0);
        $data['tahun_bantuan_pertama'] = $this->program_bantuan_model->tahun_bantuan_pertama(($data['lap'] == 'bantuan_penduduk') ? '1' : '2') ?? date('Y');
        $data['tahun']                 = $this->session->tahun ?? null;
        $data['status']                = $this->session->status ?? null;
        $this->session->filter_global  = [
            'tahun'  => $data['tahun'],
            'status' => $data['status'],
            'dusun'  => $data['dusun'],
            'rw'     => $data['rw'],
            'rt'     => $data['rt'],
        ];

        $data['judul_kelompok'] = 'Jenis Kelompok';
        $data['bantuan']        = (int) $data['lap'] > 50 || in_array($data['lap'], ['bantuan_keluarga', 'bantuan_penduduk']);
        $this->get_data_stat($data, $data['lap']);

        $this->render('statistik/penduduk', $data);
    }

    private function tautan_data(?string $lap = '0')
    {
        if ((int) $lap > 50) {
            $program_id = preg_replace('/^50/', '', $lap);

            // TODO: Sederhanakan query ini, pindahkan ke model
            $sasaran = $this->db
                ->select('sasaran')
                ->group_start()
                ->where('config_id', identitas('id'))
                ->or_where('config_id', null)
                ->group_end()
                ->where('id', $program_id)
                ->get('program')
                ->row()
                ->sasaran;
        }

        switch (true) {
            case in_array($lap, [21, 22, 23, 24, 25, 26, 27, 'kelas_sosial', 'bantuan_keluarga']) || ((int) $lap > 50 && (int) $sasaran == 2):
                $tautan = site_url("keluarga/statistik/{$lap}/");
                break;

            case $lap == 'bdt' || ((int) $lap > 50 && (int) $sasaran == 3):
                $tautan = site_url("rtm/statistik/{$lap}/");
                break;

            case $lap == 'akta-kematian':
                $tautan = site_url("penduduk_log/statistik/{$lap}/");
                break;

            case (int) $lap < 50 || $lap == 'kia' || ((int) $lap > 50 && (int) $sasaran == 1):
                $tautan = site_url("penduduk/statistik/{$lap}/");
                break;

            case (int) $lap > 50 && (int) $sasaran == 4:
                $tautan = site_url("kelompok/statistik/{$lap}/");
                break;

            default:
                // code...
                break;
        }

        return $tautan;
    }

    public function clear($lap = '0', $order_by = '1'): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->order_by($lap, $order_by);
    }

    public function order_by($lap = '0', $order_by = '1'): void
    {
        $this->session->lap      = $lap;
        $this->session->order_by = $order_by;

        redirect('statistik');
    }

    private function get_data_stat(&$data, $lap): void
    {
        switch (true) {
            case (int) $lap > 50:
                // Untuk program bantuan, $lap berbentuk '50<program_id>'
                $program_id             = preg_replace('/^50/', '', $lap);
                $data['program']        = $this->program_bantuan_model->get_sasaran($program_id);
                $data['judul_kelompok'] = $data['program']['judul_sasaran'];
                $kategori               = 'bantuan';
                break;

            case in_array($lap, ['bantuan_penduduk', 'bantuan_keluarga']):
                // Kategori bantuan
                $kategori = 'bantuan';
                break;

            case (int) $lap > 20 || "{$lap}" == 'kelas_sosial':
                // Kelurga
                $kategori = 'keluarga';

                break;

            case $lap == 'bdt':
                // RTM
                $kategori = 'rtm';
                break;

            case $lap == null:
            default:
                // Penduduk
                $kategori = 'penduduk';
                break;
        }

        $data['stat']     = $this->laporan_penduduk_model->judul_statistik($lap);
        $data['kategori'] = $kategori;
    }

    // TODO: Gunakan view global ttd
    public function dialog($aksi = ''): void
    {
        $data['aksi']        = $aksi;
        $data['lap']         = $this->session->lap;
        $data['pamong']      = Pamong::penandaTangan()->get();
        $data['getKades']    = kades()->id;
        $data['form_action'] = site_url("statistik/daftar/{$aksi}/{$data['lap']}");

        $this->load->view('statistik/ajax_daftar', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($aksi = '', $lap = ''): void
    {
        foreach ($this->_list_session as $list) {
            $data[$list] = $this->session->{$list};
        }

        $post               = $this->input->post();
        $data['aksi']       = $aksi;
        $data['stat']       = $this->laporan_penduduk_model->judul_statistik($lap);
        $data['config']     = $this->header['desa'];
        $data['main']       = $this->laporan_penduduk_model->list_data($lap);
        $data['pamong_ttd'] = $this->pamong_model->get_data($post['pamong_ttd']);
        $data['laporan_no'] = $post['laporan_no'];

        $data['file']      = 'Statistik penduduk'; // nama file
        $data['isi']       = 'statistik/penduduk_cetak';
        $data['letak_ttd'] = ['1', '1', '1'];

        $this->load->view('global/format_cetak', $data);
    }

    public function rentang_umur(): void
    {
        $data['lap']                   = 13;
        $data['main']                  = $this->laporan_penduduk_model->list_data_rentang();
        $data['stat_penduduk']         = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['stat_keluarga']         = $this->referensi_model->list_ref(STAT_KELUARGA);
        $data['stat_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
        $data['stat_bantuan']          = $this->program_bantuan_model->list_program(0);
        $data['judul_kelompok']        = 'Jenis Kelompok';
        $this->get_data_stat($data, $data['lap']);

        $this->render('statistik/rentang_umur', $data);
    }

    public function form_rentang($id = 0): void
    {
        if ($id == 0) {
            $data['form_action']       = site_url('statistik/rentang_insert');
            $data['rentang']           = $this->laporan_penduduk_model->get_rentang_terakhir();
            $data['rentang']['nama']   = '';
            $data['rentang']['sampai'] = '';
        } else {
            $data['form_action'] = site_url("statistik/rentang_update/{$id}");
            $data['rentang']     = $this->laporan_penduduk_model->get_rentang($id) ?? show_404();
        }
        $this->load->view('statistik/ajax_rentang_form', $data);
    }

    public function rentang_insert(): void
    {
        isCan('h');

        $data['insert'] = $this->laporan_penduduk_model->insert_rentang();
        redirect('statistik/rentang_umur');
    }

    public function rentang_update($id = 0): void
    {
        isCan('u');

        $this->laporan_penduduk_model->update_rentang($id);
        redirect('statistik/rentang_umur');
    }

    public function rentang_delete($id = 0): void
    {
        isCan('h');
        $this->laporan_penduduk_model->delete_rentang($id);
        redirect('statistik/rentang_umur');
    }

    public function delete_all_rentang(): void
    {
        isCan('h');
        $this->laporan_penduduk_model->delete_all_rentang();
        redirect('statistik/rentang_umur');
    }

    public function dusun($lap = 0): void
    {
        if ($lap) {
            $this->session->lap = $lap;
        }

        $this->session->unset_userdata(['rw', 'rt']);
        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $this->session->dusun = $dusun;
        } else {
            $this->session->unset_userdata('dusun');
        }

        redirect('statistik');
    }

    public function rw($lap = 0): void
    {
        if ($lap) {
            $this->session->lap = $lap;
        }

        $this->session->unset_userdata('rt');
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $this->session->rw = $rw;
        } else {
            $this->session->unset_userdata('rw');
        }
        redirect('statistik');
    }

    public function rt($lap = 0): void
    {
        if ($lap) {
            $this->session->lap = $lap;
        }

        $rt = $this->input->post('rt');
        if ($rt != '') {
            $this->session->rt = $rt;
        } else {
            $this->session->unset_userdata('rt');
        }
        redirect('statistik');
    }

    public function filter($key = ''): void
    {
        $value = $this->input->post($key);
        if ($value != '') {
            $this->session->{$key} = $value;
        } else {
            $this->session->unset_userdata($key);
        }

        redirect($this->controller);
    }

    private function get_cluster_session()
    {
        foreach ($this->_list_session as $list) {
            if (in_array($list, ['dusun', 'rw', 'rt'])) {
                ${$list} = $this->session->{$list};
            }
        }

        if (isset($dusun)) {
            $data['dusun']   = $dusun;
            $data['list_rw'] = $this->wilayah_model->list_rw($dusun);

            if (isset($rw)) {
                $data['rw']      = $rw;
                $data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

                $data['rt'] = $rt ?? '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = $data['rw'] = $data['rt'] = '';
        }

        return $data;
    }

    public function load_chart_gis($lap = 0): void
    {
        $data['main'] = $this->laporan_penduduk_model->list_data($lap);
        $data['lap']  = $lap;
        $this->get_data_stat($data, $lap);
        $this->load->view('gis/penduduk_gis', $data);
    }

    public function chart_gis_desa($lap = 0, $desa = null): void
    {
        $this->session->desa = $desa;
        $this->session->unset_userdata(['dusun', 'rw', 'rt']);

        redirect("statistik/load_chart_gis/{$lap}");
    }

    public function chart_gis_dusun($lap = 0, $dusun = null): void
    {
        $this->session->dusun = $dusun;
        $this->session->unset_userdata(['rw', 'rt']);

        redirect("statistik/load_chart_gis/{$lap}");
    }

    public function chart_gis_rw($lap = 0, $dusun = null, $rw = null): void
    {
        $this->session->dusun = $dusun;
        $this->session->rw    = $rw;
        $this->session->unset_userdata(['rt']);

        redirect("statistik/load_chart_gis/{$lap}");
    }

    public function chart_gis_rt($lap = 0, $dusun = null, $rw = null, $rt = null): void
    {
        $this->session->dusun = $dusun;
        $this->session->rw    = $rw;
        $this->session->rt    = $rt;

        redirect("statistik/load_chart_gis/{$lap}");
    }

    public function ajax_peserta_program_bantuan()
    {
        $filter = [
            'status' => $this->session->status,
            'tahun'  => $this->session->tahun,
            'dusun'  => $this->session->dusun,
            'rw'     => $this->session->rw,
            'rt'     => $this->session->rt,
        ];

        $peserta = $this->program_bantuan_model->get_peserta_bantuan($filter);
        $data    = [];
        $no      = $_POST['start'];

        foreach ($peserta as $baris) {
            if (null === $baris['peserta']) {
                continue;
            }
            $no++;
            $row    = [];
            $row[]  = $no;
            $row[]  = $baris['program'];
            $row[]  = $baris['peserta'];
            $row[]  = $baris['alamat'];
            $data[] = $row;
        }

        return json([
            'recordsTotal'    => $this->program_bantuan_model->count_peserta_bantuan_all(),
            'recordsFiltered' => $this->program_bantuan_model->count_peserta_bantuan_filtered(),
            'data'            => $data,
        ]);
    }
}
