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
		
		if($type == ''){//Semua Data
			$_SESSION['lblx'] = "Tahun";
			$_SESSION['judul'] = "SETIAP TAHUN";
			$parm1 = "YEAR(`Tanggal`) AS `tgl`, ";
		}else if($type == '1'){//Data 7 Hari Terakhir (Format Y-m-d), Hari ini dan 6 hari kebelakang
			$awal = $this->op_tgl('-6 days', date('Y-m-d'));
			$_SESSION['lblx'] = "Tanggal";
			$_SESSION['judul'] = "MINGGU INI";
			$parm1 = "`Tanggal` AS `tgl`, ";
			$parm2 = "WHERE `Tanggal` BETWEEN '".$awal."' AND '".date('Y-m-d')."'";//7 hari
		}else if($type == '2'){//Data Bulan Ini (m), Mulai Tgl 1 sampai Akhir
			$_SESSION['lblx'] = "Tanggal";
			$_SESSION['judul'] = "BULAN ".strtoupper(getBulan(date('m')));
			$parm1 = "`Tanggal` AS `tgl`, ";
			$parm2 = "WHERE MONTH(`Tanggal`) = '".date('m')."'";//7 hari
		}else if($type == '3'){//Data Tahun Ini (Y), Mulai bulan 1 sampai 12
			$_SESSION['lblx'] = "Bulan";
			$_SESSION['judul'] = "TAHUN ".date('Y');
			$parm1 = "MONTH(`Tanggal`) AS `tgl`, ";
			$parm2 = "WHERE YEAR(`Tanggal`) = '".date('Y')."'";//12 Bulan
		}else{
			//$_SESSION['lblx'] = "Rentang";
			//$parm1 = "`Tanggal` AS `tgl`, ";
			//$parm2 = "WHERE `Tanggal` BETWEEN '".$awal."' AND '".date('Y-m-d')."'";//Dari Tanggal Sampai Tanggal
		}
		$query = "SELECT ".$parm1." SUM(`Jumlah`) AS `Total` FROM sys_traffic ".$parm2." GROUP BY `tgl` ORDER BY `Tanggal` ASC ";
		$data = $this->db->query($query)->result_array();
		return $data;
	}
	
	public function get_count($tgl)
	{
		if($tgl == 1){//Semua Data
			$parm = "";
		}else if($tgl == 2){//Data Bulan Ini
			$parm = "WHERE MONTH(`Tanggal`) = '".date('m')."'";
		}else if($tgl == 3){//Data Tahun Ini
			$parm = "WHERE YEAR(`Tanggal`) = '".date('Y')."'";
		}else{//Data Dengan Rentang Jarak
			$hari = $this->op_tgl($tgl, date('Y-m-d'));
			$parm = "WHERE `Tanggal` BETWEEN '".$hari."' AND '".date('Y-m-d')."'";
		}
		$query = "SELECT SUM(`Jumlah`) AS `Total` FROM sys_traffic ".$parm;
		$data = $this->db->query($query)->result_array();
		$data = $data [0]['Total'];
		return $data;
	}
	
	public function op_tgl($op, $tgl)
	{
		$data = date('Y-m-d', strtotime($op, strtotime($tgl)));
		return $data;
	}	
}
?>
