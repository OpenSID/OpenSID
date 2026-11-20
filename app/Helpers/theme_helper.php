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

use App\Enums\StatusEnum;
use App\Models\MediaSosial;
use App\Models\Theme;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;

if (! function_exists('theme')) {
    /**
     * Ambil model tema
     *
     * @return Theme
     */
    function theme()
    {
        return new Theme();
    }
}

if (! function_exists('theme_list')) {
    /**
     * Get list of themes
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Theme>
     */
    function theme_list()
    {
        return theme()->all();
    }
}

if (! function_exists('theme_active')) {
    /**
     * Get active theme
     *
     * @return Theme
     */
    function theme_active()
    {
        if (theme()->doesntExist()) {
            // Scan ulang tema dan set tema default
            theme_scan();
        }

        $theme = theme()->aktif();

        view()->addNamespace('theme', base_path($theme->view_path));

        return $theme;
    }
}

if (! function_exists('theme_path')) {
    /**
     * Get path of active theme
     *
     * @return string
     */
    function theme_path()
    {
        return theme_active()->path;
    }
}

if (! function_exists('theme_full_path')) {
    /**
     * Get full path of active theme
     *
     * @return string
     */
    function theme_full_path()
    {
        return theme_active()->full_path;
    }
}

if (! function_exists('theme_view_path')) {
    /**
     * Get view path of active theme
     *
     * @return string
     */
    function theme_view_path()
    {
        return theme_active()->view_path . '/resources/views';
    }
}

if (! function_exists('theme_asset')) {
    /**
     * Generate an asset URL for the active theme
     *
     * @param string $uri    The URI path to the asset file within the theme
     * @param array  $config Additional query parameters for the asset URL (optional)
     *
     * @return string The complete URL to the theme asset with version parameter
     */
    function theme_asset(string $uri, $config = [])
    {
        $theme = theme_active();

        // Beberapa tema menyisipkan query string di URI (mis. js/script.min.js?v26.5);
        // pisahkan agar path aset bisa dicek keberadaannya di public/.
        [$path, $query] = array_pad(explode('?', $uri, 2), 2, '');

        $config = array_merge(['v' => VERSION], $config);

        if (! is_file(public_path("themes/{$theme->slug}/{$path}"))) {
            $params = array_merge(['file' => $path], $config);

            return url("theme_asset/{$theme->slug}?" . http_build_query($params)) . ($query !== '' ? "&{$query}" : '');
        }

        // Aset ter-publish disajikan langsung dari public/. Segmen index.php
        // (front controller) dibuang agar berkas tidak melewati PHP.
        $url = Str::remove('/index.php', Uri::to("themes/{$theme->slug}/{$path}")->withQuery($config)->toString());

        return $query !== '' ? "{$url}&{$query}" : $url;
    }
}

if (! function_exists('theme_config')) {
    /**
     * Get config of active theme
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function theme_config($key = null, $default = null)
    {
        $tema = theme_active()->opsi;

        if ($key) {
            if ($default === null) {
                $configPath = theme_full_path() . '/config.json';
                $default    = optional(
                    collect(json_decode(file_get_contents($configPath), true))
                        ->firstWhere('key', $key)
                )['value'];
            }

            return $tema[$key] ?? $default;
        }

        return $tema;
    }
}

if (! function_exists('theme_extract_base_name_and_version')) {
    /**
     * Beberapa paket tema (mis. Pusako) menuliskan nomor versi pada nama paketnya,
     * contoh "pusako_4_1". Akibatnya setiap kali tema tsb dirilis dengan folder/nama
     * paket baru, ia dianggap sebagai tema yang sama sekali berbeda dan menumpuk di
     * daftar tema. Fungsi ini memisahkan nama dasar dari versi yang menempel tsb.
     *
     * @return array{0: string, 1: string|null}
     */
    function theme_extract_base_name_and_version(string $packageName): array
    {
        if (preg_match('/^(.+?)[_-]v?(\d+(?:[._]\d+)*)$/i', $packageName, $match)) {
            return [$match[1], str_replace('_', '.', $match[2])];
        }

        return [$packageName, null];
    }
}

