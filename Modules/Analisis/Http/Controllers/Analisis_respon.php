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

use App\Enums\StatusEnum;
use App\Models\Wilayah;
use App\Traits\Upload;
use Illuminate\Support\Facades\DB;
use Modules\Analisis\Libraries\Analisis;
use Modules\Analisis\Libraries\Bdt;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisPeriode;
use Modules\Analisis\Models\AnalisisRespon;
use Modules\Analisis\Models\AnalisisResponBukti;

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_respon extends AdminModulController
{
    use Upload;

    public $moduleName    = 'Analisis';
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'analisis-respon';
    private $selectedMenu = 'Input Data';
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
                redirect_with('error', 'Tidak ada periode aktif. Entri data respon harus ada periode aktif.', ci_route('analisis_periode', $master));
            }
        }
        view()->share([
            'selectedMenu'    => $this->selectedMenu,
            'analisis_master' => $this->analisisMaster,
        ]);
    }

    public function index($master)
    {
        $data = array_merge([
            'wilayah'     => Wilayah::treeAccess(),
            'namaPeriode' => $this->periodeAktif->nama,
        ], Analisis::judul_subjek($this->analisisMaster->subjek_tipe));

        return view('analisis_respon.index', $data);
    }

    public function datatables($master)
    {
        if ($this->input->is_ajax_request()) {
            $sumberData = $this->sumberData();

            return datatables()->of($sumberData)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($master): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route("analisis_respon.{$master}.form", $row->id) . '" class="btn bg-purple btn-sm" title="Input Data"><i class="fa fa-check-square-o"></i></a>';
                    }
                    if ($row->bukti_pengesahan) {
                        $aksi .= ' <a href="' . base_url(LOKASI_PENGESAHAN . $row->bukti_pengesahan) . '" class="btn bg-olive btn-sm" title="Unduh Bukti Pengesahan" target="_blank"><i class="fa fa-paperclip"></i></a>';
                    }

                    return $aksi;
                })->editColumn('cek', static fn ($q) => '<img src="' . base_url('assets/images/icon/') . ($q->cek ? 'ok' : 'nok') . '.png">')
                ->rawColumns(['ceklist', 'aksi', 'cek'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $dusun = $this->input->get('dusun') ?? null;
        $rw    = $this->input->get('rw') ?? null;
        $rt    = $this->input->get('rt') ?? null;
        $isi   = $this->input->get('isi') ?? null;

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

        $sumber->selectRaw('(SELECT a.id_subjek FROM analisis_respon a WHERE a.id_subjek = ' . $utama . ".id AND a.id_periode = {$this->periodeAktif->id} LIMIT 1) as cek")
            ->selectRaw("(SELECT b.pengesahan FROM analisis_respon_bukti b WHERE b.id_master = {$analisisMaster->id} AND b.id_periode = {$this->periodeAktif->id} AND b.id_subjek = " . $utama . '.id limit 1) as bukti_pengesahan');

        if ($isi) {
            switch($isi) {
                case 1:
                    $sumber->whereRaw("(SELECT COUNT(id_subjek) FROM analisis_respon_hasil WHERE id_subjek = {$utama}.id AND id_periode = {$this->periodeAktif->id}) > 0");
                    break;

                case 2:
                    $sumber->whereRaw("(SELECT COUNT(id_subjek) FROM analisis_respon_hasil WHERE id_subjek = {$utama}.id AND id_periode = {$this->periodeAktif->id}) = 0");
                    break;
            }
        }

        return $sumber;
    }

    public function form($master, $idSubjek)
    {
        isCan('u');
        $analisis            = new Analisis();
        $data['fullscreen']  = $this->input->get('fs') ?? null;
        $data['form_action'] = ci_route('analisis_respon.' . $master . '.update', $idSubjek);
        $data['idSubjek']    = $idSubjek;

        $data['subjek']       = $analisis->getSubjek($this->analisisMaster, $idSubjek) ?? show_404();
        $data['list_jawab']   = $analisis->listIndikator($this->analisisMaster, $this->periodeAktif->id, $idSubjek);
        $data['list_bukti']   = $analisis->listBukti($this->analisisMaster, $this->periodeAktif->id, $idSubjek);
        $data['list_anggota'] = $analisis->listAnggota($this->analisisMaster, $idSubjek);
        $data['perbaharui']   = ci_route('analisis_respon.' . $master . '.perbaharui', $idSubjek);

        return view('analisis_respon.form', $data);
    }

    public function update($master, $idSubjek): void
    {
        isCan('u');
        DB::beginTransaction();

        try {
            if (! empty($_FILES['pengesahan']['name'])) {
                $per                     = $this->periodeAktif->id;
                $namaFile                = implode('_', [$master, $per, $idSubjek, random_int(10000, 99999)]) . '.jpg';
                $config['upload_path']   = LOKASI_PENGESAHAN;
                $config['allowed_types'] = 'jpg|jpeg';
                $config['max_size']      = 1024;
                $config['file_name']     = $namaFile;

                $namaFile            = $this->upload('pengesahan', $config, ci_route('analisis_respon.' . $master . '.form', $idSubjek));
                $bukti['pengesahan'] = $namaFile;
                $bukti['id_master']  = $master;
                $bukti['id_subjek']  = $idSubjek;
                $bukti['id_periode'] = $per;
                $bukti               = AnalisisResponBukti::firstOrCreate($bukti);
                $bukti->pengesahan   = $namaFile;
                $bukti->save();
            }
            AnalisisRespon::updateKuisioner($master, $this->periodeAktif->id, $_POST, $idSubjek);
            DB::commit();
            redirect_with('success', 'Berhasil Simpan Data Kuisioner', ci_route('analisis_respon.' . $master . '.form', $idSubjek));
        } catch (Exception $e) {
            DB::rollBack();
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal Ubah Data Kuisioner ' . $e->getMessage(), ci_route('analisis_respon.' . $master . '.form', $idSubjek));
        }
    }

    public function perbaharui($master, $idSubjek): void
    {
        isCan('u');
        AnalisisRespon::where('id_subjek', $idSubjek)->whereIn('id_indikator', static fn ($q) => $q->select('id')->from('analisis_indikator')->where(['id_master' => $master]))->delete();
        redirect(ci_route('analisis_respon.' . $master . '.form', $idSubjek));
    }

    public function data_ajax(): void
    {
        $data['analisis_master'] = $this->analisisMaster;
        view('analisis_respon.import.data_ajax', $data);
    }

    /**
     * Unduh data analisis respon
     *
     * @param int   $tipe   | 1. Dengan isian data, 2. Dengan kode isian
     * @param mixed $master
     */
    public function data_unduh($master): void
    {
        $paramDatatable      = json_decode((string) $this->input->post('params'), 1);
        $_GET                = $paramDatatable;
        $tipe                = $this->input->post('tipe') ?? 1;
        $data['subjek_tipe'] = $this->analisisMaster->subjek_tipe;
        $data['main']        = $this->sumberData()->get()->map(function ($item) {

            $par = AnalisisRespon::selectRaw('kode_jawaban, asign, jawaban, analisis_respon.id_indikator, analisis_respon.id_parameter AS korek')
                ->from('analisis_respon')
                ->join('analisis_parameter', 'analisis_parameter.id', '=', 'analisis_respon.id_parameter')
                ->where('analisis_respon.id_periode', $this->periodeAktif->id)
                ->where('analisis_respon.id_subjek', $item->id)
                ->orderBy('analisis_respon.id_indikator')
                ->get()
                ->toArray();
            $item['par'] = $par;

            return $item;
        })->toArray();
        $data['periode']   = $this->periodeAktif->id;
        $data['indikator'] = AnalisisIndikator::indikatorUnduh($master);
        $data['tipe']      = $tipe;
        $key               = ($data['periode'] + 3) * ($this->analisisMaster->id + 7) * ($this->analisisMaster->subjek_tipe * 3);
        $data['key']       = 'AN' . $key;

        $data['span_kolom'] = match ($this->analisisMaster->subjek_tipe) {
            5, 6 => 3,
            7       => 5,
            8       => 6,
            default => 7,
        };
        $data['judul'] = Analisis::judul_subjek($this->analisisMaster->subjek_tipe);

        view('analisis_respon.import.data_unduh', $data);
    }

    public function import($master, $op = 0): void
    {
        isCan('u');
        $data['form_action'] = ci_route("analisis_respon.{$master}.import_proses", $op);

        view('analisis_respon.import.import', $data);
    }

    public function import_proses($master, $op = 0): void
    {
        isCan('u');
        $periode    = $this->periodeAktif->id;
        $subjekTipe = $this->analisisMaster->subjek_tipe;
        DB::beginTransaction();

        try {
            $result = (new AnalisisRespon())->import_respon($master, $periode, $subjekTipe, $op);
            DB::commit();
            redirect_with('success', 'Data berhasil diimport', ci_route('analisis_respon.' . $master));
        } catch (Exception $e) {
            DB::rollBack();
            redirect_with('error', 'Data gagal diimport ' . $result['pesan'] . ' ' . $e->getMessage(), ci_route('analisis_respon.' . $master));
        }
    }

    public function form_impor_bdt($master): void
    {
        isCan('u');
        $data['form_action']     = ci_route("analisis_respon.{$master}.impor_bdt");
        $data['analisis_master'] = $this->analisisMaster;
        $data['formatImpor']     = ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'contoh-data-bdt2015.xlsx'));

        view('analisis_respon.import.impor_bdt', $data);
    }

    public function impor_bdt($master): void
    {
        isCan('u');
        DB::beginTransaction();

        try {
            (new Bdt($master, $this->periodeAktif->id))->impor();
            DB::commit();
            redirect_with('success', 'Data berhasil diimport', ci_route('analisis_respon.' . $master));
        } catch (Exception $e) {
            DB::rollBack();
            redirect_with('error', 'Data gagal diimport ' . $e->getMessage(), ci_route('analisis_respon.' . $master));
        }
    }
}
