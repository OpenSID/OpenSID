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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Penduduk;
use App\Models\SuplemenTerdata;

defined('BASEPATH') || exit('No direct script access allowed');

class Suplemen extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['suplemen_model', 'pamong_model', 'penduduk_model', 'keluarga_model', 'wilayah_model']);
        $this->modul_ini     = 'kependudukan';
        $this->sub_modul_ini = 'data-suplemen';
        $this->_list_session = ['cari', 'sasaran', 'sex', 'dusun', 'rw', 'rt'];
        $this->_set_page     = ['20', '50', '100'];
    }

    public function index($page_number = 1, $order_by = 0): void
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $sasaran = $this->input->post('sasaran');
        if (isset($sasaran)) {
            $this->session->sasaran = $sasaran;
        }

        $data = [
            'func'         => 'index',
            'set_page'     => $this->_set_page,
            'paging'       => $this->suplemen_model->paging_suplemen($page_number),
            'list_sasaran' => unserialize(SASARAN),
            'set_sasaran'  => $this->session->sasaran,
        ];

        $data['suplemen'] = $this->suplemen_model->list_data($order_by, $data['paging']->offset, $data['paging']->per_page);

        $this->render('suplemen/suplemen', $data);
    }

    public function form($id = ''): void
    {
        $this->redirect_hak_akses('u');
        if ($id) {
            $data['suplemen']    = $this->suplemen_model->get_suplemen($id) ?? show_404();
            $data['form_action'] = site_url("{$this->controller}/ubah/{$id}");
        } else {
            $data['suplemen']    = null;
            $data['form_action'] = site_url("{$this->controller}/tambah");
        }

        $data['list_sasaran'] = unserialize(SASARAN);

        $this->render('suplemen/form', $data);
    }

    public function tambah(): void
    {
        $this->redirect_hak_akses('u');
        $this->suplemen_model->create();

        redirect($this->controller);
    }

    public function ubah($id): void
    {
        $this->redirect_hak_akses('u');
        $this->suplemen_model->update($id);

        redirect($this->controller);
    }

    public function hapus($id): void
    {
        $this->redirect_hak_akses('h');
        $this->suplemen_model->hapus($id);

        redirect($this->controller);
    }

    public function panduan(): void
    {
        $this->render('suplemen/panduan');
    }

    public function filter($filter): void
    {
        if ($filter == 'dusun') {
            $this->session->unset_userdata(['rw', 'rt']);
        }
        if ($filter == 'rw') {
            $this->session->unset_userdata('rt');
        }

        //# untuk filter pada data rincian suplemen
        $value      = $this->input->post($filter);
        $id_rincian = $this->session->id_rincian;
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        redirect("{$this->controller}/rincian/{$id_rincian}");
    }

    public function aksi($aksi = '', $id_suplemen = 0): void
    {
        $this->redirect_hak_akses('u');
        $this->session->set_userdata('aksi', $aksi);

        redirect("{$this->controller}/form_terdata/{$id_suplemen}");
    }

    public function clear($id = 0): void
    {
        $this->session->per_page = $this->_set_page[0];
        //# untuk filter pada data rincian suplemen
        if ($id) {
            $this->session->id_rincian = $id;
            $this->session->unset_userdata(['cari', 'sex', 'dusun', 'rw', 'rt']);

            redirect("{$this->controller}/rincian/{$id}");
        }
        //Untuk index Suplemen
        else {
            $this->session->unset_userdata($this->_list_session);

            redirect($this->controller);
        }
    }

    public function rincian($id = '', $p = 1): void
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data                       = $this->suplemen_model->get_rincian($p, $id) ?? show_404();
        $data['sasaran']            = unserialize(SASARAN);
        $data['func']               = "rincian/{$id}";
        $data['per_page']           = $this->session->per_page;
        $data['set_page']           = $this->_set_page;
        $data['cari']               = $this->session->cari;
        $data['sex']                = $this->session->sex ?? null;
        $data['list_jenis_kelamin'] = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['list_dusun']         = $this->wilayah_model->list_dusun();

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

        $this->render('suplemen/suplemen_anggota', $data);
    }

    public function form_terdata($id): void
    {
        $this->redirect_hak_akses('u');
        $data['sasaran']      = unserialize(SASARAN);
        $data['suplemen']     = $this->suplemen_model->get_suplemen($id) ?? show_404();
        $sasaran              = $data['suplemen']['sasaran'];
        $data['list_sasaran'] = $this->suplemen_model->list_sasaran($id, $sasaran, false);
        $data['individu']     = isset($_POST['terdata']) ? $this->suplemen_model->get_terdata($_POST['terdata'], $sasaran) : null;

        $data['form_action'] = site_url("{$this->controller}/add_terdata");

        $this->render('suplemen/form_terdata', $data);
    }

    public function apipenduduksuplemen()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $suplemen = $this->input->get('suplemen');
            $sasaran  = $this->input->get('sasaran');
            $terdata  = SuplemenTerdata::where('id_suplemen', $suplemen)->pluck('id_terdata');

            switch ($sasaran) {
                case 1:
                    $this->get_pilihan_penduduk($cari, $terdata);
                    break;

                case 2:
                    $this->get_pilihan_kk($cari, $terdata);
                    break;

                default:
            }
        }

        return show_404();
    }

    private function get_pilihan_penduduk($cari, $terdata)
    {
        $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster', 'kk_level'])
            ->when($cari, static function ($query) use ($cari): void {
                $query->orWhere('nik', 'like', "%{$cari}%")
                    ->orWhere('nama', 'like', "%{$cari}%");
            })
            ->whereNotIn('id', $terdata)
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_kk($cari, $terdata)
    {
        $penduduk = Penduduk::with('pendudukHubungan')
            ->select(['tweb_penduduk.id', 'tweb_penduduk.nik', 'keluarga_aktif.no_kk', 'tweb_penduduk.kk_level', 'tweb_penduduk.nama', 'tweb_penduduk.id_cluster'])
            ->leftJoin('tweb_penduduk_hubungan', static function ($join): void {
                $join->on('tweb_penduduk.kk_level', '=', 'tweb_penduduk_hubungan.id');
            })
            ->leftJoin('keluarga_aktif', static function ($join): void {
                $join->on('tweb_penduduk.id_kk', '=', 'keluarga_aktif.id');
            })
            ->when($cari, static function ($query) use ($cari): void {
                $query->where(static function ($q) use ($cari): void {
                    $q->where('tweb_penduduk.nik', 'like', "%{$cari}%")
                        ->orWhere('keluarga_aktif.no_kk', 'like', "%{$cari}%")
                        ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%");
                });
            })
            ->whereIn('tweb_penduduk.kk_level', ['1'])
            // ->whereIn('tweb_penduduk.kk_level', ['1', '2', '3', '4'])
            ->whereNotIn('tweb_penduduk.id_kk', $terdata)
            ->orderBy('tweb_penduduk.id_kk')
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => 'No KK : ' . $item->no_kk . ' - ' . $item->pendudukHubungan->nama . '- NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    public function terdata($sasaran = 0, $id = 0): void
    {
        $data = $this->suplemen_model->get_terdata_suplemen($sasaran, $id);

        $this->render('suplemen/terdata', $data);
    }

    public function data_terdata($id = 0): void
    {
        $data['terdata']  = $this->suplemen_model->get_suplemen_terdata_by_id($id);
        $data['suplemen'] = $this->suplemen_model->get_suplemen($data['terdata']['id_suplemen']);
        $data['individu'] = $this->suplemen_model->get_terdata($data['terdata']['id_terdata'], $data['suplemen']['sasaran']);

        $this->render('suplemen/data_terdata', $data);
    }

    public function edit_terdata_form($id = 0): void
    {
        $this->redirect_hak_akses('u');
        $data                = $this->suplemen_model->get_suplemen_terdata_by_id($id);
        $data['form_action'] = site_url("{$this->controller}/edit_terdata/{$id}");

        $this->load->view('suplemen/edit_terdata', $data);
    }

    public function add_terdata($id): void
    {
        $this->redirect_hak_akses('u');
        $result = $this->suplemen_model->add_terdata($_POST, $id);

        status_sukses($result);

        $redirect = ($this->session->userdata('aksi') != 1) ? $_SERVER['HTTP_REFERER'] : "{$this->controller}/rincian/{$id}";

        $this->session->unset_userdata('aksi');

        redirect($redirect);
    }

    public function edit_terdata($id): void
    {
        $this->redirect_hak_akses('u');
        $this->suplemen_model->edit_terdata($_POST, $id);
        $id_suplemen = $_POST['id_suplemen'];

        redirect("{$this->controller}/rincian/{$id_suplemen}");
    }

    public function hapus_terdata($id_suplemen, $id_terdata): void
    {
        $this->redirect_hak_akses('h');
        $this->suplemen_model->hapus_terdata($id_terdata);

        redirect("{$this->controller}/rincian/{$id_suplemen}");
    }

    public function hapus_terdata_all($id_suplemen): void
    {
        $this->redirect_hak_akses('h');
        $this->suplemen_model->hapus_terdata_all();

        redirect("{$this->controller}/rincian/{$id_suplemen}");
    }

    // $aksi = cetak/unduh
    public function dialog_daftar($id = 0, $aksi = ''): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['form_action'] = site_url("{$this->controller}/daftar/{$id}/{$aksi}");

        $this->load->view('global/ttd_pamong', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($id = 0, $aksi = ''): void
    {
        if ($id > 0) {
            $post                    = $this->input->post();
            $temp                    = $this->session->per_page;
            $this->session->per_page = 1_000_000_000; // Angka besar supaya semua data terunduh
            $data                    = $this->suplemen_model->get_rincian(1, $id) ?? show_404();
            $data['sasaran']         = unserialize(SASARAN);
            $data['config']          = $this->header['desa'];
            $data['pamong_ttd']      = $this->pamong_model->get_data($post['pamong_ttd']);
            $data['pamong_ketahui']  = $this->pamong_model->get_data($post['pamong_ketahui']);
            $data['aksi']            = $aksi;
            $this->session->per_page = $temp;

            //pengaturan data untuk format cetak/ unduh
            $data['file']      = 'Laporan Suplemen ' . $data['suplemen']['nama'];
            $data['isi']       = 'suplemen/cetak';
            $data['letak_ttd'] = ['2', '2', '3'];

            $this->load->view('global/format_cetak', $data);
        }
    }

    public function impor(): void
    {
        $this->redirect_hak_akses('u');
        $id = $this->input->post('id_suplemen');
        $this->suplemen_model->impor($id);

        redirect("{$this->controller}/rincian/{$id}");
    }

    public function ekspor($id = 0): void
    {
        $temp = $this->session->per_page;

        $this->session->per_page = 0;
        $this->suplemen_model->ekspor($id);
        $this->session->per_page = $temp;
    }
}
