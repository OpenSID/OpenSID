<?php

namespace OpenSID\LaravelFilemanager\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use OpenSID\LaravelFilemanager\Support\FilemanagerConfig;

/**
 * Central Storage/Flysystem wrapper — replaces rfm's include/utils.php path
 * functions (deleteFile, deleteDir, rename_file, rename_folder,
 * duplicate_file, create_folder, folder_info, ...) and the dual local/FTP
 * code paths those carried. Any FTP need is met by pointing the "disk" /
 * "thumbs_disk" config at an ftp/sftp Flysystem disk — no package-specific
 * FTP code.
 *
 * Every $path here is relative to the configured disk's root, matching how
 * rfm's legacy frontend already thinks of paths (relative to upload_dir).
 * Thumbnails mirror the same relative path on the thumbs disk.
 */
class FilesystemManager
{
    protected FilemanagerConfig $config;

    public function __construct(?FilemanagerConfig $config = null)
    {
        $this->config = $config ?? new FilemanagerConfig;
    }

    public function disk(): Filesystem
    {
        return Storage::disk($this->config->disk());
    }

    public function thumbsDisk(): Filesystem
    {
        return Storage::disk($this->config->thumbsDisk());
    }

    public function config(): FilemanagerConfig
    {
        return $this->config;
    }

    /**
     * Get the public URL for a file on the main disk, dynamically matching
     * the current request host and port.
     */
    public function url(string $path): string
    {
        $rawUrl = $this->disk()->url($path);

        return $this->normalizeDiskUrl($rawUrl);
    }

    /**
     * Get the public URL for a thumbnail on the thumbs disk, dynamically
     * matching the current request host and port.
     */
    public function thumbUrl(string $path): string
    {
        $rawUrl = $this->thumbsDisk()->url($path);

        return $this->normalizeDiskUrl($rawUrl);
    }

    protected function normalizeDiskUrl(string $url): string
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? $url;

        // If it's a remote URL (e.g. S3 / external CDN with custom host),
        // keep it as-is unless it was generated with a default localhost/127.0.0.1
        $host = $parsed['host'] ?? '';
        if ($host !== '' && ! in_array($host, ['localhost', '127.0.0.1'], true) && ($parsed['scheme'] ?? '') !== '') {
            $configuredAppHost = parse_url((string) config('app.url', ''), PHP_URL_HOST);
            if ($host !== $configuredAppHost) {
                return $url;
            }
        }

