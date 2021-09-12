<?php 
//hari_model
class Hadir_model extends CI_Model {
private $table,$table_log;
	public function __construct()
	{
		$this->table='hadir_pamong_hari';
		$this->table_log='hadir_pamong_logs';
		parent::__construct();
	}

	function _get($params=array(),$limit=30,$start=0,$debug=false)
	{
		$this->db->from($this->table);
		if(isset($params['blank']))
		{
			$this->db->where('waktu_masuk is null')
			->where('waktu_keluar is null');
		}
		if(isset($params['keluarKosong']))
		{
			$this->db->where('waktu_keluar is null')
			->where('tanggal <', date("Y-m-d"));;
		}
		if(isset($params['tanggal']))
		{
			$this->db->where('tanggal', $params['tanggal']);
		}
		if(isset($params['now']))
		{
			$this->db->where('tanggal', date("Y-m-d"));
		}
		if(isset($params['pamong']))
		{
			$this->db->where('pamong_id', $params['pamong']);
		}
		
		if(isset($params['count']))
		{
			$this->db->select('count(*) c');
			$selectTable=1;
			$row= $this->db->get()->row_array();
			return $row['c'];
		}
		$this->db->limit($limit, $start);
		
		if(!isset($isSorted))
		{
			$this->db->order_by('id','asc');
		}
		
		if(isset($params['select']))
		{
			$this->db->select($params['select']);
			$selectTable=1;
		}
		if(!isset($selectTable))
		{
			$this->db->select('id,pamong_id,tanggal, waktu_masuk,waktu_keluar');
			//, pamong_info,hadir_logs,lapor_logs
		}
		
		if(isset($params['first']))
		{
			return $this->db->get()->row_array();
		}
		
		return $this->db->get()->result_array();
	}
	
	function _count($params=array())
	{
		$params['count']=1;
		return $this->_get($params);
	}
	
	function _update($params,$where,$cond)
	{
		$this->db->where($where,$cond)->update($this->table, $params);
		return $this->db->last_query();
	}


	function _add($params,$ignore=0)
	{
		$insert_query = $this->db->insert_string($this->table, $params);
		if($ignore)
			$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
		
		$this->db->query($insert_query);
		return ;
	}

	function _blank()
	{
		$params=['blank'=>1];
		return $this->_count($params);
	}
	
	function tidakKeluar()
	{
		$params=['keluarKosong'=>1,'select'=>'id,waktu_masuk,tanggal,pamong_id'];
		$total= $this->_count($params);
		$data= $this->_get($params,$total);
		return ['total'=>$total,'data'=>$data];
	}
}