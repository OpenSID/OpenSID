<?php

namespace App\Http\Middleware;

use App\Actions\Database\MigrationCheckAction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDatabaseMigrations
{
    public function __construct(
        protected MigrationCheckAction $migrationCheck,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->migrationCheck->execute();

        return $next($request);
    }
}
