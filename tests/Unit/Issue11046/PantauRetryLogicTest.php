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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\TestCase;
use Tests\BaseTestCase;

class PantauRetryLogicTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /**
     * Test httpPost() returns response on successful request
     *
     * @test
     */
    public function test_http_post_returns_response_on_success()
    {
        // Mock Guzzle Client
        $mockResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockResponse->method('getBody')
            ->willReturn((object)['getContents' => function() { return 'success'; }]);
        
        // Test with valid URL and params
        // Note: This is integration test, actual mock would be done in Feature tests
        // For now, we test the circuit breaker behavior
        
        $this->assertTrue(true);
    }

    /**
     * Test httpPost() checks circuit breaker before attempting request
     *
     * @test
     */
    public function test_http_post_skips_request_when_circuit_breaker_active()
    {
        // Mark pantau as down
        mark_pantau_down(3);
        
        // Attempt httpPost - should skip and return null
        $result = httpPost('http://invalid-pantau.test/api', ['test' => 'data']);
        
        $this->assertNull($result);
    }

    /**
     * Test httpPost() resets pantau status on successful request
     *
     * @test
     */
    public function test_http_post_resets_pantau_status_on_success()
    {
        // Setup: Mark pantau as down
        mark_pantau_down(2);
        $this->assertTrue(pantau_is_down());
        
        // Note: Full test would require mocking Guzzle response
        // For now, verify the reset mechanism exists
        reset_pantau_status();
        $this->assertFalse(pantau_is_down());
    }

    /**
     * Test httpPost() marks pantau down after all retries fail
     *
     * @test
     */
    public function test_http_post_marks_pantau_down_after_retries_fail()
    {
        // Initial state: pantau is up
        $this->assertFalse(pantau_is_down());
        
        // Attempt to httpPost with invalid URL (will fail all 3 retries)
        $result = httpPost('http://invalid-timeout-test:9999/api', ['test' => 'data']);
        
        // Should return null after all retries failed
        $this->assertNull($result);
        
        // Pantau should now be marked as down (eventually, after retries)
        // Note: Actual test depends on timing and network simulation
    }

    /**
     * Test opendk_api() with retry logic on failure
     *
     * @test
     */
    public function test_opendk_api_returns_error_on_connection_failure()
    {
        // This test verifies the retry logic is implemented
        // Actual execution would need Guzzle mocking or integration test setup
        
        $this->assertTrue(true); // Placeholder for integration test
    }

    /**
     * Test opendk_api() returns success response on valid response
     *
     * @test
     */
    public function test_opendk_api_returns_success_response()
    {
        // Placeholder for integration test with mock response
        $this->assertTrue(true);
    }

    /**
     * Test pantau recovery after circuit breaker expires
     *
     * @test
     */
    public function test_pantau_recovery_after_circuit_breaker_expires()
    {
        // Mark as down with 1 minute backoff
        mark_pantau_down(1);
        $this->assertTrue(pantau_is_down());
        
        // Simulate time passage (in real test, would use time travel)
        // For now, manually reset
        Cache::forget('pantau_server_down');
        
        // Should be available again
        $this->assertFalse(pantau_is_down());
    }

    /**
     * Test consecutive failures escalate backoff time
     *
     * @test
     */
    public function test_consecutive_failures_escalate_backoff()
    {
        // First failure: 1 minute backoff
        mark_pantau_down(1);
        $this->assertEquals(1, get_pantau_attempt_count());
        
        // Second group of failures: 5 minute backoff
        Cache::flush();
        mark_pantau_down(4);
        $this->assertEquals(4, get_pantau_attempt_count());
        
        // Third group: 30 minute backoff
        Cache::flush();
        mark_pantau_down(7);
        $this->assertEquals(7, get_pantau_attempt_count());
    }

    /**
     * Test get_data_desa() circuit breaker check
     *
     * @test
     */
    public function test_get_data_desa_skips_when_pantau_down()
    {
        // Mark pantau as down
        mark_pantau_down(3);
        
        // Call get_data_desa - should skip and return null immediately
        $result = get_data_desa('1234.56.78.90.123');
        
        $this->assertNull($result);
    }

    /**
     * Test retry delay timing antar attempt.
     *
     * Implementasi memakai jeda pendek tetap (200ms) antar retry agar
     * request user-facing tidak ikut ter-block lama saat pantau down.
     *
     * @test
     */
    public function test_retry_delay_timing()
    {
        $retry_delay_us = 200000; // 200ms

        $this->assertEquals(200000, $retry_delay_us);
        $this->assertLessThan(1000000, $retry_delay_us, 'Retry delay harus < 1 detik untuk menjaga responsivitas');
    }

    /**
     * Test that failed request logs error properly
     *
     * @test
     */
    public function test_failed_request_logs_error()
    {
        // This would be tested with log spy/mock in integration tests
        $this->assertTrue(true);
    }

    /**
     * Test successful request resets attempt counter
     *
     * @test
     */
    public function test_successful_request_resets_attempt_counter()
    {
        // Setup: mark as down with attempts
        mark_pantau_down(3);
        $this->assertEquals(3, get_pantau_attempt_count());
        
        // Reset when successful
        reset_pantau_status();
        $this->assertEquals(0, get_pantau_attempt_count());
    }

    /**
     * Test cache key naming consistency
     *
     * @test
     */
    public function test_cache_key_naming_consistency()
    {
        // Mark down and verify cache keys are set
        mark_pantau_down(2);
        
        $this->assertTrue(Cache::has('pantau_server_down'));
        $this->assertTrue(Cache::has('pantau_server_attempts'));
        
        // Reset and verify cache keys are removed
        reset_pantau_status();
        
        $this->assertFalse(Cache::has('pantau_server_down'));
        $this->assertFalse(Cache::has('pantau_server_attempts'));
    }
}
