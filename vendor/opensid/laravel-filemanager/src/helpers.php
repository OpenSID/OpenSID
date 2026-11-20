<?php

use Illuminate\Support\Facades\Session;

if (! function_exists('filemanager_authorize')) {
    /**
     * Stamps the calling admin module's slug into the session so the
     * filemanager's Gate checks (filemanager.upload/filemanager.delete)
     * can be scoped per-module by a host app. Call this from within the
     * Blade view that embeds the picker (iframe or TinyMCE data-filemanager
     * attribute) — NOT from anything driven by request input — since its
     * security value depends entirely on it only running as a side effect
     * of that page's own (already-enforced) access control. Returns void;
     * safe to call unconditionally on every render of the embedding page.
     */
    function filemanager_authorize(string $modul): void
    {
        Session::put('filemanager.context', $modul);
    }
}

if (! function_exists('filemanager_base_url')) {
    /**
     * The dialog route's URL — e.g. for data-filemanager's
     * external_filemanager_path, which the vendored TinyMCE
     * responsivefilemanager plugin appends "?type=4&..." to directly.
     */
    function filemanager_base_url(): string
    {
        return route('filemanager.dialog');
    }
}

if (! function_exists('filemanager_asset')) {
    /**
     * URL for a published filemanager asset (see config('filemanager.
     * assets_url_path')). Uses url() and strips index.php to produce
     * a clean static-asset URL.
     */
    function filemanager_asset(string $path = ''): string
    {
        $base = trim((string) config('filemanager.assets_url_path', 'assets/vendor/filemanager'), '/');

        $url = url($base.'/'.ltrim($path, '/'));

        return str_replace('/index.php', '', $url);
    }
}
