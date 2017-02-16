<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_DB_mysqli_utility extends CI_DB_utility {
	function _list_databases()
	{
		return "SHOW DATABASES";
	}
	function _optimize_table($table)
	{
		return "OPTIMIZE TABLE ".$this->db->_escape_identifiers($table);
	}
	function _repair_table($table)
	{
		return "REPAIR TABLE ".$this->db->_escape_identifiers($table);
	}
	function _backup($params = array())
	{
		
		return $this->db->display_error('db_unsuported_feature');
	}
}