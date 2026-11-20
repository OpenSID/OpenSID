<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 - 2022, CodeIgniter Foundation
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
 *
 * @author      EllisLab Dev Team
 * @copyright   Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright   Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @copyright   Copyright (c) 2019 - 2022, CodeIgniter Foundation (https://codeigniter.com/)
 * @license     https://opensource.org/licenses/MIT MIT License
 *
 * @link        https://codeigniter.com
 * @since       Version 1.0.0
 *
 * @filesource
 */

/**
 * System Initialization File
 *
 * Loads the base classes and executes the request.
 *
 * @category    Front-controller
 *
 * @author      EllisLab Dev Team
 *
 * @link        https://codeigniter.com/userguide3/
 */

/**
 * CodeIgniter Version
 *
 * @var string
 */
const CI_VERSION = '3.1.13';

/**
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 * - development
 * - testing
 * - production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
switch ($_ENV['CI_ENV'] ?? $_SERVER['CI_ENV'] ?? 'production') {
    case 'local':
    case 'development':
        define('ENVIRONMENT', 'development');
        break;
    case 'staging':
    case 'testing':
        define('ENVIRONMENT', 'testing');
        break;
    case 'production':
        define('ENVIRONMENT', 'production');
        break;
    default:
        define('ENVIRONMENT', 'production');
        break;
}

/**
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
        }
        break;

    default:
        throw new InvalidArgumentException('The application environment is not set correctly.');
}

/**
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
$system_path = base_path('vendor/codeigniter/framework/system');

/**
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/userguide3/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */
$application_folder = base_path('donjo-app');

/**
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
 */
$view_folder = '';

/**
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 */
// The directory name, relative to the "controllers" directory.  Leave blank
// if your controller is not in a sub-directory within the "controllers" one
// $routing['directory'] = '';

// The controller class file name.  Example:  mycontroller
// $routing['controller'] = '';

// The controller function you wish to be called.
// $routing['function']	= '';

/**
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';

// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/**
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

/*
 * Samakan folder kerja PHP dengan FCPATH — hanya untuk konteks CodeIgniter.
 *
 * CodeIgniter mengasumsikan folder kerja PHP selalu sama dengan FCPATH. Pada CI
 * standar asumsi itu terpenuhi dengan sendirinya karena index.php berada di
 * document root; baris ini semula hanya menyamakannya untuk permintaan CLI.
 *
 * Di proyek ini FCPATH adalah root proyek, sedangkan index.php sudah pindah ke
 * public/ — sehingga pada permintaan web folder kerja menjadi public/ dan asumsi
 * tersebut tidak lagi terpenuhi. Akibatnya konstanta path relatif warisan CI
 * (LOKASI_CONFIG_DESA, LOKASI_UPLOAD, LOKASI_FOTO_ARTIKEL, dsb. — 63 pemakaian
 * di 30 berkas) meleset, termasuk pemuatan desa/config/database.php yang membuat
 * aplikasi gagal terhubung ke database.
 *
 * Diletakkan di sini, bukan di public/index.php, agar cakupannya terbatas pada
 * proses boot CodeIgniter. Request yang ditangani sepenuhnya oleh Laravel tidak
 * pernah memuat berkas ini, sehingga folder kerjanya tidak tersentuh.
 *
 * Ini lapisan kompatibilitas, bukan solusi akhir. Perbaikan yang benar adalah
 * menambatkan ke-63 path tersebut ke FCPATH satu per satu, yang dapat dikerjakan
 * bertahap tanpa mengubah struktur folder lagi.
 */
chdir(base_path().DIRECTORY_SEPARATOR);

if (($_temp = realpath($system_path)) !== false) {
    $system_path = $_temp.DIRECTORY_SEPARATOR;
} else {
    // Ensure there's a trailing slash
    $system_path = strtr(
        rtrim($system_path, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
    ).DIRECTORY_SEPARATOR;
}

// Is the system path correct?
if (! is_dir($system_path)) {
    throw new RuntimeException('Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME));
}

/**
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the system directory
define('BASEPATH', $system_path);

// Path to the front controller (this file) directory
define('FCPATH', base_path().DIRECTORY_SEPARATOR);

// Path to the public directory
define('PUBLICPATH', public_path().DIRECTORY_SEPARATOR);

// Name of the "system" directory
define('SYSDIR', basename(BASEPATH));

// The path to the "application" directory
if (is_dir($application_folder)) {
    if (($_temp = realpath($application_folder)) !== false) {
        $application_folder = $_temp;
    } else {
        $application_folder = strtr(
            rtrim($application_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        );
    }
} elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR)) {
    $application_folder = BASEPATH.strtr(
        trim($application_folder, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
    );
} else {
    throw new RuntimeException('Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF);
}

define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);

// The path to the "views" directory
if (! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR)) {
    $view_folder = APPPATH.'views';
} elseif (is_dir($view_folder)) {
    if (($_temp = realpath($view_folder)) !== false) {
        $view_folder = $_temp;
    } else {
        $view_folder = strtr(
            rtrim($view_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        );
    }
} elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR)) {
    $view_folder = APPPATH.strtr(
        trim($view_folder, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
    );
} else {
    throw new RuntimeException('Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF);
}

define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);
define('FORCE_HTTPS', (bool) ($_SERVER['HTTPS'] ?? false));

/**
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ------------------------------------------------------
 *  Load the framework constants
 * ------------------------------------------------------
 */
if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/constants.php')) {
    require_once APPPATH.'config/'.ENVIRONMENT.'/constants.php';
}

if (file_exists(APPPATH.'config/constants.php')) {
    require_once APPPATH.'config/constants.php';
}

/**
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
require_once BASEPATH.'core/Common.php';

/**
 * ------------------------------------------------------
 * Security procedures
 * ------------------------------------------------------
 */
if (! is_php('5.4')) {
    ini_set('magic_quotes_runtime', 0);

    if ((bool) ini_get('register_globals')) {
        $_protected = [
            '_SERVER',
            '_GET',
            '_POST',
            '_FILES',
            '_REQUEST',
            '_SESSION',
            '_ENV',
            '_COOKIE',
            'GLOBALS',
            'HTTP_RAW_POST_DATA',
            'system_path',
            'application_folder',
            'view_folder',
            '_protected',
            '_registered',
        ];

        $_registered = ini_get('variables_order');
        foreach (['E' => '_ENV', 'G' => '_GET', 'P' => '_POST', 'C' => '_COOKIE', 'S' => '_SERVER'] as $key => $superglobal) {
            if (strpos($_registered, $key) === false) {
                continue;
            }

            foreach (array_keys($$superglobal) as $var) {
                if (isset($GLOBALS[$var]) && ! in_array($var, $_protected, true)) {
                    $GLOBALS[$var] = null;
                }
            }
        }
    }
}

/**
 * ------------------------------------------------------
 *  Define a custom error handler so we can log PHP errors
 * ------------------------------------------------------
 */
set_error_handler('_error_handler');
set_exception_handler('_exception_handler');
register_shutdown_function('_shutdown_handler');

/**
 * ------------------------------------------------------
 *  Set the subclass_prefix
 * ------------------------------------------------------
 *
 * Normally the "subclass_prefix" is set in the config file.
 * The subclass prefix allows CI to know if a core class is
 * being extended via a library in the local application
 * "libraries" folder. Since CI allows config items to be
 * overridden via data set in the main index.php file,
 * before proceeding we need to know if a subclass_prefix
 * override exists. If so, we will set this value now,
 * before any classes are loaded
 * Note: Since the config file data is cached it doesn't
 * hurt to load it here.
 */
if (! empty($assign_to_config['subclass_prefix'])) {
    get_config(['subclass_prefix' => $assign_to_config['subclass_prefix']]);
}

/**
 * ------------------------------------------------------
 *  Should we use a Composer autoloader?
 * ------------------------------------------------------
 */
if ($composer_autoload = config_item('composer_autoload')) {
    if ($composer_autoload === true) {
        file_exists(FCPATH.'vendor/autoload.php')
            ? require_once (FCPATH.'vendor/autoload.php')
            : log_message('error', '$config[\'composer_autoload\'] is set to true but '.FCPATH.'vendor/autoload.php was not found.');
    } elseif (file_exists($composer_autoload)) {
        require_once $composer_autoload;
    } else {
        log_message('error', 'Could not find the specified $config[\'composer_autoload\'] path: '.$composer_autoload);
    }
}

/**
 * ------------------------------------------------------
 *  Start the timer... tick tock tick tock...
 * ------------------------------------------------------
 */
$BM = $GLOBALS['BM'] = &load_class('Benchmark', 'core');
$BM->mark('total_execution_time_start');
$BM->mark('loading_time:_base_classes_start');

/**
 * ------------------------------------------------------
 *  Instantiate the hooks class
 * ------------------------------------------------------
 */
$EXT = $GLOBALS['EXT'] = &load_class('Hooks', 'core');

/**
 * ------------------------------------------------------
 *  Instantiate the config class
 * ------------------------------------------------------
 *
 * Note: It is important that Config is loaded first as
 * most other classes depend on it either directly or by
 * depending on another class that uses it.
 */
$CFG = $GLOBALS['CFG'] = &load_class('Config', 'core');

// Do we have any manually set config items in the index.php file?
if (isset($assign_to_config) && is_array($assign_to_config)) {
    foreach ($assign_to_config as $key => $value) {
        $CFG->set_item($key, $value);
    }
}

/**
 * ------------------------------------------------------
 * Important charset-related stuff
 * ------------------------------------------------------
 *
 * Configure mbstring and/or iconv if they are enabled
 * and set MB_ENABLED and ICONV_ENABLED constants, so
 * that we don't repeatedly do extension_loaded() or
 * function_exists() calls.
 *
 * Note: UTF-8 class depends on this. It used to be done
 * in it's constructor, but it's _not_ class-specific.
 */
$charset = strtoupper(config_item('charset'));
ini_set('default_charset', $charset);

if (extension_loaded('mbstring')) {
    define('MB_ENABLED', true);
    // mbstring.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('mbstring.internal_encoding', $charset);
    // This is required for mb_convert_encoding() to strip invalid characters.
    // That's utilized by CI_Utf8, but it's also done for consistency with iconv.
    mb_substitute_character('none');
} else {
    define('MB_ENABLED', false);
}

// There's an ICONV_IMPL constant, but the PHP manual says that using
// iconv's predefined constants is "strongly discouraged".
if (extension_loaded('iconv')) {
    define('ICONV_ENABLED', true);
    // iconv.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('iconv.internal_encoding', $charset);
} else {
    define('ICONV_ENABLED', false);
}

if (is_php('5.6')) {
    ini_set('php.internal_encoding', $charset);
}

/**
 * ------------------------------------------------------
 *  Load compatibility features
 * ------------------------------------------------------
 */

require_once BASEPATH.'core/compat/mbstring.php';
require_once BASEPATH.'core/compat/hash.php';
require_once BASEPATH.'core/compat/password.php';
require_once BASEPATH.'core/compat/standard.php';

/**
 * ------------------------------------------------------
 *  Instantiate the UTF-8 class
 * ------------------------------------------------------
 */
$UNI = $GLOBALS['UNI'] = &load_class('Utf8', 'core');

/**
 * ------------------------------------------------------
 *  Instantiate the URI class
 * ------------------------------------------------------
 */
$URI = $GLOBALS['URI'] = &load_class('URI', 'core');

/**
 * ------------------------------------------------------
 *  Instantiate the routing class and set the routing
 * ------------------------------------------------------
 */
$RTR = $GLOBALS['RTR'] = &load_class('Router', 'core', isset($routing) ? $routing : null);

/**
 * ------------------------------------------------------
 *  Instantiate the output class
 * ------------------------------------------------------
 */
$OUT = $GLOBALS['OUT'] = &load_class('Output', 'core');

/**
 * ------------------------------------------------------
 *	Is there a valid cache file? If so, we're done...
 * ------------------------------------------------------
 */
if ($EXT->call_hook('cache_override') === false && $OUT->_display_cache($CFG, $URI) === true) {
    exit;
}

/**
 * -----------------------------------------------------
 * Load the security class for xss and csrf support
 * -----------------------------------------------------
 */
$SEC = $GLOBALS['SEC'] = &load_class('Security', 'core');

/**
 * ------------------------------------------------------
 *  Load the Input class and sanitize globals
 * ------------------------------------------------------
 */
$IN = $GLOBALS['IN'] = &load_class('Input', 'core');

/**
 * ------------------------------------------------------
 *  Load the Language class
 * ------------------------------------------------------
 */
$LANG = $GLOBALS['LANG'] = &load_class('Lang', 'core');

/**
 * ------------------------------------------------------
 *  Load the app controller and local controller
 * ------------------------------------------------------
 */
// Load the base controller class
require_once APPPATH.'core/Controller.php';

/**
 * Reference to the CI_Controller method.
 *
 * Returns current CI instance object
 *
 * @return \CI_Controller
 */
function &get_instance()
{
    return CI_Controller::get_instance();
}

/**
 * Returns the framework singleton
 *
 * (Alias of &get_instance() CodeIgniter function)
 *
 * @return \CI_Controller
 */
function &ci()
{
    return get_instance();
}

if (file_exists(APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php')) {
    require_once APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php';
}

// Set a mark point for benchmarking
$BM->mark('loading_time:_base_classes_end');
