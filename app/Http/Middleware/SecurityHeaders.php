<?php

namespace App\Http\Middleware;

class SecurityHeaders
{
    public static function handle()
    {
        if (!config('security.enabled')) {
            return;
        }

        foreach (config('security.headers') as $key => $value) {

            if ($key === 'Strict-Transport-Security' && !is_https()) {
                continue;
            }

            header("{$key}: {$value}", true);
        }
    }
}
