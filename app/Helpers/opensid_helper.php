<?php

if (! function_exists('is_kelurahan')) {
    /**
     * Cek apakah kode desa merupakan kelurahan atau bukan.
     */
    function is_kelurahan($kodeWilayah = null): bool
    {
        $kodeDesa = (int) kode_desa($kodeWilayah);

        return is_numeric($kodeDesa) && $kodeDesa < 2000;
    }
}

if (! function_exists('isKelurahan')) {
    /**
     * Cek apakah kode desa merupakan kelurahan atau bukan.
     */
    function isKelurahan($kodeWilayah = null): bool
    {
        trigger_error('Fungsi isKelurahan() sudah tidak digunakan lagi. Gunakan fungsi is_kelurahanan().', E_USER_DEPRECATED);

        return is_kelurahan($kodeWilayah);
    }
}

if (! function_exists('kode_desa')) {
    /**
     * Getter untuk kode desa dari kode wilayah.
     *
     * @param string|null $kodeWilayah Kode wilayah yang akan diambil kode desanya. Jika null, maka akan mengambil dari identitas desa.
     */
    function kode_desa($kodeWilayah = null): string
    {
        $kodeWilayah ??= identitas('kode_desa');

        return substr($kodeWilayah, 6);
    }
}

if (! function_exists('get_nik')) {
    /**
     * Fungsi helper untuk validasi NIK sementara
     *
     * @param string $nik NIK yang akan divalidasi
     */
    function get_nik($nik = '0'): ?string
    {
        if ($nik === '' || substr($nik, 0, 1) === '0') {
            return '0';
        }

        return $nik;
    }
}

if (! function_exists('get_nokk')) {
    /**
     * Fungsi helper untuk validasi NOKK sementara (Alias dari get_nik)
     *
     * @param string $nokk NOKK yang akan divalidasi
     */
    function get_nokk($nokk = '0'): ?string
    {
        return get_nik($nokk);
    }
}
