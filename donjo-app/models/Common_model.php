<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Auther : Salman Iqbal
* IDE    : Sublime
* Date   : 2/5/2017
*/
class Common_model extends CI_Model
{
	public function add($table,$data)
	{
		$query = $this->db->insert($table,$data);
		return TRUE;
	}

	public function select($table)
	{
		$query = $this->db->get($table);
		return $query->result();
	}

	public function update_data($id,$table)
	{
		$this->db->where($id);
		$query = $this->db->get($table);
		return $query->row();
	}

	public function update($id,$data,$table)
	{
		if (empty($id)) return FALSE;

		$this->db->update($table, $data, array('id' => $id));

		return TRUE;
	}
	function UpdateDB($table,$Where,$Data)
	{
		$this->db->where($Where);
		$Update = $this->db->update($table,$Data);
		if ($Update):
			return true;
		else:
			return false;
		endif;
	}

	public function delete($id,$table)
	{
		$this->db->where($id);
		$this->db->delete($table);
		return TRUE;
	}
	function getAllData($table,$specific='',$row='',$Where='',$order='',$limit='',$groupBy='',$like = '')
	{
		// If Condition
		if (!empty($Where)):
			$this->db->where($Where);
		endif;
		// If Specific Columns are require
		if (!empty($specific)):
			$this->db->select($specific);
		else:
			$this->db->select('*');
		endif;

		if (!empty($groupBy)):
			$this->db->group_by($groupBy);
		endif;
		// if Order
		if (!empty($order)):
			$this->db->order_by($order);
		endif;
		// if limit
		if (!empty($limit)):
			$this->db->limit($limit);
		endif;

		//if like
		if(!empty($like)):
			$this->db->like($like);
		endif;	
		// get Data
		
		//if select row
		if(!empty($row)):
			$GetData = $this->db->get($table);
			return $GetData->row();
		else:
			$GetData = $this->db->get($table);
			return $GetData->result();
		endif;	
	}
}