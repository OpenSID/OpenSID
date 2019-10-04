<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register_model extends CI_Model {

	public function check_mail($table,$email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get($table);
		if ($query->num_rows() > 0)
		{
          return true;
        }
        else
        {
          return false;
        }
	}

	public function check_username($table,$username)
	{
		$this->db->where('username', $username);
		$query = $this->db->get($table);
		if ($query->num_rows() > 0)
		{
          return true;
        }
        else
        {
          return false;
        }
	}

	public function insert_cap($table,$data)
	{
		$query = $this->db->insert_string($table, $data);
		return $this->db->query($query);
	}

}

/* End of file Register_moded.php */
/* Location: ./application/models/Register_moded.php */