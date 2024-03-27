<?php

namespace OpenSID;

use OpenSID\Exception\RouteNotFoundException;
use OpenSID\RouteBuilder as Route;

/**
 * Defines and returns all the required OpenSID CI hooks at framework startup
 *
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Hook
{
    /**
     * Gets the OpenSID CI hooks
     *
     * @param string $config OpenSID CI configuration
     *
     * @return array
     */
    public static function getHooks($config = [])
    {
        $hooks = [];

        $hooks['pre_system'][] = function () use ($config) {
            self::preSystemHook($config);
        };

        $hooks['pre_controller'][] = function () {
            global $params, $URI, $class, $method;

            self::preControllerHook($params, $URI, $class, $method);
        };

        $hooks['post_controller_constructor'][] = function () use ($config) {
            global $params;
            self::postControllerConstructorHook($config, $params);
        };

        $hooks['post_controller'][] = function () use ($config) {
            self::postControllerHook($config);
        };

        $hooks['display_override'][] = function () {
            self::displayOverrideHook();
        };

        return $hooks;
    }

    /**
     * "pre_system" hook
     *
     * @param array $config
     *
     * @return void
     */
    private static function preSystemHook($config)
    {
        define('OpenSID_CI_VERSION', '1.0.5');
        define('OpenSID_CI_DIR', __DIR__);

        $isAjax =  isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                    && (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

        $isCli  =  is_cli();
        $isWeb  = !is_cli();

        require_once __DIR__ . '/Facades/Route.php' ;

        if(!file_exists(APPPATH . '/Routes')) {
            mkdir(APPPATH . '/Routes');
        }

        if(!file_exists(APPPATH . '/Middleware')) {
            mkdir(APPPATH . '/Middleware');
        }

        if(!file_exists(APPPATH . '/Routes/web.php')) {
            copy(__DIR__ . '/Resources/DefaultWebRoutes.php', APPPATH . '/Routes/web.php');
        }
        $modulesLocation = $config['modules_location'] ?? [];
        if($isWeb) {
            // Include all routes web.php
            $mapModules = [];
            foreach ($modulesLocation as $key => $value) {
                $mapModules = array_merge($mapModules, glob($key . '*/Routes/web.php'));
            }

            $fileWeb = array_merge($mapModules, glob(APPPATH . 'Routes/web.php'));
            foreach ($fileWeb as $file) {
                require_once $file;
            }
        }

        if(!file_exists(APPPATH . '/Routes/api.php')) {
            copy(__DIR__ . '/Resources/DefaultApiRoutes.php', APPPATH . '/Routes/api.php');
        }

        if($isAjax || $isWeb) {
            Route::group(
                '/',
                ['middleware' => [ new RouteAjaxMiddleware() ]],
                function () use ($modulesLocation) {
                    // Include all routes web.php
                    $mapModules = [];
                    foreach ($modulesLocation as $key => $value) {
                        $mapModules = array_merge($mapModules, glob($key . '*/Routes/api.php'));
                    }
                    // Include all routes api.php
                    $fileApi = array_merge($mapModules, glob(APPPATH . 'Routes/api.php'));
                    foreach ($fileApi as $file) {
                        require_once $file;
                    }
                }
            );
        }

        if(!file_exists(APPPATH . '/Routes/console.php')) {
            copy(__DIR__ . '/Resources/DefaultConsoleRoutes.php', APPPATH . '/Routes/console.php');
        }

        if($isCli) {
            require_once(APPPATH . '/Routes/console.php');
            Route::set('default_controller', Route::DEFAULT_CONTROLLER);
        }

        require_once(__DIR__ . '/Functions.php');

        // Compiling all routes
        Route::compileAll();

        // HTTP verb tweak
        //
        // (This allows us to use any HTTP Verb if the form contains a hidden field
        // named "_method")
        if(isset($_SERVER['REQUEST_METHOD'])) {
            if(strtolower($_SERVER['REQUEST_METHOD']) == 'post' && isset($_POST['_method'])) {
                $_SERVER['REQUEST_METHOD'] = $_POST['_method'];
            }

            $requestMethod = $_SERVER['REQUEST_METHOD'];
        } else {
            $requestMethod = 'CLI';
        }

        // Getting the current url
        $url = Utils::currentUrl();

        try {
            $currentRoute = Route::getByUrl($url);
        } catch(RouteNotFoundException $e) {
            Route::$compiled['routes'][$url] = Route::DEFAULT_CONTROLLER . '/index';
            $currentRoute =  Route::{ !is_cli() ? 'any' : 'cli' }($url, function () {
                if(!is_cli() && is_callable(Route::get404())) {
                    $_404 = Route::get404();
                    call_user_func($_404);
                } else {
                    show_404();
                }
            });
            $currentRoute->is404 = true;
            $currentRoute->isCli = is_cli();
        };

        $currentRoute->requestMethod = $requestMethod;

        // Debug::log('>>> CURRENT ROUTE:', 'info', 'routing');
        // Debug::log($currentRoute, 'info', 'routing');
        // Debug::log('>>> RAW ROUTING:', 'info', 'routing');
        // Debug::log(Route::$compiled['routes'], 'info', 'routing');

        Route::setCurrentRoute($currentRoute);
    }

    /**
     * "pre_controller" hook
     *
     * @param  array    $params
     * @param  string   $URI
     * @param  string   $class
     * @param  string   $method
     *
     * @return void
     */
    private static function preControllerHook(&$params, &$URI, &$class, &$method)
    {
        $route  = Route::getCurrentRoute();

        // Is a 404 route? stop this hook
        if($route->is404) {
            return;
        }

        $path   = $route->getFullPath();
        $pcount = 0;

        // Removing controller's sub-directory limitation over "/" path
        if($path == '/') {
            if(!empty($route->getNamespace()) || is_string($route->getAction())) {
                $dir = $route->getNamespace();
                list($_class, $_method) = explode('@', $route->getAction());

                $_controller = APPPATH . 'controllers/' . (!empty($dir) ? $dir . '/' : '') . $_class . '.php';

                if(file_exists($_controller)) {
                    require_once $_controller;
                    list($class, $method) = explode('@', $route->getAction());
                } else {
                    $route->setAction(function () {
                        if(!is_cli() && is_callable(Route::get404())) {
                            $_404 = Route::get404();
                            call_user_func($_404);
                        } else {
                            show_404();
                        }
                    });
                }
            }
        }


        if(!$route->isCli) {
            $params_result = [];
            $scount = 0;

            foreach(explode('/', $path) as $currentSegmentIndex => $segment) {
                $key = [];

                if(preg_match('/\{(.*?)\}+/', $segment)) {

                    foreach ($route->params as $param) {
                        if (empty($key[$param->getSegmentIndex()])) {
                            $key[$param->getSegmentIndex()] = str_replace($param->getSegment(), '(' . $param->getRegex() . ')', $param->getFullSegment());
                        } else {
                            $key[$param->getSegmentIndex()] = str_replace($param->getSegment(), '(' . $param->getRegex() . ')', $key[$param->getSegmentIndex()]);
                        }
                    }

                    foreach ($route->params as $param) {
                        if ($param->segmentIndex === $currentSegmentIndex) {
                            $segment = preg_replace('/\((.*)\):/', '', $segment);

                            $segment = $URI->segment($currentSegmentIndex + 1);
                            if ($segment !== null && preg_match('#^' . $key[$currentSegmentIndex] . '$#', $segment, $matches)) {
                                if (isset($matches[$pcount + 1 - $scount])) {
                                    $route->params[$pcount]->value = $matches[$pcount + 1 - $scount];
                                }
                            }

                            if(is_callable($route->getAction()) && !empty($URI->segment($currentSegmentIndex + 1))) {
                                $params[$route->params[$pcount]->getName()] = $URI->segment($currentSegmentIndex + 1);
                            }

                            // Removing "sticky" route parameters
                            if(substr($param->getName(), 0, 1) !== '_') {
                                $params_result[] = $route->params[$pcount]->value;
                            }

                            $pcount++;
                        }
                    }
                    $scount++;
                }
            }

            $params = $params_result;
        } else {
            if(!empty($route->params)) {
                $argv = array_slice($_SERVER['argv'], 1);

                if($argv) {
                    $params = array_slice($argv, $route->paramOffset);
                }

                foreach($route->params as $i => &$param) {
                    $param->value = isset($params[$i]) ? $params[$i] : null;
                }
            }
        }

        Route::setCurrentRoute($route);

        // If the current route is an anonymous route, we must prevent
        // the execution of their 'traditional' counterpart (if exists)
        if(is_callable($route->getAction())) {
            $RTR = &load_class('Router', 'core');
            $class = Route::DEFAULT_CONTROLLER;
            if(!class_exists($class)) {
                require_once APPPATH . '/controllers/' . Route::DEFAULT_CONTROLLER . '.php';
            }
            $method = 'index';
        }
    }

    /**
     * "post_controller" hook
     *
     * @param  array $config
     * @param  array $params
     *
     * @return void
     */
    private static function postControllerConstructorHook($config, &$params)
    {
        // Current route configuration and dispatch
        ci()->route = Route::getCurrentRoute();

        if(!ci()->route->is404) {
            ci()->load->helper('url');
            ci()->middleware = new Middleware();

            if(method_exists(ci(), 'preMiddleware')) {
                call_user_func([ci(), 'preMiddleware']);
            }

            foreach(Route::getGlobalMiddleware()['pre_controller'] as $middleware) {
                ci()->middleware->run($middleware);
            }

            // Setting "sticky" route parameters values as default for current route
            foreach(ci()->route->params as &$param) {
                if(substr($param->getName(), 0, 1) == '_') {
                    Route::setDefaultParam($param->getName(), ci()->route->param($param->getName()));
                }
            }

            foreach(ci()->route->getMiddleware() as $middleware) {
                if(is_string($middleware)) {
                    $middleware = [ $middleware ];
                }

                foreach($middleware as $_middleware) {
                    ci()->middleware->run($_middleware);
                }
            }
        }

        if(is_callable(ci()->route->getAction())) {
            call_user_func_array(ci()->route->getAction(), $params);
        }
    }

    /**
     * "post_controller" hook
     *
     * @param array $config
     *
     * @return void
     */
    private static function postControllerHook($config)
    {
        if(ci()->route->is404) {
            return;
        }

        foreach(Route::getGlobalMiddleware()['post_controller'] as $middleware) {
            ci()->middleware->run($middleware);
        }
    }

    /**
     * "display_override" hook
     *
     * @return void
     */
    private static function displayOverrideHook()
    {
        $output = ci()->output->get_output();

        if (isset(ci()->db)) {
            $queries = ci()->db->queries;
        }

        ci()->output->_display($output);
    }
}