// pindai semua folder tema
if (! function_exists('theme_scan')) {
    /**
     * Scan all theme folders
     */
    function theme_scan(): void
    {
        $themeSistem   = glob(Theme::PATH_SISTEM . '*', GLOB_ONLYDIR);
        $themeDesa     = glob('desa/themes/*', GLOB_ONLYDIR);
        $templateBlade = 'resources/views/template.blade.php';

        $themeList = collect($themeSistem)->merge($themeDesa)
            ->filter(static fn ($tema): bool => is_file(FCPATH . $tema . '/composer.json') && is_file(FCPATH . $tema . '/' . $templateBlade))
            ->map(static function (string $tema): array {
                $isStoragePath = (bool) preg_match('/storage/', $tema);
                $sistem        = $isStoragePath ? 1 : 0;
                $composer      = json_decode(file_get_contents(FCPATH . $tema . '/composer.json'), true);
                $packageName   = explode('/', $composer['name'])[1];
                [$namaDasar, $versiDariNama] = theme_extract_base_name_and_version($packageName);
                // Jika composer.json tidak menuliskan versi sama sekali (dan tidak
                // ada versi yang bisa diambil dari nama paket), anggap tema tsb
                // sebagai versi 1.0.0 alih-alih ikut versi aplikasi.
                // ltrim('vV') agar tidak dobel dengan prefix "v" yang selalu
                // ditambahkan oleh Theme::getVersiAttribute() saat ditampilkan.
                $versi         = ltrim($composer['version'] ?? $versiDariNama ?? '1.0.0', 'vV');
                $nama          = str_replace(['-', '_'], ' ', $namaDasar);
                $slug          = Str::slug(($isStoragePath ? '' : 'desa ') . $nama);
                $keterangan    = $composer['description'];

                return [
                    'config_id'  => identitas('id'),
                    'nama'       => ucwords($nama),
                    'slug'       => $slug,
                    'versi'      => $versi,
                    'sistem'     => $sistem,
                    'path'       => $tema,
                    'keterangan' => $keterangan ?: (preg_match('/storage/', $tema) ? 'Tema bawaan sistem' : 'Tema buatan desa'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            })
            // Beberapa folder dapat menghasilkan slug yang sama (rilis tema yang sama
            // dengan versi berbeda). Pertahankan hanya versi terbaru agar tidak
            // muncul duplikat pada daftar tema.
            ->groupBy('slug')
            ->map(static fn ($group) => $group->sort(
                static fn ($a, $b) => version_compare(ltrim($a['versi'], 'vV'), ltrim($b['versi'], 'vV'))
            )->last())
            ->values()
            ->toArray();

        Theme::whereNotIn('slug', collect($themeList)->pluck('slug'))->delete();
        $theme = new Theme();
        $theme->upsert($themeList, 'slug');
        $theme->flushQueryCache();

        cache()->forget('theme_active');
    }
}

if (! function_exists('media_sosial')) {
    /**
     * Get social media
     *
     * @return array
     */
    function media_sosial()
    {
        return cache()->remember('media_sosial', 60 * 60 * 24, static fn () => MediaSosial::status(StatusEnum::YA)
            ->get()
            ->map(static fn ($media): array => [
                'nama' => $media->nama,
                'link' => empty($media->link) ? '' : $media->new_link,
                'icon' => $media->url_icon,
            ])
            ->toArray());
    }
}

if (! function_exists('sinergi_program')) {
    function sinergi_program()
    {
        return cache()->rememberForever('sinergi_program', static fn () => App\Models\SinergiProgram::status(App\Models\SinergiProgram::ACTIVE)->orderBy('urut')->get()->toArray());
    }
}

if (! function_exists('module_path')) {
    /**
     * Get the full path to a specific module directory
     *
     * @param string $name The name of the module
     * @param string $path Optional path within the module directory
     *
     * @return string The full path to the module or module subdirectory
     */
    function module_path($name, $path = '')
    {
        return app()->basePath() . '/Modules' . DIRECTORY_SEPARATOR . $name . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('module_storage')) {
    /**
     * Get the storage path for a specific module
     *
     * @param string $name The name of the module
     * @param string $path Optional path within the module storage directory
     *
     * @return string The full path to the module storage directory
     */
    function module_storage($name, $path = '')
    {
        return app()->basePath() . '/Modules/' . $name . '/Storage' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('module_asset')) {
    /**
     * Generate an asset URL for a specific module file
     *
     * @param string $name   The name of the module
     * @param string $path   The path to the asset file within the module
     * @param array  $config Additional query parameters for the asset URL
     *
     * @return string The URL to the module asset with version parameter
     */
    function module_asset($name, $path, $config = [])
    {
        $name = strtolower($name);

        // Beberapa modul menyisipkan query string di path (mis. js/script.min.js?v26.5);
        // pisahkan agar path aset bisa dicek keberadaannya di public/.
        [$path, $query] = array_pad(explode('?', $path, 2), 2, '');

        $config = array_merge(['v' => VERSION], $config);

        if (! is_file(public_path("modules/{$name}/{$path}"))) {
            $params = array_merge(['file' => $path], $config);

            return url("module_asset/{$name}?" . http_build_query($params)) . ($query !== '' ? "&{$query}" : '');
        }

        // Aset ter-publish disajikan langsung dari public/. Segmen index.php
        // (front controller) dibuang agar berkas tidak melewati PHP.
        $url = Str::remove('/index.php', Uri::to("modules/{$name}/{$path}")->withQuery($config)->toString());

        return $query !== '' ? "{$url}&{$query}" : $url;
    }
}

if (! function_exists('compare_versions')) {
    /**
     * Compare two version strings
     * Returns: 1 if v1 > v2, -1 if v1 < v2, 0 if equal
     *
     * @param string|null $v1 First version
     * @param string|null $v2 Second version
     *
     * @return int
     */
    function compare_versions(?string $v1, ?string $v2)
    {
        return version_compare($v1 ?? '0.0.0', $v2 ?? '0.0.0');
    }
}

if (! function_exists('theme_version')) {
    /**
     * Get the version of a specific theme
     *
     * @param string|null $themeName The name of the theme
     *
     * @return string|null The version of the theme or null if not found
     */
    function theme_version(?string $themeName = ''): ?string
    {
        if (empty($themeName)) {
            return theme()->aktif()?->versi;
        }

        $versi = Theme::whereIn('slug', ['desa-' . $themeName, $themeName])->pluck('versi', 'slug');

        return $versi['desa-' . $themeName] ?? $versi[$themeName] ?? null;
    }
}
