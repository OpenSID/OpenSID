<?php class Web_pengunjung_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_pengunjung($type)
	{		
		$tgl = date('Y-m-d');
		$bln = date('m');
		$thn = date('Y');
		
		$this->db->select_sum('Jumlah');
		
		switch ($type)
		{
			case 1: 			
				$this->db->select('Tanggal');
				$this->kondisi($type);
				$this->db->group_by('Tanggal');
				
				$data['lblx'] = "Tanggal";
				$data['judul'] = "Hari Ini ( ".tgl_indo2($tgl).")";
				break; //Hari Ini
			case 2: 
				$awal = $this->op_tgl('-1 days', $tgl);
				$this->db->select('Tanggal');
				$this->kondisi($type);
				$this->db->group_by('Tanggal');
				
				$data['lblx'] = "Tanggal";
				$data['judul'] = "Kemarin ( ".tgl_indo2($awal).")";
				break; //Kemarin
			case 3: 
				$awal = $this->op_tgl('-6 days', $tgl);
				$this->db->select('Tanggal');
				$this->kondisi($type);
				$this->db->group_by('Tanggal');
				
				$data['lblx'] = "Tanggal";
				$data['judul'] = "Dari Tanggal ".tgl_indo2($awal)." - ".tgl_indo2($tgl);
				break; //7 Hari (Minggu Ini)
			case 4:			
				$this->db->select('Tanggal');
				$this->kondisi($type);
				$this->db->group_by('Tanggal');
				
				$data['lblx'] = "Tanggal";
				$data['judul'] = "Bulan ".ucwords(getBulan($bln))." ".$thn;
				break;//1 bulan(tgl 1 sampai akhir bulan)
			case 5: 
				$this->db->select('MONTH(`Tanggal`) AS Tanggal');
				$this->kondisi($type);
				$this->db->group_by('MONTH(`Tanggal`)');
				
				$data['lblx'] = "Bulan";
				$data['judul'] = "Tahun ".$thn;
				break; //1 tahun / 12 Bulan
			default: 
				$this->db->select('YEAR(`Tanggal`) AS Tanggal');
				$this->db->group_by('YEAR(`Tanggal`)');
				
				$data['lblx'] = "Tahun";
				$data['judul'] = "Setiap Tahun";
				break; //Semua Data
		}
		$this->db->order_by('Tanggal', 'asc');
		$sql = $this->db->get('sys_traffic')->result_array();
		$data ['pengunjung'] = $sql;
		
		foreach($sql as $total)
		{
			$jml += $total['Jumlah'];
		}
		
		$data ['Total'] = $jml;
		return $data;
	}
	
	public function kondisi($type)
	{
		$tgl = date('Y-m-d');
		$bln = date('m');
		$thn = date('Y');
		
		switch ($type)
		{
			case 1: 
				$this->db->where('Tanggal', $tgl);
				break; //Hari Ini
			case 2: 
				$awal = $this->op_tgl('-1 days', $tgl);
				$this->db->where('Tanggal', $awal);
				break; //Kemarin
			case 3: 
				$awal = $this->op_tgl('-6 days', $tgl);
				$this->db->where('Tanggal >=', $awal);
				$this->db->where('Tanggal <=', $tgl);
				break; //Minggu Ini
			case 4: 
				$this->db->where('MONTH(`Tanggal`) =', $bln);
				$this->db->where('YEAR(Tanggal) =', $thn);
				break; //Bulan Ini
			case 5: 
				$this->db->where('YEAR(Tanggal) =', $thn);
				break; //Tahun Ini
			default: 
				//null
				break; //Semua / Jumlah
		}
	}
	
	public function get_count($type)
	{
		$this->db->select_sum('Jumlah');
		$this->kondisi($type);
		$sql = $this->db->get('sys_traffic');
		
		$data = $sql->result_array();
		$data = $data [0]['Jumlah'];
		if(empty($data))
		{
			$data = 0;
		}
		return $data;
	}
	
	public function op_tgl($op, $tgl)
	{
		//Membuat Rentang Tgl
		$data = date('Y-m-d', strtotime($op, strtotime($tgl)));
		return $data;
	}	
}
?>
