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

namespace Tests\Unit\Security;

use PHPUnit\Framework\TestCase;

/**
 * Hapus Lampiran Surat hanya lewat POST dengan CSRF (#12110).
 *
 * @internal
 */
final class LampiranHapusPostTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        parent::setUp();
        $this->root = dirname(__DIR__, 3);
    }

    public function test_route_hapus_lampiran_hanya_post(): void
    {
        $routes = file_get_contents($this->root . '/donjo-app/Routes/Web/admin.php');

        $this->assertMatchesRegularExpression("#Route::post\('/delete/\{id\?\}', 'Lampiran@delete'\)#", $routes);
        $this->assertMatchesRegularExpression("#Route::post\('/delete_all', 'Lampiran@delete_all'\)#", $routes);
        $this->assertDoesNotMatchRegularExpression("#Route::get\([^)]*Lampiran@delete#", $routes);
    }

    public function test_tombol_hapus_lampiran_dikirim_sebagai_post(): void
    {
        $controller = file_get_contents($this->root . '/donjo-app/controllers/Lampiran.php');

        $this->assertMatchesRegularExpression('#lampiran/delete/\{\$row->id\}"\)[^;]*data-method="POST"#', $controller);
    }

    public function test_modal_konfirmasi_hapus_mengirim_form_post_dengan_csrf(): void
    {
        $js = file_get_contents($this->root . '/assets/js/script.js');

        $this->assertStringContainsString('data("method")', $js);
        $this->assertStringContainsString('method: "POST"', $js);
        $this->assertStringContainsString('addCsrfField(form[0])', $js);
    }
}
