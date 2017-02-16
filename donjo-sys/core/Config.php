<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Config {
	var $config = array();
	var $is_loaded = array();
	var $_config_paths = array(APPPATH);
	function __construct()
	{
		$this->config =& get_config();
		log_message('debug', "Config Class Initialized");
		
		if ($this->config['base_url'] == '')
		{
			if (isset($_SERVER['HTTP_HOST']))
			{
				$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
				$base_url .= '://'. $_SERVER['HTTP_HOST'];
				$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
			}
			else
			{
				$base_url = 'http://localhost/';
			}
			$this->set_item('base_url', $base_url);
		}
	}
	function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$file = ($file == '') ? 'config' : str_replace('.php', '', $file);
		$found = FALSE;
		$loaded = FALSE;
		foreach ($this->_config_paths as $path)
		{
			$check_locations = defined('ENVIRONMENT')
				? array(ENVIRONMENT.'/'.$file, $file)
				: array($file);
			foreach ($check_locations as $location)
			{
				$file_path = $path.'config/'.$location.'.php';
				if (in_array($file_path, $this->is_loaded, TRUE))
				{
					$loaded = TRUE;
					continue 2;
				}
				if (file_exists($file_path))
				{
					$found = TRUE;
					break;
				}
			}
			if ($found === FALSE)
			{
				continue;
			}
			include($file_path);
			if ( ! isset($config) OR ! is_array($config))
			{
				if ($fail_gracefully === TRUE)
				{
					return FALSE;
				}
				show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
			}
			if ($use_sections === TRUE)
			{
				if (isset($this->config[$file]))
				{
					$this->config[$file] = array_merge($this->config[$file], $config);
				}
				else
				{
					$this->config[$file] = $config;
				}
			}
			else
			{
				$this->config = array_merge($this->config, $config);
			}
			$this->is_loaded[] = $file_path;
			unset($config);
			$loaded = TRUE;
			log_message('debug', 'Config file loaded: '.$file_path);
			break;
		}
		if ($loaded === FALSE)
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('The configuration file '.$file.'.php'.' does not exist.');
		}
		return TRUE;
	}
	function item($item, $index = '')
	{
		if ($index == '')
		{
			if ( ! isset($this->config[$item]))
			{
				return FALSE;
			}
			$pref = $this->config[$item];
		}
		else
		{
			if ( ! isset($this->config[$index]))
			{
				return FALSE;
			}
			if ( ! isset($this->config[$index][$item]))
			{
				return FALSE;
			}
			$pref = $this->config[$index][$item];
		}
		return $pref;
	}
	function slash_item($item)
	{
		if ( ! isset($this->config[$item]))
		{
			return FALSE;
		}
		if( trim($this->config[$item]) == '')
		{
			return '';
		}
		return rtrim($this->config[$item], '/').'/';
	}
	function site_url($uri = '')
	{
		if ($uri == '')
		{
			return $this->slash_item('base_url').$this->item('index_page');
		}
		if ($this->item('enable_query_strings') == FALSE)
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').$this->slash_item('index_page').$this->_uri_string($uri).$suffix;
		}
		else
		{
			return $this->slash_item('base_url').$this->item('index_page').'?'.$this->_uri_string($uri);
		}
	}
	function base_url($uri = '')
	{
		return $this->slash_item('base_url').ltrim($this->_uri_string($uri),'/');
	}
	protected function _uri_string($uri)
	{
		if ($this->item('enable_query_strings') == FALSE)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}
			$uri = trim($uri, '/');
		}
		else
		{
			if (is_array($uri))
			{
				$i = 0;
				$str = '';
				foreach ($uri as $key => $val)
				{
					$prefix = ($i == 0) ? '' : '&';
					$str .= $prefix.$key.'='.$val;
					$i++;
				}
				$uri = $str;
			}
		}
	    return $uri;
	}
	function system_url()
	{
		$x = explode("/", preg_replace("|/*(.+?)/*$|", "\\1", BASEPATH));
		return $this->slash_item('base_url').end($x).'/';
	}
	function set_item($item, $value)
	{
		$this->config[$item] = $value;
	}
	function _assign_to_config($items = array())
	{
		if (is_array($items))
		{
			foreach ($items as $key => $val)
			{
				$this->set_item($key, $val);
			}
		}
	}
}