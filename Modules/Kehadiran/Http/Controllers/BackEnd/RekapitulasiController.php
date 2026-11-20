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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\StatusEnum;
use App\Models\Pamong;
use Illuminate\Support\Facades\DB;
use Modules\Kehadiran\Enums\JenisIzin;
use Modules\Kehadiran\Models\Kehadiran;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

defined('BASEPATH') || exit('No direct script access allowed');

class RekapitulasiController extends AdminModulController
{
    private const STATUS_HADIR      = ['hadir', 'lupa melapor keluar', 'tidak berada di kantor'];
    private const STATUS_DINAS_LUAR = ['dinas_luar_kota', 'dinas luar'];

    public $moduleName          = 'Kehadiran';
    public $modul_ini           = 'kehadiran';
    public $sub_modul_ini       = 'rekapitulasi';
    public $kategori_pengaturan = 'Kehadiran';
    public $aliasController     = 'kehadiran_rekapitulasi';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $pamong = Pamong::daftar()->where(static function ($q) {
            $q->has('penduduk')->orWhere('pamong_nama', '!=', '');
        })->get();
        $kehadiran = Kehadiran::select('status_kehadiran')->distinct()->get();

        return view('kehadiran::backend.rekapitulasi.index', ['pamong' => $pamong, 'kehadiran' => $kehadiran]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $filters = [
                'tanggal' => $this->input->post_get('daterange'),
                'status'  => $this->input->post_get('status'),
                'pamong'  => $this->input->post_get('pamong'),
            ];

            return datatables()->of(Kehadiran::with(['pamong', 'pamong.penduduk', 'pamong.jabatan'])
                ->whereHas('pamong', static function ($q) {
                    $q->has('penduduk')->orWhere('pamong_nama', '!=', '');
                })
                ->select('*', DB::raw('TIMEDIFF( jam_keluar, jam_masuk ) as total'))
                ->filter($filters))
                ->addIndexColumn()
                ->editColumn('tanggal', static fn ($row) => tgl_indo($row->tanggal))
                ->editColumn('jam_masuk', static fn ($row): string => date('H:i', strtotime($row->jam_masuk)))
                ->editColumn('jam_keluar', static fn ($row): string => $row->jam_keluar == null || $row->jam_keluar == '' ? '-' : date('H:i', strtotime($row->jam_keluar)))
                ->editColumn('total', static fn ($row): string => date('H:i', strtotime($row->total)))
                ->editColumn('jabatan', static fn ($row) => $row->pamong->status_pejabat == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') . ' ' . $row->pamong->jabatan->nama : $row->pamong->jabatan->nama)
                ->editColumn('status_kehadiran', fn ($row): string => '<span class="label label-' . $this->normalisasiStatusKehadiran($row)['tipe'] . '">' . ucwords($this->normalisasiStatusKehadiran($row)['label']) . '</span>')
                ->rawColumns(['status_kehadiran'])
                ->make();
        }

        return show_404();
    }

    public function ekspor()
    {
        $filters = [
            'tanggal' => request('daterange'),
            'status'  => request('status'),
            'pamong'  => request('pamong'),
        ];

        $judul = [
            'Nama',
            'Jabatan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Total Waktu',
            'Status Kehadiran',
        ];

        $writer = new Writer();
        $writer->openToBrowser(namafile('kehadiran') . '.xlsx');
        $writer->addRow(Row::fromValues($judul));

        $data_kehadiran = Kehadiran::with(['pamong'])
            ->whereHas('pamong', static function ($q) {
                $q->has('penduduk')->orWhere('pamong_nama', '!=', '');
            })
            ->select('*', DB::raw('TIMEDIFF( jam_keluar, jam_masuk ) as total'))
            ->filter($filters)
            ->get();

        foreach ($data_kehadiran as $row) {
            $data = [
                $row->pamong->pamong_nama ?: $row->pamong->penduduk->nama,
                $row->pamong->status_pejabat == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') . ' ' . $row->pamong->jabatan->nama : $row->pamong->jabatan->nama,
                tgl_indo($row->tanggal),
                date('H:i', strtotime($row->jam_masuk)),
                $row->jam_keluar == null ? '-' : date('H:i', strtotime($row->jam_keluar)),
                date('H:i', strtotime($row->total)),
                ucwords($this->normalisasiStatusKehadiran($row)['label']),
            ];
            $writer->addRow(Row::fromValues($data));
        }
        $writer->close();
    }

