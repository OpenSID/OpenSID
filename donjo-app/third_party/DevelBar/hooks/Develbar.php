<?php
/**
 * Class DevelBar
 *
 * @package    DevelBar
 * @author    Mohamed ES-SAHLI | simoessahli@gmail.com
 * @link    https://github.com/JCSama/CodeIgniter-develbar
 */
defined('BASEPATH') or die('No direct script access.');


class Develbar
{

    /**
     * DevelBar version
     */
    const VERSION = '1.2.2';

    /**
     * Supported CI version
     */
    const SUPPORTED_CI_VERSION = '3.0';

    /**
     * @var object
     */
    private $CI;

    /**
     * @var string
     */
    private $view_folder = 'develbar/';

    /**
     * @var string
     */
    private $assets_folder = '';

    /**
     * @var array
     */
    private $views = array();

    /**
     * List of helpers
     *
     * @var array
     */
    private $helpers = array(
        'utility',
        'language',
        'url',
        'text'
    );

    /**
     * @var array
     */
    private $mimes = array(
        'text/html'
    );

    /**
     * List of profiler sections available
     */
    private $default_options = array(
        'enable_develbar' => false,
        'check_update' => false,
        'develbar_sections' => array(
            'Benchmarks' => true,
            'Memory Usage' => true,
            'Request' => true,
            'Database' => true,
            'Hooks' => true,
            'Models' => true,
            'Libraries' => true,
            'Helpers' => true,
            'Views' => true,
            'Config' => true,
            'Session' => true,
            'Ajax' => true,
        ),
    );

    /**
     * DevelBar constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initialize DevelBar library
     */
    private function initialize()
    {
        $this->CI =& get_instance();
        $this->CI->load->config('develbar', true);
        $this->CI->load->helpers($this->helpers);

        // Initialize default options
        $config = $this->CI->config->config['develbar'];
        $this->default_options = array_merge($this->default_options, $config);
        $this->assets_folder = APPPATH . 'third_party/DevelBar/assets/';

        // Load lang file if exists
        $this->load_lang_file();

        log_message('debug', 'DevelBar Class Initialized !');
    }

    /**
     * Load translation file for the default language,
     * if the file does not exists, set english version as default
     *
     * @return void
     */
    private function load_lang_file()
    {
        $default_language = $this->CI->config->config['language'];
        $lang_file = APPPATH . 'third_party/DevelBar/language/' . $default_language . '/develbar_lang.php';

        if (!file_exists($lang_file)) {
            $default_language = 'english';
        }

        $this->CI->load->language('develbar', $default_language);
    }

    /**
     * Start Debug Mode
     *
     * @return void
     */
    public function debug()
    {
        if (version_compare(CI_VERSION, self::SUPPORTED_CI_VERSION, '<')) {
            log_message('info',
                sprintf($this->CI->lang->line('version_not_supported'), anchor($this->default_options['ci_website'])));
        }

        if (is_cli()) {
            $this->CI->output->_display();

            return;
        }

        if ($this->CI->input->is_ajax_request()) {
            $this->debug_ajax_request();

            return;
        }

        if ($this->default_options['enable_develbar'] == true && $this->CI->router->class != 'develbarprofiler'
            && in_array($this->CI->output->get_content_type(),
                $this->mimes)
        ) {
            if (version_compare(CI_VERSION, self::SUPPORTED_CI_VERSION, '<')) {
                $this->default_options['check_update'] = true;
                $this->views['not_supported'] = $this->CI->load->view($this->view_folder . 'not_supported',
                    array('config' => $this->default_options), true);
            } else {
                foreach ($this->default_options['develbar_sections'] as $section => $enabled) {
                    if ($enabled) {
                        $section = strtolower(str_replace(' ', '_', $section));
                        $this->views[$section] = call_user_func(array($this, $section . '_section'));
                    }
                }
            }


            $output = $this->CI->output->get_output();
            $develBarOutput = $this->develbar_output();

            // Patch for Pace.js or similar
            if (true == $this->default_options['develbar_sections']['Ajax']) {
                $js = $this->CI->load->file($this->assets_folder.'js/ajax.js', true);
                $js = '<script type="text/javascript">'.$js.'</script>';
                $output = preg_replace('|<head>(.*?)<\/head>|is', '<head>'.$js.'$1</head>', $output, 1, $count);
                if (!$count) {
                    $output = preg_replace('|(<script)|is', $js.'$1', $output, 1);
                }
            }
            // END Patch
            $output = preg_replace('|</body>.*?</html>|is', '', $output, -1, $count) . $develBarOutput;

            if ($count > 0) {
                $output .= '</body></html>';
            }

            $this->CI->output->_display($output);
            return;
        }

        $this->CI->output->_display();
    }

