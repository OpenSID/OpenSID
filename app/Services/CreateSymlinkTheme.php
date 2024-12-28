<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class CreateSymlinkTheme
{
    public static function handle($theme)
    {
        $publicPath = public_path('assets/themes/' . $theme->slug);
        $assetPath  = public_path($theme->asset_path);

        if (! File::exists($publicPath)) {
            File::ensureDirectoryExists(dirname($publicPath), 0755);
            File::link($assetPath, $publicPath);
        }
    }
}