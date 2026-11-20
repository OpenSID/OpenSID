<?php

namespace Tests\Feature\Api\Analisis;

use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisPeriode;
use Tests\Traits\RefreshDatabase;
use Tests\BaseTestCase;

class AnalisisIndikatorTest extends BaseTestCase
{
    use RefreshDatabase;

    private function createPublikIndikator(array $overrides = []): array
    {
        $master = AnalisisMaster::factory()->create();
        $indikator = AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true, ...$overrides]);
        $periode = AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        return compact('master', 'indikator', 'periode');
    }

    public function test_indikator_endpoint_returns_successful_response(): void
    {
        $this->createPublikIndikator();

        $response = $this->getJson(route('api.analisis.indikator'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'id', 'nomor', 'id_master', 'indikator', 'subjek', 'subjek_tipe',
                        'periode', 'tahun', 'master', 'id_periode',
                    ],
                ],
            ],
            'meta' => [
                'current_page', 'last_page', 'per_page', 'total',
                'pagination' => ['total', 'count', 'per_page', 'current_page', 'total_pages'],
                'deprecated',
            ],
        ]);
    }

    public function test_indikator_endpoint_can_filter_by_id_master(): void
    {
        $this->createPublikIndikator();
        $other = $this->createPublikIndikator();

        $response = $this->getJson(route('api.analisis.indikator', ['filter' => ['id_master' => $other['master']->id], 'page' => ['size' => 100]]));

        $response->assertOk();
        foreach ($response->json('data') as $item) {
            $this->assertEquals($other['master']->id, $item['attributes']['id_master']);
        }
    }

    public function test_indikator_endpoint_can_filter_by_tahun(): void
    {
        $this->createPublikIndikator();
        $other = $this->createPublikIndikator();
        AnalisisPeriode::where('id_master', $other['master']->id)->update(['tahun_pelaksanaan' => '2025']);

        $response = $this->getJson(route('api.analisis.indikator', ['filter' => ['tahun' => '2025'], 'page' => ['size' => 100]]));

        $response->assertOk();
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
        foreach ($response->json('data') as $item) {
            $this->assertEquals('2025', $item['attributes']['tahun']);
        }
    }

    public function test_indikator_endpoint_supports_search_filter(): void
    {
        $master = AnalisisMaster::factory()->create(['nama' => 'SearchableMaster']);
        AnalisisIndikator::factory()->create(['id_master' => $master->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $master->id, 'aktif' => true]);

        $other = AnalisisMaster::factory()->create(['nama' => 'OtherMaster']);
        AnalisisIndikator::factory()->create(['id_master' => $other->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $other->id, 'aktif' => true]);

        $response = $this->getJson(route('api.analisis.indikator', ['filter' => ['search' => 'SearchableMaster'], 'page' => ['size' => 100]]));

        $response->assertOk();
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
    }

    public function test_indikator_endpoint_supports_pagination(): void
    {
        AnalisisIndikator::where('id', '>', 0)->delete();
        AnalisisPeriode::where('id', '>', 0)->delete();

        for ($i = 0; $i < 3; $i++) {
            $this->createPublikIndikator();
        }

        $response = $this->getJson(route('api.analisis.indikator', ['page' => ['size' => 1, 'number' => 1]]));

        $response->assertOk();
        $meta = $response->json('meta');
        $this->assertEquals(1, $meta['per_page']);
        $this->assertEquals(3, $meta['total']);
        $this->assertEquals(3, $meta['last_page']);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_indikator_endpoint_supports_sorting(): void
    {
        AnalisisIndikator::where('id', '>', 0)->delete();
        AnalisisPeriode::where('id', '>', 0)->delete();

        $masterA = AnalisisMaster::factory()->create(['nama' => 'Alpha']);
        AnalisisIndikator::factory()->create(['id_master' => $masterA->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $masterA->id, 'aktif' => true, 'tahun_pelaksanaan' => '2020']);

        $masterB = AnalisisMaster::factory()->create(['nama' => 'Beta']);
        AnalisisIndikator::factory()->create(['id_master' => $masterB->id, 'is_publik' => true]);
        AnalisisPeriode::factory()->create(['id_master' => $masterB->id, 'aktif' => true, 'tahun_pelaksanaan' => '2025']);

        $response = $this->getJson(route('api.analisis.indikator', ['sort' => '-tahun', 'page' => ['size' => 100]]));

        $response->assertOk();
        $tahun = collect($response->json('data'))->pluck('attributes.tahun');
        $this->assertEquals($tahun->sortDesc()->values()->toArray(), $tahun->toArray());
    }

    public function test_indikator_endpoint_returns_correct_resource_type(): void
    {
        $this->createPublikIndikator();

        $response = $this->getJson(route('api.analisis.indikator'));

        $response->assertOk();
        foreach ($response->json('data') as $item) {
            $this->assertEquals('analisis-indikator', $item['type']);
            $this->assertIsString($item['id']);
        }
    }

    public function test_indikator_endpoint_returns_empty_data_when_no_match(): void
    {
        $this->createPublikIndikator();

        $response = $this->getJson(route('api.analisis.indikator', ['filter' => ['id_master' => 999999]]));

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    public function test_indikator_endpoint_supports_post_method(): void
    {
        $this->createPublikIndikator();

        $response = $this->postJson(route('api.analisis.indikator'));

        $response->assertOk();
    }

    public function test_indikator_endpoint_pagination_contains_legacy_format(): void
    {
        $this->createPublikIndikator();

        $response = $this->getJson(route('api.analisis.indikator'));

        $meta = $response->json('meta');
        $this->assertArrayHasKey('pagination', $meta);
        $this->assertArrayHasKey('deprecated', $meta);
        $this->assertIsArray($meta['deprecated']);
        $this->assertArrayHasKey('pagination', $meta['deprecated']);
        $this->assertEquals($meta['total'], $meta['pagination']['total']);
        $this->assertEquals($meta['current_page'], $meta['pagination']['current_page']);
    }

    public function test_indikator_endpoint_has_jsonapi_links(): void
    {
        AnalisisIndikator::where('id', '>', 0)->delete();
        AnalisisPeriode::where('id', '>', 0)->delete();

        for ($i = 0; $i < 3; $i++) {
            $this->createPublikIndikator();
        }

        $response = $this->getJson(route('api.analisis.indikator', ['page' => ['size' => 1]]));

        $response->assertOk();
        $links = $response->json('links');
        $this->assertArrayHasKey('first', $links);
        $this->assertArrayHasKey('last', $links);
        $this->assertArrayHasKey('prev', $links);
        $this->assertArrayHasKey('next', $links);
    }
}
