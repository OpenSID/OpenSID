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

use App\Actions\Theme\ActivateTheme;
use App\Models\Theme as ThemeModel;
use App\Traits\Upload;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Pelanggan\Services\PelangganService;
use Spatie\Image\Image;

defined('BASEPATH') || exit('No direct script access allowed');

class Theme extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'theme';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        theme_active();

        $kategori    = request()->get('kategori');
        $currentPage = request()->get('page', 1);
        $perPage     = 9;

        $themeOrder = collect([])
            ->flatMap(static fn ($item) => collect($item?->layanan ?? [])
                ->map(static fn ($layanan) => (array) $layanan))
            ->filter(static fn ($layanan) => ($layanan['nama_kategori'] ?? null) === 'Tema');

        // Fetch all themes from database without pagination
        $themeModel = ThemeModel::query()
            ->when($kategori == 'umum', static fn ($query) => $query->where('sistem', 1))
            ->when($kategori == 'premium', static fn ($query) => $query->where('sistem', 0))
            ->orderBy('sistem', 'desc')
            ->orderBy('versi', 'desc')
            ->get();

        $allThemes = $themeModel->toArray();

        $themeApi = [];

        try {
            $page = 1;
            do {
                $response = Http::withToken(setting('layanan_opendesa_token'))
                    ->acceptJson()
                    ->get(config_item('server_layanan') . '/api/v1/themes', [
                        'kategori' => match ($kategori) {
                            'umum'    => 1,
                            'premium' => 2,
                            default   => null,
                        },
                        'page'     => $page,
                        'per_page' => 100,
                    ])
                    ->throw()
                    ->json();

                $data = $response['data'] ?? [];
                $themeApi = array_merge($themeApi, $data);

                $lastPage = $response['meta']['last_page'] ?? 1;
                $page++;
            } while ($page <= $lastPage);

            $themeApiMapped = collect($themeApi)->map(static fn ($theme) => [
                'id'           => null,
                'config_id'    => null,
                'nama'         => $theme['name'],
                'slug'         => 'desa-' . Str::slug($theme['alias']),
                'versi'        => $theme['version'],
                'sistem'       => 0,
                'path'         => null,
                'status'       => false,
                'keterangan'   => $theme['description'],
                'opsi'         => null,
                'created_at'   => $theme['created_at'],
                'updated_at'   => $theme['updated_at'],
                'full_path'    => null,
                'view_path'    => null,
                'asset_path'   => null,
                'thumbnail'    => $theme['thumbnail'] ?? null,
                'price'        => $theme['price'] ?? null,
                'url'          => $theme['url'] ?? null,
                'totalInstall' => $theme['totalInstall'] ?? 0,
                'marketplace'  => true,
                'providers'    => $theme['providers'] ?? null,
            ])->toArray();

            $allThemes = collect($allThemes)->merge($themeApiMapped)->toArray();
        } catch (Throwable $e) {
            logger()->error($e);
        }

        // Group themes by normalized slug and get the latest version of each theme
        $groupedThemes = collect($allThemes)
            ->groupBy(static function ($theme) {
                // Hapus prefix 'desa-' dari slug untuk pengelompokan yang konsisten
                return preg_replace('/^(desa-)+/', '', $theme['slug']);
            })
            ->map(static function ($group) {
                // Cari apakah ada data tema lokal (dari database) dan remote (dari API)
                $dbTheme = $group->first(static fn ($t) => !($t['marketplace'] ?? false));
                $apiTheme = $group->first(static fn ($t) => ($t['marketplace'] ?? false));

                if ($dbTheme && $apiTheme) {
                    $vDb = ltrim($dbTheme['versi'], 'vV');
                    $vApi = ltrim($apiTheme['versi'], 'vV');

                    // Jika versi API lebih baru, pakai versi API tapi pertahankan status instalasi lokal (id, path, status, dll)
                    if (version_compare($vApi, $vDb, '>')) {
                        return array_merge($dbTheme, [
                            'versi'      => $apiTheme['versi'],
                            'thumbnail'  => $apiTheme['thumbnail'] ?? $dbTheme['thumbnail'],
                            'keterangan' => $apiTheme['keterangan'] ?? $dbTheme['keterangan'],
                        ]);
                    }

                    return $dbTheme;
                }

                // Jika hanya ada salah satu (DB saja atau API saja), pilih versi terbaru di dalam grup tersebut
                return $group->reduce(
                    static function ($latest, $current) {
                        $vCurrent = ltrim($current['versi'], 'vV');
                        $vLatest = ltrim($latest['versi'], 'vV');
                        return version_compare($vCurrent, $vLatest, '>') ? $current : $latest;
                    },
                    $group->first()
                );
            })
            ->values()
            ->sortBy([
                ['status', 'desc'], // Aktif tema di atas
                ['sistem', 'desc'], // Tema sistem di atas tema premium
                ['nama', 'asc'], // Pengurutan berdasarkan nama untuk konsistensi paginasi
            ])
            ->toArray();

        // Apply pagination on the merged and deduplicated list
        $themeList = new LengthAwarePaginator(
            array_slice($groupedThemes, ($currentPage - 1) * $perPage, $perPage),
            count($groupedThemes),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.theme.index', compact('kategori', 'themeOrder', 'themeList'));
    }

    public function unduh()
    {
        isCan('u');

        $serverLayanan = config_item('server_layanan');
        $serverHost    = parse_url($serverLayanan, PHP_URL_HOST);

        $data = $this->validated(request(), [
            'url' => [
                'required',
                'url',
                static function ($attribute, $value, $fail) use ($serverHost) {
                    $urlScheme = parse_url($value, PHP_URL_SCHEME);
                    $urlHost   = parse_url($value, PHP_URL_HOST);

                    if ($urlScheme !== 'https') {
                        $fail('URL harus menggunakan HTTPS');
                    }

                    if ($urlHost !== $serverHost) {
                        $fail("Domain URL harus sama dengan {$serverHost}");
                    }
                },
            ],
            'nama' => [
                'required',
                'string',
                static function ($attribute, $value, $fail) use ($serverLayanan) {
                    $response = Http::withToken(setting('layanan_opendesa_token'))
                        ->acceptJson()
                        ->post("{$serverLayanan}/api/v1/themes", [$attribute => $value]);

                    if ($response->failed()) {
                        $errorMessage = $response->json('message', 'Data pemesanan tidak terdaftar / salah');
                        $fail($errorMessage);
                    }
                },
            ],
        ]);

        try {
            Http::withToken(setting('layanan_opendesa_token'))
                ->acceptJson()
                ->withOptions(['sink' => $path = sys_get_temp_dir() . '/' . mt_rand(1000, 9999) . '-tema.zip'])
                ->throw()
                ->get($data['url']);

            $tema = $this->extractAndValidateTheme(['full_path' => $path]);

            redirect_with($tema['status'] ? 'success' : 'error', $tema['data']);
        } catch (Throwable $e) {
            if ($e instanceof Responsable) {
                throw $e;
            }

            logger()->error($e);

            redirect_with('error', 'Gagal mengunduh tema');
        }
    }


    public function pengaturan($id = '')
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        $form_action = site_url("theme/ubah-pengaturan/{$id}");

        return view('admin.theme.pengaturan', ['form_action' => $form_action, 'tema' => $tema]);
    }

    public function ubah_pengaturan($id = ''): void
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        $opsi = $this->validateOpsi($this->input->post('opsi'), $tema);

        $tema->update(['opsi' => $opsi]);

        redirect_with('success', 'Berhasil Ubah Data', "theme/pengaturan/{$id}");
    }

    public function salin_config($id = ''): void
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        if ($tema->sistem) {
            redirect_with('error', 'Tidak dapat menambahkan config pada tema sistem');
        }

        $sumber = FCPATH . 'storage/app/template/ekspor/config_tema.json';
        $tujuan = FCPATH . $tema->path . '/config.json';

        if (copy($sumber, $tujuan)) {
            redirect_with('success', 'Berhasil Salin Config', "theme/pengaturan/{$id}");
        }

        redirect_with('error', 'Gagal Salin Config', "theme/pengaturan/{$id}");
    }

    public function aktifkan($id = null): void
    {
        isCan('u');

        (new ActivateTheme())->handle($id);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function delete($id = ''): void
    {
        // isMultiDB();
        // isSiapPakai();
        isCan('h');

        $delete = ThemeModel::findOrFail($id);

        if ($delete->status) {
            redirect_with('error', 'Tema yang aktif tidak dapat dihapus');
        }

        if ($delete->sistem) {
            redirect_with('error', 'Tema sistem tidak dapat dihapus');
        }

        if ($delete->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function pindai(): void
    {
        isCan('u');

        theme_scan();

        redirect_with('success', 'Berhasil Memindai Tema');
    }


    protected function extractAndValidateTheme($upload)
    {
        $zip = new ZipArchive();

        if ($zip->open($upload['full_path']) !== true) {
            unlink($upload['full_path']);

            return [
                'status' => false,
                'data'   => 'Tema tidak valid',
            ];
        }

        // Validasi setiap nama entry untuk mencegah path traversal RCE
        if (($entry = validate_zip_entries($zip)) !== true) {
            $zip->close();
            unlink($upload['full_path']);

            return [
                'status' => false,
                'data'   => 'Tema mengandung path ilegal: ' . $entry,
            ];
        }

        $lokasi_ekstrak = FCPATH . 'desa/themes/';
        $subfolder      = $zip->getNameIndex(0);
        $zip->extractTo($lokasi_ekstrak);
        $zip->close();

        $lokasi_tema = $lokasi_ekstrak . substr($subfolder, 0, -1);

        if (! file_exists($lokasi_tema . '/resources/views/template.blade.php')) {
            delete_files($lokasi_tema, true);

            return [
                'status' => false,
                'data'   => 'Tema tidak valid',
            ];
        }

        theme_scan();

        Artisan::call('view:clear');
        cache()->flush();

        return [
            'status' => true,
            'data'   => 'Berhasil Unggah Tema',
        ];
    }

    protected function validateOpsi($opsi, $tema)
    {
        $opsi = [];

        foreach ($tema->config as $config) {
            $key      = $config['key'];
            $postOpsi = $this->input->post('opsi')[$key] ?? null;

            if ($config['type'] == 'unggah') {
                if (request()->file($key)?->isValid()) {
                    $opsi[$key] = $this->imageUpload($tema, $key);
                } else {
                    $opsi[$key] = $tema->opsi[$key] ?? '';
                }

                $opsi['url_' . $key] = $this->input->post('opsi')['url_' . $key] ?? '';
            } else {
                $opsi[$key] = $postOpsi;
            }
        }

        return $opsi;
    }

    protected function imageUpload($tema, $key)
    {
        $namaTema = $tema->slug;

        return $this->upload(
            file: $key,
            config: [
                'upload_path'   => CONFIG_THEMES . $namaTema,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => max_upload() * 1024,
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) use ($tema, $key, $namaTema) {
                Image::load($uploadData['full_path'])
                    ->format('webp')
                    ->save("{$uploadData['file_path']}{$uploadData['raw_name']}.webp");

                // Hapus original file
                unlink($uploadData['full_path']);

                // Hapus file lama jika ada karena overwrite tidak berfungsi pada kasus ini?
                if (file_exists($old = FCPATH . $tema->opsi[$key])) {
                    unlink($old);
                }

                return CONFIG_THEMES . "{$namaTema}/{$uploadData['raw_name']}.webp";
            }
        );
    }
}
