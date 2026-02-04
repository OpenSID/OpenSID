<?php

use App\Models\Rtm;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

if (! function_exists('generate_next_rtm_number')) {
    /**
     * Membuat nomor RTM berikutnya secara otomatis.
     *
     * Fungsi ini akan:
     * 1. Mencari nomor RTM terakhir yang ada di database.
     * 2. Jika tidak ada, akan membuat nomor RTM pertama.
     * 3. Jika ada, akan menganalisis formatnya (misal: Huruf+Angka, Huruf+Angka+Huruf).
     * 4. Mencari nomor RTM terakhir yang memiliki format yang SAMA.
     * 5. Melakukan increment pada bagian angka dari nomor tersebut.
     * 6. Mengembalikan nomor baru atau null jika format tidak valid.
     *
     * @return string|null Nomor RTM baru, atau null jika gagal.
     */
    function generate_next_rtm_number(): ?string
    {
        // Dapatkan nomor terakhir dari database berdasarkan aturan sorting default
        $lastRtmOverall = _rtm_find_last_number(null);

        if (! $lastRtmOverall) {
            // Jika tabel kosong, generate nomor pertama.
            $kodeDesa = identitas()->kode_desa ?? 'KW';

            return $kodeDesa . str_pad('1', 5, '0', STR_PAD_LEFT);
        }

        // Analisis format nomor terakhir untuk menentukan polanya
        $formatInfo = _rtm_analyze_format($lastRtmOverall);

        if (! $formatInfo) {
            // Format nomor terakhir tidak dikenali atau tidak valid untuk auto-increment (misal: hanya huruf)
            return null;
        }

        // Cari nomor terakhir yang cocok dengan POLA yang sama
        // Ini untuk memastikan increment berlanjut pada format yang benar
        // misal: setelah A10B, harusnya A11B, bukan 102 jika nomor terakhir adalah 101.
        $lastRtmWithSameFormat = _rtm_find_last_number($formatInfo['regex']);

        $numberToIncrement = $lastRtmWithSameFormat ?: $lastRtmOverall;

        // Lakukan increment pada nomor yang ditemukan
        return _rtm_increment_number($numberToIncrement, $formatInfo);
    }
}

if (! function_exists('_rtm_find_last_number')) {
    /**
     * Internal: Mencari nomor RTM terakhir di DB, dengan opsi filter berdasarkan format regex.
     *
     * @param null|string $regexPattern Pola regex untuk filter, atau null untuk tanpa filter.
     *
     * @return null|string
     */
    function _rtm_find_last_number(?string $regexPattern): ?string
    {
        $query = Rtm::select(['id', 'no_kk'])
            ->where('config_id', identitas('id'));

        if ($regexPattern) {
            // WHERE REGEXP/RLIKE digunakan untuk mencocokkan pola format
            $query->where('no_kk', 'REGEXP', trim($regexPattern, '/'));
        }

        // Urutkan berdasarkan ID DESC untuk mendapatkan entri terbaru.
        // Ini lebih bisa diandalkan daripada sorting berdasarkan string 'no_kk'
        // untuk menentukan "nomor terakhir yang diinput".
        $lastRtm = $query
            ->orderBy('id', 'desc')
            ->first();

        return $lastRtm ? $lastRtm->no_kk : null;
    }
}

if (! function_exists('_rtm_analyze_format')) {
    /**
     * Internal: Menganalisis string nomor untuk menentukan format dan bagian-bagiannya.
     *
     * @return null|array
     */
    function _rtm_analyze_format(string $number): ?array
    {
        // Pola regex diurutkan dari yang paling spesifik ke paling umum
        // Karakter 'u' untuk support UTF-8
        $patterns = [
            // Format: Huruf+Angka+Huruf (e.g., "RT001/RW01", "A1B")
            'H_A_H' => '/^([a-zA-Z][a-zA-Z\s\.\-\/]*?)(\d+)([a-zA-Z\s\.\-\/]+)$/u',
            // Format: Angka+Huruf (e.g., "12B")
            'A_H'   => '/^(\d+)([a-zA-Z][a-zA-Z\s\.\-\/]*)$/u',
            // Format: Angka+Huruf+Angka (e.g., "2023-RT-01", "1B2"). Angka terakhir di-increment.
            'A_H_A' => '/^(\d+[a-zA-Z\s\.\-\/]+)(\d+)$/u',
            // Format: Huruf+Angka (e.g., "DUKUH-101", "A1")
            'H_A'   => '/^([a-zA-Z][a-zA-Z\s\.\-\/]*?)(\d+)$/u',
            // Format: Angka saja (e.g., "123456")
            'A'     => '/^(\d+)$/u',
        ];

        foreach ($patterns as $format => $regex) {
            if (preg_match($regex, $number, $matches)) {
                return [
                    'format' => $format,
                    'regex' => $regex,
                    'matches' => $matches,
                ];
            }
        }

        return null; // Tidak ada format yang cocok
    }
}

if (! function_exists('_rtm_increment_number')) {
    /**
     * Internal: Melakukan increment pada bagian angka dari sebuah nomor berdasarkan formatnya.
     *
     * @param string $number     Nomor untuk di-increment (digunakan untuk mendapatkan panjang padding)
     * @param array  $formatInfo Informasi format dari analyzeFormat()
     *
     * @return null|string
     */
    function _rtm_increment_number(string $number, array $formatInfo): ?string
    {
        // Re-run preg_match on the specific number we are incrementing to get its parts
        preg_match($formatInfo['regex'], $number, $matches);

        if (empty($matches)) {
            return null;
        }

        switch ($formatInfo['format']) {
            case 'H_A_H':
                // matches[1] = prefix, matches[2] = number, matches[3] = suffix
                $prefix = $matches[1];
                $numberPart = $matches[2];
                $suffix = $matches[3];
                $incremented = (int) $numberPart + 1;
                $padded = str_pad($incremented, strlen($numberPart), '0', STR_PAD_LEFT);

                return $prefix . $padded . $suffix;

            case 'A_H':
                // matches[1] = number, matches[2] = suffix
                $numberPart = $matches[1];
                $suffix = $matches[2];
                $incremented = (int) $numberPart + 1;
                $padded = str_pad($incremented, strlen($numberPart), '0', STR_PAD_LEFT);

                return $padded . $suffix;

            case 'A_H_A':
            case 'H_A':
                // matches[1] = prefix, matches[2] = number
                $prefix = $matches[1];
                $numberPart = $matches[2];
                $incremented = (int) $numberPart + 1;
                $padded = str_pad($incremented, strlen($numberPart), '0', STR_PAD_LEFT);

                return $prefix . $padded;

            case 'A':
                // matches[1] = number
                $numberPart = $matches[1];
                $incremented = (int) $numberPart + 1;
                // Hanya pad jika nomor asli diawali dengan '0'
                $paddingLength = (0 === strpos($numberPart, '0')) ? strlen($numberPart) : 0;

                return str_pad($incremented, $paddingLength, '0', STR_PAD_LEFT);

            default:
                return null; // Format tidak didukung
        }
    }
}
