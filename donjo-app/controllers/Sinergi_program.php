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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\StatusEnum;
use App\Models\SinergiProgram as SinergiProgramModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Sinergi_program extends Admin_Controller
{
    public $modul_ini           = 'admin-web';
    public $sub_modul_ini       = 'sinergi-program';
    public $kategori_pengaturan = 'sinergi_program';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.sinergi_program.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(SinergiProgramModel::query())
                ->addColumn('drag-handle', static fn (): string => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->uuid . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("sinergi_program/form/{$row->uuid}") . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->status == StatusEnum::YA) {
                            $aksi .= '<a href="' . site_url("sinergi_program/lock/{$row->uuid}") . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("sinergi_program/lock/{$row->uuid}") . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url("sinergi_program/delete/{$row->uuid}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('gambar', static fn ($row): string => '<img src="' . $row->gambar_url . '" class="img-thumbnail" width="50" height="50"></a>')
                ->editColumn('status', static fn ($row): string => ($row->status == 1) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Tidak Aktif</span>')
                ->rawColumns(['drag-handle', 'ceklist', 'aksi', 'gambar', 'status'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = site_url("sinergi_program/update/{$id}");
            $data['utama']       = SinergiProgramModel::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = site_url('sinergi_program/insert');
            $data['utama']       = null;
        }

        return view('admin.sinergi_program.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (SinergiProgramModel::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = SinergiProgramModel::findOrFail($id);

        if ($data->update(static::validate($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (SinergiProgramModel::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function lock($id = 0): void
    {
        isCan('h');

        if (SinergiProgramModel::gantiStatus($id, 'status')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }

    public function tukar()
    {
        $sinergiProgram = $this->input->post('data');

        SinergiProgramModel::setNewOrder($sinergiProgram, 1, 'uuid');

        return json(['status' => 1]);
    }

    protected static function validate(array $request = [], $id = null): array
    {
        $urut = $id ? SinergiProgramModel::find($id)->urut : SinergiProgramModel::max('urut') + 1;

        $data = [
            'tautan' => $request['tautan'],
            'judul'  => htmlentities((string) $request['judul']),
            'status' => $request['status'] ?? 0,
            'urut'   => $urut,
        ];

        if (! empty($id) && empty($request['gambar'])) {
            unset($data['gambar']);
        } else {
            $data['gambar'] = static::unggah();
        }

        return $data;
    }

    protected static function unggah()
    {
        $CI = &get_instance();
        $CI->load->library('upload');
        $CI->upload->initialize([
            'upload_path'   => LOKASI_SINERGI_PROGRAM,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => 1024 * 2,
        ]);

        if ($CI->upload->do_upload('gambar')) {
            return $CI->upload->data('file_name');
        }

        redirect_with('error', $CI->upload->display_errors(null, null));
    }
}
