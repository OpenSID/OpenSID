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
		
		switch ($type)
		{
			case 1: 
				$awal = $this->op_tgl('-6 days', date('Y-m-d'));
				$data['lblx'] = "Tanggal";
				$data['judul'] = "DARI TANGGAL ".$awal." SAMPAI ".date('Y-m-d');
				$parm1 = "`Tanggal` AS `tgl`, ";
				$parm2 = "WHERE `Tanggal` BETWEEN '".$awal."' AND '".date('Y-m-d')."'";//7 hari
				break; //Data Bulan Ini
			case 2: 
				$data['lblx'] = "Tanggal";
				$data['judul'] = "BULAN ".strtoupper(getBulan(date('m')))." ".date('Y');
				$parm1 = "`Tanggal` AS `tgl`, ";
				$parm2 = "WHERE MONTH(`Tanggal`) = '".date('m')."'";//7 harib
				break;//Data Tahun Ini
			case 3: 
				$data['lblx'] = "Bulan";
				$data['judul'] = "TAHUN ".date('Y');
				$parm1 = "MONTH(`Tanggal`) AS `tgl`, ";
				$parm2 = "WHERE YEAR(`Tanggal`) = '".date('Y')."'";//12 Bulan
				break; //Semua Data
			default: 
				$data['lblx'] = "Tahun";
				$data['judul'] = "SETIAP TAHUN";
				$parm1 = "YEAR(`Tanggal`) AS `tgl`, ";
				break; //Semua Data
					
			//case untuk rentang tgl
				//$data['lblx'] = "Rentang";
				//$parm1 = "`Tanggal` AS `tgl`, ";
				//$parm2 = "WHERE `Tanggal` BETWEEN '".$awal."' AND '".date('Y-m-d')."'";//Dari Tanggal Sampai Tanggal
		}
		
		$query = "SELECT ".$parm1." SUM(`Jumlah`) AS `Total` FROM sys_traffic ".$parm2." GROUP BY `tgl` ORDER BY `Tanggal` ASC ";
		$data ['pengunjung'] = $this->db->query($query)->result_array();
		return $data;
	}
	
	public function get_count($tgl)
	{
		switch ($tgl)
		{
			case 1: 
				$parm = ""; 
				break; //Semua Data
			case 2: 
				$parm = "WHERE MONTH(`Tanggal`) = '".date('m')."'"; 
				break; //Data Bulan Ini
			case 3: 
				$parm = "WHERE YEAR(`Tanggal`) = '".date('Y')."'"; 
				break; //Data Tahun Ini
			default: 
				$hari = $this->op_tgl($tgl, date('Y-m-d')); 
				$parm = "WHERE `Tanggal` BETWEEN '".$hari."' AND '".date('Y-m-d')."'"; 
				break; //Data Dengan Rentang Jarak
		}

		$query = "SELECT SUM(`Jumlah`) AS `Total` FROM sys_traffic ".$parm;
		$data = $this->db->query($query)->result_array();
		$data = $data [0]['Total'];
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
