<?php
// Mengambil konten file PHP
$folderPath = $_SERVER['argv'][1];

// Get the list of files in the folder matching a pattern (all files)
$files = glob($folderPath . '/*');

// Output the list of filenames
foreach ($files as $filename) {
    echo getRoute(basename($filename), $filename );
}

$namaFile = $_SERVER['argv'][1];


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
$postMethod = ['update', 'insert', 'delete_all', 'hapus_semua', 'cetak', 'tambah' ];
echo "Route::group('{$path}', function() {\n";    
    foreach($publicFunctions as $public){
        if ($public == '__construct') {
            continue;
        }
        $method = in_array($public, $postMethod) ? 'post' : 'get';
        $url = $public == 'index' ? '' : $public;
        echo "Route::{$method}('/{$url}', '{$namaFile}@{$public}')->name('{$path}.{$public}');\n";
    }    
echo "});\n";

}

?>
