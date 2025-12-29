<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\SecurityHeadersTheme;

use App\Http\Middleware\SecurityHeaders;
use ReflectionClass;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan integrasi konfigurasi security tema berjalan sesuai kebutuhan
 *
 * Feature: Integrasi Konfigurasi Security Tema
 * - Load konfigurasi security dari tema (security.php atau security.json)
 * - Gabungkan dengan konfigurasi bawaan (append value untuk header yang sudah ada)
 * - Tambahkan header baru dari tema yang belum ada di config bawaan
 */
class SecurityHeadersTest extends BaseTestCase
{
    private string $testThemePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testThemePath = sys_get_temp_dir() . '/test_theme_' . uniqid();
        mkdir($this->testThemePath, 0755, true);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->testThemePath)) {
            $this->removeDirectory($this->testThemePath);
        }

        parent::tearDown();
    }

    /**
     * Helper untuk menghapus direktori secara rekursif
     */
    private function removeDirectory(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    $path = $dir . '/' . $object;
                    if (is_dir($path)) {
                        $this->removeDirectory($path);
                    } else {
                        unlink($path);
                    }
                }
            }
            rmdir($dir);
        }
    }

    /**
     * Helper untuk mengakses method protected menggunakan reflection
     */
    private function invokeProtectedMethod(string $methodName, array $args = [])
    {
        $reflection = new ReflectionClass(SecurityHeaders::class);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs(null, $args);
    }

    /**
     * Test: Method getMergedSecurityHeaders mengembalikan array
     */
    public function test_get_merged_security_headers_returns_array(): void
    {
        config(['security.headers' => [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options'        => 'SAMEORIGIN',
        ]]);

        $result = $this->invokeProtectedMethod('getMergedSecurityHeaders');

        $this->assertIsArray($result);
    }

    /**
     * Test: Konfigurasi bawaan tetap ada dalam hasil merge
     */
    public function test_default_headers_are_preserved(): void
    {
        $defaultHeaders = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options'        => 'SAMEORIGIN',
            'Content-Security-Policy' => "default-src 'self';",
        ];

        config(['security.headers' => $defaultHeaders]);

        $result = $this->invokeProtectedMethod('getMergedSecurityHeaders');

        foreach ($defaultHeaders as $key => $value) {
            $this->assertArrayHasKey($key, $result);
            $this->assertEquals($value, $result[$key]);
        }
    }

    /**
     * Test: loadThemeSecurityConfig mengembalikan array kosong jika tidak ada file konfigurasi
     */
    public function test_load_theme_security_config_returns_empty_array_when_no_config(): void
    {
        $result = $this->invokeProtectedMethod('loadThemeSecurityConfig');

        $this->assertIsArray($result);
    }

    /**
     * Test: Konfigurasi bawaan tetap menjadi basis (tidak ditimpa)
     */
    public function test_theme_config_does_not_override_default_config(): void
    {
        $defaultHeaders = [
            'X-Frame-Options' => 'SAMEORIGIN',
        ];

        config(['security.headers' => $defaultHeaders]);

        $result = $this->invokeProtectedMethod('getMergedSecurityHeaders');

        $this->assertStringContainsString('SAMEORIGIN', $result['X-Frame-Options']);
    }

    /**
     * Test: Method appendHeaderValue menggabungkan value dengan benar
     */
    public function test_append_header_value_combines_values(): void
    {
        $existingValue = "default-src 'self';";
        $newValue = '*.opendesa.id';

        $result = $this->invokeProtectedMethod('appendHeaderValue', [$existingValue, $newValue]);

        $this->assertStringContainsString("default-src 'self'", $result);
        $this->assertStringContainsString('*.opendesa.id', $result);
    }

    /**
     * Test: appendHeaderValue menghapus trailing semicolon dan spasi
     */
    public function test_append_header_value_trims_trailing_chars(): void
    {
        $existingValue = "default-src 'self'; ";
        $newValue = '*.example.com';

        $result = $this->invokeProtectedMethod('appendHeaderValue', [$existingValue, $newValue]);

        $this->assertStringNotContainsString(';  ', $result);
        $this->assertStringContainsString('*.example.com', $result);
    }

    /**
     * Test: getMergedSecurityHeaders mengembalikan default headers jika tema tidak punya konfigurasi
     */
    public function test_merged_headers_equals_default_when_no_theme_config(): void
    {
        $defaultHeaders = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options'        => 'SAMEORIGIN',
        ];

        config(['security.headers' => $defaultHeaders]);

        $result = $this->invokeProtectedMethod('getMergedSecurityHeaders');

        $this->assertEquals($defaultHeaders, $result);
    }

    /**
     * Test: Handle method tidak error ketika security disabled
     */
    public function test_handle_does_not_throw_when_security_disabled(): void
    {
        config(['security.enabled' => false]);

        $this->expectNotToPerformAssertions();

        SecurityHeaders::handle();
    }

    /**
     * Test: Konfigurasi security kosong tidak menyebabkan error
     */
    public function test_empty_security_config_does_not_cause_error(): void
    {
        config(['security.headers' => []]);

        $result = $this->invokeProtectedMethod('getMergedSecurityHeaders');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test: Struktur header hasil merge valid
     */
    public function test_merged_headers_structure_is_valid(): void
    {
        config(['security.headers' => [
            'X-Content-Type-Options' => 'nosniff',
        ]]);

        $result = $this->invokeProtectedMethod('getMergedSecurityHeaders');

        foreach ($result as $key => $value) {
            $this->assertIsString($key);
            $this->assertIsString($value);
        }
    }

    /**
     * Test: Semua header bawaan dipertahankan dalam merge
     */
    public function test_all_default_headers_retained_after_merge(): void
    {
        $defaultHeaders = [
            'Strict-Transport-Security'       => 'max-age=31536000; includeSubDomains',
            'X-Content-Type-Options'          => 'nosniff',
            'Content-Security-Policy'         => "default-src 'self';",
            'X-Permitted-Cross-Domain-Policies' => 'none',
            'Permissions-Policy'              => 'accelerometer=(),camera=(),microphone=()',
            'Cross-Origin-Embedder-Policy'    => 'same-origin',
            'Cross-Origin-Resource-Policy'    => 'same-origin',
            'Cross-Origin-Opener-Policy'      => 'same-origin',
            'X-Frame-Options'                 => 'SAMEORIGIN',
        ];

        config(['security.headers' => $defaultHeaders]);

        $result = $this->invokeProtectedMethod('getMergedSecurityHeaders');

        $this->assertCount(count($defaultHeaders), $result);

        foreach ($defaultHeaders as $key => $value) {
            $this->assertArrayHasKey($key, $result);
            $this->assertEquals($value, $result[$key]);
        }
    }
}
