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

namespace App\Services\Mandiri;

/**
 * Registry titik-masuk (landing) Layanan Mandiri, ber-kunci.
 *
 * Sebelumnya core meng-hardcode route/URL milik modul Anjungan pada beberapa
 * titik (root LM, beranda pasca-login kios, redirect logout tamu). Kini core
 * menanyakan resolver ini per-kunci; add-on mendaftarkan URL/route-nya (dan
 * boleh memutuskannya sendiri, mis. berdasarkan flag sesi miliknya). Tanpa
 * add-on → `null` (core pakai default masing-masing).
 *
 * Kunci yang dipakai core: `root` (root LM), `beranda` (pasca-login kios),
 * `logout` (landing setelah logout, mis. tamu kios).
 */
class PenentuanMasukMandiri
{
    /**
     * @var array<string, list<callable(): ?string>>
     */
    private array $providers = [];

    /**
     * @param callable(): ?string $provider
     */
    public function daftarkan(string $key, callable $provider): void
    {
        $this->providers[$key][] = $provider;
    }

    /**
     * URL landing pertama yang tersedia untuk `$key`, atau `null` bila tak ada
     * add-on yang mendaftarkannya (atau semua mengembalikan kosong).
     */
    public function titikMasuk(string $key): ?string
    {
        foreach ($this->providers[$key] ?? [] as $provider) {
            $url = $provider();
            if (! empty($url)) {
                return (string) $url;
            }
        }

        return null;
    }
}
