<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class CreateSymlinkModule
{
    public static function handle($moduleName, $moduleNameLower)
    {
        $publicPath = public_path('assets/modules/' . $moduleNameLower);
        $assetPath  = module_path($moduleName, 'Views/assets');

        if (! File::exists($publicPath)) {
            File::ensureDirectoryExists(dirname($publicPath), 0755);
            File::link($assetPath, $publicPath);
        }
    }
}