    public function bulanan()
    {
        [$bulan, $tahun] = $this->normalisasiPeriode(request('bulan'), request('tahun'));

        $rekap = $this->hitungRekapBulanan($tahun, $bulan);

        $data = [
            'rekap' => $rekap,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ];

        if (request()->ajax()) {
            return view('kehadiran::backend.rekapitulasi.bulanan_tabel', $data)->render();
        }

        return show_404();
    }

    public function dialogBulanan($aksi = 'cetak')
    {
        [$bulan, $tahun] = $this->normalisasiPeriode(request('bulan'), request('tahun'));

        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['sensor_nik']  = false;
        $data['form_action'] = ci_route('kehadiran_rekapitulasi.bulanan.' . ($aksi === 'excel' ? 'ekspor' : 'cetak')) . "?bulan={$bulan}&tahun={$tahun}";

        return view('admin.layouts.components.ttd_pamong', $data);
    }

    public function cetakBulanan()
    {
        [$bulan, $tahun] = $this->normalisasiPeriode(request('bulan'), request('tahun'));

        $rekap       = $this->hitungRekapBulanan($tahun, $bulan);
        $bulanCarbon = Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->locale('id');

        $ttd               = $this->modal_penandatangan();
        $pamong_ttd_id     = request('pamong_ttd');
        $pamong_ketahui_id = request('pamong_ketahui');
        $pamong_ttd        = Pamong::selectData()->where(['pamong_id' => $pamong_ttd_id ?: $ttd['pamong_ttd']['pamong_id']])->first()?->toArray() ?? $ttd['pamong_ttd'];
        $pamong_ketahui    = Pamong::selectData()->where(['pamong_id' => $pamong_ketahui_id ?: $ttd['pamong_ketahui']['pamong_id']])->first()?->toArray() ?? $ttd['pamong_ketahui'];

        $data = [
            'rekap'          => $rekap,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'bulanCarbon'    => $bulanCarbon,
            'pamong_ttd'     => $pamong_ttd,
            'pamong_ketahui' => $pamong_ketahui,
            'aksi'           => 'cetak',
            'file'           => 'Rekapitulasi Bulanan Kehadiran',
        ];

        return view('kehadiran::backend.rekapitulasi.cetak_bulanan', $data);
    }

