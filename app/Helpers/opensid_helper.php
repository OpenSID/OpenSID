<?php

use App\Models\FormatSurat;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\SuratDinas;
use App\Models\User;
use App\Models\UserGrup;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * VERSI
 *
 * Versi OpenSID
 */
define('VERSION', '2709.0.0');

/**
 * VERSI_DATABASE
 * Ubah setiap kali mengubah struktur database atau melakukan proses rilis (tgl 01)
 * Simpan nilai ini di tabel migrasi untuk menandakan sudah migrasi ke versi ini
 * Versi database = [yyyymmdd][nomor urut dua digit]
 * [nomor urut dua digit] : 01 => rilis umum, 71 => rilis premium,
 *
 * Varsi database jika premium = 2025061501, jika umum = 2024101651 (6 bulan setelah rilis premium, namun rilis beta)
 */
define('VERSI_DATABASE', '2026091371');

// Kode laporan statistik
define('JUMLAH', 666);
define('BELUM_MENGISI', 777);
define('TOTAL', 888);

// Kode laporan mandiri di tabel komentar
define('LAPORAN_MANDIRI', 'pesan-mandiri');

// Kode artikel terkait agenda
define('AGENDA', 'agenda');

define('MAX_PINDAH', 7);
define('MAX_ANGGOTA', 7);
define('SASARAN', serialize([
    '1' => 'Penduduk',
    '2' => 'Keluarga / KK',
    '3' => 'Rumah Tangga',
    '4' => 'Kelompok/Organisasi Kemasyarakatan',
]));
define('ASALDANA', serialize([
    'Pusat'             => 'Pusat',
    'Provinsi'          => 'Provinsi',
    'Kab/Kota'          => 'Kab/Kota',
    'Dana Desa'         => 'Dana Desa',
    'Lain-lain (Hibah)' => 'Lain-lain (Hibah)',
]));

define('TEMPAT_DILAHIRKAN', serialize([
    'RS/RB'     => '1',
    'Puskesmas' => '2',
    'Polindes'  => '3',
    'Rumah'     => '4',
    'Lainnya'   => '5',
]));
define('JENIS_KELAHIRAN', serialize([
    'Tunggal'  => '1',
    'Kembar 2' => '2',
    'Kembar 3' => '3',
    'Kembar 4' => '4',
]));
define('PENOLONG_KELAHIRAN', serialize([
    'Dokter'        => '1',
    'Bidan Perawat' => '2',
    'Dukun'         => '3',
    'Lainnya'       => '4',
]));
define('JENIS_MUTASI', serialize([
    'Hapus barang masih baik' => '1',
    'Hapus barang rusak'      => '4',
    'Status rusak'            => '2',
    'Status diperbaiki'       => '3',
]));
define('JENIS_PENGHAPUSAN', serialize([
    'Rusak'     => '1',
    'Dijual'    => '2',
    'Disumbang' => '3',
]));
define('ASAL_INVENTARIS', serialize([
    'Dibeli Sendiri'     => '1',
    'Bantuan Pemerintah' => '2',
    'Bantuan Provinsi'   => '3',
    'Bantuan Kabupaten'  => '4',
    'Sumbangan'          => '5',
]));
define('KATEGORI_MAILBOX', serialize([
    'Kotak Masuk'  => '1',
    'Kotak Keluar' => '2',
]));

define('NILAI_PENDAPAT', serialize([
    1 => 'Sangat Puas',
    2 => 'Puas',
    3 => 'Cukup',
    4 => 'Buruk',
]));

define('PENGGUNAAN_BARANG', serialize([
    '01' => 'Pemerintah Desa',
    '02' => 'Badan Permusyawaratan Desa',
    '03' => 'PKK',
    '04' => 'LKMD',
    '05' => 'Karang Taruna',
    '06' => 'RW',
    '07' => 'Puskesdes',
]));

if (! function_exists('currentVersion')) {
    /**
     * Ambil Current Version
     *
     * Mengembalikan nomor current_version
     */
    function currentVersion(): string
    {
        return substr_replace(substr(VERSION, 0, 4), '.', 2, 0);
    }
}

