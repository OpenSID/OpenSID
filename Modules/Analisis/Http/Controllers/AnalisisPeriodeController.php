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

use Modules\Analisis\Enums\TahapPedataanEnum;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisPeriode;
use Modules\Analisis\Models\AnalisisRespon;
use Modules\Analisis\Models\AnalisisResponHasil;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisPeriodeController extends AdminModulController
{
    public $moduleName    = 'Analisis';
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'analisis-periode';
    private $selectedMenu = 'Data Periode';
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
        return view('analisis::periode.index');
    }

    public function datatables($master)
    {
        if (request()->ajax()) {
            $canUpdate = can('u');
            $canDelete = can('h');

            return datatables()->of(AnalisisPeriode::whereIdMaster($master))
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete, $master): string {
                    $aksi = '';
                    if ($canUpdate) {
                        $aksi .= '<a href="' . ci_route("analisis_periode.{$master}.form", $row->id) . '" class="btn bg-orange btn-sm"  title="Ubah Data"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->isLock()) {
                            $aksi .= '<a href="' . ci_route("analisis_periode.{$master}.lock", $row->id) . '" class="btn bg-navy btn-sm"  title="Nonaktikan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route("analisis_periode.{$master}.lock", $row->id) . '" class="btn bg-navy btn-sm"  title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if ($canDelete) {
                        $aksi .= ' <a href="#" data-href="' . ci_route("analisis_periode.{$master}.delete", $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($master, $id = null)
    {
        isCan('u');
        $data['tahapan'] = TahapPedataanEnum::all();
        if ($id) {
            $data['action']           = 'Ubah';
            $data['form_action']      = ci_route('analisis_periode.' . $master . '.update', $id);
            $data['analisis_periode'] = AnalisisPeriode::findOrFail($id);
        } else {
            $data['action']           = 'Tambah';
            $data['form_action']      = ci_route('analisis_periode.' . $master . '.insert');
            $data['analisis_periode'] = null;
        }

        return view('analisis::periode.form', $data);
    }

    public function insert($master): void
    {
        isCan('u');
        $dataInsert              = static::validate($this->request);
        $dataInsert['id_master'] = $master;
        if ($result = AnalisisPeriode::create($dataInsert)) {
            if ($this->request['duplikasi']) {
                $this->duplikasi($master, $result->id, $this->request);
            }

            redirect_with('success', 'Berhasil Tambah Data', ci_route('analisis_periode.' . $master));
        }

        redirect_with('error', 'Gagal Tambah Data', ci_route('analisis_periode.' . $master));
    }

    public function update($master, $id = null): void
    {
        isCan('u');
        $dataUpdate = static::validate($this->request, $id);
        $data       = AnalisisPeriode::findOrFail($id);

        if ($data->update($dataUpdate)) {
            redirect_with('success', 'Berhasil Ubah Data', ci_route('analisis_periode.' . $master));
        }

        redirect_with('error', 'Gagal Ubah Data', ci_route('analisis_periode.' . $master));
    }

    public function lock($master, $id = null): void
    {
        isCan('u');

        if (AnalisisPeriode::gantiStatus($id, 'aktif')) {
            redirect_with('success', 'Berhasil Ubah Data', ci_route('analisis_periode.' . $master));
        }

        redirect_with('error', 'Gagal Ubah Data', ci_route('analisis_periode.' . $master));
    }

    public function delete($master, $id = null): void
    {
        isCan('h');

        if (AnalisisPeriode::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data', ci_route('analisis_periode.' . $master));
        }
        redirect_with('error', 'Gagal Hapus Data', ci_route('analisis_periode.' . $master));
    }

    protected static function validate(array $request = []): array
    {
        return [
            'nama'              => htmlentities($request['nama']),
            'id_state'          => bilangan($request['id_state']),
            'aktif'             => bilangan($request['aktif']),
            'keterangan'        => htmlentities($request['keterangan']),
            'tahun_pelaksanaan' => bilangan($request['tahun_pelaksanaan']),
        ];
    }

    private function duplikasi($idMaster, $idPeriode, $request): void
    {
        // Jika status aktif, maka nonaktifkan semua periode yang aktif lainnya pada master yang sama
        if ($request['aktif'] == 1) {
            AnalisisPeriode::where('id_master', $idMaster)->where('id', '!=', $idPeriode)->update(['aktif' => 0]);
        }

        if ($request['duplikasi'] == 1) {
            $dpd = AnalisisPeriode::where('id_master', $idMaster)
                ->where('id', '!=', $idPeriode)
                ->orderBy('id', 'desc')
                ->first();
            $sblm = $dpd->id;
            $skrg = $idPeriode;

            $dataRespon = AnalisisRespon::where('id_periode', $sblm)
                ->get(['id_subjek', 'id_indikator', 'id_parameter']);

            if ($dataRespon->isNotEmpty()) {
                $dataRespon->each(static function ($item) use ($skrg) {
                    $item->id_periode = $skrg;
                });

                AnalisisRespon::insert($dataRespon->toArray());
                AnalisisResponHasil::preUpdate($idMaster, $skrg);
            }
        }
    }
}
