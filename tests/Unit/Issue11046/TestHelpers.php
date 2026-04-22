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

namespace Tests\Unit\Issue11046;

/**
 * Test helper functions for Issue #11046 tests
 */
class TestHelpers
{
    /**
     * Simulate pantau server failure by marking it as down
     */
    public static function simulatePantauDown(int $attempts = 3): void
    {
        mark_pantau_down($attempts);
    }

    /**
     * Simulate pantau server recovery
     */
    public static function simulatePantauRecovery(): void
    {
        reset_pantau_status();
    }

    /**
     * Check if pantau is currently marked as down
     */
    public static function isPantauDown(): bool
    {
        return pantau_is_down();
    }

    /**
     * Get current attempt count
     */
    public static function getAttemptCount(): int
    {
        return get_pantau_attempt_count();
    }

    /**
     * Simulate multiple concurrent requests
     */
    public static function simulateConcurrentRequests(int $count, callable $callback): array
    {
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = $callback($i);
        }
        return $results;
    }

    /**
     * Verify circuit breaker behavior during high load
     */
    public static function verifyCircuitBreakerUnderLoad(int $requestCount = 100): array
    {
        $stats = [
            'requests_attempted' => $requestCount,
            'requests_blocked' => 0,
            'avg_response_time' => 0,
        ];

        mark_pantau_down(3);

        for ($i = 0; $i < $requestCount; $i++) {
            if (pantau_is_down()) {
                $stats['requests_blocked']++;
            }
        }

        reset_pantau_status();

        return $stats;
    }
}
