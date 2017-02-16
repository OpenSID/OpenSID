<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Router {
	var $config;
	var $routes			= array();
	var $error_routes	= array();
	var $class			= '';
	var $method			= 'index';
	var $directory		= '';
	var $default_controller;
	function __construct()
	{
		$this->config =& load_class('Config', 'core');
		$this->uri =& load_class('URI', 'core');
		log_message('debug', "Router Class Initialized");
	}
	function _set_routing()
	{
		
		
		
		$segments = array();
		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			if (isset($_GET[$this->config->item('directory_trigger')]))
			{
				$this->set_directory(trim($this->uri->_filter_uri($_GET[$this->config->item('directory_trigger')])));
				$segments[] = $this->fetch_directory();
			}
			if (isset($_GET[$this->config->item('controller_trigger')]))
			{
				$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));
				$segments[] = $this->fetch_class();
			}
			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
				$segments[] = $this->fetch_method();
			}
		}
		
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}
		elseif (is_file(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');
		}
		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);
		
		
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);
		
		if (count($segments) > 0)
		{
			return $this->_validate_request($segments);
		}
		
		$this->uri->_fetch_uri_string();
		
		if ($this->uri->uri_string == '')
		{
			return $this->_set_default_controller();
		}
		
		$this->uri->_remove_url_suffix();
		
		$this->uri->_explode_segments();
		
		$this->_parse_routes();
		
		$this->uri->_reindex_segments();
	}
	function _set_default_controller()
	{
		if ($this->default_controller === FALSE)
		{
			show_error("Unable to determine what should be displayed. A default route has not been specified in the routing file.");
		}
		
		if (strpos($this->default_controller, '/') !== FALSE)
		{
			$x = explode('/', $this->default_controller);
			$this->set_class($x[0]);
			$this->set_method($x[1]);
			$this->_set_request($x);
		}
		else
		{
			$this->set_class($this->default_controller);
			$this->set_method('index');
			$this->_set_request(array($this->default_controller, 'index'));
		}
		
		$this->uri->_reindex_segments();
		log_message('debug', "No URI present. Default controller set.");
	}
	function _set_request($segments = array())
	{
		$segments = $this->_validate_request($segments);
		if (count($segments) == 0)
		{
			return $this->_set_default_controller();
		}
		$this->set_class($segments[0]);
		if (isset($segments[1]))
		{
			
			$this->set_method($segments[1]);
		}
		else
		{
			
			
			$segments[1] = 'index';
		}
		
		
		
		$this->uri->rsegments = $segments;
	}
	function _validate_request($segments)
	{
		if (count($segments) == 0)
		{
			return $segments;
		}
		
		if (file_exists(APPPATH.'controllers/'.$segments[0].'.php'))
		{
			return $segments;
		}
		
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);
			if (count($segments) > 0)
			{
				
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].'.php'))
				{
					if ( ! empty($this->routes['404_override']))
					{
						$x = explode('/', $this->routes['404_override']);
						$this->set_directory('');
						$this->set_class($x[0]);
						$this->set_method(isset($x[1]) ? $x[1] : 'index');
						return $x;
					}
					else
					{
						show_404($this->fetch_directory().$segments[0]);
					}
				}
			}
			else
			{
				
				if (strpos($this->default_controller, '/') !== FALSE)
				{
					$x = explode('/', $this->default_controller);
					$this->set_class($x[0]);
					$this->set_method($x[1]);
				}
				else
				{
					$this->set_class($this->default_controller);
					$this->set_method('index');
				}
				
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.'.php'))
				{
					$this->directory = '';
					return array();
				}
			}
			return $segments;
		}
		
		
		if ( ! empty($this->routes['404_override']))
		{
			$x = explode('/', $this->routes['404_override']);
			$this->set_class($x[0]);
			$this->set_method(isset($x[1]) ? $x[1] : 'index');
			return $x;
		}
		
		show_404($segments[0]);
	}
	function _parse_routes()
	{
		
		$uri = implode('/', $this->uri->segments);
		
		if (isset($this->routes[$uri]))
		{
			return $this->_set_request(explode('/', $this->routes[$uri]));
		}
		
		foreach ($this->routes as $key => $val)
		{
			
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			
			if (preg_match('#^'.$key.'$#', $uri))
			{
				
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}
				return $this->_set_request(explode('/', $val));
			}
		}
		
		
		$this->_set_request($this->uri->segments);
	}
	function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}
	function fetch_class()
	{
		return $this->class;
	}
	function set_method($method)
	{
		$this->method = $method;
	}
	function fetch_method()
	{
		if ($this->method == $this->fetch_class())
		{
			return 'index';
		}
		return $this->method;
	}
	function set_directory($dir)
	{
		$this->directory = str_replace(array('/', '.'), '', $dir).'/';
	}
	function fetch_directory()
	{
		return $this->directory;
	}
	function _set_overrides($routing)
	{
		if ( ! is_array($routing))
		{
			return;
		}
		if (isset($routing['directory']))
		{
			$this->set_directory($routing['directory']);
		}
		if (isset($routing['controller']) AND $routing['controller'] != '')
		{
			$this->set_class($routing['controller']);
		}
		if (isset($routing['function']))
		{
			$routing['function'] = ($routing['function'] == '') ? 'index' : $routing['function'];
			$this->set_method($routing['function']);
		}
	}
}