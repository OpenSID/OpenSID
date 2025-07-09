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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Console\Commands;

use App\Traits\Migrator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleCommand extends Command
{
    use Migrator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensid:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memasang module baru ke OpenSID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Module:');
        $modules = collect(File::directories(base_path('Modules')))
            ->map(static fn ($path) => basename($path))
            ->diff(MODUL_BAWAAN)
            ->values()
            ->mapWithKeys(static fn ($module, $index) => [$index + 1 => $module]);

        foreach ($modules as $key => $module) {
            $this->info(" [{$key}] {$module}");
        }

        $module = $this->ask('Pilih modul yang akan dipasang (masukkan nomor):');

        if (! isset($modules[$module])) {
            $this->error('Modul tidak ditemukan');

            return;
        }

        $this->info('Migrasi:');
        $this->info('[1] Migrasi Up');
        $this->info('[2] Migrasi Down');
        $this->info('[3] Migrasi Fresh');
        $migrasi = $this->ask('Pilih migrasi yang akan dijalankan (masukkan nomor):');
        if ($migrasi == 1) {
            $this->jalankanMigrasiModule($modules[$module], 'up');
        } elseif ($migrasi == 2) {
            $this->jalankanMigrasiModule($modules[$module], 'down');
        } elseif ($migrasi == 3) {
            $this->jalankanMigrasiModule($modules[$module], 'down');
            $this->jalankanMigrasiModule($modules[$module], 'up');
        } else {
            $this->error('Pilihan tidak valid');

            return;
        }

        $this->info('Selesai');
    }
}
