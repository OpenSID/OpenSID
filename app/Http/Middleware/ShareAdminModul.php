<?php

namespace App\Http\Middleware;

use App\Attributes\AdminModul;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareAdminModul
{
    public function handle(Request $request, Closure $next): Response
    {
        $context    = $this->resolveContext($request);
        $controller = $context->controller ?? $this->routeName($request);
        $aksesModul = $context?->subModul;

        if ($aksesModul === null || $aksesModul === '') {
            $aksesModul = $context?->modul;
        }

        $aksesModul ??= $controller;

        View::share([
            'modul_ini'           => $context->modul ?? $controller,
            'sub_modul_ini'       => $context?->subModul,
            'controller'          => $controller,
            'akses_modul'         => $aksesModul,
            'kategori_pengaturan' => $context?->kategoriPengaturan,
        ]);

        return $next($request);
    }

    private function resolveContext(Request $request): ?AdminModul
    {
        $route = $request->route();

        return AdminModul::resolve(
            $route?->getControllerClass(),
            $route?->getActionMethod(),
        );
    }

    private function routeName(Request $request): ?string
    {
        $routeName = $request->route()?->getName();

        return $routeName === null ? null : explode('.', $routeName)[0];
    }
}
