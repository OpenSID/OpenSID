<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\ModuleCommand;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Console\Commands\ModuleCommand (opensid:module).
 *
 * Menguji cabang error handle() yang interaktif TANPA menjalankan migrasi nyata
 * (modul tidak ditemukan / pilihan migrasi tidak valid). Command bersih dari CI3.
 */
class ModuleCommandTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Beberapa code path saat menjalankan command membaca $_SERVER['PHP_SELF'].
        $_SERVER['PHP_SELF'] ??= $_SERVER['argv'][0] ?? 'artisan';
    }

    #[Test]
    public function command_resolvable_dan_punya_signature(): void
    {
        $command = $this->app->make(ModuleCommand::class);
        $this->assertSame('opensid:module', $command->getName());
        $this->assertNotEmpty($command->getDescription());
    }

    #[Test]
    public function menampilkan_error_jika_modul_tidak_ditemukan(): void
    {
        $this->artisan('opensid:module')
            ->expectsQuestion('Pilih modul yang akan dipasang (masukkan nomor):', 999)
            ->expectsOutput('Modul tidak ditemukan')
            ->assertExitCode(0);
    }

    #[Test]
    public function menampilkan_error_jika_pilihan_migrasi_tidak_valid(): void
    {
        // Modul nomor 1 valid (Modules/ tidak kosong), lalu pilihan migrasi invalid
        // -> cabang "Pilihan tidak valid" (tidak menjalankan migrasi apa pun).
        $this->artisan('opensid:module')
            ->expectsQuestion('Pilih modul yang akan dipasang (masukkan nomor):', 1)
            ->expectsQuestion('Pilih migrasi yang akan dijalankan (masukkan nomor):', 99)
            ->expectsOutput('Pilihan tidak valid')
            ->assertExitCode(0);
    }
}
