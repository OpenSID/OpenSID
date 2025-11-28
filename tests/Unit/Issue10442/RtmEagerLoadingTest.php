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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Issue10442;

use App\Models\Rtm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan tidak ada N+1 Query Problem pada RTM (Rumah Tangga)
 *
 * Issue: #10442
 * Fix: Menambahkan eager loading dengan withOnly pada method sumberData() di Rtm controller
 *
 * Eager loading yang ditambahkan menggunakan withOnly:
 * - anggota (dengan select kolom tertentu)
 * - kepalaKeluarga (dengan select kolom tertentu dan nested relations)
 * - kepalaKeluarga.keluarga
 * - kepalaKeluarga.keluarga.wilayah
 */
class RtmEagerLoadingTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /**
     * Setup database tables yang dibutuhkan untuk test
     */
    protected function setUpDatabase(): void
    {
        $schema = $this->app['db']->connection()->getSchemaBuilder();

        // Buat tabel wilayah jika belum ada
        if (!$schema->hasTable('tweb_wil_clusterdesa')) {
            $schema->create('tweb_wil_clusterdesa', function ($table) {
                $table->id();
                $table->integer('config_id')->default(1);
                $table->string('dusun')->nullable();
                $table->string('rw')->nullable();
                $table->string('rt')->nullable();
                $table->timestamps();
            });
        }

        // Buat tabel keluarga
        if (!$schema->hasTable('tweb_keluarga')) {
            $schema->create('tweb_keluarga', function ($table) {
                $table->id();
                $table->integer('config_id')->default(1);
                $table->string('no_kk')->nullable();
                $table->integer('nik_kepala')->nullable();
                $table->integer('id_cluster')->nullable();
                $table->string('alamat')->nullable();
                $table->timestamps();
            });
        }

        // Buat tabel penduduk
        if (!$schema->hasTable('tweb_penduduk')) {
            $schema->create('tweb_penduduk', function ($table) {
                $table->id();
                $table->integer('config_id')->default(1);
                $table->string('nama')->nullable();
                $table->string('nik')->nullable();
                $table->integer('id_kk')->nullable();
                $table->integer('kk_level')->nullable();
                $table->integer('id_rtm')->nullable();
                $table->integer('rtm_level')->nullable();
                $table->integer('id_cluster')->nullable();
                $table->integer('sex')->nullable();
                $table->integer('status_dasar')->default(1);
                $table->string('foto')->nullable();
                $table->timestamps();
            });
        }

        // Buat tabel RTM
        if (!$schema->hasTable('tweb_rtm')) {
            $schema->create('tweb_rtm', function ($table) {
                $table->id();
                $table->integer('config_id')->default(1);
                $table->string('no_kk')->nullable();
                $table->integer('nik_kepala')->nullable();
                $table->string('bdt')->nullable();
                $table->integer('terdaftar_dtks')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Seed data untuk testing
     */
    protected function seedData(int $count = 10): void
    {
        for ($i = 1; $i <= $count; $i++) {
            // Buat wilayah unik untuk setiap RTM (unik lintas pemanggilan seedData)
            $wilayahId = DB::table('tweb_wil_clusterdesa')->insertGetId([
                'config_id' => 1,
                'dusun'     => 'Dusun Test ' . $i . '-' . uniqid('', false),
                'rw'        => str_pad($i, 3, '0', STR_PAD_LEFT),
                'rt'        => '001',
            ]);
            // Buat keluarga
            $keluargaId = DB::table('tweb_keluarga')->insertGetId([
                'config_id'  => 1,
                'no_kk'      => '350000000000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'id_cluster' => $wilayahId,
                'alamat'     => 'Alamat Test ' . $i,
            ]);

            // Buat RTM
            $rtmNoKk = 'RTM' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $rtmId = DB::table('tweb_rtm')->insertGetId([
                'config_id' => 1,
                'no_kk'     => $rtmNoKk,
            ]);

            // Buat kepala keluarga (juga kepala RTM)
            $kepalaId = DB::table('tweb_penduduk')->insertGetId([
                'config_id'    => 1,
                'nama'         => 'Kepala RTM ' . $i,
                'nik'          => '350000000000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'id_kk'        => $keluargaId,
                'kk_level'     => 1,
                'id_rtm'       => $rtmNoKk, // RTM menggunakan no_kk sebagai foreign key
                'rtm_level'    => 1, // Kepala RTM
                'id_cluster'   => $wilayahId,
                'sex'          => 1,
                'status_dasar' => 1,
            ]);

            // Update keluarga dan RTM dengan nik_kepala
            DB::table('tweb_keluarga')->where('id', $keluargaId)->update(['nik_kepala' => $kepalaId]);
            DB::table('tweb_rtm')->where('id', $rtmId)->update(['nik_kepala' => $kepalaId]);

            // Buat anggota RTM
            for ($j = 1; $j <= 3; $j++) {
                DB::table('tweb_penduduk')->insert([
                    'config_id'    => 1,
                    'nama'         => 'Anggota ' . $j . ' RTM ' . $i,
                    'nik'          => '350000000000' . str_pad($i, 4, '0', STR_PAD_LEFT) . $j,
                    'id_kk'        => $keluargaId,
                    'kk_level'     => $j + 1,
                    'id_rtm'       => $rtmNoKk,
                    'rtm_level'    => $j + 1,
                    'id_cluster'   => $wilayahId,
                    'sex'          => $j % 2 == 0 ? 1 : 2,
                    'status_dasar' => 1,
                ]);
            }
        }
    }

    /**
     * Test: Query dengan eager loading (withOnly) tidak menghasilkan N+1 problem
     */
    public function test_rtm_eager_loading_prevents_n_plus_one_query(): void
    {
        $this->seedData(10);

        DB::enableQueryLog();
        DB::flushQueryLog();

        // Query dengan eager loading seperti di controller menggunakan withOnly
        $result = Rtm::withCount('anggota')
            ->withOnly([
                'anggota' => fn ($a) => $a->select(['id', 'id_rtm', 'nama', 'nik', 'id_kk']),
                'kepalaKeluarga' => fn ($q) => $q->select(['id', 'nama', 'nik', 'sex', 'foto', 'id_kk', 'status_dasar'])
                    ->withOnly([
                        'keluarga' => fn ($qq) => $qq->select(['id', 'id_cluster', 'alamat'])
                            ->withOnly([
                                'wilayah' => fn ($w) => $w->select(['id', 'dusun', 'rw', 'rt']),
                            ]),
                    ]),
            ])
            ->get();

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Dengan eager loading, jumlah query harus konstan (tidak N+1)
        // Ekspektasi: sekitar 5-8 query untuk semua relasi
        $this->assertLessThanOrEqual(10, $queryCount,
            "Query count should be constant with eager loading. Got {$queryCount} queries for 10 RTM records. " .
            "This indicates potential N+1 problem."
        );

        // Pastikan data ter-load
        $this->assertCount(10, $result);

        // Pastikan relasi sudah di-load
        $firstRtm = $result->first();
        $this->assertTrue($firstRtm->relationLoaded('anggota'));
        $this->assertTrue($firstRtm->relationLoaded('kepalaKeluarga'));
    }

    /**
     * Test: Verifikasi withOnly memuat nested relations dengan benar
     */
    public function test_rtm_withonly_loads_nested_relations_correctly(): void
    {
        $this->seedData(5);

        $result = Rtm::withOnly([
            'anggota' => fn ($a) => $a->select(['id', 'id_rtm', 'nama', 'nik', 'id_kk']),
            'kepalaKeluarga' => fn ($q) => $q->select(['id', 'nama', 'nik', 'sex', 'foto', 'id_kk', 'status_dasar'])
                ->withOnly([
                    'keluarga' => fn ($qq) => $qq->select(['id', 'id_cluster', 'alamat'])
                        ->withOnly([
                            'wilayah' => fn ($w) => $w->select(['id', 'dusun', 'rw', 'rt']),
                        ]),
                ]),
        ])->first();

        // Pastikan kepalaKeluarga ter-load
        $this->assertNotNull($result->kepalaKeluarga);
        $this->assertTrue($result->relationLoaded('kepalaKeluarga'));

        // Pastikan nested relation keluarga ter-load
        if ($result->kepalaKeluarga) {
            $this->assertTrue($result->kepalaKeluarga->relationLoaded('keluarga'));

            // Pastikan wilayah dari keluarga ter-load
            if ($result->kepalaKeluarga->keluarga) {
                $this->assertTrue($result->kepalaKeluarga->keluarga->relationLoaded('wilayah'));
            }
        }

        // Pastikan anggota ter-load
        $this->assertTrue($result->relationLoaded('anggota'));
        $this->assertGreaterThan(0, $result->anggota->count());
    }

    /**
     * Test: Verifikasi bahwa jumlah query tetap sama dengan data yang lebih banyak
     */
    public function test_query_count_remains_constant_with_more_data(): void
    {
        // Test dengan 5 record
        $this->seedData(5);

        DB::enableQueryLog();
        DB::flushQueryLog();

        Rtm::withCount('anggota')
            ->withOnly([
                'anggota' => fn ($a) => $a->select(['id', 'id_rtm', 'nama', 'nik', 'id_kk']),
                'kepalaKeluarga' => fn ($q) => $q->select(['id', 'nama', 'nik', 'sex', 'foto', 'id_kk', 'status_dasar'])
                    ->withOnly([
                        'keluarga' => fn ($qq) => $qq->select(['id', 'id_cluster', 'alamat'])
                            ->withOnly([
                                'wilayah' => fn ($w) => $w->select(['id', 'dusun', 'rw', 'rt']),
                            ]),
                    ]),
            ])
            ->get();

        $queryCountWith5Records = count(DB::getQueryLog());

        // Reset dan test dengan 20 record
        $this->setUpDatabase();
        $this->seedData(20);

        DB::flushQueryLog();

        Rtm::withCount('anggota')
            ->withOnly([
                'anggota' => fn ($a) => $a->select(['id', 'id_rtm', 'nama', 'nik', 'id_kk']),
                'kepalaKeluarga' => fn ($q) => $q->select(['id', 'nama', 'nik', 'sex', 'foto', 'id_kk', 'status_dasar'])
                    ->withOnly([
                        'keluarga' => fn ($qq) => $qq->select(['id', 'id_cluster', 'alamat'])
                            ->withOnly([
                                'wilayah' => fn ($w) => $w->select(['id', 'dusun', 'rw', 'rt']),
                            ]),
                    ]),
            ])
            ->get();

        $queryCountWith20Records = count(DB::getQueryLog());

        // Jumlah query harus sama atau hampir sama
        $this->assertLessThanOrEqual(2, abs($queryCountWith20Records - $queryCountWith5Records),
            "Query count should remain constant regardless of record count. " .
            "5 RTMs: {$queryCountWith5Records} queries, 20 RTMs: {$queryCountWith20Records} queries."
        );
    }

    /**
     * Test: Filter status hidup dengan whereHas tidak menyebabkan N+1
     */
    public function test_rtm_filter_by_status_does_not_cause_n_plus_one(): void
    {
        $this->seedData(10);

        DB::enableQueryLog();
        DB::flushQueryLog();

        // Query dengan filter status seperti di controller
        $result = Rtm::withCount('anggota')
            ->withOnly([
                'anggota' => fn ($a) => $a->select(['id', 'id_rtm', 'nama', 'nik', 'id_kk']),
                'kepalaKeluarga' => fn ($q) => $q->select(['id', 'nama', 'nik', 'sex', 'foto', 'id_kk', 'status_dasar'])
                    ->withOnly([
                        'keluarga' => fn ($qq) => $qq->select(['id', 'id_cluster', 'alamat'])
                            ->withOnly([
                                'wilayah' => fn ($w) => $w->select(['id', 'dusun', 'rw', 'rt']),
                            ]),
                    ]),
            ])
            ->whereHas('kepalaKeluarga', fn ($r) => $r->where('status_dasar', 1)->where('rtm_level', 1))
            ->get();

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Dengan eager loading + whereHas, query tetap harus konstan
        $this->assertLessThanOrEqual(12, $queryCount,
            "Query count with whereHas filter should remain constant. Got {$queryCount} queries."
        );

        $this->assertGreaterThan(0, $result->count());
    }
}