    /**
     * Debug Ajax requests
     */
    private function debug_ajax_request()
    {
        $this->CI->load->driver('cache', array('adapter' => 'file', 'key_prefix' => 'ci_toolbar_profiler_'));
        $develbarConfig = $this->CI->config->config['develbar'];

        $profiler['ajax_requests'] = $this->request_section(false);
        $profiler['database'] = $this->database_section(false);
        $profiler['models'] = $this->models_section(false);
        $profiler['helpers'] = $this->helpers_section(false);
        $profiler['libraries'] = $this->libraries_section(false);
        $profiler['config'] = $this->config_section(false);
        $profilerId = uniqid('', true);

        $this->CI->cache->save($profilerId, $profiler, $develbarConfig['profiler_key_expiration_time']);

        $this->CI->output->set_header("X-CI-Toolbar-Profiler: $profilerId");
        $this->CI->output->_display();
    }

    /**
     * Generate The Developer's Toolbar output.
     *
     * @return mixed
     */
    private function develbar_output()
    {
        $ci_new_version = $this->default_options['check_update'] === true ? check_ci_version($this->default_options['ci_update_uri']) : false;
        $develbar_new_version = $this->default_options['check_update'] === true ? check_develbar_version($this->default_options['develbar_update_uri']) : false;

        $data = array(
            'ci_version' => CI_VERSION,
            'develBar_version' => self::VERSION,
            'sections' => $this->default_options['develbar_sections'],
            'ci_new_version' => $ci_new_version,
            'develbar_new_version' => $develbar_new_version,
            'css' => $this->CI->load->file($this->assets_folder . 'css/develbar.css', true),
            'js' => $this->CI->load->file($this->assets_folder . 'js/develbar.js', true),
            'logo' => image_base64_encode($this->assets_folder . 'images/ci.png'),
            'views' => $this->views,
            'config' => $this->default_options,
        );

        return $this->CI->load->view($this->view_folder . 'develbar', $data, true);
    }

    /**
     * Benchmarks section
     *
     * This function cycles through the entire array of mark points and
     * matches any two points that are named identically (ending in "_start"
     * and "_end" respectively).  It then compiles the execution times for
     * all points and returns it as an array
     *
     * @return    array
     */
    protected function benchmarks_section($return_view = true)
    {
        $data['icon'] = image_base64_encode($this->assets_folder . 'images/timer.png');

        $data['benchmarks']['total_time'] = array(
            'profile' => 'Total Execution Time',
            'elapsed_time' => $this->CI->benchmark->elapsed_time()
        );

        foreach ($this->CI->benchmark->marker as $marker => $time) {
            if (preg_match("/(.+?)_end/i", $marker, $matches)) {
                $start = $matches[1] . '_start';
                $end = $matches[1] . '_end';
                if (isset($this->CI->benchmark->marker[$end]) AND
                    isset($this->CI->benchmark->marker[$start])
                ) {

                    $profile = ucwords(str_replace(array('_', '-'), ' ', $matches[1]));
                    $data['benchmarks']['profiles'][] = array(
                        'profile' => $profile,
                        'elapsed_time' => $this->CI->benchmark->elapsed_time($start, $end),
                    );

                }
            }
        }

        if (!$return_view) {
            return $data;
        }

        return $this->CI->load->view($this->view_folder . 'benchmarks', $data, true);
    }

    /**
     * Display total used memory
     *
     * @return mixed
     */
    protected function memory_usage_section()
    {
        $data = array(
            'icon' => image_base64_encode($this->assets_folder . 'images/memory.png'),
            'memory' => $this->CI->benchmark->memory_usage(),
        );

        return $this->CI->load->view($this->view_folder . 'memory_usage', $data, true);
    }

    /**
     * Show the controller and function that were called
     *
     * @return mixed
     */
    protected function request_section($return_view = true)
    {
        $data = array(
            'icon' => image_base64_encode($this->assets_folder . 'images/setting.png'),
            'method' => ($method = strtolower($_SERVER['REQUEST_METHOD'])),
            'controller' => $this->CI->router->class,
            'action' => $this->CI->router->method,
            'parameters' => $this->CI->input->{$method}(),
        );

        if (!$return_view) {
            return $data;
        }

        return $this->CI->load->view($this->view_folder . 'request', $data, true);
    }

