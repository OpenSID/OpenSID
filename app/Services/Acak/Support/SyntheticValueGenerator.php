<?php

declare(strict_types=1);

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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services\Acak\Support;

use Carbon\Carbon;

/**
 * Penghasil nilai sintetis deterministik.
 *
 * Menggantikan array nama yang di-hardcode pada `App\Libraries\Acak` lama dan
 * panggilan `fake()` yang tersebar di `AcakDataCommand` lama dengan satu sumber
 * yang di-seed. Nilai diturunkan dari (seed, konteks, kunci) memakai SHA-256,
 * sehingga:
 *   - reproducible: seed yang sama → data demo yang sama;
 *   - tidak bergantung pada RNG global sehingga mudah diuji.
 */
final class SyntheticValueGenerator
{
    /** @var list<string> */
    private const MALE_FIRST = [
        'Bambang', 'Abdul', 'Setiyadi', 'Dadang', 'Herman', 'Agus', 'Slamet',
        'Budi', 'Joko', 'Wahyu', 'Rizki', 'Andi', 'Eko', 'Hendra', 'Iwan',
    ];

    /** @var list<string> */
    private const FEMALE_FIRST = [
        'Yuni', 'Fatima', 'Sarah', 'Dewi', 'Hasnah', 'Siti', 'Ratna', 'Endah',
        'Lestari', 'Wulan', 'Ayu', 'Indah', 'Nur', 'Rina', 'Sari',
    ];

    /** @var list<string> */
    private const FAMILY = [
        'Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Nugroho', 'Saputra',
        'Hidayat', 'Permana', 'Utomo', 'Gunawan', 'Halim', 'Firmansyah',
    ];

    /** @var list<string> */
    private const CITIES = [
        'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Malang', 'Solo',
        'Bogor', 'Bekasi', 'Depok', 'Cirebon', 'Purwokerto', 'Magelang',
    ];

    /** @var list<string> */
    private const DUSUN = [
        'Sumber Rejo', 'Tanjung Sari', 'Suka Maju', 'Sido Makmur', 'Suka Damai',
        'Sejahtera', 'Mekar Jaya', 'Harapan Jaya', 'Tirta Kencana', 'Karya Bakti',
        'Sri Rejeki', 'Mukti Jaya', 'Sari Murni', 'Kerta Bumi', 'Mitra Sejati',
        'Sentosa', 'Subur Makmur', 'Wijaya Kusuma', 'Amerta Jaya', 'Bhakti Mulya',
        'Surya Mandiri', 'Suka Sejahtera', 'Lestari', 'Suka Raharja', 'Bhakti Jaya',
        'Sumber Harapan', 'Suka Rukun', 'Bakti Sejahtera', 'Mukti Rejo', 'Mekar Sejati',
        'Citra Jaya', 'Wana Bakti', 'Tirta Makmur', 'Karya Utama', 'Sri Mulya',
        'Mukti Sejati', 'Sari Jaya', 'Tirta Murni', 'Mitra Jaya', 'Karya Lestari',
        'Sejahtera Abadi', 'Mukti Sari', 'Harapan Baru', 'Suka Mulya', 'Bhakti Rejo',
        'Sumber Jaya', 'Suka Makmur', 'Sari Sejati', 'Kerta Makmur',
    ];

    public function __construct(private readonly string $seed = 'opensid-demo')
    {
    }

    /**
     * Nama lengkap sintetis (huruf besar, konsisten dengan data OpenSID).
     *
     * @param int $sex 1 = laki-laki, selain itu = perempuan
     */
    public function name(int|string $key, int $sex): string
    {
        $pool  = $sex === 1 ? self::MALE_FIRST : self::FEMALE_FIRST;
        $first = $this->pick($pool, 'name-first', (string) $key . '|' . $sex);
        $last  = $this->pick(self::FAMILY, 'name-family', (string) $key);

        return mb_strtoupper($first . ' ' . $last);
    }

    /**
     * Nama depan sintetis tunggal (untuk nama ayah/ibu, huruf besar).
     */
    public function firstName(int|string $key, int $sex): string
    {
        $pool = $sex === 1 ? self::MALE_FIRST : self::FEMALE_FIRST;

        return mb_strtoupper($this->pick($pool, 'first-only', (string) $key . '|' . $sex));
    }

    public function city(int|string $key): string
    {
        return $this->pick(self::CITIES, 'city', (string) $key);
    }

    /**
     * Pilih nama dusun dari daftar sintetis (untuk penomoran unik lihat pemanggil).
     */
    public function dusun(int $index): string
    {
        return self::DUSUN[$index % count(self::DUSUN)];
    }

    public function dusunCount(): int
    {
        return count(self::DUSUN);
    }

    public function phone(int|string $key): string
    {
        return '08' . $this->digits('phone', (string) $key, 10);
    }

    public function email(int|string $key): string
    {
        $first = mb_strtolower($this->pick(array_merge(self::MALE_FIRST, self::FEMALE_FIRST), 'email-user', (string) $key));

        return $first . $this->digits('email-num', (string) $key, 3) . '@example.test';
    }

    public function website(): string
    {
        return 'https://serambi.opendesa.id';
    }

    public function kodePos(int|string $key): string
    {
        return $this->digits('kodepos', (string) $key, 5);
    }

    public function nip(int|string $key): string
    {
        return $this->digits('nip', (string) $key, 18);
    }

