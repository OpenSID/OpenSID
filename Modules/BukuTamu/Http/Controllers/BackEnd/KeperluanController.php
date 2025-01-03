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

defined('BASEPATH') || exit('No direct script access allowed');

require_once FCPATH . 'Modules/BukuTamu/Http/Controllers/BackEnd/AnjunganBaseController.php';

use App\Enums\StatusEnum;
use Modules\BukuTamu\Models\KeperluanModel;

class KeperluanController extends AnjunganBaseController
{
    public $moduleName          = 'BukuTamu';
    public $modul_ini           = 'buku-tamu';
    public $sub_modul_ini       = 'data-keperluan';
    public $kategori_pengaturan = 'buku-tamu';
    public $aliasController     = 'buku_keperluan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(KeperluanModel::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('buku_keperluan.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('buku_keperluan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('tampil', static fn ($row): string => '<span class="label label-' . ($row->status ? 'success' : 'danger') . '">' . StatusEnum::valueOf($row->status) . '</span>')
                ->rawColumns(['ceklist', 'aksi', 'tampil'])
                ->make();
        }

        return view('bukutamu::backend.keperluan.index');
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['action']         = 'Ubah';
            $data['form_action']    = ci_route('buku_keperluan.update', $id);
            $data['data_keperluan'] = KeperluanModel::findOrFail($id);
        } else {
            $data['action']         = 'Tambah';
            $data['form_action']    = ci_route('buku_keperluan.insert');
            $data['data_keperluan'] = null;
        }

        return view('bukutamu::backend.keperluan.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (KeperluanModel::create($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = KeperluanModel::findOrFail($id);

        if ($data->update($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (KeperluanModel::destroy($this->request['id_cb'] ?? $id) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    private function validate(array $request = []): array
    {
        return [
            'keperluan' => htmlentities((string) $request['keperluan']),
            'status'    => htmlentities((string) $request['status']),
        ];
    }
}
