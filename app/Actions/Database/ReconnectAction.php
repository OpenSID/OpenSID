<?php

namespace App\Actions\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReconnectAction
{
    public function execute(?array $dbError, mixed $ciDb): bool
    {
        if (! isset($dbError['code']) || $dbError['code'] !== 1049 || $ciDb) {
            return true;
        }

        try {
            DB::reconnect();
            DB::connection()->getPdo();

            session()->forget('db_error');

            return true;
        } catch (\Throwable $e) {
            Log::error('Database reconnect failed: ' . $e->getMessage());

            return false;
        }
    }
}
