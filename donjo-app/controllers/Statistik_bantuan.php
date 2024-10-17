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

use App\Enums\JenisKelaminEnum;
use App\Enums\SasaranEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Enums\StatusDasarEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Kelompok;
use App\Models\KeluargaAktif;
use App\Models\PendudukHidup;
use App\Models\Rtm;
use App\Models\Wilayah;
use App\Services\LaporanPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');
class Statistik_bantuan extends Admin_Controller
{
    public $modul_ini     = 'statistik';
    public $sub_modul_ini = 'statistik-kependudukan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($id)
    {
        $data = $this->dataMenu($id);

        $view = in_array($id, array_keys(StatistikJenisBantuanEnum::allKeyLabel())) ? 'admin.statistik.bantuan.sasaran' : 'admin.statistik.bantuan.program';

        return view($view, $data);
    }

    private function dataMenu($id)
    {
        $sasaran      = ($id == 'bantuan_penduduk') ? SasaranEnum::PENDUDUK : SasaranEnum::KELUARGA;
        $tahunPertama = Bantuan::selectRaw('YEAR(sdate) as sdate')->when($sasaran, static fn ($q) => $q->whereSasaran($sasaran))->whereNotNull('sdate')->orderByRaw('YEAR(sdate)')->first()?->sdate;
        $tahunPertama ??= date('Y');
        $config    = $this->header['desa'];
        $heading   = LaporanPenduduk::judulStatistik($id);
        $idProgram = $this->generateIdProgram($id);
        $statistik = getStatistikLabel($idProgram, $heading, $config['nama_desa']);

        return [
            'lap'                   => $id,
            'heading'               => $heading,
            'allKategori'           => LaporanPenduduk::menuLabel(),
            'tahun_bantuan_pertama' => $tahunPertama,
            'judul_kelompok'        => 'Jenis Kelompok',
            'kategori'              => 'Program Bantuan',
            'wilayah'               => Wilayah::treeAccess(),
            'label'                 => $statistik['label'],
        ];
    }

