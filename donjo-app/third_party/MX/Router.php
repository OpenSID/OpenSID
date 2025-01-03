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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

// load the MX core module class
require_once __DIR__ . '/Modules.php';

class MX_Router extends CI_Router
{
    public $module;
    private int $located = 0;

    /**
     * [fetch_module description]
     *
     * @method fetch_module
     *
     * @return [type]       [description]
     */
    public function fetch_module()
    {
        return $this->module;
    }

    /**
     * [_set_request description]
     *
     * @method _set_request
     *
     * @param array $segments [description]
     */
    protected function _set_request($segments = [])
    {
        if ($this->translate_uri_dashes === true) {
            foreach (range(0, 2) as $v) {
                if (isset($segments[$v])) {
                    $segments[$v] = str_replace('-', '_', $segments[$v]);
                }
            }
        }

        $segments = $this->locate($segments);

        if ($this->located == -1) {
            $this->_set_404override_controller();

            return;
        }

        if (empty($segments)) {
            $this->_set_default_controller();

            return;
        }

        $this->set_class($segments[0]);

        if (isset($segments[1])) {
            $this->set_method($segments[1]);
        } else {
            $segments[1] = 'index';
        }

        array_unshift($segments, null);
        unset($segments[0]);
        $this->uri->rsegments = $segments;
    }

    /**
     * [_set_404override_controller description]
     *
     * @method _set_404override_controller
     */
    protected function _set_404override_controller()
    {
        $this->_set_module_path($this->routes['404_override']);
    }

    /**
     * [_set_default_controller description]
     *
     * @method _set_default_controller
     */
    protected function _set_default_controller()
    {
        if (empty($this->directory)) {
            // set the default controller module path
            $this->_set_module_path($this->default_controller);
        }

        parent::_set_default_controller();

        if (empty($this->class)) {
            $this->_set_404override_controller();
        }
    }

    /**
     * [Locate the controller]
     *
     * @method locate
     *
     * @param [type] $segments [description]
     *
     * @return [type]           [description]
     */
    public function locate($segments)
    {
        // Clear var $this->directory before search controller in function locate() of the Router class.
        // Solve the problem of trying to load a "root" controller using Modules::run('controller/method') after loading a module controller
        // with Modules::run('module/controller/method')
        $this->directory = null;
        $this->located   = 0;
        $ext             = $this->config->item('controller_suffix') . EXT;

        // use module route if available
        if (isset($segments[0]) && $routes = Modules::parse_routes($segments[0], implode('/', $segments))) {
            $segments = $routes;
        }

        // Backward function
        // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
        [$module, $directory, $controller] = array_pad($segments, 3, null);

        // check modules
        foreach (Modules::$locations as $location => $offset) {
            // module exists?
            if (is_dir($source = $location . $module . '/Http/Controllers/')) {
                $this->module    = $module;
                $this->directory = $offset . $module . '/Http/Controllers/';

                // module sub-controller exists?
                if ($directory) {
                    // module sub-directory exists?
                    if (is_dir($source . $directory . '/')) {
                        $source          .= $directory . '/';
                        $this->directory .= $directory . '/';

                        // module sub-directory controller exists?
                        if ($controller) {
                            if (is_file($source . ucfirst($controller) . $ext)) {
                                $this->located = 3;

                                return array_slice($segments, 2);
                            }
                            $this->located = -1;
                        }
                    } elseif (is_file($source . ucfirst($directory) . $ext)) {
                        $this->located = 2;

                        return array_slice($segments, 1);
                    } else {
                        $this->located = -1;
                    }
                }

                // module controller exists?
                if (is_file($source . ucfirst($module) . $ext)) {
                    $this->located = 1;

                    return $segments;
                }
            }
        }

        if ($this->directory !== null && $this->directory !== '' && $this->directory !== '0') {
            return;
        }

        // application sub-directory controller exists?
        if ($directory) {
            if (is_file(APPPATH . 'controllers/' . $module . '/' . ucfirst($directory) . $ext)) {
                $this->directory = $module . '/';

                return array_slice($segments, 1);
            }

            // application sub-sub-directory controller exists?
            if ($controller && is_file(APPPATH . 'controllers/' . $module . '/' . $directory . '/' . ucfirst($controller) . $ext)) {
                $this->directory = $module . '/' . $directory . '/';

                return array_slice($segments, 2);
            }
        }

        // application controllers sub-directory exists?
        if (is_dir(APPPATH . 'controllers/' . $module . '/')) {
            $this->directory = $module . '/';

            return array_slice($segments, 1);
        }

        // application controller exists?
        if (is_file(APPPATH . 'controllers/' . ucfirst($module) . $ext)) {
            return $segments;
        }
        $this->located = -1;
    }

    /**
     * [set module path]
     *
     * @method _set_module_path
     *
     * @param [type]  &$_route [description]
     */
    protected function _set_module_path(&$_route)
    {
        if (! empty($_route)) {
            // Are module/directory/controller/method segments being specified?
            $sgs = sscanf($_route, '%[^/]/%[^/]/%[^/]/%s', $module, $directory, $class, $method);

            // set the module/controller directory location if found
            if ($this->locate([$module, $directory, $class])) {
                //reset to class/method
                switch ($sgs) {
                    case 1: $_route = $module . '/index';
                        break;

                    case 2: $_route = ($this->located < 2) ? $module . '/' . $directory : $directory . '/index';
                        break;

                    case 3: $_route = ($this->located == 2) ? $directory . '/' . $class : $class . '/index';
                        break;

                    case 4: $_route = ($this->located == 3) ? $class . '/' . $method : $method . '/index';
                        break;
                }
            }
        }
    }

    /**
     * [set_class description]
     *
     * @method set_class
     *
     * @param [type]    $class [description]
     */
    public function set_class($class): void
    {
        $suffix = $this->config->item('controller_suffix');
        // Fixing Error Message: strpos(): Non-string needles will be interpreted as strings in the future.
        // Use an explicit chr() call to preserve the current behavior.
        if ($suffix && strpos($class, (string) $suffix) === false) {
            $class .= $suffix;
        }
        parent::set_class($class);
    }
}
