<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (! function_exists('theme')) {
    /**
     * Ambil model tema
     * 
     * @return \App\Models\Theme
     */
    function theme()
    {
        if (Schema::hasTable('theme')) {
            return (new \App\Models\Theme);
        }

        return null;
    }
}

if (! function_exists('theme_list')) {
    /**
     * Get list of themes
     * 
     * @return \App\Models\Theme[]
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
     * @return \App\Models\Theme
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
     * @return string
     */
    function theme_asset($uri)
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
            return $tema[$key] ?? $default;
        }

        return $tema;
    }
}

if (! function_exists('theme_view')) {
    /**
     * Render view tema
     * 
     * @param string $view
     * @param array $data
     * 
     * @return object|string
     */
    function theme_view($view, $data = [], $return = false)
    {
        return get_instance()->load->view(theme_view_path() . '/' . $view, $data, $return);
    }
}

// pindai semua folder tema
if (! function_exists('theme_scan')) {
    /**
     * Scan all theme folders
     * 
     * @return array
     */
    function theme_scan()
    {
        $themeSistem = glob('vendor/themes/*', GLOB_ONLYDIR);
        $themeDesa   = glob('desa/themes/*', GLOB_ONLYDIR);

        $themeList = collect($themeSistem)->merge($themeDesa)
            ->filter(function ($tema) {
                return is_file(FCPATH . $tema . '/template.php');
            })
            ->map(function ($tema) {
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
                    'nama'       => ucwords($nama),
                    'slug'       => $slug,
                    'versi'      => $versi,
                    'sistem'     => $sistem,
                    'path'       => $tema,
                    'keterangan' => $keterangan ? $keterangan : (preg_match('/vendor/', $tema) ? 'Tema bawaan sistem' : 'Tema buatan desa'),
                ];
            })
            ->toArray();

        DB::table('theme')->upsert($themeList, 'slug');
    }
}
