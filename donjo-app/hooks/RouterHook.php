<?php

defined('BASEPATH') || exit('No direct script access allowed');

use OpenSID\Exception\RouteNotFoundException;
use OpenSID\RouteBuilder as Route;
use OpenSID\Middleware;
use OpenSID\Utils;

/**
 * RouterHook - Manages all routing hooks for CodeIgniter
 *
 * Consolidates and optimizes routing logic for pre-system, pre-controller,
 * and post-controller hooks with improved code organization and performance.
 *
 * @package OpenSID
 * @author Tim Pengembang OpenDesa
 */
class RouterHook
{
    /**
     * Configuration array for the hook
     *
     * @var array
     */
    protected array $config;

    /**
     * Request method (GET, POST, etc.)
     *
     * @var string
     */
    private string $requestMethod = 'CLI';

    /**
     * Current detected environment flags
     *
     * @var array
     */
    private array $environmentFlags = [];

    /**
     * Initializes configuration and environment detection
     */
    public function __construct()
    {
        $this->config = ['modules_location' => config_item('modules_locations')];

        $this->initializeEnvironment();
    }

    /**
     * Get all hooks for CodeIgniter
     *
     * @return array Associative array of hook callbacks
     */
    public function getHooks(): array
    {
        return [
            'pre_system' => [fn() => $this->handlePreSystem()],
            'pre_controller' => [fn() => $this->handlePreController()],
            'post_controller_constructor' => [fn() => $this->handlePostControllerConstructor()],
            'post_controller' => [fn() => $this->handlePostController()],
            'display_override' => [fn() => $this->handleDisplayOverride()],
        ];
    }

    /**
     * Initialize environment detection flags
     *
     * @return void
     */
    private function initializeEnvironment(): void
    {
        $this->environmentFlags = [
            'isAjax' => $this->isAjaxRequest(),
            'isCli'  => is_cli(),
            'isWeb'  => ! is_cli(),
        ];
    }

    /**
     * Check if the current request is AJAX
     *
     * @return bool
     */
    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Handle pre_system hook
     * Initializes framework constants, loads routes, and sets up current route
     *
     * @return void
     */
    public function handlePreSystem(): void
    {
        $this->defineFrameworkConstants();
        $this->ensureDirectoriesExist();
        $this->loadRoutes();
        $this->detectRequestMethod();
        $this->resolveCurrentRoute();
    }

    /**
     * Define framework constants
     *
     * @return void
     */
    private function defineFrameworkConstants(): void
    {
        defined('OpenSID_CI_VERSION') || define('OpenSID_CI_VERSION', '1.0.5');
        defined('OpenSID_CI_DIR') || define('OpenSID_CI_DIR', base_path('vendor/opensid/router/src'));
    }

    /**
     * Ensure required directories exist
     *
     * @return void
     */
    private function ensureDirectoriesExist(): void
    {
        $directories = [
            APPPATH . '/Routes',
            APPPATH . '/Middleware',
        ];

        foreach ($directories as $directory) {
            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
        }
    }

    /**
     * Load routes from configuration
     *
     * @return void
     */
    private function loadRoutes(): void
    {
        require_once base_path('vendor/opensid/router/src/Facades/Route.php');

        // Ensure default route files exist
        $this->ensureDefaultRouteFiles();

        if ($this->environmentFlags['isWeb']) {
            $this->loadWebRoutes();
        }

        if ($this->environmentFlags['isAjax'] || $this->environmentFlags['isWeb']) {
            $this->loadApiRoutes();
        }

        if ($this->environmentFlags['isCli']) {
            $this->loadConsoleRoutes();
        }

        $route = new Route();

        // Reset compiled routes
        $route::$compiled = [
            'routes'   => [],
            'paths'    => [],
            'names'    => [],
            'reserved' => [],
        ];

        // Compile all routes
        $route::compileAll();

        // Load helper functions
        require_once base_path('vendor/opensid/router/src/Functions.php');
    }

    /**
     * Ensure default route files exist
     *
     * @return void
     */
    private function ensureDefaultRouteFiles(): void
    {
        $resourcePath = base_path('vendor/opensid/router/src/Resources');

        $defaultFiles = [
            APPPATH . '/Routes/web.php' => $resourcePath . '/DefaultWebRoutes.php',
            APPPATH . '/Routes/api.php' => $resourcePath . '/DefaultApiRoutes.php',
            APPPATH . '/Routes/console.php' => $resourcePath . '/DefaultConsoleRoutes.php',
        ];

        foreach ($defaultFiles as $target => $source) {
            if (! file_exists($target) && file_exists($source)) {
                copy($source, $target);
            }
        }
    }

    /**
     * Load web routes
     *
     * @return void
     */
    private function loadWebRoutes(): void
    {
        $routeFiles = $this->collectRouteFiles('web.php');
        foreach ($routeFiles as $file) {
            require_once $file;
        }
    }

    /**
     * Load API routes
     *
     * @return void
     */
    private function loadApiRoutes(): void
    {
        Route::group('/', [], function () {
            $routeFiles = $this->collectRouteFiles('api.php');
            foreach ($routeFiles as $file) {
                require_once $file;
            }
        });
    }

