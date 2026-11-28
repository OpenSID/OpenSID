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

use Illuminate\Console\GeneratorCommand;

abstract class BaseModuleMakeCommand extends GeneratorCommand
{
    /**
     * Folder default jika module tidak diberikan
     */
    protected string $defaultFolder = 'app';

    /**
     * Handle command
     */
    public function handle(): void
    {
        parent::handle();
        $this->info(class_basename($this->getNameInput()) . ' created successfully!');
    }

    /**
     * Call command lain sambil meneruskan module
     *
     * @param mixed $command
     */
    public function call($command, array $arguments = [])
    {
        $module = $arguments['--module'] ?? $this->option('module');
        if ($module) {
            $arguments['--module'] = $module;
        }

        return parent::call($command, $arguments);
    }

    /**
     * Stub file path
     */
    abstract protected function stub(): string;

    /**
     * Nama folder di dalam module
     */
    abstract protected function moduleFolder(): string;

    /**
     * Root namespace jika module tidak diberikan
     */
    abstract protected function defaultNamespace(): string;

    /**
     * Path file generator
     *
     * @param mixed $name
     */
    protected function getPath($name, array $arguments = [])
    {
        $module = $arguments['--module'] ?? $this->option('module');

        if ($module) {
            $directory = base_path("Modules/{$module}/{$this->moduleFolder()}");
        } else {
            $directory = base_path($this->defaultFolder . '/' . $this->moduleFolder());
        }

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        return $directory . '/' . $this->getFileName();
    }

    /**
     * Ambil stub file
     */
    protected function getStub()
    {
        return base_path($this->stub());
    }

    /**
     * Nama file
     */
    protected function getFileName(): string
    {
        return $this->getNameInput() . '.php';
    }

    /**
     * Namespace root
     */
    protected function rootNamespace(array $arguments = [])
    {
        $module = $arguments['--module'] ?? $this->option('module');

        if ($module) {
            return 'Modules\\' . $module . '\\' . str_replace('/', '\\', $this->moduleFolder());
        }

        return $this->defaultNamespace();
    }
}
