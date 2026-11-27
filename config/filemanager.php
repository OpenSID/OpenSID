<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route prefix & middleware
    |--------------------------------------------------------------------------
    */
    'route_prefix' => env('FILEMANAGER_ROUTE_PREFIX', 'filemanager'),

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Default Gate wiring — no host AuthServiceProvider code required. Each
    | value is the "akses" level passed to this app's global can($akses,
    | $modul) helper (OpenSID convention: 'b' baca/view, 'u' ubah/upload,
    | 'h' hapus/delete), checked against whatever module slug the embedding
    | page stamped via filemanager_authorize('slug'). Set a value to null to
    | always allow regardless of module context (e.g. 'access' => null lets
    | any authenticated admin browse, matching upstream rfm's always-on
    | download_files).
    |
    | To use a different permission system entirely, override the
    | Gate::define('filemanager.access'|'upload'|'delete', ...) calls
    | yourself (e.g. in your own AuthServiceProvider) — that simply
    | replaces this package's default, config-driven definition.
    |
    */
    'permissions' => [
        'access' => null,
        'upload' => 'u',
        'delete' => 'h',
    ],

    /*
    |--------------------------------------------------------------------------
    | Published static assets (CSS/JS/img/svg)
    |--------------------------------------------------------------------------
    |
    | "assets_path" is where `vendor:publish --tag=filemanager-assets` copies
    | the package's JS/CSS/img/svg, relative to base_path(). "assets_url_path"
    | is the URL segment used to reach them, relative to the app's webroot.
    |
    | This app's webroot is the project root itself (assets/ is served
    | directly, not public/ — see donjo-app/helpers/general_helper.php's
    | asset() override), so both default to the same "assets/vendor/..."
    | path. A standard Laravel app (webroot = public/) would instead set:
    |   'assets_path'     => 'public/vendor/filemanager',
    |   'assets_url_path' => 'vendor/filemanager',
    |
    */
    'assets_path' => env('FILEMANAGER_ASSETS_PATH', 'public/vendor/filemanager'),

    'assets_url_path' => env('FILEMANAGER_ASSETS_URL_PATH', 'vendor/filemanager'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks
    |--------------------------------------------------------------------------
    |
    | All file operations go through Storage::disk(). Point these at any
    | Flysystem disk configured in the host app's config/filesystems.php
    | (local, s3, ftp, sftp, ...). "thumbs_disk" holds generated thumbnails,
    | mirroring the relative path layout of "disk".
    |
    */
    'disk' => env('FILEMANAGER_DISK', 'filemanager'),

    'thumbs_disk' => env('FILEMANAGER_THUMBS_DISK', 'filemanager_thumbs'),

    /*
    |--------------------------------------------------------------------------
    | Size limits (MB)
    |--------------------------------------------------------------------------
    */
    'max_upload_size' => 8,

    'max_total_size' => false, // false = no limit

    /*
    |--------------------------------------------------------------------------
    | Allowed extensions, by category
    |--------------------------------------------------------------------------
    */
    'extensions' => [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico', 'webp'],
        // 'document' => ['doc', 'docx', 'pdf', 'xls', 'xlsx'],
        // 'video' => ['mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'webm'],
        // 'audio' => ['mp3', 'mpga', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav'],
        // 'archive' => ['zip', 'rar', 'gz', 'tar', 'iso', 'dmg'],
    ],

    'blacklisted_extensions' => ['php', 'phtml', 'php3', 'php4', 'php5', 'phar', 'exe', 'sh', 'bat', 'htaccess'],

    'editable_text_extensions' => ['txt', 'log', 'xml', 'html', 'css', 'htm', 'js'],

    // Off by default, matching this app's existing rfm/config/config.php
    // (preview_text_files/edit_text_files/create_text_files were hardcoded
    // false there) — flip on if the host app wants the text editor toolbar.
    'text_editing_enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Thumbnails
    |--------------------------------------------------------------------------
    */
    'thumbnail_width' => 122,
    'thumbnail_height' => 91,

    /*
    |--------------------------------------------------------------------------
    | Filename handling
    |--------------------------------------------------------------------------
    */
    'transliterate' => false,
    'convert_spaces' => true,
    'replace_with' => '_',
    'lower_case' => false,

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    */
    'default_language' => 'id',

    'languages' => [
        'id' => 'Bahasa Indonesia',
        'en_EN' => 'English',
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon theme
    |--------------------------------------------------------------------------
    |
    | Available out of the box: "ico" and "ico_dark".
    |
    */
    'icon_theme' => 'ico',

    /*
    |--------------------------------------------------------------------------
    | Base folder / Root directory
    |--------------------------------------------------------------------------
    |
    | The base folder relative to the disk's root that the filemanager is
    | allowed to read and manage. If set (e.g. 'desa/upload/media' or 'media'),
    | all browsing and operations are restricted within this directory.
    | Paths outside this directory will be denied. Leave empty ('') to allow
    | access to the entire root of the configured disk.
    |
    */
    'base_folder' => env('FILEMANAGER_BASE_FOLDER', 'desa/upload/media'),

    /*
    |--------------------------------------------------------------------------
    | Hidden files, folders & extensions
    |--------------------------------------------------------------------------
    |
    | Entries listed here are excluded from directory listings in the
    | filemanager dialog. "hidden_files" matches exact filenames,
    | "hidden_folders" matches exact folder names, and
    | "hidden_extensions" hides any file whose extension matches.
    |
    */
    'hidden_files' => ['config.php', '.htaccess', 'index.html', 'index.htm', '.gitignore', '.gitkeep'],

    'hidden_folders' => ['.git', '.svn', '__MACOSX'],

    'hidden_extensions' => ['php', 'phtml', 'sh', 'bat', 'htaccess'],
];