    /**
     * Load console routes
     *
     * @return void
     */
    private function loadConsoleRoutes(): void
    {
        $consoleFile = APPPATH . '/Routes/console.php';
        if (file_exists($consoleFile)) {
            require_once $consoleFile;
            Route::set('default_controller', Route::DEFAULT_CONTROLLER);
        }
    }

    /**
     * Collect route files from modules and app
     *
     * @param string $routeFile Route file name (web.php, api.php, etc.)
     * @return array List of route file paths
     */
    private function collectRouteFiles(string $routeFile): array
    {
        $modulesLocation = $this->config['modules_location'] ?? [];
        $files = [];

        // Collect module routes
        foreach ($modulesLocation as $modulePath => $value) {
            // T31: lewati rute modul terenkripsi IonCube bila Loader tak terpasang.
            $files = array_merge($files, \App\Services\Module\PemeriksaIonCube::saringRute(glob($modulePath . '*/Routes/' . $routeFile) ?: []));
        }

        // Add app routes
        $appRoute = APPPATH . 'Routes/' . $routeFile;
        if (file_exists($appRoute)) {
            $files[] = $appRoute;
        }

        return $files;
    }

    /**
     * Detect current request method
     *
     * @return void
     */
    private function detectRequestMethod(): void
    {
        if (! isset($_SERVER['REQUEST_METHOD'])) {
            $this->requestMethod = 'CLI';

            return;
        }

        // Handle HTTP method override via hidden form field
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post' && isset($_POST['_method'])) {
            $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
        }

        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Resolve current route from URL
     *
     * @return void
     */
    private function resolveCurrentRoute(): void
    {
        $url = Utils::currentUrl();

        try {
            $currentRoute = Route::getByUrl($url);
        } catch (RouteNotFoundException $e) {
            $currentRoute = $this->create404Route($url);
        }

        $currentRoute->requestMethod = $this->requestMethod;
        Route::setCurrentRoute($currentRoute);
    }

    /**
     * Create a 404 route for unmatched URLs
     *
     * @param string $url The requested URL
     * @return mixed The 404 route object
     */
    private function create404Route(string $url)
    {
        Route::$compiled['routes'][$url] = Route::DEFAULT_CONTROLLER . '/index';

        $method = $this->environmentFlags['isWeb'] ? 'any' : 'cli';
        $currentRoute = Route::$method($url, function () {
            if (!$this->environmentFlags['isWeb'] || !is_callable(Route::get404())) {
                show_404();

                return;
            }

            $handler = Route::get404();
            call_user_func($handler);
        });

        $currentRoute->is404 = true;
        $currentRoute->isCli = $this->environmentFlags['isCli'];

        return $currentRoute;
    }

    /**
     * Handle pre_controller hook
     * Processes route parameters and sets up controller/method
     *
     * @return void
     */
    public function handlePreController(): void
    {
        $route = Route::getCurrentRoute();

        if ($route->is404) {
            return;
        }

        $path = $route->getFullPath();

        // Handle root path special case
        if ($path === '/' && !empty($route->getNamespace() ?: $route->getAction())) {
            $this->handleRootPathController($route);
        }

        // Process route parameters
        if (! $route->isCli) {
            $this->processWebRouteParameters($route);
        } else {
            $this->processCliRouteParameters($route);
        }

        Route::setCurrentRoute($route);
    }

    /**
     * Handle root path controller resolution
     *
     * @param mixed $route The current route
     * @return void
     */
    public function handleRootPathController($route): void
    {
        if (! is_string($route->getAction())) {
            return;
        }

        [$controllerClass, $method] = explode('@', $route->getAction());
        $dir = $route->getNamespace();
        $controllerPath = APPPATH . 'controllers/' . ($dir ? $dir . '/' : '') . $controllerClass . '.php';

        if (! file_exists($controllerPath)) {
            $route->setAction(function () {
                if ($this->environmentFlags['isWeb'] && is_callable(Route::get404())) {
                    call_user_func(Route::get404());
                    return;
                }
                show_404();
            });
        } else {
            require_once $controllerPath;
        }
    }

    /**
     * Process route parameters for web requests
     *
     * @param mixed $route The current route
     * @return void
     */
    private function processWebRouteParameters($route): void
    {
        global $params, $URI;

        $path = $route->getFullPath();
        $paramsResult = [];
        $paramCount = 0;
        $segmentCount = 0;

        foreach (explode('/', $path) as $segmentIndex => $segment) {
            if (! preg_match('/\{(.*?)\}+/', (string) $segment)) {
                continue;
            }

            $keyMap = $this->buildParameterKeyMap($route, $segmentIndex);

            foreach ($route->params as $param) {
                if ($param->segmentIndex !== $segmentIndex) {
                    continue;
                }

                $this->processRouteParameter(
                    $route,
                    $param,
                    $segment,
                    $segmentIndex,
                    $keyMap,
                    $paramCount,
                    $segmentCount,
                    $paramsResult,
                    $URI
                );

                $paramCount++;
            }

            $segmentCount++;
        }

        $params = $paramsResult;
    }

