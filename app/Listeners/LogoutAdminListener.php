<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Container\Container;

class LogoutAdminListener
{
    public function __construct(protected Container $app)
    {
    }

    public function handle(Logout $logout)
    {
        if ($logout->guard !== 'admin') {
            return;
        }

        $this->app['ci']->session->unset_userdata([
            'siteman',
            'sesi',
            'user',
            'nama',
            'grup',
            'fm_key',
            'isAdmin',
        ]);
    }
}