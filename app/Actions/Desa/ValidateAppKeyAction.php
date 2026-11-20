<?php

namespace App\Actions\Desa;

use App\Services\DesaIdentityService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidateAppKeyAction
{
    public function __construct(
        protected DesaIdentityService $desa,
    ) {}

    public function execute(): ?RedirectResponse
    {
        $count = app(\App\Repositories\DesaRepository::class)->count();

        if ($count === 0) {
            throw new HttpException(500, 'Silakan tambah desa baru melalui console');
        }

        if ($this->desa->validAppKey()) {
            return null;
        }

        session()->put('cek_app_key', true);

        return redirect('koneksi_database/config');
    }
}
