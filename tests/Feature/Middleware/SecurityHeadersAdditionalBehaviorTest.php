<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SecurityHeadersAdditionalBehaviorTest extends TestCase
{
    protected function tearDown(): void
    {
        app()->forgetInstance('theme.security.path.resolver');

        parent::tearDown();
    }

    protected function bindEmptyThemePath(): void
    {
        // Supaya merge tema tidak ikut campur di test-test ini
        app()->bind('theme.security.path.resolver', fn () => '');
    }

    protected function registerRoute(string $uri, \Closure $handler): void
    {
        Route::middleware(SecurityHeaders::class)->get($uri, $handler);
    }

    public function test_header_dari_middleware_menimpa_header_yang_sudah_diset_controller(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);

        $this->registerRoute('/override-test', function () {
            return response('OK')->header('X-Frame-Options', 'ALLOW-FROM https://evil.example');
        });

        $response = $this->get('/override-test');

        $response->assertOk();
        // Middleware harus menang (replace = true), bukan ditimpa header controller
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_header_tetap_diset_pada_response_non_200(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);

        $this->registerRoute('/not-found-test', function () {
            return response()->json(['message' => 'Not Found'], 404);
        });

        $response = $this->get('/not-found-test');

        $response->assertStatus(404);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_header_tetap_diset_pada_response_500(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);

        $this->registerRoute('/server-error-test', function () {
            return response('Internal Server Error', 500);
        });

        $response = $this->get('/server-error-test');

        $response->assertStatus(500);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_security_headers_key_tidak_ada_di_config_tidak_error(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        // Sengaja TIDAK set 'security.headers' sama sekali (key tidak ada)

        $this->registerRoute('/no-headers-config-test', function () {
            return response('OK');
        });

        $response = $this->get('/no-headers-config-test');

        $response->assertOk(); // tidak boleh exception/error
    }

    public function test_security_config_root_tidak_ada_sama_sekali_tidak_error(): void
    {
        $this->bindEmptyThemePath();

        // Tidak ada config('security.*') sama sekali
        Config::set('security', null);

        $this->registerRoute('/no-security-config-test', function () {
            return response('OK');
        });

        $response = $this->get('/no-security-config-test');

        $response->assertOk();
        $response->assertHeaderMissing('X-Frame-Options');
    }

    public function test_permissions_policy_tetap_diset_jika_path_mengandung_kata_lain_yang_serupa(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Permissions-Policy' => 'camera=()',
        ]);

        // "bukutamu-lain" TIDAK mengandung substring "buku-tamu" (ada tanda hubung)
        $this->registerRoute('/bukutamu-lain', function () {
            return response('OK');
        });

        $response = $this->get('/bukutamu-lain');

        $response->assertOk();
        $response->assertHeader('Permissions-Policy', 'camera=()');
    }

    public function test_permissions_policy_dilewati_untuk_subpath_buku_tamu(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Permissions-Policy' => 'camera=()',
        ]);

        // str_contains -> path yang mengandung 'buku-tamu' di mana pun tetap match
        $this->registerRoute('/desa/buku-tamu/cetak', function () {
            return response('OK');
        });

        $response = $this->get('/desa/buku-tamu/cetak');

        $response->assertOk();
        $response->assertHeaderMissing('Permissions-Policy');
    }

    public function test_beberapa_header_sekaligus_semua_terpasang_dengan_benar(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'X-Frame-Options'                   => 'SAMEORIGIN',
            'X-Content-Type-Options'            => 'nosniff',
            'X-Permitted-Cross-Domain-Policies' => 'none',
            'Referrer-Policy'                   => 'strict-origin-when-cross-origin',
            'Cross-Origin-Embedder-Policy'      => 'same-origin',
            'Cross-Origin-Resource-Policy'      => 'same-origin',
            'Cross-Origin-Opener-Policy'        => 'same-origin',
        ]);

        $this->registerRoute('/many-headers-test', function () {
            return response('OK');
        });

        $response = $this->get('/many-headers-test');

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Permitted-Cross-Domain-Policies', 'none');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Cross-Origin-Embedder-Policy', 'same-origin');
        $response->assertHeader('Cross-Origin-Resource-Policy', 'same-origin');
        $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
    }

    public function test_header_multiline_dinormalisasi_sebelum_diset_ke_response(): void
    {
        $this->bindEmptyThemePath();

        Config::set('security.enabled', true);
        Config::set('security.headers', [
            'Content-Security-Policy' => "default-src 'self';\n\nscript-src 'self' 'unsafe-inline'\n    cdn.example.test;",
        ]);

        $this->registerRoute('/normalize-header-test', function () {
            return response('OK');
        });

        $response = $this->get('/normalize-header-test');

        $response->assertOk();
        $response->assertHeader(
            'Content-Security-Policy',
            "default-src 'self'; script-src 'self' 'unsafe-inline' cdn.example.test;"
        );
    }
}
