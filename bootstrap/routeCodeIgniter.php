<?php

use OpenSID\RouteBuilder;
use Illuminate\Support\Str;
use App\Events\ControllerCalled;
use App\Events\CallingController;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Events\Routing;
use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * ------------------------------------------------------
 * Sanity checks
 * ------------------------------------------------------
 *
 * The Router class has already validated the request,
 * leaving us with 3 options here:
 *
 * 1) an empty class name, if we reached the default
 *    controller, but it didn't exist;
 * 2) a query string which doesn't go through a
 *    file_exists() check
 * 3) a regular request for a non-existing page
 *
 * We handle all of these as a 404 error.
 *
 * Furthermore, none of the methods in the app controller
 * or the loader class can be called via the URI, nor can
 * controller methods that begin with an underscore.
 */
$BM  = &$GLOBALS['BM'];
$RTR = &$GLOBALS['RTR'];
$URI = &$GLOBALS['URI'];
$EXT = &$GLOBALS['EXT'];
$OUT = &$GLOBALS['OUT'];

$e404 = false;
$class = ucfirst($RTR->class);
$method = $RTR->method;

if (empty($class) or ! file_exists(APPPATH.'controllers/'.$RTR->directory.$class.'.php')) {
    $e404 = true;
} else {
    require_once APPPATH.'controllers/'.$RTR->directory.$class.'.php';

    if (! class_exists($class, false) or $method[0] === '_' or method_exists('CI_Controller', $method)) {
        $e404 = true;
    } elseif (method_exists($class, '_remap')) {
        $params = [$method, array_slice($URI->rsegments, 2)];
        $method = '_remap';
    } elseif (! method_exists($class, $method)) {
        $e404 = true;
    }
    /**
     * DO NOT CHANGE THIS, NOTHING ELSE WORKS!
     *
     * - method_exists() returns true for non-public methods, which passes the previous elseif
     * - is_callable() returns false for PHP 4-style constructors, even if there's a __construct()
     * - method_exists($class, '__construct') won't work because CI_Controller::__construct() is inherited
     * - People will only complain if this doesn't work, even though it is documented that it shouldn't.
     *
     * ReflectionMethod::isConstructor() is the ONLY reliable check,
     * knowing which method will be executed as a constructor.
     */
    else {
        $reflection = new ReflectionMethod($class, $method);
        if (! $reflection->isPublic() or $reflection->isConstructor()) {
            $e404 = true;
        }
    }
}

if ($e404) {
    if (! empty($RTR->routes['404_override'])) {
        if (sscanf($RTR->routes['404_override'], '%[^/]/%s', $error_class, $error_method) !== 2) {
            $error_method = 'index';
        }

        $error_class = ucfirst($error_class);

        if (! class_exists($error_class, false)) {
            if (file_exists(APPPATH.'controllers/'.$RTR->directory.$error_class.'.php')) {
                require_once APPPATH.'controllers/'.$RTR->directory.$error_class.'.php';
                $e404 = ! class_exists($error_class, false);
            }
            // Were we in a directory? If so, check for a global override
            elseif (! empty($RTR->directory) && file_exists(APPPATH.'controllers/'.$error_class.'.php')) {
                require_once APPPATH.'controllers/'.$error_class.'.php';
                if (($e404 = ! class_exists($error_class, false)) === false) {
                    $RTR->directory = '';
                }
            }
        } else {
            $e404 = false;
        }
    }

    // Did we reset the $e404 flag? If so, set the rsegments, starting from index 1
    if (! $e404) {
        $class = $error_class;
        $method = $error_method;

        $URI->rsegments = [
            1 => $class,
            2 => $method,
        ];
    }
}

if ($method !== '_remap') {
    $params = array_slice($URI->rsegments, 2);
}

/**
 * ------------------------------------------------------
 *  Is there a "pre_controller" hook?
 * ------------------------------------------------------
 */
Event::listen(Routing::class, fn () => $EXT->call_hook('pre_controller'));

/**
 * ------------------------------------------------------
 *  Instantiate the requested controller
 * ------------------------------------------------------
 */
// Mark a start point so we can benchmark the controller
Event::listen(CallingController::class, fn (CallingController $event) => $BM->mark("controller_execution_time_( {$event->getControllerClass()} / {$event->method} )_start"));

/**
 * ------------------------------------------------------
 *  Is there a "post_controller_constructor" hook?
 * ------------------------------------------------------
 */
Event::listen(CallingController::class, fn () => $EXT->call_hook('post_controller_constructor'));

/**
 * ------------------------------------------------------
 * Define route model bindings, pattern filters,
 * and other route configuration.
 * ------------------------------------------------------
 */
$currentRoute = RouteBuilder::getCurrentRoute();

if ($currentRoute->is404) {
    show_404("{$RTR->directory}{$class}/{$method}");
}

if ($currentRoute->isCli) {
    // Handle CLI
    [$controller, $method] = explode('@', $currentRoute->getAction());
    exit(call_user_func_array([new $controller(), $method], $params ?? []) ?? 0);
} else {
    // Handle Web
    $action = $currentRoute->getAction();

    if ($action instanceof \Closure) {
        new \CI_Controller();
    } else {
        $action = Str::afterLast($action, '/');
    }

    Route::match(
        $currentRoute->getMethods(),
        $currentRoute->getFullPath(),
        $action
    )
    ->name($currentRoute->getName())
    ->setDefaults(
        collect($currentRoute->params)
            ->filter(fn ($p) => $p->value !== null)
            ->mapWithKeys(fn ($p) => [$p->getName() => $p->value])
            ->all()
    );
}

// Mark a benchmark end point
Event::listen(ControllerCalled::class, fn (ControllerCalled $event) => $BM->mark("controller_execution_time_( {$event->getControllerClass()} / {$event->method} )_end"));

/**
 * ------------------------------------------------------
 *  Is there a "post_controller" hook?
 * ------------------------------------------------------
 */
Event::listen(ControllerCalled::class, fn () => $EXT->call_hook('post_controller'));

/**
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */
Event::listen(RequestHandled::class, function () use ($EXT, $OUT) {
    if ($EXT->call_hook('display_override') === false) {
        $OUT->_display();
    }
});

/**
 * ------------------------------------------------------
 *  Is there a "post_system" hook?
 * ------------------------------------------------------
 */
app()->terminating(fn () => $EXT->call_hook('post_system'));
