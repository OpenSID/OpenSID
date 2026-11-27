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

namespace App\Console\Commands;

use App\Traits\Migrator;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModuleCommand extends Command
{
    use Migrator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensid:module
        {module? : Nama modul, atau format Nama___url___versi}
        {--migrasi=up : Jenis migrasi yang dijalankan: up, down, atau fresh}
        {--delete : Jalankan migrasi down (setara modul hapus)}
        {--no-report : Jangan hit API install count ke server layanan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memasang atau menghapus module OpenSID (interaktif jika argumen module tidak diisi)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $module = $this->argument('module');

        if ($module === null) {
            return $this->pilihModulInteraktif();
        }

        return $this->prosesModul((string) $module);
    }

    /**
     * Mode interaktif: pilih modul dan jenis migrasi dari daftar bernomor.
     */
    private function pilihModulInteraktif(): int
    {
        $this->info('Module:');
        $modules = collect(File::directories(base_path('Modules')))
            ->map(static fn ($path): string => basename((string) $path))
            ->diff(MODUL_BAWAAN)
            ->values()
            ->mapWithKeys(static fn ($module, $index) => [$index + 1 => $module]);

        foreach ($modules as $key => $module) {
            $this->info(" [{$key}] {$module}");
        }

        $pilihan = $this->ask('Pilih modul yang akan dipasang (masukkan nomor):');

        if (! isset($modules[$pilihan])) {
            $this->error('Modul tidak ditemukan');

            return self::FAILURE;
        }

        $nama = $modules[$pilihan];

        $this->info('Migrasi:');
        $this->info('[1] Migrasi Up');
        $this->info('[2] Migrasi Down');
        $this->info('[3] Migrasi Fresh');
        $migrasi = $this->ask('Pilih migrasi yang akan dijalankan (masukkan nomor):');

        if ($migrasi == 1) {
            $this->jalankanMigrasiModule($nama, 'up');
        } elseif ($migrasi == 2) {
            $this->jalankanMigrasiModule($nama, 'down');
        } elseif ($migrasi == 3) {
            $this->jalankanMigrasiModule($nama, 'down');
            $this->jalankanMigrasiModule($nama, 'up');
        } else {
            $this->error('Pilihan tidak valid');

            return self::FAILURE;
        }

        $this->info('Selesai');

        return self::SUCCESS;
    }

    /**
     * Mode non-interaktif: dipanggil dengan argumen nama modul, mis. dari automation/siappakai.
     *
     * $namaModulVersi menerima nama modul biasa, atau format namaModul___urlDownload___versiModul
     * (kompatibel dengan pemanggilan lama `php index.php modul pasang namaModul___urlDownload___versiModul`).
     */
    private function prosesModul(string $namaModulVersi): int
    {
        [$name, , $version] = array_pad(explode('___', $namaModulVersi), 3, null);

        if ($name === null || $name === '') {
            $this->error('Nama modul tidak boleh kosong');

            return self::FAILURE;
        }

        if ($this->option('delete')) {
            return $this->hapusModul($name);
        }

        $migrasi = (string) $this->option('migrasi');

        return match ($migrasi) {
            'up'    => $this->pasangModul($name, $version),
            'down'  => $this->hapusModul($name),
            'fresh' => $this->freshModul($name, $version),
            default => $this->migrasiTidakDikenal($migrasi),
        };
    }

    /**
     * Setara `modul pasang`: jalankan migrasi up, lalu laporkan instalasi baru ke server layanan.
     */
    private function pasangModul(string $name, ?string $version): int
    {
        $modulesDirectory = array_keys(config_item('modules_locations') ?? [])[0] ?? '';
        $pasangBaru       = ! File::exists($modulesDirectory . $name);

        if (! $this->jalankanMigrasiModule($name, 'up')) {
            $this->error("Modul '{$name}' tidak ditemukan atau tidak memiliki migrasi.");

            return self::FAILURE;
        }

        if ($pasangBaru && ! $this->option('no-report')) {
            $this->laporkanInstalasiModul($name, $version);
        }

        $this->info("Modul {$name} berhasil dipasang.");

        return self::SUCCESS;
    }

    /**
     * Setara `modul hapus`: jalankan migrasi down saja (folder modul tidak dihapus).
     */
    private function hapusModul(string $name): int
    {
        try {
            if (! $this->jalankanMigrasiModule($name, 'down')) {
                $this->error("Modul '{$name}' tidak ditemukan atau tidak memiliki migrasi.");

                return self::FAILURE;
            }

            $this->info("Modul {$name} berhasil dihapus.");

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            Log::error($e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Setara migrasi fresh pada mode interaktif: migrasi down lalu up.
     */
    private function freshModul(string $name, ?string $version): int
    {
        $this->jalankanMigrasiModule($name, 'down');

        return $this->pasangModul($name, $version);
    }

    private function migrasiTidakDikenal(string $migrasi): int
    {
        $this->error("Jenis migrasi '{$migrasi}' tidak dikenal. Gunakan up, down, atau fresh.");

        return self::FAILURE;
    }

    /**
     * Hit API server layanan untuk mencatat instalasi modul baru.
     * Kegagalan tidak menghentikan alur utama, hanya dicatat di log.
     */
    private function laporkanInstalasiModul(string $name, ?string $version): void
    {
        try {
            $token    = (string) setting('layanan_opendesa_token');
            $response = Http::withToken($token)
                ->post(config_item('server_layanan') . '/api/v1/modules/install', [
                    'module_name'   => $name,
                    'version'       => $version,
                    'domain'        => request()->getSchemeAndHttpHost(),
                    'tanggal_waktu' => date('Y-m-d H:i:s'),
                ]);

            Log::notice("laporkanInstalasiModul {$name}: " . $response->body());
        } catch (Exception $e) {
            $this->warn("Gagal mengirim laporan instalasi modul {$name}: " . $e->getMessage());
            Log::error("laporkanInstalasiModul {$name}: " . $e->getMessage());
        }
    }
}
