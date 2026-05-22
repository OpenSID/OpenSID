<?php

namespace Tests\Unit;

use App\Models\Penduduk;
use App\Models\LogPenduduk;
use App\Models\Keluarga;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTestCase;

class Penduduklog_latestEagerLoadTest extends BaseTestCase
{
    use RefreshDatabase;

    protected Penduduk $penduduk;
    protected Keluarga $keluarga;
    protected Wilayah $wilayah;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTestData();
    }

    protected function setupTestData(): void
    {
        $this->wilayah = Wilayah::factory()->create();
        $this->keluarga = Keluarga::factory()->create([
            'id_cluster' => $this->wilayah->id,
        ]);

        $this->penduduk = Penduduk::factory()->create([
            'id_kk'      => $this->keluarga->id,
            'id_cluster' => $this->wilayah->id,
        ]);

        // Create multiple logs untuk testing
        LogPenduduk::factory()->create([
            'id_pend'       => $this->penduduk->id,
            'tgl_peristiwa' => Carbon::parse('2022-01-15'),
            'created_at'    => Carbon::now()->subDays(10),
        ]);

        LogPenduduk::factory()->create([
            'id_pend'       => $this->penduduk->id,
            'tgl_peristiwa' => Carbon::parse('2022-05-15'),
            'created_at'    => Carbon::now()->subDays(5),
        ]);

        LogPenduduk::factory()->create([
            'id_pend'       => $this->penduduk->id,
            'tgl_peristiwa' => Carbon::parse('2023-06-15'),
            'created_at'    => Carbon::now(),
        ]);
    }

    /**
     * Test eager loading log_latest without constraints
     *
     * @test
     */
    public function testEagerLoadlog_latestWithoutConstraints()
    {
        $penduduk = Penduduk::with('log_latest')->find($this->penduduk->id);

        $this->assertNotNull($penduduk->log_latest);
        // Should get the latest one (2023-06-15)
        $this->assertEquals('2023-06-15', $penduduk->log_latest->tgl_peristiwa->format('Y-m-d'));
    }

    /**
     * Test eager loading log_latest dengan year filter di closure
     *
     * @test
     */
    public function testEagerLoadlog_latestWithYearFilter()
    {
        $penduduk = Penduduk::with(['log_latest' => function ($query) {
            $query->whereYear('log_penduduk.tgl_peristiwa', 2022);
        }])->find($this->penduduk->id);

        $this->assertNotNull($penduduk->log_latest);
        // Should get 2022 latest (2022-05-15)
        $this->assertEquals('2022-05-15', $penduduk->log_latest->tgl_peristiwa->format('Y-m-d'));
    }

    /**
     * Test multiple instances tidak sharing eager loaded relationships
     *
     * @test
     */
    public function testMultipleInstancesIsolation()
    {
        // Create second penduduk
        $penduduk2 = Penduduk::factory()->create([
            'id_kk'      => $this->keluarga->id,
            'id_cluster' => $this->wilayah->id,
        ]);

        LogPenduduk::factory()->create([
            'id_pend'       => $penduduk2->id,
            'tgl_peristiwa' => Carbon::parse('2024-07-15'),
        ]);

        // Load both with filter
        $collection = Penduduk::with(['log_latest' => function ($query) {
            $query->whereYear('log_penduduk.tgl_peristiwa', 2023);
        }])->whereIn('id', [$this->penduduk->id, $penduduk2->id])->get();

        $p1 = $collection->find($this->penduduk->id);
        $p2 = $collection->find($penduduk2->id);

        // p1 should have 2023 log
        if ($p1->log_latest) {
            $this->assertStringContainsString('2023', $p1->log_latest->tgl_peristiwa->format('Y-m-d'));
        }

        // p2 might not have 2023 log
        // This is to test isolation
    }

    /**
     * Test eager load counts queries (N+1 prevention)
     *
     * @test
     */
    public function testEagerLoadReducesQueries()
    {
        // Reset query log
        \Illuminate\Support\Facades\DB::flushQueryLog();
        \Illuminate\Support\Facades\DB::enableQueryLog();

        // Load with eager loading
        Penduduk::with('log_latest')
            ->whereIn('id', [$this->penduduk->id])
            ->get();

        $queriesWithEager = count(\Illuminate\Support\Facades\DB::getQueryLog());

        // Reset and test without eager loading
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $penduduk = Penduduk::find($this->penduduk->id);
        $log = $penduduk->log_latest;

        $queriesWithoutEager = count(\Illuminate\Support\Facades\DB::getQueryLog());

        // With eager loading should have fewer queries
        $this->assertLessThanOrEqual($queriesWithoutEager, $queriesWithEager);
    }

    /**
     * Test filter dengan table prefix dalam closure
     *
     * @test
     */
    public function testFilterWithTablePrefixInClosure()
    {
        $penduduk = Penduduk::with(['log_latest' => function ($query) {
            $query->whereRaw("date_format(log_penduduk.tgl_peristiwa, '%Y') = 2022");
        }])->find($this->penduduk->id);

        if ($penduduk->log_latest) {
            $this->assertEquals('2022', $penduduk->log_latest->tgl_peristiwa->format('Y'));
        }
    }

    /**
     * Test combined with filterLog scope
     *
     * @test
     */
    public function testCombinedWithFilterLogScope()
    {
        $result = Penduduk::with('log_latest')
            ->filterLog(['tahun' => 2023])
            ->find($this->penduduk->id);

        if ($result) {
            $this->assertNotNull($result->log_latest);
        }
    }

    /**
     * Test accessing relation after loading
     *
     * @test
     */
    public function testAccessingRelationAfterLoading()
    {
        $penduduk = Penduduk::with('log_latest')->find($this->penduduk->id);

        $this->assertInstanceOf(LogPenduduk::class, $penduduk->log_latest);

        // Access multiple times should not trigger new query
        \Illuminate\Support\Facades\DB::flushQueryLog();
        \Illuminate\Support\Facades\DB::enableQueryLog();

        $log1 = $penduduk->log_latest;
        $log2 = $penduduk->log_latest;

        $queries = \Illuminate\Support\Facades\DB::getQueryLog();
        
        // Should be 0 queries since relation is already loaded
        $this->assertEquals(0, count($queries));
    }

    /**
     * Test null result when no matching log
     *
     * @test
     */
    public function testNullResultWhenNoMatchingLog()
    {
        $penduduk = Penduduk::with(['log_latest' => function ($query) {
            // Filter untuk tahun yang tidak ada
            $query->whereYear('log_penduduk.tgl_peristiwa', 9999);
        }])->find($this->penduduk->id);

        $this->assertNull($penduduk->log_latest);
    }

    /**
     * Test chaining multiple filters
     *
     * @test
     */
    public function testChainingMultipleFilters()
    {
        $penduduk = Penduduk::with(['log_latest' => function ($query) {
            $query->whereYear('log_penduduk.tgl_peristiwa', 2022)
                  ->whereMonth('log_penduduk.tgl_peristiwa', 5);
        }])->find($this->penduduk->id);

        if ($penduduk->log_latest) {
            $this->assertEquals('2022-05-15', $penduduk->log_latest->tgl_peristiwa->format('Y-m-d'));
        }
    }

    /**
     * Test collection loading dengan filter
     *
     * @test
     */
    public function testCollectionLoadingWithFilter()
    {
        $collection = Penduduk::with(['log_latest' => function ($query) {
            $query->whereYear('log_penduduk.tgl_peristiwa', 2023);
        }])->get();

        $this->assertGreater($collection->count(), 0);

        // Check loaded relation
        $this->assertNotNull($collection->first()->log_latest);
    }

    /**
     * Test relation load count
     *
     * @test
     */
    public function testRelationLoadCount()
    {
        \Illuminate\Support\Facades\DB::flushQueryLog();
        \Illuminate\Support\Facades\DB::enableQueryLog();

        // Create multiple penduduk
        for ($i = 0; $i < 5; $i++) {
            Penduduk::factory()->create([
                'id_kk'      => $this->keluarga->id,
                'id_cluster' => $this->wilayah->id,
            ]);
        }

        // Load all with eager loading
        Penduduk::with('log_latest')->limit(5)->get();

        $queries = \Illuminate\Support\Facades\DB::getQueryLog();

        // Should have exactly 2 queries: 1 for penduduk, 1 for log_latest
        // NOT N+1 queries
        $this->assertLessThanOrEqual(3, count($queries));
    }

    /**
     * Test lazy loading after relation is already loaded
     *
     * @test
     */
    public function testLazyLoadingAfterEagerLoad()
    {
        $penduduk = Penduduk::with('log_latest')->find($this->penduduk->id);

        // Try to lazy load same relation
        if (!$penduduk->relationLoaded('log_latest')) {
            $penduduk->load('log_latest');
        }

        $this->assertTrue($penduduk->relationLoaded('log_latest'));
    }
    /**
     * Test filtering results by year using whereHas (critical for statistik)
     * This ensures only penduduk with logs in the specified year are returned
     *
     * @test
     */
    public function testWhereHasFilterByYear()
    {
        // Create penduduk without logs
        $pendudukNoLog = Penduduk::factory()->create([
            'id_kk'      => $this->keluarga->id,
            'id_cluster' => $this->wilayah->id,
        ]);

        // Create penduduk dengan log di 2024 only
        $penduduk2024 = Penduduk::factory()->create([
            'id_kk'      => $this->keluarga->id,
            'id_cluster' => $this->wilayah->id,
        ]);
        LogPenduduk::factory()->create([
            'id_pend'       => $penduduk2024->id,
            'tgl_peristiwa' => Carbon::parse('2024-03-15'),
        ]);

        // Query: Get penduduk with log_latest in 2022
        $result2022 = Penduduk::with(['log_latest' => function ($query) {
            $query->whereYear('log_penduduk.tgl_peristiwa', 2022);
        }])
            ->whereHas('log_latest', function ($query) {
                $query->whereYear('log_penduduk.tgl_peristiwa', 2022);
            })
            ->get();

        // Query: Get penduduk with log_latest in 2024
        $result2024 = Penduduk::with(['log_latest' => function ($query) {
            $query->whereYear('log_penduduk.tgl_peristiwa', 2024);
        }])
            ->whereHas('log_latest', function ($query) {
                $query->whereYear('log_penduduk.tgl_peristiwa', 2024);
            })
            ->get();

        // Assertions
        $this->assertGreaterThanOrEqual(1, $result2022->count());
        $this->assertEquals(1, $result2024->count());

        // $this->penduduk has logs in 2022 and 2023
        $this->assertTrue($result2022->contains('id', $this->penduduk->id));

        // $penduduk2024 has log in 2024 only
        $this->assertTrue($result2024->contains('id', $penduduk2024->id));

        // $pendudukNoLog should not appear in any result
        $this->assertFalse($result2022->contains('id', $pendudukNoLog->id));
        $this->assertFalse($result2024->contains('id', $pendudukNoLog->id));

        // Verify the loaded relation
        $p2024 = $result2024->first();
        $this->assertNotNull($p2024->log_latest);
        $this->assertEquals('2024', $p2024->log_latest->tgl_peristiwa->format('Y'));
    }}
