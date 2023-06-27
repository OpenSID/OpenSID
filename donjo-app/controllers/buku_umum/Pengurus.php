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

use App\Models\Agama;
use App\Models\Pamong;
use App\Models\PendidikanKK;
use App\Models\RefJabatan;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengurus extends Admin_Controller
{
    private $_set_page;
    private $_list_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['pamong_model', 'penduduk_model', 'wilayah_model']);
        $this->modul_ini          = 301;
        $this->sub_modul_ini      = 302;
        $this->_set_page          = ['20', '50', '100'];
        $this->_list_session      = ['status', 'cari'];
        $this->header['kategori'] = 'Pemerintah Desa';
    }

    public function clear()
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = $this->_set_page[0];
        $this->session->status   = 1;
        redirect('pengurus');
    }

    public function index($p = 1)
    {
        foreach ($this->_list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']            = 'index';
        $data['set_page']        = $this->_set_page;
        $data['per_page']        = $this->session->per_page;
        $data['paging']          = $this->pamong_model->paging($p);
        $data['main']            = $this->pamong_model->list_data($data['paging']->offset, $data['paging']->per_page);
        $data['keyword']         = $this->pamong_model->autocomplete();
        $data['main_content']    = 'home/pengurus';
        $data['subtitle']        = 'Buku ' . ucwords(setting('sebutan_pemerintah_desa'));
        $data['selected_nav']    = 'aparat';
        $data['kecuali_jabatan'] = RefJabatan::EXCLUDE_DELETE;

        $this->render('bumindes/umum/main', $data);
    }

    public function form($id = 0)
    {
        $this->redirect_hak_akses('u');
        $id_pend = $this->input->post('id_pend');

        if ($id) {
            $data['pamong'] = $this->pamong_model->get_data($id);
            if (! isset($id_pend)) {
                $id_pend = $data['pamong']['id_pend'];
            }
            $data['form_action'] = site_url("pengurus/update/{$id}");
        } else {
            $data['pamong']      = null;
            $data['form_action'] = site_url('pengurus/insert');
        }

        $kecuali = [];

        // Cek apakah kades
        if (Pamong::where('jabatan_id', 1)->where('pamong_status', 1)->exists() && $data['pamong']['jabatan_id'] != 1) {
            $kecuali[] = 1;
        }

        // Cek apakah sekdes
        if (Pamong::where('jabatan_id', 2)->where('pamong_status', 1)->exists() && $data['pamong']['jabatan_id'] != 2) {
            $kecuali[] = 2;
        }

        $data['jabatan']       = RefJabatan::whereNotIn('id', $kecuali)->pluck('nama', 'id');
        $data['atasan']        = $this->pamong_model->list_atasan($id);
        $data['penduduk']      = $this->pamong_model->list_penduduk($id_pend ?? 0);
        $data['pendidikan_kk'] = PendidikanKK::pluck('nama', 'id');
        $data['agama']         = Agama::pluck('nama', 'id');

        if (! empty($id_pend)) {
            $data['individu'] = $this->penduduk_model->get_penduduk($id_pend);
        } else {
            $data['individu'] = null;
        }

        $this->render('home/pengurus_form', $data);
    }

    public function filter($filter)
    {
        $this->redirect_hak_akses('u');
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('pengurus');
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->set_validasi();
        $this->form_validation->set_rules('pamong_tag_id_card', 'Tag ID Card', 'is_unique[tweb_desa_pamong.pamong_tag_id_card]]');

        if ($this->form_validation->run() !== true) {
            session_error(trim(validation_errors()));
            redirect('pengurus/form');
        } else {
            $this->pamong_model->insert();
            redirect('pengurus');
        }
    }

    public function update($id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->set_validasi();

        $this->form_validation->set_rules('pamong_tag_id_card', 'Tag ID Card', "is_unique[tweb_desa_pamong.pamong_tag_id_card,pamong_id,{$id}]");

        if ($this->form_validation->run() !== true) {
            session_error(trim(validation_errors()));
            redirect("pengurus/form/{$id}");
        } else {
            $this->pamong_model->update($id);
            redirect('pengurus');
        }
    }

    private function set_validasi()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function delete($id = 0)
    {
        $this->redirect_hak_akses('h');
        $this->pamong_model->delete($id);
        redirect('pengurus');
    }

    public function delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->pamong_model->delete_all();
        redirect('pengurus');
    }

    public function ttd($id = 0, $val = 0)
    {
        $this->redirect_hak_akses('u');
        $this->pamong_model->ttd('a.n', $id, $val);
        redirect('pengurus');
    }

    public function ub($id = 0, $val = 0)
    {
        $this->redirect_hak_akses('u');
        $this->pamong_model->ttd('u.b', $id, $val);
        redirect('pengurus');
    }

    public function urut($p = 1, $id = 0, $arah = 0)
    {
        $this->redirect_hak_akses('u');
        $this->pamong_model->urut($id, $arah);
        redirect("pengurus/index/{$p}");
    }

    public function lock($id = 0, $val = 1)
    {
        $this->redirect_hak_akses('u');
        $this->pamong_model->lock($id, $val);
        redirect('pengurus');
    }

    public function kehadiran($id = 0, $val = 1)
    {
        $this->redirect_hak_akses('u');
        $this->pamong_model->kehadiran($id, $val);
        redirect('pengurus');
    }

    public function daftar($aksi = 'cetak')
    {
        // TODO :: gunakan view global penandatangan
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);

        $data['desa'] = $this->header['desa'];
        $data['main'] = $this->pamong_model->list_data();

        $this->load->view('home/' . $aksi, $data);
    }

    public function bagan($ada_bpd = '')
    {
        $data['desa']    = $this->header['desa'];
        $data['bagan']   = $this->pamong_model->list_bagan();
        $data['ada_bpd'] = ! empty($ada_bpd);
        $this->render('home/bagan', $data);
    }

    public function atur_bagan()
    {
        $this->redirect_hak_akses('u');
        $data['atasan']      = $this->pamong_model->list_atasan();
        $data['form_action'] = site_url('pengurus/update_bagan');
        $this->load->view('home/ajax_atur_bagan', $data);
    }

    public function update_bagan()
    {
        $this->redirect_hak_akses('u');
        $post = $this->input->post();
        $this->pamong_model->update_bagan($post);
        redirect('pengurus');
    }

    public function atur_bagan_layout()
    {
        $this->redirect_hak_akses('u');
        $data = [
            'judul'    => 'Atur Ukuran Bagan',
            'kategori' => ['conf_bagan'],
        ];

        $this->load->view('global/modal_setting', $data);
    }

    // Jabatan
    public function jabatan()
    {
        return view('admin.jabatan.index', [
            'selected_nav' => 'pengurus',
        ]);
    }

    public function jabatandatatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(RefJabatan::query()->orderBy('id')->orderBy('jenis', 'desc'))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h') && ! in_array($row->id, RefJabatan::EXCLUDE_DELETE)) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('pengurus.jabatanform', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h') && ! in_array($row->id, RefJabatan::EXCLUDE_DELETE)) {
                        $aksi .= '<a href="#" data-href="' . route('pengurus.jabatandelete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function jabatanform($id = '')
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = route('pengurus.jabatanupdate', $id);
            $jabatan     = RefJabatan::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = route('pengurus.jabataninsert');
            $jabatan     = null;
        }

        $selected_nav = 'pengurus';

        return view('admin.jabatan.form', compact('selected_nav', 'action', 'form_action', 'jabatan'));
    }

    public function jabataninsert()
    {
        $this->redirect_hak_akses('u');

        if (RefJabatan::insert(static::jabatanValidate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'pengurus/jabatan');
        }
        redirect_with('error', 'Gagal Tambah Data', 'pengurus/jabatan');
    }

    public function jabatanUpdate($id = '')
    {
        $this->redirect_hak_akses('u');

        $data = RefJabatan::findOrFail($id);

        $requests = static::jabatanValidate($this->request);

        if ($data->update($requests)) {
            redirect_with('success', 'Berhasil Ubah Data', 'pengurus/jabatan');
        }
        redirect_with('error', 'Gagal Ubah Data', 'pengurus/jabatan');
    }

    public function jabatandelete($id = '')
    {
        $this->redirect_hak_akses('h');

        $data = RefJabatan::findOrFail($id);
        if (in_array($data->id, RefJabatan::EXCLUDE_DELETE)) {
            redirect_with('error', 'Gagal Hapus Data, ' . $data->jabatan, 'pengurus/jabatan');
        }

        if ($data->destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'pengurus/jabatan');
        }

        redirect_with('error', 'Gagal Hapus Data', 'pengurus/jabatan');
    }

    // Hanya filter inputan
    protected static function jabatanValidate($request = [])
    {
        return [
            'nama'    => nama_terbatas($request['nama']),
            'tupoksi' => $request['tupoksi'],
        ];
    }
}
