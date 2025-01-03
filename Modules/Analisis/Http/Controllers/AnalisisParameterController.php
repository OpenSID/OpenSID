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

use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisParameterController extends AdminModulController
{
    public $moduleName    = 'Analisis';
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'analisis-parameter';
    private $selectedMenu = 'Data Indikator';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($master, $indikator)
    {
        $data = [
            'analisis_master' => AnalisisMaster::findOrFail($master),
            'selectedMenu'    => $this->selectedMenu,
            'baseRoute'       => ci_route('analisis_indikator.' . $master . '.parameter.' . $indikator),
        ];

        return view('analisis::parameter.index', $data);
    }

    public function datatables($master, $indikator)
    {
        if (request()->ajax()) {
            $canUpdate         = can('u');
            $analisisMaster    = AnalisisMaster::find($master);
            $analisisIndikator = AnalisisIndikator::findOrFail($indikator);

            return datatables()->of(AnalisisParameter::whereIdIndikator($indikator))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $analisisMaster, $analisisIndikator): string {
                    $aksi = '';
                    if ($canUpdate) {
                        $aksi .= '<a href="' . ci_route("analisis_indikator.{$analisisMaster->id}.parameter.{$row->id_indikator}.form", $row->id) . '" class="btn bg-orange btn-sm" title="Ubah Data"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data Parameter"><i class="fa fa-edit"></i></a>';
                    }

                    if ($analisisMaster->jenis != 1 && ! $analisisIndikator->referensi) {
                        $aksi .= ' <a href="#" data-href="' . ci_route("analisis_indikator.{$analisisMaster->id}.parameter.{$row->id_indikator}.delete", $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($master, $indikator, $id = null)
    {
        isCan('u');
        $analisisMaster             = AnalisisMaster::find($master);
        $data['selectedMenu']       = $this->selectedMenu;
        $data['analisis_master']    = $analisisMaster;
        $data['analisis_indikator'] = AnalisisIndikator::findOrFail($indikator);
        $data['analisis_parameter'] = null;
        if ($id) {
            $data['action']             = 'Ubah';
            $data['form_action']        = ci_route('analisis_indikator.' . $master . '.parameter.' . $indikator . '.update', $id);
            $data['analisis_parameter'] = AnalisisParameter::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('analisis_indikator.' . $master . '.parameter.' . $indikator . '.insert');
        }

        return view('analisis::parameter.form', $data);
    }

    public function insert($master, $indikator): void
    {
        isCan('u');
        $analisisMaster = AnalisisMaster::findOrFail($master);
        if ($analisisMaster->isSystem()) {
            redirect_with('error', 'Analisis sistem tidak boleh dirubah', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
        }
        $dataInsert                 = static::validate($this->request);
        $dataInsert['id_indikator'] = $indikator;
        if (AnalisisParameter::create($dataInsert)) {
            redirect_with('success', 'Berhasil Tambah Data', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
        }

        redirect_with('error', 'Gagal Tambah Data', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
    }

    public function update($master, $indikator, $id = null): void
    {
        isCan('u');
        $analisisMaster = AnalisisMaster::findOrFail($master);
        $dataUpdate     = static::validate($this->request, $id);
        if ($analisisMaster->isSystem() || $this->request->referensi) {
            unset($dataUpdate['kode_jawaban'], $dataUpdate['jawaban']);
        }
        $data = AnalisisParameter::findOrFail($id);

        if ($data->update($dataUpdate)) {
            redirect_with('success', 'Berhasil Ubah Data', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
        }

        redirect_with('error', 'Gagal Ubah Data', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
    }

    public function delete($master, $indikator, $id = null): void
    {
        isCan('h');
        $analisisMaster = AnalisisMaster::findOrFail($master);
        if ($analisisMaster->isSystem()) {
            redirect_with('error', 'Analisis sistem tidak boleh dihapus', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
        }
        if (AnalisisParameter::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
        }

        redirect_with('error', 'Gagal Hapus Data', ci_route('analisis_indikator.' . $master . '.parameter', $indikator));
    }

    protected static function validate(array $request = []): array
    {
        return [
            'kode_jawaban' => bilangan($request['kode_jawaban']),
            'jawaban'      => htmlentities($request['jawaban']),
            'nilai'        => bilangan($request['nilai']),
        ];
    }
}
