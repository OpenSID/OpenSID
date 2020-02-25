<?php class Web_pengunjung_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_pengunjung()
	{
		if (isset($_SESSION['filter']))
		{
			$type = $_SESSION['filter'];
		}
		
		$this->db->select_sum('Jumlah');
		
		switch ($type)
		{
			case 1: 
				$awal = $this->op_tgl('-6 days', date('Y-m-d'));
				$data['lblx'] = "Tanggal";
				$data['judul'] = "DARI TANGGAL ".$awal." SAMPAI ".date('Y-m-d');
				
				$this->db->select('Tanggal');
				$this->db->where('Tanggal >=', $awal);
				$this->db->where('Tanggal <=', date('Y-m-d'));
				$this->db->group_by('Tanggal');
				break; //7 Hari
			case 2: 
				$data['lblx'] = "Tanggal";
				$data['judul'] = "BULAN ".strtoupper(getBulan(date('m')))." ".date('Y');
				
				$this->db->select('Tanggal');
				$this->db->where('MONTH(`Tanggal`) =', date('m'));
				$this->db->group_by('Tanggal');
				break;//1 bulan(tgl 1 sampai akhir bulan)
			case 3: 
				$data['lblx'] = "Bulan";
				$data['judul'] = "TAHUN ".date('Y');

				$this->db->select('MONTH(`Tanggal`) AS Tanggal');
				$this->db->where('YEAR(Tanggal) =', date('Y'));
				$this->db->group_by('MONTH(`Tanggal`)');
				break; //1 tahun / 12 Bulan
			default: 
				$data['lblx'] = "Tahun";
				$data['judul'] = "SETIAP TAHUN";

				$this->db->select('YEAR(`Tanggal`) AS Tanggal');
				$this->db->group_by('YEAR(`Tanggal`)');
				break; //Semua Data
		}
		$this->db->order_by('Tanggal', 'asc');
		$sql = $this->db->get('sys_traffic')->result_array();
		$data ['pengunjung'] = $sql;
		
		foreach($sql as $total):
			$jml = $jml + $total['Jumlah'];
		endforeach;
		
		$data ['Total'] = $jml;
		return $data;
	}
	
	public function get_count($tgl)
	{
		$this->db->select_sum('Jumlah');
		
		switch ($tgl)
		{
			case 1: 
				//null
				break; //Semua Data
			case 2: 
				$this->db->where('MONTH(Tanggal) =', date('m'));
				break; //Data Bulan Ini
			case 3: 
				$this->db->where('YEAR(Tanggal) =', date('Y'));
				break; //Data Tahun Ini
			default: 
				$hari = $this->op_tgl($tgl, date('Y-m-d')); 
				$this->db->where('Tanggal >=', $hari);
				$this->db->where('Tanggal <=', date('Y-m-d'));
				break; //Data Dengan Rentang Jarak
		}
		$sql = $this->db->get('sys_traffic');
		
		$data = $sql->result_array();
		$data = $data [0]['Jumlah'];
		if(empty($data)):
			$data = 0;
		endif;
		return $data;
	}
	
	public function op_tgl($op, $tgl)
	{
		//Membuat Rentang Tgl
		$data = date('Y-m-d', strtotime($op, strtotime($tgl)));
		return $data;
	}	
	
	public function get_config()
	{
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}
}
?>
