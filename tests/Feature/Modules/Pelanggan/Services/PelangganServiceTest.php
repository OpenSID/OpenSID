<?php

namespace Tests\Feature\Modules\Pelanggan\Services;

use App\Repositories\SettingAplikasiRepository;
use App\Services\Layanan\TokenDecoder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Mockery;
use Modules\Pelanggan\Services\PelangganService;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class PelangganServiceTest extends BaseTestCase
{
    private PelangganService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $repo = Mockery::mock(SettingAplikasiRepository::class);
        $repo->shouldReceive('resolveSetting')
            ->byDefault()
            ->andReturn((object) ['layanan_opendesa_token' => null]);
        app()->instance(SettingAplikasiRepository::class, $repo);

        Config::set('opensid.demo_mode', false);

        $this->service = new PelangganService();
    }

    protected function tearDown(): void
    {
        Cache::store('subscription')->forget('status_langganan');
        parent::tearDown();
    }

    private function mockSetting(string $token): void
    {
        $repo = Mockery::mock(SettingAplikasiRepository::class);
        $repo->shouldReceive('resolveSetting')
            ->andReturn((object) ['layanan_opendesa_token' => $token]);
        // SettingAplikasi::boot() memicu forgetResolvedSetting() lewat event `updated`
        // setiap kali baris setting benar-benar tersimpan. Tanpa ekspektasi ini,
        // Mockery melempar BadMethodCallException yang ikut tertelan blok catch.
        $repo->shouldReceive('forgetResolvedSetting')
            ->andReturnNull();
        app()->instance(SettingAplikasiRepository::class, $repo);
    }

    #[Test]
    public function api_pelanggan_pemesanan_returns_null_when_token_empty(): void
    {
        $result = PelangganService::apiPelangganPemesanan();

        $this->assertNull($result);
        $this->assertTrue(Session::has('error_status_langganan'));
    }

    #[Test]
    public function api_pelanggan_pemesanan_returns_cached_data_when_cache_hit(): void
    {
        $this->mockSetting('valid-token');

        $cachedData = (object) ['body' => 'test'];
        Cache::store('subscription')->put('status_langganan', $cachedData);

        $result = PelangganService::apiPelangganPemesanan();

        $this->assertEquals($cachedData, $result);
    }

    #[Test]
    public function perbarui_langganan_skips_when_controller_is_pengguna(): void
    {
        $this->mockSetting('valid-token');
        Config::set('opensid.demo_mode', false);
        Http::fake();

        PelangganService::perbaruiLangganan('pengguna', true);

        Http::assertNothingSent();
    }

    #[Test]
    public function perbarui_langganan_skips_when_demo_mode(): void
    {
        $this->mockSetting('valid-token');
        Config::set('opensid.demo_mode', true);
        Http::fake();

        PelangganService::perbaruiLangganan('beranda', true);

        Http::assertNothingSent();
    }

    #[Test]
    public function perbarui_langganan_makes_api_call_when_conditions_met(): void
    {
        $originalEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->mockSetting('valid-token');
            Config::set('opensid.demo_mode', false);
            Config::set('services.opendesa.server_layanan', 'https://layanan.example.com');

            Http::fake([
                'https://layanan.example.com/*' => Http::response(['token' => 'new-token', 'desa_id' => '12.34.56.7890'], 200),
            ]);

            PelangganService::perbaruiLangganan('beranda', true);

            Http::assertSent(function ($request) {
                return $request->url() === 'https://layanan.example.com/api/v1/pelanggan/pemesanan';
            });
        } finally {
            $this->app['env'] = $originalEnv;
        }
    }

    #[Test]
    public function perbarui_langganan_does_not_call_api_when_flag_false(): void
    {
        $this->mockSetting('valid-token');
        Config::set('opensid.demo_mode', false);

        PelangganService::perbaruiLangganan('beranda', false);

        Http::assertNothingSent();
    }

    #[Test]
    public function perbarui_langganan_returns_early_when_token_empty(): void
    {
        Http::fake();

        PelangganService::perbaruiLangganan('beranda', true);

        Http::assertNothingSent();
    }

    #[Test]
    public function perbarui_langganan_returns_early_when_backoff_key_exists(): void
    {
        $this->mockSetting('valid-token');
        Config::set('opensid.demo_mode', false);

        $tokenHash = md5('valid-token');
        Cache::put("perbarui-langganan-backoff:{$tokenHash}", true, 60);

        Http::fake();

        PelangganService::perbaruiLangganan('beranda', true);

        Http::assertNothingSent();
    }

    #[Test]
    public function perbarui_langganan_sets_backoff_key_on_429(): void
    {
        $originalEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->mockSetting('valid-token');
            Config::set('opensid.demo_mode', false);
            Config::set('services.opendesa.server_layanan', 'https://layanan.example.com');

            Http::fake([
                'https://layanan.example.com/*' => Http::response('Too Many Requests', 429, ['Retry-After' => '30']),
            ]);

            PelangganService::perbaruiLangganan('beranda', true);

            $tokenHash = md5('valid-token');
            $this->assertTrue(Cache::has("perbarui-langganan-backoff:{$tokenHash}"));
        } finally {
            $this->app['env'] = $originalEnv;
        }
    }

    #[Test]
    public function perbarui_langganan_sets_backoff_on_401(): void
    {
        $originalEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->mockSetting('valid-token');
            Config::set('opensid.demo_mode', false);
            Config::set('services.opendesa.server_layanan', 'https://layanan.example.com');

            Http::fake([
                'https://layanan.example.com/*' => Http::response('Unauthorized', 401),
            ]);

            PelangganService::perbaruiLangganan('beranda', true);

            $tokenHash = md5('valid-token');
            $this->assertTrue(Cache::has("perbarui-langganan-backoff:{$tokenHash}"));
        } finally {
            $this->app['env'] = $originalEnv;
        }
    }

    #[Test]
    public function perbarui_langganan_sets_backoff_on_500(): void
    {
        $originalEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->mockSetting('valid-token');
            Config::set('opensid.demo_mode', false);
            Config::set('services.opendesa.server_layanan', 'https://layanan.example.com');

            Http::fake([
                'https://layanan.example.com/*' => Http::response('Internal Server Error', 500),
            ]);

            PelangganService::perbaruiLangganan('beranda', true);

            Http::assertSent(function ($request) {
                return $request->url() === 'https://layanan.example.com/api/v1/pelanggan/pemesanan';
            });

            $tokenHash = md5('valid-token');
            $this->assertTrue(Cache::has("perbarui-langganan-backoff:{$tokenHash}"));
        } finally {
            $this->app['env'] = $originalEnv;
        }
    }

    #[Test]
    public function perbarui_langganan_handles_generic_exception_gracefully(): void
    {
        $originalEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->mockSetting('valid-token');
            Config::set('opensid.demo_mode', false);
            Config::set('services.opendesa.server_layanan', 'https://layanan.example.com');

            Http::fake(function () {
                throw new \Exception('Connection timeout');
            });

            PelangganService::perbaruiLangganan('beranda', true);

            $tokenHash = md5('valid-token');
            $this->assertTrue(Cache::has("perbarui-langganan-backoff:{$tokenHash}"));
        } finally {
            $this->app['env'] = $originalEnv;
        }
    }

    #[Test]
    public function get_layanan_aktif_tier_returns_umum_when_no_response(): void
    {
        $this->mockSetting('valid-token');

        $result = $this->service->getLayananAktifTier();

        $this->assertSame('umum', $result);
    }

    #[Test]
    public function get_layanan_aktif_tier_returns_umum_when_response_empty(): void
    {
        Cache::store('subscription')->put('status_langganan', (object) ['body' => (object) []]);

        $result = $this->service->getLayananAktifTier();

        $this->assertSame('umum', $result);
    }

    #[Test]
    public function get_layanan_aktif_tier_returns_siappakai_when_siap_pakai_active(): void
    {
        $this->mockSetting('valid-token');
        $futureDate = now()->addYear()->format('Y-m-d');
        $data = (object) [
            'body' => (object) [
                'pemesanan' => [
                    (object) [
                        'layanan' => [
                            (object) [
                                'kategori_id' => PelangganService::KATEGORI_SIAPPAKAI,
                                'tanggal_akhir' => $futureDate,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        Cache::store('subscription')->put('status_langganan', $data);

        $result = $this->service->getLayananAktifTier();

        $this->assertSame('siappakai', $result);
    }

    #[Test]
    public function get_layanan_aktif_tier_returns_premium_when_only_premium_active(): void
    {
        $this->mockSetting('valid-token');
        $futureDate = now()->addYear()->format('Y-m-d');
        $data = (object) [
            'body' => (object) [
                'pemesanan' => [
                    (object) [
                        'layanan' => [
                            (object) [
                                'kategori_id' => PelangganService::KATEGORI_PREMIUM,
                                'tanggal_akhir' => $futureDate,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        Cache::store('subscription')->put('status_langganan', $data);

        $result = $this->service->getLayananAktifTier();

        $this->assertSame('premium', $result);
    }

    #[Test]
    public function get_layanan_aktif_tier_ignores_expired_services(): void
    {
        $pastDate = now()->subDay()->format('Y-m-d');
        $data = (object) [
            'body' => (object) [
                'pemesanan' => [
                    (object) [
                        'layanan' => [
                            (object) [
                                'kategori_id' => PelangganService::KATEGORI_SIAPPAKAI,
                                'tanggal_akhir' => $pastDate,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        Cache::store('subscription')->put('status_langganan', $data);

        $result = $this->service->getLayananAktifTier();

        $this->assertSame('umum', $result);
    }

    #[Test]
    public function refresh_langganan_menggunakan_token_input_dari_browser(): void
    {
        $originalEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->mockSetting('token-lama');
            Config::set('opensid.demo_mode', false);
            Config::set('services.opendesa.server_layanan', 'https://layanan.example.com');

            // Sediakan identitas desa agar tes tetap jalan saat DB kosong. Nilai
            // desa_id respons TIDAK boleh di-hardcode: saat suite penuh dijalankan,
            // identitas() terisi data desa dari DB, bukan dari cache ini.
            Cache::put('identitas_desa', (object) [
                'kode_desa' => '1234567890',
                'nama_desa' => 'Desa Test',
                'id' => 1,
            ], 600);

            // pemesanan() menolak respons yang desa_id-nya tidak sama dengan kode
            // desa lokal, jadi respons palsu harus mengikuti identitas yang aktif.
            $desaId = kode_wilayah(identitas()->kode_desa);

            // LayananTokenRepository::simpan() memverifikasi struktur JWT sebelum
            // menyimpan token baru — token uji ini bukan JWT asli, jadi TokenDecoder
            // di-mock supaya pemesanan() benar-benar sampai menyimpan token.
            $decoder = Mockery::mock(TokenDecoder::class);
            $decoder->shouldReceive('decode')
                ->with('token-baru')
                ->andReturn((object) []);
            app()->instance(TokenDecoder::class, $decoder);

            $authorizationHeader = null;

            Http::fake([
                'https://layanan.example.com/*' => function ($request) use (&$authorizationHeader, $desaId) {
                    $authorizationHeader = $request->header('Authorization');

                    return Http::response(['token' => 'token-baru', 'desa_id' => $desaId], 200);
                },
            ]);

            $result = PelangganService::refreshLangganan('token-baru-dari-browser');

            $this->assertTrue($result);
            $this->assertSame(['Bearer token-baru-dari-browser'], $authorizationHeader);
        } finally {
            Cache::forget('identitas_desa');
            $this->app['env'] = $originalEnv;
        }
    }

    #[Test]
    public function status_percobaan_returns_null_when_token_empty(): void
    {
        $result = PelangganService::statusPercobaan();

        $this->assertNull($result);
    }
}
