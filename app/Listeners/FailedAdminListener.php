<?php

namespace App\Listeners;

use App\Models\LoginAttempts;
use Illuminate\Auth\Events\Failed;

class FailedAdminListener
{
    public function handle(Failed $failed)
    {
        if ($failed->guard !== 'admin') {
            return;
        }
    
        LoginAttempts::create([
            'username'   => $failed->user?->username ?? request('username'),
            'time'       => time(),
            'ip_address' => request()->ip(),
        ]);
    }
}