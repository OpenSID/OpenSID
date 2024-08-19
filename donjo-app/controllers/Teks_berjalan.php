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

use App\Enums\SistemEnum;
use App\Enums\StatusEnum;
use App\Models\Artikel;
use App\Models\TeksBerjalan;

defined('BASEPATH') || exit('No direct script access allowed');

class Teks_berjalan extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'teks-berjalan';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('admin.web.teks_berjalan.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(TeksBerjalan::with('artikel'))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('teks_berjalan.urut') . '/' . $row->id . '/1' . '" class="btn bg-olive btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a> ';
                        $aksi .= '<a href="' . ci_route('teks_berjalan.urut') . '/' . $row->id . '/2' . '" class="btn bg-olive btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a> ';
                        $aksi .= '<a href="' . ci_route('teks_berjalan.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->status == StatusEnum::YA) {
                            $aksi .= '<a href="' . ci_route('teks_berjalan.lock') . '/' . $row->id . '/' . StatusEnum::TIDAK . '" class="btn bg-navy btn-sm" title="Aktifkan Anjungan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('teks_berjalan.lock') . '/' . $row->id . '/' . StatusEnum::YA . '" class="btn bg-navy btn-sm" title="Nonaktifkan Anjungan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('teks_berjalan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('teks', static function ($row): string {
                    $text = $row->teks;

                    return $text . (' <a href="' . menu_slug('artikel/' . $row->tautan) . '" target="_blank">' . $row->judul_tautan . '</a><br>');
                })
                ->editColumn('tampilkan', static fn($row) => SistemEnum::valueOf($row->tipe))
                ->addColumn('judul_tautan', static function ($row) {
                    if ($row->tautan) {
                        return '<a href="' . $row->tautan . '" target="_blank">' . tgl_indo($row->artikel->tgl_upload) . ' <br> ' . $row->artikel->judul . '</a>';
                    }
                })
                ->rawColumns(['ceklist', 'aksi', 'teks', 'judul_tautan'])
                ->orderColumn('teks', static function ($query, $order): void {
                    $query->orderBy('teks', $order);
                })
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');
        $data['list_artikel'] = Artikel::where('id_kategori', 999)->limit(500)->orderBy('id', 'DESC')->get();
        if ($id) {
            $data['teks']        = TeksBerjalan::findOrFail($id);
            $data['form_action'] = ci_route('teks_berjalan.update', $id);
        } else {
            $data['teks']        = null;
            $data['form_action'] = ci_route('teks_berjalan.insert');
        }

        $data['daftar_tampil'] = SistemEnum::all();

        return view('admin.web.teks_berjalan.form', $data);
    }

    public function insert(): void
    {
        $this->redirect_hak_akses('u');

        if (TeksBerjalan::create($this->validated($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        $this->redirect_hak_akses('u');
        if (TeksBerjalan::findOrFail($id)->update($this->validated($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        $this->redirect_hak_akses('h');

        if (TeksBerjalan::destroy($this->request['id_cb'] ?? $id) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function urut($id = 0, $arah = 0): void
    {
        $this->redirect_hak_akses('u');
        TeksBerjalan::nomorUrut($id, $arah);
        redirect('teks_berjalan');
    }

    public function lock($id = 0, $val = 1): void
    {
        $this->redirect_hak_akses('u');
        if (TeksBerjalan::findOrFail($id)->update(['status' => $val])) {
            redirect_with('success', 'Berhasil Ubah Status');
        }
        redirect_with('error', 'Gagal Ubah Status');
    }

    protected function validated($request = [], $id = null)
    {
        $data = [
            'teks'         => htmlentities($request['teks']),
            'tautan'       => (int) $request['tautan'],
            'judul_tautan' => htmlentities($request['judul_tautan']),
            'tipe'         => 1, // web
            'status'       => (int) $request['status'],
        ];

        if ($id === null) {
            $data['urut'] = TeksBerjalan::UrutMax();
        }

        return $data;
    }
}
