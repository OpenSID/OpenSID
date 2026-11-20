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

namespace Modules\Kehadiran\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Modules\Kehadiran\Enums\StatusCatatan;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Traits\HasIndonesianDateFormat;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Service class untuk handle report generation dan export
 *
 * Tanggung jawab:
 * - Generate recap data dengan filters
 * - Memory safety untuk PDF/CSV export
 * - Data transformation untuk reporting
 */
class RekapCatatanService
{
    use HasIndonesianDateFormat;

    /**
     * Maximum rows untuk PDF export (memory safe)
     */
    private const MAX_ROWS_PDF = 5000;

    /**
     * Maximum rows untuk Excel export (memory safe)
     */
    private const MAX_ROWS_EXPORT = 50000;

    /**
     * Get recap data query builder dengan filters
     *
     * @param string|null $bulan Format: YYYY-MM
     * @param string|null $tahun Format: YYYY
     *
     * @return Builder
     */
    public function getRekapQuery(
        ?string $status = null,
        ?string $bulan = null,
        ?string $tahun = null,
        ?string $pamongId = null
    ) {
        $query = CatatanHarian::with([
            'pamong:pamong_id,id_pend,jabatan_id,pamong_nama,gelar_depan,gelar_belakang',
            'pamong.penduduk:id,nama',
            'pamong.jabatan:id,nama',
        ]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($bulan) {
            [$tahun, $month] = explode('-', $bulan);
            $query->byMonth((int) $month, (int) $tahun);
        } elseif ($tahun) {
            $query->byYear($tahun);
        }

        if ($pamongId) {
            $query->where('pamong_id', $pamongId);
        }

        return $query->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get recap data dengan filters dan pagination
     *
     * @param string|null $bulan Format: YYYY-MM
     * @param string|null $tahun Format: YYYY
     */
    public function getRekapData(
        ?string $status = null,
        ?string $bulan = null,
        ?string $tahun = null,
        ?string $pamongId = null,
        int $limit = 100,
        int $offset = 0
    ): Collection {
        return $this->getRekapQuery($status, $bulan, $tahun, $pamongId)
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    /**
     * Count total records dengan filters
     *
     * @param string|null $bulan Format: YYYY-MM
     * @param string|null $tahun Format: YYYY
     */
    public function countRekapData(
        ?string $status = null,
        ?string $bulan = null,
        ?string $tahun = null,
        ?string $pamongId = null
    ): int {
        $query = CatatanHarian::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($bulan) {
            [$tahun, $month] = explode('-', $bulan);
            $query->byMonth((int) $month, (int) $tahun);
        } elseif ($tahun) {
            $query->byYear($tahun);
        }

        if ($pamongId) {
            $query->where('pamong_id', $pamongId);
        }

        return $query->count();
    }

    /**
     * Get data untuk PDF export dengan memory safety
     *
     * @param string|null $bulan Format: YYYY-MM
     * @param string|null $tahun Format: YYYY
     *
     * @throws Exception Jika total records melebihi limit
     */
    public function getDataForPdfExport(
        ?string $status = null,
        ?string $bulan = null,
        ?string $tahun = null,
        ?string $pamongId = null
    ): Collection {
        $totalRows = $this->countRekapData($status, $bulan, $tahun, $pamongId);

        if ($totalRows > self::MAX_ROWS_PDF) {
            throw new Exception("Data terlalu besar ({$totalRows} baris). Maximum untuk PDF adalah " .
                self::MAX_ROWS_PDF . ' baris. Gunakan filter yang lebih spesifik.');
        }

        return $this->getRekapData(
            $status,
            $bulan,
            $tahun,
            $pamongId,
            self::MAX_ROWS_PDF,
            0
        );
    }

    /**
     * Get data untuk Excel export dengan memory safety
     *
     * @param string|null $bulan Format: YYYY-MM
     * @param string|null $tahun Format: YYYY
     *
     * @throws Exception Jika total records melebihi limit
     */
    public function getDataForExcelExport(
        ?string $status = null,
        ?string $bulan = null,
        ?string $tahun = null,
        ?string $pamongId = null
    ): Collection {
        $totalRows = $this->countRekapData($status, $bulan, $tahun, $pamongId);

        if ($totalRows > self::MAX_ROWS_EXPORT) {
            throw new Exception("Data terlalu besar ({$totalRows} baris). Maximum untuk Excel export adalah " .
                self::MAX_ROWS_EXPORT . ' baris. Gunakan filter yang lebih spesifik.');
        }

        return $this->getRekapData(
            $status,
            $bulan,
            $tahun,
            $pamongId,
            self::MAX_ROWS_EXPORT,
            0
        );
    }

    /**
     * Transform catatan collection untuk tampilan table
     */
    public function transformForTable(Collection $catatan): array
    {
        return $catatan->map(fn (CatatanHarian $item) => [
            'id'                 => $item->id,
            'tanggal'            => $item->tanggal,
            'hari'               => $this->getHariIndonesia($item->hari),
            'pamong_nama'        => $item->pamong?->penduduk?->nama ?? '-',
            'pamong_jabatan'     => $item->pamong?->nama_jabatan ?? '-',
            'uraian_kegiatan'    => $item->uraian_kegiatan,
            'status'             => $item->status,
            'status_label'       => StatusCatatan::from($item->status)->label(),
            'status_badge_color' => StatusCatatan::from($item->status)->badgeColor(),
            'approved_at'        => $item->approved_at,
            'alasan_penolakan'   => $item->alasan_penolakan,
        ])->toArray();
    }

    /**
     * Generate PDF content (HTML)
     *
     * @param string|null $bulan Format: YYYY-MM
     *
     * @throws Exception
     *
     * @return string HTML content untuk PDF
     */
    public function generatePdfContent(
        ?string $bulan = null,
        ?string $pamongId = null
    ): string {
        $tahun    = Carbon::now()->year;
        $bulanNum = Carbon::now()->month;

        if ($bulan) {
            [$tahun, $bulanNum] = explode('-', $bulan);
        }

        $catatans = $this->getDataForPdfExport(
            StatusCatatan::DISETUJUI->value,
            $tahun . '-' . str_pad($bulanNum, 2, '0', STR_PAD_LEFT),
            null,
            $pamongId
        );

        $pamongData = null;
        if ($pamongId) {
            $pamongData = \App\Models\Pamong::where('pamong_id', $pamongId)
                ->with('penduduk:id,nama')
                ->first();
        }

        return View::make('kehadiran::backend.rekap_catatan.cetak', [
            'catatans' => $catatans,
            'bulan'    => $tahun . '-' . str_pad($bulanNum, 2, '0', STR_PAD_LEFT),
            'tahun'    => $tahun,
            'pamong'   => $pamongData,
        ])->render();
    }

    /**
     * Get filename untuk PDF export
     *
     * @param string|null $bulan Format: YYYY-MM
     */
    public function getPdfFilename(?string $bulan = null): string
    {
        $tahun    = Carbon::now()->year;
        $bulanNum = Carbon::now()->month;

        if ($bulan) {
            [$tahun, $bulanNum] = explode('-', $bulan);
        }

        $bulanName = $this->getBulanIndonesia((int) $bulanNum);

        return "Rekap_Catatan_Harian_{$bulanName}_{$tahun}.pdf";
    }

    /**
     * Get hari in Indonesian for display
     */
    private function getHariIndonesia(string $hari): string
    {
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        return $hariMap[$hari] ?? $hari;
    }
}
