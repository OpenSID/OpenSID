<?php

namespace Tests\Feature\Modules\Pelanggan\Services;

use App\Repositories\SettingAplikasiRepository;
use App\Services\Layanan\Exceptions\TokenTidakValidException;
use App\Services\Layanan\TokenDecoder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Mockery;
use Modules\Pelanggan\Services\CekService;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class CekServiceTest extends BaseTestCase
{
    private CekService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*/api/v1/pelanggan/daftarhitam' => Http::response(['status' => 'ok'], 200),
        ]);

        $repo = Mockery::mock(SettingAplikasiRepository::class);
        $repo->shouldReceive('resolveSetting')
            ->byDefault()
            ->andReturn((object) ['layanan_opendesa_token' => 'test-token']);
        app()->instance(SettingAplikasiRepository::class, $repo);

        Cache::put('identitas_desa', (object) [
            'kode_desa' => '1234567890',
            'nama_desa' => 'Desa Test',
            'id'        => 1,
        ], 600);

        $this->service = new CekService();
    }

    protected function tearDown(): void
    {
        Cache::forget('identitas_desa');
        unset($_SERVER['REMOTE_ADDR']);
        parent::tearDown();
    }

    #[Test]
    public function decode_token_payload_returns_payload_on_valid_token(): void
    {
        $decoder = Mockery::mock(TokenDecoder::class);
        $decoder->shouldReceive('decode')
            ->with('valid.jwt.token')
            ->andReturn((object) ['desa_id' => '12.34.56.7890', 'domain' => 'example.com']);
        app()->instance(TokenDecoder::class, $decoder);

        $result = $this->service->dekodeToken('valid.jwt.token');

        $this->assertIsObject($result);
        $this->assertEquals('12.34.56.7890', $result->desa_id);
    }

    #[Test]
    public function decode_token_payload_returns_null_on_invalid_token(): void
    {
        $decoder = Mockery::mock(TokenDecoder::class);
        $decoder->shouldReceive('decode')
            ->with('invalid-token')
            ->andThrow(new TokenTidakValidException('Token tidak valid'));
        app()->instance(TokenDecoder::class, $decoder);

        $result = $this->service->dekodeToken('invalid-token');

        $this->assertNull($result);
    }

    #[Test]
    public function decode_token_payload_uses_app_to_resolve_token_decoder(): void
    {
        $decoder = Mockery::mock(TokenDecoder::class);
        $decoder->shouldReceive('decode')
            ->with('some-token')
            ->andReturn((object) ['desa_id' => '12.34.56.7890']);
        app()->instance(TokenDecoder::class, $decoder);

        $result = $this->service->dekodeToken('some-token');

        $this->assertIsObject($result);
        $this->assertEquals('12.34.56.7890', $result->desa_id);
    }

    #[Test]
    public function validasi_returns_true_when_excepted_controller(): void
    {
        $result = $this->service->validasi('beranda');

        $this->assertTrue($result);
    }

    #[Test]
    public function validasi_returns_true_when_controller_is_null(): void
    {
        Config::set('opensid.demo_mode', false);

        $result = $this->service->validasi(null);

        $this->assertTrue($result);
    }

    #[Test]
    public function validasi_returns_true_for_all_excepted_controllers(): void
    {
        $kecuali = ['beranda', 'identitas_desa', 'pengguna', 'pelanggan', 'setting', 'notif', 'main', 'info_sistem'];

        foreach ($kecuali as $controller) {
            $result = $this->service->validasi($controller);
            $this->assertTrue($result, "Controller '{$controller}' harus di-except");
        }
    }

    #[Test]
    public function validasi_akses_returns_false_when_token_empty(): void
    {
        $repo = Mockery::mock(SettingAplikasiRepository::class);
        $repo->shouldReceive('resolveSetting')
            ->andReturn((object) ['layanan_opendesa_token' => null]);
        app()->instance(SettingAplikasiRepository::class, $repo);

        $service = new CekService();
        $result  = $service->validasiAkses();

        $this->assertFalse($result);
        $this->assertTrue(session()->has('error_premium'));
    }

    #[Test]
    public function validasi_akses_returns_false_when_kode_desa_empty(): void
    {
        Cache::put('identitas_desa', (object) [
            'kode_desa' => '',
            'nama_desa' => 'Desa Test',
        ], 600);

        $result = $this->service->validasiAkses();

        $this->assertFalse($result);
        $this->assertTrue(session()->has('error_premium'));
    }

    #[Test]
    public function validasi_akses_returns_false_when_desa_id_mismatch(): void
    {
        $_SERVER['REMOTE_ADDR'] = '8.8.8.8';
        request()->server->set('REMOTE_ADDR', '8.8.8.8');

        $decoder = Mockery::mock(TokenDecoder::class);
        $decoder->shouldReceive('decode')
            ->with('test-token')
            ->andReturn((object) [
                'desa_id'                   => '99.99.99.9999',
                'tanggal_berlangganan'      => (object) ['akhir' => '2099-12-31'],
                'domain'                    => 'example.com',
            ]);
        app()->instance(TokenDecoder::class, $decoder);

        $result = $this->service->validasiAkses();

        $this->assertFalse($result);
        $this->assertTrue(session()->has('error_premium'));

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/v1/pelanggan/daftarhitam')
                && $request['kode_desa'] === kode_wilayah('1234567890')
                && $request['token'] === 'test-token';
        });
    }

    #[Test]
    public function validasi_akses_returns_true_when_all_valid(): void
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        request()->server->set('REMOTE_ADDR', '127.0.0.1');

        $decoder = Mockery::mock(TokenDecoder::class);
        $decoder->shouldReceive('decode')
            ->with('test-token')
            ->andReturn((object) [
                'desa_id'                   => '12.34.56.7890',
                'tanggal_berlangganan'      => (object) ['akhir' => '2099-12-31'],
                'domain'                    => null,
            ]);
        app()->instance(TokenDecoder::class, $decoder);

        $result = $this->service->validasiAkses();

        $this->assertTrue($result);
    }

    public static function domainMismatchProvider(): array
    {
        return [
            'domain sama persis'                    => ['desa.id', 'https://desa.id', null, false],
            'host pakai www, domain tanpa www'       => ['www.desa.id', 'https://desa.id', null, false],
            'host tanpa www, domain pakai www'       => ['desa.id', 'https://www.desa.id', null, false],
            'host huruf besar, www, domain kecil'    => ['WWW.DESA.ID', 'https://desa.id', null, false],
            'domain benar-benar berbeda'             => ['lain.id', 'https://desa.id', null, true],
            'cocok lewat domain_alternatif'          => ['alias.id', 'https://desa.id', 'https://alias.id', false],
            'domain claim tanpa scheme ditolak'       => ['desa.id', 'desa.id', null, true],
        ];
    }

    #[Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('domainMismatchProvider')]
    public function is_domain_mismatch_mengabaikan_prefix_www(
        string $currentHost,
        ?string $domain,
        ?string $domainAlternatif,
        bool $expectedMismatch
    ): void {
        $jwtPayload = (object) [
            'domain'            => $domain,
            'domain_alternatif' => $domainAlternatif,
        ];

        $method = new \ReflectionMethod(CekService::class, 'isDomainMismatch');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $jwtPayload, $currentHost);

        $this->assertSame($expectedMismatch, $result);
    }

    #[Test]
    public function is_domain_mismatch_menolak_host_kosong(): void
    {
        $jwtPayload = (object) ['domain' => 'https://desa.id'];

        $method = new \ReflectionMethod(CekService::class, 'isDomainMismatch');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $jwtPayload, '');

        $this->assertTrue($result);
    }
}