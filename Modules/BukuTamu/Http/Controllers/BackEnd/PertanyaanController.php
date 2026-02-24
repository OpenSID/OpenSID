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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

require_once FCPATH . 'Modules/BukuTamu/Http/Controllers/BackEnd/AnjunganBaseController.php';

use App\Enums\StatusEnum;
use Modules\BukuTamu\Http\Requests\PertanyaanRequest;
use Modules\BukuTamu\Models\PertanyaanModel;

class PertanyaanController extends AnjunganBaseController
{
    public $moduleName          = 'BukuTamu';
    public $modul_ini           = 'buku-tamu';
    public $sub_modul_ini       = 'data-pertanyaan';
    public $kategori_pengaturan = 'Buku Tamu';
    public $aliasController     = 'buku_pertanyaan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(PertanyaanModel::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('buku_pertanyaan.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('buku_pertanyaan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('tampil', static fn ($row): string => '<span class="label label-' . ($row->status ? 'success' : 'danger') . '">' . StatusEnum::valueOf($row->status) . '</span>')
                ->rawColumns(['ceklist', 'aksi', 'tampil'])
                ->make();
        }

        return view('bukutamu::backend.pertanyaan.index');
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['action']          = 'Ubah';
            $data['form_action']     = ci_route('buku_pertanyaan.update', $id);
            $data['data_pertanyaan'] = PertanyaanModel::findOrFail($id);
        } else {
            $data['action']          = 'Tambah';
            $data['form_action']     = ci_route('buku_pertanyaan.insert');
            $data['data_pertanyaan'] = null;
        }

        return view('bukutamu::backend.pertanyaan.form', $data);
    }

    public function insert()
    {
        isCan('u');

        $request = new PertanyaanRequest();

        if (PertanyaanModel::create($request->validated())) {
            return json([
                'status'       => true,
                'message'      => 'Berhasil Tambah Data',
                'redirect_url' => ci_route('buku_pertanyaan'),
            ]);
        }

        return json([
            'status'  => false,
            'message' => 'Gagal Tambah Data',
        ]);
    }

    public function update($id = null)
    {
        isCan('u');

        $request = new PertanyaanRequest();
        $data    = PertanyaanModel::findOrFail($id);

        if ($data->update($request->validated())) {
            return json([
                'status'       => true,
                'message'      => 'Berhasil Ubah Data',
                'redirect_url' => ci_route('buku_pertanyaan'),
            ]);
        }

        return json([
            'status'  => false,
            'message' => 'Gagal Ubah Data',
        ]);
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (PertanyaanModel::destroy($this->request['id_cb'] ?? $id) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }
}
