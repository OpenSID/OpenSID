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

use App\Actions\Theme\ActivateTheme;
use App\Models\Theme;
use Illuminate\Support\Facades\Log;

/**
 * Menyelesaikan "tema bawaan wilayah": bila desa ini berada di dalam
 * kabupaten/kota yang punya tema mitra (mis. Tema Tabanan untuk Kab.
 * Tabanan), pasang tema itu (bila belum ada) dan jadikan tema aktif/default.
 *
 * Prioritas tema default: **tema mitra kabupaten/kota > tema bawaan rilis**.
 * Untuk desa di kabupaten/kota mitra, tema mitra menang atas tema default
 * (esensi/natra) -- padanan Premium (premium#6991), tanpa lapisan Wira
 * (tak ada tema premium-eksklusif di Umum).
 *
 * Sumber data tema mitra adalah seam {@see BursaTema::temaBawaanWilayah()};
 * core tak tahu apa pun tentang Layanan/token. Tanpa modul Pelanggan
 * (mis. rilis Umum polos) seam mengembalikan `null` dan kelas ini no-op.
 */
class PenyelesaiTemaBawaan
{
    /**
     * Slug tema bawaan rilis yang boleh "ditimpa" oleh tema mitra tanpa
     * dianggap pilihan sadar admin: tema bundel Umum (`esensi`, `natra`).
     * Tema aktif selain ini dihormati -- resolver tak akan menggantinya
     * (kecuali dipanggil dengan `$paksa = true`).
     */
    public const TEMA_DEFAULT_RILIS = ['esensi', 'natra'];

    public function __construct(private readonly BursaTema $bursa)
    {
    }

    /**
     * Deskriptor tema mitra untuk wilayah desa `$kodeDesa` (default: desa
     * instalasi ini), atau `null` bila tak ada.
     *
     * @return array<string, mixed>|null `['nama' => ..., 'alias' => ..., 'url' => ...]`
     */
    public function deskriptorBawaan(?string $kodeDesa = null): ?array
    {
        $kodeDesa = $kodeDesa ?: (string) identitas('kode_desa');

        if ($kodeDesa === '') {
            return null;
        }

        return $this->bursa->temaBawaanWilayah($kodeDesa);
    }

    /**
     * Pasang & aktifkan tema mitra wilayah bila relevan.
     *
     * @param string|null $kodeDesa Kode desa; default desa instalasi ini.
     * @param bool        $paksa    Timpa tema aktif walau bukan tema default rilis.
     *
     * @return string|null Slug tema yang diaktifkan, atau `null` bila tak ada
     *                     tema mitra untuk wilayah ini / tema aktif tak diubah.
     */
    public function terapkan(?string $kodeDesa = null, bool $paksa = false): ?string
    {
        $deskriptor = $this->deskriptorBawaan($kodeDesa);

        if ($deskriptor === null) {
            return null;
        }

        $alias = (string) ($deskriptor['alias'] ?? '');
        $nama  = (string) ($deskriptor['nama'] ?? '');

        if ($alias === '' && $nama === '') {
            return null;
        }

        $tema = $this->temaLokal($alias, $nama);

        if ($tema === null) {
            $this->pasang((string) ($deskriptor['url'] ?? ''));
            $tema = $this->temaLokal($alias, $nama);
        }

        if ($tema === null) {
            Log::warning('PenyelesaiTemaBawaan: tema mitra tak tersedia lokal & gagal dipasang.', [
                'alias' => $alias,
                'nama'  => $nama,
            ]);

            return null;
        }

        $aktif = Theme::isActive()->first();

        if ($aktif !== null && (int) $aktif->id === (int) $tema->id) {
            // Sudah aktif -- idempoten, tak perlu re-aktivasi (yang meng-flush
            // cache & view tiap kali command terjadwal jalan).
            return $tema->slug;
        }

        if (! $paksa && $aktif !== null && ! in_array($aktif->slug, self::TEMA_DEFAULT_RILIS, true)) {
            // Admin sudah memilih tema non-default secara sadar -- hormati,
            // jangan ditimpa oleh resolver terjadwal.
            return null;
        }

        (new ActivateTheme())->handle((int) $tema->id);

        return $tema->slug;
    }

    /**
     * Cari baris tema lokal yang cocok dengan deskriptor mitra. Slug bisa
     * `<alias>` (tema sistem hasil sinkronisasi SiapPakai di
     * `storage/app/themes/`) atau `desa-<alias>` (hasil unduh bursa ke
     * `desa/themes/`), atau cocok lewat `nama`.
     */
    private function temaLokal(string $alias, string $nama): ?Theme
    {
        return Theme::query()
            ->when($alias !== '', static function ($q) use ($alias): void {
                $q->orWhere('slug', $alias)->orWhere('slug', 'desa-' . $alias);
            })
            ->when($nama !== '', static function ($q) use ($nama): void {
                $q->orWhere('nama', $nama);
            })
            ->first();
    }

    /**
     * Unduh & ekstrak ZIP tema mitra dari bursa (seam). Gagal-aman: bila tak
     * ada provider unduh (modul Pelanggan absen) atau unduhan gagal, tak
     * melempar -- pemanggil sudah menangani `temaLokal()` yang tetap `null`.
     */
    private function pasang(string $url): void
    {
        if ($url === '') {
            return;
        }

        $path = $this->bursa->unduh($url);

        if ($path === null) {
            return;
        }

        // ThemeZipExtractor memvalidasi + memindah ke desa/themes/<alias> dan
        // memanggil theme_scan() sendiri saat sukses.
        app(ThemeZipExtractor::class)->extract(['full_path' => $path]);
    }
}
