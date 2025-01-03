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

use Modules\Analisis\Models\AnalisisKlasifikasi;
use Modules\Analisis\Models\AnalisisMaster;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisKlasifikasiController extends AdminModulController
{
    public $moduleName    = 'Analisis';
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'analisis-klasifikasi';
    private $selectedMenu = 'Data Klasifikasi';
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
        return view('analisis::klasifikasi.index');
    }

    public function datatables($master)
    {
        if (request()->ajax()) {
            $canUpdate = can('u');
            $canDelete = can('h');

            return datatables()->of(AnalisisKlasifikasi::whereIdMaster($master))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete, $master) {
                    $aksi = '';
                    if ($canUpdate) {
                        $aksi .= '<a href="' . ci_route("analisis_klasifikasi.{$master}.form", $row->id) . '" class="btn bg-orange btn-sm"  title="Ubah Data"  data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data"><i class="fa fa-edit"></i></a>';
                    }

                    if ($canDelete) {
                        $aksi .= ' <a href="#" data-href="' . ci_route("analisis_klasifikasi.{$master}.delete", $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
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
        if ($id) {
            $data['action']               = 'Ubah';
            $data['form_action']          = ci_route('analisis_klasifikasi.' . $master . '.update', $id);
            $data['analisis_klasifikasi'] = AnalisisKlasifikasi::findOrFail($id);
        } else {
            $data['action']               = 'Tambah';
            $data['form_action']          = ci_route('analisis_klasifikasi.' . $master . '.insert');
            $data['analisis_klasifikasi'] = null;
        }

        return view('analisis::klasifikasi.form', $data);
    }

    public function insert($master): void
    {
        isCan('u');
        $dataInsert              = static::validate($this->request);
        $dataInsert['id_master'] = $master;
        if (AnalisisKlasifikasi::create($dataInsert)) {
            redirect_with('success', 'Berhasil Tambah Data', ci_route('analisis_klasifikasi.' . $master));
        }
        redirect_with('error', 'Gagal Tambah Data', ci_route('analisis_klasifikasi.' . $master));
    }

    public function update($master, $id = null): void
    {
        isCan('u');
        $dataUpdate = static::validate($this->request, $id);
        $data       = AnalisisKlasifikasi::findOrFail($id);

        if ($data->update($dataUpdate)) {
            redirect_with('success', 'Berhasil Ubah Data', ci_route('analisis_klasifikasi.' . $master));
        }

        redirect_with('error', 'Gagal Ubah Data', ci_route('analisis_klasifikasi.' . $master));
    }

    public function delete($master, $id = null): void
    {
        isCan('h');

        if (AnalisisKlasifikasi::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data', ci_route('analisis_klasifikasi.' . $master));
        }

        redirect_with('error', 'Gagal Hapus Data', ci_route('analisis_klasifikasi.' . $master));
    }

    protected static function validate(array $request = []): array
    {
        return [
            'nama'   => nomor_surat_keputusan($request['nama']),
            'minval' => bilangan_titik($request['minval']),
            'maxval' => bilangan_titik($request['maxval']),
        ];
    }
}