    /**
     * Compile Queries
     *
     * @param bool $return_view
     * @return mixed
     */
    protected function database_section($return_view = true)
    {
        $dbs = $data = array();
        $cobjects = get_object_vars($this->CI);

        foreach ($cobjects as $name => $cobject) {
            if (is_object($cobject)) {
                if ($cobject instanceof CI_DB) {
                    $controller = &get_instance();
                    if ($controller instanceof CI_Controller) {
                        $database = array(
                            'database' => $cobject->database,
                            'hostname' => $cobject->hostname,
                            'queries' => $cobject->queries,
                            'query_times' => $cobject->query_times,
                            'query_count' => $cobject->query_count,
                        );
                        $dbs[get_class($this->CI) . ':$' . $name] = $database;
                    }
                } elseif ($cobject instanceof CI_Model) {
                    foreach (get_object_vars($cobject) as $mname => $mobject) {
                        if ($mobject instanceof CI_DB) {
                            $database = array(
                                'database' => $mobject->database,
                                'hostname' => $mobject->hostname,
                                'queries' => $mobject->queries,
                                'query_times' => $mobject->query_times,
                                'query_count' => $mobject->query_count,
                            );
                            $dbs[get_class($cobject) . ':$' . $mname] = $database;
                        }
                    }
                }
            }
        }

        $data = array(
            'icon' => image_base64_encode($this->assets_folder . 'images/database.png'),
            'dbs' => $dbs,
        );

        if (!$return_view) {
            return $data;
        }

        return $this->CI->load->view($this->view_folder . 'database', $data, true);
    }

    /**
     * Retrieve activated Hooks
     *
     * @return    array
     */
    protected function hooks_section()
    {
        $total_hooks = 0;
        $hooks = array();

        foreach ($this->CI->hooks->hooks as $hook_point => $_hooks) {
            if (is_callable($_hooks)) {
                $hooks[$hook_point][] = 'Closure';
                $total_hooks++;
                continue;
            }
            if (!isset($_hooks[0])) {
                $_hooks = array($_hooks);
            }
            foreach ($_hooks as $hook) {
                if (!array_key_exists('class', $hook)) {
                    $hooks[$hook_point][] = $hook;
                    $total_hooks++;
                }
                elseif (class_exists($hook['class']) && get_class($this) != $hook['class']) {
                    $hooks[$hook_point][] = $hook;
                    $total_hooks++;
                }
            }
        }

        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/hook.png'),
            'loaded_hooks' => $hooks,
            'total_hooks' => $total_hooks,
        );

        return $this->CI->load->view($this->view_folder . 'hooks', $data, true);
    }

    /**
     * Lists of loaded libraries
     *
     * @param bool $return_view
     * @return mixed
     */
    protected function libraries_section($return_view = true)
    {
        $loaded_libraries =& is_loaded();
        asort($loaded_libraries);

        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/library.png'),
            'loaded_libraries' => $loaded_libraries,
        );

        if (!$return_view) {
            return $data;
        }

        return $this->CI->load->view($this->view_folder . 'libraries', $data, true);
    }

    /**
     * Lists of loaded helpers
     *
     * @return mixed
     */
    protected function helpers_section($return_view = true)
    {
        $helpers = array_keys($this->CI->load->get_helpers());
        asort($helpers);

        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/helper.png'),
            'helpers' => $helpers,
        );

        if (!$return_view) {
            return $data;
        }

        return $this->CI->load->view($this->view_folder . 'helpers', $data, true);
    }

    /**
     * Lists of loaded Models
     *
     * @return mixed
     */
    protected function models_section($return_view = true)
    {
        $models = $this->CI->load->get_models();
        asort($models);

        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/model.png'),
            'models' => $this->CI->load->get_models(),
        );

        if (!$return_view) {
            return $data;
        }

        return $this->CI->load->view($this->view_folder . 'models', $data, true);
    }

    /**
     * Lists of loaded helpers
     *
     * @return mixed
     */
    protected function views_section()
    {
        $views = $this->CI->load->get_views();
        $base_path = substr(str_replace(SYSDIR, '', BASEPATH), 0, -1);

        $_views = array();

        foreach ($views as $path => $data) {
            if (stripos($path, 'develbar') !== false) {
                continue;
            }

            $path = str_replace($base_path, '', $path);
            $_views[$path] = $data;
        }

        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/view.png'),
            'views' => $_views,
        );

        return $this->CI->load->view($this->view_folder . 'views', $data, true);
    }

    /**
     * Lists developer config variables
     *
     * @return mixed
     */
    protected function config_section($return_view = true)
    {
        unset($this->CI->config->config['develbar']);
        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/config.png'),
            'configuration' => $this->CI->config->config
        );

        if (!$return_view) {
            return $data;
        }

        return $this->CI->load->view($this->view_folder . 'config', $data, true);
    }

    /**
     * Compile session userdata
     *
     * @return  mixed
     */
    protected function session_section()
    {
        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/session.png'),
            'session' => isset($this->CI->session) ? $this->CI->session->all_userdata() : array()
        );

        return $this->CI->load->view($this->view_folder . 'session', $data, true);
    }

    /**
     * List ajax requests
     *
     * @return string
     */
    protected function ajax_section()
    {
        $data = array(
            'icon' => $data['icon'] = image_base64_encode($this->assets_folder . 'images/ajax.png'),
        );

        return $this->CI->load->view($this->view_folder . 'ajax', $data, true);
    }
}
