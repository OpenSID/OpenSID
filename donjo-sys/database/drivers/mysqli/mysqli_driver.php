<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_mysqli_driver extends CI_DB {
	var $dbdriver = 'mysqli';
	var $_escape_char = '`';
	var $_like_escape_str = '';
	var $_like_escape_chr = '';
	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword = ' RAND()'; 
	var $delete_hack = TRUE;
	var $use_set_names;
	function db_connect()
	{
		if ($this->port != '')
		{
			return @mysqli_connect($this->hostname, $this->username, $this->password, $this->database, $this->port);
		}
		else
		{
			return @mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
		}
	}
	function db_pconnect()
	{
		return $this->db_connect();
	}
	function reconnect()
	{
		if (mysqli_ping($this->conn_id) === FALSE)
		{
			$this->conn_id = FALSE;
		}
	}
	function db_select()
	{
		return @mysqli_select_db($this->conn_id, $this->database);
	}
	function _db_set_charset($charset, $collation)
	{
		if ( ! isset($this->use_set_names))
		{
			
			$this->use_set_names = (version_compare(mysqli_get_server_info($this->conn_id), '5.0.7', '>=')) ? FALSE : TRUE;
		}
		if ($this->use_set_names === TRUE)
		{
			return @mysqli_query($this->conn_id, "SET NAMES '".$this->escape_str($charset)."' COLLATE '".$this->escape_str($collation)."'");
		}
		else
		{
			return @mysqli_set_charset($this->conn_id, $charset);
		}
	}
	function _version()
	{
		return "SELECT version() AS ver";
	}
	function _execute($sql)
	{
		$sql = $this->_prep_query($sql);
		$result = @mysqli_query($this->conn_id, $sql);
		return $result;
	}
	function _prep_query($sql)
	{
		
		
		if ($this->delete_hack === TRUE)
		{
			if (preg_match('/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $sql))
			{
				$sql = preg_replace("/^\s*DELETE\s+FROM\s+(\S+)\s*$/", "DELETE FROM \\1 WHERE 1=1", $sql);
			}
		}
		return $sql;
	}
	function trans_begin($test_mode = FALSE)
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}
		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}
		
		
		
		$this->_trans_failure = ($test_mode === TRUE) ? TRUE : FALSE;
		$this->simple_query('SET AUTOCOMMIT=0');
		$this->simple_query('START TRANSACTION'); // can also be BEGIN or BEGIN WORK
		return TRUE;
	}
	function trans_commit()
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}
		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}
		$this->simple_query('COMMIT');
		$this->simple_query('SET AUTOCOMMIT=1');
		return TRUE;
	}
	function trans_rollback()
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}
		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}
		$this->simple_query('ROLLBACK');
		$this->simple_query('SET AUTOCOMMIT=1');
		return TRUE;
	}
	function escape_str($str, $like = FALSE)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str[$key] = $this->escape_str($val, $like);
			}
			return $str;
		}
		$str = mysqli_real_escape_string($this->conn_id, $str);
		
		if ($like === TRUE)
		{
			$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
		}
		return $str;
	}
	function affected_rows()
	{
		return @mysqli_affected_rows($this->conn_id);
	}
	function insert_id()
	{
		return @mysqli_insert_id($this->conn_id);
	}
	function count_all($table = '')
	{
		if ($table == '')
		{
			return 0;
		}
		$query = $this->query($this->_count_string . $this->_protect_identifiers('numrows') . " FROM " . $this->_protect_identifiers($table, TRUE, NULL, FALSE));
		if ($query->num_rows() == 0)
		{
			return 0;
		}
		$row = $query->row();
		$this->_reset_select();
		return (int) $row->numrows;
	}
	function _list_tables($prefix_limit = FALSE)
	{
		$sql = "SHOW TABLES FROM ".$this->_escape_char.$this->database.$this->_escape_char;
		if ($prefix_limit !== FALSE AND $this->dbprefix != '')
		{
			$sql .= " LIKE '".$this->escape_like_str($this->dbprefix)."%'";
		}
		return $sql;
	}
	function _list_columns($table = '')
	{
		return "SHOW COLUMNS FROM ".$this->_protect_identifiers($table, TRUE, NULL, FALSE);
	}
	function _field_data($table)
	{
		return "DESCRIBE ".$table;
	}
	function _error_message()
	{
		return mysqli_error($this->conn_id);
	}
	function _error_number()
	{
		return mysqli_errno($this->conn_id);
	}
	function _escape_identifiers($item)
	{
		if ($this->_escape_char == '')
		{
			return $item;
		}
		foreach ($this->_reserved_identifiers as $id)
		{
			if (strpos($item, '.'.$id) !== FALSE)
			{
				$str = $this->_escape_char. str_replace('.', $this->_escape_char.'.', $item);
				
				return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
			}
		}
		if (strpos($item, '.') !== FALSE)
		{
			$str = $this->_escape_char.str_replace('.', $this->_escape_char.'.'.$this->_escape_char, $item).$this->_escape_char;
		}
		else
		{
			$str = $this->_escape_char.$item.$this->_escape_char;
		}
		
		return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
	}
	function _from_tables($tables)
	{
		if ( ! is_array($tables))
		{
			$tables = array($tables);
		}
		return '('.implode(', ', $tables).')';
	}
	function _insert($table, $keys, $values)
	{
		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}
	function _insert_batch($table, $keys, $values)
	{
		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES ".implode(', ', $values);
	}
	function _replace($table, $keys, $values)
	{
		return "REPLACE INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}
	function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
	{
		foreach ($values as $key => $val)
		{
			$valstr[] = $key." = ".$val;
		}
		$limit = ( ! $limit) ? '' : ' LIMIT '.$limit;
		$orderby = (count($orderby) >= 1)?' ORDER BY '.implode(", ", $orderby):'';
		$sql = "UPDATE ".$table." SET ".implode(', ', $valstr);
		$sql .= ($where != '' AND count($where) >=1) ? " WHERE ".implode(" ", $where) : '';
		$sql .= $orderby.$limit;
		return $sql;
	}
	function _update_batch($table, $values, $index, $where = NULL)
	{
		$ids = array();
		$where = ($where != '' AND count($where) >=1) ? implode(" ", $where).' AND ' : '';
		foreach ($values as $key => $val)
		{
			$ids[] = $val[$index];
			foreach (array_keys($val) as $field)
			{
				if ($field != $index)
				{
					$final[$field][] =  'WHEN '.$index.' = '.$val[$index].' THEN '.$val[$field];
				}
			}
		}
		$sql = "UPDATE ".$table." SET ";
		$cases = '';
		foreach ($final as $k => $v)
		{
			$cases .= $k.' = CASE '."\n";
			foreach ($v as $row)
			{
				$cases .= $row."\n";
			}
			$cases .= 'ELSE '.$k.' END, ';
		}
		$sql .= substr($cases, 0, -2);
		$sql .= ' WHERE '.$where.$index.' IN ('.implode(',', $ids).')';
		return $sql;
	}
	function _truncate($table)
	{
		return "TRUNCATE ".$table;
	}
	function _delete($table, $where = array(), $like = array(), $limit = FALSE)
	{
		$conditions = '';
		if (count($where) > 0 OR count($like) > 0)
		{
			$conditions = "\nWHERE ";
			$conditions .= implode("\n", $this->ar_where);
			if (count($where) > 0 && count($like) > 0)
			{
				$conditions .= " AND ";
			}
			$conditions .= implode("\n", $like);
		}
		$limit = ( ! $limit) ? '' : ' LIMIT '.$limit;
		return "DELETE FROM ".$table.$conditions.$limit;
	}
	function _limit($sql, $limit, $offset)
	{
		$sql .= "LIMIT ".$limit;
		if ($offset > 0)
		{
			$sql .= " OFFSET ".$offset;
		}
		return $sql;
	}
	function _close($conn_id)
	{
		@mysqli_close($conn_id);
	}
}