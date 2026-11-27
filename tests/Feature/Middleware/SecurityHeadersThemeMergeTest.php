<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SecurityHeadersThemeMergeTest extends TestCase
{
    protected function runMiddleware(array $themeConfig, string $uri = '/'): Response
    {
        $middleware = new class($themeConfig) extends SecurityHeaders
        {
            public function __construct(private array $themeConfig) {}

            protected function loadThemeSecurityConfig(): array
            {
                return $this->themeConfig;
            }
        };

        return $middleware->handle(Request::create($uri), static fn () => response('OK'));
    }

    public function test_sumber_csp_dari_tema_digabung_ke_direktif_yang_sama(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; connect-src 'self' api.ipify.org; script-src 'self';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "default-src 'self'; connect-src 'self' https://cdn.silirdev.com https://api.myquran.com; script-src 'self' https://cdn.silirdev.com",
        ]);

        $csp = $response->headers->get('Content-Security-Policy');

        // Tiap direktif hanya boleh muncul sekali, kalau tidak browser mengabaikan yang kedua
        $this->assertSame(1, substr_count($csp, 'connect-src'));
        $this->assertSame(1, substr_count($csp, 'script-src'));
        $this->assertSame(1, substr_count($csp, 'default-src'));

        $this->assertStringContainsString("connect-src 'self' api.ipify.org https://cdn.silirdev.com https://api.myquran.com;", $csp);
        $this->assertStringContainsString("script-src 'self' https://cdn.silirdev.com;", $csp);
    }

    public function test_direktif_csp_baru_dari_tema_ditambahkan(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "font-src 'self' https://cdn.silirdev.com;",
        ]);

        $this->assertSame(
            "default-src 'self'; font-src 'self' https://cdn.silirdev.com;",
            $response->headers->get('Content-Security-Policy')
        );
    }

    public function test_direktif_terakhir_bawaan_tidak_menelan_direktif_tema(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; base-uri 'self';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "default-src 'self'; connect-src 'self' https://cdn.silirdev.com;",
        ]);

        $csp = $response->headers->get('Content-Security-Policy');

        // 'default-src' tidak boleh berakhir sebagai source expression milik 'base-uri'
        $this->assertStringNotContainsString("base-uri 'self' default-src", $csp);
        $this->assertSame(
            "default-src 'self'; base-uri 'self'; connect-src 'self' https://cdn.silirdev.com;",
            $csp
        );
    }

    public function test_sumber_duplikat_tidak_ditulis_dua_kali(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "img-src 'self' data:;",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "img-src 'self' data: blob:;",
        ]);

        $this->assertSame(
            "img-src 'self' data: blob:;",
            $response->headers->get('Content-Security-Policy')
        );
    }

    public function test_header_non_csp_bawaan_tidak_dapat_ditimpa_tema(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ]);

        $response = $this->runMiddleware([
            'X-Frame-Options' => 'ALLOWALL',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ]);

        $this->assertSame('SAMEORIGIN', $response->headers->get('X-Frame-Options'));
        $this->assertSame('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
    }

    public function test_header_baru_dari_tema_ditambahkan_dan_dinormalisasi(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);

        $response = $this->runMiddleware([
            'X-Custom-Header' => "custom-value\n   lanjutan",
        ]);

        $this->assertSame('custom-value lanjutan', $response->headers->get('X-Custom-Header'));
    }

    public function test_config_tema_kosong_tidak_mengubah_header_bawaan(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self';",
            'X-Frame-Options'         => 'SAMEORIGIN',
        ]);

        $response = $this->runMiddleware([]);

        $this->assertSame("default-src 'self';", $response->headers->get('Content-Security-Policy'));
        $this->assertSame('SAMEORIGIN', $response->headers->get('X-Frame-Options'));
    }

    public function test_csp_report_only_juga_digabung_per_direktif(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy-Report-Only' => "default-src 'self'; script-src 'self';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy-Report-Only' => "script-src 'self' https://cdn.silirdev.com;",
        ]);

        $this->assertSame(
            "default-src 'self'; script-src 'self' https://cdn.silirdev.com;",
            $response->headers->get('Content-Security-Policy-Report-Only')
        );
    }

    public function test_nama_header_tema_dicocokkan_tanpa_bedakan_huruf_besar_kecil(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; connect-src 'self';",
            'X-Frame-Options'         => 'SAMEORIGIN',
        ]);

        // Tema menulis nama header dalam huruf kecil
        $response = $this->runMiddleware([
            'content-security-policy' => "connect-src 'self' https://cdn.silirdev.com;",
            'x-frame-options'         => 'ALLOWALL',
        ]);

        // CSP tetap digabung, bukan menimpa header bawaan
        $this->assertSame(
            "default-src 'self'; connect-src 'self' https://cdn.silirdev.com;",
            $response->headers->get('Content-Security-Policy')
        );
        $this->assertSame('SAMEORIGIN', $response->headers->get('X-Frame-Options'));
    }

    public function test_tema_tidak_dapat_melonggarkan_direktif_di_luar_fetch_directive(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "frame-ancestors 'self' https://situs-lain.example; base-uri 'self' https://situs-lain.example; default-src 'self' https://situs-lain.example; form-action 'self' https://situs-lain.example;",
        ]);

        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertSame(
            "default-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self';",
            $csp
        );
        $this->assertStringNotContainsString('situs-lain.example', $csp);
    }

    public function test_tema_tetap_dapat_memperluas_fetch_directive(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; frame-ancestors 'self';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "manifest-src 'self' data:; media-src https://cdn.silirdev.com; frame-src https://www.youtube-nocookie.com;",
        ]);

        $this->assertSame(
            "default-src 'self'; frame-ancestors 'self'; manifest-src 'self' data:; media-src https://cdn.silirdev.com; frame-src https://www.youtube-nocookie.com;",
            $response->headers->get('Content-Security-Policy')
        );
    }

    public function test_keyword_none_dibuang_saat_sumber_tema_ditambahkan(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; media-src 'none';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "media-src https://cdn.silirdev.com;",
        ]);

        // "'none' 'self'" membuat direktif invalid lalu diabaikan browser
        $this->assertSame(
            "default-src 'self'; media-src https://cdn.silirdev.com;",
            $response->headers->get('Content-Security-Policy')
        );
    }

    public function test_keyword_none_dari_tema_diabaikan(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; img-src 'self' data:;",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "img-src 'none';",
        ]);

        $this->assertSame(
            "default-src 'self'; img-src 'self' data:;",
            $response->headers->get('Content-Security-Policy')
        );
    }

    public function test_nilai_header_tema_yang_bukan_string_atau_array_diabaikan(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);

        $response = $this->runMiddleware([
            'X-Foo' => null,
            'X-Bar' => 123,
            'X-Baz' => ['a', 'b'],
        ]);

        $this->assertNull($response->headers->get('X-Foo'));
        $this->assertNull($response->headers->get('X-Bar'));
        $this->assertSame('a b', $response->headers->get('X-Baz'));
        $this->assertSame('SAMEORIGIN', $response->headers->get('X-Frame-Options'));
    }

    public function test_key_berawalan_garis_bawah_diperlakukan_sebagai_komentar(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self';",
        ]);

        $response = $this->runMiddleware([
            '_catatan'                => ['ini komentar', 'bukan header'],
            'Content-Security-Policy' => "img-src https://cdn.silirdev.com;",
        ]);

        $this->assertNull($response->headers->get('_catatan'));
        $this->assertSame(
            "default-src 'self'; img-src https://cdn.silirdev.com;",
            $response->headers->get('Content-Security-Policy')
        );
    }

    public function test_header_non_csp_tema_yang_diabaikan_dicatat_di_log(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->withArgs(static fn (string $message, array $context): bool => str_contains($message, 'diabaikan karena sudah diatur konfigurasi bawaan')
                && $context['header'] === 'Referrer-Policy');

        $this->runMiddleware(['Referrer-Policy' => 'unsafe-url']);
    }

    public function test_direktif_csp_tema_yang_diabaikan_dicatat_di_log(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "frame-ancestors 'self';",
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->withArgs(static fn (string $message, array $context): bool => str_contains($message, 'bukan fetch-directive')
                && $context['direktif'] === 'frame-ancestors');

        $this->runMiddleware(['Content-Security-Policy' => "frame-ancestors https://situs-lain.example;"]);
    }

    public function test_x_frame_options_deny_pada_halaman_sso(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);

        $response = $this->runMiddleware(['X-Frame-Options' => 'ALLOWALL'], 'admin/sso-login');

        $this->assertSame('DENY', $response->headers->get('X-Frame-Options'));
    }

    public function test_hsts_dilewati_saat_bukan_https_dan_diset_saat_https(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        ]);

        $this->assertNull(
            $this->runMiddleware([], 'http://situs-desa.example/')->headers->get('Strict-Transport-Security')
        );
        $this->assertSame(
            'max-age=31536000; includeSubDomains',
            $this->runMiddleware([], 'https://situs-desa.example/')->headers->get('Strict-Transport-Security')
        );
    }

    public function test_csp_ketat_halaman_sso_tidak_terpengaruh_tema(): void
    {
        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self'; connect-src 'self';",
        ]);

        $response = $this->runMiddleware([
            'Content-Security-Policy' => "connect-src 'self' https://cdn.silirdev.com;",
        ], 'admin/sso-login');

        $this->assertSame(
            "default-src 'self'; frame-ancestors 'none';",
            $response->headers->get('Content-Security-Policy')
        );
    }
}
