<?php

namespace Tests\Feature\Modules\Pelanggan;

use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class PelangganControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();
    }

    protected function tearDown(): void
    {
        Cache::forget('tema_premium');
        Cache::store('subscription')->forget('status_langganan');

        parent::tearDown();
    }

    private function validPdf(): UploadedFile
    {
        return UploadedFile::fake()->create('permohonan.pdf', 500, 'application/pdf');
    }

    private function postPerpanjang(array $data): TestResponse
    {
        $this->startSession();

        return $this->actingAs($this->user, 'admin')
            ->post('/pelanggan/perpanjang', $data);
    }

    #[Test]
    public function perpanjang_berhasil_mengirim_permohonan_dan_redirect_ke_pelanggan(): void
    {
        Cache::put('tema_premium', 'aktif', now()->addMinutes(10));

        Http::fake([
            '*/api/v1/pelanggan/perpanjang' => Http::response([
                'message' => 'Permohonan perpanjangan layanan berhasil diajukan.',
            ], 201),
        ]);

        $response = $this->postPerpanjang([
            'pemesanan_id' => 1,
            'permohonan'   => $this->validPdf(),
        ]);

        $response->assertRedirect('pelanggan');
        $response->assertSessionHas('success', 'Permohonan perpanjangan layanan berhasil diajukan.');

        $this->assertNull(Cache::get('tema_premium'));
        $this->assertNull(Cache::store('subscription')->get('status_langganan'));

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), '/api/v1/pelanggan/perpanjang')) {
                return false;
            }

            $pemesananIdPart = collect($request->data())->firstWhere('name', 'pemesanan_id');

            return $pemesananIdPart
                && (int) $pemesananIdPart['contents'] === 1
                && $request->hasFile('permohonan');
        });
    }

    #[Test]
    public function perpanjang_menampilkan_pesan_error_dari_server(): void
    {
        Http::fake([
            '*/api/v1/pelanggan/perpanjang' => Http::response([
                'message' => 'Data tidak berhasil disimpan',
                'errors'  => [
                    'pemesanan_id' => ['Pemesanan tidak ditemukan.'],
                ],
            ], 422),
        ]);

        $response = $this->postPerpanjang([
            'pemesanan_id' => 1,
            'permohonan'   => $this->validPdf(),
        ]);

        $response->assertSessionHasErrors(['pemesanan_id', 'permohonan']);

        $errors = session('errors');
        $this->assertContains('Pemesanan tidak ditemukan.', $errors->get('pemesanan_id'));
        $this->assertContains('Data tidak berhasil disimpan', $errors->get('permohonan'));
    }

    #[Test]
    public function perpanjang_menampilkan_pesan_fallback_saat_server_gagal_dihubungi(): void
    {
        Http::fake([
            '*/api/v1/pelanggan/perpanjang' => function () {
                throw new ConnectionException('Unable to connect to server_layanan');
            },
        ]);

        $response = $this->postPerpanjang([
            'pemesanan_id' => 1,
            'permohonan'   => $this->validPdf(),
        ]);

        $response->assertSessionHasErrors(['permohonan']);
        $this->assertContains(
            'Terjadi kesalahan saat memperpanjang layanan. Silakan coba lagi.',
            session('errors')->get('permohonan')
        );
    }
}
