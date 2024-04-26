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

use App\Models\Pemilihan as PemilihanModel;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Pemilihan extends Admin_Controller
{
    public $modul_ini       = 'kependudukan';
    public $sub_modul_ini   = 'calon-pemilih';
    public $aliasController = 'dpt';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        isCan('u');
        if (! Schema::hasTable('pemilihan')) {
            session_error('Tabel Pemilihan tidak ditemukan, silahkan lakukan migrasi database terlebih dahulu.');
            redirect('dpt');
        }
    }

    public function index()
    {
        return view('admin.pemilihan.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(PemilihanModel::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->uuid . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('pemilihan.form', $row->uuid) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('u')) {
                        if ($row->status) {
                            $aksi .= '<a href="' . site_url("pemilihan/status/{$row->uuid}") . '" class="btn bg-olive btn-sm" title="Non Aktifkan"><i class="fa fa-star"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("pemilihan/status/{$row->uuid}") . '" class="btn bg-purple btn-sm" title="Aktifkan"><i class="fa fa-star-o"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('pemilihan.delete', $row->uuid) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('tanggal', static fn ($row) => tgl_indo2($row->tanggal))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = ci_route('pemilihan.update', $id);
            $pemilihan   = PemilihanModel::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('pemilihan.insert');
            $pemilihan   = null;
        }

        return view('admin.pemilihan.form', ['action' => $action, 'form_action' => $form_action, 'pemilihan' => $pemilihan]);
    }

    public function insert(): void
    {
        isCan('u');

        if (PemilihanModel::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'pemilihan/');
        }
        redirect_with('error', 'Gagal Tambah Data', 'pemilihan/');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $data = PemilihanModel::findOrFail($id);

        if ($data->update(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'pemilihan/');
        }
        redirect_with('error', 'Gagal Ubah Data', 'pemilihan/');
    }

    public function status($id = null): void
    {
        isCan('u');

        if (PemilihanModel::gantiStatus($id)) {
            redirect_with('success', 'Berhasil Ubah Data', 'pemilihan/');
        }
        redirect_with('error', 'Gagal Ubah Data', 'pemilihan/');
    }

    public function delete($id): void
    {
        isCan('h');

        $data = PemilihanModel::findOrFail($id);

        if ($data->destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'pemilihan/');
        }

        redirect_with('error', 'Gagal Hapus Data', 'pemilihan/');
    }

    public function delete_all(): void
    {
        isCan('h');

        if (PemilihanModel::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data', 'pemilihan/');
        }

        redirect_with('error', 'Gagal Hapus Data', 'pemilihan/');
    }

    protected static function Validate($request = [])
    {
        return [
            'judul'      => nama_terbatas($request['judul']),
            'tanggal'    => date('Y-m-d', strtotime($request['tanggal'])),
            'keterangan' => $request['keterangan'],
            'status'     => $request['status'] ?? 0,
        ];
    }
}
