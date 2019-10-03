<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users_modal extends CI_Model 
{
	// get All Users
	public function all_users()
	{
		$this->db->order_by('id','desc');
		$this->db->limit(5);
		$query = $this->db->get('users');
		return $query->result();
	}

	//Count Users
	public function count_users()
	{
		$this->db->select('*');
		$this->db->from('users');
		return $this->db->count_all_results();
	}

	public function recent_users()
	{
		$this->db->where('date', date('Y-m-d'));
		$query = $this->db->get('users');
		return $count = $query->num_rows();
	}

	public function weekly_data()
	{
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('DATE > DATE_SUB(NOW(), INTERVAL 1 WEEK)');
		return $this->db->count_all_results();
	}

	public function get_user_privileges($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.perm_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

       public function get_user_privileges_create($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.create_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

        public function get_user_privileges_update($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.update_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

        public function get_user_privileges_delete($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.delete_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

        public function get_user_privileges_print($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.print_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}


              
	public function remove_from_privileges($privilege_ids=false, $group_id=false)
	{
		// group id is required
		if(empty($group_id))
		{
			return FALSE;
		}

		// if privilege id(s) are passed remove privilege from the group(s)
		
		if(!is_array($privilege_ids))
		{
			$privilege_ids = array($privilege_ids);
		}

		foreach($privilege_ids as $privilege_id)
		{
			$this->db->select('*')
					 ->from('group_perm')
					 ->join('groups', "groups.id = group_perm.group_id")
					 ->join('setting_modul', "setting_modul.id = group_perm.perm_id")
					 ->where('group_perm.group_id',$group_id);
					 //->where('group_perm.perm_id',$privilege_id);
			$this->db->delete();		 	
		}

		return TRUE;
	}


        public function get_group_users($group)
	{
		$this->db->select('email')
		         ->from('groups')
		         ->join('users_groups','groups.id = users_groups.group_id')
		         ->join('users','users.id = users_groups.user_id')
		         ->where('groups.id',$group);
		$query = $this->db->get();
		return $query->result();
	}


        function list_data_main()
	{
		$sql = "SELECT u.* FROM setting_modul u WHERE parent = 0 ";
                $sql .= ' ORDER BY urut';
		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['submodul'] = $this->list_sub_modul($data[$i]['id']);
		}
		return $data;
	}


       function list_data_sub()
	{
		$sql = "SELECT u.* FROM setting_modul u WHERE parent <> 0 ";
                $sql .= ' ORDER BY urut';
		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['submodul'] = $this->list_sub_modul($data[$i]['id']);
		}
		return $data;
	}


       public function list_sub_modul($modul_id=1)
	{
		$data	= $this->db->select('*')->where('parent', $modul_id)->order_by('urut')->get('setting_modul')->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['modul'] = str_ireplace('[desa]', ucwords($this->setting->sebutan_desa), $data[$i]['modul']);
		}
		return $data;
	}

       
        

}

/* End of file Users_modal.php */
/* Location: ./application/models/Users_modal.php */
