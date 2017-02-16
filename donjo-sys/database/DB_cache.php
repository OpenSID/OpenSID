<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_Cache {
	var $CI;
	var $db;	
	function __construct(&$db)
	{
		
		
		$this->CI =& get_instance();
		$this->db =& $db;
		$this->CI->load->helper('file');
	}
	function check_path($path = '')
	{
		if ($path == '')
		{
			if ($this->db->cachedir == '')
			{
				return $this->db->cache_off();
			}
			$path = $this->db->cachedir;
		}
		
		$path = preg_replace("/(.+?)\
	function read($sql)
	{
		if ( ! $this->check_path())
		{
			return $this->db->cache_off();
		}
		$segment_one = ($this->CI->uri->segment(1) == FALSE) ? 'default' : $this->CI->uri->segment(1);
		$segment_two = ($this->CI->uri->segment(2) == FALSE) ? 'index' : $this->CI->uri->segment(2);
		$filepath = $this->db->cachedir.$segment_one.'+'.$segment_two.'/'.md5($sql);
		if (FALSE === ($cachedata = read_file($filepath)))
		{
			return FALSE;
		}
		return unserialize($cachedata);
	}
	function write($sql, $object)
	{
		if ( ! $this->check_path())
		{
			return $this->db->cache_off();
		}
		$segment_one = ($this->CI->uri->segment(1) == FALSE) ? 'default' : $this->CI->uri->segment(1);
		$segment_two = ($this->CI->uri->segment(2) == FALSE) ? 'index' : $this->CI->uri->segment(2);
		$dir_path = $this->db->cachedir.$segment_one.'+'.$segment_two.'/';
		$filename = md5($sql);
		if ( ! @is_dir($dir_path))
		{
			if ( ! @mkdir($dir_path, DIR_WRITE_MODE))
			{
				return FALSE;
			}
			@chmod($dir_path, DIR_WRITE_MODE);
		}
		if (write_file($dir_path.$filename, serialize($object)) === FALSE)
		{
			return FALSE;
		}
		@chmod($dir_path.$filename, FILE_WRITE_MODE);
		return TRUE;
	}
	function delete($segment_one = '', $segment_two = '')
	{
		if ($segment_one == '')
		{
			$segment_one  = ($this->CI->uri->segment(1) == FALSE) ? 'default' : $this->CI->uri->segment(1);
		}
		if ($segment_two == '')
		{
			$segment_two = ($this->CI->uri->segment(2) == FALSE) ? 'index' : $this->CI->uri->segment(2);
		}
		$dir_path = $this->db->cachedir.$segment_one.'+'.$segment_two.'/';
		delete_files($dir_path, TRUE);
	}
	function delete_all()
	{
		delete_files($this->db->cachedir, TRUE);
	}
}