    public function datatables($id)
    {
        [$filter, $filterGlobal] = $this->getFilters();
        $filterGlobal            = http_build_query($filterGlobal ?? []);
        $sasaran                 = SasaranEnum::PENDUDUK;
        $idProgram               = $this->generateIdProgram($id);
        if ($id == 'bantuan_keluarga') {
            $sasaran = SasaranEnum::KELUARGA;
        }
        if (! in_array($id, array_keys(StatistikJenisBantuanEnum::allKeyLabel()))) {
            $sasaran = Bantuan::find($id)?->sasaran;
        }

        switch($sasaran) {
            case SasaranEnum::PENDUDUK:
                $tautan_data = ci_route("penduduk.statistik.{$idProgram}");
                break;

            case SasaranEnum::KELUARGA:
                $tautan_data = ci_route("keluarga.statistik.{$idProgram}");
                break;

            case SasaranEnum::RUMAH_TANGGA:
                $tautan_data = ci_route("rtm.statistik.{$idProgram}");
                break;

            case SasaranEnum::KELOMPOK:
                $tautan_data = ci_route("kelompok.statistik.{$idProgram}");
                break;
        }

        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData($id, $sasaran, $filter))
                ->addIndexColumn()
                ->editColumn('nama', static fn ($row) => strtoupper($row['nama']))
                ->editColumn('jumlah', static fn ($row) => '<a href="' . $tautan_data . '/' . $row['id'] . '/0?' . $filterGlobal . '" target="_blank">' . $row['jumlah'] . '</a>')
                ->editColumn('laki', static fn ($row) => '<a href="' . $tautan_data . '/' . $row['id'] . '/1?' . $filterGlobal . '" target="_blank">' . $row['laki'] . '</a>')
                ->editColumn('perempuan', static fn ($row) => '<a href="' . $tautan_data . '/' . $row['id'] . '/2?' . $filterGlobal . '" target="_blank">' . $row['perempuan'] . '</a>')
                ->rawColumns(['jumlah', 'laki', 'perempuan', 'nama'])
                ->make();
        }

        return show_404();
    }

    private function getFilters()
    {
        $status    = $this->input->get('status') ?? null;
        $tahun     = $this->input->get('tahun') ?? null;
        $namaDusun = $this->input->get('dusun') ?? null;
        $rw        = $this->input->get('rw') ?? null;
        $rt        = $this->input->get('rt') ?? null;

        $idCluster = $rt ? [$rt] : [];

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($namaDusun)) {
            $idCluster = Wilayah::whereDusun($namaDusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        $filterGlobal = $filter = [
            'tahun'  => $tahun,
            'status' => $status,
            'dusun'  => $namaDusun,
            'rw'     => $namaRw,
            'rt'     => $rt,
        ];
        $filter['cluster']  = $idCluster;
        $filterGlobal['rt'] = $rt ? Wilayah::find($rt)->rt : null;

        return [$filter, $filterGlobal];
    }

    public function sumberData($lap, $sasaran, $filter = [])
    {
        $program = false;
        $bantuan = (new Bantuan())->whereSasaran($sasaran);
        $label   = 'PENERIMA';

        $cluster = $filter['cluster'];
        if ($filter['tahun']) {
            $bantuan->whereRaw("YEAR(sdate) <= {$filter['tahun']}")->whereRaw("YEAR(edate) >= {$filter['tahun']}");
        }
        if ($filter['status']) {
            $bantuan->whereStatus($filter['status']);
        }

        if (! in_array($lap, array_keys(unserialize(STAT_BANTUAN)))) {
            $bantuan->where('id', $lap);
            $program = true;
            $label   = 'PESERTA';
        }
        $data = $bantuan->withCount(['peserta as peserta_lakilaki_count' => static function ($query) use ($sasaran, $cluster) {
            $query->when($sasaran == SasaranEnum::PENDUDUK, static fn ($query) => $query->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->when($sasaran == SasaranEnum::KELUARGA, static fn ($query) => $query->whereHas('keluarga', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::RUMAH_TANGGA, static fn ($query) => $query->whereHas('rtm', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::KELOMPOK, static fn ($query) => $query->whereHas('kelompok', static fn ($q) => $q->whereHas('ketua', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))));
        }, 'peserta as peserta_perempuan_count' => static function ($query) use ($sasaran, $cluster) {
            $query->when($sasaran == SasaranEnum::PENDUDUK, static fn ($query) => $query->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->when($sasaran == SasaranEnum::KELUARGA, static fn ($query) => $query->whereHas('keluarga', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::RUMAH_TANGGA, static fn ($query) => $query->whereHas('rtm', static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))))->when($sasaran == SasaranEnum::KELOMPOK, static fn ($query) => $query->whereHas('kelompok', static fn ($q) => $q->whereHas('ketua', static fn ($t) => $t->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))));
        }])->get();

        $total  = $this->getTotal($sasaran);
        $result = $data->map(static fn ($item) => ['id' => $item->id, 'nama' => $item->nama, 'jumlah' => $item->peserta_lakilaki_count + $item->peserta_perempuan_count, 'persen' => persen2($item->peserta_lakilaki_count + $item->peserta_perempuan_count, $total['lk'] + $total['pr']), 'laki' => $item->peserta_lakilaki_count, 'persen1' => persen2($total['lk'], $item->peserta_lakilaki_count), 'perempuan' => $item->peserta_perempuan_count, 'persen2' => persen2($item->peserta_perempuan_count, $total['lk'] + $total['pr'])]);

        $resume = [
            ['id' => JUMLAH, 'nama' => $label, 'jumlah' => $result->sum('jumlah'), 'persen' => persen2($result->sum('jumlah'), $total['lk'] + $total['pr']), 'laki' => $result->sum('laki'), 'persen1' => persen2($result->sum('laki'), $total['lk'] + $total['pr']), 'perempuan' => $result->sum('perempuan'), 'persen2' => persen2($result->sum('perempuan'), $total['lk'] + $total['pr'])],
            ['id' => BELUM_MENGISI, 'nama' => 'BUKAN ' . $label, 'jumlah' => $total['lk'] + $total['pr'] - $result->sum('jumlah'), 'persen' => persen2($total['lk'] + $total['pr'] - $result->sum('jumlah'), $total['lk'] + $total['pr']), 'laki' => $total['lk'] - $result->sum('laki'), 'persen1' => persen2($total['lk'] - $result->sum('laki'), $total['lk']), 'perempuan' => $total['pr'] - $result->sum('perempuan'), 'persen2' => persen2($total['pr'] - $result->sum('perempuan'), $total['lk'] + $total['pr'])],
            ['id' => TOTAL, 'nama' => 'TOTAL', 'jumlah' => $total['lk'] + $total['pr'], 'persen' => persen2($total['lk'] + $total['pr'], $total['lk'] + $total['pr']), 'laki' => $total['lk'], 'persen1' => persen2($total['lk'], $total['lk'] + $total['pr']), 'perempuan' => $total['pr'], 'persen2' => persen2($total['pr'], $total['lk'] + $total['pr'])],
        ];

        if ($program) {
            $result = collect($resume);
        } else {
            // untuk total sasaran penerima bantuan, harus dihitung ulang karena satu pihak bisa menerima lebih dari satu bantuan
            $penerimaBantuanLaki              = 0;
            $penerimaBantuanPerempuan         = 0;
            $penerimaBantuanLakiNonAktif      = 0;
            $penerimaBantuanPerempuanNonAktif = 0;

            switch($sasaran) {
                case SasaranEnum::PENDUDUK:
                    $penerimaBantuanLaki              = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    $penerimaBantuanPerempuan         = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    $penerimaBantuanLakiNonAktif      = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    $penerimaBantuanPerempuanNonAktif = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('penduduk', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster)))->count();
                    break;

                case SasaranEnum::KELUARGA:
                    $penerimaBantuanLaki              = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    $penerimaBantuanPerempuan         = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    $penerimaBantuanLakiNonAktif      = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    $penerimaBantuanPerempuanNonAktif = BantuanPeserta::distinct('peserta')->whereIn('program_id', $result->pluck('id'))->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN])->where('status_dasar', '!=', StatusDasarEnum::HIDUP)->when($cluster, static fn ($r) => $r->whereIn('id_cluster', $cluster))))->count();
                    break;
            }

            $resume[0]['jumlah']    = $penerimaBantuanLaki + $penerimaBantuanPerempuan;
            $resume[0]['laki']      = $penerimaBantuanLaki;
            $resume[0]['perempuan'] = $penerimaBantuanPerempuan;
            $resume[0]['persen']    = persen2($penerimaBantuanLaki + $penerimaBantuanPerempuan, $total['lk'] + $total['pr']);
            $resume[0]['persen1']   = persen2($penerimaBantuanLaki, $total['lk'] + $total['pr']);
            $resume[0]['persen2']   = persen2($penerimaBantuanPerempuan, $total['lk'] + $total['pr']);

            $resume[1]['jumlah']    = $total['lk'] + $total['pr'] - $resume[0]['jumlah'] + $penerimaBantuanLakiNonAktif + $penerimaBantuanPerempuanNonAktif;
            $resume[1]['laki']      = $total['lk'] - $resume[0]['laki'] + $penerimaBantuanLakiNonAktif;
            $resume[1]['perempuan'] = $total['pr'] - $resume[0]['perempuan'] + $penerimaBantuanPerempuanNonAktif;
            $resume[1]['persen']    = persen2($resume[1]['jumlah'], $total['lk'] + $total['pr']);
            $resume[1]['persen1']   = persen2($resume[1]['laki'], $total['lk'] + $total['pr']);
            $resume[1]['persen2']   = persen2($resume[1]['perempuan'], $total['lk'] + $total['pr']);

            $result = $result ? collect(array_merge($result->toArray(), $resume)) : collect($resume);
        }

        return $result;
    }

    public function peserta_datatables($id)
    {
        if ($this->input->is_ajax_request()) {
            [$filter, $filterGlobal] = $this->getFilters();
            $sasaran                 = SasaranEnum::PENDUDUK;
            $query                   = BantuanPeserta::join('program', 'program.id', '=', 'program_peserta.program_id')
                ->when($filter['tahun'], static fn ($q) => $q->whereRaw("YEAR(sdate) <= {$filter['tahun']}")->whereRaw("YEAR(edate) >= {$filter['tahun']}"))
                ->when($filter['status'], static fn ($q) => $q->whereStatus($filter['status']));
            $cluster = $filter['cluster'];

            switch($id) {
                case 'bantuan_penduduk':
                    $sasaran = SasaranEnum::PENDUDUK;
                    break;

                case 'bantuan_keluarga':
                    $sasaran = SasaranEnum::KELUARGA;
                    break;

                default:
                    $query->where('program.id', $id);
                    $sasaran = Bantuan::find($id)->sasaran;
            }
            $query->whereSasaran($sasaran);

            switch($sasaran) {
                case SasaranEnum::PENDUDUK:
                    $query->when($cluster, static fn ($r) => $r->whereHas('penduduk', static fn ($s) => $s->whereIn('id_cluster', $cluster)));
                    break;

                case SasaranEnum::KELUARGA:
                    $query->when($cluster, static fn ($r) => $r->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($r) => $r->whereIn('id_cluster', $cluster))));
                    break;

                case SasaranEnum::RUMAH_TANGGA:
                    $query->when($cluster, static fn ($r) => $r->whereHas('rtm', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($r) => $r->whereIn('id_cluster', $cluster))));
                    break;

                case SasaranEnum::KELOMPOK:
                    break;
            }

            return datatables()->of($query)
                ->addIndexColumn()
                ->make();
        }
    }

    private function getTotal($sasaran)
    {
        switch($sasaran) {
            case SasaranEnum::PENDUDUK:
                $pr = PendudukHidup::where(['sex' => JenisKelaminEnum::PEREMPUAN])->count();
                $lk = PendudukHidup::where(['sex' => JenisKelaminEnum::LAKI_LAKI])->count();
                break;

            case SasaranEnum::KELUARGA:
                $pr = KeluargaAktif::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN]))->count();
                $lk = KeluargaAktif::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI]))->count();
                break;

            case SasaranEnum::RUMAH_TANGGA:
                $pr = Rtm::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN]))->count();
                $lk = Rtm::whereHas('kepalaKeluarga', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI]))->count();
                break;

            case SasaranEnum::KELOMPOK:
                $pr = Kelompok::whereHas('ketua', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::PEREMPUAN]))->count();
                $lk = Kelompok::whereHas('ketua', static fn ($q) => $q->where(['sex' => JenisKelaminEnum::LAKI_LAKI]))->count();
                break;
        }

        return ['pr' => $pr, 'lk' => $lk];
    }

    public function dialog($lap, $tipe, $aksi = 'cetak')
    {
        $data         = $this->modal_penandatangan();
        $data['aksi'] = $aksi;

        $data['formAction'] = ci_route('statistik.bantuan.' . $lap . '.cetak.' . $tipe, $aksi);

        return view('admin.statistik.dialog', $data);
    }

    public function cetak($id, $tipe, $aksi = 'cetak')
    {
        $paramDatatable = json_decode($this->input->post('params'), 1);
        $_GET           = $paramDatatable;

        [$filter, $filterGlobal] = $this->getFilters();
        $sasaran                 = SasaranEnum::PENDUDUK;
        if ($id == 'bantuan_keluarga') {
            $sasaran = SasaranEnum::KELUARGA;
        }
        if (! in_array($id, array_keys(unserialize(STAT_BANTUAN)))) {
            $sasaran = Bantuan::find($id)?->sasaran;
        }

        $data = array_merge($filter, $this->modal_penandatangan());

        $query              = $this->sumberData($id, $sasaran, $filter);
        $data['laporan_no'] = $this->input->post('laporan_no');
        $data['main']       = $query;
        $data['stat']       = LaporanPenduduk::judulStatistik($id);
        $data['aksi']       = $aksi;
        $data['config']     = $this->header['desa'];
        $data['file']       = 'Statistik penduduk';
        $data['isi']        = 'admin.statistik.cetak';
        $data['letak_ttd']  = ['2', '2', '9'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    private function generateIdProgram($id)
    {
        $idProgram = $id;
        if (! in_array($id, array_keys(StatistikJenisBantuanEnum::allKeyLabel()))) {
            $idProgram = '50' . $id;
        }

        return $idProgram;
    }
}
