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
 * Registry penjaga Tema Pro (seam). Core TIDAK memuat aturan kepemilikan, daftar tema Pro, atau
 * kunci produk: ia hanya tahu bahwa tema ber-`kategori` premium/premium-eksklusif adalah Tema Pro
 * dan menanyakan penjaga yang didaftarkan add-on (modul Pelanggan, `GerbangTemaPro`).
 *
 *  - {@see self::berhakAktivasi()}: "boleh tema ini diaktifkan?" -- semua penjaga terdaftar harus
 *    setuju (AND). Tema `umum`/`mitra` selalu lolos; selain itu (termasuk tanpa kategori) ditanyakan ke penjaga.
 *    TANPA penjaga terdaftar, Tema Pro yang dinyatakan (`premium`/`premium-eksklusif`) DITOLAK: instalasi tanpa
 *    modul Pelanggan tak bisa mengaktifkan Tema Pro; tema tanpa kategori dibiarkan lolos.
 *  - {@see self::perluDipaksaTurun()}: pemeriksaan reaktif tiap request publik -- cukup satu penjaga
 *    yang meminta turun (OR). Tanpa penjaga: tak ada yang diturunkan.
 *
 * Catatan keamanan: di Umum core adalah kode GPL yang bisa diedit siapa pun, jadi seam ini bukan
 * pelindung terhadap pengubahan kode di sisi desa; perlindungan nyata ada pada modul Pelanggan
 * terenkripsi, unduhan wajib-pesan di Layanan, dan ketentuan lisensi (SISA_PEKERJAAN T27).
 */
class PenjagaTema
{
    /**
     * Nilai `themes.kategori` / `theme.json`. Sama dengan konstanta `Theme::KATEGORI_*` (dijaga PenjagaTemaTest); ditulis literal supaya berkas ini identik dengan salinannya di pipeline Umum, yang
     * `Theme`-nya (v2601–v2609) belum punya konstanta kategori.
     */
    public const KATEGORI_UMUM = 'umum';

    public const KATEGORI_MITRA = 'mitra';

    public const KATEGORI_PREMIUM = 'premium';

    public const KATEGORI_PREMIUM_EKSKLUSIF = 'premium-eksklusif';

    /** @var list<Closure(?string, ?string, string): bool> */
    private array $penjagaAktivasi = [];

    /** @var list<Closure(string): bool> */
    private array $penjagaTurun = [];

    /**
     * @param Closure(?string $kategori, ?string $slug, string $nama): bool $penjaga true = boleh diaktifkan
     */
    public function daftarkanAktivasi(Closure $penjaga): void
    {
        $this->penjagaAktivasi[] = $penjaga;
    }

    /**
     * @param Closure(string $slugTemaAktif): bool $penjaga true = tema aktif harus diturunkan ke tema gratis
     */
    public function daftarkanTurun(Closure $penjaga): void
    {
        $this->penjagaTurun[] = $penjaga;
    }

    public static function temaPro(?string $kategori): bool
    {
        return in_array($kategori, [self::KATEGORI_PREMIUM, self::KATEGORI_PREMIUM_EKSKLUSIF], true);
    }

    public function berhakAktivasi(?string $kategori, ?string $slug, string $nama): bool
    {
        // Tema gratis yang dinyatakan eksplisit (umum, mitra) selalu lolos.
        if (in_array($kategori, [self::KATEGORI_UMUM, self::KATEGORI_MITRA], true)) {
            return true;
        }

        if ($this->penjagaAktivasi === []) {
            // Tanpa penjaga: hanya Tema Pro yang dinyatakan (kategori premium/premium-eksklusif) yang ditolak.
            // Tema tanpa kategori (mis. buatan desa, atau tema lama tanpa theme.json) tak bisa dikenali core;
            // pengenalan lewat katalog Layanan adalah tugas penjaga.
            return ! self::temaPro($kategori);
        }

        foreach ($this->penjagaAktivasi as $penjaga) {
            if (! $penjaga($kategori, $slug, $nama)) {
                return false;
            }
        }

        return true;
    }

    public function perluDipaksaTurun(string $slugTemaAktif): bool
    {
        foreach ($this->penjagaTurun as $penjaga) {
            if ($penjaga($slugTemaAktif)) {
                return true;
            }
        }

        return false;
    }
}
