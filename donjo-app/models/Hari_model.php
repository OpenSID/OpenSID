<?php 
//hari_model
class Hari_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert_ignore($params)
	{
		$insert_query = $this->db->insert_string('setting_harimerah', $params);
		$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
		$this->db->query($insert_query);
		return ;
	}
	
	public function tgl_by_range($start=0,$end=0)
	{
		if ($start == 0)
		{
			$start=date("Y-m-d");
		}
			
		if ($end == 0)
		{
			$end =  date("Y-m-d");
		}

		$this->db->where('tgl_merah >=', $start)
			->where('tgl_merah <=', $end)
			->where('status >=', 1)
			->from('setting_harimerah')
			->select('tgl_merah,status');
			
		$result = $this->db
			->get()
			->result();
			
		$return = [];//$this->db->last_query(),$start,$end
		foreach ($result as $row)
		{
			$return[] = $row->tgl_merah;
		}
	
		return $return;
	}
	
	public function _get($params = array(), $limit = 30, $start = 0, $debug = false)
	{
		$this->db->from('setting_harimerah');
		
		if (isset($params['tanggal']))
		{
			$this->db->where('tgl_merah', $params['tanggal']);
		}
		
		if (isset($params['now']))
		{
			$this->db->where('tgl_merah', date("Y-m-d"));
		}
		
		if (isset($params['active']))
		{
			$this->db->where('status >',0);
		}
		
		if (isset($params['date_range']))
		{
			$this->db
				->where('tgl_merah >=', $params['date_range'][0])
				->where('tgl_merah <=', $params['date_range'][1]);
		}
		
		if (isset($params['datatable_search']))
		{
			$this->db->where('tgl_merah like "%'.$params['datatable_search'].'%"')
			->or_where('detail like "%'.$params['datatable_search'].'%"');
		}
		
		if(isset($params['count']))
		{
			$this->db->select('count(*) c');
			
			$row= $this->db->get()->row_array();
			return $row['c'];
		}
		
		$this->db->limit($limit, $start);
		if(!isset($isSorted))
		{
			$this->db->order_by('tgl_merah','asc');
		}
		
		if(isset($params['first']))
		{
			return $this->db->get()->row_array();
		}
		
		return $this->db->get()->result_array();
	}
	
	public function _count($params=array())
	{
		$params['count'] = 1;
		return $this->_get($params);
	}
	
	public function _update($params = array() )
	{
		$tgl_merah = $params['tgl_merah'];
		unset($params['tgl_merah']);
		$this->db->where('tgl_merah', $tgl_merah)
		->update('setting_harimerah', $params);
		return $this->db->last_query();
	}
}