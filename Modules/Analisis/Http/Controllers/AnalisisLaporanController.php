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
use App\Enums\JenisKelaminEnum;
use App\Enums\StatusEnum;
use App\Models\Pamong;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Modules\Analisis\Libraries\Analisis;
use Modules\Analisis\Models\AnalisisKlasifikasi;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisPeriode;
use Modules\Analisis\Models\AnalisisResponHasil;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisLaporanController extends AdminModulController
{
    public $moduleName    = 'Analisis';
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'analisis-laporan';
    private $selectedMenu = 'Laporan Analisis';
    protected $periodeAktif;
    protected $analisisMaster;

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
        $data = [
            'judul'            => Analisis::judulSubjek($this->analisisMaster->subjek_tipe),
            'list_klasifikasi' => AnalisisKlasifikasi::where('id_master', $master)->get(),
            'analisis_periode' => $this->periodeAktif->id,
            'wilayah'          => Wilayah::treeAccess(),
            'namaPeriode'      => $this->periodeAktif->nama,
        ];

        return view('analisis::laporan.index', $data);
    }

    public function datatables($master)
    {
        if (request()->ajax()) {
            $sumberData = $this->sumberData();

            return datatables()->of($sumberData)
                ->addIndexColumn()
                ->addColumn('aksi', static fn ($row): string => '<a href="' . ci_route("analisis_laporan.{$master}.form", $row->id) . '" class="btn bg-purple btn-sm" title="Input Data"><i class="fa fa-check-square-o"></i></a>')->editColumn('alamat', static fn ($q) => strtoupper($q->alamat . ' ' . 'RT/RW ' . $q->rt . '/' . $q->rw . ' - ' . setting('sebutan_dusun') . ' ' . $q->dusun))
                ->editColumn('nilai', static fn ($q) => $q->nilai ? number_format($q->nilai, 2, ',', '.') : '-')
                ->editColumn('sex', static fn ($q) => strtoupper(JenisKelaminEnum::valueOf($q->sex)))
                ->editColumn('cek', static fn ($q) => '<img src="' . base_url('assets/images/icon/') . ($q->cek ? 'ok' : 'nok') . '.png">')
                ->rawColumns(['ceklist', 'aksi', 'cek'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $dusun       = $this->input->get('dusun') ?? null;
        $rw          = $this->input->get('rw') ?? null;
        $rt          = $this->input->get('rt') ?? null;
        $klasifikasi = $this->input->get('klasifikasi') ?? null;

        $idCluster = $rt ? [$rt] : [];

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        $analisisMaster   = $this->analisisMaster;
        $analisSumberData = Analisis::sumberData($analisisMaster->subjek_tipe, $idCluster);
        $utama            = $analisSumberData['utama'];
        $sumber           = $analisSumberData['sumber'];
        $pembagi          = (int) $analisisMaster->pembagi;

        $sumber->selectRaw("CAST((analisis_respon_hasil.akumulasi/{$pembagi}) AS decimal(8,3)) AS nilai, analisis_klasifikasi.nama AS klasifikasi")
            ->leftJoin('analisis_respon_hasil', $utama . '.id', '=', 'analisis_respon_hasil.id_subjek')
            ->leftJoin('analisis_klasifikasi', static function ($join) use ($pembagi, $analisisMaster) {
                $join->on(DB::raw("analisis_respon_hasil.akumulasi / {$pembagi}"), '>=', 'analisis_klasifikasi.minval')
                    ->on(DB::raw("analisis_respon_hasil.akumulasi / {$pembagi}"), '<=', 'analisis_klasifikasi.maxval')
                    ->on('analisis_klasifikasi.id_master', '=', DB::raw($analisisMaster->id));
            })
            ->where('analisis_respon_hasil.id_periode', $this->periodeAktif->id);
        if ($klasifikasi) {
            $sumber->where('analisis_klasifikasi.id', $klasifikasi);
        }

        return $sumber;
    }

    public function form($master, $idSubjek)
    {
        $analisis = new Analisis();

        $data['total']        = AnalisisResponHasil::where(['id_subjek' => $idSubjek, 'id_periode' => $this->periodeAktif->id])->first()->akumulasi ?? 0;
        $data['subjek']       = $analisis->getSubjek($this->analisisMaster, $idSubjek) ?? show_404();
        $data['list_jawab']   = $analisis->listIndikatorLaporan($this->analisisMaster, $this->periodeAktif->id, $idSubjek);
        $data['list_bukti']   = $analisis->listBukti($this->analisisMaster, $this->periodeAktif->id, $idSubjek);
        $data['list_anggota'] = $analisis->listAnggota($this->analisisMaster, $idSubjek);
        $data['asubjek']      = $this->analisisMaster->subjek_tipe == AnalisisRefSubjekEnum::DESA ? ucwords(setting('sebutan_desa')) : AnalisisRefSubjekEnum::valueOf($this->analisisMaster->subjek_tipe);
        $data['id']           = $idSubjek;

        return view('analisis::laporan.form', $data);
    }

    // $aksi = cetak/unduh
    public function dialogKuisioner($master, $id, $aksi = '')
    {
        $data                = $this->modal_penandatangan(); // TODO:: Sesuaikan dulu di controller utama
        $data['aksi']        = ucwords((string) $aksi);
        $data['form_action'] = ci_route("analisis_laporan.{$master}.daftar.{$id}.{$aksi}");

        return view('analisis::admin.layouts.components.ttd_pamong', $data);
    }

    public function daftar($master, $idSubjek, $aksi = '')
    {
        $analisis             = new Analisis();
        $data['total']        = AnalisisResponHasil::where(['id_subjek' => $idSubjek, 'id_periode' => $this->periodeAktif->id])->first()->akumulasi ?? 0;
        $data['subjek']       = $analisis->getSubjek($this->analisisMaster, $idSubjek) ?? show_404();
        $data['list_jawab']   = $analisis->listIndikatorLaporan($this->analisisMaster, $this->periodeAktif->id, $idSubjek);
        $data['list_bukti']   = $analisis->listBukti($this->analisisMaster, $this->periodeAktif->id, $idSubjek);
        $data['list_anggota'] = $analisis->listAnggota($this->analisisMaster, $idSubjek);
        $data['asubjek']      = $this->analisisMaster->subjek_tipe == AnalisisRefSubjekEnum::DESA ? ucwords(setting('sebutan_desa')) : AnalisisRefSubjekEnum::valueOf($this->analisisMaster->subjek_tipe);

        $data['config']         = $this->header['desa'];
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => request('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => request('pamong_ketahui')])->first()->toArray();
        $data['aksi']           = $aksi;

        return view('analisis::laporan.form_cetak', $data);
    }

    // $aksi = cetak/unduh
    public function dialog($master, $aksi = '')
    {
        // Simpan session lama
        $data                = $this->modal_penandatangan();
        $data['aksi']        = ucwords((string) $aksi);
        $data['form_action'] = ci_route("analisis_laporan.{$master}.cetak.{$aksi}");

        return view('analisis::laporan.ttd_pamong', $data);
    }

    public function cetak($master, $aksi = '')
    {
        $paramDatatable = json_decode((string) request('params'), 1);
        $_GET           = $paramDatatable;

        $query = $this->sumberData();

        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => request('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => request('pamong_ketahui')])->first()->toArray();
        $data['aksi']           = $aksi;
        $data['config']         = $this->header['desa'];
        // $data['judul']           = Analisis::judulSubjek($this->analisisMaster->subjek_tipe);
        $data['file']      = 'Laporan Hasil Analisis ' . AnalisisRefSubjekEnum::valueOf($this->analisisMaster->subjek_tipe);
        $data['isi']       = 'analisis_laporan.table_print';
        $data['main']      = $query->get();
        $data['letak_ttd'] = ['2', '2', '1'];

        return view('analisis::admin.layouts.components.format_cetak', $data);
    }

    public function ajaxMultiJawab($master)
    {
        $data['jawab']       = session('jawab') ?? '';
        $data['main']        = (new Analisis())->multiJawab($master);
        $data['form_action'] = ci_route("analisis_laporan.{$master}.multi_jawab_proses");

        return view('analisis::laporan.ajax_multi', $data);
    }

    public function multiJawabProses($master)
    {
        if (isset($_POST['id_cb'])) {
            unset($_SESSION['jawab'], $_SESSION['jmkf']);

            $id_cb = $_POST['id_cb'];
            $cb    = '';
            if (count($id_cb) > 0) {
                foreach ($id_cb as $id) {
                    $cb .= $id . ',';
                }
            }
            set_session('jawab', $cb . '7777777');
            $jawab = session('jawab');
            set_session('jmkf', AnalisisParameter::selectRaw('DISTINCT(id_indikator) AS id_jmkf')->whereRaw('id in (' . $jawab . ')')->count());
        }

        redirect(ci_route("analisis_laporan.{$master}"));
    }
}
