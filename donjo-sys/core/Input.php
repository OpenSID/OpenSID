<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Input {
	var $ip_address				= FALSE;
	var $user_agent				= FALSE;
	var $_allow_get_array		= TRUE;
	var $_standardize_newlines	= TRUE;
	var $_enable_xss			= FALSE;
	var $_enable_csrf			= FALSE;
	protected $headers			= array();
	public function __construct()
	{
		log_message('debug', "Input Class Initialized");
		$this->_allow_get_array	= (config_item('allow_get_array') === TRUE);
		$this->_enable_xss		= (config_item('global_xss_filtering') === TRUE);
		$this->_enable_csrf		= (config_item('csrf_protection') === TRUE);
		global $SEC;
		$this->security =& $SEC;
		
		if (UTF8_ENABLED === TRUE)
		{
			global $UNI;
			$this->uni =& $UNI;
		}
		
		$this->_sanitize_globals();
	}
	function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE)
	{
		if ( ! isset($array[$index]))
		{
			return FALSE;
		}
		if ($xss_clean === TRUE)
		{
			return $this->security->xss_clean($array[$index]);
		}
		return $array[$index];
	}
	function get($index = NULL, $xss_clean = FALSE)
	{
		
		if ($index === NULL AND ! empty($_GET))
		{
			$get = array();
			
			foreach (array_keys($_GET) as $key)
			{
				$get[$key] = $this->_fetch_from_array($_GET, $key, $xss_clean);
			}
			return $get;
		}
		return $this->_fetch_from_array($_GET, $index, $xss_clean);
	}
	function post($index = NULL, $xss_clean = FALSE)
	{
		
		if ($index === NULL AND ! empty($_POST))
		{
			$post = array();
			
			foreach (array_keys($_POST) as $key)
			{
				$post[$key] = $this->_fetch_from_array($_POST, $key, $xss_clean);
			}
			return $post;
		}
		return $this->_fetch_from_array($_POST, $index, $xss_clean);
	}
	function get_post($index = '', $xss_clean = FALSE)
	{
		if ( ! isset($_POST[$index]) )
		{
			return $this->get($index, $xss_clean);
		}
		else
		{
			return $this->post($index, $xss_clean);
		}
	}
	function cookie($index = '', $xss_clean = FALSE)
	{
		return $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
	}
	function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE)
	{
		if (is_array($name))
		{
			
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'name') as $item)
			{
				if (isset($name[$item]))
				{
					$$item = $name[$item];
				}
			}
		}
		if ($prefix == '' AND config_item('cookie_prefix') != '')
		{
			$prefix = config_item('cookie_prefix');
		}
		if ($domain == '' AND config_item('cookie_domain') != '')
		{
			$domain = config_item('cookie_domain');
		}
		if ($path == '/' AND config_item('cookie_path') != '/')
		{
			$path = config_item('cookie_path');
		}
		if ($secure == FALSE AND config_item('cookie_secure') != FALSE)
		{
			$secure = config_item('cookie_secure');
		}
		if ( ! is_numeric($expire))
		{
			$expire = time() - 86500;
		}
		else
		{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}
		setcookie($prefix.$name, $value, $expire, $path, $domain, $secure);
	}
	function server($index = '', $xss_clean = FALSE)
	{
		return $this->_fetch_from_array($_SERVER, $index, $xss_clean);
	}
	public function ip_address()
	{
		if ($this->ip_address !== FALSE)
		{
			return $this->ip_address;
		}
		$proxy_ips = config_item('proxy_ips');
		if ( ! empty($proxy_ips))
		{
			$proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
			foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header)
			{
				if (($spoof = $this->server($header)) !== FALSE)
				{
					
					
					
					if (strpos($spoof, ',') !== FALSE)
					{
						$spoof = explode(',', $spoof, 2);
						$spoof = $spoof[0];
					}
					if ( ! $this->valid_ip($spoof))
					{
						$spoof = FALSE;
					}
					else
					{
						break;
					}
				}
			}
			$this->ip_address = ($spoof !== FALSE && in_array($_SERVER['REMOTE_ADDR'], $proxy_ips, TRUE))
				? $spoof : $_SERVER['REMOTE_ADDR'];
		}
		else
		{
			$this->ip_address = $_SERVER['REMOTE_ADDR'];
		}
		if ( ! $this->valid_ip($this->ip_address))
		{
			$this->ip_address = '0.0.0.0';
		}
		return $this->ip_address;
	}
	public function valid_ip($ip, $which = '')
	{
		$which = strtolower($which);
		
		if (is_callable('filter_var'))
		{
			switch ($which) {
				case 'ipv4':
					$flag = FILTER_FLAG_IPV4;
					break;
				case 'ipv6':
					$flag = FILTER_FLAG_IPV6;
					break;
				default:
					$flag = '';
					break;
			}
			return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
		}
		if ($which !== 'ipv6' && $which !== 'ipv4')
		{
			if (strpos($ip, ':') !== FALSE)
			{
				$which = 'ipv6';
			}
			elseif (strpos($ip, '.') !== FALSE)
			{
				$which = 'ipv4';
			}
			else
			{
				return FALSE;
			}
		}
		$func = '_valid_'.$which;
		return $this->$func($ip);
	}
	protected function _valid_ipv4($ip)
	{
		$ip_segments = explode('.', $ip);
		
		if (count($ip_segments) !== 4)
		{
			return FALSE;
		}
		
		if ($ip_segments[0][0] == '0')
		{
			return FALSE;
		}
		
		foreach ($ip_segments as $segment)
		{
			
			
			if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
			{
				return FALSE;
			}
		}
		return TRUE;
	}
	protected function _valid_ipv6($str)
	{
		
		
		
		$groups = 8;
		$collapsed = FALSE;
		$chunks = array_filter(
			preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
		);
		
		if (current($chunks) == ':' OR end($chunks) == ':')
		{
			return FALSE;
		}
		
		if (strpos(end($chunks), '.') !== FALSE)
		{
			$ipv4 = array_pop($chunks);
			if ( ! $this->_valid_ipv4($ipv4))
			{
				return FALSE;
			}
			$groups--;
		}
		while ($seg = array_pop($chunks))
		{
			if ($seg[0] == ':')
			{
				if (--$groups == 0)
				{
					return FALSE;	
				}
				if (strlen($seg) > 2)
				{
					return FALSE;	
				}
				if ($seg == '::')
				{
					if ($collapsed)
					{
						return FALSE;	
					}
					$collapsed = TRUE;
				}
			}
			elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4)
			{
				return FALSE; 
			}
		}
		return $collapsed OR $groups == 1;
	}
	function user_agent()
	{
		if ($this->user_agent !== FALSE)
		{
			return $this->user_agent;
		}
		$this->user_agent = ( ! isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];
		return $this->user_agent;
	}
	function _sanitize_globals()
	{
		
		$protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST',
							'_SESSION', '_ENV', 'GLOBALS', 'HTTP_RAW_POST_DATA',
							'system_folder', 'application_folder', 'BM', 'EXT',
							'CFG', 'URI', 'RTR', 'OUT', 'IN');
		
		
		foreach (array($_GET, $_POST, $_COOKIE) as $global)
		{
			if ( ! is_array($global))
			{
				if ( ! in_array($global, $protected))
				{
					global $$global;
					$$global = NULL;
				}
			}
			else
			{
				foreach ($global as $key => $val)
				{
					if ( ! in_array($key, $protected))
					{
						global $$key;
						$$key = NULL;
					}
				}
			}
		}
		
		if ($this->_allow_get_array == FALSE)
		{
			$_GET = array();
		}
		else
		{
			if (is_array($_GET) AND count($_GET) > 0)
			{
				foreach ($_GET as $key => $val)
				{
					$_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
				}
			}
		}
		
		if (is_array($_POST) AND count($_POST) > 0)
		{
			foreach ($_POST as $key => $val)
			{
				$_POST[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
		}
		
		if (is_array($_COOKIE) AND count($_COOKIE) > 0)
		{
			
			
			
			// http://www.ietf.org/rfc/rfc2109.txt
			
			unset($_COOKIE['$Version']);
			unset($_COOKIE['$Path']);
			unset($_COOKIE['$Domain']);
			// Work-around for PHP bug #66827 (https://bugs.php.net/bug.php?id=66827)
			
			
			
			$sess_cookie_name = config_item('cookie_prefix').config_item('sess_cookie_name');
			if (isset($_COOKIE[$sess_cookie_name]) && ! is_string($_COOKIE[$sess_cookie_name]))
			{
				unset($_COOKIE[$sess_cookie_name]);
			}
			foreach ($_COOKIE as $key => $val)
			{
				
				if ($key === $sess_cookie_name && config_item('sess_encrypt_cookie'))
				{
					continue;
				}
				$_COOKIE[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
		}
		
		$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);
		
		if ($this->_enable_csrf == TRUE && ! $this->is_cli_request())
		{
			$this->security->csrf_verify();
		}
		log_message('debug', "Global POST and COOKIE data sanitized");
	}
	function _clean_input_data($str)
	{
		if (is_array($str))
		{
			$new_array = array();
			foreach ($str as $key => $val)
			{
				$new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
			return $new_array;
		}
		
		if ( ! is_php('5.4') && get_magic_quotes_gpc())
		{
			$str = stripslashes($str);
		}
		
		if (UTF8_ENABLED === TRUE)
		{
			$str = $this->uni->clean_string($str);
		}
		
		$str = remove_invisible_characters($str);
		
		if ($this->_enable_xss === TRUE)
		{
			$str = $this->security->xss_clean($str);
		}
		
		if ($this->_standardize_newlines == TRUE)
		{
			if (strpos($str, "\r") !== FALSE)
			{
				$str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
			}
		}
		return $str;
	}
	function _clean_input_keys($str)
	{
		if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str))
		{
			exit('Disallowed Key Characters.');
		}
		
		if (UTF8_ENABLED === TRUE)
		{
			$str = $this->uni->clean_string($str);
		}
		return $str;
	}
	public function request_headers($xss_clean = FALSE)
	{
		
		if (function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}
		else
		{
			$headers['Content-Type'] = (isset($_SERVER['CONTENT_TYPE'])) ? $_SERVER['CONTENT_TYPE'] : @getenv('CONTENT_TYPE');
			foreach ($_SERVER as $key => $val)
			{
				if (strncmp($key, 'HTTP_', 5) === 0)
				{
					$headers[substr($key, 5)] = $this->_fetch_from_array($_SERVER, $key, $xss_clean);
				}
			}
		}
		
		foreach ($headers as $key => $val)
		{
			$key = str_replace('_', ' ', strtolower($key));
			$key = str_replace(' ', '-', ucwords($key));
			$this->headers[$key] = $val;
		}
		return $this->headers;
	}
	public function get_request_header($index, $xss_clean = FALSE)
	{
		if (empty($this->headers))
		{
			$this->request_headers();
		}
		if ( ! isset($this->headers[$index]))
		{
			return FALSE;
		}
		if ($xss_clean === TRUE)
		{
			return $this->security->xss_clean($this->headers[$index]);
		}
		return $this->headers[$index];
	}
	public function is_ajax_request()
	{
		return ($this->server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
	}
	public function is_cli_request()
	{
		return (php_sapi_name() === 'cli' OR defined('STDIN'));
	}
}