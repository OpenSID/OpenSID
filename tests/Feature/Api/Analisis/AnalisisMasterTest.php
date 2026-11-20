<?php

namespace Tests\Feature\Api\Analisis;

use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisPeriode;
use Tests\Traits\RefreshDatabase;
use Tests\BaseTestCase;

class AnalisisMasterTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_master_endpoint_returns_successful_response(): void
    {
        $master = AnalisisMaster::factory()->create();
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        $response = $this->getJson(route('api.analisis.master'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'id', 'master', 'subjek_tipe', 'subjek', 'periode', 'tahun', 'id_periode',
                    ],
                ],
            ],
        ]);
    }

    public function test_master_endpoint_can_filter_by_id(): void
    {
        $master = AnalisisMaster::factory()->create();
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        $response = $this->getJson(route('api.analisis.master', ['filter' => ['id' => $master->id]]));

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals((string) $master->id, $response->json('data.0.id'));
    }

    public function test_master_endpoint_can_filter_by_tahun(): void
    {
        $master = AnalisisMaster::factory()->create();
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true, 'tahun_pelaksanaan' => '2020']);

        $other = AnalisisMaster::factory()->create();
        AnalisisIndikator::factory()->create(['id_master' => $other->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $other->id, 'aktif' => true, 'tahun_pelaksanaan' => '2025']);

        $response = $this->getJson(route('api.analisis.master', ['filter' => ['tahun' => '2025']]));

        $response->assertOk();
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
        foreach ($response->json('data') as $item) {
            $this->assertEquals('2025', $item['attributes']['tahun']);
        }
    }

    public function test_master_endpoint_supports_search_filter(): void
    {
        $master = AnalisisMaster::factory()->create(['nama' => 'UniqueAnalisisName']);
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        $other = AnalisisMaster::factory()->create(['nama' => 'OtherMaster']);
        AnalisisIndikator::factory()->create(['id_master' => $other->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $other->id, 'aktif' => true]);

        $response = $this->getJson(route('api.analisis.master', ['filter' => ['search' => 'UniqueAnalisisName']]));

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('UniqueAnalisisName', $response->json('data.0.attributes.master'));
    }

    public function test_master_endpoint_supports_sorting(): void
    {
        $masterA = AnalisisMaster::factory()->create(['nama' => 'Alpha']);
        AnalisisIndikator::factory()->create(['id_master' => $masterA->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $masterA->id, 'aktif' => true, 'tahun_pelaksanaan' => '2020']);

        $masterB = AnalisisMaster::factory()->create(['nama' => 'Beta']);
        AnalisisIndikator::factory()->create(['id_master' => $masterB->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $masterB->id, 'aktif' => true, 'tahun_pelaksanaan' => '2025']);

        $response = $this->getJson(route('api.analisis.master', ['sort' => '-tahun']));

        $response->assertOk();
        $tahun = collect($response->json('data'))->map(fn($item) => $item['attributes']['tahun']);
        $this->assertEquals($tahun->sortDesc()->values()->toArray(), $tahun->toArray());
    }

    public function test_master_endpoint_returns_correct_resource_type(): void
    {
        $master = AnalisisMaster::factory()->create();
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        $response = $this->getJson(route('api.analisis.master'));

        $response->assertOk();
        foreach ($response->json('data') as $item) {
            $this->assertEquals('analisis-master', $item['type']);
            $this->assertIsString($item['id']);
        }
    }

    public function test_master_endpoint_returns_empty_data_when_no_match(): void
    {
        $master = AnalisisMaster::factory()->create();
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        $response = $this->getJson(route('api.analisis.master', ['filter' => ['id' => 999999]]));

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    public function test_master_endpoint_returns_empty_when_no_publik_data(): void
    {
        $master = AnalisisMaster::factory()->create();
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => false]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        $response = $this->getJson(route('api.analisis.master'));

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }
}
