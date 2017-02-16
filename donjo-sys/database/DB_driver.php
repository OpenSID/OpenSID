<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_driver {
	var $username;
	var $password;
	var $hostname;
	var $database;
	var $dbdriver		= 'mysql';
	var $dbprefix		= '';
	var $char_set		= 'utf8';
	var $dbcollat		= 'utf8_general_ci';
	var $autoinit		= TRUE; 
	var $swap_pre		= '';
	var $port			= '';
	var $pconnect		= FALSE;
	var $conn_id		= FALSE;
	var $result_id		= FALSE;
	var $db_debug		= FALSE;
	var $benchmark		= 0;
	var $query_count	= 0;
	var $bind_marker	= '?';
	var $save_queries	= TRUE;
	var $queries		= array();
	var $query_times	= array();
	var $data_cache		= array();
	var $trans_enabled	= TRUE;
	var $trans_strict	= TRUE;
	var $_trans_depth	= 0;
	var $_trans_status	= TRUE; 
	var $cache_on		= FALSE;
	var $cachedir		= '';
	var $cache_autodel	= FALSE;
	var $CACHE; 
	var $_protect_identifiers	= TRUE;
	var $_reserved_identifiers	= array('*'); // Identifiers that should NOT be escaped
	var $stmt_id;
	var $curs_id;
	var $limit_used;
	function __construct($params)
	{
		if (is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$this->$key = $val;
			}
		}
		log_message('debug', 'Database Driver Class Initialized');
	}
	function initialize()
	{
		
		
		if (is_resource($this->conn_id) OR is_object($this->conn_id))
		{
			return TRUE;
		}
		
		
		$this->conn_id = ($this->pconnect == FALSE) ? $this->db_connect() : $this->db_pconnect();
		
		if ( ! $this->conn_id)
		{
			log_message('error', 'Unable to connect to the database');
			if ($this->db_debug)
			{
				$this->display_error('db_unable_to_connect');
			}
			return FALSE;
		}
		
		
		if ($this->database != '')
		{
			if ( ! $this->db_select())
			{
				log_message('error', 'Unable to select database: '.$this->database);
				if ($this->db_debug)
				{
					$this->display_error('db_unable_to_select', $this->database);
				}
				return FALSE;
			}
			else
			{
				
				if ( ! $this->db_set_charset($this->char_set, $this->dbcollat))
				{
					return FALSE;
				}
				return TRUE;
			}
		}
		return TRUE;
	}
	function db_set_charset($charset, $collation)
	{
		if ( ! $this->_db_set_charset($this->char_set, $this->dbcollat))
		{
			log_message('error', 'Unable to set database connection charset: '.$this->char_set);
			if ($this->db_debug)
			{
				$this->display_error('db_unable_to_set_charset', $this->char_set);
			}
			return FALSE;
		}
		return TRUE;
	}
	function platform()
	{
		return $this->dbdriver;
	}
	function version()
	{
		if (FALSE === ($sql = $this->_version()))
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_unsupported_function');
			}
			return FALSE;
		}
		
		
		$driver_version_exceptions = array('oci8', 'sqlite', 'cubrid');
		if (in_array($this->dbdriver, $driver_version_exceptions))
		{
			return $sql;
		}
		else
		{
			$query = $this->query($sql);
			return $query->row('ver');
		}
	}
	function query($sql, $binds = FALSE, $return_object = TRUE)
	{
		if ($sql == '')
		{
			if ($this->db_debug)
			{
				log_message('error', 'Invalid query: '.$sql);
				return $this->display_error('db_invalid_query');
			}
			return FALSE;
		}
		
		if ( ($this->dbprefix != '' AND $this->swap_pre != '') AND ($this->dbprefix != $this->swap_pre) )
		{
			$sql = preg_replace("/(\W)".$this->swap_pre."(\S+?)/", "\\1".$this->dbprefix."\\2", $sql);
		}
		
		if ($binds !== FALSE)
		{
			$sql = $this->compile_binds($sql, $binds);
		}
		
		
		
		if ($this->cache_on == TRUE AND stristr($sql, 'SELECT'))
		{
			if ($this->_cache_init())
			{
				$this->load_rdriver();
				if (FALSE !== ($cache = $this->CACHE->read($sql)))
				{
					return $cache;
				}
			}
		}
		
		if ($this->save_queries == TRUE)
		{
			$this->queries[] = $sql;
		}
		
		$time_start = list($sm, $ss) = explode(' ', microtime());
		
		if (FALSE === ($this->result_id = $this->simple_query($sql)))
		{
			if ($this->save_queries == TRUE)
			{
				$this->query_times[] = 0;
			}
			
			$this->_trans_status = FALSE;
			if ($this->db_debug)
			{
				
				
				$error_no = $this->_error_number();
				$error_msg = $this->_error_message();
				
				
				
				
				$this->trans_complete();
				
				log_message('error', 'Query error: '.$error_msg);
				
				
				if($error_no != 1054){
				return $this->display_error(
										array(
												'Error Number: '.$error_no,
												$error_msg,
												$sql
											)
										);
				
				
				}else return FALSE;
			}
			return FALSE;
		}
		
		$time_end = list($em, $es) = explode(' ', microtime());
		$this->benchmark += ($em + $es) - ($sm + $ss);
		if ($this->save_queries == TRUE)
		{
			$this->query_times[] = ($em + $es) - ($sm + $ss);
		}
		
		$this->query_count++;
		
		
		if ($this->is_write_type($sql) === TRUE)
		{
			
			
			if ($this->cache_on == TRUE AND $this->cache_autodel == TRUE AND $this->_cache_init())
			{
				$this->CACHE->delete();
			}
			return TRUE;
		}
		
		
		
		if ($return_object !== TRUE)
		{
			return TRUE;
		}
		
		$driver			= $this->load_rdriver();
		$RES			= new $driver();
		$RES->conn_id	= $this->conn_id;
		$RES->result_id	= $this->result_id;
		if ($this->dbdriver == 'oci8')
		{
			$RES->stmt_id		= $this->stmt_id;
			$RES->curs_id		= NULL;
			$RES->limit_used	= $this->limit_used;
			$this->stmt_id		= FALSE;
		}
		
		$RES->num_rows	= $RES->num_rows();
		
		
		if ($this->cache_on == TRUE AND $this->_cache_init())
		{
			
			
			
			
			
			
			$CR = new CI_DB_result();
			$CR->num_rows		= $RES->num_rows();
			$CR->result_object	= $RES->result_object();
			$CR->result_array	= $RES->result_array();
			
			$CR->conn_id		= NULL;
			$CR->result_id		= NULL;
			$this->CACHE->write($sql, $CR);
		}
		return $RES;
	}
	function load_rdriver()
	{
		$driver = 'CI_DB_'.$this->dbdriver.'_result';
		if ( ! class_exists($driver))
		{
			include_once(BASEPATH.'database/DB_result.php');
			include_once(BASEPATH.'database/drivers/'.$this->dbdriver.'/'.$this->dbdriver.'_result.php');
		}
		return $driver;
	}
	function simple_query($sql)
	{
		if ( ! $this->conn_id)
		{
			$this->initialize();
		}
		return $this->_execute($sql);
	}
	function trans_off()
	{
		$this->trans_enabled = FALSE;
	}
	function trans_strict($mode = TRUE)
	{
		$this->trans_strict = is_bool($mode) ? $mode : TRUE;
	}
	function trans_start($test_mode = FALSE)
	{
		if ( ! $this->trans_enabled)
		{
			return FALSE;
		}
		
		if ($this->_trans_depth > 0)
		{
			$this->_trans_depth += 1;
			return;
		}
		$this->trans_begin($test_mode);
	}
	function trans_complete()
	{
		if ( ! $this->trans_enabled)
		{
			return FALSE;
		}
		
		if ($this->_trans_depth > 1)
		{
			$this->_trans_depth -= 1;
			return TRUE;
		}
		
		if ($this->_trans_status === FALSE)
		{
			$this->trans_rollback();
			
			
			
			if ($this->trans_strict === FALSE)
			{
				$this->_trans_status = TRUE;
			}
			log_message('debug', 'DB Transaction Failure');
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}
	function trans_status()
	{
		return $this->_trans_status;
	}
	function compile_binds($sql, $binds)
	{
		if (strpos($sql, $this->bind_marker) === FALSE)
		{
			return $sql;
		}
		if ( ! is_array($binds))
		{
			$binds = array($binds);
		}
		
		$segments = explode($this->bind_marker, $sql);
		
		
		if (count($binds) >= count($segments)) {
			$binds = array_slice($binds, 0, count($segments)-1);
		}
		
		$result = $segments[0];
		$i = 0;
		foreach ($binds as $bind)
		{
			$result .= $this->escape($bind);
			$result .= $segments[++$i];
		}
		return $result;
	}
	function is_write_type($sql)
	{
		if ( ! preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql))
		{
			return FALSE;
		}
		return TRUE;
	}
	function elapsed_time($decimals = 6)
	{
		return number_format($this->benchmark, $decimals);
	}
	function total_queries()
	{
		return $this->query_count;
	}
	function last_query()
	{
		return end($this->queries);
	}
	function escape($str)
	{
		if (is_string($str))
		{
			$str = "'".$this->escape_str($str)."'";
		}
		elseif (is_bool($str))
		{
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif (is_null($str))
		{
			$str = 'NULL';
		}
		return $str;
	}
	function escape_like_str($str)
	{
		return $this->escape_str($str, TRUE);
	}
	function primary($table = '')
	{
		$fields = $this->list_fields($table);
		if ( ! is_array($fields))
		{
			return FALSE;
		}
		return current($fields);
	}
	function list_tables($constrain_by_prefix = FALSE)
	{
		
		if (isset($this->data_cache['table_names']))
		{
			return $this->data_cache['table_names'];
		}
		if (FALSE === ($sql = $this->_list_tables($constrain_by_prefix)))
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_unsupported_function');
			}
			return FALSE;
		}
		$retval = array();
		$query = $this->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if (isset($row['TABLE_NAME']))
				{
					$retval[] = $row['TABLE_NAME'];
				}
				else
				{
					$retval[] = array_shift($row);
				}
			}
		}
		$this->data_cache['table_names'] = $retval;
		return $this->data_cache['table_names'];
	}
	function table_exists($table_name)
	{
		return ( ! in_array($this->_protect_identifiers($table_name, TRUE, FALSE, FALSE), $this->list_tables())) ? FALSE : TRUE;
	}
	function list_fields($table = '')
	{
		
		if (isset($this->data_cache['field_names'][$table]))
		{
			return $this->data_cache['field_names'][$table];
		}
		if ($table == '')
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_field_param_missing');
			}
			return FALSE;
		}
		if (FALSE === ($sql = $this->_list_columns($table)))
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_unsupported_function');
			}
			return FALSE;
		}
		$query = $this->query($sql);
		$retval = array();
		foreach ($query->result_array() as $row)
		{
			if (isset($row['COLUMN_NAME']))
			{
				$retval[] = $row['COLUMN_NAME'];
			}
			else
			{
				$retval[] = current($row);
			}
		}
		$this->data_cache['field_names'][$table] = $retval;
		return $this->data_cache['field_names'][$table];
	}
	function field_exists($field_name, $table_name)
	{
		return ( ! in_array($field_name, $this->list_fields($table_name))) ? FALSE : TRUE;
	}
	function field_data($table = '')
	{
		if ($table == '')
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_field_param_missing');
			}
			return FALSE;
		}
		$query = $this->query($this->_field_data($this->_protect_identifiers($table, TRUE, NULL, FALSE)));
		return $query->field_data();
	}
	function insert_string($table, $data)
	{
		$fields = array();
		$values = array();
		foreach ($data as $key => $val)
		{
			$fields[] = $this->_escape_identifiers($key);
			$values[] = $this->escape($val);
		}
		return $this->_insert($this->_protect_identifiers($table, TRUE, NULL, FALSE), $fields, $values);
	}
	function update_string($table, $data, $where)
	{
		if ($where == '')
		{
			return false;
		}
		$fields = array();
		foreach ($data as $key => $val)
		{
			$fields[$this->_protect_identifiers($key)] = $this->escape($val);
		}
		if ( ! is_array($where))
		{
			$dest = array($where);
		}
		else
		{
			$dest = array();
			foreach ($where as $key => $val)
			{
				$prefix = (count($dest) == 0) ? '' : ' AND ';
				if ($val !== '')
				{
					if ( ! $this->_has_operator($key))
					{
						$key .= ' =';
					}
					$val = ' '.$this->escape($val);
				}
				$dest[] = $prefix.$key.$val;
			}
		}
		return $this->_update($this->_protect_identifiers($table, TRUE, NULL, FALSE), $fields, $dest);
	}
	function _has_operator($str)
	{
		$str = trim($str);
		if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
		{
			return FALSE;
		}
		return TRUE;
	}
	function call_function($function)
	{
		$driver = ($this->dbdriver == 'postgre') ? 'pg_' : $this->dbdriver.'_';
		if (FALSE === strpos($driver, $function))
		{
			$function = $driver.$function;
		}
		if ( ! function_exists($function))
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_unsupported_function');
			}
			return FALSE;
		}
		else
		{
			$args = (func_num_args() > 1) ? array_splice(func_get_args(), 1) : null;
			if (is_null($args))
			{
				return call_user_func($function);
			}
			else
			{
				return call_user_func_array($function, $args);
			}
		}
	}
	function cache_set_path($path = '')
	{
		$this->cachedir = $path;
	}
	function cache_on()
	{
		$this->cache_on = TRUE;
		return TRUE;
	}
	function cache_off()
	{
		$this->cache_on = FALSE;
		return FALSE;
	}
	function cache_delete($segment_one = '', $segment_two = '')
	{
		if ( ! $this->_cache_init())
		{
			return FALSE;
		}
		return $this->CACHE->delete($segment_one, $segment_two);
	}
	function cache_delete_all()
	{
		if ( ! $this->_cache_init())
		{
			return FALSE;
		}
		return $this->CACHE->delete_all();
	}
	function _cache_init()
	{
		if (is_object($this->CACHE) AND class_exists('CI_DB_Cache'))
		{
			return TRUE;
		}
		if ( ! class_exists('CI_DB_Cache'))
		{
			if ( ! @include(BASEPATH.'database/DB_cache.php'))
			{
				return $this->cache_off();
			}
		}
		$this->CACHE = new CI_DB_Cache($this); 
		return TRUE;
	}
	function close()
	{
		if (is_resource($this->conn_id) OR is_object($this->conn_id))
		{
			$this->_close($this->conn_id);
		}
		$this->conn_id = FALSE;
	}
	function display_error($error = '', $swap = '', $native = FALSE)
	{
		$LANG =& load_class('Lang', 'core');
		$LANG->load('db');
		$heading = $LANG->line('db_error_heading');
		if ($native == TRUE)
		{
			$message = $error;
		}
		else
		{
			$message = ( ! is_array($error)) ? array(str_replace('%s', $swap, $LANG->line($error))) : $error;
		}
		
		
		
		$trace = debug_backtrace();
		foreach ($trace as $call)
		{
			if (isset($call['file']) && strpos($call['file'], BASEPATH.'database') === FALSE)
			{
				
				$message[] = 'Filename: '.str_replace(array(BASEPATH, APPPATH), '', $call['file']);
				$message[] = 'Line Number: '.$call['line'];
				break;
			}
		}
		$error =& load_class('Exceptions', 'core');
		echo $error->show_error($heading, $message, 'error_db');
		exit;
	}
	function protect_identifiers($item, $prefix_single = FALSE)
	{
		return $this->_protect_identifiers($item, $prefix_single);
	}
	function _protect_identifiers($item, $prefix_single = FALSE, $protect_identifiers = NULL, $field_exists = TRUE)
	{
		if ( ! is_bool($protect_identifiers))
		{
			$protect_identifiers = $this->_protect_identifiers;
		}
		if (is_array($item))
		{
			$escaped_array = array();
			foreach ($item as $k => $v)
			{
				$escaped_array[$this->_protect_identifiers($k)] = $this->_protect_identifiers($v);
			}
			return $escaped_array;
		}
		
		$item = preg_replace('/[\t ]+/', ' ', $item);
		
		
		if (strpos($item, ' ') !== FALSE)
		{
			$alias = strstr($item, ' ');
			$item = substr($item, 0, - strlen($alias));
		}
		else
		{
			$alias = '';
		}
		
		
		
		
		if (strpos($item, '(') !== FALSE)
		{
			return $item.$alias;
		}
		
		
		
		if (strpos($item, '.') !== FALSE)
		{
			$parts	= explode('.', $item);
			
			
			
			if (in_array($parts[0], $this->ar_aliased_tables))
			{
				if ($protect_identifiers === TRUE)
				{
					foreach ($parts as $key => $val)
					{
						if ( ! in_array($val, $this->_reserved_identifiers))
						{
							$parts[$key] = $this->_escape_identifiers($val);
						}
					}
					$item = implode('.', $parts);
				}
				return $item.$alias;
			}
			
			if ($this->dbprefix != '')
			{
				
				
				
				if (isset($parts[3]))
				{
					$i = 2;
				}
				
				
				elseif (isset($parts[2]))
				{
					$i = 1;
				}
				
				
				else
				{
					$i = 0;
				}
				
				
				if ($field_exists == FALSE)
				{
					$i++;
				}
				
				if ($this->swap_pre != '' && strncmp($parts[$i], $this->swap_pre, strlen($this->swap_pre)) === 0)
				{
					$parts[$i] = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $parts[$i]);
				}
				
				if (substr($parts[$i], 0, strlen($this->dbprefix)) != $this->dbprefix)
				{
					$parts[$i] = $this->dbprefix.$parts[$i];
				}
				
				$item = implode('.', $parts);
			}
			if ($protect_identifiers === TRUE)
			{
				$item = $this->_escape_identifiers($item);
			}
			return $item.$alias;
		}
		
		if ($this->dbprefix != '')
		{
			
			if ($this->swap_pre != '' && strncmp($item, $this->swap_pre, strlen($this->swap_pre)) === 0)
			{
				$item = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $item);
			}
			
			if ($prefix_single == TRUE AND substr($item, 0, strlen($this->dbprefix)) != $this->dbprefix)
			{
				$item = $this->dbprefix.$item;
			}
		}
		if ($protect_identifiers === TRUE AND ! in_array($item, $this->_reserved_identifiers))
		{
			$item = $this->_escape_identifiers($item);
		}
		return $item.$alias;
	}
	protected function _reset_select()
	{
	}
}