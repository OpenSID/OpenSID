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

use App\Enums\AnalisisRefSubjekEnum;
use App\Enums\StatusEnum;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Analisis\Enums\TipePertanyaanEnum;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisKategori;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisPeriode;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisStatistikJawabanController extends AdminModulController
{
    public $moduleName    = 'Analisis';
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'analisis-statistik-jawaban';
    private $selectedMenu = 'Statistik Jawaban';
    protected $periodeAktif;
    protected $analisisMaster;
    private $listCluster  = [];
    private $filterColumn = [];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $master               = request()->segment(2);
        $this->analisisMaster = AnalisisMaster::findOrFail($master);
        if ($master) {
            $this->periodeAktif = AnalisisPeriode::whereIdMaster($master)->where(['aktif' => StatusEnum::YA])->first();
            if (! $this->periodeAktif) {
                redirect_with('error', 'Tidak ada periode aktif. Untuk laporan ini harus ada periode aktif.', ci_route('analisis_periode', $master));
            }
        }
        view()->share([
            'selectedMenu'    => $this->selectedMenu,
            'analisis_master' => $this->analisisMaster,
        ]);
    }

    public function index($master)
    {

        $data['list_tipe']     = TipePertanyaanEnum::all();
        $data['list_kategori'] = AnalisisKategori::where(['id_master' => $master])->get();
        $data['wilayah']       = Wilayah::treeAccess();

        return view('analisis::statistik_jawaban.index', $data);
    }

    public function datatables($master)
    {
        if (request()->ajax()) {
            $sumberData  = $this->sumberData();
            $idCluster   = $this->getCluster();
            $sbj         = $this->getQuerySubject($idCluster);
            $per         = $this->periodeAktif->id;
            $listCluster = http_build_query($this->listCluster);

            return datatables()->of($sumberData)
                ->addIndexColumn()
                ->addColumn('par', static fn ($q) => DB::select("SELECT i.id,i.kode_jawaban,i.jawaban,(SELECT COUNT(r.id_subjek) FROM analisis_respon r {$sbj} WHERE r.id_parameter = i.id AND r.id_periode = {$per}) AS jml_p FROM analisis_parameter i WHERE i.id_indikator = {$q->id} ORDER BY i.kode_jawaban  AND i.config_id = " . identitas('id')))
                ->editColumn('bobot', static fn ($q) => '<a href="' . ci_route("analisis_statistik_jawaban.{$master}.grafik_parameter", $q->id) . '?' . $listCluster . '" > ' . $q->bobot . '</a>')
                ->addColumn('list_cluster', $listCluster)
                ->editColumn('act_analisis', static fn ($q) => StatusEnum::valueOf($q->act_analisis))
                ->editColumn('id_tipe', static fn ($q) => TipePertanyaanEnum::valueOf($q->id_tipe))
                ->rawColumns(['ceklist', 'bobot'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $idCluster      = $this->getCluster();
        $sbj            = $this->getQuerySubject($idCluster);
        $analisisMaster = $this->analisisMaster;

        return AnalisisIndikator::with(['kategori'])
            ->selectRaw('analisis_indikator.*')
            ->selectRaw("(SELECT COUNT(DISTINCT r.id_subjek) AS jml FROM analisis_respon r {$sbj} WHERE r.id_indikator = analisis_indikator.id AND r.id_periode = {$this->periodeAktif->id} AND id_parameter > 0) as bobot")
            ->where(['id_master' => $analisisMaster->id]);
    }

    private function getCluster()
    {
        $dusun = request()->get('dusun') ?? null;
        $rw    = request()->get('rw') ?? null;
        $rt    = request()->get('rt') ?? null;
        if ($rt) {
            [$namaDusun, $namaRw]       = explode('__', $rw);
            $this->listCluster['dusun'] = $namaDusun;
            $this->listCluster['rw']    = $namaRw;
            $this->listCluster['rt']    = $rt;
        }
        $idCluster = $rt ? [$rt] : [];

        if (empty($idCluster) && ! empty($rw)) {
            if (Str::contains($rw, '__') ) {
                [$namaDusun, $namaRw] = explode('__', $rw);
            } else {
                $namaDusun = $dusun;
                $namaRw    = $rw;
            }

            $idCluster                  = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
            $this->listCluster['dusun'] = $namaDusun;
            $this->listCluster['rw']    = $namaRw;
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster                  = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
            $this->listCluster['dusun'] = $dusun;
        }

        return $idCluster;
    }

    private function getQuerySubject($idCluster)
    {
        $sbj        = '';
        $clusterStr = $idCluster ? ' and a.id in (' . implode(',', $idCluster) . ')' : '';

        switch ($this->analisisMaster->subjek_tipe) {
            case AnalisisRefSubjekEnum::PENDUDUK: $sbj = 'JOIN tweb_penduduk p ON r.id_subjek = p.id JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case AnalisisRefSubjekEnum::KELUARGA: $sbj = 'JOIN tweb_keluarga v ON r.id_subjek = v.id JOIN tweb_penduduk p ON v.nik_kepala = p.id JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;

            case AnalisisRefSubjekEnum::RUMAH_TANGGA: $sbj = 'JOIN tweb_rtm v ON r.id_subjek = v.id JOIN tweb_penduduk p ON v.nik_kepala = p.id JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id ';
                break;

            case AnalisisRefSubjekEnum::KELOMPOK: $sbj = 'JOIN kelompok v ON r.id_subjek = v.id JOIN tweb_penduduk p ON v.id_ketua = p.id JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id  ';
                break;
        }
        $sbj .= $clusterStr;

        return $sbj;
    }

    public function grafikParameter($master, $id = '')
    {
        if (request()->get('dusun')) {
            $this->filterColumn['dusun'] = request()->get('dusun');
        }
        if (request()->get('rw')) {
            $this->filterColumn['rw'] = request()->get('rw');
        }
        if (request()->get('rt')) {
            $this->filterColumn['rt'] = request()->get('rt');
        }

        $idCluster                          = $this->getCluster();
        $sbj                                = $this->getQuerySubject($idCluster);
        $per                                = $this->periodeAktif->id;
        $data['form_action']                = ci_route("analisis_statistik_jawaban.{$master}.grafik_parameter", $id);
        $data['filterColumn']               = $this->filterColumn;
        $data['wilayah']                    = Wilayah::treeAccess();
        $data['analisis_statistik_jawaban'] = AnalisisIndikator::findOrFail($id);
        $data['analisis_master']            = $this->analisisMaster;
        $data['main']                       = AnalisisParameter::selectRaw('analisis_parameter.*')
            ->selectRaw("(SELECT COUNT(r.id_subjek) FROM analisis_respon r {$sbj} WHERE r.id_parameter = analisis_parameter.id AND r.id_periode = {$per}) as nilai")
            ->where('id_indikator', $id)->orderBy('kode_jawaban')->get()->toArray();

        return view('analisis::statistik_jawaban.parameter.grafik_table', $data);
    }

    public function subjekParameter($master, $id, $par)
    {

        if (request()->get('dusun')) {
            $this->filterColumn['dusun'] = request()->get('dusun');
        }
        if (request()->get('rw')) {
            $this->filterColumn['rw'] = request()->get('rw');
        }
        if (request()->get('rt')) {
            $this->filterColumn['rt'] = request()->get('rt');
        }

        $idCluster                             = $this->getCluster();
        $sbj                                   = $this->getQuerySubject($idCluster);
        $per                                   = $this->periodeAktif->id;
        $listCluster                           = http_build_query($this->listCluster);
        $sql                                   = "SELECT p.id AS id_pend,r.id_subjek,p.nama,p.nik,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = p.id AND config_id = " . identitas('id') . ") AS umur,p.sex,a.dusun,a.rw,a.rt FROM analisis_respon r {$sbj} WHERE r.id_parameter = {$par} AND r.id_periode = {$per}";
        $data['filterColumn']                  = $this->filterColumn;
        $data['wilayah']                       = Wilayah::treeAccess();
        $data['form_action']                   = ci_route("analisis_statistik_jawaban.{$master}.subjek_parameter.{$id}", $par);
        $data['analisis_statistik_pertanyaan'] = AnalisisIndikator::findOrFail($id);
        $data['analisis_statistik_jawaban']    = AnalisisParameter::findOrFail($par);
        $data['cetak_action']                  = ci_route("analisis_statistik_jawaban.{$master}.cetak_subjek.{$id}.{$par}.cetak") . '?' . $listCluster;
        $data['unduh_action']                  = ci_route("analisis_statistik_jawaban.{$master}.cetak_subjek.{$id}.{$par}.unduh") . '?' . $listCluster;
        $data['analisis_master']               = $this->analisisMaster;
        $data['main']                          = DB::select($sql);

        return view('analisis::statistik_jawaban.parameter.subjek_table', $data);
    }

    public function cetak($master)
    {
        $tipe = request('tipe', 'cetak');
        if ($tipe == 'unduh') {
            $tgl = date('d_m_Y');
            header('Content-type: application/octet-stream');
            header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_{$tgl}.xls");
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        $paramDatatable = json_decode((string) request('params'), 1);
        $_GET           = $paramDatatable;
        $idCluster      = $this->getCluster();
        $sbj            = $this->getQuerySubject($idCluster);
        $per            = $this->periodeAktif->id;
        $data['main']   = $this->sumberData()->get()->map(static function ($item) use ($per, $sbj) {
            $par         = DB::select("SELECT i.id,i.kode_jawaban,i.jawaban,(SELECT COUNT(r.id_subjek) FROM analisis_respon r {$sbj} WHERE r.id_parameter = i.id AND r.id_periode = {$per}) AS jml_p FROM analisis_parameter i WHERE i.id_indikator = {$item->id} ORDER BY i.kode_jawaban  AND i.config_id = " . identitas('id'));
            $item['par'] = $par;

            return $item;
        })->toArray();

        return view('analisis::statistik_jawaban.table_print', $data);
    }

    public function cetakSubjek($master, $id, $par, $tipe = 'cetak')
    {
        if ($tipe == 'unduh') {
            $tgl = date('d_m_Y');
            header('Content-type: application/octet-stream');
            header("Content-Disposition: attachment; filename=subjek_analisis_{$tgl}.xls");
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        $idCluster                             = $this->getCluster();
        $sbj                                   = $this->getQuerySubject($idCluster);
        $per                                   = $this->periodeAktif->id;
        $data['analisis_statistik_pertanyaan'] = AnalisisIndikator::findOrFail($id);
        $data['analisis_statistik_jawaban']    = AnalisisParameter::findOrFail($par);
        $sql                                   = "SELECT p.id AS id_pend,r.id_subjek,p.nama,p.nik,(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = p.id AND config_id = " . identitas('id') . ") AS umur,p.sex,a.dusun,a.rw,a.rt FROM analisis_respon r {$sbj} WHERE r.id_parameter = {$par} AND r.id_periode = {$per}";
        $data['main']                          = DB::select($sql);

        return view('analisis::statistik_jawaban.parameter.subjek_print', $data);
    }
}
