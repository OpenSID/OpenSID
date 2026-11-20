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

namespace Tests\Feature\Kehadiran;

use Tests\TestCase;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Services\CatatanHarianService;
use Modules\Kehadiran\Services\ApprovalCatatanService;
use Modules\Kehadiran\Services\RekapCatatanService;
use Modules\Kehadiran\Enums\StatusCatatan;

/**
 * Integration tests untuk full workflow Catatan Harian
 * dari creation → submission → approval → reporting
 */
class CatatanHarianWorkflowTest extends TestCase
{
    private CatatanHarianService $catatanService;
    private ApprovalCatatanService $approvalService;
    private RekapCatatanService $rekapService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->catatanService = app(CatatanHarianService::class);
        $this->approvalService = app(ApprovalCatatanService::class);
        $this->rekapService = app(RekapCatatanService::class);
    }

    /** @test */
    public function workflow_lengkap_dari_draft_hingga_approved()
    {
        // Step 1: Pamong membuat catatan DRAFT
        $catatan = $this->catatanService->createCatatan([
            'tanggal' => '2026-04-15',
            'hari' => 'Monday',
            'uraian_kegiatan' => 'Sosialisasi Program Pembangunan',
            'hasil_diharapkan' => 'Masyarakat memahami program',
            'lokasi_kegiatan' => 'Kantor desa',
            'config_id' => 1,
            'pamong_id' => 1,
            'status' => StatusCatatan::DRAFT->value,
        ], []);

        $this->assertEquals(StatusCatatan::DRAFT->value, $catatan->status);
        $this->assertNull($catatan->approved_at);

        // Step 2: Pamong edit catatan (still draft)
        $this->catatanService->updateCatatan($catatan, [
            'uraian_kegiatan' => 'Sosialisasi Program Pembangunan (Updated)',
            'tanggal' => $catatan->tanggal,
            'hari' => $catatan->hari,
            'lokasi_kegiatan' => 'Kantor desa dan lapangan',
            'hasil_diharapkan' => 'Masyarakat memahami dan siap mengikuti program',
        ], []);

        $catatan->refresh();
        $this->assertStringContainsString('Updated', $catatan->uraian_kegiatan);

        // Step 3: Ubah status ke MENUNGGU (simulate submission)
        $catatan->update(['status' => StatusCatatan::MENUNGGU->value]);
        $catatan->refresh();
        $this->assertEquals(StatusCatatan::MENUNGGU->value, $catatan->status);

        // Step 4: Approver approve catatan
        $approverId = 5;
        $this->approvalService->approveCatatan($catatan, $approverId);

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        $this->assertEquals($approverId, $catatan->approved_by);
        $this->assertNotNull($catatan->approved_at);

        // Step 5: Catatan muncul di rekap
        $rekapData = $this->rekapService->getRekapData(1, '04', '2026', null);
        $this->assertTrue($rekapData->contains('id', $catatan->id));
    }

    /** @test */
    public function workflow_rejection_dan_resubmission()
    {
        // Step 1: Create catatan
        $catatan = $this->catatanService->createCatatan([
            'tanggal' => '2026-04-15',
            'hari' => 'Tuesday',
            'uraian_kegiatan' => 'Initial activity',
            'hasil_diharapkan' => 'Expected result',
            'lokasi_kegiatan' => 'Location',
            'config_id' => 1,
            'pamong_id' => 1,
            'status' => StatusCatatan::MENUNGGU->value,
        ], []);

        // Step 2: Approver reject
        $alasan = 'Kegiatan perlu diperjelas lebih detail';
        $this->approvalService->rejectCatatan($catatan, $alasan, 5);

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::DITOLAK->value, $catatan->status);
        $this->assertEquals($alasan, $catatan->alasan_penolakan);

        // Step 3: Pamong fix dan resubmit (edit rejected catatan)
        $this->catatanService->updateCatatan($catatan, [
            'uraian_kegiatan' => 'Improved activity with more details',
            'tanggal' => $catatan->tanggal,
            'hari' => $catatan->hari,
            'lokasi_kegiatan' => $catatan->lokasi_kegiatan,
            'hasil_diharapkan' => 'Clear expected result',
        ], []);

        // Step 4: Change status back to MENUNGGU
        $catatan->update(['status' => StatusCatatan::MENUNGGU->value]);

        // Step 5: Approver approve on resubmission
        $this->approvalService->approveCatatan($catatan, 5);

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        $this->assertStringContainsString('Improved', $catatan->uraian_kegiatan);
    }

    /** @test */
    public function workflow_multiple_catatans_dengan_bulk_approval()
    {
        // Create multiple pending catatans
        $catatans = [];
        for ($i = 1; $i <= 5; $i++) {
            $catatan = $this->catatanService->createCatatan([
                'tanggal' => '2026-04-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'hari' => 'Monday',
                'uraian_kegiatan' => "Activity $i",
                'hasil_diharapkan' => "Result $i",
                'lokasi_kegiatan' => "Location $i",
                'config_id' => 1,
                'pamong_id' => $i,
                'status' => StatusCatatan::MENUNGGU->value,
            ], []);
            $catatans[] = $catatan;
        }

        $this->assertCount(5, $catatans);

        // Bulk approve all
        $ids = array_map(fn ($c) => $c->id, $catatans);
        $updated = $this->approvalService->bulkApproveCatatan($ids, 5);

        $this->assertEquals(5, $updated);

        // Verify all approved
        foreach ($catatans as $catatan) {
            $catatan->refresh();
            $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        }
    }

    /** @test */
    public function workflow_approval_stats_dashboard()
    {
        // Create mix of statuses
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'status' => StatusCatatan::DRAFT->value,
        ]);
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'status' => StatusCatatan::MENUNGGU->value,
        ]);
        CatatanHarian::factory()->createMany(8, [
            'config_id' => 1,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(1, [
            'config_id' => 1,
            'status' => StatusCatatan::DITOLAK->value,
        ]);

        // Get stats
        $stats = $this->approvalService->getApprovalStats();

        $this->assertEquals(2, $stats['draft']);
        $this->assertEquals(5, $stats['menunggu']);
        $this->assertEquals(8, $stats['disetujui']);
        $this->assertEquals(1, $stats['ditolak']);

        $total = array_sum($stats);
        $this->assertEquals(16, $total);
    }

    /** @test */
    public function workflow_rekap_dengan_filter_multiple()
    {
        $pamong1 = 1;
        $pamong2 = 2;

        // Create catatans for multiple pamongs di bulan April
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'pamong_id' => $pamong1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'pamong_id' => $pamong2,
            'tanggal' => '2026-04-20',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        // Create catatans untuk Mei
        CatatanHarian::factory()->createMany(4, [
            'config_id' => 1,
            'pamong_id' => $pamong1,
            'tanggal' => '2026-05-10',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        // Get rekap for April only
        $aprilData = $this->rekapService->getRekapData(1, '04', '2026', null);
        $this->assertCount(5, $aprilData);

        // Get rekap for April + pamong1 only
        $april_pamong1 = $this->rekapService->getRekapData(1, '04', '2026', $pamong1);
        $this->assertCount(3, $april_pamong1);

        // Get rekap for all months + pamong1
        $all_pamong1 = $this->rekapService->getRekapData(1, null, '2026', $pamong1);
        $this->assertCount(7, $all_pamong1);
    }

    /** @test */
    public function workflow_cannot_delete_approved_catatan()
    {
        $catatan = $this->catatanService->createCatatan([
            'tanggal' => '2026-04-15',
            'hari' => 'Monday',
            'uraian_kegiatan' => 'Test',
            'hasil_diharapkan' => 'Result',
            'lokasi_kegiatan' => 'Location',
            'config_id' => 1,
            'pamong_id' => 1,
            'status' => StatusCatatan::MENUNGGU->value,
        ], []);

        // Approve
        $this->approvalService->approveCatatan($catatan, 5);
        $catatan->refresh();

        // Try to delete
        $this->expectException(\Exception::class);
        $this->catatanService->deleteCatatan($catatan);
    }

    /** @test */
    public function workflow_pdf_export_with_approved_catatans()
    {
        // Create approved catatans for April
        CatatanHarian::factory()->createMany(10, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        // Generate PDF
        $pdf = $this->rekapService->generatePdfContent(1, '04', null);

        $this->assertNotEmpty($pdf);
        $this->assertStringContainsString('html', strtolower($pdf));

        // Get filename
        $filename = $this->rekapService->getPdfFilename('04');
        $this->assertStringContainsString('April', $filename);
    }

    /** @test */
    public function workflow_csv_export_with_filters()
    {
        $pamongId = 1;

        // Create catatans for specific pamong
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'pamong_id' => $pamongId,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'pamong_id' => 2,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        // Export CSV for specific pamong
        $csv = $this->rekapService->generateCsvContent(1, '04', $pamongId);

        $this->assertNotEmpty($csv);
        $this->assertStringContainsString(',', $csv);

        // Count rows (should be 5 + header)
        $lines = explode("\n", $csv);
        $this->assertGreaterThanOrEqual(5, count($lines) - 1);
    }

    /** @test */
    public function workflow_multi_tenancy_isolation()
    {
        // Config 1 catatans
        $config1_catatans = CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'status' => StatusCatatan::MENUNGGU->value,
        ]);

        // Config 2 catatans
        $config2_catatans = CatatanHarian::factory()->createMany(2, [
            'config_id' => 2,
            'status' => StatusCatatan::MENUNGGU->value,
        ]);

        // Approve only config 1 catatans
        $config1_ids = array_map(fn ($c) => $c->id, $config1_catatans);
        $this->approvalService->bulkApproveCatatan($config1_ids, 5);

        // Verify config 1 approved, config 2 still pending
        foreach ($config1_catatans as $c) {
            $c->refresh();
            $this->assertEquals(StatusCatatan::DISETUJUI->value, $c->status);
        }

        foreach ($config2_catatans as $c) {
            $c->refresh();
            $this->assertEquals(StatusCatatan::MENUNGGU->value, $c->status);
        }

        // Verify stats are separate
        $stats1 = $this->approvalService->getApprovalStats();
        $stats2 = $this->approvalService->getApprovalStats();

        $this->assertEquals(3, $stats1['disetujui']);
        $this->assertEquals(0, $stats2['disetujui']);
        $this->assertEquals(0, $stats1['menunggu']);
        $this->assertEquals(2, $stats2['menunggu']);
    }
}
