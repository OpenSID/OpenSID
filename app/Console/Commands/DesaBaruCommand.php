<?php

namespace App\Console\Commands;

use App\Models\Config;
use App\Libraries\Database;
use Illuminate\Console\Command;

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
        } catch (\Throwable $th) {
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
