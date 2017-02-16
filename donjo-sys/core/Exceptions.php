<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Exceptions {
	var $action;
	var $severity;
	var $message;
	var $filename;
	var $line;
	var $ob_level;
	var $levels = array(
						E_ERROR				=>	'Error',
						E_WARNING			=>	'Warning',
						E_PARSE				=>	'Parsing Error',
						E_NOTICE			=>	'Notice',
						E_CORE_ERROR		=>	'Core Error',
						E_CORE_WARNING		=>	'Core Warning',
						E_COMPILE_ERROR		=>	'Compile Error',
						E_COMPILE_WARNING	=>	'Compile Warning',
						E_USER_ERROR		=>	'User Error',
						E_USER_WARNING		=>	'User Warning',
						E_USER_NOTICE		=>	'User Notice',
						E_STRICT			=>	'Runtime Notice'
					);
	public function __construct()
	{
		$this->ob_level = ob_get_level();
		
	}
	function log_exception($severity, $message, $filepath, $line)
	{
		$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];
		log_message('error', 'Severity: '.$severity.'  --> '.$message. ' '.$filepath.' '.$line, TRUE);
	}
	function show_404($page = '', $log_error = TRUE)
	{
		$heading = "404 Page Not Found";
		$message = "The page you requested was not found.";
		
		if ($log_error)
		{
			log_message('error', '404 Page Not Found --> '.$page);
		}
		echo $this->show_error($heading, $message, 'error_404', 404);
		exit;
	}
	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		set_status_header($status_code);
		$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';
		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include(APPPATH.'errors/'.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	function show_php_error($severity, $message, $filepath, $line)
	{
		$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];
		$filepath = str_replace("\\", "/", $filepath);
		
		if (FALSE !== strpos($filepath, '/'))
		{
			$x = explode('/', $filepath);
			$filepath = $x[count($x)-2].'/'.end($x);
		}
		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include(APPPATH.'errors/error_php.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
}