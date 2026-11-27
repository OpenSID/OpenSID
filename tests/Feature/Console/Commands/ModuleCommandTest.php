<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\ModuleCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Console\Commands\ModuleCommand (opensid:module).
 *
 * Menguji mode interaktif (pilih dari daftar bernomor) dan mode non-interaktif
 * (argumen nama modul, --migrasi, --delete, --no-report). Modul uji memakai
 * folder migrasi kosong agar tidak menyentuh database sungguhan.
 */
class ModuleCommandTest extends BaseTestCase
{
    private string $namaModulUji;
    private string $pathModulUji;

    protected function setUp(): void
    {
        parent::setUp();

        $this->namaModulUji = 'ModuleCommandTestDummy';
        $this->pathModulUji = base_path('Modules') . DIRECTORY_SEPARATOR . $this->namaModulUji;

        // Folder migrasi kosong: cukup agar jalankanMigrasiModule() mengenali
        // modul ini tanpa menjalankan migrasi database sungguhan apa pun.
        File::ensureDirectoryExists($this->pathModulUji . '/database/migrations');
        // module.json menandai folder ini sebagai modul valid untuk daftar interaktif.
        File::put($this->pathModulUji . '/module.json', json_encode(['name' => $this->namaModulUji]));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->pathModulUji);
        parent::tearDown();
    }

    #[Test]
    public function command_resolvable_dan_punya_signature_baru(): void
    {
        $command = $this->app->make(ModuleCommand::class);
        $this->assertSame('opensid:module', $command->getName());
        $this->assertNotEmpty($command->getDescription());

        $definition = $command->getDefinition();
        $this->assertTrue($definition->hasArgument('module'));
        $this->assertFalse($definition->getArgument('module')->isRequired());
        $this->assertTrue($definition->hasOption('migrasi'));
        $this->assertSame('up', $definition->getOption('migrasi')->getDefault());
        $this->assertTrue($definition->hasOption('delete'));
        $this->assertTrue($definition->hasOption('no-report'));
    }

    #[Test]
    public function mode_interaktif_berhenti_jika_tidak_ada_modul_terdeteksi(): void
    {
        File::deleteDirectory($this->pathModulUji);

        $this->artisan('opensid:module')
            ->expectsOutput('Tidak ada modul yang terdeteksi.')
            ->doesntExpectOutput('Module:')
            ->assertExitCode(0);
    }

    #[Test]
    public function mode_interaktif_mengabaikan_folder_tanpa_module_json(): void
    {
        $folderIseng = base_path('Modules') . DIRECTORY_SEPARATOR . 'FolderIsengTanpaManifest';
        File::ensureDirectoryExists($folderIseng);

        try {
            $this->artisan('opensid:module')
                ->expectsOutput('Module:')
                ->expectsOutput(" [1] {$this->namaModulUji}")
                ->doesntExpectOutput(' [2] FolderIsengTanpaManifest')
                ->expectsQuestion('Pilih modul yang akan dipasang (masukkan nomor):', 999)
                ->expectsOutput('Modul tidak ditemukan')
                ->assertExitCode(1);
        } finally {
            File::deleteDirectory($folderIseng);
        }
    }

    #[Test]
    public function mode_interaktif_berhenti_jika_hanya_ada_folder_tanpa_module_json(): void
    {
        File::deleteDirectory($this->pathModulUji);
        $folderIseng = base_path('Modules') . DIRECTORY_SEPARATOR . 'FolderIsengTanpaManifest';
        File::ensureDirectoryExists($folderIseng);

        try {
            $this->artisan('opensid:module')
                ->expectsOutput('Tidak ada modul yang terdeteksi.')
                ->doesntExpectOutput('Module:')
                ->assertExitCode(0);
        } finally {
            File::deleteDirectory($folderIseng);
        }
    }

    #[Test]
    public function mode_interaktif_menampilkan_error_jika_modul_tidak_ditemukan(): void
    {
        $this->artisan('opensid:module')
            ->expectsQuestion('Pilih modul yang akan dipasang (masukkan nomor):', 999)
            ->expectsOutput('Modul tidak ditemukan')
            ->assertExitCode(1);
    }

    #[Test]
    public function mode_interaktif_menampilkan_error_jika_pilihan_migrasi_tidak_valid(): void
    {
        $this->artisan('opensid:module')
            ->expectsQuestion('Pilih modul yang akan dipasang (masukkan nomor):', 1)
            ->expectsQuestion('Pilih migrasi yang akan dijalankan (masukkan nomor):', 99)
            ->expectsOutput('Pilihan tidak valid')
            ->assertExitCode(1);
    }

    #[Test]
    public function non_interaktif_error_jika_nama_modul_kosong(): void
    {
        $this->artisan('opensid:module', ['module' => ''])
            ->expectsOutput('Nama modul tidak boleh kosong')
            ->assertExitCode(1);
    }

    #[Test]
    public function non_interaktif_error_jika_modul_tidak_ditemukan(): void
    {
        $this->artisan('opensid:module', ['module' => 'ModulTidakAdaSamaSekali'])
            ->assertExitCode(1);
    }

    #[Test]
    public function non_interaktif_error_jika_migrasi_tidak_dikenal(): void
    {
        $this->artisan('opensid:module', ['module' => $this->namaModulUji, '--migrasi' => 'acak'])
            ->expectsOutputToContain('tidak dikenal')
            ->assertExitCode(1);
    }

    #[Test]
    public function non_interaktif_pasang_modul_by_name_sukses_tanpa_hit_api(): void
    {
        Http::fake();

        $this->artisan('opensid:module', ['module' => $this->namaModulUji])
            ->expectsOutput("Modul {$this->namaModulUji} berhasil dipasang.")
            ->assertExitCode(0);

        Http::assertNothingSent();
    }

    #[Test]
    public function non_interaktif_menerima_format_nama_url_versi(): void
    {
        Http::fake();

        $namaModulVersi = "{$this->namaModulUji}___https://contoh.test/paket.zip___1.0.0";

        $this->artisan('opensid:module', ['module' => $namaModulVersi])
            ->expectsOutput("Modul {$this->namaModulUji} berhasil dipasang.")
            ->assertExitCode(0);
    }

    #[Test]
    public function non_interaktif_opsi_no_report_diterima_tanpa_error(): void
    {
        Http::fake();

        $this->artisan('opensid:module', ['module' => $this->namaModulUji, '--no-report' => true])
            ->assertExitCode(0);

        Http::assertNothingSent();
    }

    #[Test]
    public function non_interaktif_hapus_modul_by_name_setara_modul_hapus(): void
    {
        $this->artisan('opensid:module', ['module' => $this->namaModulUji, '--delete' => true])
            ->expectsOutput("Modul {$this->namaModulUji} berhasil dihapus.")
            ->assertExitCode(0);
    }

    #[Test]
    public function non_interaktif_migrasi_down_setara_hapus(): void
    {
        $this->artisan('opensid:module', ['module' => $this->namaModulUji, '--migrasi' => 'down'])
            ->expectsOutput("Modul {$this->namaModulUji} berhasil dihapus.")
            ->assertExitCode(0);
    }

    #[Test]
    public function non_interaktif_migrasi_fresh_sukses(): void
    {
        $this->artisan('opensid:module', ['module' => $this->namaModulUji, '--migrasi' => 'fresh'])
            ->expectsOutput("Modul {$this->namaModulUji} berhasil dipasang.")
            ->assertExitCode(0);
    }

    #[Test]
    public function non_interaktif_hapus_modul_tidak_ditemukan_gagal(): void
    {
        $this->artisan('opensid:module', ['module' => 'ModulTidakAdaSamaSekali', '--delete' => true])
            ->assertExitCode(1);
    }

    #[Test]
    public function pasang_modul_mempublikasikan_aset_ke_public_modules(): void
    {
        $nama = 'ModuleCommandTestDummyAsset';
        $path = base_path('Modules') . DIRECTORY_SEPARATOR . $nama;
        $slug = strtolower($nama);

        File::ensureDirectoryExists($path . '/database/migrations');
        File::ensureDirectoryExists($path . '/Views/assets/css');
        File::put($path . '/Views/assets/css/style.css', 'body {}');

        try {
            $this->artisan('opensid:module', ['module' => $nama])
                ->assertExitCode(0);

            $this->assertFileExists(public_path("modules/{$slug}/css/style.css"));

            $this->artisan('opensid:module', ['module' => $nama, '--delete' => true])
                ->assertExitCode(0);

            $this->assertDirectoryDoesNotExist(public_path("modules/{$slug}"));
        } finally {
            File::deleteDirectory($path);
            File::deleteDirectory(public_path("modules/{$slug}"));
        }
    }
}
