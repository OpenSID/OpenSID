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

use Illuminate\Support\Facades\Log;

/**
 * Gerbang kompatibilitas versi tema <-> versi core (`min_core`/`max_core`),
 * padanan {@see \App\Traits\ModuleMigrations::pesanMinCoreTakTerpenuhi()} untuk
 * tema. Lihat premium#7026 dan `rencana-refaktor-tema-siappakai.md` §1.6.
 *
 * Sebuah rilis tema mendeklarasikan di `theme.json`-nya rentang versi core
 * tempat build itu bekerja: `min_core` = lantai (floor), `max_core` =
 * langit-langit (ceiling). Kosong = tak dibatasi di sisi itu. Gerbang ini
 * dipanggil di titik AKTIVASI ({@see \App\Actions\Theme\ActivateTheme}) dan
 * titik UNDUH bursa ({@see ThemeZipExtractor}) supaya tenant tak pernah
 * mengaktifkan/memasang build tema yang mengasumsikan partial/Blade core yang
 * belum ada di versi core yang berjalan.
 *
 * Rilis Premium 2610 adalah rilis pertama yang membawa gerbang ini untuk tema;
 * nilai baseline awal `min_core` = versi rilis 2610 (`max_core` sengaja
 * dikosongkan). Perbandingan `version_compare` datar sudah benar untuk lini
 * Premium (26xx) MAUPUN Umum (27xx) selama `max_core` kosong: `min_core`
 * `2610.0.0` tidak memblokir Umum 2709+ karena `2709 > 2610`. Begitu `max_core`
 * mulai diisi, ini butuh pemetaan tahun-bulan lintas-lini (lihat
 * `App\Support\CoreVersion` di Layanan#467 / premium#7026) agar tak keliru
 * memblokir lini Umum.
 */
class KompatibilitasCoreTema
{
    /**
     * Periksa kompatibilitas core sebuah folder tema, DENGAN bypass
     * `ENVIRONMENT=development` (dicatat via Log::info -- bukan diam-diam --
     * supaya bypass tetap terlihat di log bila lingkungan `development`
     * ternyata bocor ke instalasi yang seharusnya produksi, pola sama
     * {@see \App\Traits\ModuleMigrations::periksaMinCoreModul()}).
     *
     * @param string $temaDir Folder tema (berisi `theme.json`), absolut atau
     *                        relatif terhadap root aplikasi.
     *
     * @return string|null Pesan galat bila tak kompatibel, null bila kompatibel/tak relevan.
     */
    public static function periksa(string $temaDir): ?string
    {
        if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) === 'development') {
            Log::info("Penegakan kompatibilitas core tema dilewati (ENVIRONMENT=development): {$temaDir}");

            return null;
        }

        return self::pesanTakTerpenuhi($temaDir);
    }

    /**
     * Logika murni (tanpa bypass lingkungan): baca `min_core`/`max_core` dari
     * `theme.json` di `$temaDir` dan bandingkan dengan {@see VERSION} core.
     *
     * @param string $temaDir Folder tema (berisi `theme.json`).
     *
     * @return string|null Pesan galat bila {@see VERSION} di luar rentang, null bila kompatibel/tak relevan.
     */
    public static function pesanTakTerpenuhi(string $temaDir): ?string
    {
        $manifest = rtrim($temaDir, '/\\') . '/theme.json';
        if (! is_file($manifest)) {
            return null;
        }

        $meta = json_decode((string) file_get_contents($manifest), true);
        if (! is_array($meta)) {
            return null;
        }

        return self::pesanRentang($meta['min_core'] ?? null, $meta['max_core'] ?? null, VERSION);
    }

    /**
     * Logika perbandingan murni: pesan galat bila `$coreVersion` di luar
     * rentang `[$minCore, $maxCore]`, atau null bila di dalam / tak dibatasi.
     * Dipisah supaya bisa diuji lepas dari berkas & dipakai ulang (mis. sisi
     * modul, lihat premium#6869) tanpa menyeret pembacaan manifest.
     */
    public static function pesanRentang(?string $minCore, ?string $maxCore, string $coreVersion): ?string
    {
        if (! empty($minCore) && version_compare($coreVersion, (string) $minCore, '<')) {
            return "Tema ini membutuhkan OpenSID minimal versi {$minCore}; versi terpasang {$coreVersion}.";
        }

        if (! empty($maxCore) && version_compare($coreVersion, (string) $maxCore, '>')) {
            return "Tema ini hanya kompatibel hingga OpenSID versi {$maxCore}; versi terpasang {$coreVersion}. Gunakan versi tema yang lebih baru.";
        }

        return null;
    }
}
