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

use App\Actions\Theme\ActivateTheme;
use App\Models\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ZipArchive;

/**
 * Test-support: pasang + aktifkan satu tema berbayar dari Layanan tanpa lewat
 * UI admin. Padanan tema untuk `modul:pasang-uji` (lihat
 * `PasangModulUji.php`) — entrypoint deterministik untuk harness Playwright
 * `smoke-2701/addon-theme-install-flow` (OpenSID/OpenSID#11931 Fase 3.5,
 * "Langkah — Jalur Tema").
 *
 * Beda dengan modul: tak ada kelas "installer" tunggal di core untuk tema
 * (`BursaTema`/`SumberTemaBursa` hanyalah seam yang menampung closure dari
 * add-on Pelanggan). Jadi command ini melakukan sendiri: resolusi katalog →
 * unduh ZIP terautentikasi (HTTPS + host-pinning) → ekstrak ke `desa/themes/`
 * → `theme_scan()` (menyisipkan baris `Theme`) → `ActivateTheme::handle()`.
 * Alur ini persis yang dijalankan `Theme.php::unduh()` + `aktifkan()` di core,
 * dirangkai jadi satu langkah non-interaktif.
 *
 * Mendukung DUA mode (sama seperti `PasangModulUji`):
 * - Uji hermetik (CI): `--url-penyedia` menunjuk mock server HTTPS lokal yang
 *   menyajikan `GET /api/v1/themes` + ZIP fixture.
 * - Verifikasi manual lawan Layanan sungguhan: `--url-penyedia` dikosongkan
 *   (default `config('bursa.url_penyedia')`), `--token` diisi token nyata.
 */
class PasangTemaUji extends Command
{
    protected $signature = 'tema:pasang-uji {tema : Nama tema persis seperti dikembalikan Layanan (field name/alias)} {--url-penyedia= : Override bursa.url_penyedia utk permintaan ini (fixture lokal)} {--token= : Token Layanan; default ke setting layanan_opendesa_token}';

    protected $description = 'Pasang + aktifkan satu tema dari Layanan (test-support, non-interaktif)';

    public function handle(): int
    {
        $nama        = (string) $this->argument('tema');
        $overrideUrl = $this->option('url-penyedia');
        $urlPenyedia = rtrim((string) ($overrideUrl ?: config('bursa.url_penyedia')), '/');
        $token       = (string) ($this->option('token') ?: setting('layanan_opendesa_token'));

        $penyediaHost = parse_url($urlPenyedia, PHP_URL_HOST);

        $request = Http::acceptJson();
        if ($token !== '') {
            $request = $request->withToken($token);
        }

        // 1. Resolusi katalog tema.
        try {
            $katalog = $request->get($urlPenyedia . '/api/v1/themes', ['per_page' => 100])->throw()->json();
        } catch (\Throwable $e) {
            $this->error("Gagal mengambil daftar tema dari {$urlPenyedia}: {$e->getMessage()}");

            return self::FAILURE;
        }

        $tema = collect($katalog['data'] ?? [])->first(
            static fn ($t) => in_array($nama, [$t['name'] ?? null, $t['alias'] ?? null], true)
        );

        if ($tema === null || empty($tema['url'])) {
            $this->error("Tema \"{$nama}\" tidak ditemukan di katalog (atau desa ini tak berhak / tanpa URL unduh).");

            return self::FAILURE;
        }

        // 2. Host-pinning: hanya terima unduhan HTTPS dari host penyedia yang sama
        //    (identik dgn validasi `Theme.php::unduh()` di core).
        $url = (string) $tema['url'];
        if (parse_url($url, PHP_URL_SCHEME) !== 'https' || parse_url($url, PHP_URL_HOST) !== $penyediaHost) {
            $this->error("URL unduh tema ditolak host-pinning: {$url} (harus HTTPS ke {$penyediaHost}).");

            return self::FAILURE;
        }

        // 3. Unduh ZIP terautentikasi ke berkas sementara.
        $zipPath = rtrim(sys_get_temp_dir(), '/') . '/' . uniqid('tema-uji-', true) . '.zip';
        try {
            $request->withOptions(['sink' => $zipPath])->get($url)->throw();
        } catch (\Throwable $e) {
            @unlink($zipPath);
            $this->error("Gagal mengunduh tema \"{$nama}\": {$e->getMessage()}");

            return self::FAILURE;
        }

        // 4. Ekstrak + validasi (padanan Theme::extractAndValidateTheme() core).
        $folder = $this->ekstrak($zipPath);
        @unlink($zipPath);
        if ($folder === null) {
            $this->error("ZIP tema \"{$nama}\" tidak valid (path ilegal / struktur tema salah).");

            return self::FAILURE;
        }

        // 5. Pindai → sisipkan baris Theme.
        $this->pindaiTema();

        $baris = Theme::where('path', 'desa/themes/' . $folder)->first();
        if ($baris === null) {
            $this->error("Tema \"{$nama}\" terekstrak tetapi tidak muncul setelah theme_scan() (folder: {$folder}).");

            return self::FAILURE;
        }

        // 6. Aktifkan lewat Action yang di-port (jalur yang sama dengan core).
        try {
            (new ActivateTheme())->handle($baris->slug);
        } catch (\Throwable $e) {
            $this->error("Gagal mengaktifkan tema \"{$nama}\": {$e->getMessage()}");

            return self::FAILURE;
        }

        $this->info("Tema \"{$nama}\" (slug: {$baris->slug}) berhasil dipasang dan diaktifkan.");

        return self::SUCCESS;
    }

    /**
     * Ekstrak ZIP tema ke `desa/themes/`; kembalikan nama folder tema, atau
     * `null` bila tidak valid. `validate_zip_entries()` belum ada di sumber
     * v2601–v2604, jadi validasi path-traversal dilakukan inline di sini.
     */
    private function ekstrak(string $zipPath): ?string
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return null;
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $nama = (string) $zip->getNameIndex($i);
            if (str_contains($nama, '..') || str_starts_with($nama, '/') || str_contains($nama, '\\')) {
                $zip->close();

                return null;
            }
        }

        $tujuan   = FCPATH . 'desa/themes/';
        $subEntry = (string) $zip->getNameIndex(0);
        $folder   = rtrim(explode('/', $subEntry)[0], '/');

        $staging = $tujuan . '_staging_' . uniqid('', true) . '/';
        @mkdir($staging, 0755, true);
        $zip->extractTo($staging);
        $zip->close();

        $temaStaging = $staging . $folder;
        $valid = is_file($temaStaging . '/composer.json')
            && is_file($temaStaging . '/resources/views/template.blade.php');

        if (! $valid) {
            File::deleteDirectory($staging);
            @rmdir($staging);

            return null;
        }

        $temaFinal = $tujuan . $folder;
        if (is_dir($temaFinal)) {
            File::deleteDirectory($temaFinal);
            @rmdir($temaFinal);
        }
        @rename($temaStaging, $temaFinal);
        File::deleteDirectory($staging);
        @rmdir($staging);

        return $folder;
    }

    /**
     * `theme_scan()` hidup di helper CI3 (`donjo-app/helpers/theme_helper.php`
     * kohort I) yang tidak auto-load di konteks artisan — muat eksplisit lewat
     * loader CI3 bila tersedia.
     */
    private function pindaiTema(): void
    {
        if (! function_exists('theme_scan') && function_exists('get_instance') && get_instance() !== null) {
            get_instance()->load->helper('theme');
        }

        if (function_exists('theme_scan')) {
            theme_scan();
        }
    }
}
