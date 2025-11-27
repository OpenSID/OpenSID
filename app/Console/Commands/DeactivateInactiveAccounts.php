<?php

namespace App\Console\Commands;

use App\Services\MasaAktifAkunService;
use Illuminate\Console\Command;

class DeactivateInactiveAccounts extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'opensid:deactivate-inactive-accounts';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Menonaktifkan akun pengguna yang tidak aktif berdasarkan pengaturan sistem.';

    /**
     * {@inheritDoc}
     */
    public function handle(MasaAktifAkunService $masaAktifAkunService)
    {
        if (! setting('masa_akun_pengguna')) {
            $this->info('Fitur penonaktifan akun otomatis tidak aktif.');
            return 0;
        }

        if (setting('jenis_trigger_nonaktifkan_akun') !== 'cron') {
            $this->info('Trigger penonaktifkan akun diatur ke Manual. Cron job tidak akan berjalan.');
            return 0;
        }

        $result = $masaAktifAkunService->deactivateInactiveAccounts();

        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }

        return 0;
    }
}