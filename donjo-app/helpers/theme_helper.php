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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\StatusEnum;
use App\Models\MediaSosial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

if (! function_exists('theme')) {
    /**
     * Ambil model tema
     *
     * @return App\Models\Theme
     */
    function theme()
    {
        if (Schema::hasTable('theme')) {
            return new App\Models\Theme();
        }

        return null;
    }
}

if (! function_exists('theme_list')) {
    /**
     * Get list of themes
     *
     * @return App\Models\Theme[]
     */
    function theme_list()
    {
        return theme()->all();
    }
}

if (! function_exists('theme_list_with_path')) {
    function theme_list_with_path()
    {
        return theme()->list_all(true);
    }
}

if (! function_exists('theme_active')) {
    /**
     * Get active theme
     *
     * @return App\Models\Theme
     */
    function theme_active()
    {
        if (theme() === null) {
            return (object) [
                'nama'       => 'esensi',
                'slug'       => 'esensi',
                'versi'      => VERSION,
                'sistem'     => 1,
                'path'       => 'vendor/themes/esensi',
                'full_path'  => 'vendor/themes/esensi',
                'view_path'  => '../../vendor/themes/esensi',
                'keterangan' => 'Tema bawaan sistem',
            ];
        }

        return theme()->aktif();
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
        return theme_active()->view_path;
    }
}

if (! function_exists('theme_asset')) {
    /**
     * Get asset path of active theme
     *
     * @param mixed $uri
     *
     * @return string
     */
    function theme_asset(string $uri)
    {
        $path = theme_active()->view_path . '/assets/' . $uri;

        return base_url($path);
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
                $default = collect(json_decode(file_get_contents(theme_full_path() . '/config.json'), true))->where('key', $key)->first()['value'] ?? null;
            }

            return $tema[$key] ?? $default;
        }

        return $tema;
    }
}

if (! function_exists('theme_view')) {
    /**
     * Render view tema
     *
     * @param array $data
     * @param mixed $return
     *
     * @return object|string
     */
    function theme_view(string $view, $data = [], $return = false)
    {
        return get_instance()->load->view(theme_view_path() . '/' . $view, $data, $return);
    }
}

// pindai semua folder tema
if (! function_exists('theme_scan')) {
    /**
     * Scan all theme folders
     */
    function theme_scan(): void
    {
        $themeSistem = glob('vendor/themes/*', GLOB_ONLYDIR);
        $themeDesa   = glob('desa/themes/*', GLOB_ONLYDIR);

        $themeList = collect($themeSistem)->merge($themeDesa)
            ->filter(static fn ($tema): bool => is_file(FCPATH . $tema . '/template.php'))
            ->map(static function (string $tema) {
                $sistem = preg_match('/vendor/', $tema) ? 1 : 0;

                if (! is_file(FCPATH . $tema . '/composer.json')) {
                    $versi = VERSION;
                    $nama  = basename($tema);
                    $slug  = Str::slug(($sistem ? 'sistem ' : 'desa ') . $nama);
                } else {
                    $composer   = json_decode(file_get_contents(FCPATH . $tema . '/composer.json'), true);
                    $versi      = $composer['version'] ?? VERSION;
                    $nama       = str_replace('-', ' ', explode('/', $composer['name'])[1]);
                    $slug       = Str::slug(($sistem ? '' : 'desa ') . $nama);
                    $keterangan = $composer['description'];
                }

                return [
                    'config_id'  => identitas('id'),
                    'nama'       => ucwords($nama),
                    'slug'       => $slug,
                    'versi'      => $versi,
                    'sistem'     => $sistem,
                    'path'       => $tema,
                    'keterangan' => $keterangan ?: (preg_match('/vendor/', $tema) ? 'Tema bawaan sistem' : 'Tema buatan desa'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            })
            ->toArray();

        DB::table('theme')->upsert($themeList, 'slug');
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
