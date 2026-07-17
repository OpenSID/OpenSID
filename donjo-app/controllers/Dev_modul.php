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

use App\Services\Module\LayananHttpSource;
use App\Services\Module\LocalRepoSource;
use App\Services\Module\ModuleManager;
use Illuminate\Support\Facades\Http;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Panel PENGEMBANGAN "Sumber Modul": pasang/perbarui add-on dari repo lokal
 * (simulasi Layanan) atau dari server Layanan nyata — dengan pemilih sumber,
 * strategi, ref, dan tarik-remote per-pasang.
 *
 * DEV-ONLY: konstruktor menolak selain `ENVIRONMENT=development`. Berkas ini,
 * rutenya (`Routes/Web/dev.php`), dan view-nya (`admin/dev_modul/*`) semuanya
 * di-`export-ignore` sehingga TIDAK ikut ke ZIP rilis.
 */
class Dev_modul extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'paket-tambahan';
    private int|string $modulesDirectory;

    public function __construct()
    {
        parent::__construct();

        if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) !== 'development') {
            show_404();
        }

        isCan('b');
        $this->modulesDirectory = array_keys(config_item('modules_locations') ?? [])[0] ?? '';
    }

    public function index(): void
    {
        $data = [
            'content'           => 'admin.dev_modul.index',
            'repo_base'         => (string) config_item('module_dev_repo_base'),
            'modul_repo'        => $this->modulRepoLokal(),
            'default_strategy'  => (string) (config_item('module_dev_repo_strategy') ?: 'working-tree'),
            'default_ref'       => (string) (config_item('module_dev_repo_ref') ?: 'HEAD'),
            'default_fetch'     => filter_var(config_item('module_dev_repo_fetch'), FILTER_VALIDATE_BOOLEAN),
            'server_layanan'    => (string) config_item('server_layanan'),
            'form_action'       => site_url('dev-modul/pasang'),
            'form_hapus'        => site_url('dev-modul/hapus'),
        ];

        view('admin.dev_modul.index', $data);
    }

    public function pasang()
    {
        try {
            $name     = (string) ($this->input->post('name') ?? '');
            $source   = (string) ($this->input->post('source') ?? 'working-tree');
            $ref      = trim((string) ($this->input->post('ref') ?? '')) ?: 'HEAD';
            $fetch    = filter_var($this->input->post('fetch'), FILTER_VALIDATE_BOOLEAN);

            if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
                throw new RuntimeException('Nama modul tidak valid.');
            }

            [$sumber, $locator] = $this->bangunSumber($name, $source, $ref, $fetch);

            $baru = app(ModuleManager::class)->installFromSource($name, $locator, $sumber);

            // Laporan instalasi hanya relevan untuk jalur Layanan nyata.
            if ($baru && $source === 'layanan') {
                app(ModuleManager::class)->reportInstall($name, $locator);
            }

            return redirect_with('success', "Modul {$name} berhasil dipasang dari sumber '{$source}'. Silakan aktifkan.", 'dev-modul');
        } catch (Throwable $e) {
            log_message('error', 'Dev_modul pasang: ' . $e->getMessage());

            return redirect_with('error', 'Gagal memasang modul: ' . $e->getMessage(), 'dev-modul');
        }
    }

    public function hapus()
    {
        $name = (string) ($this->input->post('name') ?? '');

        try {
            if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
                throw new RuntimeException('Nama modul tidak valid.');
            }

            app(ModuleManager::class)->uninstall($name, true);

            return redirect_with('success', "Modul {$name} berhasil dihapus.", 'dev-modul');
        } catch (Throwable $e) {
            log_message('error', 'Dev_modul hapus: ' . $e->getMessage());

            return redirect_with('error', "Gagal menghapus modul {$name}: " . $e->getMessage(), 'dev-modul');
        }
    }

    /**
     * Bangun sumber modul terpilih + locator-nya.
     *
     * @return array{0: \App\Services\Module\ModuleSource, 1: string}
     */
    private function bangunSumber(string $name, string $source, string $ref, bool $fetch): array
    {
        $base = (string) config_item('module_dev_repo_base');

        return match ($source) {
            'layanan' => [new LayananHttpSource(), $this->cariUrlLayanan($name)],
            'git-archive' => [new LocalRepoSource($base, 'git-archive', $ref, $fetch), ''],
            default   => [new LocalRepoSource($base, 'working-tree'), ''],
        };
    }

    /**
     * Modul yang tersedia sebagai repo di bawah `module_dev_repo_base`.
     *
     * @return list<array{name: string, folder: string, is_git: bool, head: string, version: string, installed: bool}>
     */
    private function modulRepoLokal(): array
    {
        $base = rtrim((string) config_item('module_dev_repo_base'), '/\\');
        if ($base === '' || ! is_dir($base)) {
            return [];
        }

        $daftar = [];

        foreach (glob($base . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
            $manifest = $dir . '/module.json';
            if (! is_file($manifest)) {
                continue;
            }

            $meta = json_decode((string) file_get_contents($manifest), true);
            $name = is_array($meta) ? (string) ($meta['name'] ?? basename($dir)) : basename($dir);
            $isGit = is_dir($dir . '/.git');

            $daftar[] = [
                'name'      => $name,
                'folder'    => basename($dir),
                'is_git'    => $isGit,
                'head'      => $isGit ? $this->gitHead($dir) : '',
                'version'   => is_array($meta) ? (string) ($meta['version'] ?? '') : '',
                'installed' => is_dir($this->modulesDirectory . $name),
            ];
        }

        return $daftar;
    }

    /**
     * Short SHA HEAD sebuah repo git (kosong bila gagal).
     */
    private function gitHead(string $dir): string
    {
        $out  = [];
        $code = 0;
        @exec('git -C ' . escapeshellarg($dir) . ' rev-parse --short HEAD 2>/dev/null', $out, $code);

        return $code === 0 ? trim((string) ($out[0] ?? '')) : '';
    }

    /**
     * Cari URL unduh modul `$name` di katalog Layanan (menelusuri halaman).
     *
     * @throws RuntimeException bila Layanan tak terjangkau / modul tak ditemukan.
     */
    private function cariUrlLayanan(string $name): string
    {
        $endpoint = rtrim((string) config_item('server_layanan'), '/') . '/api/v1/modules';
        $token    = (string) setting('layanan_opendesa_token');

        for ($page = 1; $page <= 20; $page++) {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get($endpoint, ['page' => $page]);

            if (! $response->successful()) {
                throw new RuntimeException('Katalog Layanan tak terjangkau (HTTP ' . $response->status() . ').');
            }

            $json = $response->json();
            foreach (($json['data'] ?? []) as $item) {
                if (($item['name'] ?? null) === $name && ! empty($item['url'])) {
                    return (string) $item['url'];
                }
            }

            $meta = $json['meta'] ?? [];
            $total = (int) ($meta['total'] ?? 0);
            $per   = max(1, (int) ($meta['per_page'] ?? 1));
            if ($page >= (int) ceil($total / $per)) {
                break;
            }
        }

        throw new RuntimeException("Modul {$name} tak ditemukan di katalog Layanan.");
    }
}