    /**
     * Build parameter key map for regex matching
     *
     * @param mixed $route The current route
     * @param int $segmentIndex The segment index
     * @return string The built key pattern
     */
    private function buildParameterKeyMap($route, int $segmentIndex): string
    {
        $key = '';
        foreach ($route->params as $param) {
            if ($param->getSegmentIndex() !== $segmentIndex) {
                continue;
            }

            $key = str_replace(
                $param->getSegment(),
                "({$param->getRegex()})",
                $param->getFullSegment()
            );
        }

        return $key;
    }

    /**
     * Process individual route parameter
     *
     * @param mixed $route Current route
     * @param mixed $param Current parameter
     * @param string $segment Current path segment
     * @param int $segmentIndex Segment index
     * @param string $keyMap Key pattern map
     * @param int $paramCount Parameter count
     * @param int $segmentCount Segment count
     * @param array $paramsResult Result parameters array
     * @param mixed $URI URI object
     * @return void
     */
    private function processRouteParameter(
        $route,
        $param,
        string $segment,
        int $segmentIndex,
        string $keyMap,
        int $paramCount,
        int $segmentCount,
        array &$paramsResult,
        $URI
    ): void {
        global $params;

        $segment = preg_replace('/\((.*)\):/', '', $segment);
        $segmentValue = $URI->segment($segmentIndex + 1) ?? '';

        if (preg_match("#^{$keyMap}$#", $segmentValue, $matches)) {
            if (isset($matches[$paramCount + 1 - $segmentCount])) {
                $route->params[$paramCount]->value = $matches[$paramCount + 1 - $segmentCount];
            }
        }

        if (is_callable($route->getAction()) && ! empty($segmentValue)) {
            $params[$route->params[$paramCount]->getName()] = $segmentValue;
        }

        // Only add non-sticky parameters to results
        if (substr($param->getName(), 0, 1) !== '_') {
            $paramsResult[] = $route->params[$paramCount]->value;
        }
    }

    /**
     * Process route parameters for CLI requests
     *
     * @param mixed $route The current route
     * @return void
     */
    private function processCliRouteParameters($route): void
    {
        global $params;

        if (empty($route->params)) {
            return;
        }

        $argv = array_slice($_SERVER['argv'] ?? [], 1);
        if ($argv) {
            $params = array_slice($argv, $route->paramOffset);
        }

        foreach ($route->params as $i => &$param) {
            $param->value = $params[$i] ?? null;
        }
    }

    /**
     * Handle post_controller_constructor hook
     * Sets up middleware and executes current action
     *
     * @return void
     */
    public function handlePostControllerConstructor(): void
    {
        global $params;

        ci()->route = Route::getCurrentRoute();

        if (ci()->route->is404) {
            return;
        }

        $this->setupMiddleware();
        $this->applyPreControllerMiddleware();
        $this->applyStickyRouteParameters();
        $this->applyRouteMiddleware();
        $this->executeRouteAction($params);
    }

    /**
     * Setup middleware instance
     *
     * @return void
     */
    private function setupMiddleware(): void
    {
        ci()->load->helper('url');
        ci()->middleware = new Middleware();

        if (method_exists(ci(), 'preMiddleware')) {
            call_user_func([ci(), 'preMiddleware']);
        }
    }

    /**
     * Apply global pre-controller middleware
     *
     * @return void
     */
    private function applyPreControllerMiddleware(): void
    {
        foreach (Route::getGlobalMiddleware()['pre_controller'] as $middleware) {
            ci()->middleware->run($middleware);
        }
    }

    /**
     * Apply sticky route parameters as default
     *
     * @return void
     */
    private function applyStickyRouteParameters(): void
    {
        foreach (ci()->route->params as &$param) {
            if (substr($param->getName(), 0, 1) === '_') {
                Route::setDefaultParam(
                    $param->getName(),
                    ci()->route->param($param->getName())
                );
            }
        }
    }

    /**
     * Apply route-specific middleware
     *
     * @return void
     */
    private function applyRouteMiddleware(): void
    {
        foreach (ci()->route->getMiddleware() as $middleware) {
            $middlewareList = is_string($middleware) ? [$middleware] : $middleware;

            foreach ($middlewareList as $middlewareName) {
                ci()->middleware->run($middlewareName);
            }
        }
    }

    /**
     * Execute the current route action
     *
     * @param array $params Route parameters
     * @return void
     */
    private function executeRouteAction(array $params): void
    {
        if (is_callable(ci()->route->getAction())) {
            call_user_func_array(ci()->route->getAction(), $params);
        }
    }

    /**
     * Handle post_controller hook
     * Executes post-controller global middleware
     *
     * @return void
     */
    public function handlePostController(): void
    {
        if (ci()->route->is404) {
            return;
        }

        foreach (Route::getGlobalMiddleware()['post_controller'] as $middleware) {
            ci()->middleware->run($middleware);
        }
    }

    /**
     * Handle display_override hook
     * Outputs the final response
     *
     * @return void
     */
    public function handleDisplayOverride(): void
    {
        //
    }
}
