<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_URI {
	var	$keyval			= array();
	var $uri_string;
	var $segments		= array();
	var $rsegments		= array();
	function __construct()
	{
		$this->config =& load_class('Config', 'core');
		log_message('debug', "URI Class Initialized");
	}
	function _fetch_uri_string()
	{
		if (strtoupper($this->config->item('uri_protocol')) == 'AUTO')
		{
			
			if (php_sapi_name() == 'cli' or defined('STDIN'))
			{
				$this->_set_uri_string($this->_parse_cli_args());
				return;
			}
			
			if ($uri = $this->_detect_uri())
			{
				$this->_set_uri_string($uri);
				return;
			}
			
			
			$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				$this->_set_uri_string($path);
				return;
			}
			
			$path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
			if (trim($path, '/') != '')
			{
				$this->_set_uri_string($path);
				return;
			}
			
			if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
			{
				$this->_set_uri_string(key($_GET));
				return;
			}
			
			$this->uri_string = '';
			return;
		}
		$uri = strtoupper($this->config->item('uri_protocol'));
		if ($uri == 'REQUEST_URI')
		{
			$this->_set_uri_string($this->_detect_uri());
			return;
		}
		elseif ($uri == 'CLI')
		{
			$this->_set_uri_string($this->_parse_cli_args());
			return;
		}
		$path = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
		$this->_set_uri_string($path);
	}
	function _set_uri_string($str)
	{
		
		$str = remove_invisible_characters($str, FALSE);
		
		$this->uri_string = ($str == '/') ? '' : $str;
	}
	private function _detect_uri()
	{
		if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))
		{
			return '';
		}
		$uri = $_SERVER['REQUEST_URI'];
		if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
		{
			$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		}
		elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
		{
			$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
		}
		
		
		if (strncmp($uri, '?/', 2) === 0)
		{
			$uri = substr($uri, 2);
		}
		$parts = preg_split('#\?#i', $uri, 2);
		$uri = $parts[0];
		if (isset($parts[1]))
		{
			$_SERVER['QUERY_STRING'] = $parts[1];
			parse_str($_SERVER['QUERY_STRING'], $_GET);
		}
		else
		{
			$_SERVER['QUERY_STRING'] = '';
			$_GET = array();
		}
		if ($uri == '/' || empty($uri))
		{
			return '/';
		}
		$uri = parse_url($uri, PHP_URL_PATH);
		
		return str_replace(array('//', '../'), '/', trim($uri, '/'));
	}
	private function _parse_cli_args()
	{
		$args = array_slice($_SERVER['argv'], 1);
		return $args ? '/' . implode('/', $args) : '';
	}
	function _filter_uri($str)
	{
		if ($str != '' && $this->config->item('permitted_uri_chars') != '' && $this->config->item('enable_query_strings') == FALSE)
		{
			
			
			if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $str))
			{
				show_error('The URI you submitted has disallowed characters.', 400);
			}
		}
		
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');
		return str_replace($bad, $good, $str);
	}
	function _remove_url_suffix()
	{
		if  ($this->config->item('url_suffix') != "")
		{
			$this->uri_string = preg_replace("|".preg_quote($this->config->item('url_suffix'))."$|", "", $this->uri_string);
		}
	}
	function _explode_segments()
	{
		foreach (explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val)
		{
			$val = trim($this->_filter_uri($val));
			if ($val != '')
			{
				$this->segments[] = $val;
			}
		}
	}
	function _reindex_segments()
	{
		array_unshift($this->segments, NULL);
		array_unshift($this->rsegments, NULL);
		unset($this->segments[0]);
		unset($this->rsegments[0]);
	}
	function segment($n, $no_result = FALSE)
	{
		return ( ! isset($this->segments[$n])) ? $no_result : $this->segments[$n];
	}
	function rsegment($n, $no_result = FALSE)
	{
		return ( ! isset($this->rsegments[$n])) ? $no_result : $this->rsegments[$n];
	}
	function uri_to_assoc($n = 3, $default = array())
	{
		return $this->_uri_to_assoc($n, $default, 'segment');
	}
	function ruri_to_assoc($n = 3, $default = array())
	{
		return $this->_uri_to_assoc($n, $default, 'rsegment');
	}
	function _uri_to_assoc($n = 3, $default = array(), $which = 'segment')
	{
		if ($which == 'segment')
		{
			$total_segments = 'total_segments';
			$segment_array = 'segment_array';
		}
		else
		{
			$total_segments = 'total_rsegments';
			$segment_array = 'rsegment_array';
		}
		if ( ! is_numeric($n))
		{
			return $default;
		}
		if (isset($this->keyval[$n]))
		{
			return $this->keyval[$n];
		}
		if ($this->$total_segments() < $n)
		{
			if (count($default) == 0)
			{
				return array();
			}
			$retval = array();
			foreach ($default as $val)
			{
				$retval[$val] = FALSE;
			}
			return $retval;
		}
		$segments = array_slice($this->$segment_array(), ($n - 1));
		$i = 0;
		$lastval = '';
		$retval  = array();
		foreach ($segments as $seg)
		{
			if ($i % 2)
			{
				$retval[$lastval] = $seg;
			}
			else
			{
				$retval[$seg] = FALSE;
				$lastval = $seg;
			}
			$i++;
		}
		if (count($default) > 0)
		{
			foreach ($default as $val)
			{
				if ( ! array_key_exists($val, $retval))
				{
					$retval[$val] = FALSE;
				}
			}
		}
		
		$this->keyval[$n] = $retval;
		return $retval;
	}
	function assoc_to_uri($array)
	{
		$temp = array();
		foreach ((array)$array as $key => $val)
		{
			$temp[] = $key;
			$temp[] = $val;
		}
		return implode('/', $temp);
	}
	function slash_segment($n, $where = 'trailing')
	{
		return $this->_slash_segment($n, $where, 'segment');
	}
	function slash_rsegment($n, $where = 'trailing')
	{
		return $this->_slash_segment($n, $where, 'rsegment');
	}
	function _slash_segment($n, $where = 'trailing', $which = 'segment')
	{
		$leading	= '/';
		$trailing	= '/';
		if ($where == 'trailing')
		{
			$leading	= '';
		}
		elseif ($where == 'leading')
		{
			$trailing	= '';
		}
		return $leading.$this->$which($n).$trailing;
	}
	function segment_array()
	{
		return $this->segments;
	}
	function rsegment_array()
	{
		return $this->rsegments;
	}
	function total_segments()
	{
		return count($this->segments);
	}
	function total_rsegments()
	{
		return count($this->rsegments);
	}
	function uri_string()
	{
		return $this->uri_string;
	}
	function ruri_string()
	{
		return '/'.implode('/', $this->rsegment_array());
	}
}