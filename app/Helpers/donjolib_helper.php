<?php

if (! function_exists('date_is_empty')) {
    /**
     * Cek apakah tanggal kosong atau bernilai placeholder '0000-00-00'.
     */
    function date_is_empty($tgl): bool
    {
        return empty($tgl) || substr($tgl, 0, 10) === '0000-00-00';
    }
}

if (! function_exists('bulan')) {
    /**
     * Mengembalikan daftar nama bulan (1-12) dalam Bahasa Indonesia.
     */
    function bulan(): array
    {
        return [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
    }
}

if (! function_exists('getBulan')) {
    /**
     * Mengembalikan nama bulan dalam Bahasa Indonesia dari nomor bulan (1-12).
     */
    function getBulan(int $bln): string
    {
        return bulan()[$bln] ?? '';
    }
}

if (! function_exists('tgl_indo2')) {
    /**
     * Format tanggal ke "tanggal bulan tahun jam" dalam Bahasa Indonesia.
     */
    function tgl_indo2($tgl, $replace_with = '-')
    {
        if (date_is_empty($tgl) || strlen($tgl) < 7) {
            return $replace_with;
        }
        $tanggal = substr($tgl, 8, 2);
        $jam     = substr($tgl, 11, 8);
        $bulan   = getBulan((int) substr($tgl, 5, 2));
        $tahun   = substr($tgl, 0, 4);

        return $tanggal . ' ' . $bulan . ' ' . $tahun . ' ' . $jam;
    }
}

if (! function_exists('labelPengunjung')) {
    /**
     * Mengembalikan label tampilan pengunjung berdasarkan tipe grouping (Bulan/Tahun/Hari).
     *
     * @param  string  $lblx     Tipe label: 'Bulan', 'Tahun', atau lainnya (Hari)
     * @param  mixed   $tanggal  Nilai tanggal dari data pengunjung
     */
    function labelPengunjung(string $lblx, $tanggal): string
    {
        return match ($lblx) {
            'Bulan' => getBulan((int) $tanggal) . ' ' . date('Y'),
            'Tahun' => (string) $tanggal,
            default => tgl_indo2($tanggal),
        };
    }
}

if (! function_exists('tahun')) {
    /**
     * Mengembalikan daftar tahun dari $awal hingga tahun berjalan (+ $tambah), descending secara default.
     *
     * @return int[]
     */
    function tahun(?int $awal = null, bool $asc = false, ?int $tambah = null): array
    {
        $akhir = $tambah ? (int) date('Y') + $tambah : (int) date('Y');
        $awal ??= $akhir;
        $tahun = [];

        for ($i = $akhir; $i >= $awal; $i--) {
            $tahun[] = $i;
        }

        if ($asc) {
            sort($tahun);
        }

        return $tahun;
    }
}

if (! function_exists('tgl_indo_in')) {
    /**
     * Mengubah tanggal dari format d-m-Y (opsional dengan jam) menjadi Y-m-d.
     *
     * @param string $tgl          Tanggal sumber.
     * @param string $replace_with Nilai pengganti bila tanggal kosong.
     */
    function tgl_indo_in($tgl, $replace_with = '-')
    {
        if (date_is_empty($tgl)) {
            return $replace_with;
        }
        $tanggal = substr($tgl, 0, 2);
        $bulan   = substr($tgl, 3, 2);
        $tahun   = substr($tgl, 6, 4);
        $jam     = substr($tgl, 11);
        $jam     = $jam === '' ? '' : ' ' . $jam;

        return $tahun . '-' . $bulan . '-' . $tanggal . $jam;
    }
}

if (! function_exists('validate_zip_entries')) {
    /**
     * Memvalidasi seluruh nama entri di dalam file ZIP untuk mencegah path traversal (Zip Slip).
     *
     * @param ZipArchive $zip
     * @return bool|string True jika valid, atau string nama entri ilegal jika tidak valid.
     */
    function validate_zip_entries(ZipArchive $zip): bool|string
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            // Tolak hanya ".." yang menjadi segmen path (path traversal / Zip Slip),
            // bukan ".." yang kebetulan berada di dalam nama file (mis. "foto...jpg").
            // Pemisah "\" sudah ditolak terpisah, jadi cukup periksa segmen "/".
            $traversal = $entry === '..'
                || str_starts_with($entry, '../')
                || str_contains($entry, '/../')
                || str_ends_with($entry, '/..');
            if (
                $traversal ||
                str_starts_with($entry, '/') ||
                str_contains($entry, '\\') ||
                str_contains($entry, ':')
            ) {
                return $entry;
            }
        }

        return true;
    }
}

if (! function_exists('persen')) {
    /**
     * Mengubah nilai desimal menjadi persentase dalam format string.
     *
     * @param float|int $data            Nilai desimal yang ingin dikonversi (misalnya 0.25 menjadi 25).
     * @param string    $simbol          Simbol yang ditambahkan di akhir (default: '%').
     * @param int       $digit           Jumlah digit di belakang koma (default: 2).
     * @param bool      $tampilkanSimbol Apakah simbol akan ditampilkan atau tidak (default: true).
     */
    function persen($data, string $simbol = '%', int $digit = 2, bool $tampilkanSimbol = true): string
    {
        $nilai = is_nan($data) ? 0 : (float) ($data * 100);
        $str   = number_format($nilai, $digit, '.', '');
        $hasil = str_replace('.', ',', $str);

        return $tampilkanSimbol ? $hasil . $simbol : $hasil;
    }
}

if (! function_exists('persen2')) {
    /**
     * Menghitung persentase dari pembilang dan pembagi, lalu mengubahnya ke format string.
     *
     * @param float|int $pembilang       Nilai pembilang.
     * @param float|int $pembagi         Nilai pembagi.
     * @param string    $simbol          Simbol yang ditambahkan di akhir (default: '%').
     * @param int       $digit           Jumlah digit di belakang koma (default: 2).
     * @param bool      $tampilkanSimbol Apakah simbol akan ditampilkan atau tidak (default: true).
     */
    function persen2($pembilang, $pembagi, string $simbol = '%', int $digit = 2, bool $tampilkanSimbol = true): string
    {
        $data = ($pembagi == 0) ? 0 : $pembilang / $pembagi;

        return persen($data, $simbol, $digit, $tampilkanSimbol);
    }
}

if (! function_exists('persen3')) {
    /**
     * Menghitung persentase dari dua angka dan mengembalikannya sebagai string.
     *
     * @param float|int $number          Nilai pembilang.
     * @param float|int $total           Nilai pembagi.
     * @param int       $precision       Jumlah digit di belakang koma (default: 2).
     * @param string    $simbol          Simbol yang ditambahkan di akhir (default: '%').
     * @param bool      $tampilkanSimbol Apakah simbol akan ditampilkan atau tidak (default: true).
     */
    function persen3($number, $total, int $precision = 2, string $simbol = '%', bool $tampilkanSimbol = true): string
    {
        if ($total == 0) {
            return $tampilkanSimbol ? '0' . $simbol : '0';
        }

        $result    = round(($number / $total) * 100, $precision);
        $formatted = str_replace('.', ',', number_format($result, $precision, '.', ''));

        return $tampilkanSimbol ? $formatted . $simbol : $formatted;
    }
}

