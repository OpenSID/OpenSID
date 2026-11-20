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

namespace App\Console\Commands;

use App\Services\Module\LayananHttpSource;
use App\Services\Module\ModuleManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Test-support: pasang satu modul lewat `ModuleManager`/`LayananHttpSource`
 * sungguhan, tanpa lewat UI admin. Dipakai harness Playwright
 * (`smoke-2701/addon-install-flow`, lihat komentar di berkas spec itu untuk
 * kontrak parameter persis) sebagai entrypoint deterministik — lihat
 * OpenSID/OpenSID#11931 Fase 3.5.
 *
 * Mendukung DUA mode:
 * - Uji hermetik (CI): `--url-penyedia` menunjuk server HTTP lokal fixture
 *   (di luar `config('bursa.url_penyedia')` default) — dipakai spec Playwright
 *   supaya tak bergantung jaringan/Layanan sungguhan. Resolusi modul lewat
 *   `GET {url}/api/v1/token/bootstrap` (kontrak sama dengan
 *   `ModuleManager::jalankanBootstrap()`), TANPA token.
 * - Verifikasi manual lawan Layanan sungguhan: `--url-penyedia` dikosongkan
 *   (default ke config), `--token` diisi token nyata — resolusi lewat katalog
 *   penuh `GET /api/v1/modules` (mendukung modul berbayar/entitlement).
 *
 * TIDAK dimaksudkan sebagai pengganti UI "Paket Tambahan" -- hanya jalur uji.
 */
class PasangModulUji extends Command
{
    protected $signature = 'modul:pasang-uji {modul : Nama modul persis seperti dikembalikan Layanan} {--url-penyedia= : Override bursa.url_penyedia utk permintaan ini (fixture lokal)} {--token= : Token Layanan; default ke setting layanan_opendesa_token}';

    protected $description = 'Pasang satu modul dari Layanan (test-support, non-interaktif)';

    public function handle(ModuleManager $manager): int
    {
        $nama = (string) $this->argument('modul');

        $overrideUrl = $this->option('url-penyedia');
        $urlPenyedia = rtrim((string) ($overrideUrl ?: config('bursa.url_penyedia')), '/');
        $token       = (string) ($this->option('token') ?: setting('layanan_opendesa_token'));

        $request = Http::acceptJson();
        if ($token !== '') {
            $request = $request->withToken($token);
        }

        try {
            // Coba endpoint bootstrap dulu (kontrak jalankanBootstrap(), tanpa
            // syarat entitlement -- cocok utk fixture lokal uji hermetik).
            $bootstrap = $request->get($urlPenyedia . '/api/v1/token/bootstrap')->throw()->json();
            $modul     = collect($bootstrap['modules'] ?? [])->first(static fn ($m) => ($m['name'] ?? null) === $nama);

            if ($modul === null) {
                // Jatuh ke katalog penuh (Layanan sungguhan, modul berbayar/entitlement).
                $katalog = $request->get($urlPenyedia . '/api/v1/modules', ['per_page' => 100])->throw()->json();
                $modul   = collect($katalog['data'] ?? [])->first(static fn ($m) => ($m['name'] ?? null) === $nama);
            }
        } catch (\Throwable $e) {
            $this->error("Gagal mengambil daftar modul dari {$urlPenyedia}: {$e->getMessage()}");

            return self::FAILURE;
        }

        if ($modul === null) {
            $this->error("Modul \"{$nama}\" tidak ditemukan (atau desa ini tak berhak).");

            return self::FAILURE;
        }

        // Override sumber HANYA bila caller minta URL non-default (fixture lokal) --
        // jalur default tetap lewat binding DI biasa (host-pinning ke config asli).
        $source = $overrideUrl !== null ? new LayananHttpSource($urlPenyedia) : null;

        try {
            $manager->installFromSource($nama, (string) $modul['url'], $source);
        } catch (\Throwable $e) {
            $this->error("Gagal memasang modul \"{$nama}\": {$e->getMessage()}");

            return self::FAILURE;
        }

        $this->info("Modul \"{$nama}\" berhasil dipasang.");

        return self::SUCCESS;
    }
}
