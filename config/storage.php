<?php

// Direktori storage yang wajib selalu ada agar aplikasi bisa boot (cache,
// session, view compiler, log). Diakses lewat config('storage')
// dan dipakai bersama oleh:
// - App\Bootstrap\EnsureStorageDirectoriesExist: membuat ulang direktori ini
//   saat boot jika hilang.
// - donjo-app/config/installer.php: membuat + memproteksi (htaccess) saat instalasi.

return [
    'storage/framework/',
    'storage/framework/cache/',
    'storage/framework/cache/data/',
    'storage/framework/sessions/',
    'storage/framework/testing/',
    'storage/framework/views/',
    'storage/logs/',
];
