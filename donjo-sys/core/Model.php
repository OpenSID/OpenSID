<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Model {
	function __construct()
	{
		log_message('debug', "Model Class Initialized");
	}
	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
}