if (! function_exists('favico_desa')) {
    /**
     * Mengembalikan path lengkap untuk file favicon desa
     */
    function favico_desa($favico = 'favicon.ico'): string
    {
        // Tiga lokasi dengan tambatan berbeda: desa/logo tetap di root proyek,
        // sedangkan assets/ dan favicon.ico ikut pindah ke public/ bersama
        // index.php. Kunci array tetap path relatif karena dipakai sebagai URL.
        $lokasi = [
            LOKASI_LOGO_DESA . $favico  => base_path(LOKASI_LOGO_DESA . $favico),
            LOKASI_FILES_LOGO . $favico => public_path(LOKASI_FILES_LOGO . $favico),
            $favico                     => public_path($favico),
        ];

        foreach ($lokasi as $path => $full) {
            if (is_file($full)) {
                return preg_replace('#/index\.php(?=/|$)#i', '', url($path)) . '?v=' . md5_file($full);
            }
        }

        return preg_replace('#/index\.php(?=/|$)#i', '', url($favico));
    }
}

if (! function_exists('gambar_desa')) {
    /**
     * gambar_desa / KantorDesa
     *
     * Mengembalikan path lengkap untuk file logo desa / kantor desa
     *
     * @param string|null $nama_file
     * @param mixed $type
     * @param mixed $file
     */
    function gambar_desa(?string $nama_file = null, $type = false, $file = false): string
    {
        $filePath = base_path(str_replace('/', DIRECTORY_SEPARATOR, LOKASI_LOGO_DESA . $nama_file));

        if (is_file($filePath)) {
            $relatif = LOKASI_LOGO_DESA . $nama_file;

            if ($file) {
                return base_path(str_replace('/', DIRECTORY_SEPARATOR, $relatif));
            }

            return preg_replace('#/index\.php(?=/|$)#i', '', url($relatif));
        }

        // type FALSE = logo, TRUE = kantor
        $default = ($type) ? 'opensid_kantor.jpg' : 'opensid_logo.png';
        $relatif = "assets/files/logo/{$default}";

        if ($file) {
            return base_path(str_replace('/', DIRECTORY_SEPARATOR, $relatif));
        }

        return preg_replace('#/index\.php(?=/|$)#i', '', asset(substr($relatif, 7)));
    }
}

if (! function_exists('AmbilVersi')) {
    /**
     * Ambil Versi
     *
     * Mengembalikan nomor versi aplikasi
     */
    function AmbilVersi(): string
    {
        return VERSION;
    }
}

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

