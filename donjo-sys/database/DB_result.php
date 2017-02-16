<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_result {
	var $conn_id				= NULL;
	var $result_id				= NULL;
	var $result_array			= array();
	var $result_object			= array();
	var $custom_result_object	= array();
	var $current_row			= 0;
	var $num_rows				= 0;
	var $row_data				= NULL;
	public function result($type = 'object')
	{
		if ($type == 'array') return $this->result_array();
		else if ($type == 'object') return $this->result_object();
		else return $this->custom_result_object($type);
	}
	public function custom_result_object($class_name)
	{
		if (array_key_exists($class_name, $this->custom_result_object))
		{
			return $this->custom_result_object[$class_name];
		}
		if ($this->result_id === FALSE OR $this->num_rows() == 0)
		{
			return array();
		}
		
		$this->_data_seek(0);
		$result_object = array();
		while ($row = $this->_fetch_object())
		{
			$object = new $class_name();
			foreach ($row as $key => $value)
			{
				$object->$key = $value;
			}
			$result_object[] = $object;
		}
		
		return $this->custom_result_object[$class_name] = $result_object;
	}
	public function result_object()
	{
		if (count($this->result_object) > 0)
		{
			return $this->result_object;
		}
		
		
		
		if ($this->result_id === FALSE OR $this->num_rows() == 0)
		{
			return array();
		}
		$this->_data_seek(0);
		while ($row = $this->_fetch_object())
		{
			$this->result_object[] = $row;
		}
		return $this->result_object;
	}
	public function result_array()
	{
		if (count($this->result_array) > 0)
		{
			return $this->result_array;
		}
		
		
		
		if ($this->result_id === FALSE OR $this->num_rows() == 0)
		{
			return array();
		}
		$this->_data_seek(0);
		while ($row = $this->_fetch_assoc())
		{
			$this->result_array[] = $row;
		}
		return $this->result_array;
	}
	public function row($n = 0, $type = 'object')
	{
		if ( ! is_numeric($n))
		{
			
			if ( ! is_array($this->row_data))
			{
				$this->row_data = $this->row_array(0);
			}
			
			if (array_key_exists($n, $this->row_data))
			{
				return $this->row_data[$n];
			}
			
			$n = 0;
		}
		if ($type == 'object') return $this->row_object($n);
		else if ($type == 'array') return $this->row_array($n);
		else return $this->custom_row_object($n, $type);
	}
	public function set_row($key, $value = NULL)
	{
		
		if ( ! is_array($this->row_data))
		{
			$this->row_data = $this->row_array(0);
		}
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->row_data[$k] = $v;
			}
			return;
		}
		if ($key != '' AND ! is_null($value))
		{
			$this->row_data[$key] = $value;
		}
	}
	public function custom_row_object($n, $type)
	{
		$result = $this->custom_result_object($type);
		if (count($result) == 0)
		{
			return $result;
		}
		if ($n != $this->current_row AND isset($result[$n]))
		{
			$this->current_row = $n;
		}
		return $result[$this->current_row];
	}
	public function row_object($n = 0)
	{
		$result = $this->result_object();
		if (count($result) == 0)
		{
			return $result;
		}
		if ($n != $this->current_row AND isset($result[$n]))
		{
			$this->current_row = $n;
		}
		return $result[$this->current_row];
	}
	public function row_array($n = 0)
	{
		$result = $this->result_array();
		if (count($result) == 0)
		{
			return $result;
		}
		if ($n != $this->current_row AND isset($result[$n]))
		{
			$this->current_row = $n;
		}
		return $result[$this->current_row];
	}
	public function first_row($type = 'object')
	{
		$result = $this->result($type);
		if (count($result) == 0)
		{
			return $result;
		}
		return $result[0];
	}
	public function last_row($type = 'object')
	{
		$result = $this->result($type);
		if (count($result) == 0)
		{
			return $result;
		}
		return $result[count($result) -1];
	}
	public function next_row($type = 'object')
	{
		$result = $this->result($type);
		if (count($result) == 0)
		{
			return $result;
		}
		if (isset($result[$this->current_row + 1]))
		{
			++$this->current_row;
		}
		return $result[$this->current_row];
	}
	public function previous_row($type = 'object')
	{
		$result = $this->result($type);
		if (count($result) == 0)
		{
			return $result;
		}
		if (isset($result[$this->current_row - 1]))
		{
			--$this->current_row;
		}
		return $result[$this->current_row];
	}
	public function num_rows() { return $this->num_rows; }
	public function num_fields() { return 0; }
	public function list_fields() { return array(); }
	public function field_data() { return array(); }
	public function free_result() { return TRUE; }
	protected function _data_seek() { return TRUE; }
	protected function _fetch_assoc() { return array(); }
	protected function _fetch_object() { return array(); }
}