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

namespace App\Services\Module;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Sumber modul default: klien Layanan.
 *
 * Mengunduh ZIP add-on dari server Layanan lewat HTTP (token per-desa). Ini
 * adalah komponen TERBUKA yang selalu ada di rilis — bukan add-on berbayar:
 * penegakan langganan berada di sisi-server Layanan (memutuskan ZIP mana yang
 * dilayani), bukan di klien ini. Karena itu memublikasikan klien nyaris tak
 * mengurangi proteksi.
 *
 * Kepercayaan-asal ditegakkan di sini: URL unduh wajib HTTPS dan host-nya harus
 * sama dengan host `server_layanan` (mencegah unduhan dari domain sembarang).
 */
class LayananHttpSource implements ModuleSource
{
    /**
     * @param string|null $serverLayanan Basis URL server Layanan; bila null
     *                                   dibaca dari `config_item('server_layanan')`.
     * @param string|null $modulesDir    Direktori modul tujuan unduhan; bila null
     *                                   diresolusi dari `config_item('modules_locations')`.
     */
    public function __construct(
        private readonly ?string $serverLayanan = null,
        private readonly ?string $modulesDir = null,
    ) {}

    public function fetch(string $name, string $locator = ''): string
    {
        $this->validasiUrlPaket($locator);

        $zipPath  = $this->modulesDir() . $name . '.zip';
        $token    = (string) setting('layanan_opendesa_token');
        $response = Http::withToken($token)
            ->withOptions(['sink' => $zipPath, 'timeout' => 120])
            ->get($locator);

        if (! $response->successful()) {
            @unlink($zipPath);
            $status = $response->status();
            log_message('error', "LayananHttpSource: gagal mengunduh {$name} dari {$locator} | HTTP {$status}");

            throw new RuntimeException("Gagal mengunduh paket {$name}. Status server: HTTP {$status}");
        }

        return $zipPath;
    }

    /**
     * Basis URL server Layanan (dengan fallback konfigurasi).
     */
    private function serverLayanan(): string
    {
        return $this->serverLayanan
            ?? (function_exists('config_item') ? (string) config_item('server_layanan') : '');
    }

    /**
     * Direktori modul tujuan (dengan pemisah akhir).
     */
    private function modulesDir(): string
    {
        $path = $this->modulesDir;

        if ($path === null && function_exists('config_item')) {
            $path = array_keys((array) (config_item('modules_locations') ?: []))[0] ?? null;
        }

        return rtrim((string) $path, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * Validasi URL paket: wajib HTTPS dan host harus sama dengan server Layanan.
     *
     * @throws RuntimeException
     */
    private function validasiUrlPaket(string $url): void
    {
        $serverHost = parse_url($this->serverLayanan(), PHP_URL_HOST);

        if (empty($serverHost)) {
            throw new RuntimeException('Konfigurasi server layanan tidak valid.');
        }

        if (parse_url($url, PHP_URL_SCHEME) !== 'https') {
            throw new RuntimeException('URL harus menggunakan HTTPS');
        }

        if (parse_url($url, PHP_URL_HOST) !== $serverHost) {
            throw new RuntimeException("Domain URL harus sama dengan {$serverHost}");
        }
    }
}
