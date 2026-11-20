<?php

namespace Tests\Feature\WilayahAdministratif;

use App\Enums\StatusDasarEnum;
use App\Models\Penduduk;
use App\Models\User;
use Tests\Traits\RefreshDatabase;
use Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\Test;

class WilayahAdministratifStatusDasarTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function filter_data_status_dasar_hidup()
    {
        $response = $this->actingAs(User::first())
            ->withHeader('x-requested-with', 'XMLHttpRequest')
            ->getJson(
                '/wilayah/apipendudukwilayah?filter_status=' . StatusDasarEnum::HIDUP
            );

        $response->assertStatus(200);

        $results = $response->json('results');
        $this->assertNotEmpty($results);

        $ids = collect($results)->pluck('id');
        $statusLain = Penduduk::whereIn('id', $ids)
            ->where('status_dasar', '!=', StatusDasarEnum::HIDUP)
            ->exists();

        $this->assertFalse($statusLain, 'Terdapat penduduk dengan status bukan HIDUP dalam hasil filter');
    }

    #[Test]
    public function filter_data_status_dasar_mati()
    {
        $response = $this->actingAs(User::first())
            ->withHeader('x-requested-with', 'XMLHttpRequest')
            ->getJson(
                '/wilayah/apipendudukwilayah?filter_status=' . StatusDasarEnum::MATI
            );

        $response->assertStatus(200);

        $results = $response->json('results');
        $this->assertNotEmpty($results);

        $ids = collect($results)->pluck('id');
        $statusLain = Penduduk::whereIn('id', $ids)
            ->where('status_dasar', '!=', StatusDasarEnum::MATI)
            ->exists();

        $this->assertFalse($statusLain, 'Terdapat penduduk dengan status bukan MATI dalam hasil filter');
    }

    #[Test]
    public function tanpa_filter_status_menampilkan_semua_status_dasar()
    {
        $response = $this->actingAs(User::first())
            ->withHeader('x-requested-with', 'XMLHttpRequest')
            ->getJson('/wilayah/apipendudukwilayah');

        $response->assertStatus(200);

        $results = $response->json('results');
        $this->assertNotEmpty($results);

        $ids = collect($results)->pluck('id');
        $adaHidup = Penduduk::whereIn('id', $ids)->where('status_dasar', StatusDasarEnum::HIDUP)->exists();
        $adaMati  = Penduduk::whereIn('id', $ids)->where('status_dasar', StatusDasarEnum::MATI)->exists();

        $this->assertTrue($adaHidup || $adaMati, 'Tanpa filter seharusnya menampilkan data dari berbagai status');
    }

    #[Test]
    public function filter_status_dengan_pencarian_nama()
    {
        $penduduk = Penduduk::where('status_dasar', StatusDasarEnum::HIDUP)->first();

        $response = $this->actingAs(User::first())
            ->withHeader('x-requested-with', 'XMLHttpRequest')
            ->getJson(
                '/wilayah/apipendudukwilayah?filter_status=' . StatusDasarEnum::HIDUP . '&q=' . urlencode(substr($penduduk->nama, 0, 3))
            );

        $response->assertStatus(200);

        $results = $response->json('results');
        $this->assertNotEmpty($results);

        $ids = collect($results)->pluck('id');
        $statusLain = Penduduk::whereIn('id', $ids)
            ->where('status_dasar', '!=', StatusDasarEnum::HIDUP)
            ->exists();

        $this->assertFalse($statusLain, 'Filter status harus tetap berlaku meski ada parameter pencarian');
    }

    #[Test]
    public function response_memiliki_struktur_pagination()
    {
        $response = $this->actingAs(User::first())
            ->withHeader('x-requested-with', 'XMLHttpRequest')
            ->getJson('/wilayah/apipendudukwilayah');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    '*' => ['id', 'text'],
                ],
                'pagination' => ['more'],
            ]);
    }

    #[Test]
    public function tanpa_login_redirect_ke_siteman()
    {
        $response = $this->get('/wilayah/apipendudukwilayah');

        $response->assertRedirect('siteman');
    }
}
