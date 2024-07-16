<?php

namespace OpenSID;

/**
 * Represents a OpenSID CI route
 *
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Route
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $fullPath;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $methods = [];

    /**
     * @var string|callable
     */
    private $action;

    /**
     * @var array
     */
    private $middleware = [];

    /**
     * @var string
     */
    private $namespace = '';

    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var RouteParam[]
     */
    public $params = [];

    /**
     * @var int
     */
    public $paramOffset;

    /**
     * @var int
     */
    public $optionalParamOffset;

    /**
     * @var bool
     */
    private $hasOptionalParams = false;

    /**
     * @var bool
     */
    public $is404 = false;

    /**
     * @var bool
     */
    public $isCli = false;

    /**
     * @var string
     */
    public $requestMethod;
    
    /**
     * Gets compiled routes (Alias of RouteBuilder::getRoutes())
     * 
     * @return array
     */
    public static function getRoutes()
    {
        return RouteBuilder::getRoutes();
    }

    /**
     * @param string|array  $methods  Route accepted HTTP verbs
     * @param array         $route    Route attributes
     */
    public function __construct($methods, $route)
    {
        if($methods == 'any')
        {
            $methods = RouteBuilder::HTTP_VERBS;
        }
        elseif(is_string($methods))
        {

            $methods = [ strtoupper($methods) ];
        }
        else
        {
            array_shift($route);
        }

        foreach($methods as $method)
        {
            $this->methods[] = strtoupper($method);
        }

        // Required route attributes
        list($path, $action) = $route;
        $this->path = trim($path, '/') == '' ? '/' : trim($path, '/');

        if(!is_callable($action) && count(explode('@', $action)) != 2)
        {
            show_error('Route action must be in <strong>controller@method</strong> syntax or be a valid callback');
        }

        $this->action = $action;
        $attributes = isset($route[2]) && is_array($route[2]) ? $route[2] : NULL;

        // Route group inherited attributes
        if(!empty(RouteBuilder::getContext('prefix')))
        {
            $prefixes = RouteBuilder::getContext('prefix');
            foreach($prefixes as $prefix)
            {
                $this->prefix .= trim($prefix,'/') != '' ? '/' .trim($prefix, '/') : '';
            }
            $this->prefix = trim($this->prefix,'/');
        }

        if(!empty(RouteBuilder::getContext('namespace')))
        {
            $namespaces = RouteBuilder::getContext('namespace');
            foreach($namespaces as $namespace)
            {
                $this->namespace .= trim($namespace, '/') != '' ? '/' .trim($namespace, '/') : '';
            }
            $this->namespace = trim($this->namespace,'/');
        }

        if(!empty(RouteBuilder::getContext('middleware')['route']))
        {
            $middlewares = RouteBuilder::getContext('middleware')['route'];
            foreach($middlewares as $middleware)
            {
                if(!in_array($middleware, $this->middleware))
                {
                    $this->middleware[] = $middleware;
                }
            }
        }

        // Optional route attributes
        if($attributes !== NULL)
        {
            if(isset($attributes['namespace']))
            {
                $this->namespace = (!empty($this->namespace) ? '/' : '' ) . trim($attributes['namespace'], '/');
            }

            if(isset($attributes['prefix']))
            {
                $this->prefix .= (!empty($this->prefix) ? '/' : '' ) . trim($attributes['prefix'], '/');
            }

            if(isset($attributes['middleware']))
            {
                if(is_string($attributes['middleware']))
                {
                    $attributes['middleware'] = [ $attributes['middleware'] ];
                }

                $this->middleware = array_merge($this->middleware, array_unique($attributes['middleware']));
            }
        }

        // Parsing route parameters
        $_names   = [];
        $fullPath = trim($this->prefix,'/') != '' ? $this->prefix . '/' . $this->path : $this->path;
        $fullPath = trim($fullPath, '/') == '' ? '/' : trim($fullPath, '/');

        $this->fullPath = $fullPath;

        foreach(explode('/', $fullPath) as $i => $segment)
        {
            if(preg_match_all('/\{(.*?)\}+/', $segment, $matches))
            {
                if($this->paramOffset === null)
                {
                    $this->paramOffset = $i;
                }

                $params = [];

                foreach ($matches[0] as $paramCode) {
                    $params[] = new RouteParam($paramCode, $i, $segment);
                }

                foreach ($params as $key => $param) {
                    if(in_array($param->getName(), $_names))
                    {
                        show_error('Duplicate route parameter <strong>' . $param->getName() . '</strong> in route <strong>"' .  $this->path . '</strong>"');
                    }

                    $_names[] = $param->getName();

                    if( $param->isOptional() )
                    {
                        $this->hasOptionalParams = true;
                        if($this->optionalParamOffset === null)
                        {
                            $this->optionalParamOffset = $i;
                        }
                    }
                    else
                    {
                        if( $this->hasOptionalParams )
                        {
                            show_error('Required <strong>' . $param->getName() . '</strong> route parameter is not allowed at this position in <strong>"' . $this->path . '"</strong> route');
                        }
                    }
                    $this->params[] = $param;
                }
            }
        }
        
        // Automatically set the default controller if the path is "/"
        if($fullPath == '/' && in_array('GET', $this->methods))
        {
            RouteBuilder::$compiled['reserved']['default_controller'] = is_string($action)
                ? ( empty($this->namespace) ? str_ireplace('@', '/', $action) : RouteBuilder::DEFAULT_CONTROLLER )
                :  RouteBuilder::DEFAULT_CONTROLLER;
        }

        $this->isCli = is_cli();
    }

    /**
     * Compiles route to a CodeIgniter native route
     *
     * @return array
     */
    public function compile()
    {
        $routes = [];

        foreach($this->methods as $method)
        {
            $path = $this->fullPath;

            foreach($this->params as $param)
            {
                $path = str_ireplace($param->getSegment(),  $param->getPlaceholder(), $path);
            }

            $pCount = 0;

            if(is_callable($this->action))
            {
                $target = RouteBuilder::DEFAULT_CONTROLLER;
                $baseTarget = $target;
            }
            else
            {
                $baseTarget = ( !empty($this->namespace) ? $this->namespace . '/' : '' )
                    . str_ireplace('@','/', $this->action);

                $target = $baseTarget;

                foreach($this->params as $c => $param)
                {
                    $target .= '/$' . ($c + 1);
                    if(!$param->isOptional())
                    {
                        $baseTarget .= '/$'. ($c + 1);
                        $pCount++;
                    }
                }
            }

            // Fallback routes
            if($this->optionalParamOffset !== null)
            {
                $segments = explode('/', $path);
                $sCount   = count($segments);
                $basePath = implode('/', array_slice($segments, 0, $this->optionalParamOffset));
                $routes[][$basePath][$method] = $baseTarget;

                for($i = $this->optionalParamOffset; $i < $sCount; $i++)
                {
                    $basePath .= '/' . $segments[$i];
                    if(is_string($this->action))
                    {
                        $baseTarget .= '/$' . ++$pCount;
                    }
                    $routes[][$basePath][$method] = $baseTarget;
                }
            }
            
            // Main route
            $routes[][$path][$method] = $target;
        }

        return $routes;
    }

    /**
     * Gets or sets a route parameter
     * 
     * @param  string  $name  Parameter name
     * @param  string  $value Parameter value
     * 
     * @return mixed
     */
    public function param($name, $value = null)
    {
        foreach($this->params as &$_param)
        {
            if($name == $_param->getName())
            {
                if($value !== null)
                {
                    $_param->value = $value;
                }
                return $_param->value;
            }
        }
    }

    /**
     * Checks if the route has a specific parameter
     *
     * @param  string  $name Parameter name
     *
     * @return bool
     */
    public function hasParam($name)
    {
        foreach($this->params as &$_param)
        {
            if($name == $_param->getName())
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Builds the route absolute url
     *
     * @param  mixed $params Route parameters
     *
     * @return string
     */
    public function buildUrl($params)
    {
        $defaults = RouteBuilder::getDefaultParams();

        // Thanks to @Ihabafia for the suggest!
        if(is_object($params)){
            $params = (array) $params;
        }
        
        if(!is_array($params))
        {
            if(!empty($params) && count($this->params) == 1)
            {
                $params = [ $this->params[0]->getName() => $params ];
            }
            else
            {
                $params = [];
            }
        }

        $path = $this->getPrefix() . '/' . $this->getPath();
        $skippedOptional = null;
        
        foreach($this->params as &$param)
        {
            $name = $param->getName();
            $isMissingRequiredField = !$param->isOptional() && !isset($defaults[$name]) && !isset($params[$param->getName()]);
            $alreadySkippedOptionalField = $param->isOptional() && $skippedOptional !== null && (isset($defaults[$name]) || isset($params[$name]));
            
            if( $isMissingRequiredField || $alreadySkippedOptionalField )
            {
                throw new \Exception('Missing "' . ($skippedOptional === null ? $name : $skippedOptional) . '" parameter for "' . $this->getName() . '" route');
            }
                        
            if(isset($defaults[$name]))
            {
                $param->value = $defaults[$param->getName()];
            }

            if(isset($params[$param->getName()]))
            {
                $param->value = $params[$param->getName()];
            }

            if(isset($defaults[$name]) || isset($params[$param->getName()]))
            {
                $path = str_replace($param->getSegment(), $param->value, $path);
            }
            else
            {
                $skippedOptional = $skippedOptional === null 
                    ? $param->getName() 
                    : $skippedOptional;
                
                $_path = explode('/', $path);
                unset($_path[array_search($param->getSegment(), $_path)]);
                $path  = implode('/', $_path);
            }
        }

        return base_url() . trim($path,'/');
    }

    /**
     * Sets route name
     *
     * @param  string $name route name
     *
     * @return self
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets route name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets route name (alias of Route::name())
     *
     * @param  string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets route path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Gets route full path
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * Sets route path
     *
     * @param  string  $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Gets route prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Gets route action
     *
     * @return string|callable
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets route action
     *
     * @param  string|callable  $action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get route middleware
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Gets route namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Gets route accepted HTTP Verbs
     *
     * @return mixed
     */
    public function getMethods()
    {
        return $this->methods;
    }
}