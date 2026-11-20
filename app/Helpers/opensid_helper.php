<?php

use App\Models\SettingAplikasi;
use Illuminate\Support\Str;

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

if (! function_exists('namafile')) {
    /**
     * Mengembalikan string nama file yang diubah menjadi format slug dengan pemisah underscore
     * dan menambahkan tanggal hari ini jika parameter tanggal bernilai true.
     * 
     * @param string $str String yang akan diubah menjadi nama file.
     * @param bool $tanggal Default true. Jika true, maka akan ditambahkan tanggal hari ini dengan format d_m_Y.
     * 
     * @return string Nama file dalam format slug.
     */
    function namafile($str, $tanggal = true): string
    {
        return Str::slug($str, '_') . ($tanggal ? '_' . date('d_m_Y') : '');
    }
}

if (! function_exists('ambilBerkas')) {
    /**
     * Mengambil/mengunduh berkas dengan validasi whitelist dan path traversal.
     */
    function ambilBerkas(
        ?string $nama_berkas,
        $redirect_url = null,
        $unique_id = null,
        ?string $lokasi = null,
        $tampil = false,
        $popup = false,
        $base64 = false,
        string $default = '',
        string $lokasi_default = ''
    ) {
        $lokasi ??= 'desa/arsip/';
        $fcpath  = dirname(__DIR__, 2) . '/';

        // Validasi whitelist dan path traversal
        $gabunganPath = $lokasi . $nama_berkas;
        $gabunganPath = str_replace('\\', '/', $gabunganPath);

        if (strpos($gabunganPath, '..') !== false) {
            abort(404, 'Path traversal detected');
        }

        $whitelist = [
            'desa/upload/',
            'desa/media/',
            'desa/arsip/',
            'desa/logo/',
            'desa/themes/',
            'desa/anjungan/',
            'assets/',
            'template-surat/',
            'storage/app/template/impor/'
        ];

        $isValid = false;
        foreach ($whitelist as $allowedDir) {
            if (strpos($gabunganPath, $allowedDir) === 0) {
                $isValid = true;
                break;
            }
        }

        if (! $isValid) {
            abort(404, 'File path not whitelisted');
        }

        // Validasi nama berkas
        if (! preg_match('/^(?:[a-z0-9_-]|\.(?!\.))+$/iD', $nama_berkas)) {
            $pesan = 'Nama berkas tidak valid';
            if ($redirect_url) {
                if ($popup) {
                    echo $pesan;
                    exit;
                }
                session()->flash('error', $pesan);
                return redirect($redirect_url);
            }
            abort(404, $pesan);
        }

        // Tentukan path berkas utama
        $pathBerkas = $fcpath . $lokasi . $nama_berkas;
        $pathBerkas = str_replace('/', DIRECTORY_SEPARATOR, $pathBerkas);

        // Jika berkas tidak ditemukan, gunakan file default
        if (! file_exists($pathBerkas) && ! empty($default)) {
            $nama_berkas = $default;
            $lokasi      = ! empty($lokasi_default) ? $lokasi_default : $lokasi;
            $pathBerkas  = $fcpath . $lokasi . $default;
            $pathBerkas  = str_replace('/', DIRECTORY_SEPARATOR, $pathBerkas);
        }

        // Jika tetap tidak ditemukan, tampilkan error
        if (! file_exists($pathBerkas)) {
            $pesan = 'Berkas tidak ditemukan';
            if ($redirect_url) {
                if ($popup) {
                    echo $pesan;
                    exit;
                }
                session()->flash('error', $pesan);
                return redirect($redirect_url);
            }
            abort(404, $pesan);
        }

        // Jika unique_id diberikan, ubah nama file
        if (null !== $unique_id) {
            $nama_berkas_parts = explode($unique_id, $nama_berkas);
            $namaFile          = $nama_berkas_parts[0];
            $ekstensiFile      = explode('.', end($nama_berkas_parts));
            $ekstensiFile      = end($ekstensiFile);
            $nama_berkas       = $namaFile . '.' . $ekstensiFile;
        }

        // Kembalikan base64 jika $base64 true
        if ($base64) {
            return base64_encode(file_get_contents($pathBerkas));
        }

        // Tampilkan inline jika $tampil true
        if ($tampil) {
            return response()->file($pathBerkas, [
                'Content-Disposition' => 'inline; filename="' . $nama_berkas . '"',
                'Cache-Control'       => 'private, no-transform, no-store, must-revalidate'
            ]);
        }

        // Unduh berkas
        return response()->download($pathBerkas, $nama_berkas);
    }
}

if (! function_exists('kode_wilayah')) {
    /**
     * Kode Wilayah Dengan Titik.
     * Mengubah format kode wilayah dari tanpa titik menjadi dengan titik.
     * Contoh: 5201142005 --> 52.01.14.2005
     *
     * @param string $kode_wilayah
     *
     * @return string
     */
    function kode_wilayah($kode_wilayah): string
    {
        $kode_prov_kab_kec = str_split(substr($kode_wilayah, 0, 6), 2);
        $kode_desa         = (strlen($kode_wilayah) > 6) ? '.' . substr($kode_wilayah, 6) : '';

        return implode('.', $kode_prov_kab_kec) . $kode_desa;
    }
}

if (! function_exists('emptyData')) {
    /**
     * Membuat array data kosong berdasarkan field yang ditentukan.
     *
     * @param array $fields
     *
     * @return array
     */
    function emptyData($fields): array
    {
        $data = [];

        foreach ($fields as $key => $value) {
            $data[$value] = '';
        }

        return $data;
    }
}

if (! function_exists('SebutanDesa')) {
    /**
     * Mengganti kata [Desa], [desa], [Pemerintah Desa], [dusun] sesuai pengaturan.
     *
     * @param string|null $params
     *
     * @return string|null
     */
    function SebutanDesa($params = null)
    {
        $replaceWord = ['[Desa]', '[desa]', '[Pemerintah Desa]', '[dusun]'];
        if (! Str::contains($params, $replaceWord)) {
            return $params;
        }

        // Tidak bisa gunakan helper setting karena value belum di load
        $setting = SettingAplikasi::whereIn('key', ['sebutan_desa', 'sebutan_dusun', 'default_tampil_peta_infrastruktur'])->pluck('value', 'key')->toArray();

        return str_replace(
            $replaceWord,
            [ucwords($setting['sebutan_desa']), ucwords($setting['sebutan_desa']), ucwords('Pemerintah ' . $setting['sebutan_desa']), ucwords($setting['sebutan_dusun'])],
            $params
        );
    }
}