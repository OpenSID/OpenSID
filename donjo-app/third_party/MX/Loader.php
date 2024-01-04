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

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 *
 * @see http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter CI_Loader class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Loader.php
 *
 * @copyright   Copyright (c) 2015 Wiredesignz
 *
 * @version     5.5
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class MX_Loader extends CI_Loader
{
    protected $_module;
    public $_ci_plugins     = [];
    public $_ci_cached_vars = [];

    /**
     * [Initialize the loader variables]
     *
     * @method initialize
     *
     * @param bool $controller [description]
     *
     * @return [type]                 [description]
     */
    public function initialize($controller = null)
    {
        // set the module name
        $this->_module = CI::$APP->router->fetch_module();

        if ($controller instanceof MX_Controller) {
            // reference to the module controller
            $this->controller = $controller;

            // references to ci loader variables
            foreach (get_class_vars('CI_Loader') as $var => $val) {
                if ($var !== '_ci_ob_level') {
                    $this->{$var} = &CI::$APP->load->{$var};
                }
            }
        } else {
            parent::initialize();

            // autoload module items
            $this->_autoloader([]);
        }

        // add this module path to the loader variables
        $this->_add_module_paths($this->_module);
    }

    /**
     * [Add a module path loader variables]
     *
     * @method _add_module_paths
     *
     * @param string $module [description]
     */
    public function _add_module_paths($module = '')
    {
        if (empty($module)) {
            return;
        }

        foreach (Modules::$locations as $location => $offset) {
            // only add a module path if it exists
            if (is_dir($module_path = $location . $module . '/') && ! in_array($module_path, $this->_ci_model_paths)) {
                array_unshift($this->_ci_model_paths, $module_path);
            }
        }
    }

    /**
     * [Load a module config file]
     *
     * @method config
     *
     * @param [type]  $file            [description]
     * @param bool $use_sections    [description]
     * @param bool $fail_gracefully [description]
     *
     * @return [type]                   [description]
     */
    public function config($file, $use_sections = false, $fail_gracefully = false)
    {
        return CI::$APP->config->load($file, $use_sections, $fail_gracefully, $this->_module);
    }

    /**
     * [Load the database drivers]
     *
     * @method database
     *
     * @param string $params [description]
     * @param bool   $return [description]
     * @param [type]   $query_builder [description]
     *
     * @return [type]                  [description]
     */
    public function database($params = '', $return = false, $query_builder = null)
    {
        if ($return === false && $query_builder === null
            && isset(CI::$APP->db) && is_object(CI::$APP->db) && ! empty(CI::$APP->db->conn_id)) {
            return false;
        }

        require_once BASEPATH . 'database/DB' . EXT;

        if ($return === true) {
            return DB($params, $query_builder);
        }

        CI::$APP->db = DB($params, $query_builder);

        return $this;
    }

    /**
     * [Load a module helper]
     *
     * @method helper
     *
     * @param array $helper [description]
     *
     * @return [type]         [description]
     */
    public function helper($helper = [])
    {
        if (is_array($helper)) {
            return $this->helpers($helper);
        }

        if (isset($this->_ci_helpers[$helper])) {
            return;
        }
        // Backward function
        // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
        if (version_compare(PHP_VERSION, '7.1', '<')) {
            // php version isn't high enough
            [$path, $_helper] = Modules::find($helper . '_helper', $this->_module, 'helpers/');
        } else {
            [$path, $_helper] = Modules::find($helper . '_helper', $this->_module, 'helpers/');
        }

        if ($path === false) {
            return parent::helper($helper);
        }

        Modules::load_file($_helper, $path);
        $this->_ci_helpers[$_helper] = true;

        return $this;
    }

    /**
     * [Load an array of helpers]
     *
     * @method helpers
     *
     * @param array $helpers [description]
     *
     * @return [type]           [description]
     */
    public function helpers($helpers = [])
    {
        foreach ($helpers as $_helper) {
            $this->helper($_helper);
        }

        return $this;
    }

    /**
     * [Load a module language file]
     *
     * @method language
     *
     * @param [type]   $langfile   [description]
     * @param string $idiom      [description]
     * @param bool   $return     [description]
     * @param bool   $add_suffix [description]
     * @param string $alt_path   [description]
     *
     * @return [type]               [description]
     */
    public function language($langfile, $idiom = '', $return = false, $add_suffix = true, $alt_path = '')
    {
        CI::$APP->lang->load($langfile, $idiom, $return, $add_suffix, $alt_path, $this->_module);

        return $this;
    }

    /**
     * [languages description]
     *
     * @method languages
     *
     * @param [type]    $languages [description]
     *
     * @return [type]               [description]
     */
    public function languages($languages)
    {
        foreach ($languages as $_language) {
            $this->language($_language);
        }

        return $this;
    }

    /**
     * [Load a module library]
     *
     * @method library
     *
     * @param [type]  $library     [description]
     * @param [type]  $params      [description]
     * @param [type]  $object_name [description]
     *
     * @return [type]               [description]
     */
    public function library($library, $params = null, $object_name = null)
    {
        if (is_array($library)) {
            return $this->libraries($library);
        }

        $class = strtolower(basename($library));

        if (isset($this->_ci_classes[$class]) && $_alias = $this->_ci_classes[$class]) {
            return $this;
        }

        ($_alias = strtolower($object_name)) || $_alias = $class;

        // Backward function
        // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
        if (version_compare(PHP_VERSION, '7.1', '<')) {
            // php version isn't high enough
            [$path, $_library] = Modules::find($library, $this->_module, 'libraries/');
        } else {
            [$path, $_library] = Modules::find($library, $this->_module, 'libraries/');
        }

        // load library config file as params
        if ($params === null) {
            // Backward function
            // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
            if (version_compare(PHP_VERSION, '7.1', '<')) {
                // php version isn't high enough
                [$path2, $file] = Modules::find($_alias, $this->_module, 'config/');
            } else {
                [$path2, $file] = Modules::find($_alias, $this->_module, 'config/');
            }
            $path2 && $params = Modules::load_file($file, $path2, 'config');
        }

        if ($path === false) {
            $this->_ci_load_library($library, $params, $object_name);
        } else {
            Modules::load_file($_library, $path);

            $library            = ucfirst($_library);
            CI::$APP->{$_alias} = new $library($params);

            $this->_ci_classes[$class] = $_alias;
        }

        return $this;
    }

    /**
     * [Load an array of libraries]
     *
     * @method libraries
     *
     * @param [type]    $libraries [description]
     *
     * @return [type]               [description]
     */
    public function libraries($libraries)
    {
        foreach ($libraries as $library => $alias) {
            is_int($library) ? $this->library($alias) : $this->library($library, null, $alias);
        }

        return $this;
    }

    /**
     * [Load a module model]
     *
     * @method model
     *
     * @param [type]  $model       [description]
     * @param [type]  $object_name [description]
     * @param bool $connect [description]
     *
     * @return [type]               [description]
     */
    public function model($model, $object_name = null, $connect = false)
    {
        if (is_array($model)) {
            return $this->models($model);
        }

        ($_alias = $object_name) || $_alias = basename($model);

        if (in_array($_alias, $this->_ci_models, true)) {
            return $this;
        }

        // Backward function
        // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
        if (version_compare(PHP_VERSION, '7.1', '<')) {
            // php version isn't high enough
            // check module
            [$path, $_model] = Modules::find(strtolower($model), $this->_module, 'models/');
        } else {
            [$path, $_model] = Modules::find(strtolower($model), $this->_module, 'models/');
        }

        if ($path === false) {
            // check application & packages
            parent::model($model, $object_name, $connect);
        } else {
            class_exists('CI_Model', false) || load_class('Model', 'core');

            if ($connect !== false && ! class_exists('CI_DB', false)) {
                if ($connect === true) {
                    $connect = '';
                }
                $this->database($connect, false, true);
            }

            Modules::load_file($_model, $path);

            $model              = ucfirst($_model);
            CI::$APP->{$_alias} = new $model();

            $this->_ci_models[] = $_alias;
        }

        return $this;
    }

    /**
     * [Load an array of models]
     *
     * @method models
     *
     * @param [type] $models [description]
     *
     * @return [type]         [description]
     */
    public function models($models)
    {
        foreach ($models as $model => $alias) {
            is_int($model) ? $this->model($alias) : $this->model($model, $alias);
        }

        return $this;
    }

    /**
     * [Load a module controller]
     *
     * @method module
     *
     * @param [type] $module [description]
     * @param [type] $params [description]
     *
     * @return [type]         [description]
     */
    public function module($module, $params = null)
    {
        if (is_array($module)) {
            return $this->modules($module);
        }

        $_alias             = strtolower(basename($module));
        CI::$APP->{$_alias} = Modules::load([$module => $params]);

        return $this;
    }

    /**
     * [Load an array of controllers]
     *
     * @method modules
     *
     * @param [type]  $modules [description]
     *
     * @return [type]           [description]
     */
    public function modules($modules)
    {
        foreach ($modules as $_module) {
            $this->module($_module);
        }

        return $this;
    }

    /**
     * [Load a module plugin]
     *
     * @method plugin
     *
     * @param [type] $plugin [description]
     *
     * @return [type]         [description]
     */
    public function plugin($plugin)
    {
        if (is_array($plugin)) {
            return $this->plugins($plugin);
        }

        if (isset($this->_ci_plugins[$plugin])) {
            return $this;
        }

        // Backward function
        // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
        if (version_compare(PHP_VERSION, '7.1', '<')) {
            // php version isn't high enough
            [$path, $_plugin] = Modules::find($plugin . '_pi', $this->_module, 'plugins/');
        } else {
            [$path, $_plugin] = Modules::find($plugin . '_pi', $this->_module, 'plugins/');
        }

        if ($path === false && ! is_file($_plugin = APPPATH . 'plugins/' . $_plugin . EXT)) {
            show_error("Unable to locate the plugin file: {$_plugin}");
        }

        Modules::load_file($_plugin, $path);
        $this->_ci_plugins[$plugin] = true;

        return $this;
    }

    /**
     * [Load an array of plugins]
     *
     * @method plugins
     *
     * @param [type]  $plugins [description]
     *
     * @return [type]           [description]
     */
    public function plugins($plugins)
    {
        foreach ($plugins as $_plugin) {
            $this->plugin($_plugin);
        }

        return $this;
    }

    /**
     * [Load a module view]
     *
     * @method view
     *
     * @param [type]  $view   [description]
     * @param array $vars   [description]
     * @param bool  $return [description]
     *
     * @return [type]          [description]
     */
    public function view($view, $vars = [], $return = false)
    {
        // Backward function
        // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
        if (version_compare(PHP_VERSION, '7.1', '<')) {
            // php version isn't high enough
            [$path, $_view] = Modules::find($view, $this->_module, 'views/');
        } else {
            [$path, $_view] = Modules::find($view, $this->_module, 'views/');
        }

        if ($path != false) {
            $this->_ci_view_paths = [$path => true] + $this->_ci_view_paths;
            $view                 = $_view;
        }

        if (method_exists($this, '_ci_object_to_array')) {
            return $this->_ci_load(['_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return]);
        }

        return $this->_ci_load(['_ci_view' => $view, '_ci_vars' => $this->_ci_prepare_view_vars($vars), '_ci_return' => $return]);
    }

    /**
     * [_ci_get_component description]
     *
     * @method _ci_get_component
     *
     * @param [type]            $component [description]
     *
     * @return [type]                       [description]
     */
    protected function &_ci_get_component($component)
    {
        return CI::$APP->{$component};
    }

    /**
     * [__get description]
     *
     * @method __get
     *
     * @param [type] $class [description]
     *
     * @return [type]        [description]
     */
    public function __get($class)
    {
        return isset($this->controller) ? $this->controller->{$class} : CI::$APP->{$class};
    }

    /**
     * [_ci_load description]
     *
     * @method _ci_load
     *
     * @param [type]   $_ci_data [description]
     *
     * @return [type]             [description]
     */
    public function _ci_load($_ci_data)
    {
        extract($_ci_data);

        if (isset($_ci_view)) {
            $_ci_path = '';

            // add file extension if not provided
            $_ci_file = pathinfo($_ci_view, PATHINFO_EXTENSION) ? $_ci_view : $_ci_view . EXT;

            foreach ($this->_ci_view_paths as $path => $cascade) {
                if (file_exists($view = $path . $_ci_file)) {
                    $_ci_path = $view;
                    break;
                }
                if (! $cascade) {
                    break;
                }
            }
        } elseif (isset($_ci_path)) {
            $_ci_file = basename($_ci_path);
            if (! file_exists($_ci_path)) {
                $_ci_path = '';
            }
        }

        if (empty($_ci_path)) {
            show_error('Unable to load the requested file: ' . $_ci_file);
        }

        if (isset($_ci_vars)) {
            $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, (array) $_ci_vars);
        }

        extract($this->_ci_cached_vars);

        ob_start();

        if ((bool) @ini_get('short_open_tag') === false && CI::$APP->config->item('rewrite_short_tags') == true) {
            echo eval('?>' . preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        } else {
            include $_ci_path;
        }

        log_message('debug', 'File loaded: ' . $_ci_path);

        if ($_ci_return === true) {
            return ob_get_clean();
        }

        if (ob_get_level() > $this->_ci_ob_level + 1) {
            ob_end_flush();
        } else {
            CI::$APP->output->append_output(ob_get_clean());
        }
    }

    /**
     * [Autoload module items]
     *
     * @method _autoloader
     *
     * @param [type]      $autoload [description]
     *
     * @return [type]                [description]
     */
    public function _autoloader($autoload)
    {
        $path = false;

        if ($this->_module) {
            // Backward function
            // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
            if (version_compare(PHP_VERSION, '7.1', '<')) {
                // php version isn't high enough
                [$path, $file] = Modules::find('constants', $this->_module, 'config/');
            } else {
                [$path, $file] = Modules::find('constants', $this->_module, 'config/');
            }
            // module constants file
            if ($path !== false) {
                include_once $path . $file . EXT;
            }

            // Backward function
            // Before PHP 7.1.0, list() only worked on numerical arrays and assumes the numerical indices start at 0.
            if (version_compare(PHP_VERSION, '7.1', '<')) {
                // php version isn't high enough
                [$path, $file] = Modules::find('autoload', $this->_module, 'config/');
            } else {
                [$path, $file] = Modules::find('autoload', $this->_module, 'config/');
            }

            // module autoload file
            if ($path !== false) {
                $autoload = array_merge(Modules::load_file($file, $path, 'autoload'), $autoload);
            }
        }

        // nothing to do
        if (count($autoload) === 0) {
            return;
        }

        // autoload package paths
        if (isset($autoload['packages'])) {
            foreach ($autoload['packages'] as $package_path) {
                $this->add_package_path($package_path);
            }
        }

        // autoload config
        if (isset($autoload['config'])) {
            foreach ($autoload['config'] as $config) {
                $this->config($config);
            }
        }

        // autoload helpers, plugins, languages
        foreach (['helper', 'plugin', 'language'] as $type) {
            if (isset($autoload[$type])) {
                foreach ($autoload[$type] as $item) {
                    $this->{$type}($item);
                }
            }
        }

        // Autoload drivers
        if (isset($autoload['drivers'])) {
            foreach ($autoload['drivers'] as $item => $alias) {
                is_int($item) ? $this->driver($alias) : $this->driver($item, $alias);
            }
        }

        // autoload database & libraries
        if (isset($autoload['libraries'])) {
            if (! $db = CI::$APP->config->item('database') && in_array('database', $autoload['libraries'])) {
                $this->database();

                $autoload['libraries'] = array_diff($autoload['libraries'], ['database']);
            }

            // autoload libraries
            foreach ($autoload['libraries'] as $library => $alias) {
                is_int($library) ? $this->library($alias) : $this->library($library, null, $alias);
            }
        }

        // autoload models
        if (isset($autoload['model'])) {
            foreach ($autoload['model'] as $model => $alias) {
                is_int($model) ? $this->model($alias) : $this->model($model, $alias);
            }
        }

        // autoload module controllers
        if (isset($autoload['modules'])) {
            foreach ($autoload['modules'] as $controller) {
                ($controller != $this->_module) && $this->module($controller);
            }
        }
    }
}

// load the CI class for Modular Separation
(class_exists('CI', false)) || require_once __DIR__ . '/Ci.php';
