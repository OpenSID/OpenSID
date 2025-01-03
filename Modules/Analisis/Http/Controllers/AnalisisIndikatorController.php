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
use Modules\Analisis\Enums\TipePertanyaanEnum;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisKategori;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisIndikatorController extends AdminModulController
{
    public $moduleName    = 'Analisis';
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'analisis-indikator';
    private $selectedMenu = 'Data Indikator';
    protected $analisisMaster;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $master               = request()->segment(2);
        $this->analisisMaster = AnalisisMaster::findOrFail($master);
        view()->share([
            'selectedMenu'    => $this->selectedMenu,
            'analisis_master' => $this->analisisMaster,
        ]);
    }

    public function index($master)
    {
        return view('analisis::indikator.index', [
            'tipeKategori' => AnalisisKategori::where(['id_master' => $master])->pluck('kategori', 'id'),
        ]);
    }

    public function datatables($master)
    {
        if (request()->ajax()) {
            $canUpdate      = can('u');
            $canDelete      = can('h');
            $orderColumn    = request()->input('order.0.column');
            $orderDesc      = request()->input('order.0.dir');
            $analisisMaster = $this->analisisMaster;

            return datatables()->of(AnalisisIndikator::with(['kategori'])->whereIdMaster($master)
                ->when($orderColumn == 3, static fn ($q) => $q->orderByRaw("LPAD(nomor, 10, ' ') {$orderDesc}")))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete, $analisisMaster): string {
                    $aksi = '';
                    if ($analisisMaster->isLock()) {
                        return $aksi;
                    }
                    if ($canUpdate) {
                        if (in_array($row->id_tipe, [TipePertanyaanEnum::PILIHAN_TUNGGAL, TipePertanyaanEnum::PILIHAN_GANDA])) {
                            $aksi .= ' <a href="' . ci_route("analisis_indikator.{$analisisMaster->id}.parameter", $row->id) . '" class="btn bg-purple btn-sm"  title="Jawaban"><i class="fa fa-list"></i></a>';
                        }
                    }
                    $aksi .= ' <a href="' . ci_route("analisis_indikator.{$analisisMaster->id}.form", $row->id) . '" class="btn bg-orange btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>';
                    if ($analisisMaster->jenis != TipePertanyaanEnum::PILIHAN_TUNGGAL && $canDelete) {
                        $aksi .= ' <a href="#" data-href="' . ci_route("analisis_indikator.{$analisisMaster->id}.delete", $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }

                    return $aksi;
                })
                ->editColumn('act_analisis', static fn ($q) => StatusEnum::valueOf($q->act_analisis))
                ->editColumn('id_tipe', static fn ($q) => TipePertanyaanEnum::valueOf($q->id_tipe))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($master, $id = null)
    {
        isCan('u');
        $analisisMaster        = $this->analisisMaster;
        $data['list_kategori'] = AnalisisKategori::where(['id_master' => $master])->pluck('kategori', 'id');
        $data['data_tabel']    = AnalisisIndikator::hubungan($analisisMaster->subjek_tipe);
        if ($id) {
            $data['action']             = 'Ubah';
            $data['form_action']        = ci_route('analisis_indikator.' . $master . '.update', $id);
            $data['analisis_indikator'] = AnalisisIndikator::findOrFail($id);
            $data['ubah']               = (AnalisisParameter::where('id_indikator', $id)->exists() && in_array($data['analisis_indikator']['id_tipe'], [1, 2])) ? false : true;
        } else {
            $data['action']             = 'Tambah';
            $data['form_action']        = ci_route('analisis_indikator.' . $master . '.insert');
            $data['analisis_indikator'] = null;
            $data['ubah']               = true;
        }

        return view('analisis::indikator.form', $data);
    }

    public function insert($master): void
    {
        isCan('u');
        $analisisMaster = $this->analisisMaster;
        if ($analisisMaster->isSystem()) {
            redirect_with('error', 'Analisis sistem tidak boleh dirubah', ci_route('analisis_indikator.' . $master));
        }
        $dataInsert              = static::validate($this->request);
        $dataInsert['id_master'] = $master;
        if (AnalisisIndikator::create($dataInsert)) {
            redirect_with('success', 'Berhasil Tambah Data', ci_route('analisis_indikator.' . $master));
        }
        redirect_with('error', 'Gagal Tambah Data', ci_route('analisis_indikator.' . $master));
    }

    public function update($master, $id = null): void
    {
        isCan('u');
        $analisisMaster = $this->analisisMaster;
        $dataUpdate     = static::validate($this->request, $id);
        if ($analisisMaster->isSystem()) {
            // Hanya kolom yang boleh diubah untuk analisis sistem
            $dataUpdate = ['is_publik' => $dataUpdate['is_publik']];
        }
        $data = AnalisisIndikator::findOrFail($id);

        if ($data->update($dataUpdate)) {
            redirect_with('success', 'Berhasil Ubah Data', ci_route('analisis_indikator.' . $master));
        }

        redirect_with('error', 'Gagal Ubah Data', ci_route('analisis_indikator.' . $master));
    }

    public function delete($master, $id = null): void
    {
        isCan('h');
        $analisisMaster = $this->analisisMaster;
        if ($analisisMaster->isSystem()) {
            redirect_with('error', 'Analisis sistem tidak boleh dihapus', ci_route('analisis_indikator.' . $master));
        }
        $adaParameter = AnalisisIndikator::whereIn('id', $id ? [$id] : $this->request['id_cb'])->whereHas('parameter')->exists();
        if ($adaParameter) {
            redirect_with('error', 'Gagal hapus, masih ada parameter dalam indikator tersebut', ci_route('analisis_indikator.' . $master));
        }
        if (AnalisisIndikator::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data', ci_route('analisis_indikator.' . $master));
        }
        redirect_with('error', 'Gagal Hapus Data', ci_route('analisis_indikator.' . $master));
    }

    protected static function validate(array $request = []): array
    {
        $data = [
            'id_tipe'      => $request['id_tipe'],
            'referensi'    => $request['referensi'] ?? null,
            'nomor'        => nomor_surat_keputusan($request['nomor']),
            'pertanyaan'   => htmlentities($request['pertanyaan']),
            'id_kategori'  => $request['id_kategori'] ?? null,
            'bobot'        => bilangan($request['bobot']),
            'act_analisis' => $request['act_analisis'],
            'is_publik'    => $request['is_publik'],
        ];

        if ($data['id_tipe'] != 1) {
            $data['act_analisis'] = 2;
            $data['bobot']        = 0;
        }

        return $data;
    }
}
