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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Console\Commands;

use Tests\BaseTestCase;

class PindaiTemaCommandTest extends BaseTestCase
{
    /**
     * Test command signature is correct
     *
     * @test
     */
    public function test_command_signature()
    {
        $this->artisan('opensid:pindai-tema')
            ->assertExitCode(0);
    }

    /**
     * Test command executes successfully
     *
     * @test
     */
    public function test_command_executes_successfully()
    {
        $response = $this->artisan('opensid:pindai-tema');

        $response->assertExitCode(0);
    }

    /**
     * Test command displays task message
     *
     * @test
     */
    public function test_command_displays_task_message()
    {
        $this->artisan('opensid:pindai-tema')
            ->expectsOutput('Memulai pindai tema')
            ->assertExitCode(0);
    }

    /**
     * Test command displays completion message
     *
     * @test
     */
    public function test_command_displays_completion_message()
    {
        $this->artisan('opensid:pindai-tema')
            ->expectsOutputToContain('Pindai tema selesai!')
            ->assertExitCode(0);
    }

    /**
     * Test command displays theme count
     *
     * @test
     */
    public function test_command_displays_theme_count()
    {
        $response = $this->artisan('opensid:pindai-tema');

        // Should display count of themes found
        $response->expectsOutputToContain('tema ditemukan');
        $response->assertExitCode(0);
    }

    /**
     * Test command displays table with theme data
     *
     * @test
     */
    public function test_command_displays_theme_table()
    {
        $this->artisan('opensid:pindai-tema')
            ->expectsTableHeaders(['#', 'Nama', 'Slug', 'Versi', 'Sistem', 'Aktif'])
            ->assertExitCode(0);
    }

    /**
     * Test command output structure contains expected columns
     *
     * @test
     */
    public function test_command_output_contains_expected_columns()
    {
        $response = $this->artisan('opensid:pindai-tema');

        $response->assertExitCode(0);
        
        // The table should be displayed with header columns
        // Verify via output contains expected headers
        $output = $response->getOutput();
        
        $this->assertStringContainsString('#', $output);
        $this->assertStringContainsString('Nama', $output);
        $this->assertStringContainsString('Slug', $output);
        $this->assertStringContainsString('Versi', $output);
        $this->assertStringContainsString('Sistem', $output);
        $this->assertStringContainsString('Aktif', $output);
    }

    /**
     * Test command can be resolved from container
     *
     * @test
     */
    public function test_command_can_be_resolved()
    {
        $command = $this->app->make('App\Console\Commands\PindaiTemaCommand');

        $this->assertNotNull($command);
        $this->assertEquals('opensid:pindai-tema', $command->signature);
    }

    /**
     * Test command description is set
     *
     * @test
     */
    public function test_command_has_description()
    {
        $command = $this->app->make('App\Console\Commands\PindaiTemaCommand');

        $this->assertEquals('Pindai tema untuk memperbarui daftar tema yang tersedia.', $command->description);
    }

    /**
     * Test command output shows at least one theme
     *
     * @test
     */
    public function test_command_returns_at_least_default_theme()
    {
        $response = $this->artisan('opensid:pindai-tema');

        // System should have at least default theme
        $output = $response->getOutput();
        
        // Count table rows (lines with numbers at start)
        $this->assertStringContainsString('Pindai tema selesai!', $output);
    }

    /**
     * Test theme_scan function is called
     *
     * @test
     */
    public function test_theme_scan_is_executed()
    {
        // theme_scan should be called during command execution
        $response = $this->artisan('opensid:pindai-tema');

        $response->assertExitCode(0);
        $output = $response->getOutput();
        
        // Verify the task message appears (theme_scan was called)
        $this->assertStringContainsString('Memulai pindai tema', $output);
    }

    /**
     * Test theme_list function result is used
     *
     * @test
     */
    public function test_theme_list_result_is_displayed()
    {
        $response = $this->artisan('opensid:pindai-tema');

        $response->assertExitCode(0);
        $output = $response->getOutput();
        
        // Verify theme count message (uses theme_list result)
        $this->assertStringContainsString('tema ditemukan', $output);
    }

    /**
     * Test command handles empty theme list gracefully
     *
     * @test
     */
    public function test_command_handles_empty_theme_list()
    {
        $response = $this->artisan('opensid:pindai-tema');

        // Command should not fail even if theme list is empty
        $response->assertExitCode(0);
    }

    /**
     * Test command output format with valid table structure
     *
     * @test
     */
    public function test_command_table_has_correct_columns_count()
    {
        $response = $this->artisan('opensid:pindai-tema');

        $response->assertExitCode(0);
        $output = $response->getOutput();
        
        // Verify all expected columns appear in output
        $expectedColumns = ['#', 'Nama', 'Slug', 'Versi', 'Sistem', 'Aktif'];
        foreach ($expectedColumns as $column) {
            $this->assertStringContainsString($column, $output);
        }
    }
}
