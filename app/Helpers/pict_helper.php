<?php

define('FOTO_DEFAULT_PRIA', 'assets/images/pengguna/kuser.png');
define('FOTO_DEFAULT_WANITA', 'assets/images/pengguna/wuser.png');

define('MIME_TYPE_SIMBOL', serialize([
    'image/png',
    'image/x-png',
]));

define('EXT_SIMBOL', serialize([
    '.png',
]));

define('MIME_TYPE_DOKUMEN', serialize([
    'application/x-download',
    'application/pdf',
    'application/ppt',
    'application/pptx',
    'application/excel',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/rtf',
    'application/powerpoint',
    'application/vnd.ms-powerpoint',
    'application/vnd.ms-excel',
    'application/msexcel',
]));

define('EXT_DOKUMEN', serialize([
    '.pdf',
    '.ppt',
    '.pptx',
    '.pps',
    '.ppsx',
    '.doc',
    '.docx',
    '.rtf',
    '.xls',
    '.xlsx',
]));

define('MIME_TYPE_GAMBAR', serialize([
    'image/jpeg',
    'image/pjpeg',
    'image/png',
    'image/x-png',
    'image/gif',
    'image/webp',
]));

define('EXT_GAMBAR', serialize([
    '.jpg',
    '.jpeg',
    '.png',
    '.gif',
    '.webp',
]));

define('MIME_TYPE_ARSIP', serialize([
    'application/rar',
    'application/x-rar',
    'application/x-rar-compressed',
    'application/octet-stream',
    'application/zip',
    'application/x-zip',
    'application/x-zip-compressed',
]));

define('EXT_ARSIP', serialize([
    '.zip',
    '.rar',
]));

if (! function_exists('TipeFile')) {
    /**
     * Deteksi MIME type dari file yang diunggah ($file_upload = $_FILES['<lokasi>']).
     */
    function TipeFile(array $file_upload)
    {
        $lokasi_file = $file_upload['tmp_name'];
        if (empty($lokasi_file)) {
            return '';
        }
        if (isPHP($file_upload['tmp_name'], $file_upload['name'])) {
            return 'application/x-php';
        }

        return DeteksiMimeType($lokasi_file);
    }
}

if (! function_exists('DeteksiMimeType')) {
    /**
     * Deteksi MIME type dari isi file di server (bukan dari header yang dikirim client),
     * karena header MIME type dari client bisa dipalsukan.
     *
     * $finfo_tersedia dan $mime_content_type_tersedia hanya dipakai untuk unit test,
     * agar skenario "ekstensi fileinfo tidak tersedia" bisa diuji tanpa mengubah
     * konfigurasi PHP yang sesungguhnya.
     */
    function DeteksiMimeType(string $lokasi_file, ?bool $finfo_tersedia = null, ?bool $mime_content_type_tersedia = null): string
    {
        $finfo_tersedia             ??= function_exists('finfo_open');
        $mime_content_type_tersedia ??= function_exists('mime_content_type');

        if ($finfo_tersedia) {
            $finfo     = finfo_open(FILEINFO_MIME_TYPE);
            $tipe_file = finfo_file($finfo, $lokasi_file);
            finfo_close($finfo);

            return $tipe_file;
        }

        if ($mime_content_type_tersedia) {
            return mime_content_type($lokasi_file);
        }

        // Jangan percaya MIME type dari client ($file_upload['type']), karena bisa dipalsukan.
        // Tanpa cara server untuk memeriksa isi file, tolak saja unggahan ini.
        return '';
    }
}

if (! function_exists('versi_foto')) {
    /**
     * Tambahkan query string versi (`?v=<mtime>`) ke URL berkas gambar lokal, supaya
     * begitu isi berkas berubah (unggah ulang, edit, Acak Data, dll) browser otomatis
     * mengambil versi terbaru pada refresh biasa alih-alih menyajikan dari cache.
     */
    function versi_foto(string $relatif, string $url): string
    {
        $abs = defined('FCPATH') ? FCPATH . $relatif : base_path($relatif);

        clearstatcache(true, $abs);

        return is_file($abs) ? $url . '?v=' . filemtime($abs) : $url;
    }
}

if (! function_exists('AmbilFoto')) {
    /**
     * Ambil foto profil berdasarkan parameter
     */
    function AmbilFoto(?string $foto, string $ukuran = 'kecil_', ?string $sex = '1', string $lokasi = LOKASI_USER_PICT): string
    {
        $sex       = $sex ?: '1';
        $file_foto = Foto_Default($foto, $sex);

        if ($foto == $file_foto) {
            $ukuran    = ($ukuran == 'kecil_') ? 'kecil_' : '';
            $relatif   = $lokasi . $ukuran . $foto;
            $file_foto = preg_replace('#/index\.php(?=/|$)#i', '', url($relatif));

            if (! file_exists(base_path($relatif))) {
                $file_foto = Foto_Default(null, $sex);
            } else {
                $file_foto = versi_foto($relatif, $file_foto);
            }
        }

        $url = str_starts_with(strtolower($file_foto), 'assets/')
            ? asset(substr($file_foto, 7))
            : $file_foto;

        return preg_replace('#/index\.php(?=/|$)#i', '', $url);
    }
}

if (! function_exists('Foto_Default')) {
    /**
     * Ambil foto default berdasarkan parameter
     */
    function Foto_Default(?string $foto, ?string $sex = '1'): string
    {
        if (! in_array($foto, ['kuser.png', 'wuser.png']) && ($foto !== null && $foto !== '' && $foto !== '0')) {
            return $foto;
        }
        if (($foto == 'kuser.png') || $sex == 1) {
            return FOTO_DEFAULT_PRIA;
        }
        if (($foto == 'wuser.png') || $sex == 2) {
            return FOTO_DEFAULT_WANITA;
        }

        return FOTO_DEFAULT_PRIA;
    }
}
