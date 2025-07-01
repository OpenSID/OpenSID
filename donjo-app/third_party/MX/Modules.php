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

defined('BASEPATH') || exit('No direct script access allowed');

defined('EXT') || define('EXT', '.php');

global $CFG;

// get module locations from config settings or use the default module location and offset
if (! is_array(Modules::$locations = $CFG->item('modules_locations'))) {
    Modules::$locations = [
        APPPATH . 'modules/' => '../modules/',
    ];
}

// PHP5 spl_autoload
spl_autoload_register('Modules::autoload');

class Modules
{
    public static $routes;
    public static $locations;

    /**
     * [Library base class autoload]
     *
     * @method autoload
     *
     * @param [type]   $class [description]
     *
     * @return [type]          [description]
     */
    public static function autoload(string $class): void
    {
        // don't autoload CI_ prefixed classes or those using the config subclass_prefix
        if (strstr($class, 'CI_') || strstr($class, (string) config_item('subclass_prefix'))) {
            return;
        }

        // autoload Modular Extensions MX core classes
        if (strstr($class, 'MX_')) {
            if (is_file($location = __DIR__ . '/' . substr($class, 3) . EXT)) {
                include_once $location;

                return;
            }
            show_error('Failed to load MX core class: ' . $class);
        }

        // autoload core classes
        if (is_file($location = APPPATH . 'core/' . ucfirst($class) . EXT)) {
            include_once $location;

            return;
        }

        // autoload library classes
        if (is_file($location = APPPATH . 'libraries/' . ucfirst($class) . EXT)) {
            include_once $location;

            return;
        }
    }

    /**
     * [Load a module file]
     *
     * @method load_file
     *
     * @param [type]    $file   [description]
     * @param [type]    $path   [description]
     * @param string $type   [description]
     * @param bool   $result [description]
     *
     * @return [type]            [description]
     */
    public static function load_file($file, string $path, $type = 'other', $result = true)
    {
        $file     = str_replace(EXT, '', $file);
        $location = $path . $file . EXT;

        if ($type === 'other') {
            if (class_exists($file, false)) {
                log_message('debug', "File already loaded: {$location}");

                return $result;
            }
            include_once $location;
        } else {
            // load config or language array
            include $location;

            if (! isset(${$type}) || ! is_array(${$type})) {
                show_error("{$location} does not contain a valid {$type} array");
            }

            $result = ${$type};
        }
        log_message('debug', "File loaded: {$location}");

        return $result;
    }

    /**
     * [Find a file,
     *  scans for files located within modules directories,
     *  also scans application directories for models,
     *  plugins and views, Generates fatal error if file not found]
     *
     * @method find
     *
     * @param [type] $file   [description]
     * @param [type] $module [description]
     * @param [type] $base   [description]
     *
     * @return [type]         [description]
     */
    public static function find($file, string $module, string $base): array
    {
        $segments = explode('/', $file);

        $file     = array_pop($segments);
        $file_ext = pathinfo($file, PATHINFO_EXTENSION) ? $file : $file . EXT;

        $path                       = ltrim(implode('/', $segments) . '/', '/');
        $module ? $modules[$module] = $path : $modules = [];

        if (! empty($segments)) {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments) . '/', '/');
        }

        foreach (self::$locations as $location => $offset) {
            foreach ($modules as $module => $subpath) {
                $fullpath = $location . $module . '/' . $base . $subpath;

                if ($base === 'libraries/' || $base === 'models/') {
                    if (is_file($fullpath . ucfirst($file_ext))) {
                        return [$fullpath, ucfirst($file)];
                    }
                } elseif // load non-class files
                (is_file($fullpath . $file_ext)) {
                    return [$fullpath, $file];
                }
            }
        }

        return [false, $file];
    }

    /**
     * [Parse module routes]
     *
     * @method parse_routes
     *
     * @param [type]       $module [description]
     * @param [type]       $uri    [description]
     *
     * @return [type]               [description]
     */
    public static function parse_routes(string $module, $uri)
    {
        // load the route file
        if (! isset(self::$routes[$module])) {
            // Backward function
            // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
            if (version_compare(PHP_VERSION, '7.1', '<')) {
                // php version isn't high enough
                if ([$path] = self::find('routes', $module, 'config/')) {
                    $path && self::$routes[$module] = self::load_file('routes', $path, 'route');
                }
            } elseif ([$path] = self::find('routes', $module, 'config/')) {
                $path && self::$routes[$module] = self::load_file('routes', $path, 'route');
            }
        }

        if (! isset(self::$routes[$module])) {
            return;
        }

        // Add http verb support for each module routing
        $http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

        // parse module routes
        foreach (self::$routes[$module] as $key => $val) {
            // Add http verb support for each module routing
            if (is_array($val)) {
                $val = array_change_key_case($val, CASE_LOWER);

                if (isset($val[$http_verb])) {
                    $val = $val[$http_verb];
                } else {
                    continue;
                }
            }

            $key = str_replace([':any', ':num'], ['.+', '[0-9]+'], $key);

            if (preg_match('#^' . $key . '$#', $uri)) {
                if (strpos($val, '$') !== false && strpos($key, '(') !== false) {
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
                }

                return explode('/', $module . '/' . $val);
            }
        }
    }
}
