<?php

namespace OpenSID\LaravelFilemanager\Console;

use Illuminate\Console\Command;
use OpenSID\LaravelFilemanager\Services\FilesystemManager;

/**
 * Retrofits the hardened .htaccess / index.html onto an existing upload
 * tree — the per-directory protection only fires when THIS package creates
 * a folder, so installs migrated from legacy rfm/ (or with folders made by
 * hand / by other code) need this one-shot pass.
 *
 * Run after `composer update` on an upgrade, and any time the disk root
 * changes. Safe to run repeatedly — it overwrites, never appends.
 */
class HardenUploadsCommand extends Command
{
    protected $signature = 'filemanager:harden';

    protected $description = 'Menimpa berkas .htaccess/index.html pengaman di seluruh folder disk filemanager (Apache/LiteSpeed/OLS).';

    public function handle(FilesystemManager $files): int
    {
        $this->info('Menulis ulang proteksi .htaccess + index.html ...');

        $counts = $files->hardenAllDirectories();

        $this->info(sprintf(
            'Selesai: %d folder pada disk utama, %d folder pada disk thumbnail.',
            $counts['disk'],
            $counts['thumbs'],
        ));

        $this->newLine();
        $this->warn('Nginx & OpenLiteSpeed (mode native) TIDAK membaca .htaccess.');
        $this->warn('Terapkan juga blok "location" dari README bagian "Keamanan Web Server".');

        return self::SUCCESS;
    }
}
