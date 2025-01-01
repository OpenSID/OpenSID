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

use App\Models\Cdesa as CdesaModel;
use App\Models\Pamong;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Cdesa extends Admin_Controller
{
    public $modul_ini     = 'pertanahan';
    public $sub_modul_ini = 'c-desa';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.pertanahan.cdesa.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $query = CdesaModel::with(['penduduk'])->selectRaw('cdesa.*')->jumlahPersil('jumlah');

            return datatables()->of($query)
                ->filter(static function ($query) {
                    $keyword = request('search')['value'] ?? null;
                    if ($keyword) {
                        $query->where('nomor', 'like', DB::raw("cast('{$keyword}' as unsigned)"))
                            ->orWhere('nama_kepemilikan', 'like', "%{$keyword}%")
                            ->orWhere('nama_pemilik_luar', 'like', "%{$keyword}%")
                            ->orWhereHas('penduduk', static fn ($q) => $q->where('nik', 'like', "%{$keyword}%")->orWhere('nama', 'like', "%{$keyword}%"));
                    }
                })->orderColumn('nomor', static function ($query, $order) {
                    $query->orderByRaw('cast(nomor as unsigned) ' . $order);
                })->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '<a href="' . ci_route('cdesa.rincian', $row->id) . '" class="btn bg-purple btn-sm"><i class="fa fa-bars"></i></a> ';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('cdesa.create_mutasi', ['id_cdesa' => $row->id]) . '" class="btn btn-success btn-sm"  title="Tambah Data"><i class="fa fa-plus"></i></a> ';
                        $aksi .= '<a href="' . ci_route('cdesa.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('cdesa.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })->addColumn('nama_pemilik', static fn ($row) => $row->nama_pemilik)
                ->addColumn('nik_pemilik', static fn ($row) => $row->nik_pemilik)
                ->addColumn('id_pemilik', static fn ($row) => $row->id_pemilik)
                ->editColumn('nomor', static fn ($row) => sprintf('%04s', $row->nomor))
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
            $form_action = ci_route('cdesa.update', $id);

            $cdesa = CdesaModel::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('cdesa.insert');
            $cdesa       = null;
        }

        return view('admin.pertanahan.cdesa.form', ['action' => $action, 'form_action' => $form_action, 'cdesa' => $cdesa]);
    }

    public function insert(): void
    {
        isCan('u');

        $req  = static::validate();
        $data = CdesaModel::create($req['data']);

        if ($data) {
            if ($req['data']['jenis_pemilik'] == 1) {
                $data->cdesaPenduduk()->create($req['penduduk']);
            }
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $req  = static::validate();
        $data = CdesaModel::with('penduduk')->findOrFail($id);

        $data->fill($req['data']);

        if ($req['data']['jenis_pemilik'] == 1) {
            $data->cdesaPenduduk ? $data->cdesaPenduduk->update($req['penduduk']) : $data->cdesaPenduduk()->create($req['penduduk']);
        } else {
            $data->cdesaPenduduk?->delete();
        }

        if ($data->save()) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = ''): void
    {
        isCan('h');

        if (CdesaModel::findOrFail($id)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll(): void
    {
        isCan('h');

        $id_cb = $this->input->post('id_cb');
        CdesaModel::whereIn('id', $id_cb)->delete();

        redirect_with('success', 'Berhasil Hapus Data');
    }

    protected function validate()
    {
        $data = $this->input->post();

        $penduduk = [];
        if ($data['jenis_pemilik'] == 1) {
            $penduduk['id_pend']  = $data['id_penduduk'];
            $penduduk['id_cdesa'] = $data['id_cdesa'];
        }
        unset($data['id_penduduk']);

        $cdesa['data']     = $data;
        $cdesa['penduduk'] = $penduduk;

        return $cdesa;
    }

    public function dialog($aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['form_action'] = ci_route("{$this->controller}.cetak.{$aksi}");
        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function cetak($aksi = '')
    {
        $data                   = $this->modal_penandatangan();
        $data['aksi']           = $aksi;
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ketahui')])->first()->toArray();
        $data['main']           = CdesaModel::listCdesa();

        $data['file'] = 'Daftar C-Desa ' . date('Y-m-d');

        $data['isi']       = 'admin.pertanahan.cdesa.cetak';
        $data['letak_ttd'] = ['1', '2', '12'];

        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=data_persil.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        view('admin.layouts.components.format_cetak', $data);
    }

    public function apipendudukdesa()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun),
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function detailPenduduk()
    {
        $id_penduduk = $this->input->get('id_penduduk');
        $individu    = Penduduk::with('wilayah')->findOrFail($id_penduduk);
        $html        = view('admin.pertanahan.cdesa.detail_penduduk', ['pemilik' => $individu], [], true);

        $sumber = [
            'html' => (string) $html,
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($sumber, JSON_THROW_ON_ERROR));
    }

    public function form_c_desa($id = 0)
    {
        // $data           = $this->modal_penandatangan();
        $data['cdesa']  = CdesaModel::findOrFail($id);
        $data['basah']  = CdesaModel::cetakMutasi($id, 'BASAH');
        $data['kering'] = CdesaModel::cetakMutasi($id, 'KERING');

        $data['aksi'] = 'cetak';

        $data['file'] = 'Form C-Desa ' . date('Y-m-d');

        $data['isi'] = 'admin.pertanahan.cdesa.cdesa_form_cetak';
        // $data['letak_ttd'] = ['1', '2', '12'];
        $data['letak_ttd'] = [];

        return view('admin.layouts.components.format_cetak', $data);
    }
}