    public function eksporBulanan()
    {
        [$bulan, $tahun] = $this->normalisasiPeriode(request('bulan'), request('tahun'));

        $rekap       = $this->hitungRekapBulanan($tahun, $bulan);
        $bulanCarbon = Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->locale('id');
        $period      = $tahun . '-' . $bulan;
        $filename    = 'rekap_bulanan_kehadiran_' . $period;

        $ttd               = $this->modal_penandatangan();
        $pamong_ttd_id     = request('pamong_ttd');
        $pamong_ketahui_id = request('pamong_ketahui');
        $pamong_ttd        = Pamong::selectData()->where(['pamong_id' => $pamong_ttd_id ?: $ttd['pamong_ttd']['pamong_id']])->first()?->toArray() ?? $ttd['pamong_ttd'];
        $pamong_ketahui    = Pamong::selectData()->where(['pamong_id' => $pamong_ketahui_id ?: $ttd['pamong_ketahui']['pamong_id']])->first()?->toArray() ?? $ttd['pamong_ketahui'];

        $data = [
            'rekap'          => $rekap,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'bulanCarbon'    => $bulanCarbon,
            'pamong_ttd'     => $pamong_ttd,
            'pamong_ketahui' => $pamong_ketahui,
            'aksi'           => 'unduh',
            'file'           => $filename,
        ];

        return response(
            view('kehadiran::backend.rekapitulasi.cetak_bulanan', $data)->render(),
            200,
            [
                'Content-Type'        => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="' . namafile($filename) . '.xls"',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ]
        );
    }

    private function normalisasiStatusKehadiran($row): array
    {
        $status    = trim((string) $row->status_kehadiran);
        $jamMasuk  = $row->jam_masuk;
        $jamKeluar = $row->jam_keluar;

        if ($status === '') {
            if ($jamMasuk && $jamKeluar) {
                $status = 'tidak berada di kantor';
                $tipe   = 'danger';
            } elseif ($jamMasuk && ! $jamKeluar) {
                $status = 'lupa melapor keluar';
                $tipe   = 'warning';
            } else {
                $status = 'belum ditentukan';
                $tipe   = 'warning';
            }
        } else {
            $statusLower = strtolower($status);

            if ($statusLower === 'hadir' && $jamKeluar) {
                $statusLower = 'tidak berada di kantor';
            }

            $tipe = ($statusLower === 'hadir') ? 'success'
                : (($statusLower === 'tidak berada di kantor') ? 'danger'
                : 'warning');

            $status = JenisIzin::valueOf($statusLower, $statusLower);
        }

        return [
            'label' => $status,
            'tipe'  => $tipe,
        ];
    }

    private function hitungRekapBulanan($tahun, $bulan): array
    {
        $hariKerja      = $this->dapatkanHariKerja($tahun, $bulan);
        $totalHariKerja = count($hariKerja);

        $pamongList = Pamong::daftar()
            ->where(static function ($q) {
                $q->has('penduduk')->orWhere('pamong_nama', '!=', '');
            })
            ->with(['penduduk', 'jabatan'])
            ->get();

        // Batch load semua kehadiran sekaligus (fix N+1 query)
        $semuaKehadiran = Kehadiran::whereIn('pamong_id', $pamongList->pluck('pamong_id'))
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->groupBy('pamong_id');

        $rekap = [];

        foreach ($pamongList as $p) {
            $nama    = $p->pamong_nama ?: ($p->penduduk->nama ?? '-');
            $jabatan = $p->status_pejabat == StatusEnum::YA
                ? setting('sebutan_pj_kepala_desa') . ' ' . $p->jabatan->nama
                : $p->jabatan->nama;

            $kehadirans = ($semuaKehadiran[$p->pamong_id] ?? collect())->keyBy('tanggal');

            $hadir            = 0;
            $izin             = 0;
            $sakit            = 0;
            $cuti             = 0;
            $dinas_luar       = 0;
            $tanpa_keterangan = 0;

            foreach ($hariKerja as $tgl) {
                if (isset($kehadirans[$tgl])) {
                    $status = strtolower(trim((string) $kehadirans[$tgl]->status_kehadiran));
                    if (in_array($status, self::STATUS_HADIR)) {
                        $hadir++;
                    } elseif ($status === 'izin') {
                        $izin++;
                    } elseif ($status === 'sakit') {
                        $sakit++;
                    } elseif ($status === 'cuti') {
                        $cuti++;
                    } elseif (in_array($status, self::STATUS_DINAS_LUAR)) {
                        $dinas_luar++;
                    } else {
                        $tanpa_keterangan++;
                    }
                } else {
                    $tanpa_keterangan++;
                }
            }

            $jumlah_absen = $izin + $sakit + $cuti + $dinas_luar + $tanpa_keterangan;

            $rekap[] = [
                'pamong_id'        => $p->pamong_id,
                'nama'             => $nama,
                'jabatan'          => $jabatan,
                'hari_kerja'       => $totalHariKerja,
                'hadir'            => $hadir,
                'izin'             => $izin,
                'sakit'            => $sakit,
                'cuti'             => $cuti,
                'dinas_luar'       => $dinas_luar,
                'tanpa_keterangan' => $tanpa_keterangan,
                'jumlah_absen'     => $jumlah_absen,
                'keterangan'       => '',
            ];
        }

        return $rekap;
    }

    private function dapatkanHariKerja($tahun, $bulan): array
    {
        $jumlahHari     = Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->daysInMonth;
        $hariAktif      = Modules\Kehadiran\Models\JamKerja::where('status', 1)->pluck('nama_hari')->toArray();
        $ikutiHariLibur = setting('ikuti_hari_libur_terdaftar');

        $hariLibur = [];
        if ($ikutiHariLibur) {
            $hariLibur = Modules\Kehadiran\Models\HariLibur::whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', (int) $bulan)
                ->pluck('tanggal')
                ->toArray();
        }

        $dayMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $hariKerja = [];

        for ($d = 1; $d <= $jumlahHari; $d++) {
            $date    = Carbon\Carbon::createFromDate($tahun, (int) $bulan, $d);
            $engDay  = $date->englishDayOfWeek;
            $indoDay = $dayMap[$engDay] ?? '';

            if (in_array($indoDay, $hariAktif)) {
                if (! ($ikutiHariLibur && in_array($date->toDateString(), $hariLibur))) {
                    $hariKerja[] = $date->toDateString();
                }
            }
        }

        return $hariKerja;
    }

    private function normalisasiPeriode($bulan, $tahun): array
    {
        $bulan                                     = (int) $bulan;
        $tahun                                     = (int) $tahun;
        if ($bulan < 1 || $bulan > 12)      $bulan = (int) date('m');
        if ($tahun < 2000 || $tahun > 2100) $tahun = (int) date('Y');

        return [str_pad((string) $bulan, 2, '0', STR_PAD_LEFT), $tahun];
    }
}
