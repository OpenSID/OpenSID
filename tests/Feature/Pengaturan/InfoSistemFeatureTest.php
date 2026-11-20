<?php

namespace Tests\Feature\Pengaturan;

use App\Libraries\Sistem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTestCase;

/**
 * Feature test untuk logika Pengaturan > Info Sistem.
 */
class InfoSistemFeatureTest extends BaseTestCase
{
    use RefreshDatabase;

    // ─── daftarEkstensiWajib ────────────────────────────────────────────────────

    public function test_daftar_ekstensi_wajib_returns_expected_keys(): void
    {
        $expected = ['pdo_mysql', 'curl', 'exif', 'fileinfo', 'gd', 'iconv', 'json', 'mbstring', 'mysqli', 'mysqlnd', 'tidy', 'zip', 'ionCube Loader'];
        $daftar   = Sistem::daftarEkstensiWajib();

        foreach ($expected as $ext) {
            $this->assertArrayHasKey($ext, $daftar, "Extension '{$ext}' missing from daftarEkstensiWajib()");
        }
        $this->assertCount(count($expected), $daftar);
    }

    public function test_daftar_ekstensi_wajib_each_entry_has_name(): void
    {
        foreach (Sistem::daftarEkstensiWajib() as $ext => $meta) {
            $this->assertArrayHasKey('name', $meta, "Entry '{$ext}' is missing 'name'");
            $this->assertNotEmpty($meta['name']);
        }
    }

    public function test_daftar_ekstensi_wajib_ioncube_is_dev_optional(): void
    {
        $daftar = Sistem::daftarEkstensiWajib();

        $this->assertArrayHasKey('ionCube Loader', $daftar);
        $this->assertTrue(! empty($daftar['ionCube Loader']['dev_optional']));
    }

    public function test_daftar_ekstensi_wajib_non_ioncube_entries_are_not_dev_optional(): void
    {
        foreach (Sistem::daftarEkstensiWajib() as $ext => $meta) {
            if ($ext === 'ionCube Loader') {
                continue;
            }
            $this->assertEmpty($meta['dev_optional'] ?? null, "Extension '{$ext}' should not be dev_optional");
        }
    }

    // ─── cekEkstensi ───────────────────────────────────────────────────────────

    public function test_cek_ekstensi_returns_lengkap_and_ekstensi_keys(): void
    {
        $result = Sistem::cekEkstensi();

        $this->assertArrayHasKey('lengkap', $result);
        $this->assertArrayHasKey('ekstensi', $result);
        $this->assertIsBool($result['lengkap']);
        $this->assertIsArray($result['ekstensi']);
    }

    public function test_cek_ekstensi_each_entry_has_loaded_dev_optional_dev_bypassed(): void
    {
        foreach (Sistem::cekEkstensi()['ekstensi'] as $ext => $entry) {
            $this->assertArrayHasKey('loaded', $entry, "Entry '{$ext}' missing 'loaded'");
            $this->assertArrayHasKey('dev_optional', $entry, "Entry '{$ext}' missing 'dev_optional'");
            $this->assertArrayHasKey('dev_bypassed', $entry, "Entry '{$ext}' missing 'dev_bypassed'");
            $this->assertIsBool($entry['loaded']);
            $this->assertIsBool($entry['dev_optional']);
            $this->assertIsBool($entry['dev_bypassed']);
        }
    }

    public function test_cek_ekstensi_loaded_reflects_actual_extension_state(): void
    {
        foreach (Sistem::cekEkstensi()['ekstensi'] as $ext => $entry) {
            $this->assertSame(
                extension_loaded($ext),
                $entry['loaded'],
                "loaded flag for '{$ext}' does not match extension_loaded()"
            );
        }
    }

    public function test_cek_ekstensi_ioncube_is_marked_dev_optional(): void
    {
        $result  = Sistem::cekEkstensi();
        $ioncube = $result['ekstensi']['ionCube Loader'];

        $this->assertTrue($ioncube['dev_optional']);
    }

