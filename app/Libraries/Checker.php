<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use Illuminate\Support\Str;

class Checker
{
    private $appKey;
    private $currentName;
    private $prefix        = ['kecil_', 'sedang_'];
    private $defaultPrefix = '';
    private $fileDb        = '';

    // Konstruktor untuk menginisialisasi direktori dan pola
    public function __construct($appKey, $currentName)
    {
        $this->appKey = preg_replace('/[^a-zA-Z0-9]/', '', $appKey);

        foreach ($this->prefix as $prefix) {
            if (substr($currentName, 0, strlen($prefix)) == $prefix) {
                $currentName         = substr($currentName, strlen($prefix));
                $this->defaultPrefix = $prefix;
            }
        }
        $this->currentName = $currentName;
    }

    public function encrypt()
    {
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
        $this->fileDb    = $randomSubstring . '_' . $this->currentName;

        return $this->defaultPrefix . $randomSubstring . '_' . $this->currentName;
    }

    public function isValid()
    {
        [$randomString, $originalName] = explode('_', $this->currentName);
        if (empty($originalName)) return false;

        return (bool) Str::contains($this->appKey, $randomString);
    }

    /**
     * Get the value of currentName
     */
    public function getCurrentName()
    {
        return $this->currentName;
    }

    /**
     * Get the value of fileDb
     */
    public function getFileDb()
    {
        return $this->fileDb;
    }
}
