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
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Kehadiran\Enums\StatusCatatan;
use Modules\Kehadiran\Models\CatatanHarian;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Service class untuk handle approval workflow
 *
 * Tanggung jawab:
 * - Validasi status catatan
 * - Approve catatan dengan proper status checking
 * - Reject catatan dengan alasan
 * - Bulk approve operations (performance optimized)
 *
 * Business Rules:
 * - Hanya catatan dengan status MENUNGGU yang bisa disetujui/ditolak
 * - Approver harus terdefenisi
 * - Alasan penolakan wajib untuk reject
 */
class ApprovalCatatanService
{
    /**
     * Approve single catatan dengan business logic validation
     *
     * @throws Exception                Jika update gagal
     * @throws InvalidArgumentException Jika status tidak valid
     */
    public function approveCatatan(CatatanHarian $catatan, ?int $approverPamongId = null): bool
    {
        // Business rule: hanya catatan MENUNGGU yang bisa disetujui
        if ($catatan->status !== StatusCatatan::MENUNGGU) {
            throw new InvalidArgumentException(
                'Catatan dengan status ' . $catatan->status->label() .
                ' tidak dapat disetujui. Hanya catatan dengan status ' .
                StatusCatatan::MENUNGGU->label() . ' yang dapat disetujui.'
            );
        }

        return DB::transaction(static fn () => $catatan->update([
            'status'      => StatusCatatan::DISETUJUI->value,
            'approved_by' => $approverPamongId,
            'approved_at' => Carbon::now()->toDateTimeString(),
        ]));
    }

    /**
     * Reject catatan dengan alasan (business logic validation)
     *
     * @param string $alasan Alasan penolakan
     *
     * @throws Exception                Jika update gagal
     * @throws InvalidArgumentException Jika status tidak valid atau alasan kosong
     */
    public function rejectCatatan(CatatanHarian $catatan, string $alasan, ?int $approverPamongId = null): bool
    {
        // Business rule: hanya catatan MENUNGGU yang bisa ditolak
        if ($catatan->status !== StatusCatatan::MENUNGGU) {
            throw new InvalidArgumentException(
                'Catatan dengan status ' . $catatan->status->label() .
                ' tidak dapat ditolak. Hanya catatan dengan status ' .
                StatusCatatan::MENUNGGU->label() . ' yang dapat ditolak.'
            );
        }

        // Business rule: alasan penolakan wajib
        $alasan = trim($alasan);
        if (empty($alasan)) {
            throw new InvalidArgumentException('Alasan penolakan wajib diisi');
        }

        if (strlen($alasan) < 5) {
            throw new InvalidArgumentException('Alasan penolakan minimal 5 karakter');
        }

        if (strlen($alasan) > 1000) {
            throw new InvalidArgumentException('Alasan penolakan maksimal 1000 karakter');
        }

        return DB::transaction(static fn () => $catatan->update([
            'status'           => StatusCatatan::DITOLAK->value,
            'approved_by'      => $approverPamongId,
            'approved_at'      => Carbon::now()->toDateTimeString(),
            'alasan_penolakan' => $alasan,
        ]));
    }

    /**
     * Bulk approve dengan batch update (performance optimized)
     *
     * Business rule: hanya catatan MENUNGGU yang diupdate
     *
     * @param array    $ids              Array of catatan IDs
     * @param int|null $approverPamongId ID of approver
     *
     * @throws Exception                Jika update gagal
     * @throws InvalidArgumentException Jika IDs kosong atau tidak valid
     *
     * @return int Number of updated records
     */
    public function bulkApproveCatatan(array $ids, ?int $approverPamongId): int
    {
        // Business rule: minimal harus ada satu ID
        if (empty($ids)) {
            throw new InvalidArgumentException('Pilih minimal satu catatan untuk disetujui');
        }

        // Business rule: IDs harus array dan berisi nilai
        $ids = array_filter($ids, static fn ($id) => ! empty($id));
        if (empty($ids)) {
            throw new InvalidArgumentException('Data catatan tidak valid');
        }

        return DB::transaction(static function () use ($ids, $approverPamongId) {
            $updated = CatatanHarian::where('status', StatusCatatan::MENUNGGU->value)
                ->whereIn('uuid', $ids)
                ->update([
                    'status'      => StatusCatatan::DISETUJUI->value,
                    'approved_by' => $approverPamongId,
                    'approved_at' => Carbon::now()->toDateTimeString(),
                ]);

            if ($updated === 0) {
                throw new Exception('Tidak ada catatan dengan status menunggu yang ditemukan');
            }

            return $updated;
        });
    }

    /**
     * Get approval statistics untuk dashboard
     */
    public function getApprovalStats(): array
    {
        return [
            'menunggu' => CatatanHarian::where('status', StatusCatatan::MENUNGGU->value)
                ->count(),
            'disetujui' => CatatanHarian::where('status', StatusCatatan::DISETUJUI->value)
                ->count(),
            'ditolak' => CatatanHarian::where('status', StatusCatatan::DITOLAK->value)
                ->count(),
            'draft' => CatatanHarian::where('status', StatusCatatan::DRAFT->value)
                ->count(),
        ];
    }
}
