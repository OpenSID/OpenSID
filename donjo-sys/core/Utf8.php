<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Utf8 {
	function __construct()
	{
		log_message('debug', "Utf8 Class Initialized");
		global $CFG;
		if (
			preg_match('/./u', 'é') === 1					// PCRE must support UTF-8
			AND function_exists('iconv')					// iconv must be installed
			AND ini_get('mbstring.func_overload') != 1		// Multibyte string function overloading cannot be enabled
			AND $CFG->item('charset') == 'UTF-8'			// Application charset must be UTF-8
			)
		{
			log_message('debug', "UTF-8 Support Enabled");
			define('UTF8_ENABLED', TRUE);
			
			
			
			if (extension_loaded('mbstring'))
			{
				define('MB_ENABLED', TRUE);
				mb_internal_encoding('UTF-8');
			}
			else
			{
				define('MB_ENABLED', FALSE);
			}
		}
		else
		{
			log_message('debug', "UTF-8 Support Disabled");
			define('UTF8_ENABLED', FALSE);
		}
	}
	function clean_string($str)
	{
		if ($this->_is_ascii($str) === FALSE)
		{
			$str = @iconv('UTF-8', 'UTF-8//IGNORE', $str);
		}
		return $str;
	}
	function safe_ascii_for_xml($str)
	{
		return remove_invisible_characters($str, FALSE);
	}
	function convert_to_utf8($str, $encoding)
	{
		if (function_exists('iconv'))
		{
			$str = @iconv($encoding, 'UTF-8', $str);
		}
		elseif (function_exists('mb_convert_encoding'))
		{
			$str = @mb_convert_encoding($str, 'UTF-8', $encoding);
		}
		else
		{
			return FALSE;
		}
		return $str;
	}
	function _is_ascii($str)
	{
		return (preg_match('/[^\x00-\x7F]/S', $str) == 0);
	}
}