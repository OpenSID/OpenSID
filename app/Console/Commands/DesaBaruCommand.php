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

use App\Libraries\Database;
use App\Models\Config;
use Illuminate\Console\Command;
use Throwable;

class DesaBaruCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'opensid:desa-baru';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Inisialisasi data desa baru untuk OpenSID';

    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        $this->handleHapusCache();

        if (Config::whereNull('kode_desa')->orWhere('kode_desa', '')->exists()) {
            $this->warn('Data desa sudah ada, tetapi belum lengkap. Silakan lengkapi data desa di menu Identitas Desa.');

            return;
        }

        if (Config::appKey()->exists()) {
            $this->warn('Data desa sudah ada. Tidak dilakukan perubahan.');

            return;
        }

        try {
            $this->info('Memulai inisialisasi data desa baru...');

            $tasks = [
                'Menambahkan data sementara',
                'Menjalankan migrasi data awal',
                'Memeriksa migrasi database',
                'Menghapus cache desa',
                'Menghapus session',
            ];

            $this->withProgressBar($tasks, function ($task) {
                $this->handleTask($task);
            });

            $this->newLine();
            $this->info('Inisialisasi data desa baru selesai.');
        } catch (Throwable $th) {
            $this->newLine();
            $this->error("Gagal inisialisasi desa baru:\n {$th->getMessage()}");
            logger()->error($th);
        }
    }

    protected function handleTask(string $task): void
    {
        match ($task) {
            'Menambahkan data sementara'    => $this->handleTambahDataSementara(),
            'Menjalankan migrasi data awal' => $this->handleMigrasiDataAwal(),
            'Memeriksa migrasi database'    => $this->handlePeriksaMigrasi(),
            'Menghapus cache desa'          => $this->handleHapusCache(),
            'Menghapus session'             => $this->handleHapusSession(),
            default                         => null,
        };
    }

    protected function handleTambahDataSementara(): void
    {
        Config::create([
            'app_key'           => get_app_key(),
            'nama_desa'         => '',
            'kode_desa'         => '',
            'nama_kecamatan'    => '',
            'kode_kecamatan'    => '',
            'nama_kabupaten'    => '',
            'kode_kabupaten'    => '',
            'nama_propinsi'     => '',
            'kode_propinsi'     => '',
            'nama_kepala_camat' => '',
            'nip_kepala_camat'  => '',
        ]);
    }

    protected function handleMigrasiDataAwal(): void
    {
        $this->laravel->make('ci')->load->model('migrations/data_awal', 'data_awal');
        $this->laravel->make('ci')->data_awal->up();
    }

    protected function handlePeriksaMigrasi(): void
    {
        (new Database())->checkMigration(true);
    }

    protected function handleHapusCache(): void
    {
        resetCacheDesa();
        $this->callSilent('cache:clear');
    }

    protected function handleHapusSession(): void
    {
        session_destroy();
    }
}