if (! function_exists('ambil_id_youtube')) {
    /**
     * Ekstrak ID video YouTube (11 karakter) dari nilai pengaturan anjungan.
     * Menerima ID mentah atau URL (termasuk URL embed), supaya nilai lama yang
     * tersimpan berupa URL utuh tidak menghasilkan src iframe ganda seperti
     * "embed/https://...".
     */
    function ambil_id_youtube(?string $value): ?string
    {
        if (! $value) {
            return $value;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return basename(parse_url($value, PHP_URL_PATH));
        }

        return $value;
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

if (! function_exists('usia')) {
    /**
     * Menghitung usia berdasarkan tanggal lahir.
     *
     * Contoh format: %y Tahun %m Bulan %d Hari
     *
     * @param string      $tanggal_lahir Tanggal lahir.
     * @param string|null $tanggal_akhir Tanggal akhir penghitungan. Jika null, memakai tanggal hari ini.
     * @param string      $format        Format keluaran DateInterval.
     */
    function usia($tanggal_lahir, $tanggal_akhir = null, $format = '%y Tahun'): string
    {
        $tanggal_akhir ??= date('Y-m-d');
        $tanggal_lahir = Carbon::parse($tanggal_lahir);
        $tanggal_akhir = Carbon::parse($tanggal_akhir);
        $usia          = $tanggal_lahir->diff($tanggal_akhir);

        return $usia->format($format);
    }
}

if (! function_exists('bulan_romawi')) {
    /**
     * Mengubah angka bulan (1-12) menjadi angka Romawi.
     *
     * @param int $bulan Angka bulan 1-12.
     *
     * @return string|false Angka Romawi, atau false bila di luar rentang.
     */
    function bulan_romawi($bulan)
    {
        if ($bulan < 1 || $bulan > 12) {
            return false;
        }

        $bulan_romawi = [
            1  => 'I',
            2  => 'II',
            3  => 'III',
            4  => 'IV',
            5  => 'V',
            6  => 'VI',
            7  => 'VII',
            8  => 'VIII',
            9  => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $bulan_romawi[$bulan];
    }
}

if (! function_exists('bilangan')) {
    /**
     * Sanitasi string agar hanya berisi angka.
     *
     * @param string|null $str
     */
    function bilangan($str)
    {
        if ($str == null) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', strip_tags($str));
    }
}

if (! function_exists('email')) {
    /**
     * Sanitasi string email (alpha, numeric, titik, strip, dan tanda et).
     */
    function email($str): ?string
    {
        return preg_replace('/[^a-zA-Z0-9@._\\-]/', '', htmlentities($str));
    }
}

if (! function_exists('nama')) {
    /**
     * Sanitasi nama (alpha, spasi, titik, koma, tanda petik, dan strip).
     */
    function nama($str): ?string
    {
        return preg_replace("/[^a-zA-Z '\\.,\\-]/", '', strip_tags($str));
    }
}

if (! function_exists('nama_terbatas')) {
    /**
     * Sanitasi nama terbatas (alfanumerik, spasi, slash, dan strip).
     */
    function nama_terbatas($str)
    {
        return preg_replace('/[^a-zA-Z0-9 \\/\\-]/', '', $str);
    }
}

if (! function_exists('nomor_surat_keputusan')) {
    /**
     * Sanitasi nomor surat keputusan (alfanumerik, spasi, titik, strip, slash, dan koma).
     */
    function nomor_surat_keputusan($str)
    {
        return preg_replace('/[^a-zA-Z0-9 \.\-\/,]/', '', $str);
    }
}

if (! function_exists('alfanumerik')) {
    /**
     * Sanitasi string agar hanya berisi karakter alfanumerik.
     */
    function alfanumerik($str): ?string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', htmlentities($str));
    }
}

if (! function_exists('sql_in_list')) {
    /**
     * Mengubah array menjadi daftar nilai ber-kutip untuk klausa SQL IN.
     * Contoh: ['a', 'b'] menjadi "'a', 'b'".
     *
     * @param array $list_array
     *
     * @return string|false
     */
    function sql_in_list($list_array)
    {
        if (empty($list_array)) {
            return false;
        }

        $prefix = $list = '';

        foreach ($list_array as $value) {
            $list .= $prefix . "'" . $value . "'";
            $prefix = ', ';
        }

        return $list;
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
        // Gunakan FCPATH jika tersedia (multisite), fallback ke dirname
        $fcpath  = defined('FCPATH') ? FCPATH : (dirname(__DIR__, 2) . '/');

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
            'desa/pengaturan/',
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
                'Content-Type'              => mime_content_type($pathBerkas),
                'Content-Disposition'       => 'inline; filename="' . $nama_berkas . '"',
                'Expires'                   => '0',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length'            => filesize($pathBerkas),
                'Cache-Control'             => 'private, no-transform, no-store, must-revalidate'
            ])->send();
        }

        // Unduh berkas
        return response()->download($pathBerkas, $nama_berkas)->send();
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

if (! function_exists('default_file')) {
    /**
     * Fungsi untuk menentukan URL file yang akan digunakan, dengan prioritas:
     * 1. Jika $new_file diberikan dan merupakan file yang valid, gunakan URL dari file tersebut.
     * 2. Jika $new_file tidak valid atau tidak diberikan, gunakan $default sebagai fallback.
     *
     * @param string|null $new_file Path file baru yang ingin digunakan (relatif terhadap base_path()).
     * @param string|null $default  Path file default yang akan digunakan jika $new_file
     */
    function default_file(?string $new_file = null, ?string $default = null): ?string
    {
        $new_file = trim((string) $new_file);
        $isSafe   = $new_file !== '' && ! str_contains($new_file, '..');

        // Berkas aset berada di public/assets sejak index.php pindah ke public/,
        // sedangkan desa/ dan storage/ tetap di root proyek. Nilai yang
        // dikembalikan tetap path relatif agar pemakaian sebagai URL tidak
        // berubah — yang ditambatkan hanya pemeriksaan keberadaan berkasnya.
        $absolut = str_starts_with(strtolower($new_file), 'assets/')
            ? public_path($new_file)
            : base_path($new_file);

        $path = ($isSafe && is_file($absolut)) ? $new_file : null;

        if ($path === null) {
            $default = trim((string) $default);

            if ($default === '') {
                return null;
            }

            if (stripos($default, 'modules/') !== false) {
                return $default;
            }

            $path = $default;
        }

        $url = str_starts_with(strtolower($path), 'assets/')
            ? asset(substr($path, 7))
            : url($path);

        return preg_replace('#/index\.php(?=/|$)#i', '', $url);
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

if (! function_exists('get_extension')) {
    /**
     * Mengambil ekstensi file dari nama file.
     *
     * @param string $filename
     *
     * @return string
     */
    function get_extension($filename): string
    {
        // Jika file diawali titik dan tidak mengandung titik lain, ini adalah hidden file tanpa extension (misal .htaccess)
        if (strpos($filename, '.') === 0 && strpos($filename, '.', 1) === false) {
            return '';
        }

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        return $ext ? '.' . strtolower($ext) : '';
    }
}

if (! function_exists('isPHP')) {
    /**
     * Deteksi apakah file berisi kode script PHP
     *
     * @param string $file Path file yang akan diperiksa
     * @param string $filename Nama file yang akan diperiksa (untuk deteksi ekst
     */
    function isPHP($file, $filename): bool
    {
        $ext = get_extension($filename);
        if ($ext === '.php') {
            return true;
        }

        if (! file_exists($file)) {
            return false;
        }

        $handle = fopen($file, 'rb');
        if (! $handle) {
            return false;
        }

        // Polyglot files biasanya menyisipkan PHP setelah magic bytes (di awal)
        // EXIF data juga terletak di awal file, jadi 1MB sudah cukup
        $buffer = fread($handle, 1024 * 1024);
        fclose($handle);

        if (empty($buffer)) {
            return false;
        }

        // Jalankan pattern matching pada SEMUA file untuk deteksi polyglot
        // Tidak ada early-return berdasarkan tipe atau magic bytes

        // Deteksi PHP opening tags dengan variasi spacing/newline/parenthesis
        // Menangkap: <?php, <?= dengan atau tanpa spasi, <?=(variable)
        if (preg_match('/<\?php[\s\(\r\n]|<\?=[\s\$]/i', $buffer)) {
            return true;
        }

        // Deteksi short tag di akhir file
        if (preg_match('/<\?php$|<\?=$/i', $buffer)) {
            return true;
        }

        // Deteksi script tag dengan language=php attribute
        if (preg_match('/<script\s+language\s*=\s*["\']?php["\']?/i', $buffer)) {
            return true;
        }

        // Deteksi halt compiler (sering digunakan dalam polyglot/obfuscator)
        return (bool) (preg_match('/__halt_compiler\s*\(/i', $buffer));
    }
}

if (! function_exists('kades')) {
    /**
     * - Fungsi untuk mengambil data jabatan kades.
     *
     * @return array|object
     */
    function kades()
    {
        return RefJabatan::getKades();
    }
}

if (! function_exists('sekdes')) {
    /**
     * - Fungsi untuk mengambil data jabatan sekdes.
     *
     * @return array|object
     */
    function sekdes()
    {
        return RefJabatan::getSekdes();
    }
}

if (! function_exists('getSuratBawaanTinyMCE')) {
    /**
     * Mengambil daftar template surat bawaan TinyMCE dari file JSON.
     *
     * @param string|null $url_surat Jika diberikan, hanya mengembalikan template dengan URL surat tertentu.
     *
     * @return \Illuminate\Support\Collection
     */
    function getSuratBawaanTinyMCE($url_surat = null)
    {
        $list_data = file_get_contents(DEFAULT_LOKASI_IMPOR . 'template-surat-tinymce.json');

        return collect(json_decode($list_data, true))
            ->when($url_surat, static fn ($collection) => $collection->where('url_surat', $url_surat))->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa', 'kunci')->toArray());
    }
}

if (! function_exists('restoreSuratBawaanTinyMCE')) {
    /**
     * Mengembalikan template surat bawaan TinyMCE ke database.
     *
     * @param int|null $id ID konfigurasi desa. Jika null, akan menggunakan ID dari identitas desa saat ini.
     * @return void
     */
    function restoreSuratBawaanTinyMCE($id = null)
    {
        $id ??= identitas('id');
        $suratFormats = FormatSurat::withoutConfigId($id)
            ->where('jenis', 3)
            ->get()->keyBy('url_surat');

        $suratBawaanTinyMCE = getSuratBawaanTinyMCE();

        foreach ($suratBawaanTinyMCE as $defaultSurat) {
            $defaultSurat['config_id']  = $id;
            $defaultSurat['form_isian'] = isset($defaultSurat['form_isian']) ? json_encode($defaultSurat['form_isian']) : null;
            $defaultSurat['kode_isian'] = isset($defaultSurat['kode_isian']) ? json_encode($defaultSurat['kode_isian']) : null;
            $urlSurat                   = $defaultSurat['url_surat'];
            if (isset($suratFormats[$urlSurat])) {
                $defaultSurat['kunci']   = $suratFormats[$urlSurat]->kunci;
                $defaultSurat['favorit'] = $suratFormats[$urlSurat]->favorit;
            }
            FormatSurat::withoutConfigId($id)->upsert($defaultSurat, ['url_surat', 'config_id']);
        }
    }
}

if (! function_exists('getSuratBawaanDinasTinyMCE')) {
    /**
     * Mengambil daftar template surat dinas bawaan TinyMCE dari file JSON.
     *
     * @param string|null $url_surat Jika diberikan, hanya mengembalikan template dengan URL surat tertentu.
     *
     * @return \Illuminate\Support\Collection
     */
    function getSuratBawaanDinasTinyMCE($url_surat = null)
    {
        $list_data = file_get_contents(DEFAULT_LOKASI_IMPOR . 'template-surat-dinas-tinymce.json');

        return collect(json_decode($list_data, true))
            ->when($url_surat, static fn ($collection) => $collection->where('url_surat', $url_surat))->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa', 'kunci', 'qr_code_tte')->toArray());
    }
}

if (! function_exists('restoreSuratBawaanDinasTinyMCE')) {
    /**
     * Mengembalikan template surat dinas bawaan TinyMCE ke database.
     *
     * @param int|null $id ID konfigurasi desa. Jika null, akan menggunakan ID dari identitas desa saat ini.
     * @return void
     */
    function restoreSuratBawaanDinasTinyMCE($id = null)
    {
        $id ??= identitas('id');

        $suratFormats = SuratDinas::withoutConfigId($id)
            ->where('jenis', 3)
            ->get()->keyBy('url_surat');

        $suratBawaanTinyMCE = getSuratBawaanDinasTinyMCE();

        foreach ($suratBawaanTinyMCE as $defaultSurat) {
            $defaultSurat['config_id']  = $id;
            $defaultSurat['form_isian'] = $defaultSurat['form_isian'] ? json_encode($defaultSurat['form_isian']) : null;
            $defaultSurat['kode_isian'] = $defaultSurat['kode_isian'] ? json_encode($defaultSurat['kode_isian']) : null;
            $urlSurat                   = $defaultSurat['url_surat'];
            if (isset($suratFormats[$urlSurat])) {
                $defaultSurat['kunci']   = $suratFormats[$urlSurat]->kunci;
                $defaultSurat['favorit'] = $suratFormats[$urlSurat]->favorit;
            }
            SuratDinas::withoutConfigId($id)->upsert($defaultSurat, ['url_surat', 'config_id']);
        }
    }
}

if (! function_exists('php_cli_binary')) {
    /**
     * Cari biner PHP CLI untuk menjalankan job latar belakang lewat shell_exec.
     *
     * Saat request web dilayani oleh PHP-FPM, PHP_BINARY menunjuk ke `php-fpm`
     * yang tidak bisa dipakai untuk `php -f index.php …`. Lokasi biner CLI-nya
     * berbeda-beda tergantung lingkungan:
     *   Homebrew macOS : …/sbin/php-fpm        → …/bin/php
     *   Docker         : /usr/local/bin/php-fpm → /usr/local/bin/php
     *   Ubuntu/Debian  : /usr/sbin/php-fpm8.4   → /usr/bin/php8.4
     *   Local/Laragon  : …/bin/php-fpm          → …/bin/php
     */
    function php_cli_binary(): string
    {
        $binary = PHP_BINARY;

        if (! str_contains(basename($binary), 'fpm')) {
            return $binary; // sudah biner CLI (mis. apache mod_php, konteks CLI)
        }

        $ver = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        $dir = dirname($binary);

        $candidates = [
            $dir . '/php',                                          // Docker, Local by Flywheel
            $dir . '/php' . $ver,                                   // versi dalam dir yang sama
            preg_replace('|/sbin$|', '/bin', $dir) . '/php',        // Homebrew (sbin→bin)
            preg_replace('|/sbin$|', '/bin', $dir) . '/php' . $ver, // Ubuntu (sbin→bin, berversi)
            '/usr/bin/php' . $ver,
            '/usr/local/bin/php' . $ver,
            '/usr/bin/php',
            '/usr/local/bin/php',
        ];

        foreach ($candidates as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        return $binary;
    }
}

if (! function_exists('super_admin')) {
    /**
     * Mengambil ID user super admin.
     */
    function super_admin(): int
    {
        return User::superAdmin()->firstOrFail()->id;
    }
}

if (! function_exists('is_super_admin')) {
    /**
     * Cek apakah user yang sedang login adalah super admin.
     */
    function is_super_admin(): bool
    {
        return (int) auth('admin')->id() === super_admin();
    }
}

if (! function_exists('is_group_administrator')) {
    /**
     * Cek apakah user yang sedang login adalah administrator grup.
     */
    function is_group_administrator(): bool
    {
        return (int) auth('admin')->user()->id_grup === UserGrup::getGrupId(UserGrup::ADMINISTRATOR);
    }
}

if (! function_exists('get_domain')) {
    /**
     * Mengambil domain dari URL.
     *
     * @param string $url URL yang akan diambil domainnya.
     */
    function get_domain($url): string
    {
        $parsed = parse_url($url);

        if ($parsed === false || ! isset($parsed['host'])) {
            return '';
        }

        return $parsed['host'];
    }
}

if (! function_exists('pantau_is_down')) {
    /**
     * Cek apakah pantau server sedang down berdasarkan circuit breaker cache
     */
    function pantau_is_down(): bool
    {
        $cache_key = 'pantau_server_down';

        return cache()->has($cache_key);
    }
}

if (! function_exists('get_external_ip')) {
    /**
     * Mengambil IP eksternal dari server.
     *
     * @return string|null
     */
    function get_external_ip()
    {
        // Batasi waktu mencoba
        $options = stream_context_create([
            'http' => [
                'timeout' => 2, //2 seconds
            ],
        ]);

        try {
            $externalContent = file_get_contents('http://checkip.dyndns.com/', false, $options);
            preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $externalContent, $m);
        } catch (\Exception $e) {
            return null;
        }

        return $m[0];
    }
}

if (! function_exists('httpPost')) {
    /**
     * Untuk mengirim data ke OpenSID tracker.
     *
     * Timeout sengaja dibuat pendek (2 detik + 1 detik connect) karena
     * fungsi ini dipanggil sinkron pada request web (mis. Tracker::kirimData),
     * sehingga user tidak ikut menunggu lama ketika pantau lambat/mati.
     *
     * Retry juga dibatasi (2 attempts, jeda 200ms) agar worst case user pertama
     * tidak melebihi timeout lama (< 5 detik). Setelah itu circuit breaker aktif
     * dan request berikutnya langsung di-skip.
     *
     * @param mixed $url
     * @param mixed $params
     */
    function httpPost($url, $params): ?string
    {
        // Cek apakah pantau sedang down, skip jika masih dalam backoff period
        if (pantau_is_down()) {
            logger()->info('Skipping pantau request - server marked as down (circuit breaker active)');

            return null;
        }

        $max_retries = 2;
        $attempt     = 0;

        while ($attempt < $max_retries) {
            try {
                $response = (new Client())->post($url, [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Authorization'    => 'Bearer ' . config_item('token_pantau'),
                    ],
                    'form_params'     => $params,
                    'timeout'         => 2,
                    'connect_timeout' => 1,
                ]);

                // Success! Reset pantau status dan return response
                reset_pantau_status();

                return $response->getBody()->getContents();
            } catch (ClientException $cx) {
                logger()->error('Pantau request ClientException (attempt ' . ($attempt + 1) . '): ' . $cx->getMessage());
                $attempt++;

                if ($attempt < $max_retries) {
                    // Jeda singkat antar retry (200ms) — hindari blocking sleep panjang
                    usleep(200000);
                }
            } catch (Exception $e) {
                logger()->error('Pantau request Exception (attempt ' . ($attempt + 1) . '): ' . $e->getMessage());
                $attempt++;

                if ($attempt < $max_retries) {
                    usleep(200000);
                }
            }
        }

        // Semua retry gagal, tandai pantau sebagai down
        $current_attempts = get_pantau_attempt_count() + 1;
        mark_pantau_down($current_attempts);

        logger()->error('Pantau request failed after ' . $max_retries . ' attempts');

        return null;
    }
}

if (! function_exists('reset_pantau_status')) {
    /**
     * Reset status pantau server menjadi normal (tidak down).
     */
    function reset_pantau_status(): void
    {
        cache()->forget('pantau_server_down');
        cache()->forget('pantau_attempt_count');
    }
}

if (! function_exists('get_pantau_attempt_count')) {
    /**
     * Mengambil jumlah percobaan koneksi pantau yang gagal.
     *
     * @return int
     */
    function get_pantau_attempt_count(): int
    {
        return cache()->get('pantau_attempt_count', 0);
    }
}

if (! function_exists('get_dynamic_title_page_from_path')) {
    /**
     * Mengambil judul halaman dinamis dari path request.
     */
    function get_dynamic_title_page_from_path(): string
    {
        $parse = str_replace([
            '/first',
        ], '', '/' . request()->path());

        $explo = explode('/', $parse);

        $title   = '';
        $counter = count($explo);

        for ($i = 0; $i < $counter; $i++) {
            $t = trim($explo[$i]);
            if ($t !== '' && $t !== '1' && $t !== '0') {
                $title .= ((is_numeric($t)) ? ' ' : ' - ') . $t;
            }
        }

        return ucwords(str_replace([
            '  ',
            '_',
        ], ' ', $title));
    }
}

if (! function_exists('cek_koneksi_internet')) {
    /**
     * Cek ada koneksi internet.
     *
     * @param string $sCheckHost Default: www.google.com
     */
    function cek_koneksi_internet(string $sCheckHost = 'www.google.com'): bool
    {
        if (! setting('notifikasi_koneksi')) {
            return true;
        }

        $connected = @fsockopen($sCheckHost, 80, $errno, $errstr, 5);

        if ($connected) {
            fclose($connected);

            return true;
        }
        logger()->error('Gagal menghubungi ' . $sCheckHost . ' dengan status error ' . $errno . ' - ' . $errstr);

        return false;
    }
}

if (! function_exists('isLocalIPAddress')) {
    function isLocalIPAddress($IPAddress): bool
    {
        if (strpos($IPAddress, '127.0.') === 0) {
            return true;
        }

        return ! filter_var($IPAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}

