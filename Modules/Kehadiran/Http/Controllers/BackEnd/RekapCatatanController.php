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

use App\Models\Pamong;
use Modules\Kehadiran\Enums\StatusCatatan;
use Modules\Kehadiran\Exports\RekapCatatanExport;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Services\RekapCatatanService;

defined('BASEPATH') || exit('No direct script access allowed');

class RekapCatatanController extends AdminModulController
{
    public $moduleName      = 'Kehadiran';
    public $modul_ini       = 'kehadiran';
    public $sub_modul_ini   = 'rekap-catatan-harian';
    public $aliasController = 'kehadiran_rekap_catatan';
    private RekapCatatanService $service;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->service = new RekapCatatanService();
    }

    public function index()
    {
        $bulan  = request('bulan') ?? date('m');
        $tahun  = request('tahun') ?? date('Y');
        $pamong = request('pamong');

        $data = $this->service->getRekapData(
            StatusCatatan::DISETUJUI->value,
            $tahun . '-' . $bulan,
            null,
            $pamong,
            500,
            0
        );

        // Get list of pamong for filter
        $listPamong = Pamong::with('penduduk:id,nama')
            ->select('pamong_id', 'id_pend', 'jabatan_id', 'pamong_nama', 'gelar_depan', 'gelar_belakang')
            ->get();

        return view('kehadiran::backend.rekap_catatan.index', [
            'catatans'   => $data,
            'listPamong' => $listPamong,
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'pamong'     => $pamong,
            'minTahun'   => CatatanHarian::getMinYear(),
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $bulan  = request('bulan') ?: date('m');
            $tahun  = request('tahun') ?: date('Y');
            $pamong = request('pamong');

            // Get query builder directly for proper DataTables pagination
            $query = $this->service->getRekapQuery(
                StatusCatatan::DISETUJUI->value,
                $tahun . '-' . $bulan,
                null,
                $pamong
            );

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('nama_pamong', static fn ($row) => $row->pamong?->pamong_nama ?? '-')
                ->addColumn('jabatan', static fn ($row) => $row->pamong?->jabatan?->nama ?? '-')
                ->addColumn('tanggal', static fn ($row) => tgl_indo($row->tanggal))
                ->addColumn('uraian_kegiatan', static fn ($row) => $row->uraian_kegiatan)
                ->addColumn('hasil_diharapkan', static fn ($row) => $row->hasil_diharapkan ?? '-')
                ->filterColumn('nama_pamong', static function ($query, $keyword) {
                    $query->whereHas('pamong', static function ($q) use ($keyword) {
                        $q->where('pamong_nama', 'like', "%{$keyword}%")
                            ->orWhereHas('penduduk', static function ($q2) use ($keyword) {
                                $q2->where('nama', 'like', "%{$keyword}%");
                            });
                    });
                })
                ->filterColumn('jabatan', static function ($query, $keyword) {
                    $query->whereHas('pamong.jabatan', static function ($q) use ($keyword) {
                        $q->where('nama', 'like', "%{$keyword}%");
                    });
                })
                ->filter(static function ($query) {
                    if ($search = request('search')['value']) {
                        $query->where(static function ($q) use ($search) {
                            $q->where('uraian_kegiatan', 'like', "%{$search}%")
                                ->orWhere('hasil_diharapkan', 'like', "%{$search}%")
                                ->orWhereHas('pamong', static function ($q2) use ($search) {
                                    $q2->where('pamong_nama', 'like', "%{$search}%")
                                        ->orWhereHas('penduduk', static function ($q3) use ($search) {
                                            $q3->where('nama', 'like', "%{$search}%");
                                        });
                                });
                        });
                    }
                })
                ->make(true);
        }

        return show_404();
    }

    public function cetak(): void
    {
        try {
            $bulan  = request('bulan') ?? date('m');
            $tahun  = request('tahun') ?? date('Y');
            $pamong = request('pamong');

            $catatans = $this->service->getDataForExcelExport(
                StatusCatatan::DISETUJUI->value,
                $tahun . '-' . $bulan,
                $tahun,
                $pamong
            );

            $data = [
                'aksi'     => 'cetak',
                'bulan'    => $tahun . '-' . $bulan,
                'pamong'   => $pamong,
                'catatans' => $catatans,
                'file'     => 'Rekap Catatan Harian Kerja',
            ];

            view('kehadiran::backend.rekap_catatan.cetak', $data);
        } catch (Exception $e) {
            log_message('error', 'Gagal cetak rekap catatan: ' . $e->getMessage());
            redirect_with('error', 'Gagal mencetak rekap catatan harian.');
        }
    }

    public function ekspor(): void
    {
        try {
            $filters  = $this->getFilters();
            $bulanStr = $this->formatBulanStr($filters['tahun'], $filters['bulan']);

            $export = new RekapCatatanExport(
                $bulanStr,
                $filters['pamong'] ?? null
            );

            $export->download();
        } catch (Exception $e) {
            redirect_with('error', $e->getMessage());
        }
    }

    /**
     * Get report filters from request
     *
     * @return array Array dengan keys: bulan, tahun, pamong
     */
    private function getFilters(): array
    {
        return [
            'bulan'  => request('bulan') ?? date('m'),
            'tahun'  => request('tahun') ?? date('Y'),
            'pamong' => request('pamong'),
        ];
    }

    /**
     * Format bulan string untuk service (YYYY-MM)
     *
     * @return string Formatted as YYYY-MM
     */
    private function formatBulanStr(string|int $tahun, string|int $bulan): string
    {
        return $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Output PDF to browser dengan Html2Pdf
     *
     * @param string $html     HTML content untuk PDF
     * @param string $filename Output filename
     */
    private function outputPdf(string $html, string $filename): void
    {
        $pdf = new Spipu\Html2Pdf\Html2Pdf('L', 'A4', 'en');
        $pdf->setDefaultFont('Arial');
        $pdf->writeHTML($html);
        $pdf->output($filename, 'D');
    }
}
