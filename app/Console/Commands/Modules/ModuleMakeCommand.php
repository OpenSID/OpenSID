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

namespace App\Console\Commands\Modules;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModuleMakeCommand extends Command
{
    protected $signature   = 'make:module';
    protected $description = 'Create a new module with default structure and files';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $moduleName      = $this->ask('Masukkan nama modul (contoh: Blog)');
        $moduleNameLower = $this->ask('Masukkan nama modul kecil / lowercase (contoh: blog)', Str::lower($moduleName));

        $moduleDir   = base_path("Modules/{$moduleName}");
        $moduleClass = Str::ucfirst($moduleName);

        if ($this->files->exists($moduleDir)) {
            $this->error("Module {$moduleName} sudah ada!");

            return;
        }

        $folders = [
            'app/Helpers',
            'app/Http/Controllers',
            'app/Models',
            'app/Providers',
            'config',
            'database/Migrations',
            'database/Seeders',
            'resources/Views',
            'routes',
        ];

        foreach ($folders as $folder) {
            $this->files->makeDirectory("{$moduleDir}/{$folder}", 0755, true);
        }

        $this->createFileFromStub("{$moduleDir}/app/Providers/{$moduleName}ServiceProvider.php", 'provider.stub', [
            '{{ namespace }}'   => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
            '{{ class }}'       => "{$moduleClass}ServiceProvider",
        ]);

        $this->createFileFromStub("{$moduleDir}/app/Http/Controllers/{$moduleClass}Controller.php", 'controller.stub', [
            '{{ namespace }}'   => "Modules\\{$moduleName}\\App\\Http\\Controllers",
            '{{ moduleLower }}' => $moduleNameLower,
            '{{ class }}'       => "{$moduleClass}Controller",
        ]);

        $this->createFileFromStub("{$moduleDir}/app/Models/{$moduleClass}Model.php", 'model.stub', [
            '{{ namespace }}' => "Modules\\{$moduleName}\\App\\Models",
            '{{ class }}'     => "{$moduleClass}Model",
        ]);

        $this->createFileFromStub("{$moduleDir}/app/Helpers/{$moduleNameLower}_helper.php", 'helper.stub', [
            '{{ moduleName }}'  => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        $migrationFile = date('Y_m_d_His') . "_create_{$moduleNameLower}_table.php";
        $this->createFileFromStub("{$moduleDir}/database/Migrations/{$migrationFile}", 'migration.stub', [
            '{{ class }}' => "Create{$moduleClass}Table",
        ]);

        $this->createFileFromStub("{$moduleDir}/database/Seeders/{$moduleClass}Seeder.php", 'seed.stub', [
            '{{ namespace }}' => "Modules\\{$moduleName}\\Database\\Seeders",
            '{{ class }}'     => "{$moduleClass}Seeder",
        ]);

        $this->createFileFromStub("{$moduleDir}/Config/config.php", 'config.stub', [
            '{{ moduleName }}'  => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        $this->createFileFromStub("{$moduleDir}/routes/api.php", 'Routes/api.stub', [
            '{{ namespace }}'   => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
            '{{ class }}'       => "{$moduleClass}",
        ]);

        $this->createFileFromStub("{$moduleDir}/routes/web.php", 'Routes/web.stub', [
            '{{ namespace }}'   => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
            '{{ class }}'       => "{$moduleClass}",
        ]);

        $this->createFileFromStub("{$moduleDir}/resources/Views/index.blade.php", 'Views/index.blade.stub', [
            '{{ moduleName }}'  => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
            '{{ class }}'       => "{$moduleClass}",
        ]);

        $this->createFileFromStub("{$moduleDir}/composer.json", 'composer.stub', [
            '{{ namespace }}'   => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        $this->createFileFromStub("{$moduleDir}/module.json", 'module.stub', [
            '{{ namespace }}'   => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        $this->info("Module {$moduleName} berhasil dibuat!");
    }

    protected function createFileFromStub(string $path, string $stub, array $replace = []): void
    {
        $stubPath = base_path("app/Console/Commands/Modules/Stubs/{$stub}");
        if (! $this->files->exists($stubPath)) {
            $this->error("Stub {$stub} tidak ditemukan di {$stubPath}");

            return;
        }

        $content = $this->files->get($stubPath);
        $content = str_replace(array_keys($replace), array_values($replace), $content);

        $this->files->put($path, $content);
    }
}
