<?php
namespace App\Libraries;
use  Illuminate\Support\Str;

class Checker {    
    private $appKey;
    private $currentName;    
    private $prefix = ['kecil_', 'sedang_'];
    // Konstruktor untuk menginisialisasi direktori dan pola
    public function __construct($appKey, $currentName) {
        $this->appKey = preg_replace('/[^a-zA-Z0-9]/', '', $appKey);
        foreach($this->prefix as $prefix){
            if(substr($currentName, 0, strlen($prefix)) == $prefix){
                $currentName = substr($currentName, strlen($prefix));
            }
        }
        $this->currentName = $currentName;
    }

    public function encrypt(){        
        // Dekode string dari Base64
        $decodedString = substr($this->appKey, 7);
        // Tentukan panjang substring yang ingin diambil
        $substringLength = 5; // Misalnya, 5 karakter
        // Dapatkan panjang string yang sudah didekode
        $decodedLength = strlen($decodedString);
        // Pastikan panjang substring tidak lebih besar dari panjang string
        if ($substringLength > $decodedLength) {
            $substringLength = $decodedLength;
        }
        // Tentukan posisi acak untuk mulai mengambil substring
        $startPosition = mt_rand(0, $decodedLength - $substringLength);
        // Ambil substring
        $randomSubstring = substr($decodedString, $startPosition, $substringLength);
        
        return $randomSubstring.'_'.$this->currentName;
    }

    public function isValid(){
        list($randomString, $originalName) = explode('_', $this->currentName);
        if(empty($originalName)) return false;
        return (bool) Str::contains($this->appKey, $randomString);
    }
}
?>