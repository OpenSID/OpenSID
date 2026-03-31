<?php

namespace Orchestra\Workbench\Http\Controllers;

use Orchestra\Workbench\Workbench;

abstract class Controller extends \Illuminate\Routing\Controller
{
    /**
     * Get redirect to path after logged in.
     */
    protected function redirectToAfterLoggedIn(): ?string
    {
        $start = Workbench::config('start') ?? '/';
        $hasAuthentication = Workbench::config('auth') ?? false;

        return match (true) {
            $hasAuthentication === true && $start === '/' => route('dashboard', absolute: false),
            $hasAuthentication === true => $start,
            default => $start,
        };
    }
}
