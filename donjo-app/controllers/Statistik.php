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

use App\Enums\Statistik\StatistikPendudukEnum;
use App\Models\Bantuan;
use App\Models\Wilayah;
use App\Services\LaporanPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Statistik extends Admin_Controller
{
    public $modul_ini     = 'statistik';
    public $sub_modul_ini = 'statistik-kependudukan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($lap = null)
    {
        if ($lap === null) {
            redirect('statistik/penduduk/' . StatistikPendudukEnum::PENDIDIKAN_KK['key']);
        }

        $data['lap']            = $lap;
        $data['heading']        = LaporanPenduduk::judulStatistik($data['lap']);
        $data['judul_kelompok'] = 'Jenis Kelompok';
        $data['bantuan']        = false;
        $data['wilayah']        = Wilayah::treeAccess();
        $data['allKategori']    = LaporanPenduduk::menuLabel();
        $this->get_data_stat($data, $data['lap']);

        return view('admin.statistik.index', $data);
    }

    public function datatables($lap)
    {
        $tautan_data = $this->tautan_data($lap);

        $dusun = $this->input->get('dusun') ?? null;
        $rw    = $this->input->get('rw') ?? null;
        $rt    = $this->input->get('rt') ?? null;

        if ($rt) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            // $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->whereRt($rt)->select(['id'])->get()->pluck('id')->toArray();
            $idCluster = [$rt];
            $rt        = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->whereId($rt)->select(['rt'])->get()->pluck('rt')->first();
        }

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        $filter = [
            'tahun'     => $this->input->get('tahun'),
            'status'    => $this->input->get('status'),
            'dusun'     => $namaDusun,
            'rw'        => $namaRw,
            'rt'        => $rt,
            'idCluster' => $idCluster,
        ];

        $filterGlobal = [
            'tahun'  => $this->input->get('tahun'),
            'status' => $this->input->get('status'),
            'dusun'  => $dusun,
            'rw'     => $namaRw,
            'rt'     => $rt,
        ];

        $filterGlobal = http_build_query($filterGlobal);

        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData($lap, $filter))
                ->editColumn('nama', static fn ($row) => strtoupper($row['nama']))
                ->editColumn('jumlah', static fn ($row) => '<a href="' . $tautan_data . $row['id'] . '/0?' . $filterGlobal . '" target="_blank">' . $row['jumlah'] . '</a>')
                ->editColumn('laki', static fn ($row) => '<a href="' . $tautan_data . $row['id'] . '/1?' . $filterGlobal . '" target="_blank">' . $row['laki'] . '</a>')
                ->editColumn('perempuan', static fn ($row) => '<a href="' . $tautan_data . $row['id'] . '/2?' . $filterGlobal . '" target="_blank">' . $row['perempuan'] . '</a>')
                ->rawColumns(['jumlah', 'laki', 'perempuan', 'nama'])
                ->make();
        }

        return show_404();
    }

    public function sumberData($lap, $filter = [], $paramCetak = [])
    {
        return (new LaporanPenduduk())->listData($lap, $filter, $paramCetak);
    }

    private function tautan_data(?string $lap = '0')
    {
        $sasaran = null;

        if ((int) $lap > 50) {
            $program_id = preg_replace('/^50/', '', $lap);
            $sasaran    = Bantuan::find($program_id)?->sasaran;
        }

        return match (true) {
            in_array($lap, [21, 22, 23, 24, 25, 26, 27, 'kelas_sosial', 'bantuan_keluarga']) || ((int) $lap > 50 && (int) $sasaran == 2) => site_url("keluarga/statistik/{$lap}/"),
            $lap == 'bdt'                                                                    || ((int) $lap > 50 && (int) $sasaran == 3) => site_url("rtm/statistik/{$lap}/"),
            $lap == 'akta-kematian'                                                                                                      => site_url("penduduk_log/statistik/{$lap}/"),
            (int) $lap < 50 || $lap == 'kia' || ((int) $lap > 50 && (int) $sasaran == 1)                                                 => site_url("penduduk/statistik/{$lap}/"),
            (int) $lap > 50 && (int) $sasaran == 4                                                                                       => site_url("kelompok/statistik/{$lap}/"),
            default                                                                                                                      => null,
        };
    }

    public function get_data_stat(&$data, $lap): void
    {
        $config       = $this->header['desa'];
        $data['stat'] = LaporanPenduduk::judulStatistik($lap);

        $statistik = getStatistikLabel($lap, $data['stat'], $config['nama_desa']);

        $data['label']    = $statistik['label'];
        $data['kategori'] = $statistik['kategori'];
    }

    public function dialog($lap, $aksi = 'cetak')
    {
        $data         = $this->modal_penandatangan();
        $data['aksi'] = $aksi;
        $config       = $this->header['desa'];
        $statistik    = getStatistikLabel($lap, $data['stat'], $config['nama_desa']);

        $data['formAction'] = ci_route('statistik.' . strtolower($statistik['kategori']) . '.' . $lap . '.cetak', $aksi);

        return view('admin.statistik.dialog', $data);
    }

    public function cetak($lap, $aksi = '')
    {
        $paramDatatable = json_decode($this->input->post('params'), 1);

        $dusun = $paramDatatable['dusun'];
        $rw    = $paramDatatable['rw'];
        $rt    = $paramDatatable['rt'];

        if ($rt) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = [$rt];
            $rt                   = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->whereId($rt)->select(['rt'])->get()->pluck('rt')->first();
        }

        if (empty($idCluster) && ! empty($rw)) {
            [$namaDusun, $namaRw] = explode('__', $rw);
            $idCluster            = Wilayah::whereDusun($namaDusun)->whereRw($namaRw)->select(['id'])->get()->pluck('id')->toArray();
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        $filter = [
            'tahun'     => $paramDatatable['tahun'],
            'status'    => $paramDatatable['status'],
            'dusun'     => $dusun,
            'rw'        => $namaRw,
            'rt'        => $rt,
            'idCluster' => $idCluster,
        ];

        $data = array_merge($filter, $this->modal_penandatangan());

        $query              = $this->sumberData($lap, $filter);
        $data['laporan_no'] = $this->input->post('laporan_no');
        $data['main']       = $query;
        $data['stat']       = LaporanPenduduk::judulStatistik($lap);
        $data['aksi']       = $aksi;
        $data['config']     = $this->header['desa'];
        $data['file']       = 'Statistik penduduk';
        $data['isi']        = 'admin.statistik.cetak';
        $data['letak_ttd']  = ['2', '2', '9'];

        return view('admin.layouts.components.format_cetak', $data);
    }
}
