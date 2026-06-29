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

use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan tidak ada N+1 Query Problem pada Keluarga
 *
 * Issue: #10442
 * Fix: Menambahkan eager loading pada method sumberData() di Keluarga controller
 *
 * Eager loading yang ditambahkan:
 * - wilayah
 * - kepalaKeluarga
 * - kepalaKeluarga.keluarga
 * - kepalaKeluarga.keluarga.wilayah
 * - kepalaKeluarga.rtm
 */
class KeluargaEagerLoadingTest extends BaseTestCase
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

        // Buat tabel wilayah jika belum ada (mungkin sudah dari BaseTestCase)
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
                $table->string('kelas_sosial')->nullable();
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
        // Buat keluarga dan penduduk
        for ($i = 1; $i <= $count; $i++) {
            // Buat wilayah unik untuk setiap keluarga
            $wilayahId = DB::table('tweb_wil_clusterdesa')->insertGetId([
                'config_id' => 1,
                'dusun'     => 'Dusun Test ' . $i . '-' . uniqid('', false),
                'rw'        => str_pad($i, 3, '0', STR_PAD_LEFT),
                'rt'        => '001',
            ]);
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

            // Update RTM dengan nik_kepala
            DB::table('tweb_rtm')->where('id', $rtmId)->update(['nik_kepala' => $kepalaId]);

            // Buat anggota keluarga
            for ($j = 1; $j <= 3; $j++) {
                DB::table('tweb_penduduk')->insert([
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
            }
        }
    }

    /**
     * Test: Query dengan eager loading tidak menghasilkan N+1 problem
     *
     * Ekspektasi: Jumlah query harus konstan, tidak bertambah seiring jumlah record
     */
    public function test_keluarga_eager_loading_prevents_n_plus_one_query(): void
    {
        $this->seedData(10);

        DB::enableQueryLog();
        DB::flushQueryLog();

        // Query dengan eager loading seperti di controller
        $result = Keluarga::with([
            'wilayah',
            'kepalaKeluarga',
            'kepalaKeluarga.keluarga',
            'kepalaKeluarga.keluarga.wilayah',
            'kepalaKeluarga.rtm',
        ])->get();

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Dengan eager loading, jumlah query harus konstan (tidak N+1)
        // Ekspektasi: 1 query untuk keluarga + beberapa query untuk relasi (maksimal ~6-8 query)
        // Tanpa eager loading: 1 + (N * jumlah_relasi) query
        $this->assertLessThanOrEqual(10, $queryCount, 
            "Query count should be constant with eager loading. Got {$queryCount} queries for 10 records. " .
            "This indicates potential N+1 problem."
        );

        // Pastikan data ter-load
        $this->assertCount(10, $result);

        // Pastikan relasi sudah di-load (tidak lazy loading)
        $firstKeluarga = $result->first();
        $this->assertTrue($firstKeluarga->relationLoaded('wilayah'));
        $this->assertTrue($firstKeluarga->relationLoaded('kepalaKeluarga'));
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

        Keluarga::with([
            'wilayah',
            'kepalaKeluarga',
            'kepalaKeluarga.keluarga',
            'kepalaKeluarga.keluarga.wilayah',
            'kepalaKeluarga.rtm',
        ])->get();

        $queryCountWith5Records = count(DB::getQueryLog());

        // Reset dan test dengan 20 record
        $this->setUpDatabase();
        $this->seedData(20);

        DB::flushQueryLog();

        Keluarga::with([
            'wilayah',
            'kepalaKeluarga',
            'kepalaKeluarga.keluarga',
            'kepalaKeluarga.keluarga.wilayah',
            'kepalaKeluarga.rtm',
        ])->get();

        $queryCountWith20Records = count(DB::getQueryLog());

        // Jumlah query harus sama atau hampir sama (beda maksimal 2 karena perbedaan internal)
        $this->assertLessThanOrEqual(2, abs($queryCountWith20Records - $queryCountWith5Records),
            "Query count should remain constant regardless of record count. " .
            "5 records: {$queryCountWith5Records} queries, 20 records: {$queryCountWith20Records} queries."
        );
    }
}
