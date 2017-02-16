<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_utility extends CI_DB_forge {
	var $db;
	var $data_cache		= array();
	function __construct()
	{
		
		$CI =& get_instance();
		$this->db =& $CI->db;
		log_message('debug', "Database Utility Class Initialized");
	}
	function list_databases()
	{
		
		if (isset($this->data_cache['db_names']))
		{
			return $this->data_cache['db_names'];
		}
		$query = $this->db->query($this->_list_databases());
		$dbs = array();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$dbs[] = current($row);
			}
		}
		$this->data_cache['db_names'] = $dbs;
		return $this->data_cache['db_names'];
	}
	function database_exists($database_name)
	{
		
		
		
		if (method_exists($this, '_database_exists'))
		{
			return $this->_database_exists($database_name);
		}
		else
		{
			return ( ! in_array($database_name, $this->list_databases())) ? FALSE : TRUE;
		}
	}
	function optimize_table($table_name)
	{
		$sql = $this->_optimize_table($table_name);
		if (is_bool($sql))
		{
				show_error('db_must_use_set');
		}
		$query = $this->db->query($sql);
		$res = $query->result_array();
		
		
		return current($res);
	}
	function optimize_database()
	{
		$result = array();
		foreach ($this->db->list_tables() as $table_name)
		{
			$sql = $this->_optimize_table($table_name);
			if (is_bool($sql))
			{
				return $sql;
			}
			$query = $this->db->query($sql);
			
			
			
			$res = $query->result_array();
			$res = current($res);
			$key = str_replace($this->db->database.'.', '', current($res));
			$keys = array_keys($res);
			unset($res[$keys[0]]);
			$result[$key] = $res;
		}
		return $result;
	}
	function repair_table($table_name)
	{
		$sql = $this->_repair_table($table_name);
		if (is_bool($sql))
		{
			return $sql;
		}
		$query = $this->db->query($sql);
		
		
		$res = $query->result_array();
		return current($res);
	}
	function csv_from_result($query, $delim = ",", $newline = "\n", $enclosure = '"')
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('You must submit a valid result object');
		}
		$out = '';
		
		foreach ($query->list_fields() as $name)
		{
			$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $name).$enclosure.$delim;
		}
		$out = rtrim($out);
		$out .= $newline;
		
		foreach ($query->result_array() as $row)
		{
			foreach ($row as $item)
			{
				$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $item).$enclosure.$delim;
			}
			$out = rtrim($out);
			$out .= $newline;
		}
		return $out;
	}
	function xml_from_result($query, $params = array())
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('You must submit a valid result object');
		}
		
		foreach (array('root' => 'root', 'element' => 'element', 'newline' => "\n", 'tab' => "\t") as $key => $val)
		{
			if ( ! isset($params[$key]))
			{
				$params[$key] = $val;
			}
		}
		
		extract($params);
		
		$CI =& get_instance();
		$CI->load->helper('xml');
		
		$xml = "<{$root}>".$newline;
		foreach ($query->result_array() as $row)
		{
			$xml .= $tab."<{$element}>".$newline;
			foreach ($row as $key => $val)
			{
				$xml .= $tab.$tab."<{$key}>".xml_convert($val)."</{$key}>".$newline;
			}
			$xml .= $tab."</{$element}>".$newline;
		}
		$xml .= "</$root>".$newline;
		return $xml;
	}
	function backup($params = array())
	{
		
		
		
		if (is_string($params))
		{
			$params = array('tables' => $params);
		}
		
		
		$prefs = array(
							'tables'		=> array(),
							'ignore'		=> array(),
							'filename'		=> '',
							'format'		=> 'gzip', 
							'add_drop'		=> TRUE,
							'add_insert'	=> TRUE,
							'newline'		=> "\n"
						);
		
		if (count($params) > 0)
		{
			foreach ($prefs as $key => $val)
			{
				if (isset($params[$key]))
				{
					$prefs[$key] = $params[$key];
				}
			}
		}
		
		
		
		if (count($prefs['tables']) == 0)
		{
			$prefs['tables'] = $this->db->list_tables();
		}
		
		
		if ( ! in_array($prefs['format'], array('gzip', 'zip', 'txt'), TRUE))
		{
			$prefs['format'] = 'txt';
		}
		
		
		
		if (($prefs['format'] == 'gzip' AND ! @function_exists('gzencode'))
		OR ($prefs['format'] == 'zip'  AND ! @function_exists('gzcompress')))
		{
			if ($this->db->db_debug)
			{
				return $this->db->display_error('db_unsuported_compression');
			}
			$prefs['format'] = 'txt';
		}
		
		
		if ($prefs['filename'] == '' AND $prefs['format'] == 'zip')
		{
			$prefs['filename'] = (count($prefs['tables']) == 1) ? $prefs['tables'] : $this->db->database;
			$prefs['filename'] .= '_'.date('Y-m-d_H-i', time());
		}
		
		
		if ($prefs['format'] == 'gzip')
		{
			return gzencode($this->_backup($prefs));
		}
		
		
		if ($prefs['format'] == 'txt')
		{
			return $this->_backup($prefs);
		}
		
		
		if ($prefs['format'] == 'zip')
		{
			
			if (preg_match("|.+?\.zip$|", $prefs['filename']))
			{
				$prefs['filename'] = str_replace('.zip', '', $prefs['filename']);
			}
			
			if ( ! preg_match("|.+?\.sql$|", $prefs['filename']))
			{
				$prefs['filename'] .= '.sql';
			}
			
			$CI =& get_instance();
			$CI->load->library('zip');
			$CI->zip->add_data($prefs['filename'], $this->_backup($prefs));
			return $CI->zip->get_zip();
		}
	}
}