    public function test_cek_ekstensi_non_ioncube_entries_are_not_dev_optional(): void
    {
        foreach (Sistem::cekEkstensi()['ekstensi'] as $ext => $entry) {
            if ($ext === 'ionCube Loader') {
                continue;
            }
            $this->assertFalse($entry['dev_optional'], "'{$ext}' should not be dev_optional");
        }
    }

    public function test_cek_ekstensi_non_optional_extensions_are_never_dev_bypassed(): void
    {
        foreach (Sistem::cekEkstensi()['ekstensi'] as $ext => $entry) {
            if ($ext === 'ionCube Loader') {
                continue;
            }
            $this->assertFalse($entry['dev_bypassed'], "'{$ext}' should never be dev_bypassed");
        }
    }

    // ─── syaratPhp / syaratDatabase ─────────────────────────────────────────────

    public function test_syarat_php_returns_name_min_and_max(): void
    {
        $syarat = Sistem::syaratPhp();

        $this->assertArrayHasKey('name', $syarat);
        $this->assertArrayHasKey('min', $syarat);
        $this->assertArrayHasKey('max', $syarat);
    }

    public function test_daftar_ekstensi_wajib_includes_pdo_mysql(): void
    {
        $daftar = Sistem::daftarEkstensiWajib();

        $this->assertArrayHasKey('pdo_mysql', $daftar);
        $this->assertNotEmpty($daftar['pdo_mysql']['name']);
    }

    public function test_syarat_database_returns_mysql_and_mariadb_bounds(): void
    {
        $syarat = Sistem::syaratDatabase();

        foreach (['mysql', 'mariadb'] as $driver) {
            $this->assertArrayHasKey($driver, $syarat, "Missing driver '{$driver}'");
            $this->assertArrayHasKey('min', $syarat[$driver]);
            $this->assertArrayHasKey('max', $syarat[$driver]);
        }
    }

    // ─── cekPhp / cekDatabase / cekKebutuhanSistem / disableFunctions ──────────

    public function test_cek_php_returns_version_boolean_and_syarat(): void
    {
        $result = Sistem::cekPhp();

        $this->assertArrayHasKey('versi', $result);
        $this->assertArrayHasKey('cek', $result);
        $this->assertArrayHasKey('syarat', $result);
        $this->assertIsString($result['versi']);
        $this->assertIsBool($result['cek']);
        $this->assertSame(PHP_VERSION, $result['versi']);
        $this->assertSame(Sistem::syaratPhp(), $result['syarat']);
    }

    public function test_cek_php_cek_reflects_both_min_and_max_bounds(): void
    {
        $syarat   = Sistem::syaratPhp();
        $expected = version_compare(PHP_VERSION, $syarat['min'], '>=') && version_compare(PHP_VERSION, $syarat['max'], '<=');

        $this->assertSame($expected, Sistem::cekPhp()['cek']);
    }

    public function test_cek_kebutuhan_sistem_returns_expected_keys(): void
    {
        $result = Sistem::cekKebutuhanSistem();

        $this->assertArrayHasKey('max_execution_time', $result);
        $this->assertArrayHasKey('post_max_size', $result);
        $this->assertArrayHasKey('upload_max_filesize', $result);
        $this->assertArrayHasKey('memory_limit', $result);

        foreach ($result as $entry) {
            $this->assertArrayHasKey('required', $entry);
            $this->assertArrayHasKey('current', $entry);
            $this->assertArrayHasKey('result', $entry);
            $this->assertIsBool($entry['result']);
        }
    }

    public function test_disable_functions_returns_structure_with_boolean_values(): void
    {
        $result = Sistem::disableFunctions();

        $this->assertArrayHasKey('lengkap', $result);
        $this->assertArrayHasKey('functions', $result);
        $this->assertIsBool($result['lengkap']);
        $this->assertIsArray($result['functions']);
    }
}
