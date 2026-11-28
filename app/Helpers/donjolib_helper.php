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
