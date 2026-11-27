<?php

namespace Tests\Feature\Api\Analisis;

use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisParameter;
use Tests\Traits\RefreshDatabase;
use Tests\BaseTestCase;

class AnalisisJawabanTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_jawaban_endpoint_returns_successful_response(): void
    {
        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id]);

        $response = $this->getJson(route('api.analisis.jawaban'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'id', 'id_indikator', 'jawaban', 'kode_jawaban',
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

    public function test_jawaban_endpoint_can_filter_by_id_indikator(): void
    {
        $indikator = AnalisisIndikator::factory()->create();
        $other = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id]);
        AnalisisParameter::factory()->create(['id_indikator' => $other->id]);

        $response = $this->getJson(route('api.analisis.jawaban', ['filter' => ['id_indikator' => $indikator->id], 'page' => ['size' => 100]]));

        $response->assertOk();
        foreach ($response->json('data') as $item) {
            $this->assertEquals($indikator->id, $item['attributes']['id_indikator']);
        }
    }

    public function test_jawaban_endpoint_supports_search_filter(): void
    {
        $indikator = AnalisisIndikator::factory()->create(['is_publik' => true]);
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id, 'jawaban' => 'UniqueAnswer']);
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id, 'jawaban' => 'OtherAnswer']);

        $response = $this->getJson(route('api.analisis.jawaban', ['filter' => ['search' => 'UniqueAnswer'], 'page' => ['size' => 100]]));

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('UniqueAnswer', $response->json('data.0.attributes.jawaban'));
    }

    public function test_jawaban_endpoint_supports_sorting(): void
    {
        AnalisisParameter::where('id', '>', 0)->delete();

        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id, 'kode_jawaban' => 2, 'jawaban' => 'Beta']);
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id, 'kode_jawaban' => 1, 'jawaban' => 'Alpha']);

        $response = $this->getJson(route('api.analisis.jawaban', ['sort' => 'jawaban', 'page' => ['size' => 100]]));

        $response->assertOk();
        $jawaban = collect($response->json('data'))->pluck('attributes.jawaban');
        $this->assertEquals($jawaban->sort()->values()->toArray(), $jawaban->toArray());
    }

    public function test_jawaban_endpoint_supports_pagination(): void
    {
        AnalisisParameter::where('id', '>', 0)->delete();

        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->count(3)->create(['id_indikator' => $indikator->id]);

        $response = $this->getJson(route('api.analisis.jawaban', ['page' => ['size' => 1, 'number' => 1]]));

        $response->assertOk();
        $meta = $response->json('meta');
        $this->assertEquals(1, $meta['per_page']);
        $this->assertEquals(3, $meta['total']);
        $this->assertEquals(3, $meta['last_page']);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_jawaban_endpoint_returns_correct_resource_type(): void
    {
        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id]);

        $response = $this->getJson(route('api.analisis.jawaban'));

        $response->assertOk();
        foreach ($response->json('data') as $item) {
            $this->assertEquals('analisis-jawaban', $item['type']);
            $this->assertIsString($item['id']);
        }
    }

    public function test_jawaban_endpoint_returns_empty_data_when_no_match(): void
    {
        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id]);

        $response = $this->getJson(route('api.analisis.jawaban', ['filter' => ['id_indikator' => 999999]]));

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    public function test_jawaban_endpoint_sql_injection_is_safe(): void
    {
        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id]);

        $response = $this->getJson(route('api.analisis.jawaban', ['filter' => ['id_periode' => "1' OR '1'='1"], 'page' => ['size' => 100]]));

        $response->assertStatus(422);
    }

    public function test_jawaban_endpoint_supports_post_method(): void
    {
        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id]);

        $response = $this->postJson(route('api.analisis.jawaban'));

        $response->assertOk();
    }

    public function test_jawaban_endpoint_pagination_contains_legacy_format(): void
    {
        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->create(['id_indikator' => $indikator->id]);

        $response = $this->getJson(route('api.analisis.jawaban'));

        $meta = $response->json('meta');
        $this->assertArrayHasKey('pagination', $meta);
        $this->assertArrayHasKey('deprecated', $meta);
        $this->assertIsArray($meta['deprecated']);
        $this->assertArrayHasKey('pagination', $meta['deprecated']);
    }

    public function test_jawaban_endpoint_has_jsonapi_links(): void
    {
        AnalisisParameter::where('id', '>', 0)->delete();

        $indikator = AnalisisIndikator::factory()->create();
        AnalisisParameter::factory()->count(3)->create(['id_indikator' => $indikator->id]);

        $response = $this->getJson(route('api.analisis.jawaban', ['page' => ['size' => 1]]));

        $response->assertOk();
        $links = $response->json('links');
        $this->assertArrayHasKey('first', $links);
        $this->assertArrayHasKey('last', $links);
        $this->assertArrayHasKey('prev', $links);
        $this->assertArrayHasKey('next', $links);
    }
}
