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
	
	function tgl_by_range($start,$end)
	{
		$this->db->where('tgl_merah >=',$start)
			->where('tgl_merah <=',$end)
			->where('status >=',1)
			->from('setting_harimerah')
			->select('tgl_merah,status');
		$result=$this->db->get()
			->result();
			
		$return=[];//$this->db->last_query(),$start,$end
		foreach($result as $row)
		{
			$return[]=$row->tgl_merah;
		}
	
		return $return;
	}
	
	function _get($params)
	{
		$this->db->from('setting_harimerah');
		
		if(isset($params['tanggal']))
		{
			$this->db->where('tgl_merah',$params['tanggal']);
		}
		
		if(isset($params['first']))
		{
			return $this->db->get()->row_array();
		}
		
		return NULL;
	}
	
	function _update($params)
	{
		$this->db->replace('setting_harimerah',$params);
		
		return ;
	}
}