    /**
     * NIK 16 digit acak (untuk nik ayah/ibu yang tidak dipetakan ke penduduk nyata).
     */
    public function nik16(int|string $key): string
    {
        return $this->digits('nik16', (string) $key, 16);
    }

    public function aktaLahir(int|string $key): string
    {
        return $this->digits('akta-lahir-a', (string) $key, 5)
            . '-LT-'
            . $this->digits('akta-lahir-b', (string) $key, 6)
            . '-'
            . $this->digits('akta-lahir-c', (string) $key, 4);
    }

    public function aktaPerkawinan(int|string $key): string
    {
        return $this->digits('akta-kawin-a', (string) $key, 3)
            . '/'
            . $this->digits('akta-kawin-b', (string) $key, 2)
            . '/X/'
            . $this->digits('akta-kawin-c', (string) $key, 4);
    }

    public function tagIdCard(int|string $key): string
    {
        return $this->digits('tag-id-card', (string) $key, 10);
    }

    public function noAsuransi(int|string $key): string
    {
        return $this->digits('no-asuransi', (string) $key, 7);
    }

    public function alamat(int|string $key): string
    {
        $jalan = $this->pick(self::DUSUN, 'alamat-jalan', (string) $key);

        return 'Jalan ' . $jalan . ' No. ' . (string) ($this->number('alamat-no', (string) $key, 200) + 1);
    }

    /**
     * Kode wilayah 6 digit untuk awalan NIK/KK. Diambil dari 6 digit pertama
     * nilai asli agar konteks geografis tetap terjaga tanpa mengidentifikasi
     * ulang; jatuh ke $fallback (mis. kode kecamatan) bila asli tidak memadai.
     */
    public function region6(string $original, string $fallback = ''): string
    {
        $digits = preg_replace('/\D/', '', $original) ?? '';
        if (strlen($digits) >= 6) {
            return substr($digits, 0, 6);
        }

        $fb = preg_replace('/\D/', '', $fallback) ?? '';

        return str_pad(substr($fb, 0, 6), 6, '0');
    }

    /**
     * Tanggal sintetis deterministik (Y-m-d): tahun DIPERTAHANKAN dari tanggal
     * asli (realisme umur + reproducibility antar-run), bulan/hari diturunkan dari
     * HMAC(key). Mengembalikan null bila tanggal asli kosong.
     *
     * Karena tahun dipertahankan, menjalankan acak berulang dengan seed sama
     * menghasilkan tanggal yang sama (tidak menggeser seperti addDays()).
     */
    public function syntheticDate(string $context, int|string $key, ?string $original): ?string
    {
        if ($original === null || $original === '' || str_starts_with((string) $original, '0000')) {
            return null;
        }

        try {
            $year = (int) Carbon::parse($original)->year;
        } catch (\Throwable) {
            $year = 1990;
        }
        if ($year < 1900 || $year > 2100) {
            $year = 1990;
        }

        $month = $this->number($context . '-m', (string) $key, 12) + 1;
        $day   = $this->number($context . '-d', (string) $key, 28) + 1;

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    /**
     * Segmen tanggal lahir 6 digit (ddmmyy) untuk NIK. Perempuan ditambah 40
     * pada hari, sesuai konvensi NIK.
     */
    public function nikDob(Carbon $dob, int $sex): string
    {
        $day = $dob->day + ($sex === 2 ? 40 : 0);

        return sprintf('%02d%02d%02d', $day, $dob->month, $dob->year % 100);
    }

    /**
     * Nomor urut 4 digit (1000-9999) deterministik, digeser $offset untuk
     * penelusuran (probing) saat terjadi tabrakan.
     */
    public function seq4(string $context, string $key, int $offset = 0): string
    {
        $base = $this->number($context, $key, 9000);

        return sprintf('%04d', ($base + $offset) % 9000 + 1000);
    }

    /**
     * NIK "yatim" 16 digit deterministik untuk referensi NIK yang tidak dipetakan
     * ke penduduk nyata (mis. di log/pengaduan). Diturunkan dari nilai lama agar
     * stabil antar-pemanggilan.
     */
    public function orphanNik(string $old): string
    {
        return $this->digits('orphan-nik', $old, 16);
    }

    /**
     * No. KK "yatim" 16 digit deterministik untuk referensi no_kk yang tidak
     * ada lagi di tabel keluarga.
     */
    public function orphanKk(string $old): string
    {
        return $this->digits('orphan-kk', $old, 16);
    }

    /**
     * String angka `$length` digit yang deterministik.
     */
    public function digits(string $context, string $key, int $length): string
    {
        $out = '';

        for ($i = 0; $i < $length; $i++) {
            $hex = substr(hash('sha256', $this->seed . '|' . $context . '|' . $key . '|' . $i), 0, 8);
            $out .= (string) (hexdec($hex) % 10);
        }

        return $out;
    }

    /**
     * Integer deterministik 0..($max-1).
     */
    public function number(string $context, string $key, int $max): int
    {
        $hex = substr(hash('sha256', $this->seed . '|' . $context . '|' . $key), 0, 12);

        return $max > 0 ? (int) (hexdec($hex) % $max) : 0;
    }

    /**
     * @param list<string> $pool
     */
    private function pick(array $pool, string $context, string $key): string
    {
        return $pool[$this->number($context, $key, count($pool))];
    }
}