        return str_replace('/index.php', '', url($path));
    }

    /**
     * List the immediate contents of a directory (files + subdirectories),
     * excluding hidden entries and unallowed formats per config in a single filesystem pass.
     */
    public function list(string $directory, ?int $type = null): array
    {
        $baseFolder = $this->config->baseFolder();
        $directory = trim($directory, '/');

        if ($directory === '' && $baseFolder !== '') {
            $directory = $baseFolder;
        }

        PathGuard::assertSafe($directory);
        PathGuard::assertInsideBaseFolder($directory, $baseFolder);

        $disk = $this->disk();
        $entries = [];
        $allowedExtensions = $this->config->allowedExtensionsForType($type);

        foreach ($disk->listContents($directory, false) as $item) {
            $path = $item->path();
            $name = basename($path);

            if ($item instanceof DirectoryAttributes) {
                if ($this->config->isHiddenFolder($name)) {
                    continue;
                }

                $entries[] = [
                    'name' => $name,
                    'path' => $path,
                    'is_dir' => true,
                    'size' => null,
                    'date' => $item->lastModified() ?? 0,
                    'extension' => null,
                ];
            } elseif ($item instanceof FileAttributes) {
                if ($this->config->isHiddenFile($name)) {
                    continue;
                }

                $extension = mb_strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if ($this->config->isHiddenExtension($extension)) {
                    continue;
                }

                if (! in_array($extension, $allowedExtensions, true)) {
                    continue;
                }

                $entries[] = [
                    'name' => $name,
                    'path' => $path,
                    'is_dir' => false,
                    'size' => $item->fileSize() ?? 0,
                    'date' => $item->lastModified() ?? 0,
                    'extension' => $extension,
                ];
            }
        }

        return $entries;
    }

    public function exists(string $path): bool
    {
        return $this->disk()->exists($path);
    }

    public function isDirectory(string $path): bool
    {
        return $this->disk()->directoryExists($path);
    }

    public function makeDirectory(string $path): bool
    {
        if ($this->disk()->exists($path)) {
            return false;
        }

        $ok = $this->disk()->makeDirectory($path);

        if ($ok) {
            $this->protectDirectory($path);
        }

        // best-effort mirrored thumbs directory; not fatal if it fails
        try {
            $this->thumbsDisk()->makeDirectory($path);
            $this->protectDirectory($path, isThumbDisk: true);
        } catch (\Throwable) {
            // ignore
        }

        return $ok;
    }

    /**
     * Places a hardened .htaccess file and index.html inside the directory
     * to prevent script execution (PHP, CGI, ASP, shell scripts, etc.) and
     * directory listing on Apache, OpenLiteSpeed, and LiteSpeed.
     *
     * Nginx ignores .htaccess entirely — for Nginx/OLS-native setups the
     * host MUST also add the server-level location block documented in the
     * package README ("Keamanan Web Server"). This file is one layer, not
     * the whole defence.
     */
    public function protectDirectory(string $path, bool $isThumbDisk = false): void
    {
        $htaccessContent = <<<HTACCESS
# ----------------------------------------------------------------------
# 1. Block Access to Dangerous Files (Apache 2.2, 2.4, OpenLiteSpeed, LiteSpeed)
# ----------------------------------------------------------------------
<FilesMatch "(?i)\.(php|php\.|php[0-9]*|phtml|phtm|phps|pht|phpt|phar|pgif|hphp|ctp|module|inc|pl|py|pyc|jsp|asp|aspx|htm|html|shtml|xhtml|sh|bash|cgi|exe|bat|cmd|com|scr|env|htaccess|htpasswd|ini)$">
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Deny from all
    </IfModule>
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
</FilesMatch>

# ----------------------------------------------------------------------
# 2. Rewrite-Based Denial (Fully supported by OpenLiteSpeed, LiteSpeed, Apache)
# ----------------------------------------------------------------------
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule (?i)\.(php|php\.|php[0-9]*|phtml|phtm|phps|pht|phpt|phar|pgif|hphp|ctp|module|inc|pl|py|pyc|jsp|asp|aspx|htm|html|shtml|xhtml|sh|bash|cgi|exe|bat|cmd|com|scr|env|htaccess|htpasswd|ini)$ - [F,L]
</IfModule>

# ----------------------------------------------------------------------
# 3. Disable Script Handlers & Force Plain Handling
# ----------------------------------------------------------------------
<IfModule mod_mime.c>
    RemoveHandler .php .php3 .php4 .php5 .php7 .php8 .phtml .phtm .phps .pht .phpt .phar .pgif .cgi .pl .py .inc .module
    RemoveType .php .php3 .php4 .php5 .php7 .php8 .phtml .phtm .phps .pht .phpt .phar .pgif .cgi .pl .py .inc .module
    AddType text/plain .php .php3 .php4 .php5 .php7 .php8 .phtml .phtm .phps .pht .phpt .phar .inc .module
</IfModule>

<FilesMatch "(?i)\.(php|php[0-9]*|phtml|phtm|phps|pht|phpt|phar|pgif|hphp|ctp|module|inc)$">
    SetHandler None
    ForceType text/plain
</FilesMatch>

# ----------------------------------------------------------------------
# 4. Disable CGI Execution & Directory Indexing
# ----------------------------------------------------------------------
Options -ExecCGI -Indexes

# ----------------------------------------------------------------------
# 4b. Stop MIME sniffing (an uploaded .svg / polyglot won't be re-typed
#     as HTML/JS by the browser) and neutralise inline rendering.
# ----------------------------------------------------------------------
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    <FilesMatch "(?i)\.(svg|svgz|xml|html?|xht|xhtml)$">
        Header set Content-Disposition "attachment"
        Header set Content-Security-Policy "default-src 'none'; style-src 'unsafe-inline'; sandbox"
    </FilesMatch>
</IfModule>

# ----------------------------------------------------------------------
# 5. Disable PHP Engine Execution (mod_php)
# ----------------------------------------------------------------------
<IfModule mod_php.c>
    php_flag engine off
</IfModule>
<IfModule mod_php7.c>
    php_flag engine off
</IfModule>
<IfModule mod_php8.c>
    php_flag engine off
</IfModule>
HTACCESS;

        $indexContent = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Directory Access is Forbidden</h1></body></html>'.PHP_EOL;

        try {
            $disk = $isThumbDisk ? $this->thumbsDisk() : $this->disk();
            $base = trim($path, '/');
            $htaccessPath = $base === '' ? '.htaccess' : $base.'/.htaccess';
            $indexPath = $base === '' ? 'index.html' : $base.'/index.html';

            $disk->put($htaccessPath, $htaccessContent);
            $disk->put($indexPath, $indexContent);
        } catch (\Throwable) {
            // ignore if storage disk is remote/S3 or read-only
        }
    }

    /**
     * Re-stamps the hardened .htaccess / index.html into the disk root and
     * every existing sub-directory (both the main disk and the thumbs
     * disk), overwriting any old/weak file. For installs that predate the
     * per-makeDirectory() protection, or whose upload tree was created
     * outside this package. Driven by the `filemanager:harden` command.
     *
     * @return array{disk:int, thumbs:int} directories processed per disk
     */
    public function hardenAllDirectories(): array
    {
        $base = $this->config->baseFolder();
        $counts = ['disk' => 0, 'thumbs' => 0];

        $this->protectDirectory($base);
        $counts['disk']++;

        foreach ($this->disk()->allDirectories($base) as $dir) {
            $this->protectDirectory($dir);
            $counts['disk']++;
        }

        try {
            $this->protectDirectory($base, isThumbDisk: true);
            $counts['thumbs']++;

            foreach ($this->thumbsDisk()->allDirectories($base) as $dir) {
                $this->protectDirectory($dir, isThumbDisk: true);
                $counts['thumbs']++;
            }
        } catch (\Throwable) {
            // thumbs disk may be remote/read-only — main disk is what matters
        }

        return $counts;
    }

    public function deleteFile(string $path): bool
    {
        $ok = $this->disk()->delete($path);

        if ($this->thumbsDisk()->exists($path)) {
            $this->thumbsDisk()->delete($path);
        }

        return $ok;
    }

    public function deleteDirectory(string $path): bool
    {
        $ok = $this->disk()->deleteDirectory($path);

        if ($this->thumbsDisk()->exists($path)) {
            $this->thumbsDisk()->deleteDirectory($path);
        }

        return $ok;
    }

    public function move(string $from, string $to): bool
    {
        if ($this->disk()->exists($to)) {
            return false;
        }

        $ok = $this->disk()->move($from, $to);

        if ($this->thumbsDisk()->exists($from)) {
            $this->thumbsDisk()->move($from, $to);
        }

        return $ok;
    }

    public function copy(string $from, string $to): bool
    {
        if ($this->disk()->exists($to)) {
            return false;
        }

        $ok = $this->disk()->copy($from, $to);

        if ($this->thumbsDisk()->exists($from)) {
            $this->thumbsDisk()->copy($from, $to);
        }

        return $ok;
    }

    public function put(string $path, string $contents): bool
    {
        return $this->disk()->put($path, $contents) !== false;
    }

    public function get(string $path): string
    {
        return $this->disk()->get($path);
    }

    /**
     * Recursive size/file-count/folder-count for a directory, mirroring
     * rfm's folder_info().
     *
     * @return array{0: int, 1: int, 2: int} [totalBytes, fileCount, folderCount]
     */
    public function directoryStats(string $directory): array
    {
        $disk = $this->disk();
        $directory = trim($directory, '/');

        $files = $disk->allFiles($directory);
        $folders = $disk->allDirectories($directory);

        $totalSize = 0;
        foreach ($files as $file) {
            $totalSize += $disk->size($file);
        }

        return [$totalSize, count($files), count($folders)];
    }

    public function humanFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unit = 0;

        while (round($bytes / 1024) > 0 && $unit < count($units) - 1) {
            $bytes /= 1024;
            $unit++;
        }

        return number_format($bytes, 0).' '.$units[$unit];
    }
}
