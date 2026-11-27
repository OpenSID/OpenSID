<?php

namespace App\Bootstrap;

use Illuminate\Foundation\Application;

class EnsureStorageDirectoriesExist
{
    /**
     * Buat ulang direktori storage/framework/* dan storage/logs jika hilang
     * (mis. terhapus manual), supaya aplikasi bisa pulih sendiri di request
     * pertama tanpa intervensi manual. Daftar direktori dipakai bersama
     * dengan donjo-app/config/installer.php lewat config/storage.php.
     */
    public function bootstrap(Application $app): void
    {
        foreach (config('storage', []) as $relativeDirectory) {
            $directory = $app->basePath($relativeDirectory);

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
        }
    }
}
