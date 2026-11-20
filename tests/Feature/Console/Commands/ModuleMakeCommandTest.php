<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\Modules\ModuleMakeCommand;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Console\Commands\Modules\ModuleMakeCommand (make:module).
 *
 * Menguji cabang aman "Module sudah ada!" (early return) memakai nama modul yang
 * memang sudah ada (Modules/Lapak) sehingga TIDAK membuat file/direktori baru.
 * Command bersih dari helper CI3.
 */
class ModuleMakeCommandTest extends BaseTestCase
{
    #[Test]
    public function command_resolvable_dan_punya_signature(): void
    {
        $command = $this->app->make(ModuleMakeCommand::class);
        $this->assertSame('make:module', $command->getName());
        $this->assertNotEmpty($command->getDescription());
    }

    #[Test]
    public function menolak_membuat_modul_yang_sudah_ada(): void
    {
        $this->artisan('make:module')
            ->expectsQuestion('Masukkan nama modul (contoh: Blog)', 'Lapak')
            ->expectsQuestion('Masukkan nama modul kecil / lowercase (contoh: blog)', 'lapak')
            ->expectsOutput('Module Lapak sudah ada!')
            ->assertExitCode(0);

        // Pastikan tidak ada direktori modul baru yang ter-create akibat test.
        $this->assertDirectoryDoesNotExist(base_path('Modules/Blog'));
    }
}
