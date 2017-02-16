<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Hooks {
	var $enabled		= FALSE;
	var $hooks			= array();
	var $in_progress	= FALSE;
	function __construct()
	{
		$this->_initialize();
		log_message('debug', "Hooks Class Initialized");
	}
	function _initialize()
	{
		$CFG =& load_class('Config', 'core');
		
		
		if ($CFG->item('enable_hooks') == FALSE)
		{
			return;
		}
		
		
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/hooks.php'))
		{
		    include(APPPATH.'config/'.ENVIRONMENT.'/hooks.php');
		}
		elseif (is_file(APPPATH.'config/hooks.php'))
		{
			include(APPPATH.'config/hooks.php');
		}
		if ( ! isset($hook) OR ! is_array($hook))
		{
			return;
		}
		$this->hooks =& $hook;
		$this->enabled = TRUE;
	}
	function _call_hook($which = '')
	{
		if ( ! $this->enabled OR ! isset($this->hooks[$which]))
		{
			return FALSE;
		}
		if (isset($this->hooks[$which][0]) AND is_array($this->hooks[$which][0]))
		{
			foreach ($this->hooks[$which] as $val)
			{
				$this->_run_hook($val);
			}
		}
		else
		{
			$this->_run_hook($this->hooks[$which]);
		}
		return TRUE;
	}
	function _run_hook($data)
	{
		if ( ! is_array($data))
		{
			return FALSE;
		}
		
		
		
		
		
		if ($this->in_progress == TRUE)
		{
			return;
		}
		
		
		
		if ( ! isset($data['filepath']) OR ! isset($data['filename']))
		{
			return FALSE;
		}
		$filepath = APPPATH.$data['filepath'].'/'.$data['filename'];
		if ( ! file_exists($filepath))
		{
			return FALSE;
		}
		
		
		
		$class		= FALSE;
		$function	= FALSE;
		$params		= '';
		if (isset($data['class']) AND $data['class'] != '')
		{
			$class = $data['class'];
		}
		if (isset($data['function']))
		{
			$function = $data['function'];
		}
		if (isset($data['params']))
		{
			$params = $data['params'];
		}
		if ($class === FALSE AND $function === FALSE)
		{
			return FALSE;
		}
		
		
		
		$this->in_progress = TRUE;
		
		
		
		if ($class !== FALSE)
		{
			if ( ! class_exists($class))
			{
				require($filepath);
			}
			$HOOK = new $class;
			$HOOK->$function($params);
		}
		else
		{
			if ( ! function_exists($function))
			{
				require($filepath);
			}
			$function($params);
		}
		$this->in_progress = FALSE;
		return TRUE;
	}
}