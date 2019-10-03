<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_groups extends CI_Model 
{

	public function check_group($table,$group_name)
	{
		$this->db->where('name', $group_name);
		$query = $this->db->get($table);
		if ($query->num_rows() > 0)
		{
          return TRUE;
        }
        else
        {
          return FALSE;
        }
	}
	public function update($id,$data,$table)
	{
		if (empty($id)) return FALSE;
		$this->db->where($id);
		$this->db->update($table,$data);
		return TRUE;
	}
        
}

/* End of file Users_groups.php */
/* Location: ./application/models/Users_groups.php */
