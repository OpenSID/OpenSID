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

use App\Models\LogPenduduk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan tidak ada N+1 Query Problem pada LogPenduduk (Penduduk_log)
 *
 * Issue: #10442
 * Fix: Menambahkan eager loading pada method sumberData() di Penduduk_log controller
 *
 * Eager loading yang ditambahkan:
 * - penduduk.keluarga.kepalaKeluarga
 * - penduduk.keluarga.wilayah
 * - penduduk.wilayah
 * - penduduk.rtm
 * - keluarga
 * - pergiTerakhir
 */
class PendudukLogEagerLoadingTest extends BaseTestCase
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
                $table->timestamps();
            });
        }

        // Buat tabel log_penduduk
        if (!$schema->hasTable('log_penduduk')) {
            $schema->create('log_penduduk', function ($table) {
                $table->id();
                $table->integer('config_id')->default(1);
                $table->integer('id_pend')->nullable();
                $table->integer('kode_peristiwa')->nullable();
                $table->datetime('tgl_lapor')->nullable();
                $table->datetime('tgl_peristiwa')->nullable();
                $table->string('file_akta_mati')->nullable();
                $table->integer('id_detail')->nullable();
                $table->integer('ref_pindah')->nullable();
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
            // Buat wilayah unik untuk setiap keluarga (unik lintas pemanggilan seedData)
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
            $rtmId = DB::table('tweb_rtm')->insertGetId([
                'config_id' => 1,
                'no_kk'     => 'RTM' . str_pad($i, 4, '0', STR_PAD_LEFT),
            ]);

            // Buat kepala keluarga
            $kepalaId = DB::table('tweb_penduduk')->insertGetId([
                'config_id'    => 1,
                'nama'         => 'Kepala KK ' . $i,
                'nik'          => '350000000000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'id_kk'        => $keluargaId,
                'kk_level'     => 1,
                'id_rtm'       => $rtmId,
                'rtm_level'    => 1,
                'id_cluster'   => $wilayahId,
                'sex'          => 1,
                'status_dasar' => 1,
            ]);

            // Update keluarga dengan nik_kepala
            DB::table('tweb_keluarga')->where('id', $keluargaId)->update(['nik_kepala' => $kepalaId]);

            // Buat log penduduk untuk kepala
            DB::table('log_penduduk')->insert([
                'config_id'      => 1,
                'id_pend'        => $kepalaId,
                'kode_peristiwa' => 1, // Lahir
                'tgl_lapor'      => now(),
                'tgl_peristiwa'  => now(),
            ]);

            // Buat anggota keluarga dengan log
            for ($j = 1; $j <= 3; $j++) {
                $anggotaId = DB::table('tweb_penduduk')->insertGetId([
                    'config_id'    => 1,
                    'nama'         => 'Anggota ' . $j . ' KK ' . $i,
                    'nik'          => '350000000000' . str_pad($i, 4, '0', STR_PAD_LEFT) . $j,
                    'id_kk'        => $keluargaId,
                    'kk_level'     => $j + 1,
                    'id_rtm'       => $rtmId,
                    'rtm_level'    => $j + 1,
                    'id_cluster'   => $wilayahId,
                    'sex'          => $j % 2 == 0 ? 1 : 2,
                    'status_dasar' => 1,
                ]);

                // Buat log penduduk untuk anggota
                DB::table('log_penduduk')->insert([
                    'config_id'      => 1,
                    'id_pend'        => $anggotaId,
                    'kode_peristiwa' => 1,
                    'tgl_lapor'      => now(),
                    'tgl_peristiwa'  => now(),
                ]);
            }
        }
    }

    /**
     * Test: Query dengan eager loading tidak menghasilkan N+1 problem
     */
    public function test_log_penduduk_eager_loading_prevents_n_plus_one_query(): void
    {
        $this->seedData(10);

        DB::enableQueryLog();
        DB::flushQueryLog();

        // Query dengan eager loading seperti di controller
        $result = LogPenduduk::with([
            'penduduk.keluarga.kepalaKeluarga',
            'penduduk.keluarga.wilayah',
            'penduduk.wilayah',
            'penduduk.rtm',
            'keluarga',
            'pergiTerakhir',
        ])->get();

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Dengan eager loading, jumlah query harus konstan (tidak N+1)
        // Ekspektasi: sekitar 8-12 query untuk semua relasi
        $this->assertLessThanOrEqual(15, $queryCount,
            "Query count should be constant with eager loading. Got {$queryCount} queries. " .
            "This indicates potential N+1 problem."
        );

        // Pastikan data ter-load (10 keluarga * 4 anggota = 40 log)
        $this->assertGreaterThanOrEqual(10, $result->count());

        // Pastikan relasi sudah di-load
        $firstLog = $result->first();
        $this->assertTrue($firstLog->relationLoaded('penduduk'));
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

        LogPenduduk::with([
            'penduduk.keluarga.kepalaKeluarga',
            'penduduk.keluarga.wilayah',
            'penduduk.wilayah',
            'penduduk.rtm',
            'keluarga',
            'pergiTerakhir',
        ])->get();

        $queryCountWith5Records = count(DB::getQueryLog());

        // Reset dan test dengan 20 record
        $this->setUpDatabase();
        $this->seedData(20);

        DB::flushQueryLog();

        LogPenduduk::with([
            'penduduk.keluarga.kepalaKeluarga',
            'penduduk.keluarga.wilayah',
            'penduduk.wilayah',
            'penduduk.rtm',
            'keluarga',
            'pergiTerakhir',
        ])->get();

        $queryCountWith20Records = count(DB::getQueryLog());

        // Jumlah query harus sama atau hampir sama
        $this->assertLessThanOrEqual(3, abs($queryCountWith20Records - $queryCountWith5Records),
            "Query count should remain constant regardless of record count. " .
            "5 families: {$queryCountWith5Records} queries, 20 families: {$queryCountWith20Records} queries."
        );
    }
}
