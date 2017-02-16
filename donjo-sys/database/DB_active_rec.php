<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_active_record extends CI_DB_driver {
	var $ar_select				= array();
	var $ar_distinct			= FALSE;
	var $ar_from				= array();
	var $ar_join				= array();
	var $ar_where				= array();
	var $ar_like				= array();
	var $ar_groupby				= array();
	var $ar_having				= array();
	var $ar_keys				= array();
	var $ar_limit				= FALSE;
	var $ar_offset				= FALSE;
	var $ar_order				= FALSE;
	var $ar_orderby				= array();
	var $ar_set					= array();
	var $ar_wherein				= array();
	var $ar_aliased_tables		= array();
	var $ar_store_array			= array();
	var $ar_caching				= FALSE;
	var $ar_cache_exists		= array();
	var $ar_cache_select		= array();
	var $ar_cache_from			= array();
	var $ar_cache_join			= array();
	var $ar_cache_where			= array();
	var $ar_cache_like			= array();
	var $ar_cache_groupby		= array();
	var $ar_cache_having		= array();
	var $ar_cache_orderby		= array();
	var $ar_cache_set			= array();
	var $ar_no_escape 			= array();
	var $ar_cache_no_escape     = array();
	public function select($select = '*', $escape = NULL)
	{
		if (is_string($select))
		{
			$select = explode(',', $select);
		}
		foreach ($select as $val)
		{
			$val = trim($val);
			if ($val != '')
			{
				$this->ar_select[] = $val;
				$this->ar_no_escape[] = $escape;
				if ($this->ar_caching === TRUE)
				{
					$this->ar_cache_select[] = $val;
					$this->ar_cache_exists[] = 'select';
					$this->ar_cache_no_escape[] = $escape;
				}
			}
		}
		return $this;
	}
	public function select_max($select = '', $alias = '')
	{
		return $this->_max_min_avg_sum($select, $alias, 'MAX');
	}
	public function select_min($select = '', $alias = '')
	{
		return $this->_max_min_avg_sum($select, $alias, 'MIN');
	}
	public function select_avg($select = '', $alias = '')
	{
		return $this->_max_min_avg_sum($select, $alias, 'AVG');
	}
	public function select_sum($select = '', $alias = '')
	{
		return $this->_max_min_avg_sum($select, $alias, 'SUM');
	}
	protected function _max_min_avg_sum($select = '', $alias = '', $type = 'MAX')
	{
		if ( ! is_string($select) OR $select == '')
		{
			$this->display_error('db_invalid_query');
		}
		$type = strtoupper($type);
		if ( ! in_array($type, array('MAX', 'MIN', 'AVG', 'SUM')))
		{
			show_error('Invalid function type: '.$type);
		}
		if ($alias == '')
		{
			$alias = $this->_create_alias_from_table(trim($select));
		}
		$sql = $type.'('.$this->_protect_identifiers(trim($select)).') AS '.$alias;
		$this->ar_select[] = $sql;
		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_select[] = $sql;
			$this->ar_cache_exists[] = 'select';
		}
		return $this;
	}
	protected function _create_alias_from_table($item)
	{
		if (strpos($item, '.') !== FALSE)
		{
			return end(explode('.', $item));
		}
		return $item;
	}
	public function distinct($val = TRUE)
	{
		$this->ar_distinct = (is_bool($val)) ? $val : TRUE;
		return $this;
	}
	public function from($from)
	{
		foreach ((array) $from as $val)
		{
			if (strpos($val, ',') !== FALSE)
			{
				foreach (explode(',', $val) as $v)
				{
					$v = trim($v);
					$this->_track_aliases($v);
					$this->ar_from[] = $this->_protect_identifiers($v, TRUE, NULL, FALSE);
					if ($this->ar_caching === TRUE)
					{
						$this->ar_cache_from[] = $this->_protect_identifiers($v, TRUE, NULL, FALSE);
						$this->ar_cache_exists[] = 'from';
					}
				}
			}
			else
			{
				$val = trim($val);
				
				
				$this->_track_aliases($val);
				$this->ar_from[] = $this->_protect_identifiers($val, TRUE, NULL, FALSE);
				if ($this->ar_caching === TRUE)
				{
					$this->ar_cache_from[] = $this->_protect_identifiers($val, TRUE, NULL, FALSE);
					$this->ar_cache_exists[] = 'from';
				}
			}
		}
		return $this;
	}
	public function join($table, $cond, $type = '')
	{
		if ($type != '')
		{
			$type = strtoupper(trim($type));
			if ( ! in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER')))
			{
				$type = '';
			}
			else
			{
				$type .= ' ';
			}
		}
		
		
		$this->_track_aliases($table);
		
		if (preg_match('/([\w\.]+)([\W\s]+)(.+)/', $cond, $match))
		{
			$match[1] = $this->_protect_identifiers($match[1]);
			$match[3] = $this->_protect_identifiers($match[3]);
			$cond = $match[1].$match[2].$match[3];
		}
		
		$join = $type.'JOIN '.$this->_protect_identifiers($table, TRUE, NULL, FALSE).' ON '.$cond;
		$this->ar_join[] = $join;
		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_join[] = $join;
			$this->ar_cache_exists[] = 'join';
		}
		return $this;
	}
	public function where($key, $value = NULL, $escape = TRUE)
	{
		return $this->_where($key, $value, 'AND ', $escape);
	}
	public function or_where($key, $value = NULL, $escape = TRUE)
	{
		return $this->_where($key, $value, 'OR ', $escape);
	}
	protected function _where($key, $value = NULL, $type = 'AND ', $escape = NULL)
	{
		if ( ! is_array($key))
		{
			$key = array($key => $value);
		}
		
		if ( ! is_bool($escape))
		{
			$escape = $this->_protect_identifiers;
		}
		foreach ($key as $k => $v)
		{
			$prefix = (count($this->ar_where) == 0 AND count($this->ar_cache_where) == 0) ? '' : $type;
			if (is_null($v) && ! $this->_has_operator($k))
			{
				
				$k .= ' IS NULL';
			}
			if ( ! is_null($v))
			{
				if ($escape === TRUE)
				{
					$k = $this->_protect_identifiers($k, FALSE, $escape);
					$v = ' '.$this->escape($v);
				}
				
				if ( ! $this->_has_operator($k))
				{
					$k .= ' = ';
				}
			}
			else
			{
				$k = $this->_protect_identifiers($k, FALSE, $escape);
			}
			$this->ar_where[] = $prefix.$k.$v;
			if ($this->ar_caching === TRUE)
			{
				$this->ar_cache_where[] = $prefix.$k.$v;
				$this->ar_cache_exists[] = 'where';
			}
		}
		return $this;
	}
	public function where_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values);
	}
	public function or_where_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, FALSE, 'OR ');
	}
	public function where_not_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, TRUE);
	}
	public function or_where_not_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, TRUE, 'OR ');
	}
	protected function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ')
	{
		if ($key === NULL OR $values === NULL)
		{
			return;
		}
		if ( ! is_array($values))
		{
			$values = array($values);
		}
		$not = ($not) ? ' NOT' : '';
		foreach ($values as $value)
		{
			$this->ar_wherein[] = $this->escape($value);
		}
		$prefix = (count($this->ar_where) == 0) ? '' : $type;
		$where_in = $prefix . $this->_protect_identifiers($key) . $not . " IN (" . implode(", ", $this->ar_wherein) . ") ";
		$this->ar_where[] = $where_in;
		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_where[] = $where_in;
			$this->ar_cache_exists[] = 'where';
		}
		
		$this->ar_wherein = array();
		return $this;
	}
	public function like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'AND ', $side);
	}
	public function not_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'AND ', $side, 'NOT');
	}
	public function or_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'OR ', $side);
	}
	public function or_not_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'OR ', $side, 'NOT');
	}
	protected function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
	{
		if ( ! is_array($field))
		{
			$field = array($field => $match);
		}
		foreach ($field as $k => $v)
		{
			$k = $this->_protect_identifiers($k);
			$prefix = (count($this->ar_like) == 0) ? '' : $type;
			$v = $this->escape_like_str($v);
			
			if ($side == 'none')
			{
				$like_statement = $prefix." $k $not LIKE '{$v}'";
			}
			elseif ($side == 'before')
			{
				$like_statement = $prefix." $k $not LIKE '%{$v}'";
			}
			elseif ($side == 'after')
			{
				$like_statement = $prefix." $k $not LIKE '{$v}%'";
			}
			else
			{
				$like_statement = $prefix." $k $not LIKE '%{$v}%'";
			}
			
			if ($this->_like_escape_str != '')
			{
				$like_statement = $like_statement.sprintf($this->_like_escape_str, $this->_like_escape_chr);
			}
			$this->ar_like[] = $like_statement;
			if ($this->ar_caching === TRUE)
			{
				$this->ar_cache_like[] = $like_statement;
				$this->ar_cache_exists[] = 'like';
			}
		}
		return $this;
	}
	public function group_by($by)
	{
		if (is_string($by))
		{
			$by = explode(',', $by);
		}
		foreach ($by as $val)
		{
			$val = trim($val);
			if ($val != '')
			{
				$this->ar_groupby[] = $this->_protect_identifiers($val);
				if ($this->ar_caching === TRUE)
				{
					$this->ar_cache_groupby[] = $this->_protect_identifiers($val);
					$this->ar_cache_exists[] = 'groupby';
				}
			}
		}
		return $this;
	}
	public function having($key, $value = '', $escape = TRUE)
	{
		return $this->_having($key, $value, 'AND ', $escape);
	}
	public function or_having($key, $value = '', $escape = TRUE)
	{
		return $this->_having($key, $value, 'OR ', $escape);
	}
	protected function _having($key, $value = '', $type = 'AND ', $escape = TRUE)
	{
		if ( ! is_array($key))
		{
			$key = array($key => $value);
		}
		foreach ($key as $k => $v)
		{
			$prefix = (count($this->ar_having) == 0) ? '' : $type;
			if ($escape === TRUE)
			{
				$k = $this->_protect_identifiers($k);
			}
			if ( ! $this->_has_operator($k))
			{
				$k .= ' = ';
			}
			if ($v != '')
			{
				$v = ' '.$this->escape($v);
			}
			$this->ar_having[] = $prefix.$k.$v;
			if ($this->ar_caching === TRUE)
			{
				$this->ar_cache_having[] = $prefix.$k.$v;
				$this->ar_cache_exists[] = 'having';
			}
		}
		return $this;
	}
	public function order_by($orderby, $direction = '')
	{
		if (strtolower($direction) == 'random')
		{
			$orderby = ''; 
			$direction = $this->_random_keyword;
		}
		elseif (trim($direction) != '')
		{
			$direction = (in_array(strtoupper(trim($direction)), array('ASC', 'DESC'), TRUE)) ? ' '.$direction : ' ASC';
		}
		if (strpos($orderby, ',') !== FALSE)
		{
			$temp = array();
			foreach (explode(',', $orderby) as $part)
			{
				$part = trim($part);
				if ( ! in_array($part, $this->ar_aliased_tables))
				{
					$part = $this->_protect_identifiers(trim($part));
				}
				$temp[] = $part;
			}
			$orderby = implode(', ', $temp);
		}
		else if ($direction != $this->_random_keyword)
		{
			$orderby = $this->_protect_identifiers($orderby);
		}
		$orderby_statement = $orderby.$direction;
		$this->ar_orderby[] = $orderby_statement;
		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_orderby[] = $orderby_statement;
			$this->ar_cache_exists[] = 'orderby';
		}
		return $this;
	}
	public function limit($value, $offset = '')
	{
		$this->ar_limit = (int) $value;
		if ($offset != '')
		{
			$this->ar_offset = (int) $offset;
		}
		return $this;
	}
	public function offset($offset)
	{
		$this->ar_offset = (int) $offset;
		return $this;
	}
	public function set($key, $value = '', $escape = TRUE)
	{
		$key = $this->_object_to_array($key);
		if ( ! is_array($key))
		{
			$key = array($key => $value);
		}
		foreach ($key as $k => $v)
		{
			if ($escape === FALSE)
			{
				$this->ar_set[$this->_protect_identifiers($k)] = $v;
			}
			else
			{
				$this->ar_set[$this->_protect_identifiers($k, FALSE, TRUE)] = $this->escape($v);
			}
		}
		return $this;
	}
	public function get($table = '', $limit = null, $offset = null)
	{
		if ($table != '')
		{
			$this->_track_aliases($table);
			$this->from($table);
		}
		if ( ! is_null($limit))
		{
			$this->limit($limit, $offset);
		}
		$sql = $this->_compile_select();
		$result = $this->query($sql);
		$this->_reset_select();
		return $result;
	}
	public function count_all_results($table = '')
	{
		if ($table != '')
		{
			$this->_track_aliases($table);
			$this->from($table);
		}
		$sql = $this->_compile_select($this->_count_string . $this->_protect_identifiers('numrows'));
		$query = $this->query($sql);
		$this->_reset_select();
		if ($query->num_rows() == 0)
		{
			return 0;
		}
		$row = $query->row();
		return (int) $row->numrows;
	}
	public function get_where($table = '', $where = null, $limit = null, $offset = null)
	{
		if ($table != '')
		{
			$this->from($table);
		}
		if ( ! is_null($where))
		{
			$this->where($where);
		}
		if ( ! is_null($limit))
		{
			$this->limit($limit, $offset);
		}
		$sql = $this->_compile_select();
		$result = $this->query($sql);
		$this->_reset_select();
		return $result;
	}
	public function insert_batch($table = '', $set = NULL)
	{
		if ( ! is_null($set))
		{
			$this->set_insert_batch($set);
		}
		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		
		for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100)
		{
			$sql = $this->_insert_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_keys, array_slice($this->ar_set, $i, 100));
			
			$this->query($sql);
		}
		$this->_reset_write();
		return TRUE;
	}
	public function set_insert_batch($key, $value = '', $escape = TRUE)
	{
		$key = $this->_object_to_array_batch($key);
		if ( ! is_array($key))
		{
			$key = array($key => $value);
		}
		$keys = array_keys(current($key));
		sort($keys);
		foreach ($key as $row)
		{
			if (count(array_diff($keys, array_keys($row))) > 0 OR count(array_diff(array_keys($row), $keys)) > 0)
			{
				
				$this->ar_set[] = array();
				return;
			}
			ksort($row); 
			if ($escape === FALSE)
			{
				$this->ar_set[] =  '('.implode(',', $row).')';
			}
			else
			{
				$clean = array();
				foreach ($row as $value)
				{
					$clean[] = $this->escape($value);
				}
				$this->ar_set[] =  '('.implode(',', $clean).')';
			}
		}
		foreach ($keys as $k)
		{
			$this->ar_keys[] = $this->_protect_identifiers($k);
		}
		return $this;
	}
	function insert($table = '', $set = NULL)
	{
		if ( ! is_null($set))
		{
			$this->set($set);
		}
		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		$sql = $this->_insert($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_keys($this->ar_set), array_values($this->ar_set));
		$this->_reset_write();
		return $this->query($sql);
	}
	public function replace($table = '', $set = NULL)
	{
		if ( ! is_null($set))
		{
			$this->set($set);
		}
		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		$sql = $this->_replace($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_keys($this->ar_set), array_values($this->ar_set));
		$this->_reset_write();
		return $this->query($sql);
	}
	public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
	{
		
		$this->_merge_cache();
		if ( ! is_null($set))
		{
			$this->set($set);
		}
		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		if ($where != NULL)
		{
			$this->where($where);
		}
		if ($limit != NULL)
		{
			$this->limit($limit);
		}
		$sql = $this->_update($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);
		$this->_reset_write();
		return $this->query($sql);
	}
	public function update_batch($table = '', $set = NULL, $index = NULL)
	{
		
		$this->_merge_cache();
		if (is_null($index))
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_index');
			}
			return FALSE;
		}
		if ( ! is_null($set))
		{
			$this->set_update_batch($set, $index);
		}
		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		
		for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100)
		{
			$sql = $this->_update_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_slice($this->ar_set, $i, 100), $this->_protect_identifiers($index), $this->ar_where);
			$this->query($sql);
		}
		$this->_reset_write();
	}
	public function set_update_batch($key, $index = '', $escape = TRUE)
	{
		$key = $this->_object_to_array_batch($key);
		if ( ! is_array($key))
		{
			
		}
		foreach ($key as $k => $v)
		{
			$index_set = FALSE;
			$clean = array();
			foreach ($v as $k2 => $v2)
			{
				if ($k2 == $index)
				{
					$index_set = TRUE;
				}
				else
				{
					$not[] = $k2.'-'.$v2;
				}
				if ($escape === FALSE)
				{
					$clean[$this->_protect_identifiers($k2)] = $v2;
				}
				else
				{
					$clean[$this->_protect_identifiers($k2)] = $this->escape($v2);
				}
			}
			if ($index_set == FALSE)
			{
				return $this->display_error('db_batch_missing_index');
			}
			$this->ar_set[] = $clean;
		}
		return $this;
	}
	public function empty_table($table = '')
	{
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		else
		{
			$table = $this->_protect_identifiers($table, TRUE, NULL, FALSE);
		}
		$sql = $this->_delete($table);
		$this->_reset_write();
		return $this->query($sql);
	}
	public function truncate($table = '')
	{
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		else
		{
			$table = $this->_protect_identifiers($table, TRUE, NULL, FALSE);
		}
		$sql = $this->_truncate($table);
		$this->_reset_write();
		return $this->query($sql);
	}
	public function delete($table = '', $where = '', $limit = NULL, $reset_data = TRUE)
	{
		
		$this->_merge_cache();
		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}
			$table = $this->ar_from[0];
		}
		elseif (is_array($table))
		{
			foreach ($table as $single_table)
			{
				$this->delete($single_table, $where, $limit, FALSE);
			}
			$this->_reset_write();
			return;
		}
		else
		{
			$table = $this->_protect_identifiers($table, TRUE, NULL, FALSE);
		}
		if ($where != '')
		{
			$this->where($where);
		}
		if ($limit != NULL)
		{
			$this->limit($limit);
		}
		if (count($this->ar_where) == 0 && count($this->ar_wherein) == 0 && count($this->ar_like) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_del_must_use_where');
			}
			return FALSE;
		}
		$sql = $this->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);
		if ($reset_data)
		{
			$this->_reset_write();
		}
		return $this->query($sql);
	}
	public function dbprefix($table = '')
	{
		if ($table == '')
		{
			$this->display_error('db_table_name_required');
		}
		return $this->dbprefix.$table;
	}
	public function set_dbprefix($prefix = '')
	{
		return $this->dbprefix = $prefix;
	}
	protected function _track_aliases($table)
	{
		if (is_array($table))
		{
			foreach ($table as $t)
			{
				$this->_track_aliases($t);
			}
			return;
		}
		
		
		if (strpos($table, ',') !== FALSE)
		{
			return $this->_track_aliases(explode(',', $table));
		}
		
		if (strpos($table, " ") !== FALSE)
		{
			
			$table = preg_replace('/\s+AS\s+/i', ' ', $table);
			
			$table = trim(strrchr($table, " "));
			
			if ( ! in_array($table, $this->ar_aliased_tables))
			{
				$this->ar_aliased_tables[] = $table;
			}
		}
	}
	protected function _compile_select($select_override = FALSE)
	{
		
		$this->_merge_cache();
		
		
		if ($select_override !== FALSE)
		{
			$sql = $select_override;
		}
		else
		{
			$sql = ( ! $this->ar_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';
			if (count($this->ar_select) == 0)
			{
				$sql .= '*';
			}
			else
			{
				
				
				
				foreach ($this->ar_select as $key => $val)
				{
					$no_escape = isset($this->ar_no_escape[$key]) ? $this->ar_no_escape[$key] : NULL;
					$this->ar_select[$key] = $this->_protect_identifiers($val, FALSE, $no_escape);
				}
				$sql .= implode(', ', $this->ar_select);
			}
		}
		
		
		if (count($this->ar_from) > 0)
		{
			$sql .= "\nFROM ";
			$sql .= $this->_from_tables($this->ar_from);
		}
		
		
		if (count($this->ar_join) > 0)
		{
			$sql .= "\n";
			$sql .= implode("\n", $this->ar_join);
		}
		
		
		if (count($this->ar_where) > 0 OR count($this->ar_like) > 0)
		{
			$sql .= "\nWHERE ";
		}
		$sql .= implode("\n", $this->ar_where);
		
		
		if (count($this->ar_like) > 0)
		{
			if (count($this->ar_where) > 0)
			{
				$sql .= "\nAND ";
			}
			$sql .= implode("\n", $this->ar_like);
		}
		
		
		if (count($this->ar_groupby) > 0)
		{
			$sql .= "\nGROUP BY ";
			$sql .= implode(', ', $this->ar_groupby);
		}
		
		
		if (count($this->ar_having) > 0)
		{
			$sql .= "\nHAVING ";
			$sql .= implode("\n", $this->ar_having);
		}
		
		
		if (count($this->ar_orderby) > 0)
		{
			$sql .= "\nORDER BY ";
			$sql .= implode(', ', $this->ar_orderby);
			if ($this->ar_order !== FALSE)
			{
				$sql .= ($this->ar_order == 'desc') ? ' DESC' : ' ASC';
			}
		}
		
		
		if (is_numeric($this->ar_limit))
		{
			$sql .= "\n";
			$sql = $this->_limit($sql, $this->ar_limit, $this->ar_offset);
		}
		return $sql;
	}
	public function _object_to_array($object)
	{
		if ( ! is_object($object))
		{
			return $object;
		}
		$array = array();
		foreach (get_object_vars($object) as $key => $val)
		{
			
			if ( ! is_object($val) && ! is_array($val) && $key != '_parent_name')
			{
				$array[$key] = $val;
			}
		}
		return $array;
	}
	public function _object_to_array_batch($object)
	{
		if ( ! is_object($object))
		{
			return $object;
		}
		$array = array();
		$out = get_object_vars($object);
		$fields = array_keys($out);
		foreach ($fields as $val)
		{
			
			if ($val != '_parent_name')
			{
				$i = 0;
				foreach ($out[$val] as $data)
				{
					$array[$i][$val] = $data;
					$i++;
				}
			}
		}
		return $array;
	}
	public function start_cache()
	{
		$this->ar_caching = TRUE;
	}
	public function stop_cache()
	{
		$this->ar_caching = FALSE;
	}
	public function flush_cache()
	{
		$this->_reset_run(array(
			'ar_cache_select'		=> array(),
			'ar_cache_from'			=> array(),
			'ar_cache_join'			=> array(),
			'ar_cache_where'		=> array(),
			'ar_cache_like'			=> array(),
			'ar_cache_groupby'		=> array(),
			'ar_cache_having'		=> array(),
			'ar_cache_orderby'		=> array(),
			'ar_cache_set'			=> array(),
			'ar_cache_exists'		=> array(),
			'ar_cache_no_escape'	=> array()
		));
	}
	protected function _merge_cache()
	{
		if (count($this->ar_cache_exists) == 0)
		{
			return;
		}
		foreach ($this->ar_cache_exists as $val)
		{
			$ar_variable	= 'ar_'.$val;
			$ar_cache_var	= 'ar_cache_'.$val;
			if (count($this->$ar_cache_var) == 0)
			{
				continue;
			}
			$this->$ar_variable = array_unique(array_merge($this->$ar_cache_var, $this->$ar_variable));
		}
		
		
		if ($this->_protect_identifiers === TRUE AND count($this->ar_cache_from) > 0)
		{
			$this->_track_aliases($this->ar_from);
		}
		$this->ar_no_escape = $this->ar_cache_no_escape;
	}
	protected function _reset_run($ar_reset_items)
	{
		foreach ($ar_reset_items as $item => $default_value)
		{
			if ( ! in_array($item, $this->ar_store_array))
			{
				$this->$item = $default_value;
			}
		}
	}
	protected function _reset_select()
	{
		$ar_reset_items = array(
			'ar_select'			=> array(),
			'ar_from'			=> array(),
			'ar_join'			=> array(),
			'ar_where'			=> array(),
			'ar_like'			=> array(),
			'ar_groupby'		=> array(),
			'ar_having'			=> array(),
			'ar_orderby'		=> array(),
			'ar_wherein'		=> array(),
			'ar_aliased_tables'	=> array(),
			'ar_no_escape'		=> array(),
			'ar_distinct'		=> FALSE,
			'ar_limit'			=> FALSE,
			'ar_offset'			=> FALSE,
			'ar_order'			=> FALSE,
		);
		$this->_reset_run($ar_reset_items);
	}
	protected function _reset_write()
	{
		$ar_reset_items = array(
			'ar_set'		=> array(),
			'ar_from'		=> array(),
			'ar_where'		=> array(),
			'ar_like'		=> array(),
			'ar_orderby'	=> array(),
			'ar_keys'		=> array(),
			'ar_limit'		=> FALSE,
			'ar_order'		=> FALSE
		);
		$this->_reset_run($ar_reset_items);
	}
}