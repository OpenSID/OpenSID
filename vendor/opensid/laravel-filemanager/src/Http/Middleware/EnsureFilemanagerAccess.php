<?php

namespace OpenSID\LaravelFilemanager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsureFilemanagerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Defence-in-depth: the default `filemanager.access` gate returns
        // true for everyone when `permissions.access` is null (its
        // documented "any authenticated admin" mode). If a host trims the
        // route's middleware and drops `auth`, that would otherwise expose
        // the whole disk to guests. The filemanager is never a public
        // surface — require a logged-in user regardless of middleware
        // config.
        abort_if(Auth::guest(), 401);

        abort_unless(Gate::allows('filemanager.access'), 403);

        return $next($request);
    }
}
