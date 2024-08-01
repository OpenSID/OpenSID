<?php

namespace OpenSID;

/**
 * Middleware handler class
 *
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Middleware
{
    /**
     * @var array
     */
    private static $loadedMiddleware = [];
    
    /**
     * Loads a middleware class
     * 
     * @param mixed $middleware Middleware name
     * 
     * @return void
     */
    public static function load($middleware)
    {
        if(isset(self::$loadedMiddleware[$middleware]))
        {
            return self::$loadedMiddleware[$middleware];
        }

        $target = APPPATH . '/Middleware/' . $middleware . '.php';

        if( file_exists($target))
        {
            require_once($target);

            $middlewareInstance = new $middleware();

            if(!$middlewareInstance instanceof MiddlewareInterface)
            {
                show_error('Your middleware MUST implement the "MiddlewareInterface" interface');
            }

            self::$loadedMiddleware[$middleware] = $middlewareInstance;

            return $middlewareInstance;
        }

        show_error('Unable to find <strong>' . $middleware .'.php</strong> in your application/middleware folder');
    }

    /**
     * Runs a middleware
     * 
     * @param string|callable $middleware
     * @param array $args
     * 
     * @return void
     */
    final public function run($middleware, $args = [])
    {
        if(is_callable($middleware))
        {
            call_user_func_array($middleware, $args);
        }
        // else if(is_object($middleware) && $middleware instanceof MiddlewareInterface)
        else if(is_object($middleware))
        {
            if(!$middleware instanceof MiddlewareInterface)
            {
                Debug::log('DEPRECATED: All your middleware MUST implement the Luthire\MiddlewareInterface interface. Please fix this issue with "' . get_class($middleware) . '" middleware');
                if(method_exists($middleware,'run'))
                {
                    show_error('Your "' . get_class($middleware) . '" middleware doesn\'t have a run() public method');
                }
            }

            $middleware->run($args);
        }
        else if(is_array($middleware))
        {
            foreach($middleware as $run)
            {
                $this->run($run, $args);
            }
            return;
        }
        else
        {
            $middlewareInstance = self::load($middleware);
            call_user_func([$middlewareInstance, 'run'], $args);
        }
    }

    /**
     * Binds a middleware to CodeIgniter hook at runtime
     * 
     * @param string    $hook        Hook name
     * @param callable  $middleware  Middleware callable
     * @param array[]   ...$args     Middleware ars
     * 
     * @return void
     */
    final public function addHook($hook, $middleware, ...$args)
    {
        if(is_callable($middleware))
        {
            if(empty($args))
            {
                $args[] =& get_instance();
            }

            if(isset(ci()->hooks->hooks[$hook]) && !is_array(ci()->hooks->hooks[$hook]))
            {
                $_hook = ci()->hooks->hooks[$hook];
                ci()->hooks->hooks[$hook] = [ $_hook ];
            }

            ci()->hooks->hooks[$hook][] = call_user_func_array($middleware, $args);
        }
        else
        {
            ci()->hooks->hooks[$hook][] = call_user_function_array([$this,'run'], [ $middleware, $args] );
        }
    }
}
