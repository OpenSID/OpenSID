<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_mysqli_forge extends CI_DB_forge {
	function _create_database($name)
	{
		return "CREATE DATABASE ".$name;
	}
	function _drop_database($name)
	{
		return "DROP DATABASE ".$name;
	}
	function _process_fields($fields)
	{
		$current_field_count = 0;
		$sql = '';
		foreach ($fields as $field=>$attributes)
		{
			
			
			
			if (is_numeric($field))
			{
				$sql .= "\n\t$attributes";
			}
			else
			{
				$attributes = array_change_key_case($attributes, CASE_UPPER);
				$sql .= "\n\t".$this->db->_protect_identifiers($field);
				if (array_key_exists('NAME', $attributes))
				{
					$sql .= ' '.$this->db->_protect_identifiers($attributes['NAME']).' ';
				}
				if (array_key_exists('TYPE', $attributes))
				{
					$sql .=  ' '.$attributes['TYPE'];
				}
				if (array_key_exists('CONSTRAINT', $attributes))
				{
					$sql .= '('.$attributes['CONSTRAINT'].')';
				}
				if (array_key_exists('UNSIGNED', $attributes) && $attributes['UNSIGNED'] === TRUE)
				{
					$sql .= ' UNSIGNED';
				}
				if (array_key_exists('DEFAULT', $attributes))
				{
					$sql .= ' DEFAULT \''.$attributes['DEFAULT'].'\'';
				}
				if (array_key_exists('NULL', $attributes) && $attributes['NULL'] === TRUE)
				{
					$sql .= ' NULL';
				}
				else
				{
					$sql .= ' NOT NULL';
				}
				if (array_key_exists('AUTO_INCREMENT', $attributes) && $attributes['AUTO_INCREMENT'] === TRUE)
				{
					$sql .= ' AUTO_INCREMENT';
				}
			}
			
			if (++$current_field_count < count($fields))
			{
				$sql .= ',';
			}
		}
		return $sql;
	}
	function _create_table($table, $fields, $primary_keys, $keys, $if_not_exists)
	{
		$sql = 'CREATE TABLE ';
		if ($if_not_exists === TRUE)
		{
			$sql .= 'IF NOT EXISTS ';
		}
		$sql .= $this->db->_escape_identifiers($table)." (";
		$sql .= $this->_process_fields($fields);
		if (count($primary_keys) > 0)
		{
			$key_name = $this->db->_protect_identifiers(implode('_', $primary_keys));
			$primary_keys = $this->db->_protect_identifiers($primary_keys);
			$sql .= ",\n\tPRIMARY KEY ".$key_name." (" . implode(', ', $primary_keys) . ")";
		}
		if (is_array($keys) && count($keys) > 0)
		{
			foreach ($keys as $key)
			{
				if (is_array($key))
				{
					$key_name = $this->db->_protect_identifiers(implode('_', $key));
					$key = $this->db->_protect_identifiers($key);
				}
				else
				{
					$key_name = $this->db->_protect_identifiers($key);
					$key = array($key_name);
				}
				$sql .= ",\n\tKEY {$key_name} (" . implode(', ', $key) . ")";
			}
		}
		$sql .= "\n) DEFAULT CHARACTER SET {$this->db->char_set} COLLATE {$this->db->dbcollat};";
		return $sql;
	}
	function _drop_table($table)
	{
		return "DROP TABLE IF EXISTS ".$this->db->_escape_identifiers($table);
	}
	function _alter_table($alter_type, $table, $fields, $after_field = '')
	{
		$sql = 'ALTER TABLE '.$this->db->_protect_identifiers($table)." $alter_type ";
		
		if ($alter_type == 'DROP')
		{
			return $sql.$this->db->_protect_identifiers($fields);
		}
		$sql .= $this->_process_fields($fields);
		if ($after_field != '')
		{
			$sql .= ' AFTER ' . $this->db->_protect_identifiers($after_field);
		}
		return $sql;
	}
	function _rename_table($table_name, $new_table_name)
	{
		$sql = 'ALTER TABLE '.$this->db->_protect_identifiers($table_name)." RENAME TO ".$this->db->_protect_identifiers($new_table_name);
		return $sql;
	}
}