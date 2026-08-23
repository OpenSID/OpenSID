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

namespace App\Actions\Theme;

use App\Enums\AktifEnum;
use App\Models\Theme;
use App\Services\Kapabilitas\GerbangFitur;
use App\Services\Theme\SumberTemaBursa;
use Exception;
use Illuminate\Support\Facades\Artisan;

class ActivateTheme
{
    /**
     * Mengaktifkan tema dan menonaktifkan tema lainnya.
     *
     * @param int|string $idOrSlug ID atau slug tema
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function handle(int|string $idOrSlug): Theme
    {
        $theme = Theme::where(static function ($q) use ($idOrSlug): void {
                $q->where('id', $idOrSlug)
                    ->orWhere('slug', $idOrSlug);
            })
            ->first();

        if (! $theme) {
            throw new Exception("Theme tidak ditemukan: {$idOrSlug}");
        }

        $this->pastikanBerhakAktivasi($theme);

        $theme->update(['status' => AktifEnum::AKTIF]);

        Theme::where('id', '!=', $theme->id)
            ->update(['status' => AktifEnum::TIDAK_AKTIF]);

        Artisan::call('view:clear');
        cache()->flush();

        return $theme;
    }

    /**
     * @throws Exception bila tema berbayar dan desa ini (di SiapPakai) belum berhak
     */
    private function pastikanBerhakAktivasi(Theme $theme): void
    {
        if (self::berhakAktivasi($theme->kategori, $theme->slug, (string) $theme->nama)) {
            return;
        }

        $pesan = $theme->kategori === Theme::KATEGORI_PREMIUM_EKSKLUSIF
            ? "Tema \"{$theme->nama}\" hanya tersedia gratis selama langganan Premium aktif."
            : "Tema \"{$theme->nama}\" belum dipesan untuk desa ini.";

        throw new Exception($pesan);
    }

    /**
     * Apakah desa ini berhak mengaktifkan tema ini? Dipakai `pastikanBerhakAktivasi()`
     * (melempar bila `false`) MAUPUN kartu tema di UI (`box.blade.php`, yang
     * bekerja dengan array/skalar lepas, bukan instance `Theme` -- makanya
     * method ini menerima primitif, bukan model) untuk memutuskan tombol
     * "Aktifkan" vs tautan pemesanan.
     *
     * KHUSUS tenant SiapPakai (`cache('siappakai')`, flag yang sama dipakai
     * `isSiapPakai()` di `core_helper.php`). Instalasi mandiri TIDAK
     * disentuh sama sekali: perilakunya tetap seperti sebelum perubahan ini
     * (izinkan tanpa syarat) karena tema `premium` di sana hanya pernah ada
     * lokal lewat jalur unduh bursa yang SUDAH tervalidasi
     * (`BursaTema::validasiPesanan()` saat `theme/unduh`) -- tidak ada
     * invarian implisit yang berubah.
     *
     * Kebutuhan ini muncul KHUSUS untuk SiapPakai: sinkronisasi Fase 1
     * (dasbor-siappakai/dokumentasi/rencana-refaktor-tema-siappakai.md §1.2)
     * menaruh SELURUH katalog Layanan -- gratis maupun Tema Pro berbayar --
     * ke `storage/app/themes/` yang sama untuk SEMUA tenant lewat symlink
     * pohon kode tunggal, bukan salinan per desa. Baris `Theme` lokal untuk
     * Tema Pro jadi ada di SETIAP tenant walau belum dibayar -- gerbang ini
     * memindahkan pengecekan yang biasanya terjadi di titik UNDUH (mandiri)
     * ke titik PILIH/aktifkan (SiapPakai). Ini BUKAN perbaikan untuk
     * "Isu-1" (portabilitas ZIP antar-desa, `MANAJEMEN_TEMA.md` §13) --
     * desa SiapPakai tak punya jalur unggah/unduh manual sama sekali.
     */
    public static function berhakAktivasi(?string $kategori, ?string $slug, string $nama): bool
    {
        if ($kategori === Theme::KATEGORI_PREMIUM_EKSKLUSIF) {
            // Sama persis PenjagaTemaEksklusif -- dipanggil PREVENTIF di sini
            // juga (bukan cuma reaktif di Web_Controller pada request publik
            // berikutnya), supaya aktivasi gagal cepat. Berlaku SAMA di
            // instalasi mandiri MAUPUN SiapPakai -- tak ada perubahan
            // perilaku, gerbang ini sudah ada & aktif di kedua konteks hari ini.
            return app(GerbangFitur::class)->mengizinkan('premium');
        }

        if ($kategori !== Theme::KATEGORI_PREMIUM) {
            // KATEGORI_UMUM, atau nilai tak dikenal/kosong (mis. baris lama
            // tanpa theme_scan() lengkap) -- gagal terbuka, sama seperti
            // perilaku sebelum gerbang ini ada. theme_scan() sendiri selalu
            // mengisi salah satu dari tiga konstanta yang dikenal, jadi
            // kasus tak dikenal ini seharusnya tidak pernah tercapai di
            // data hasil scan asli.
            return true;
        }

        if (! cache('siappakai')) {
            // Instalasi mandiri: TIDAK berubah, izinkan tanpa syarat.
            return true;
        }

        $bebasEdisiPremium = defined('TEMA_PREMIUM_FREE')
            && in_array($slug, TEMA_PREMIUM_FREE, true)
            && app(GerbangFitur::class)->mengizinkan('premium');

        if ($bebasEdisiPremium) {
            return true;
        }

        // Pencocokan case-insensitive: nama lokal berasal dari composer.json
        // (diproses ucwords() oleh theme_scan()), klaim tema_pro berasal dari
        // Layanan.Themes.nama -- konvensi kedua sisi seharusnya sama, tapi
        // dibuat toleran huruf besar/kecil supaya tak rapuh terhadap variasi
        // kapitalisasi kecil antar repo tema.
        $dipesan = array_map('mb_strtolower', app(SumberTemaBursa::class)->temaProDipesan());

        return in_array(mb_strtolower($nama), $dipesan, true);
    }
}
