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

use Carbon\Carbon;
use App\Enums\StatusEnum;
use App\Models\Pemilihan;

defined('BASEPATH') || exit('No direct script access allowed');

class Dpt extends Admin_Controller
{
    private $set_page;
    private $list_session;
    private $tanggal_pemilihan;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penduduk_model', 'dpt_model', 'wilayah_model']);
        $this->modul_ini     = 'kependudukan';
        $this->sub_modul_ini = 'calon-pemilih';
        $this->set_page      = ['50', '100', '200', [0, 'Semua']];
        $this->list_session  = ['cari', 'sex', 'dusun', 'rw', 'rt', 'tanggal_pemilihan', 'umurx', 'umur_min', 'umur_max', 'cacatx', 'menahunx', 'pekerjaan_id', 'status', 'agama', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'tag_id_card'];
        $tanggal                  = Pemilihan::status()->orderBy('tanggal')->first()->tanggal ?? Carbon::now();
        $this->tanggal_pemilihan = Carbon::parse($tanggal)->format('d-m-Y');
    }

    public function clear()
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = $this->set_page[0];

        redirect($this->controller);
    }

    public function index($p = 1, $o = 0)
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

        $this->session->tanggal_pemilihan = $this->session->tanggal_pemilihan ?? $this->tanggal_pemilihan;

        if (isset($dusun)) {
            $data['dusun']   = $dusun;
            $data['list_rw'] = $this->wilayah_model->list_rw($dusun);

            if (isset($rw)) {
                $data['rw']      = $rw;
                $data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

                if (isset($rt)) {
                    $data['rt'] = $rt;
                } else {
                    $data['rt'] = '';
                }
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = $data['rw'] = $data['rt'] = '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']               = 'index';
        $data['set_page']           = $this->set_page;
        $list_data                  = $this->dpt_model->list_data($o, $p);
        $data['paging']             = $list_data['paging'];
        $data['main']               = $list_data['main'];
        $data['list_jenis_kelamin'] = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['list_dusun']         = $this->wilayah_model->list_dusun();
        $data['keyword']            = $this->dpt_model->autocomplete();

        $this->render('dpt/dpt', $data);
    }

    public function filter($filter)
    {
        if ($filter == 'dusun') {
            $this->session->unset_userdata(['rw', 'rt']);
        }
        if ($filter == 'rw') {
            $this->session->unset_userdata('rt');
        }

        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        redirect($this->controller);
    }

    public function ajax_adv_search()
    {
        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $data['input_umur']           = true;
        $data['list_agama']           = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['list_pendidikan']      = $this->referensi_model->list_data('tweb_penduduk_pendidikan');
        $data['list_pendidikan_kk']   = $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk');
        $data['list_pekerjaan']       = $this->referensi_model->list_data('tweb_penduduk_pekerjaan');
        $data['list_status_kawin']    = $this->referensi_model->list_data('tweb_penduduk_kawin');
        $data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
        $data['list_tag_id_card']     = StatusEnum::all();
        $data['form_action']          = site_url("{$this->controller}/adv_search_proses");

        $this->load->view('sid/kependudukan/ajax_adv_search_form', $data);
    }

    public function adv_search_proses()
    {
        $adv_search = $_POST;
        $i          = 0;

        while ($i++ < count($adv_search)) {
            $col[$i] = key($adv_search);
            next($adv_search);
        }
        $i = 0;

        while ($i++ < count($col)) {
            if ($adv_search[$col[$i]] == '') {
                unset($adv_search[$col[$i]], $_SESSION[$col[$i]]);
            } else {
                $_SESSION[$col[$i]] = $adv_search[$col[$i]];
            }
        }

        redirect($this->controller);
    }

    public function cetak($page = 1, $o = 0, $aksi = '', $privasi_nik = 0)
    {
        $data['main'] = $this->dpt_model->list_data($o, $page)['main'];
        $data['aksi'] = $aksi;
        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }
        $this->load->view("dpt/dpt_{$aksi}", $data);
    }

    public function ajax_cetak($page = 1, $o = 0, $aksi = '')
    {
        $data['o']                   = $o;
        $data['aksi']                = $aksi;
        $data['form_action']         = site_url("{$this->controller}/cetak/{$page}/{$o}/{$aksi}");
        $data['form_action_privasi'] = site_url("{$this->controller}/cetak/{$page}/{$o}/{$aksi}/1");

        $this->load->view('sid/kependudukan/ajax_cetak_bersama', $data);
    }

    // Pemilihan
    public function pemilihan()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(Pemilihan::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('dpt.pemilihanform', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('u')) {
                        if ($row->status) {
                            $aksi .= '<a href="' . site_url("dpt/pemilihanstatus/{$row->id}/1") . '" class="btn bg-olive btn-sm" title="Non Aktifkan"><i class="fa fa-star"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("dpt/pemilihanstatus/{$row->id}/0") . '" class="btn bg-purple btn-sm" title="Aktifkan"><i class="fa fa-star-o"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('dpt.pemilihandelete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return view('admin.pemilihan.index', [
            'selected_nav' => 'dpt',
        ]);
    }

    public function pemilihanform($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = route('dpt.pemilihanupdate', $id);
            $pemilihan     = Pemilihan::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = route('dpt.pemilihaninsert');
            $pemilihan     = null;
        }

        return view('admin.pemilihan.form', compact('action', 'form_action', 'pemilihan'));
    }

    public function pemilihaninsert()
    {
        $this->redirect_hak_akses('u');

        if (Pemilihan::create(static::pemilihanValidate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'dpt/pemilihan');
        }
        redirect_with('error', 'Gagal Tambah Data', 'dpt/pemilihan');
    }

    public function pemilihanUpdate($id = '')
    {
        $this->redirect_hak_akses('u');

        $data = Pemilihan::findOrFail($id);

        if ($data->update(static::pemilihanValidate($this->request, $data->id))) {
            redirect_with('success', 'Berhasil Ubah Data', 'dpt/pemilihan');
        }
        redirect_with('error', 'Gagal Ubah Data', 'dpt/pemilihan');
    }

    public function pemilihanstatus($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $status = Pemilihan::findOrFail($id);
        $status->update(['status' => ($val == 1) ? 0 : 1]);

        if ($status->update(['status' => ($val == 1) ? 0 : 1])) {
            redirect_with('success', 'Berhasil Ubah Data', 'dpt/pemilihan');
        }
        redirect_with('error', 'Gagal Ubah Data', 'dpt/pemilihan');
    }

    public function pemilihandelete($id = '')
    {
        $this->redirect_hak_akses('h');

        $data = Pemilihan::findOrFail($id);

        if ($data->destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'dpt/pemilihan');
        }

        redirect_with('error', 'Gagal Hapus Data', 'dpt/pemilihan');
    }

    protected static function pemilihanValidate($request = [])
    {
        return [
            'judul'    => nama_terbatas($request['judul']),
            'tanggal' => date('Y-m-d', strtotime($request['tanggal'])),
            'keterangan' => $request['keterangan'],
            'status' => $request['status'],
        ];
    }
}
