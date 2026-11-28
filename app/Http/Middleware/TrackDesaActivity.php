<?php

namespace App\Http\Middleware;

use App\Actions\Tracking\TrackDesaAction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackDesaActivity
{
    public function __construct(
        protected TrackDesaAction $trackDesa,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->trackDesa->execute();

        return $next($request);
    }
}
