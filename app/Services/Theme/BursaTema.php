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

namespace App\Services\Theme;

use Closure;

/**
 * Seam bursa tema — katalog & instalasi tema dari penyedia add-on.
 *
 * Sebelumnya core (Theme controller) langsung meng-`Http::withToken(
 * setting('layanan_opendesa_token'))->get(server_layanan . '/api/v1/themes')`
 * untuk mengambil daftar tema bursa serta mengunduh/mem-verifikasi pesanannya.
 * Itu = pengetahuan langganan (token + host vendor) di core OSS. Kini core hanya
 * bertanya ke seam ini; add-on penyedia langganan (mis. modul Pelanggan) memasang
 * providernya saat boot.
 *
 * Null-object default: tanpa provider, {@see daftar()} kosong dan {@see unduh()}
 * gagal (`null`) — rilis Umum tanpa modul tak menampilkan/mengunduh tema bursa,
 * hanya tema lokal. Ekstraksi & pemindaian ZIP tema tetap milik core (netral);
 * seam hanya menyuplai katalog + unduhan terautentikasi.
 */
class BursaTema
{
    /**
     * @var (Closure(?string): array<int, array<string, mixed>>)|null
     */
    private ?Closure $penyediaDaftar = null;

    /**
     * @var (Closure(string, string): (string|null))|null
     */
    private ?Closure $penyediaValidasiPesanan = null;

    /**
     * @var (Closure(string): (string|null))|null
     */
    private ?Closure $penyediaUnduh = null;

    /**
     * @var (Closure(string): (array<string, mixed>|null))|null
     */
    private ?Closure $penyediaTemaBawaanWilayah = null;

    /**
     * Pasang penyedia katalog tema bursa.
     *
     * @param Closure(?string): array<int, array<string, mixed>> $penyedia
     */
    public function setDaftar(Closure $penyedia): void
    {
        $this->penyediaDaftar = $penyedia;
    }

    /**
     * Pasang penyedia validasi pesanan (memeriksa nama tema terdaftar di pesanan desa).
     *
     * @param Closure(string, string): (string|null) $penyedia
     */
    public function setValidasiPesanan(Closure $penyedia): void
    {
        $this->penyediaValidasiPesanan = $penyedia;
    }

    /**
     * Pasang penyedia unduhan ZIP tema terautentikasi.
     *
     * @param Closure(string): (string|null) $penyedia
     */
    public function setUnduh(Closure $penyedia): void
    {
        $this->penyediaUnduh = $penyedia;
    }

    /**
     * Pasang penyedia "tema bawaan wilayah": diberi kode desa, kembalikan
     * deskriptor tema mitra kabupaten/kota yang jadi bawaan untuk wilayah
     * desa itu (`['nama' => ..., 'alias' => ..., 'url' => ...]`), atau `null`
     * bila tak ada. Dipakai {@see \App\Services\Theme\PenyelesaiTemaBawaan}.
     *
     * @param Closure(string): (array<string, mixed>|null) $penyedia
     */
    public function setTemaBawaanWilayah(Closure $penyedia): void
    {
        $this->penyediaTemaBawaanWilayah = $penyedia;
    }

    /**
     * Daftar tema bursa (sudah dipetakan ke bentuk baris tema core), atau `[]`
     * bila tak ada provider (modul absen).
     *
     * @return array<int, array<string, mixed>>
     */
    public function daftar(?string $kategori): array
    {
        return $this->penyediaDaftar !== null ? (array) ($this->penyediaDaftar)($kategori) : [];
    }

    /**
     * Validasi pesanan tema: kembalikan pesan galat bila nama tidak terdaftar di
     * pesanan desa, atau `null` bila valid / tak ada provider (dibiarkan lolos;
     * unduhan akan gagal aman bila modul absen).
     */
    public function validasiPesanan(string $attribute, string $nama): ?string
    {
        return $this->penyediaValidasiPesanan !== null
            ? ($this->penyediaValidasiPesanan)($attribute, $nama)
            : null;
    }

    /**
     * Unduh ZIP tema bursa terautentikasi ke berkas sementara; kembalikan path-nya,
     * atau `null` bila gagal / tak ada provider (modul absen).
     */
    public function unduh(string $url): ?string
    {
        return $this->penyediaUnduh !== null ? ($this->penyediaUnduh)($url) : null;
    }

    /**
     * Deskriptor tema mitra kabupaten/kota yang jadi bawaan untuk wilayah
     * desa `$kodeDesa`, atau `null` bila tak ada / tak ada provider (modul
     * Pelanggan absen — rilis Umum tanpa modul tak pernah auto-pasang tema
     * mitra).
     *
     * @return array<string, mixed>|null
     */
    public function temaBawaanWilayah(string $kodeDesa): ?array
    {
        if ($this->penyediaTemaBawaanWilayah === null) {
            return null;
        }

        $hasil = ($this->penyediaTemaBawaanWilayah)($kodeDesa);

        return is_array($hasil) && $hasil !== [] ? $hasil : null;
    }
}
