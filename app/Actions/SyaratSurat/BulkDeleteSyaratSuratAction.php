<?php

declare(strict_types=1);

namespace App\Actions\SyaratSurat;

use App\Repositories\SyaratSuratRepository;

class BulkDeleteSyaratSuratAction
{
    public function __construct(
        private readonly SyaratSuratRepository $repository,
    ) {
    }

    public function execute(array $ids): bool
    {
        foreach ($ids as $id) {
            if (! is_numeric($id)) {
                return false;
            }

            if ($this->repository->deleteWithFormatSuratExist((int) $id) === 0) {
                return false;
            }
        }

        return true;
    }
}
