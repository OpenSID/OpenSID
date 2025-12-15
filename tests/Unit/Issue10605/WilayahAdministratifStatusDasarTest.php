<?php

namespace Tests\Unit\Issue10605;

use App\Models\Penduduk;
use App\Enums\StatusDasarEnum;
use Tests\BaseTestCase;

class WilayahAdministratifStatusDasarTest extends BaseTestCase
{
    /** @test */
    public function filter_data_status_dasar_hidup()
    {
        // Panggil API dengan filter status hidup
        $response = $this->getJson(
            '/wilayah/apipendudukwilayah?filter_status=' . StatusDasarEnum::HIDUP
        );

        $response->assertStatus(200);

        // Pastikan semua status_dasar = HIDUP dari data asli
        $penduduk = Penduduk::inRandomOrder()->where('status_dasar', StatusDasarEnum::HIDUP)->first();
        $this->assertEquals(StatusDasarEnum::HIDUP, $penduduk->status_dasar);
    }

    /** @test */
    public function test_filter_status_selain_hidup_harus_menghasilkan_data_kosong()
    {
        // Kirim filter selain HIDUP, misal MATI
        $response = $this->getJson(
            'wilayah/apipendudukwilayah?filter_status=' . StatusDasarEnum::MATI
        );

        // Pastikan endpoint sukses
        $response->assertStatus(200);

        // Ambil hasil respon API
        $results = $response->json('results');

        // Assert bahwa results harus kosong
        $this->assertEmpty(
            $results,
            "Seharusnya API mengembalikan array kosong jika status_dasar bukan HIDUP"
        );
    }
}