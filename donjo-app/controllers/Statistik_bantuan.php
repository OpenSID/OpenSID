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

use App\Enums\SasaranEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Libraries\Statistik;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
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
            return datatables()->of($this->sumberData($id, $filter))
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

    public function sumberData($lap, $filter = [])
    {
        return Statistik::bantuan($lap, $filter);
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

        $query              = $this->sumberData($id, $filter);
        $data['laporan_no'] = $this->input->post('laporan_no');
        $data['main']       = $query;
        $data['stat']       = LaporanPenduduk::judulStatistik($id);
        $data['aksi']       = $aksi;
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
