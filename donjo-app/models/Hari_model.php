<?php 
//hari_model
class Hari_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	function insert_ignore($params)
	{
		$insert_query = $this->db->insert_string('setting_harimerah', $params);
		$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
		$this->db->query($insert_query);
		return ;
	}
}