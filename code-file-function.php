<?php
// Mengambil konten file PHP
$filename = $_SERVER['argv'][1];
echo getRoute(str_replace('.php','',basename($filename)), $filename );

function cekStringArray($string, $array): bool {
    return array_reduce($array, fn($carry, $elemen): bool => $carry || str_contains($string, $elemen), false);
}

function getRoute($namaFile,$path ): void{
    $fileContent = file_get_contents($path);
// Mencocokkan semua fungsi publik menggunakan ekspresi reguler
$pattern = '/\bpublic\s+function\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(/';
preg_match_all($pattern, $fileContent, $matches);

// $matches[1] berisi nama-nama fungsi publik
$publicFunctions = $matches[1];

// Menampilkan hasil
// echo "Fungsi-fungsi publik pada file:\n";
$path = strtolower($namaFile);
$postMethod = ['update', 'insert', 'delete_all', 'hapus_semua', 'cetak', 'tambah', 'search', 'filter' ];
$addId = ['update', 'delete', 'form', 'hapus', 'cetak', 'lock', 'unlock'];
echo "Route::group('{$path}', function() {\n";    
    foreach($publicFunctions as $public){
        if ($public == '__construct') {
            continue;
        }
        $method = in_array($public, $postMethod) ? 'post' : 'get';
        $url = $public == 'index' ? '' : $public;
        $param  = cekStringArray($public, $addId) ? '/{id?}' : '';
        echo "Route::{$method}('/{$url}{$param}', '{$namaFile}@{$public}')->name('{$path}.{$public}');\n";
    }    
echo "});\n";

}

?>
