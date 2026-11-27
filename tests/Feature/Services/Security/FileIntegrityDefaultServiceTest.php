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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Feature\Services\Security;

use App\Models\SecurityReport;
use App\Services\Security\FileIntegrityDefaultService;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Fase 6C — bagian DB/ringan dari `FileIntegrityDefaultService`.
 *
 * Method scan filesystem penuh (generateBaseline/runScan/fullScan) sengaja TIDAK ditest
 * di sini karena memindai seluruh codebase (lambat & rapuh) — fokus ke jalur report/baseline DB.
 */
class FileIntegrityDefaultServiceTest extends BaseTestCase
{
    use RefreshDatabase;

    private function service(): FileIntegrityDefaultService
    {
        return new FileIntegrityDefaultService();
    }

    public function test_get_pattern_stats_delegasi_ke_detector(): void
    {
        $stats = $this->service()->getPatternStats();

        $this->assertArrayHasKey('total_patterns', $stats);
        $this->assertGreaterThan(0, $stats['total_patterns']);
    }

    public function test_baseline_info_null_saat_belum_ada(): void
    {
        $this->assertNull($this->service()->getBaselineInfo());
        $this->assertFalse($this->service()->deleteBaseline());
    }

    public function test_export_report_lalu_ambil(): void
    {
        $svc      = $this->service();
        $filename = $svc->exportReport(['ringkasan' => 'uji', 'total' => 3], 'integrity');

        $this->assertStringContainsString('security_integrity_', $filename);
        $this->assertStringEndsWith('.json', $filename);

        $this->assertDatabaseHas('security_reports', ['filename' => $filename, 'type' => 'integrity']);

        $report = $svc->getReport($filename);
        $this->assertInstanceOf(SecurityReport::class, $report);
        $this->assertSame('uji', $report->data['ringkasan']);
    }

    public function test_get_reports_dan_filter_tipe(): void
    {
        $svc = $this->service();
        $svc->exportReport(['a' => 1], 'integrity');
        $svc->exportReport(['b' => 2], 'scan');

        $this->assertGreaterThanOrEqual(2, $svc->getReports()->count());
        $this->assertTrue($svc->getReports('scan')->every(static fn ($r): bool => $r->type === 'scan'));
    }

    public function test_delete_report(): void
    {
        $svc      = $this->service();
        $filename = $svc->exportReport(['x' => 1], 'integrity');

        $this->assertTrue($svc->deleteReport($filename));
        $this->assertNull($svc->getReport($filename));
        $this->assertFalse($svc->deleteReport('tidak-ada.json'));
    }
}
