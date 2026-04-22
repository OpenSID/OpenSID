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

use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\TestCase;
use Tests\BaseTestCase;

class PantauCircuitBreakerTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear all cache before each test
        Cache::flush();
    }

    /**
     * Test pantau_is_down() returns false when cache is empty
     *
     * @test
     */
    public function test_pantau_is_down_returns_false_initially()
    {
        $this->assertFalse(pantau_is_down());
    }

    /**
     * Test pantau_is_down() returns true after mark_pantau_down() is called
     *
     * @test
     */
    public function test_pantau_is_down_returns_true_after_marking_down()
    {
        mark_pantau_down(1);
        
        $this->assertTrue(pantau_is_down());
    }

    /**
     * Test mark_pantau_down() sets 1 minute backoff for attempt 1-3
     *
     * @test
     */
    public function test_mark_pantau_down_sets_1_minute_backoff_for_attempt_1_to_3()
    {
        mark_pantau_down(1);
        
        // Cache should have 'pantau_server_down' key
        $this->assertTrue(Cache::has('pantau_server_down'));
        
        // Verify cache expires within 1 minute (60 seconds)
        $ttl = Cache::getStore()->connection()->ttl('pantau_server_down');
        $this->assertLessThanOrEqual(60, $ttl);
    }

    /**
     * Test mark_pantau_down() sets 5 minute backoff for attempt 4-6
     *
     * @test
     */
    public function test_mark_pantau_down_sets_5_minute_backoff_for_attempt_4_to_6()
    {
        mark_pantau_down(4);
        
        $this->assertTrue(Cache::has('pantau_server_down'));
        
        // Verify cache expires within 5 minutes (300 seconds)
        $ttl = Cache::getStore()->connection()->ttl('pantau_server_down');
        $this->assertLessThanOrEqual(300, $ttl);
        $this->assertGreaterThan(240, $ttl); // More than 4 minutes
    }

    /**
     * Test mark_pantau_down() sets 30 minute backoff for attempt 7+
     *
     * @test
     */
    public function test_mark_pantau_down_sets_30_minute_backoff_for_attempt_7_plus()
    {
        mark_pantau_down(7);
        
        $this->assertTrue(Cache::has('pantau_server_down'));
        
        // Verify cache expires within 30 minutes (1800 seconds)
        $ttl = Cache::getStore()->connection()->ttl('pantau_server_down');
        $this->assertLessThanOrEqual(1800, $ttl);
        $this->assertGreaterThan(1740, $ttl); // More than 29 minutes
    }

    /**
     * Test mark_pantau_down() stores attempt count
     *
     * @test
     */
    public function test_mark_pantau_down_stores_attempt_count()
    {
        mark_pantau_down(3);
        
        $this->assertEquals(3, get_pantau_attempt_count());
    }

    /**
     * Test get_pantau_attempt_count() returns 0 when no attempts recorded
     *
     * @test
     */
    public function test_get_pantau_attempt_count_returns_0_initially()
    {
        $this->assertEquals(0, get_pantau_attempt_count());
    }

    /**
     * Test reset_pantau_status() clears cache
     *
     * @test
     */
    public function test_reset_pantau_status_clears_cache()
    {
        // Setup: mark pantau as down
        mark_pantau_down(2);
        $this->assertTrue(pantau_is_down());
        
        // Reset status
        reset_pantau_status();
        
        // Verify both cache keys are cleared
        $this->assertFalse(pantau_is_down());
        $this->assertEquals(0, get_pantau_attempt_count());
    }

    /**
     * Test reset_pantau_status() handles empty cache gracefully
     *
     * @test
     */
    public function test_reset_pantau_status_handles_empty_cache()
    {
        // Should not throw exception when cache is empty
        $this->assertTrue(true); // If no exception thrown, test passes
        reset_pantau_status();
        $this->assertFalse(pantau_is_down());
    }

    /**
     * Test circuit breaker lifecycle: mark down -> wait -> check down -> reset
     *
     * @test
     */
    public function test_circuit_breaker_lifecycle()
    {
        // Step 1: Initially should be up
        $this->assertFalse(pantau_is_down());
        
        // Step 2: Mark as down after 2 failed attempts
        mark_pantau_down(2);
        $this->assertTrue(pantau_is_down());
        $this->assertEquals(2, get_pantau_attempt_count());
        
        // Step 3: Reset when connection restored
        reset_pantau_status();
        $this->assertFalse(pantau_is_down());
        $this->assertEquals(0, get_pantau_attempt_count());
    }

    /**
     * Test escalating backoff: attempt count increases
     *
     * @test
     * @dataProvider attemptBackoffProvider
     */
    public function test_escalating_backoff($attempt, $expectedMinSeconds, $expectedMaxSeconds)
    {
        Cache::flush();
        mark_pantau_down($attempt);
        
        $ttl = Cache::getStore()->connection()->ttl('pantau_server_down');
        $this->assertGreaterThanOrEqual($expectedMinSeconds, $ttl);
        $this->assertLessThanOrEqual($expectedMaxSeconds, $ttl);
    }

    /**
     * Data provider for escalating backoff test
     */
    public function attemptBackoffProvider(): array
    {
        return [
            'attempt 1' => [1, 50, 60],      // 1 minute
            'attempt 3' => [3, 50, 60],      // 1 minute
            'attempt 4' => [4, 290, 300],    // 5 minutes
            'attempt 6' => [6, 290, 300],    // 5 minutes
            'attempt 7' => [7, 1790, 1800],  // 30 minutes
            'attempt 10' => [10, 1790, 1800], // 30 minutes
        ];
    }
}
